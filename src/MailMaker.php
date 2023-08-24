<?php

namespace App\DailyReportCrawler;
use App\DailyReportCrawler\DBConnect;

use DateTime;

class MailMaker {

    // 오늘 기준으로 -1(기준종가), -2(D-1종가), -6(D-5종가), -21(D-20종가) 필요
    function mailMake($stockCountByCategoryArr, $stockPriceDtoArr) {
        // 문서에 필요한 날짜 변수
        $todayFormat = Date('Y-m-d');
        // 하루 전날
        $today = new DateTime();
        $yesterday = $today->modify('-1 day');

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
        
        // 현재 카테고리에서 몇 번째 행인지
        $currentCount = 1;
        
        foreach ($stockPriceDtoArr as $dto) {

            // 현재 카테고리에 해당하는 stock 개수
            $stockCount = $stockCountByCategoryArr[$dto->stockCategoryId];

            // 현재 카테고리의 마지막 행이라면 tr에 스타일 적용
            if ($currentCount == $stockCount) {
                $mailBody .= "<tr style=\"border-bottom: 1px solid #aaa;\">";
            // 그 외
            } else {
                $mailBody .= "<tr>";
            }
            
            // 해당 카테고리의 첫 행이라면 th 추가
            if ($currentCount == 1) {
                $mailBody .= "<th rowspan=\"{$stockCount}\" style=\"{$fonts} font-size: 14px;\">{$dto->categoryName}</th>";
            }

            $mailBody .= "<td style=\"{$fonts} {$table_td}\">{$dto->stockName}</td>";
            $mailBody .= "<td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($dto->d0Value, false, $dto->categoryName) . "</td>";

            $mailBody .= "<td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dto->d1Diff) . "\"> " 
                            . $this->valueFormat($dto->d1Diff, true) . "</td>";

            $mailBody .= "<td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dto->d5Diff) . "\"> " 
                            . $this->valueFormat($dto->d5Diff, true) . "</td>";
        
            $mailBody .= "<td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dto->d20Diff) . "\"> " 
                            . $this->valueFormat($dto->d20Diff, true) . "</td>";

            $mailBody .= "<td style=\"{$fonts} {$table_td}\">$dto->remarks";


            // 같은 날짜에 해당 항목에만 데이터가 없는 경우 비고 란에 내용 추가
            // 기준 종가가 없다면 모든 데이터가 null이므로 기준일 휴장만 기입
            if ($dto->d0Value === null) {
                if ($dto->remarks != "") {
                    $mailBody .= "<br>";
                }
                $mailBody .= "<span style=\"color: gray; font-size: 12px\">기준일 휴장</span>";
            } else {
                if ($dto->remarks === null) {
                    $mailBody .= "<br>";
                }
                // 여러 일자에 휴장인 경우도 있을 수 있으므로 else if를 쓰지 않음
                if ($dto->d1Diff === null) {
                    $mailBody .= "<span style=\"color: gray\">D-1 휴장</span>";
                }
                if ($dto->d5Diff === null) {
                    $mailBody .= "<span style=\"color: gray\">D-5 휴장</span>";
                }
                if ($dto->d20Diff === null) {
                    $mailBody .= "<span style=\"color: gray\">D-20 휴장</span>";
                }
            }
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
    // id가 바뀔 때를 대비해서 Name으로 인자를 받음 (기본값이 있어서 필요한 셀에서만 입력)
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