<?php

namespace App\DailyReportCrawler;

/**
 * @author 서영
 */

class StockPriceDto {
    public int $stockCategoryId;
    public string $categoryName;
    public int $stockId;
    public string $stockName;
    public ?float $d0Value;
    public ?float $d1Diff;
    public ?float $d5Diff;
    public ?float $d20Diff;
    public ?string $remarks;

    public function __construct($stockCategoryId, $categoryName, $stockId, $stockName, $d0Value, $d1Diff, $d5Diff, $d20Diff, $remarks) {
        $this->stockCategoryId = $stockCategoryId;
        $this->categoryName = $categoryName;
        $this->stockId = $stockId;
        $this->stockName = $stockName;
        $this->d0Value = $d0Value;
        $this->d1Diff = $d1Diff;
        $this->d5Diff = $d5Diff;
        $this->d20Diff = $d20Diff;
        $this->remarks = $remarks;
    }
}