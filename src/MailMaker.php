<?php

namespace App\DailyReportCrawler;

use DateTime;

class MailMaker{

    // todo : D-1, D-5, D-20 데이터는 DB에서 가져와서 매개변수로 추가로 받아야함
    function mailMake($dataArr1, $dataArr2, $dataArr3, $dataArr4) {

        // 문서에 필요한 날짜 변수
        $todayFormat = Date('Y-m-d');
        // 하루 전날
        $today = new DateTime();
        $yesterday = $today->modify('-1 day');

        // 인라인 스타일 코드 간소화
        $table_td = "text-align: center; font-size: 14px; padding: 7px 0;";
        $value_td = "font-size: 14px; padding: 7px 0 7px 12px;";
        $fonts = "font-family: AppleSDGothic, malgun gothic, nanum gothic, Noto Sans KR, sans-serif;";

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
                <tbody>
                    <tr>
                        <th rowspan=\"5\" style=\"{$fonts} font-size: 14px;\">주가지수</th>
                        <td style=\"{$fonts} {$table_td}\">KOSPI</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($dataArr1[0]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[0]->stockPrice - $dataArr2[0]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[0]->stockPrice - $dataArr2[0]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[0]->stockPrice - $dataArr2[0]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[0]->stockPrice - $dataArr3[0]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[0]->stockPrice - $dataArr3[0]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[0]->stockPrice - $dataArr3[0]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[0]->stockPrice - $dataArr4[0]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[0]->stockPrice - $dataArr4[0]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[0]->stockPrice - $dataArr4[0]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$table_td}\"></td>
                    </tr>


                    <tr>
                        <td style=\"{$fonts} {$table_td}\">KOSDAQ</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($dataArr1[1]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[1]->stockPrice - $dataArr2[1]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[1]->stockPrice - $dataArr2[1]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[1]->stockPrice - $dataArr2[1]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[1]->stockPrice - $dataArr3[1]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[1]->stockPrice - $dataArr3[1]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[1]->stockPrice - $dataArr3[1]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[1]->stockPrice - $dataArr4[1]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[1]->stockPrice - $dataArr4[1]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[1]->stockPrice - $dataArr4[1]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$table_td}\"></td>
                    </tr>
                    <tr>
                        <td style=\"{$fonts} {$table_td}\">DOW JONES</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($dataArr1[2]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[2]->stockPrice - $dataArr2[2]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[2]->stockPrice - $dataArr2[2]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[2]->stockPrice - $dataArr2[2]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[2]->stockPrice - $dataArr3[2]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[2]->stockPrice - $dataArr3[2]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[2]->stockPrice - $dataArr3[2]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[2]->stockPrice - $dataArr4[2]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[2]->stockPrice - $dataArr4[2]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[2]->stockPrice - $dataArr4[2]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$table_td}\"></td>
                    </tr>
                    <tr>
                        <td style=\"{$fonts} {$table_td}\">나스닥</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($dataArr1[3]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[3]->stockPrice - $dataArr2[3]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[3]->stockPrice - $dataArr2[3]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[3]->stockPrice - $dataArr2[3]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[3]->stockPrice - $dataArr3[3]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[3]->stockPrice - $dataArr3[3]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[3]->stockPrice - $dataArr3[3]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[3]->stockPrice - $dataArr4[3]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[3]->stockPrice - $dataArr4[3]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[3]->stockPrice - $dataArr4[3]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$table_td}\"></td>
                    </tr>
                    <tr style=\"border-bottom: 1px solid #aaa;\">
                        <td style=\"{$fonts} {$table_td}\">상해종합</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($dataArr1[4]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[4]->stockPrice - $dataArr2[4]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[4]->stockPrice - $dataArr2[4]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[4]->stockPrice - $dataArr2[4]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[4]->stockPrice - $dataArr3[4]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[4]->stockPrice - $dataArr3[4]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[4]->stockPrice - $dataArr3[4]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[4]->stockPrice - $dataArr4[4]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[4]->stockPrice - $dataArr4[4]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[4]->stockPrice - $dataArr4[4]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$table_td}\"></td>
                    </tr>

                    <tr>
                        <th rowspan=\"5\" style=\"{$fonts} font-size: 14px;\">주요주가</th>
                        <td style=\"{$fonts} {$table_td}\">메쎄이상</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($dataArr1[5]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[5]->stockPrice - $dataArr2[5]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[5]->stockPrice - $dataArr2[5]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[5]->stockPrice - $dataArr2[5]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[5]->stockPrice - $dataArr3[5]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[5]->stockPrice - $dataArr3[5]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[5]->stockPrice - $dataArr3[5]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[5]->stockPrice - $dataArr4[5]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[5]->stockPrice - $dataArr4[5]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[5]->stockPrice - $dataArr4[5]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$table_td}\">KOSDAQ</td>
                    </tr>
                    <tr>
                        <td style=\"{$fonts} {$table_td}\">이상네트웍스</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($dataArr1[6]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[6]->stockPrice - $dataArr2[6]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[6]->stockPrice - $dataArr2[6]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[6]->stockPrice - $dataArr2[6]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[6]->stockPrice - $dataArr3[6]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[6]->stockPrice - $dataArr3[6]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[6]->stockPrice - $dataArr3[6]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[6]->stockPrice - $dataArr4[6]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[6]->stockPrice - $dataArr4[6]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[6]->stockPrice - $dataArr4[6]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$table_td}\">KOSDAQ</td>
                    </tr>
                    <tr>
                        <td style=\"{$fonts} {$table_td}\">황금에스티</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($dataArr1[7]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[7]->stockPrice - $dataArr2[7]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[7]->stockPrice - $dataArr2[7]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[7]->stockPrice - $dataArr2[7]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[7]->stockPrice - $dataArr3[7]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[7]->stockPrice - $dataArr3[7]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[7]->stockPrice - $dataArr3[7]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[7]->stockPrice - $dataArr4[7]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[7]->stockPrice - $dataArr4[7]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[7]->stockPrice - $dataArr4[7]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$table_td}\">KOSPI</td>
                    </tr>
                    <tr>
                        <td style=\"{$fonts} {$table_td}\">유에스티</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($dataArr1[8]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[8]->stockPrice - $dataArr2[8]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[8]->stockPrice - $dataArr2[8]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[8]->stockPrice - $dataArr2[8]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[8]->stockPrice - $dataArr3[8]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[8]->stockPrice - $dataArr3[8]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[8]->stockPrice - $dataArr3[8]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[8]->stockPrice - $dataArr4[8]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[8]->stockPrice - $dataArr4[8]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[8]->stockPrice - $dataArr4[8]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$table_td}\">KOSDAQ</td>
                    </tr>
                    <tr style=\"border-bottom: 1px solid #aaa;\">
                        <td style=\"{$fonts} {$table_td}\">길교이앤씨</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($dataArr1[9]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[9]->stockPrice - $dataArr2[9]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[9]->stockPrice - $dataArr2[9]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[9]->stockPrice - $dataArr2[9]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[9]->stockPrice - $dataArr3[9]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[9]->stockPrice - $dataArr3[9]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[9]->stockPrice - $dataArr3[9]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[9]->stockPrice - $dataArr4[9]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[9]->stockPrice - $dataArr4[9]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[9]->stockPrice - $dataArr4[9]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$table_td}\">KONEX</td>
                    </tr>

                    <tr>
                        <th rowspan=\"5\" style=\"{$fonts} font-size: 14px;\">주요금리</th>
                        <td style=\"{$fonts} {$table_td}\">국고채 3년</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($dataArr1[10]->stockPrice) . "%</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[10]->stockPrice - $dataArr2[10]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[10]->stockPrice - $dataArr2[10]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[10]->stockPrice - $dataArr2[10]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[10]->stockPrice - $dataArr3[10]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[10]->stockPrice - $dataArr3[10]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[10]->stockPrice - $dataArr3[10]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[10]->stockPrice - $dataArr4[10]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[10]->stockPrice - $dataArr4[10]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[10]->stockPrice - $dataArr4[10]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$table_td}\"></td>
                    </tr>
                    <tr>
                        <td style=\"{$fonts} {$table_td}\">국고채 10년</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($dataArr1[11]->stockPrice) . "%</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[11]->stockPrice - $dataArr2[11]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[11]->stockPrice - $dataArr2[11]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[11]->stockPrice - $dataArr2[11]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[11]->stockPrice - $dataArr3[11]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[11]->stockPrice - $dataArr3[11]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[11]->stockPrice - $dataArr3[11]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[11]->stockPrice - $dataArr4[11]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[11]->stockPrice - $dataArr4[11]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[11]->stockPrice - $dataArr4[11]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$table_td}\"></td>
                    </tr>
                    <tr>
                        <td style=\"{$fonts} {$table_td}\">한국 CD 91일</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($dataArr1[12]->stockPrice) . "%</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[12]->stockPrice - $dataArr2[12]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[12]->stockPrice - $dataArr2[12]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[12]->stockPrice - $dataArr2[12]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[12]->stockPrice - $dataArr3[12]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[12]->stockPrice - $dataArr3[12]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[12]->stockPrice - $dataArr3[12]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[12]->stockPrice - $dataArr4[12]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[12]->stockPrice - $dataArr4[12]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[12]->stockPrice - $dataArr4[12]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$table_td}\"></td>
                    </tr>
                    <tr>
                        <td style=\"{$fonts} {$table_td}\">미국채 2년</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($dataArr1[13]->stockPrice) . "%</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[13]->stockPrice - $dataArr2[13]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[13]->stockPrice - $dataArr2[13]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[13]->stockPrice - $dataArr2[13]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[13]->stockPrice - $dataArr3[13]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[13]->stockPrice - $dataArr3[13]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[13]->stockPrice - $dataArr3[13]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[13]->stockPrice - $dataArr4[13]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[13]->stockPrice - $dataArr4[13]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[13]->stockPrice - $dataArr4[13]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$table_td}\"></td>
                    </tr>
                    <tr style=\"border-bottom: 1px solid #aaa;\">
                        <td style=\"{$fonts} {$table_td}\">미국채 10년</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($dataArr1[14]->stockPrice) . "%</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[14]->stockPrice - $dataArr2[14]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[14]->stockPrice - $dataArr2[14]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[14]->stockPrice - $dataArr2[14]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[14]->stockPrice - $dataArr3[14]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[14]->stockPrice - $dataArr3[14]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[14]->stockPrice - $dataArr3[14]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[14]->stockPrice - $dataArr4[14]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[14]->stockPrice - $dataArr4[14]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[14]->stockPrice - $dataArr4[14]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$table_td}\"></td>
                    </tr>
                    <tr>
                        <th rowspan=\"4\" style=\"{$fonts} font-size: 14px;\">환율</th>
                        <td style=\"{$fonts} {$table_td}\">달러/원 USDKRW</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($dataArr1[15]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[15]->stockPrice - $dataArr2[15]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[15]->stockPrice - $dataArr2[15]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[15]->stockPrice - $dataArr2[15]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[15]->stockPrice - $dataArr3[15]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[15]->stockPrice - $dataArr3[15]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[15]->stockPrice - $dataArr3[15]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[15]->stockPrice - $dataArr4[15]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[15]->stockPrice - $dataArr4[15]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[15]->stockPrice - $dataArr4[15]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$table_td}\">고시환율</td>
                    </tr>
                    <tr>
                        <td style=\"{$fonts} {$table_td}\">유로/달러 EURUSD</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($dataArr1[16]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[16]->stockPrice - $dataArr2[16]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[16]->stockPrice - $dataArr2[16]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[16]->stockPrice - $dataArr2[16]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[16]->stockPrice - $dataArr3[16]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[16]->stockPrice - $dataArr3[16]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[16]->stockPrice - $dataArr3[16]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[16]->stockPrice - $dataArr4[16]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[16]->stockPrice - $dataArr4[16]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[16]->stockPrice - $dataArr4[16]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$table_td}\"></td>
                    </tr>
                    <tr>
                        <td style=\"{$fonts} {$table_td}\">달러/위안 USDCNY</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($dataArr1[17]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[17]->stockPrice - $dataArr2[17]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[17]->stockPrice - $dataArr2[17]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[17]->stockPrice - $dataArr2[17]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[17]->stockPrice - $dataArr3[17]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[17]->stockPrice - $dataArr3[17]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[17]->stockPrice - $dataArr3[17]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[17]->stockPrice - $dataArr4[17]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[17]->stockPrice - $dataArr4[17]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[17]->stockPrice - $dataArr4[17]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$table_td}\"></td>
                    </tr>
                    <tr style=\"border-bottom: 1px solid #aaa;\">
                        <td style=\"{$fonts} {$table_td}\">100엔/원 JPYKRW</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($dataArr1[18]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[18]->stockPrice - $dataArr2[18]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[18]->stockPrice - $dataArr2[18]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[18]->stockPrice - $dataArr2[18]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[18]->stockPrice - $dataArr3[18]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[18]->stockPrice - $dataArr3[18]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[18]->stockPrice - $dataArr3[18]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[18]->stockPrice - $dataArr4[18]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[18]->stockPrice - $dataArr4[18]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[18]->stockPrice - $dataArr4[18]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$table_td}\">100¥ 기준</td>
                    </tr>
                    <tr>
                        <th rowspan=\"3\" style=\"font-size: 14px;\">주요원자재</th>
                        <td style=\"{$fonts} {$table_td}\">국제유가 (WTI)</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($dataArr1[19]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[19]->stockPrice - $dataArr2[19]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[19]->stockPrice - $dataArr2[19]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[19]->stockPrice - $dataArr2[19]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[19]->stockPrice - $dataArr3[19]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[19]->stockPrice - $dataArr3[19]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[19]->stockPrice - $dataArr3[19]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[19]->stockPrice - $dataArr4[19]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[19]->stockPrice - $dataArr4[19]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[19]->stockPrice - $dataArr4[19]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$table_td}\">단위 : 배럴</td>
                    </tr>
                    <tr>
                        <td style=\"{$fonts} {$table_td}\">금</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($dataArr1[20]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[20]->stockPrice - $dataArr2[20]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[20]->stockPrice - $dataArr2[20]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[20]->stockPrice - $dataArr2[20]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[20]->stockPrice - $dataArr3[20]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[20]->stockPrice - $dataArr3[20]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[20]->stockPrice - $dataArr3[20]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[20]->stockPrice - $dataArr4[20]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[20]->stockPrice - $dataArr4[20]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[20]->stockPrice - $dataArr4[20]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$table_td}\">단위 : 트로이온스</td>
                    </tr>
                    <tr style=\"{$fonts} border-bottom: 2px solid #555;\">
                        <td style=\"{$fonts} {$table_td}\">니켈</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($dataArr1[21]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[21]->stockPrice - $dataArr2[21]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[21]->stockPrice - $dataArr2[21]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[21]->stockPrice - $dataArr2[21]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[21]->stockPrice - $dataArr3[21]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[21]->stockPrice - $dataArr3[21]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[21]->stockPrice - $dataArr3[21]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown($dataArr1[21]->stockPrice - $dataArr4[21]->stockPrice) . "\"> " . $this->changeSymbolByUpDown($dataArr1[21]->stockPrice - $dataArr4[21]->stockPrice) . "&nbsp" . $this->valueFormat($dataArr1[21]->stockPrice - $dataArr4[21]->stockPrice) . "</td>
                        <td style=\"{$fonts} {$table_td}\">단위 : TON</td>
                    </tr>
                </tbody>
            </table>
        </body>

        </html>";

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
        return number_format(abs($value), 2);
    }
    
}
    ?>