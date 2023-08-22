<?php

namespace App\DailyReportCrawler;

use GuzzleHttp\Client;

class PostCrawler
{
    // data 같은애들 이름 다시 짓기 (어떤 데이터인지 명확하지 않음) , getbody 예외처리만들기 (response가 200일 때만 실행되도록)
    function getPostHTML($postUrl, $postBodyData): string
    {
        //composer autoload 파일 불러오기
        require 'vendor/autoload.php';

        $client = new Client();

        try {
            // Guzzle을 사용해서 POST 요청 보내기
            $response = $client->post($postUrl, [
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

    function setPostBodyData()
    {
        // 오늘 날짜, 12일 전의 날짜 생성
        $sndDtm = date("Ymd");
        $vlidStDtm = date("Ymd", strtotime("-12 days"));

        $postBodyData = '{
            "header":
                {"guidSeq":1,
                "trxCd":"OSUUA02R01",
                "scrId":"IECOSPCM02",
                "sysCd":"03",
                "fstChnCd":"WEB",
                "langDvsnCd":"KO",
                "envDvsnCd":"D",
                "sndRspnDvsnCd":"S",
                "sndDtm":"' . $sndDtm . '",
                "ipAddr":"210.221.90.133",
                "usrId":"IECOSPC",
                "pageNum":1,"pageCnt":1000},
            "data":
                {"statSrchDsList":
                    [
                    {"dsItmVal1":"010200000",
                        "dsItmId1":"ACC_ITEM",
                        "dsItmValNm1":"국고채(3년)",
                        "dsItmValEngNm1":"Treasury Bonds (3-year)",
                        "dsItmGrpId1":"G11950","dsId":"817Y002"},
                    {"dsItmVal1":"010210000",
                        "dsItmId1":"ACC_ITEM",
                        "dsItmValNm1":"국고채(10년)",
                        "dsItmValEngNm1":"Treasury Bonds (10-year)",
                        "dsItmGrpId1":"G11950",
                        "dsId":"817Y002"},
                    {"dsItmVal1":"010502000",
                        "dsItmId1":"ACC_ITEM",
                        "dsItmValNm1":"CD(91일)",
                        "dsItmValEngNm1":"CD (91-day)",
                        "dsItmGrpId1":"G11950",
                        "dsId":"817Y002"}],
                "statSrchFreqList":
                    [{"freq":"D",
                        "vlidStDtm":"' . $vlidStDtm . '",
                        "vlidEndDtm":"' . $sndDtm . '"}],
                "statTyp":"E",
                "statDataCvsnCdList":["00"],
                "viewType":"01",
                "holidayYn":"Y"}
            }';
        return $postBodyData;
    }

    function showResult($extractedData){
        foreach ($extractedData as $data) {
            foreach ($data as $key => $value) {
                echo "$key: $value<br>";
            }
        }
    }
}

