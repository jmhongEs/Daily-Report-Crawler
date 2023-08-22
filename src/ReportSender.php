<?php

namespace App\DailyReportCrawler;

abstract class ReportSender
{
    public function send(ReportRecipientInterface $recipient, ReportInforatmion $reportInformation): void {}
}
