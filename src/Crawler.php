<?php

namespace App\DailyReportCrawler;

use DOMDocument;
use DOMXPath;
use Symfony\Component\CssSelector\CssSelectorConverter;
use GuzzleHttp\Client;

class Crawler
{

    public CssSelectorConverter $converter;
    public Client $client;

    // 생성자에서 CssSelectorConverter 객체도 함께 생성되어 멤버변수로 저장됨
    function __construct()
    {
        $this->converter = new CssSelectorConverter();
        $this->client = new Client();
    }

    function saveHtml($url, $stock)
    {
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

        $file_path = $stock . '.html';
        file_put_contents($file_path, $html);
    }

    function checkPath($url, $cssPath)
    {
        $html = file_get_contents($url . '.html');

        $html = str_replace(array("\t", "\n"), '', $html);

        $cssSelectors = explode(';', $cssPath);

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);

        $selectedValues = array();

        foreach ($cssSelectors as $cssSelector) {
            $xpathSelector = $this->converter->toXPath(trim($cssSelector));
            $selectedElements = $xpath->query($xpathSelector);

            if ($selectedElements->length > 0) {
                $selectedValues[] = trim($selectedElements[0]->nodeValue);
            } else {
                $selectedValues[] = "Not found";
            }
        }

        return $selectedValues;
    }

    // HTML 문서 가져오는 함수
    // 가급적 composer를 활용해서 HTTP 패키지를 사용할 것
    function getHtml($url): string
    {

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

        //$html 앞 부분에 <script></script> 부분이 붙어 있을 때 에러가 발생하는 경우가 있어서 <html 부분부터 잘라냄
        $html = '<html' . explode("<html", $html)[1];

        //<!DOCTYPE html>가 없을 때 에러가 발생하는 경우가 있어서 앞에 붙여줌
        if (strpos($html, "<!DOCTYPE") == false) {
            $html = '<!DOCTYPE html>' . $html;
        }

        return $html;
    }

    function findElementsBySelector($url, $cssSelector)
    {

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

    function getPost($postUrl, $postBodyData){
        try {
            // Guzzle을 사용해서 POST 요청 보내기
            $response = $this->client->post($postUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'body' => $postBodyData,
            ]);

            if ($response->getStatusCode() !== 200) {
                // HTTP 에러 처리
                $errorMessage = "HTTP Error: " . $response->getStatusCode();
                // 예를 들어, 에러를 띄우거나 로그에 기록하는 등의 처리를 할 수 있습니다.
                echo $errorMessage;
                return ''; // 에러 발생 시 빈 문자열 반환
            } else {
                // 200인 경우에만 postHtml에 저장하고 반환
                $postHtml = $response->getBody()->getContents();
                return $postHtml;
            }
        } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return '';
        }

    }

    function extractData($postHtml): array
    {
        $responseArray = json_decode($postHtml, true);
        // echo '<hr>';
        // echo gettype($responseArray);

        if (isset($responseArray['data']['jsonCtnt'])) {  //array key exsist -> isset 의 경우 배열의 키가 없으면 에러가 뜬다
            $jsonCtntArray = json_decode($responseArray['data']['jsonCtnt'], true);
            $extractedData = [];

            // echo '<hr>';
            // echo $jsonCtntArray;
            // echo '<hr>';
            // echo gettype($jsonCtntArray);

            foreach ($jsonCtntArray as $item) {
                // dump($item);
                $keys = array_keys($item);
                $startIndex = array_search('Wgt', $keys) + 1;
                $endIndex = array_search('변환', $keys);
                // dump($keys);

                return array($keys[$startIndex],$item[$keys[$startIndex]]);

                $extractedItem = [];
                $title = $item['계정항목']; // 계정항목 값 추출
                $extractedItem['계정항목'] = $title;
                for ($i = $startIndex; $i < $endIndex; $i++) {
                    $key = $keys[$i];
                    // echo "key = $key<br>";
                    $value = $item[$key];
                    // echo "value = $value<br>";
                    $extractedItem[$key] = $value;
                    // $extractedItemString = implode(', ', $extractedItem);
                    // echo "extractedItem = $extractedItemString<br>";
                }

                $extractedData[] = $extractedItem;
            }

            // return $extractedData;
        } else {
            echo '올바른 데이터를 불러오지 못 했습니다.';
            exit();
        }
    }

}
