<?php

namespace App\DailyReportCrawler;

class StockPrice {
    public int $stockPriceId;
    public int $stockDate;
    public string $createdDate;
    public int $stockId;
    public float $stockPrice;

    public function __construct($stockPriceId, $stockDate, $createdDate, $stockId, $stockPrice) {
        $this->stockPriceId = $stockPriceId;
        $this->stockDate = $stockDate;
        $this->createdDate = $createdDate;
        $this->stockId = $stockId;
        $this->stockPrice = $stockPrice;
    }

}