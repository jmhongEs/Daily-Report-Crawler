<?php

require_once 'bootstrap.php';

use App\DailyReportCrawler\CrawlData;
use App\DailyReportCrawler\Mailer;

$mailer = new Mailer();
$crawlData = new CrawlData;

// $mailer->sendEmail($_ENV['REPORT_RECIPIENT_EMAIL']);
$resultArray= $crawlData->crawldata();
print_r($resultArray);
?>