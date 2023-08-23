<?php

namespace App\DailyReportCrawler;

/**
 * @author 서영
 * stock 테이블과 stock_price 테이블을 조인한 데이터를 담을 DTO 클래스
 */
class StockPriceDto {
    public int $stockDate;
    public int $stockId;
    public string $stockName;
    public float $stockValue;
    public string $remarks;

    public function __construct($stockDate, $stockId, $stockName, $stockValue, $remarks) {
        $this->stockDate = $stockDate;
        $this->stockId = $stockId;
        $this->stockName = $stockName;
        $this->stockValue = $stockValue;
        $this->remarks = $remarks;
    }
}