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

    public function forCheckPath()
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