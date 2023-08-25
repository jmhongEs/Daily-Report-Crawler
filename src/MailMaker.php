<?php

namespace App\DailyReportCrawler;
use App\DailyReportCrawler\DBConnect;

use DateTime;

class MailMaker {

    /**
     * 메일 바디를 만드는 메서드
     * todo : 템플릿과 메일 만드는 클래스를 분리하기
     * @param DateTime $targetStockDate 
     * @param array $stockCountByCategoryArr 
     * @param array $stockPriceDtoArr 
     * @return string 
     */
    function mailMake($stockCountByCategoryArr, $cleanedStockPriceArr, $targetStockDate) {

        // 문서에 필요한 날짜 변수
        $todayFormat = Date('Y-m-d');

        // 인라인 스타일 코드 간소화
        $table_td = "text-align: center; font-size: 14px; padding: 7px 0;";
        $value_td = "font-size: 14px; padding: 7px 0 7px 10px;";
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
                <h4 style=\"{$fonts} margin-bottom: 9px;\">메쎄이상</h4>
                <h1 style=\"{$fonts} margin-bottom: 9px;\">Daily Report</h1>
                <h4 style=\"{$fonts} margin-bottom: 9px;\"> {$todayFormat}</h4>
            </header>
            <hr style=\"margin: 0 0 2px;\">
            <hr style=\"margin: 0 0 4px;\">

            <div style=\"display: flex; justify-content: space-between;\">
                <h4 style=\"{$fonts} margin-bottom: 4px;\">[주요지수 변동현황]</h4>
                <h4 style=\"{$fonts} margin-bottom: 4px;\">
                    {$targetStockDate->format('(m월 d일 종가 기준)')}
                </h4>
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
        
        // 현재 카테고리에서 몇 번째 행인지
        $currentCount = 1;
        
        foreach ($cleanedStockPriceArr as $data) {

            // 현재 카테고리에 해당하는 stock 개수
            $stockCount = $stockCountByCategoryArr[$data['stockCategoryId']];

            // 현재 카테고리의 마지막 행이라면 tr에 스타일 적용
            if ($currentCount == $stockCount) {
                $mailBody .= "<tr style=\"border-bottom: 1px solid #aaa;\">";
            // 그 외
            } else {
                $mailBody .= "<tr>";
            }
            
            // 해당 카테고리의 첫 행이라면 th 추가
            if ($currentCount == 1) {
                $mailBody .= "<th rowspan=\"{$stockCount}\" style=\"{$fonts} font-size: 14px;\">{$data['categoryName']}</th>";
            }

            $mailBody .= "<td style=\"{$fonts} {$table_td}\">{$data['stockName']}</td>";
            $mailBody .= "<td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($data['d0Value'], false, $data['categoryName']) . "</td>";

            $mailBody .= "<td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($data['d0Value'] - $data['d1Value']) . "\"> " 
                            . $this->valueFormat($data['d0Value'] - $data['d1Value'], true) . "</td>";

            $mailBody .= "<td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($data['d0Value'] - $data['d5Value']) . "\"> " 
                            . $this->valueFormat($data['d0Value'] - $data['d5Value'], true) . "</td>";
        
            $mailBody .= "<td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($data['d0Value'] - $data['d20Value']) . "\"> " 
                            . $this->valueFormat($data['d0Value'] - $data['d20Value'], true) . "</td>";

            $mailBody .= "<td style=\"{$fonts} {$table_td}\">" . $data['remarks'];

            $mailBody .= "</td>";
            $mailBody .= "</tr>";
            
            // 현재 카테고리의 마지막 행이라면 반복 횟수 초기화
            if ($currentCount == $stockCount) {
                $currentCount = 1;
            // 그 외
            } else {
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
    
    // 증감값에 절댓값 처리 + 포맷팅 + 특수문자 처리
    // 카테고리에 따라 종가 값에 %나 $를 붙여줘야 하는 경우가 있음
    // delete/insert하면서 id가 바뀔 때를 대비해서 Name으로 인자를 받음 (기본값이 있어서 필요한 셀에서만 입력)
    function valueFormat($value, $hasSymbol, $categoryName="") {
        $result = "";
        
        if ($value != null && $value != 0) {

            if ($value > 0) {
                if ($hasSymbol == true) {
                    $result .= "▲&nbsp;";
                }
                $result .= number_format($value, 2);
    
            } else if ($value < 0) {
                if ($hasSymbol == true) {
                    $result .= "▼&nbsp;";
                }
                $result .= number_format(abs($value), 2);
            }

            // categoryName이 특정 카테고리라면 %나 $를 추가함
            if (str_replace(" ", "", $categoryName) == "주요금리") {
                $result .= "%";
            } else if (str_replace(" ", "", $categoryName) == "주요원자재") {
                $result = "$" . $result;
            }

        } else {
            $result .= "&nbsp-&nbsp";
        }
        return $result;
    }
    
    // 날짜를 계산해서 int형으로 바꿔서 반환
    function dateFormat($minusDay) {
        $today = new DateTime();
        $targetdate = $today->modify("-{$minusDay} day");
        return intval($targetdate->format('Ymd'));
    }

}
    ?>