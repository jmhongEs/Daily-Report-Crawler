<?php

namespace App\DailyReportCrawler;
use App\DailyReportCrawler\GetCrawler;
use App\DailyReportCrawler\PostCrawler;

class CrawlData
{
    private $crawler;
    private $postCrawler;

    public function __construct()
    {
        $this->crawler = new GetCrawler();
        $this->postCrawler = new postCrawler();
    }
    function crawldata()
    {   
        $resultArray=[];
        // 코스피 현재
        $kospiNow = $this->crawler->findElementsBySelector("https://finance.naver.com/sise/", "#KOSPI_now");
        //$this->crawler->showResult("코스피 현재", $kospiNow);
        $resultArray[] = $kospiNow[0]->textContent;

        // 코스닥 현재
        $kosdaqNow = $this->crawler->findElementsBySelector("https://finance.naver.com/sise/", "#KOSDAQ_now");
        //$this->crawler->showResult("코스닥 현재", $kosdaqNow);
        $resultArray[] = $kosdaqNow[0]->textContent;

        // 코스피 체결가
        $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/sise/sise_index_day.naver?code=KOSPI", "tr:nth-of-type(3) > .date", 3);
        $kospiValue = $this->crawler->findElementsBySelector("https://finance.naver.com/sise/sise_index_day.naver?code=KOSPI", "tr:nth-of-type({$targetIndex}) > td:nth-child(2)");
        //$this->crawler->showResult("코스피 체결가", $kospiValue);
        $resultArray[] = $kospiValue[0]->textContent;

        // 코스닥 체결가 
        $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/sise/sise_index_day.naver?code=KOSDAQ", "tr:nth-of-type(3) > .date", 3);
        $kosdaqValue = $this->crawler->findElementsBySelector("https://finance.naver.com/sise/sise_index_day.naver?code=KOSDAQ", "tr:nth-of-type({$targetIndex}) > td:nth-child(2)");
        //$this->crawler->showResult("코스닥 체결가", $kosdaqValue);
        $resultArray[] = $kosdaqValue[0]->textContent;

        // 다우 산업 종합
        $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/world/sise.naver?symbol=DJI@DJI", "#dayTable > tbody > tr:nth-child(1) > td.tb_td", 1);
        $dauValue = $this->crawler->findElementsBySelector("https://finance.naver.com/world/sise.naver?symbol=DJI@DJI", "#dayTable > tbody > tr:nth-child({$targetIndex}) > td.tb_td2 > span");
        //$this->crawler->showResult("다우 산업 종가", $dauValue);
        $resultArray[] = $dauValue[0]->textContent;

        // 나스닥 종합
        $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/world/sise.naver?symbol=NAS@IXIC", "#dayTable > tbody > tr:nth-child(1) > td.tb_td", 1);
        $nasValue = $this->crawler->findElementsBySelector("https://finance.naver.com/world/sise.naver?symbol=NAS@IXIC", "#dayTable > tbody > tr:nth-child({$targetIndex}) > td.tb_td2 > span");
        //$this->crawler->showResult("나스닥 종가", $nasValue);
        $resultArray[] = $nasValue[0]->textContent;

        // 상해 종합
        $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/world/sise.naver?symbol=SHS@000001", "#dayTable > tbody > tr:nth-child(1) > td:nth-child(1)", 1);
        $shsValue = $this->crawler->findElementsBySelector("https://finance.naver.com/world/sise.naver?symbol=SHS@000001", "#dayTable > tbody > tr:nth-child({$targetIndex}) > td.tb_td2 > span");
        //$this->crawler->showResult("상해 종가", $shsValue);
        $resultArray[] = $shsValue[0]->textContent;

        // 메쎄이상 주가
        $targetIndex = $this->crawler->checkDate('m/d', "https://finance.naver.com/item/main.naver?code=408920", ".invest_trend > div.sub_section.right > table > tbody > tr:nth-child(2) > th", 2);
        $msValue = $this->crawler->findElementsBySelector("https://finance.naver.com/item/main.naver?code=408920", ".invest_trend > div.sub_section.right > table > tbody > tr:nth-child({$targetIndex}) > td:nth-of-type(1)");
        //$this->crawler->showResult("메쎄이상 주가", $msValue);
        $resultArray[] = $msValue[0]->textContent;

        // 이상네트웍스 주가
        $targetIndex = $this->crawler->checkDate('m/d', "https://finance.naver.com/item/main.naver?code=080010", ".invest_trend > div.sub_section.right > table > tbody > tr:nth-child(2) > th", 2);
        $esValue = $this->crawler->findElementsBySelector("https://finance.naver.com/item/main.naver?code=080010", ".invest_trend > div.sub_section.right > table > tbody > tr:nth-child({$targetIndex}) > td:nth-of-type(1)");
        //$this->crawler->showResult("이상네트웍스 주가", $esValue);
        $resultArray[] = $esValue[0]->textContent;

        // 황금에스티 주가
        $targetIndex = $this->crawler->checkDate('m/d', "https://finance.naver.com/item/main.naver?code=032560", ".invest_trend > div.sub_section.right > table > tbody > tr:nth-child(2) > th", 2);
        $hkValue = $this->crawler->findElementsBySelector("https://finance.naver.com/item/main.naver?code=032560", ".invest_trend > div.sub_section.right > table > tbody > tr:nth-child({$targetIndex}) > td:nth-of-type(1)");
        //$this->crawler->showResult("황금에스티 주가", $hkValue);
        $resultArray[] = $hkValue[0]->textContent;

        // 유에스티 주가
        $targetIndex = $this->crawler->checkDate('m/d', "https://finance.naver.com/item/main.naver?code=263770", ".invest_trend > div.sub_section.right > table > tbody > tr:nth-child(2) > th", 2);
        $usValue = $this->crawler->findElementsBySelector("https://finance.naver.com/item/main.naver?code=263770", ".invest_trend > div.sub_section.right > table > tbody > tr:nth-child({$targetIndex}) > td:nth-of-type(1)");
        //$this->crawler->showResult("유에스티 주가", $usValue);
        $resultArray[] = $usValue[0]->textContent;

        // 길교이앤씨 주가
        $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/item/sise_day.naver?code=456700", "tr:nth-child(3) > td:nth-child(1) > span", 3);
        $ggValue = $this->crawler->findElementsBySelector("https://finance.naver.com/item/sise_day.naver?code=456700", "tr:nth-child({$targetIndex}) > td:nth-child(2) > span");
        //$this->crawler->showResult("길교이앤씨 주가", $ggValue);
        $resultArray[] = $ggValue[0]->textContent;


        // 국고채 3년, 국고채 10년, 한국 CD 91일
        $postBodyData = $this->postCrawler->setPostBodyData();
        $postHtml = $this->postCrawler->getPostHTML("https://ecos.bok.or.kr/serviceEndpoint/httpService/request.json", $postBodyData);
        $extractedData = $this->postCrawler->extractData($postHtml);
        //$this->postCrawler->showResult($extractedData);
        foreach ($extractedData as $extracted){
            $extractedValues = array_values($extracted);
            $resultArray[] = $extractedValues[1];
        };

        // 미국채 2년
        $us2Years = $this->crawler->findElementsBySelectorWithCurl("https://indexergo.com/series/?frq=D&idxDetail=13212", "#chartTable > tbody > tr:nth-child(1) > td.text-end.fw-bold.text-sm");
        //$this->crawler->showResult("미국 국채 2년", $us2Years);
        $resultArray[] = $us2Years[0]->textContent;

        // 미국채 10년
        $us10Years = $this->crawler->findElementsBySelectorWithCurl("https://indexergo.com/series/?frq=D&idxDetail=13220", "#chartTable > tbody > tr:nth-child(1) > td.text-end.fw-bold.text-sm");
        //$this->crawler->showResult("미국 국채 10년", $us10Years);
        $resultArray[] = $us10Years[0]->textContent;

        // 달러/원 환율
        $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/marketindex/exchangeDailyQuote.naver?marketindexCd=FX_USDKRW", "body > div > table > tbody > tr:nth-child(1) > td.date", 1);
        $usdKrwValue = $this->crawler->findElementsBySelector("https://finance.naver.com/marketindex/exchangeDailyQuote.naver?marketindexCd=FX_USDKRW", "body > div > table > tbody > tr:nth-child({$targetIndex}) > td:nth-child(2)");
        //$this->crawler->showResult("달러/원 환율", $usdKrwValue);
        $resultArray[] = $usdKrwValue[0]->textContent;

        // 유로/달러 환율
        $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/marketindex/worldDailyQuote.naver?fdtc=4&marketindexCd=FX_EURUSD", "body > div > table > tbody > tr:nth-child(1) > td.date", 1);
        $eurUsdValue = $this->crawler->findElementsBySelector("https://finance.naver.com/marketindex/worldDailyQuote.naver?fdtc=4&marketindexCd=FX_EURUSD", "body > div > table > tbody > tr:nth-child({$targetIndex}) > td:nth-child(2)");
        //$this->crawler->showResult("유로/달러 환율", $eurUsdValue);
        $resultArray[] = $eurUsdValue[0]->textContent;

        // 달러/중국위안 환율
        $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/marketindex/worldDailyQuote.naver?fdtc=4&marketindexCd=FX_USDCNY", "body > div > table > tbody > tr:nth-child(1) > td.date", 1);
        $usdCnyValue = $this->crawler->findElementsBySelector("https://finance.naver.com/marketindex/worldDailyQuote.naver?fdtc=4&marketindexCd=FX_USDCNY", "body > div > table > tbody > tr:nth-child({$targetIndex}) > td:nth-child(2)");
        //$this->crawler->showResult("달러/중국위안 환율", $usdCnyValue);
        $resultArray[] = $usdCnyValue[0]->textContent;

        // 100엔/원 환율
        $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/marketindex/exchangeDailyQuote.naver?marketindexCd=FX_JPYKRW", "body > div > table > tbody > tr:nth-child(1) > td.date", 1);
        $jpyKrwValue = $this->crawler->findElementsBySelector("https://finance.naver.com/marketindex/exchangeDailyQuote.naver?marketindexCd=FX_JPYKRW", "body > div > table > tbody > tr:nth-child({$targetIndex}) > td:nth-child(2)");
        //$this->crawler->showResult("100엔/원 환율", $jpyKrwValue);
        $resultArray[] = $jpyKrwValue[0]->textContent;

        // 국제유가 (WTI)
        $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/marketindex/worldDailyQuote.naver?marketindexCd=OIL_CL&fdtc=2", "body > div > table > tbody > tr:nth-child(1) > td.date", 1);
        $wtiValue = $this->crawler->findElementsBySelector("https://finance.naver.com/marketindex/worldDailyQuote.naver?marketindexCd=OIL_CL&fdtc=2", "body > div > table > tbody > tr:nth-child({$targetIndex}) > td:nth-child(2)");
        //$this->crawler->showResult("국제 유가 (WTI)", $wtiValue);
        $resultArray[] = $wtiValue[0]->textContent;

        // 국제 금
        $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/marketindex/worldDailyQuote.naver?marketindexCd=CMDT_GC&fdtc=2", "body > div > table > tbody > tr:nth-child(1) > td.date", 1);
        $goldValue = $this->crawler->findElementsBySelector("https://finance.naver.com/marketindex/worldDailyQuote.naver?marketindexCd=CMDT_GC&fdtc=2", "body > div > table > tbody > tr:nth-child({$targetIndex}) > td:nth-child(2)");
        //$this->crawler->showResult("국제 금", $goldValue);
        $resultArray[] = $goldValue[0]->textContent;

        // 니켈
        $targetIndex = $this->crawler->checkDate('Y.m.d', "https://finance.naver.com/marketindex/worldDailyQuote.naver?fdtc=2&marketindexCd=CMDT_NDY&page=1", "body > div > table > tbody > tr:nth-child(1) > td.date", 1);
        $niValue = $this->crawler->findElementsBySelector("https://finance.naver.com/marketindex/worldDailyQuote.naver?fdtc=2&marketindexCd=CMDT_NDY&page=1", "body > div > table > tbody > tr:nth-child({$targetIndex}) > td:nth-child(2)");
        //$this->crawler->showResult("니켈", $niValue);
        $resultArray[] = $niValue[0]->textContent;

        return $resultArray;
    }
}
