<?php

namespace App\DailyReportCrawler;

use App\DailyReportCrawler\Crawler;

class CrawlData
{
    private $crawler;
    private $dataURL;
    private $dataPath;
    private $postBodyData;
    private $sndDtm;
    private $vlidStDtm;

    public function __construct()
    {
        $this->crawler = new Crawler();
        $this->dataURL =
            '
        {
            "1":"https://finance.naver.com/sise/sise_index_day.naver?code=KOSPI",
            "2":"https://finance.naver.com/sise/sise_index_day.naver?code=KOSDAQ",
            "3":"https://finance.naver.com/world/sise.naver?symbol=DJI@DJI",
            "4":"https://finance.naver.com/world/sise.naver?symbol=NAS@IXIC",
            "5":"https://finance.naver.com/world/sise.naver?symbol=SHS@000001",
            "6":"https://finance.naver.com/item/sise_day.naver?code=408920",
            "7":"https://finance.naver.com/item/sise_day.naver?code=080010",
            "8":"https://finance.naver.com/item/sise_day.naver?code=032560",
            "9":"https://finance.naver.com/item/sise_day.naver?code=263770",
            "10":"https://finance.naver.com/item/sise_day.naver?code=456700",
            "11":"https://ecos.bok.or.kr/serviceEndpoint/httpService/request.json",
            "14":"https://kr.investing.com/rates-bonds/u.s.-2-year-bond-yield",
            "15":"https://kr.investing.com/rates-bonds/u.s.-10-year-bond-yield",
            "16":"https://finance.naver.com/marketindex/exchangeDailyQuote.naver?marketindexCd=FX_USDKRW",
            "17":"https://finance.naver.com/marketindex/worldDailyQuote.naver?fdtc=4&marketindexCd=FX_EURUSD",
            "18":"https://finance.naver.com/marketindex/worldDailyQuote.naver?fdtc=4&marketindexCd=FX_USDCNY",
            "19":"https://finance.naver.com/marketindex/exchangeDailyQuote.naver?marketindexCd=FX_JPYKRW",
            "20":"https://finance.naver.com/marketindex/worldDailyQuote.naver?marketindexCd=OIL_CL&fdtc=2",
            "21":"https://finance.naver.com/marketindex/worldDailyQuote.naver?marketindexCd=CMDT_GC&fdtc=2",
            "22":"https://finance.naver.com/marketindex/worldDailyQuote.naver?fdtc=2&marketindexCd=CMDT_NDY&page=1"
        }
        ';
        $this->dataPath =
            '
        {
            "1":"td.date;td.number_1",
            "2":"td.date;td.number_1",
            "3":"#dayTable > tbody > tr:nth-child(1) > td.tb_td;#dayTable > tbody > tr:nth-child(1) > td.tb_td2 > span",
            "4":"#dayTable > tbody > tr:nth-child(1) > td.tb_td;#dayTable > tbody > tr:nth-child(1) > td.tb_td2 > span",
            "5":"#dayTable > tbody > tr:nth-child(1) > td.tb_td;#dayTable > tbody > tr:nth-child(1) > td.tb_td2 > span",
            "6":"body > table.type2 > tr:nth-child(3) > td:nth-child(1) > span;body > table.type2 > tr:nth-child(3) > td:nth-child(2) > span",
            "7":"body > table.type2 > tr:nth-child(3) > td:nth-child(1) > span;body > table.type2 > tr:nth-child(3) > td:nth-child(2) > span",
            "8":"body > table.type2 > tr:nth-child(3) > td:nth-child(1) > span;body > table.type2 > tr:nth-child(3) > td:nth-child(2) > span",
            "9":"body > table.type2 > tr:nth-child(3) > td:nth-child(1) > span;body > table.type2 > tr:nth-child(3) > td:nth-child(2) > span",
            "10":"body > table.type2 > tr:nth-child(3) > td:nth-child(1) > span;body > table.type2 > tr:nth-child(3) > td:nth-child(2) > span",
            "11":"postBodyData",
            "14":"dl > div:nth-child(1) > dd > span > span:nth-child(2)",
            "15":"dl > div:nth-child(1) > dd > span > span:nth-child(2)",
            "16":"body > div > table > tbody > tr:nth-child(1) > td.date;body > div > table > tbody > tr:nth-child(1) > td:nth-child(2)",
            "17":"body > div > table > tbody > tr:nth-child(1) > td.date;body > div > table > tbody > tr:nth-child(1) > td:nth-child(2)",
            "18":"body > div > table > tbody > tr:nth-child(1) > td.date;body > div > table > tbody > tr:nth-child(1) > td:nth-child(2)",
            "19":"body > div > table > tbody > tr:nth-child(1) > td.date;body > div > table > tbody > tr:nth-child(1) > td:nth-child(2)",
            "20":"body > div > table > tbody > tr:nth-child(1) > td.date;body > div > table > tbody > tr:nth-child(1) > td:nth-child(2)",
            "21":"body > div > table > tbody > tr:nth-child(1) > td.date;body > div > table > tbody > tr:nth-child(1) > td:nth-child(2)",
            "22":"body > div > table > tbody > tr:nth-child(1) > td.date;body > div > table > tbody > tr:nth-child(1) > td:nth-child(2)"
        }
        ';
        $this->sndDtm = date("Ymd");
        $this->vlidStDtm = date("Ymd", strtotime("-12 days"));
        $this->postBodyData = '{
            "header":
                {"guidSeq":1,
                "trxCd":"OSUUA02R01",
                "scrId":"IECOSPCM02",
                "sysCd":"03",
                "fstChnCd":"WEB",
                "langDvsnCd":"KO",
                "envDvsnCd":"D",
                "sndRspnDvsnCd":"S",
                "sndDtm":"' . $this->sndDtm . '",
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
                        "vlidStDtm":"' . $this->vlidStDtm . '",
                        "vlidEndDtm":"' . $this->sndDtm . '"}],
                "statTyp":"E",
                "statDataCvsnCdList":["00"],
                "viewType":"01",
                "holidayYn":"Y"}
            }
        ';
    }

    function crawldata()
    {
        // $resultArray=[];
        // 코스피 현재
        // $kospiNow = $this->crawler->findElementsBySelector("https://finance.naver.com/sise/", "#KOSPI_now");
        //$this->crawler->showResult("코스피 현재", $kospiNow);
        // $resultArray[] = $kospiNow[0]->textContent;

        // 코스닥 현재
        // $kosdaqNow = $this->crawler->findElementsBySelector("https://finance.naver.com/sise/", "#KOSDAQ_now");
        //$this->crawler->showResult("코스닥 현재", $kosdaqNow);
        // $resultArray[] = $kosdaqNow[0]->textContent;

        // 코스피 체결가
        // $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/sise/sise_index_day.naver?code=KOSPI", "tr:nth-of-type(3) > .date", 3);
        // $kospiValue = $this->crawler->findElementsBySelector("https://finance.naver.com/sise/sise_index_day.naver?code=KOSPI", "tr:nth-of-type({$targetIndex}) > td:nth-child(2)");
        //$this->crawler->showResult("코스피 체결가", $kospiValue);
        // $resultArray[] = $kospiValue[0]->textContent;
        // $this->crawler->saveHtml('https://finance.naver.com/sise/sise_index_day.naver?code=KOSPI','1'); 

        // 코스닥 체결가 
        // $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/sise/sise_index_day.naver?code=KOSDAQ", "tr:nth-of-type(3) > .date", 3);
        // $kosdaqValue = $this->crawler->findElementsBySelector("https://finance.naver.com/sise/sise_index_day.naver?code=KOSDAQ", "tr:nth-of-type({$targetIndex}) > td:nth-child(2)");
        //$this->crawler->showResult("코스닥 체결가", $kosdaqValue);
        // $resultArray[] = $kosdaqValue[0]->textContent;
        // $this->crawler->saveHtml('https://finance.naver.com/sise/sise_index_day.naver?code=KOSDAQ','2');

        // 다우 산업 종합
        // $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/world/sise.naver?symbol=DJI@DJI", "#dayTable > tbody > tr:nth-child(1) > td.tb_td", 1);
        // $dauValue = $this->crawler->findElementsBySelector("https://finance.naver.com/world/sise.naver?symbol=DJI@DJI", "#dayTable > tbody > tr:nth-child({$targetIndex}) > td.tb_td2 > span");
        //$this->crawler->showResult("다우 산업 종가", $dauValue);
        // $resultArray[] = $dauValue[0]->textContent;
        //$this->crawler->saveHtml('https://finance.naver.com/world/sise.naver?symbol=DJI@DJI','3');

        // 나스닥 종합
        // $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/world/sise.naver?symbol=NAS@IXIC", "#dayTable > tbody > tr:nth-child(1) > td.tb_td", 1);
        // $nasValue = $this->crawler->findElementsBySelector("https://finance.naver.com/world/sise.naver?symbol=NAS@IXIC", "#dayTable > tbody > tr:nth-child({$targetIndex}) > td.tb_td2 > span");
        //$this->crawler->showResult("나스닥 종가", $nasValue);
        // $resultArray[] = $nasValue[0]->textContent;
        //$this->crawler->saveHtml('https://finance.naver.com/world/sise.naver?symbol=NAS@IXIC','4');

        // 상해 종합
        // $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/world/sise.naver?symbol=SHS@000001", "#dayTable > tbody > tr:nth-child(1) > td:nth-child(1)", 1);
        // $shsValue = $this->crawler->findElementsBySelector("https://finance.naver.com/world/sise.naver?symbol=SHS@000001", "#dayTable > tbody > tr:nth-child({$targetIndex}) > td.tb_td2 > span");
        //$this->crawler->showResult("상해 종가", $shsValue);
        // $resultArray[] = $shsValue[0]->textContent;
        //$this->crawler->saveHtml('https://finance.naver.com/world/sise.naver?symbol=SHS@000001','5');

        // 메쎄이상 주가
        // $targetIndex = $this->crawler->checkDate('m/d', "https://finance.naver.com/item/main.naver?code=408920", ".invest_trend > div.sub_section.right > table > tbody > tr:nth-child(2) > th", 2);
        // $msValue = $this->crawler->findElementsBySelector("https://finance.naver.com/item/main.naver?code=408920", ".invest_trend > div.sub_section.right > table > tbody > tr:nth-child({$targetIndex}) > td:nth-of-type(1)");
        //$this->crawler->showResult("메쎄이상 주가", $msValue);
        // $resultArray[] = $msValue[0]->textContent;
        //$this->crawler->saveHtml('https://finance.naver.com/item/sise_day.naver?code=408920','6');

        // 이상네트웍스 주가
        // $targetIndex = $this->crawler->checkDate('m/d', "https://finance.naver.com/item/main.naver?code=080010", ".invest_trend > div.sub_section.right > table > tbody > tr:nth-child(2) > th", 2);
        // $esValue = $this->crawler->findElementsBySelector("https://finance.naver.com/item/main.naver?code=080010", ".invest_trend > div.sub_section.right > table > tbody > tr:nth-child({$targetIndex}) > td:nth-of-type(1)");
        //$this->crawler->showResult("이상네트웍스 주가", $esValue);
        // $resultArray[] = $esValue[0]->textContent;
        //$this->crawler->saveHtml('https://finance.naver.com/item/sise_day.naver?code=080010','7');

        // 황금에스티 주가
        // $targetIndex = $this->crawler->checkDate('m/d', "https://finance.naver.com/item/main.naver?code=032560", ".invest_trend > div.sub_section.right > table > tbody > tr:nth-child(2) > th", 2);
        // $hkValue = $this->crawler->findElementsBySelector("https://finance.naver.com/item/main.naver?code=032560", ".invest_trend > div.sub_section.right > table > tbody > tr:nth-child({$targetIndex}) > td:nth-of-type(1)");
        //$this->crawler->showResult("황금에스티 주가", $hkValue);
        // $resultArray[] = $hkValue[0]->textContent;
        //$this->crawler->saveHtml('https://finance.naver.com/item/sise_day.naver?code=032560','8');

        // 유에스티 주가
        // $targetIndex = $this->crawler->checkDate('m/d', "https://finance.naver.com/item/main.naver?code=263770", ".invest_trend > div.sub_section.right > table > tbody > tr:nth-child(2) > th", 2);
        // $usValue = $this->crawler->findElementsBySelector("https://finance.naver.com/item/main.naver?code=263770", ".invest_trend > div.sub_section.right > table > tbody > tr:nth-child({$targetIndex}) > td:nth-of-type(1)");
        //$this->crawler->showResult("유에스티 주가", $usValue);
        // $resultArray[] = $usValue[0]->textContent;
        //$this->crawler->saveHtml('https://finance.naver.com/item/sise_day.naver?code=263770','9');

        // 길교이앤씨 주가
        // $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/item/sise_day.naver?code=456700", "tr:nth-child(3) > td:nth-child(1) > span", 3);
        // $ggValue = $this->crawler->findElementsBySelector("https://finance.naver.com/item/sise_day.naver?code=456700", "tr:nth-child({$targetIndex}) > td:nth-child(2) > span");
        //$this->crawler->showResult("길교이앤씨 주가", $ggValue);
        // $resultArray[] = $ggValue[0]->textContent;
        //$this->crawler->saveHtml('https://finance.naver.com/item/sise_day.naver?code=456700','10');


        // 국고채 3년, 국고채 10년, 한국 CD 91일
        // $postBodyData = $this->postCrawler->setPostBodyData();
        // // $postHtml = $this->postCrawler->getPostHTML("https://ecos.bok.or.kr/serviceEndpoint/httpService/request.json", $postBodyData);
        // $extractedData = $this->postCrawler->extractData($postHtml);
        //$this->postCrawler->showResult($extractedData);
        // foreach ($extractedData as $extracted){
        //     $extractedValues = array_values($extracted);
        //     $resultArray[] = $extractedValues[1];
        // };

        // 미국채 2년
        // $us2Years = $this->crawler->findElementsBySelectorWithCurl("https://indexergo.com/series/?frq=D&idxDetail=13212", "#chartTable > tbody > tr:nth-child(1) > td.text-end.fw-bold.text-sm");
        //$this->crawler->showResult("미국 국채 2년", $us2Years);
        // $resultArray[] = $us2Years[0]->textContent;
        $this->crawler->saveHtml('https://kr.investing.com/rates-bonds/u.s.-2-year-bond-yield', '14');

        // 미국채 10년
        // $us10Years = $this->crawler->findElementsBySelectorWithCurl("https://indexergo.com/series/?frq=D&idxDetail=13220", "#chartTable > tbody > tr:nth-child(1) > td.text-end.fw-bold.text-sm");
        //$this->crawler->showResult("미국 국채 10년", $us10Years);
        // $resultArray[] = $us10Years[0]->textContent;
        $this->crawler->saveHtml('https://kr.investing.com/rates-bonds/u.s.-10-year-bond-yield', '15');

        // 달러/원 환율
        // $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/marketindex/exchangeDailyQuote.naver?marketindexCd=FX_USDKRW", "body > div > table > tbody > tr:nth-child(1) > td.date", 1);
        // $usdKrwValue = $this->crawler->findElementsBySelector("https://finance.naver.com/marketindex/exchangeDailyQuote.naver?marketindexCd=FX_USDKRW", "body > div > table > tbody > tr:nth-child({$targetIndex}) > td:nth-child(2)");
        //$this->crawler->showResult("달러/원 환율", $usdKrwValue);
        // $resultArray[] = $usdKrwValue[0]->textContent;
        //$this->crawler->saveHtml('https://finance.naver.com/marketindex/exchangeDailyQuote.naver?marketindexCd=FX_USDKRW','16');

        // 유로/달러 환율
        // $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/marketindex/worldDailyQuote.naver?fdtc=4&marketindexCd=FX_EURUSD", "body > div > table > tbody > tr:nth-child(1) > td.date", 1);
        // $eurUsdValue = $this->crawler->findElementsBySelector("https://finance.naver.com/marketindex/worldDailyQuote.naver?fdtc=4&marketindexCd=FX_EURUSD", "body > div > table > tbody > tr:nth-child({$targetIndex}) > td:nth-child(2)");
        //$this->crawler->showResult("유로/달러 환율", $eurUsdValue);
        // $resultArray[] = $eurUsdValue[0]->textContent;
        //$this->crawler->saveHtml('https://finance.naver.com/marketindex/worldDailyQuote.naver?fdtc=4&marketindexCd=FX_EURUSD','17');

        // 달러/중국위안 환율
        // $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/marketindex/worldDailyQuote.naver?fdtc=4&marketindexCd=FX_USDCNY", "body > div > table > tbody > tr:nth-child(1) > td.date", 1);
        // $usdCnyValue = $this->crawler->findElementsBySelector("https://finance.naver.com/marketindex/worldDailyQuote.naver?fdtc=4&marketindexCd=FX_USDCNY", "body > div > table > tbody > tr:nth-child({$targetIndex}) > td:nth-child(2)");
        //$this->crawler->showResult("달러/중국위안 환율", $usdCnyValue);
        // $resultArray[] = $usdCnyValue[0]->textContent;
        //$this->crawler->saveHtml('https://finance.naver.com/marketindex/worldDailyQuote.naver?fdtc=4&marketindexCd=FX_USDCNY','18');

        // 100엔/원 환율
        // $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/marketindex/exchangeDailyQuote.naver?marketindexCd=FX_JPYKRW", "body > div > table > tbody > tr:nth-child(1) > td.date", 1);
        // $jpyKrwValue = $this->crawler->findElementsBySelector("https://finance.naver.com/marketindex/exchangeDailyQuote.naver?marketindexCd=FX_JPYKRW", "body > div > table > tbody > tr:nth-child({$targetIndex}) > td:nth-child(2)");
        //$this->crawler->showResult("100엔/원 환율", $jpyKrwValue);
        // $resultArray[] = $jpyKrwValue[0]->textContent;
        //$this->crawler->saveHtml('https://finance.naver.com/marketindex/exchangeDailyQuote.naver?marketindexCd=FX_JPYKRW','19');

        // 국제유가 (WTI)
        // $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/marketindex/worldDailyQuote.naver?marketindexCd=OIL_CL&fdtc=2", "body > div > table > tbody > tr:nth-child(1) > td.date", 1);
        // $wtiValue = $this->crawler->findElementsBySelector("https://finance.naver.com/marketindex/worldDailyQuote.naver?marketindexCd=OIL_CL&fdtc=2", "body > div > table > tbody > tr:nth-child({$targetIndex}) > td:nth-child(2)");
        //$this->crawler->showResult("국제 유가 (WTI)", $wtiValue);
        // $resultArray[] = $wtiValue[0]->textContent;
        //$this->crawler->saveHtml('https://finance.naver.com/marketindex/worldDailyQuote.naver?marketindexCd=OIL_CL&fdtc=2','20');

        // 국제 금
        // $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/marketindex/worldDailyQuote.naver?marketindexCd=CMDT_GC&fdtc=2", "body > div > table > tbody > tr:nth-child(1) > td.date", 1);
        // $goldValue = $this->crawler->findElementsBySelector("https://finance.naver.com/marketindex/worldDailyQuote.naver?marketindexCd=CMDT_GC&fdtc=2", "body > div > table > tbody > tr:nth-child({$targetIndex}) > td:nth-child(2)");
        //$this->crawler->showResult("국제 금", $goldValue);
        // $resultArray[] = $goldValue[0]->textContent;
        //$this->crawler->saveHtml('https://finance.naver.com/marketindex/worldDailyQuote.naver?marketindexCd=CMDT_GC&fdtc=2','21');

        // 니켈
        // $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/marketindex/worldDailyQuote.naver?fdtc=2&marketindexCd=CMDT_NDY&page=1", "body > div > table > tbody > tr:nth-child(1) > td.date", 1);
        // $niValue = $this->crawler->findElementsBySelector("https://finance.naver.com/marketindex/worldDailyQuote.naver?fdtc=2&marketindexCd=CMDT_NDY&page=1", "body > div > table > tbody > tr:nth-child({$targetIndex}) > td:nth-child(2)");
        //$this->crawler->showResult("니켈", $niValue);
        // $resultArray[] = $niValue[0]->textContent;
        // $this->crawler->saveHtml('https://finance.naver.com/marketindex/worldDailyQuote.naver?fdtc=2&marketindexCd=CMDT_NDY&page=1','22');

        // $floatResultArray= [];
        // foreach($resultArray as $result){
        //     $cleanResult = str_replace(',','',$result);
        //     $floatResult = floatval($cleanResult);
        //     $floatResultArray[] = $floatResult;
        // }

        // return $floatResultArray;
    }

    function forCheckPath()
    {
        $dataURL = json_decode($this->dataURL, true);
        $dataPath = json_decode($this->dataPath, true);

        $crawlDataArray = [];
        foreach ($dataURL as $key => $url) {

            if (!in_array($key, array(11, 14, 15))) {
                $crawlDataArray[$key] = $this->crawler->checkPath($dataURL[$key], $dataPath[$key]);
            } elseif (in_array($key, array(11))) {
                $postHtml = $this->crawler->getPost($url, $this->postBodyData);
                $extractedArray[] = $this->crawler->extractData($postHtml);
                $i = 11;
                foreach ($extractedArray[0] as $extracted) {
                    $extractedKey = array_keys($extracted);
                    $crawlData = [$extractedKey[1], $extracted["$extractedKey[1]"]];
                    $crawlDataArray[$i] = $crawlData;
                    $i += 1;
                };
            } elseif (in_array($key, array(14, 15))) {
                $currentDate = $crawlDataArray[1][0];
                dump($crawlDataArray[1][0]);
                $crawlDataArray[$key] = [$currentDate, $this->crawler->checkPathCurl($dataURL[$key], $dataPath[$key])];
            }
        }
        return $crawlDataArray;
    }
}
// // $postHtml = $this->postCrawler->getPostHTML("https://ecos.bok.or.kr/serviceEndpoint/httpService/request.json", $postBodyData);
        // $extractedData = $this->postCrawler->extractData($postHtml);
        //$this->postCrawler->showResult($extractedData);
        // foreach ($extractedData as $extracted){
        //     $extractedValues = array_values($extracted);
        //     $resultArray[] = $extractedValues[1];
        // };