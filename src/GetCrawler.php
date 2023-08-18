<?php

namespace App\DailyReportCrawler;

use DOMDocument;
use DOMXPath;
use Symfony\Component\CssSelector\CssSelectorConverter;

class GetCrawler {

    public CssSelectorConverter $converter;

    // 생성자에서 CssSelectorConverter 객체도 함께 생성되어 멤버변수로 저장됨
    function __construct() {
        $this->converter = new CssSelectorConverter();
    }

    // HTML 문서 가져오는 함수
    // 가급적 composer를 활용해서 HTTP 패키지를 사용할 것
    function getHtml($url) : string {

        // todo : 잘못된 url 입력 시 예외 처리

        $options = array(
            'http' => array( // 데이터가 추출되지 않는 경우, User-Agent가 설정되어 있는지를 확인해야 함
                'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36",  // User-Agent 헤더 설정
                'method' => 'GET',  // HTTP 메소드 설정 (GET, POST 등)
            )
        );
        
        $context = stream_context_create($options);
        
        $html = file_get_contents($url, false, $context);

        // 인코딩 변환 : EUC-KR을 UTF-8로
        $html = iconv("EUC-KR", "UTF-8", $html);

        // $html 앞 부분에 <script></script> 부분이 붙어 있을 때 에러가 발생하는 경우가 있어서 <html 부분부터 잘라냄
        $html = '<html' . explode("<html", $html)[1];

        // <!DOCTYPE html>가 없을 때 에러가 발생하는 경우가 있어서 앞에 붙여줌
        if (strpos($html, "<!DOCTYPE") == false) {
            $html = '<!DOCTYPE html>' . $html;
        }

        return $html;
    }

    // 특정 id/class의 text 가져오기
    // $converter : CssSelectorConverter 객체
    // $url
    // $cssSelector 예시 : '#KOSPI_now'
    function findElementsBySelector($url, $cssSelector) {
        
        $html = $this->getHtml($url);

        // 변환된 CSS 선택자 사용
        $xpathSelector = $this->converter->toXPath($cssSelector);
        
        $dom = new DOMDocument();
        // 에러 무시 
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);

        // 변환된 XPath 선택자를 사용하여 요소 선택
        $selectedElements = $xpath->query($xpathSelector);

        return $selectedElements;
    }

    function getHtmlWithCurl($url) : string {

        // todo : 잘못된 url 입력 시 예외 처리

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $html = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($html === false) {
            $error = curl_error($ch); // 오류 메시지 가져오기
            echo "CURL Error: " . $error;
        } elseif ($status_code !== 200) {
            echo "HTTP Error: " . $status_code;
        } else {
            // 성공적으로 데이터를 가져온 경우
        }

        curl_close($ch);

        // 인코딩 변환 : EUC-KR을 UTF-8로
        // $html = iconv("EUC-KR", "UTF-8", $html);

        // $html 앞 부분에 <script></script> 부분이 붙어 있을 때 에러가 발생하는 경우가 있어서 <html 부분부터 잘라냄
        // <!DOCTYPE html>가 없을 때 에러가 발생하는 경우가 있어서 앞에 붙여줌
        // $html = '<!DOCTYPE html>' . '<html' . explode("<html", $html)[1];
        // $tableStart = strpos($html, '<table');
        // $tableEnd = strpos($html, '</table>', $tableStart);

        // if ($tableStart !== false && $tableEnd !== false) {
        //     $html = substr($html, $tableStart, $tableEnd - $tableStart + 8);
        //     // echo $tableContent;
        // } else {
        //     echo "table content not found.";
        // }
        
        return $html;
    }

    function findElementsBySelectorWithCurl($url, $cssSelector){

        $html = $this->getHtmlWithCurl($url);

        // echo $html;

        // 변환된 CSS 선택자 사용
        $xpathSelector = $this->converter->toXPath($cssSelector);
        
        $dom = new DOMDocument();
        // 에러 무시 
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);

        // 변환된 XPath 선택자를 사용하여 요소 선택
        $selectedElements = $xpath->query($xpathSelector);

        return $selectedElements;
    }


    // todo : showResult라는 멤버함수 추가로 선언
    // findElementsBySelector 멤버함수가 반환한 값이 배열인지 아닌지를 확인하고
    // findElementsBySelector는 항상 DOMNodeList를 반환함
    // 따라서, DOMNodeList의 길이가 1이면 단일 값, 1보다 크면 여러 요소 포함

    function showResult($dataName, $selectedElements) {

        // 요소가 하나라면
        if ($selectedElements->length == 1) {
            echo "{$dataName} : {$selectedElements[0]->textContent} <br>";

        // 요소가 없다면
        } else if ($selectedElements->length == 0) {
            echo "{$dataName} : 값이 추출되지 않았습니다. <br>";

        // 요소가 여러 개라면
        } else {
            echo $dataName . " : ";
            foreach ($selectedElements as $el) {
                echo $el->textContent . "<br>";
            }
        }
    }


    /**
     * 일별 시세 테이블의 첫 번째 행에 오늘 날짜가 나오는 경우도 있기 때문에
     * 해당 행의 날짜가 오늘 날짜와 같은지 다른지를 먼저 확인해야 함
     * 오늘 날짜와 targetDate가 같다면 인자의 index + 1인 값을 return하는 함수
     * @param format : string / 날짜 형식 ex) Y.m.d
     * @param targetDateSelector : string / 오늘 날짜와 비교할 날짜가 담긴 cssSelector
     * @param index : int / 대상 인덱스
     * @return resultIndex : int
     */

    function checkDate($format, $url, $targetDateSelector, $index) {
        $today = date($format);

        $targetDate = $this->findElementsBySelector($url, $targetDateSelector);

        if ($targetDate->length != 0) {
            if ($today == $targetDate[0]->textContent) {
                $index++;
            }
        }
 
        return $index;
    }


}