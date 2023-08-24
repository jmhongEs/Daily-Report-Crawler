<?php

use App\DailyReportCrawler\ReportInforatmion;
use App\DailyReportCrawler\ReportRecipientInterface;
use App\DailyReportCrawler\ReportSender;

final class ReportSmsSender extends ReportSender
{
    public function send(ReportRecipientInterface $recipient, ReportInforatmion $reportInformation): void
    {
    }
}
