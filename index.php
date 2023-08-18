<?php

require_once 'bootstrap.php';

use App\DailyReportCrawler\CrawlData;
use App\DailyReportCrawler\Mailer;
use App\DailyReportCrawler\MailMaker;

$mailer = new Mailer();
$crawlData = new CrawlData();

// 데이터 모으기
$resultArray= $crawlData->crawldata();

// 데이터 HTML 문서에 삽입

// HTML 문서를 Mail Body에 실어서 보내기
// $mailer->sendEmail($_ENV['REPORT_RECIPIENT_EMAIL'],$mailBody);


?>