<?php

namespace App\DailyReportCrawler;

/**
 * @author 서영
 * stock_category 
 */
class StockCategoryDto {
    public int $stockCategoryId;
    public string $categoryName;
    public int $stockCount;

    public function __construct($stockCategoryId, $categoryName, $stockCount) {
        $this->stockCategoryId = $stockCategoryId;
        $this->categoryName = $categoryName;
        $this->stockCount = $stockCount;
    }
}