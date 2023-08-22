<?php

require_once 'bootstrap.php';

use App\DailyReportCrawler\CrawlData;
use App\DailyReportCrawler\Mailer;
use App\DailyReportCrawler\MailMaker;
use App\DailyReportCrawler\DBConnect;

$mailer = new Mailer();
$crawlData = new CrawlData();
$mailMaker = new MailMaker();
$dbConnect = new DBConnect();

// 데이터 모으기
$floatResultArray= $crawlData->crawldata();

// DB에 삽입
$dbConnect->dataInsert($floatResultArray);

// 데이터 SELECT
// 오늘 기준으로 -1(기준종가), -2(D-1종가), -6(D-5종가), -21(D-20종가) 필요

// 날짜를 계산해서 int형으로 바꿔서 반환해주는 함수
function dateFormat($minusDay) {
    $today = new DateTime();
    $targetdate = $today->modify("-{$minusDay} day");
    return intval($targetdate->format('Ymd'));
}


$dataArr1 = $dbConnect->selectStockPriceByStockDate(dateFormat(1));
$dataArr2 = $dbConnect->selectStockPriceByStockDate(dateFormat(2)); 
$dataArr3 = $dbConnect->selectStockPriceByStockDate(dateFormat(6)); 
$dataArr4 = $dbConnect->selectStockPriceByStockDate(dateFormat(21));

// 데이터 HTML 문서에 삽입 
$mailBody = $mailMaker->mailMake($dataArr1, $dataArr2, $dataArr3, $dataArr4);
echo $mailBody;



// $emails = $_ENV['REPORT_RECIPIENT_EMAIL'];

//$emails = explode(';', $_ENV['REPORT_RECIPIENT_EMAIL']);

// dump($emails);
// $thisTime = new DateTime();
// $thisTimeStr = $thisTime->format('Y-m-d H:i:s');
// $thisTimeStr = $thisTime->format('Y-m-d');
// $thisTimeStr = date("Y-m-d");
// $subject = $thisTimeStr.' 주가 스크래퍼 입니다.';
// HTML 문서를 Mail Body에 실어서 보내기
// foreach ($emails as $email) {
//     $mailer->sendEmail($subject, $email, $mailBody);
// }