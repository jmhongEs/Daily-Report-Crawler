<?php

namespace App\DailyReportCrawler;

/**
 * @author 서영
 */

class StockPriceDto {
    public string $gbn;
    public int $stockCategoryId;
    public string $categoryName;
    public int $stockDate;
    public int $stockId;
    public string $stockName;
    public float $stockValue;
    public ?string $remarks;


    public function __construct($gbn, $stockCategoryId, $categoryName, $stockDate, $stockId, $stockName, $stockValue, $remarks) {
        $this->gbn = $gbn;
        $this->stockCategoryId = $stockCategoryId;
        $this->categoryName = $categoryName;
        $this->stockDate = $stockDate;
        $this->stockId = $stockId;
        $this->stockName = $stockName;
        $this->stockValue = $stockValue;
        $this->remarks = $remarks;
    }
}