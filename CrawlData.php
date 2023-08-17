<?php

/**
 * @author 이서영
 * 데이터를 모두 뽑아내고 나서 종무님 코드랑 합치기
 */

require 'vendor/autoload.php'; // Composer autoloader 로드
require 'GetCrawler.php';
require 'PostCrawler.php';

$crawler = new GetCrawler();
$postCrawler = new PostCrawler();

// 코스피 현재
$kospiNow = $crawler->findElementsBySelector("https://finance.naver.com/sise/", "#KOSPI_now");
$crawler->showResult("코스피 현재", $kospiNow);

// 코스닥 현재
$kosdaqNow = $crawler->findElementsBySelector("https://finance.naver.com/sise/", "#KOSDAQ_now");
$crawler->showResult("코스닥 현재", $kosdaqNow);

// 코스피 체결가
$targetIndex = $crawler->checkDate('Y.m.d', "https://finance.naver.com/sise/sise_index_day.naver?code=KOSPI", "tr:nth-of-type(3) > .date", 3);
$kospiValue = $crawler->findElementsBySelector("https://finance.naver.com/sise/sise_index_day.naver?code=KOSPI", "tr:nth-of-type({$targetIndex}) > td:nth-child(2)");
$crawler->showResult("코스피 체결가", $kospiValue);

// 코스닥 체결가 
$targetIndex = $crawler->checkDate('Y.m.d', "https://finance.naver.com/sise/sise_index_day.naver?code=KOSDAQ", "tr:nth-of-type(3) > .date", 3);
$kosdaqValue = $crawler->findElementsBySelector("https://finance.naver.com/sise/sise_index_day.naver?code=KOSDAQ", "tr:nth-of-type({$targetIndex}) > td:nth-child(2)");
$crawler->showResult("코스닥 체결가", $kosdaqValue);

// 다우 산업 종합
$targetIndex = $crawler->checkDate('Y.m.d', "https://finance.naver.com/world/sise.naver?symbol=DJI@DJI", "#dayTable > tbody > tr:nth-child(1) > td.tb_td", 1);
$dauValue = $crawler->findElementsBySelector("https://finance.naver.com/world/sise.naver?symbol=DJI@DJI", "#dayTable > tbody > tr:nth-child({$targetIndex}) > td.tb_td2 > span");
$crawler->showResult("다우 산업 종가", $dauValue);

// 나스닥 종합
$targetIndex = $crawler->checkDate('Y.m.d', "https://finance.naver.com/world/sise.naver?symbol=NAS@IXIC", "#dayTable > tbody > tr:nth-child(1) > td.tb_td", 1);
$nasValue = $crawler->findElementsBySelector("https://finance.naver.com/world/sise.naver?symbol=NAS@IXIC", "#dayTable > tbody > tr:nth-child({$targetIndex}) > td.tb_td2 > span");
$crawler->showResult("나스닥 종가", $nasValue);

// 상해 종합
$targetIndex = $crawler->checkDate('Y.m.d', "https://finance.naver.com/world/sise.naver?symbol=SHS@000001", "#dayTable > tbody > tr:nth-child(1) > td:nth-child(1)", 1);
$shsValue = $crawler->findElementsBySelector("https://finance.naver.com/world/sise.naver?symbol=SHS@000001", "#dayTable > tbody > tr:nth-child({$targetIndex}) > td.tb_td2 > span");
$crawler->showResult("상해 종가", $shsValue);

// 메쎄이상 주가
$targetIndex = $crawler->checkDate('m/d', "https://finance.naver.com/item/main.naver?code=408920", ".invest_trend > div.sub_section.right > table > tbody > tr:nth-child(2) > th", 2);
$msValue = $crawler->findElementsBySelector("https://finance.naver.com/item/main.naver?code=408920", ".invest_trend > div.sub_section.right > table > tbody > tr:nth-child({$targetIndex}) > td:nth-of-type(1)");
$crawler->showResult("메쎄이상 주가", $msValue);

// 이상네트웍스 주가
$targetIndex = $crawler->checkDate('m/d', "https://finance.naver.com/item/main.naver?code=080010", ".invest_trend > div.sub_section.right > table > tbody > tr:nth-child(2) > th", 2);
$esValue = $crawler->findElementsBySelector("https://finance.naver.com/item/main.naver?code=080010", ".invest_trend > div.sub_section.right > table > tbody > tr:nth-child({$targetIndex}) > td:nth-of-type(1)");
$crawler->showResult("이상네트웍스 주가", $esValue);

// 황금에스티 주가
$targetIndex = $crawler->checkDate('m/d', "https://finance.naver.com/item/main.naver?code=032560", ".invest_trend > div.sub_section.right > table > tbody > tr:nth-child(2) > th", 2);
$hkValue = $crawler->findElementsBySelector("https://finance.naver.com/item/main.naver?code=032560", ".invest_trend > div.sub_section.right > table > tbody > tr:nth-child({$targetIndex}) > td:nth-of-type(1)");
$crawler->showResult("황금에스티 주가", $hkValue);

// 유에스티 주가
$targetIndex = $crawler->checkDate('m/d', "https://finance.naver.com/item/main.naver?code=263770", ".invest_trend > div.sub_section.right > table > tbody > tr:nth-child(2) > th", 2);
$usValue = $crawler->findElementsBySelector("https://finance.naver.com/item/main.naver?code=263770", ".invest_trend > div.sub_section.right > table > tbody > tr:nth-child({$targetIndex}) > td:nth-of-type(1)");
$crawler->showResult("유에스티 주가", $usValue);

// 길교이앤씨 주가
$targetIndex = $crawler->checkDate('Y.m.d', "https://finance.naver.com/item/sise_day.naver?code=456700", "tr:nth-child(3) > td:nth-child(1) > span", 3);
$ggValue = $crawler->findElementsBySelector("https://finance.naver.com/item/sise_day.naver?code=456700", "tr:nth-child({$targetIndex}) > td:nth-child(2) > span");
$crawler->showResult("길교이앤씨 주가", $ggValue);


// 국고채 3년, 국고채 10년, 한국 CD 91일
$postBodyData = $postCrawler->setPostBodyData();
$postHtml = $postCrawler->getPostHTML("https://ecos.bok.or.kr/serviceEndpoint/httpService/request.json",$postBodyData);
$extractedData = $postCrawler->extractData($postHtml);
$postCrawler->showResult($extractedData);

// 미국채 2년
$us2Years = $crawler->findElementsBySelectorWithCurl("https://indexergo.com/series/?frq=D&idxDetail=13212", "#chartTable > tbody > tr:nth-child(1) > td.text-end.fw-bold.text-sm");
$crawler->showResult("미국 국채 2년", $us2Years);

// 미국채 10년
$us10Years = $crawler->findElementsBySelectorWithCurl("https://indexergo.com/series/?frq=D&idxDetail=13220", "#chartTable > tbody > tr:nth-child(1) > td.text-end.fw-bold.text-sm");
$crawler->showResult("미국 국채 10년", $us10Years);

// 달러/원 환율
$targetIndex = $crawler->checkDate('Y.m.d', "https://finance.naver.com/marketindex/exchangeDailyQuote.naver?marketindexCd=FX_USDKRW", "body > div > table > tbody > tr:nth-child(1) > td.date", 1);
$usdKrwValue = $crawler->findElementsBySelector("https://finance.naver.com/marketindex/exchangeDailyQuote.naver?marketindexCd=FX_USDKRW", "body > div > table > tbody > tr:nth-child({$targetIndex}) > td:nth-child(2)");
$crawler->showResult("달러/원 환율", $usdKrwValue);

// 유로/달러 환율
$targetIndex = $crawler->checkDate('Y.m.d', "https://finance.naver.com/marketindex/worldDailyQuote.naver?fdtc=4&marketindexCd=FX_EURUSD", "body > div > table > tbody > tr:nth-child(1) > td.date", 1);
$eurUsdValue = $crawler->findElementsBySelector("https://finance.naver.com/marketindex/worldDailyQuote.naver?fdtc=4&marketindexCd=FX_EURUSD", "body > div > table > tbody > tr:nth-child({$targetIndex}) > td:nth-child(2)");
$crawler->showResult("유로/달러 환율", $eurUsdValue);

// 달러/중국위안 환율
$targetIndex = $crawler->checkDate('Y.m.d', "https://finance.naver.com/marketindex/worldDailyQuote.naver?fdtc=4&marketindexCd=FX_USDCNY", "body > div > table > tbody > tr:nth-child(1) > td.date", 1);
$usdCnyValue = $crawler->findElementsBySelector("https://finance.naver.com/marketindex/worldDailyQuote.naver?fdtc=4&marketindexCd=FX_USDCNY", "body > div > table > tbody > tr:nth-child({$targetIndex}) > td:nth-child(2)");
$crawler->showResult("달러/중국위안 환율", $usdCnyValue);

// 100엔/원 환율
$targetIndex = $crawler->checkDate('Y.m.d', "https://finance.naver.com/marketindex/exchangeDailyQuote.naver?marketindexCd=FX_JPYKRW", "body > div > table > tbody > tr:nth-child(1) > td.date", 1);
$jpyKrwValue = $crawler->findElementsBySelector("https://finance.naver.com/marketindex/exchangeDailyQuote.naver?marketindexCd=FX_JPYKRW", "body > div > table > tbody > tr:nth-child({$targetIndex}) > td:nth-child(2)");
$crawler->showResult("100엔/원 환율", $jpyKrwValue);

// 국제유가 (WTI)
$targetIndex = $crawler->checkDate('Y.m.d', "https://finance.naver.com/marketindex/worldDailyQuote.naver?marketindexCd=OIL_CL&fdtc=2", "body > div > table > tbody > tr:nth-child(1) > td.date", 1);
$wtiValue = $crawler->findElementsBySelector("https://finance.naver.com/marketindex/worldDailyQuote.naver?marketindexCd=OIL_CL&fdtc=2", "body > div > table > tbody > tr:nth-child({$targetIndex}) > td:nth-child(2)");
$crawler->showResult("국제 유가 (WTI)", $wtiValue);

// 국제 금
$targetIndex = $crawler->checkDate('Y.m.d', "https://finance.naver.com/marketindex/worldDailyQuote.naver?marketindexCd=CMDT_GC&fdtc=2", "body > div > table > tbody > tr:nth-child(1) > td.date", 1);
$goldValue = $crawler->findElementsBySelector("https://finance.naver.com/marketindex/worldDailyQuote.naver?marketindexCd=CMDT_GC&fdtc=2", "body > div > table > tbody > tr:nth-child({$targetIndex}) > td:nth-child(2)");
$crawler->showResult("국제 금", $goldValue);

// 니켈
$targetIndex = $crawler->checkDate('Y.m.d', "https://finance.naver.com/marketindex/worldDailyQuote.naver?fdtc=2&marketindexCd=CMDT_NDY&page=1", "body > div > table > tbody > tr:nth-child(1) > td.date", 1);
$niValue = $crawler->findElementsBySelector("https://finance.naver.com/marketindex/worldDailyQuote.naver?fdtc=2&marketindexCd=CMDT_NDY&page=1", "body > div > table > tbody > tr:nth-child({$targetIndex}) > td:nth-child(2)");
$crawler->showResult("니켈", $niValue);

?>