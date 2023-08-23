<?php

namespace App\DailyReportCrawler;
use App\DailyReportCrawler\DBConnect;

use DateTime;

class MailMaker {

    function test() {


    }


    // 오늘 기준으로 -1(기준종가), -2(D-1종가), -6(D-5종가), -21(D-20종가) 필요
    function mailMake() {
        // 문서에 필요한 날짜 변수
        $todayFormat = Date('Y-m-d');
        // 하루 전날
        $today = new DateTime();
        $yesterday = $today->modify('-1 day');
        $dbConnect = new DBConnect();

        // 인라인 스타일 코드 간소화
        $table_td = "text-align: center; font-size: 14px; padding: 7px 0;";
        $value_td = "font-size: 14px; padding: 7px 0 7px 12px;";
        $fonts = "font-family: AppleSDGothic, malgun gothic, nanum gothic, Noto Sans KR, sans-serif;";

        // 문서 윗부분
        $mailBody = "
        <!DOCTYPE html>
        <html lang=\"en\">

        <head>
            <meta charset=\"UTF-8\">
            <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
            <title>Daily Report</title>
        </head>

        <body style=\"width: 800px;  padding: 0 20px;\">

            <header style=\"display: flex; justify-content: space-between; align-items: flex-end; margin: 0 30px 0;\">
                <h5 style=\"{$fonts} margin-bottom: 9px;\">메쎄이상</h5>
                <h1 style=\"{$fonts} margin-bottom: 9px;\">Daily Report</h1>
                <h5 style=\"{$fonts} margin-bottom: 9px;\"> {$todayFormat}</h5>
            </header>
            <hr style=\"margin: 0 0 2px;\">
            <hr style=\"margin: 0;\">

            <div style=\"display: flex; justify-content: space-between;\">
                <h4 style=\"{$fonts} margin-bottom: 4px;\">[주요지수 변동현황]</h4>
                <h5 style=\"{$fonts} margin-bottom: 4px;\">
                    {$yesterday->format('(m월 d일 종가 기준)')}
                </h5>
            </div>

            <table style=\"border-collapse: collapse; margin: 0 auto; width: 100%;\">
                <thead>
                    <tr style=\"border-top: 2px solid #555; border-bottom: 1px solid #aaa; background-color: #eee;\">
                        <th style=\"{$fonts} width: 15%; padding: 10px; font-size: 14px;\">구분</th>
                        <th style=\"{$fonts} width: 20%; padding: 10px; border-right: 1px solid #aaa; font-size: 14px;\"></th>
                        <th style=\"{$fonts} width: 12%; padding: 10px; border-right: 1px solid #aaa; font-size: 14px;\">종가</th>
                        <th style=\"{$fonts} width: 12%; padding: 10px; border-right: 1px solid #aaa; font-size: 14px;\">D-1</th>
                        <th style=\"{$fonts} width: 12%; padding: 10px; border-right: 1px solid #aaa; font-size: 14px;\">D-5</th>
                        <th style=\"{$fonts} width: 12%; padding: 10px; border-right: 1px solid #aaa; font-size: 14px;\">D-20</th>
                        <th style=\"{$fonts} width: 15%; padding: 10px; font-size: 14px;\">비고</th>
                    </tr>
                </thead>
                <tbody>";
        
        // category 데이터 (id, name)
        $stockCategoryArr = $dbConnect->selectStockCategory();

        foreach ($stockCategoryArr as $category) {
            // 현재 반복에서 사용될 targetCategoryId (가독성을 위해 변수로 담음)
            $targetCategoryId = $category->stockCategoryId;

            
            // 해당 카테고리에 존재하는 stock_id 배열
            $stockIdArr = $dbConnect->selectStockIdByCategory($targetCategoryId);
            
            // 해당 카테고리의 stock 개수
            $stockIdCount = count($stockIdArr);

            // 첫 행이나 마지막 행에 추가해야 할 요소가 있으므로, 이를 확인하기 위해 변수를 선언함 (현재 반복 횟수)
            $currentCount = 1;
            
            // category별 stock 개수만큼씩 반복
            foreach ($stockIdArr as $targetStockId) {

                // 종가기준일과 stockId에 해당하는 stockPrice 배열 반환
                // dateFormat : 오늘 날짜 기준으로 n일 이전의 날짜를 Ymd 형태의 정수로 반환하는 메서드
                // 기준 종가 (현재 날짜 기준 -1일)
                $valueNow = $dbConnect->selectStockPriceByStockDateAndStockId($this->dateFormat(1), $targetStockId);
                // D-1 종가 (현재 날짜 기준 -2일)
                $valueD1 = $dbConnect->selectStockPriceByStockDateAndStockId($this->dateFormat(2), $targetStockId);
                // D-5 종가 (현재 날짜 기준 -6일)
                $valueD5 = $dbConnect->selectStockPriceByStockDateAndStockId($this->dateFormat(6), $targetStockId);
                // D-20 종가 (현재 날짜 기준 -21일)
                $valueD20 = $dbConnect->selectStockPriceByStockDateAndStockId($this->dateFormat(21), $targetStockId);

                // 해당 카테고리의 마지막 행이라면 
                if ($currentCount == $stockIdCount) {
                    $mailBody .= "<tr style=\"border-bottom: 1px solid #aaa;\">";
                // 그 외
                } else {
                    $mailBody .= "<tr>";
                }
                
                // 해당 카테고리의 첫 행이라면
                if ($currentCount == 1) {
                    $mailBody .= "<th rowspan=\"{$category->stockCount}\" style=\"{$fonts} font-size: 14px;\">{$category->categoryName}</th>";
                }

                $mailBody .= "<td style=\"{$fonts} {$table_td}\">{$valueNow->stockName}</td>";
                $mailBody .= "<td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($valueNow->stockValue) . "</td>";


                // 각 데이터가 없는 경우 일단 - 처리해두었는데
                // 지금처럼 - 처리할지, 아니면 stock_date 값을 낮추면서 가장 최근의 값을 가져와서 계산할지 얘기해보고 바꾸기

                // 기준종가도 데이터가 없는 경우 - 처리를 할지 아니면 가장 최근의 값을 가져와서 계산할지 얘기해보아야 함

                if ($valueD1 == null) {
                    $mailBody .= "<td style=\"{$fonts} {$value_td}\">-&nbsp</td>";                   
                } else {
                    $mailBody .= "<td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($valueNow->stockValue - $valueD1->stockValue) . "\"> " 
                                    . $this->changeSymbolByUpDown($valueNow->stockValue - $valueD1->stockValue) . "&nbsp" 
                                    . $this->valueFormat($valueNow->stockValue - $valueD1->stockValue) . "</td>";
                }

                if ($valueD5 == null) {
                    $mailBody .= "<td style=\"{$fonts} {$value_td}\">-&nbsp</td>";                           
                } else {
                    $mailBody .= "<td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($valueNow->stockValue - $valueD5->stockValue) . "\"> " 
                    . $this->changeSymbolByUpDown($valueNow->stockValue - $valueD5->stockValue) . "&nbsp" 
                    . $this->valueFormat($valueNow->stockValue - $valueD5->stockValue) . "</td>";
                }
                
                if ($valueD20 == null) {
                    $mailBody .= "<td style=\"{$fonts} {$value_td}\">-&nbsp</td>";                           
                } else {
                    $mailBody .= "<td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($valueNow->stockValue - $valueD20->stockValue) . "\"> " 
                                    . $this->changeSymbolByUpDown($valueNow->stockValue - $valueD20->stockValue) . "&nbsp" 
                                    . $this->valueFormat($valueNow->stockValue - $valueD20->stockValue) . "</td>";
                }

                $mailBody .= "<td style=\"{$fonts} {$table_td}\">$valueNow->remarks</td>";
                $mailBody .= "</tr>";

                // 반복 횟수 + 1
                $currentCount++;
            }
                
        }
            
        // 태그 닫아주기
        $mailBody .= "</tbody></table></body></html>";

        return $mailBody;
    }
    
    // style 변경 (글자 색)
    function changeStyleByUpDown($diff) {
        if ($diff > 0) {
            return "color: #CC3300";
        } else if ($diff < 0) {
            return "color: #0000CC";
        } else {
            return "color: #333333";
        }
    }
    
    // 문자 변경
    function changeSymbolByUpDown($diff) {
        if ($diff > 0) {
            return "▲";
        } else if ($diff < 0) {
            return "▼";
        } else {
            return "&nbsp-&nbsp";
        }
    }
    
    // 증감값에 절댓값 처리 + 포맷팅
    function valueFormat($value) {
        if ($value == 0) {
            return null;
        } else {
            return number_format(abs($value), 2);
        }
    }
    
    // 날짜를 계산해서 int형으로 바꿔서 반환
    function dateFormat($minusDay) {
        $today = new DateTime();
        $targetdate = $today->modify("-{$minusDay} day");
        return intval($targetdate->format('Ymd'));
    }

}
    ?>