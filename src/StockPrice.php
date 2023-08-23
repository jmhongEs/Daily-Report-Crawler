<?php

namespace App\DailyReportCrawler;

class StockPrice {
    public int $stockPriceId;
    public int $stockDate;
    public string $createdDate;
    public int $stockId;
    public float $stockPrice;

    public function __construct($stockDate, $stockId, $stockPrice) {
        $this->stockDate = $stockDate;
        $this->stockId = $stockId;
        $this->stockPrice = $stockPrice;
    }


}