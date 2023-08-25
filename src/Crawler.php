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

    function saveHtml($url)
    {

        $ch = curl_init();

        $cookies = "cookie_name=__cf_bm=YOkI4vI54ACrAszcYRzDGSdXb2JExN3CTF5qNGhUjtU-1692865467-0-AcBAKTeizLM0LePZPCxCkO9V2FVhEO3jzBPYDvqfdEuqXFZJwdsDnP12PWv69R7A76vofAa70lyQc15i+AMdIi0=; gcc=KR; gsc=11; leaderboard_variant=0; ov_page_variant=1; smd=ffe1f74e8f727825b979efa8c0e98899-1692865467; udid=ffe1f74e8f727825b979efa8c0e98899; __cflb=02DiuF9qvuxBvFEb2q9Qemd3EPFFTD8S9GYyB6TobU5sA";
        $headers = array(
            "Cookie: " . $cookies,
        );

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $html = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($html === false) {
            $error = curl_error($ch); // 오류 메시지 가져오기
            echo "CURL Error: " . $error;
        } elseif ($status_code !== 200) {
            echo "HTTP Error: " . $status_code;
        } else {
            // 성공적으로 데이터를 가져온 경우
            return $html;
        }

        // curl_close($ch);

        // $file_path = $stock . '.html';
        // file_put_contents($file_path, $html);
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
        // $html = '<html' . explode("<html", $html)[1];

        //<!DOCTYPE html>가 없을 때 에러가 발생하는 경우가 있어서 앞에 붙여줌
        // if (strpos($html, "<!DOCTYPE") == false) {
        //     $html = '<!DOCTYPE html>' . $html;
        // }

        return $html;
    }

    function checkPath($url, $cssPath)
    {
        $html = $this->getHtml($url);

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

    // 이전것
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

    function getPost($postUrl, $postBodyData)
    {
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
                $keys = array_keys($item);
                $startIndex = array_search('Wgt', $keys) + 1;
                $endIndex = array_search('변환', $keys);

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

            return $extractedData;
        } else {
            echo '올바른 데이터를 불러오지 못 했습니다.';
            exit();
        }
    }



    function checkPathCurl($url, $cssPath)
    {
        $html = $this->saveHtml($url);

        $html = str_replace(array("\t", "\n"), '', $html);

        $cssSelectors = explode(';', $cssPath);

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);

        foreach ($cssSelectors as $cssSelector) {
            $xpathSelector = $this->converter->toXPath(trim($cssSelector));
            $selectedElements = $xpath->query($xpathSelector);

            if ($selectedElements->length > 0) {
                $selectedValue = trim($selectedElements[0]->nodeValue);
            } else {
                $selectedValue = "Not found";
            }
        }

        return $selectedValue;
    }
}
