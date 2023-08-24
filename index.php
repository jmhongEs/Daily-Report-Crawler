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
// $floatResultArray= $crawlData->crawldata();

// DB에 삽입
// $dbConnect->dataInsert($floatResultArray);

// 데이터 HTML 문서에 삽입 

// 기준 날짜 (오늘날짜 - 1)
// 현재 스크래핑 방식 변경 중이라 20230823 데이터가 없어서 주석해둠
// $today = new DateTime();
// $yesterday = $today->modify('-1 day');
// $targetDate = intval($yesterday->format('Ymd'));
// $stockPriceDtoArr = $dbConnect->selectStockPriceDiff($targetDate);

// 테스트용으로 20230822 하드코딩해서 넣음
$stockPriceDtoArr = $dbConnect->selectStockPriceDiff(20230822);

// 카테고리의 첫 행, 마지막 행임을 알아야 함
// 카테고리별 stock 개수
$stockCountByCategoryArr = $dbConnect->selectStockCountByCategory();

$mailBody = $mailMaker->mailMake($stockCountByCategoryArr, $stockPriceDtoArr);
echo $mailBody;

// 쿼리 실행 후 마지막에 실행 (커넥션 종료)
$dbConnect->closeConnection();


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