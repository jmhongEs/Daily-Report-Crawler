<?php

namespace App\DailyReportCrawler;

use DateTime;

class MailMaker{

    // todo : D-1, D-5, D-20 데이터는 DB에서 가져와서 매개변수로 추가로 받아야함
    /**
     * 
     * @param $currentArray : CrawlData 클래스 이용해서 현재 데이터 가져오기 
     */
    function mailMake($currentArray) {

        $todayFormat = Date('Y-m-d');

        // 하루 전날
        // todo : DateTime 클래스 왜 작동 안하는지 알아보기
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
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($currentArray[2]) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(111) . "\"> " . $this->changeSymbolByUpDown(1) . "&nbsp" . $this->valueFormat(1) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(5) . "\"> " . $this->changeSymbolByUpDown(5) . "&nbsp" . $this->valueFormat(5) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(20) . "\"> " . $this->changeSymbolByUpDown(20) . "&nbsp" . $this->valueFormat(20) . "</td>
                        <td style=\"{$fonts} {$table_td}\"></td>
                    </tr>


                    <tr>
                        <td style=\"{$fonts} {$table_td}\">KOSDAQ</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($currentArray[3]) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(1) . "\"> " . $this->changeSymbolByUpDown(1) . "&nbsp" . $this->valueFormat(1) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(5) . "\"> " . $this->changeSymbolByUpDown(5) . "&nbsp" . $this->valueFormat(5) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(20) . "\"> " . $this->changeSymbolByUpDown(20) . "&nbsp" . $this->valueFormat(20) . "</td>
                        <td style=\"{$fonts} {$table_td}\"></td>
                    </tr>
                    <tr>
                        <td style=\"{$fonts} {$table_td}\">DOW JONES</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($currentArray[4]) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(1) . "\"> " . $this->changeSymbolByUpDown(1) . "&nbsp" . $this->valueFormat(1) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(5) . "\"> " . $this->changeSymbolByUpDown(5) . "&nbsp" . $this->valueFormat(5) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(20) . "\"> " . $this->changeSymbolByUpDown(20) . "&nbsp" . $this->valueFormat(20) . "</td>
                        <td style=\"{$fonts} {$table_td}\"></td>
                    </tr>
                    <tr>
                        <td style=\"{$fonts} {$table_td}\">나스닥</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($currentArray[5]) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(1) . "\"> " . $this->changeSymbolByUpDown(1) . "&nbsp" . $this->valueFormat(1) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(5) . "\"> " . $this->changeSymbolByUpDown(5) . "&nbsp" . $this->valueFormat(5) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(20) . "\"> " . $this->changeSymbolByUpDown(20) . "&nbsp" . $this->valueFormat(20) . "</td>
                        <td style=\"{$fonts} {$table_td}\"></td>
                    </tr>
                    <tr style=\"border-bottom: 1px solid #aaa;\">
                        <td style=\"{$fonts} {$table_td}\">상해종합</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($currentArray[6]) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(1) . "\"> " . $this->changeSymbolByUpDown(1) . "&nbsp" . $this->valueFormat(1) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(5) . "\"> " . $this->changeSymbolByUpDown(5) . "&nbsp" . $this->valueFormat(5) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(20) . "\"> " . $this->changeSymbolByUpDown(20) . "&nbsp" . $this->valueFormat(20) . "</td>
                        <td style=\"{$fonts} {$table_td}\"></td>
                    </tr>

                    <tr>
                        <th rowspan=\"5\" style=\"{$fonts} font-size: 14px;\">주요주가</th>
                        <td style=\"{$fonts} {$table_td}\">메쎄이상</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($currentArray[7]) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(1) . "\"> " . $this->changeSymbolByUpDown(1) . "&nbsp" . $this->valueFormat(1) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(5) . "\"> " . $this->changeSymbolByUpDown(5) . "&nbsp" . $this->valueFormat(5) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(20) . "\"> " . $this->changeSymbolByUpDown(20) . "&nbsp" . $this->valueFormat(20) . "</td>
                        <td style=\"{$fonts} {$table_td}\">KOSDAQ</td>
                    </tr>
                    <tr>
                        <td style=\"{$fonts} {$table_td}\">이상네트웍스</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($currentArray[8]) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(1) . "\"> " . $this->changeSymbolByUpDown(1) . "&nbsp" . $this->valueFormat(1) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(5) . "\"> " . $this->changeSymbolByUpDown(5) . "&nbsp" . $this->valueFormat(5) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(20) . "\"> " . $this->changeSymbolByUpDown(20) . "&nbsp" . $this->valueFormat(20) . "</td>
                        <td style=\"{$fonts} {$table_td}\">KOSDAQ</td>
                    </tr>
                    <tr>
                        <td style=\"{$fonts} {$table_td}\">황금에스티</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($currentArray[9]) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(1) . "\"> " . $this->changeSymbolByUpDown(1) . "&nbsp" . $this->valueFormat(1) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(5) . "\"> " . $this->changeSymbolByUpDown(5) . "&nbsp" . $this->valueFormat(5) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(20) . "\"> " . $this->changeSymbolByUpDown(20) . "&nbsp" . $this->valueFormat(20) . "</td>
                        <td style=\"{$fonts} {$table_td}\">KOSPI</td>
                    </tr>
                    <tr>
                        <td style=\"{$fonts} {$table_td}\">유에스티</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($currentArray[10]) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(1) . "\"> " . $this->changeSymbolByUpDown(1) . "&nbsp" . $this->valueFormat(1) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(5) . "\"> " . $this->changeSymbolByUpDown(5) . "&nbsp" . $this->valueFormat(5) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(20) . "\"> " . $this->changeSymbolByUpDown(20) . "&nbsp" . $this->valueFormat(20) . "</td>
                        <td style=\"{$fonts} {$table_td}\">KOSDAQ</td>
                    </tr>
                    <tr style=\"border-bottom: 1px solid #aaa;\">
                        <td style=\"{$fonts} {$table_td}\">길교이앤씨</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($currentArray[11]) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(1) . "\"> " . $this->changeSymbolByUpDown(1) . "&nbsp" . $this->valueFormat(1) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(5) . "\"> " . $this->changeSymbolByUpDown(5) . "&nbsp" . $this->valueFormat(5) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(20) . "\"> " . $this->changeSymbolByUpDown(20) . "&nbsp" . $this->valueFormat(20) . "</td>
                        <td style=\"{$fonts} {$table_td}\">KONEX</td>
                    </tr>

                    <tr>
                        <th rowspan=\"5\" style=\"{$fonts} font-size: 14px;\">주요금리</th>
                        <td style=\"{$fonts} {$table_td}\">국고채 3년</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($currentArray[12]) . "%</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(1) . "\"> " . $this->changeSymbolByUpDown(1) . "&nbsp" . $this->valueFormat(1) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(5) . "\"> " . $this->changeSymbolByUpDown(5) . "&nbsp" . $this->valueFormat(5) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(20) . "\"> " . $this->changeSymbolByUpDown(20) . "&nbsp" . $this->valueFormat(20) . "</td>
                        <td style=\"{$fonts} {$table_td}\"></td>
                    </tr>
                    <tr>
                        <td style=\"{$fonts} {$table_td}\">국고채 10년</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($currentArray[13]) . "%</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(1) . "\"> " . $this->changeSymbolByUpDown(1) . "&nbsp" . $this->valueFormat(1) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(-5) . "\"> " . $this->changeSymbolByUpDown(-5) . "&nbsp" . $this->valueFormat(-5) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(20) . "\"> " . $this->changeSymbolByUpDown(20) . "&nbsp" . $this->valueFormat(20) . "</td>
                        <td style=\"{$fonts} {$table_td}\"></td>
                    </tr>
                    <tr>
                        <td style=\"{$fonts} {$table_td}\">한국 CD 91일</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($currentArray[14]) . "%</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(1) . "\"> " . $this->changeSymbolByUpDown(1) . "&nbsp" . $this->valueFormat(1) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(5) . "\"> " . $this->changeSymbolByUpDown(5) . "&nbsp" . $this->valueFormat(5) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(20) . "\"> " . $this->changeSymbolByUpDown(20) . "&nbsp" . $this->valueFormat(20) . "</td>
                        <td style=\"{$fonts} {$table_td}\"></td>
                    </tr>
                    <tr>
                        <td style=\"{$fonts} {$table_td}\">미국채 2년</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($currentArray[15]) . "%</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(1) . "\"> " . $this->changeSymbolByUpDown(1) . "&nbsp" . $this->valueFormat(1) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(5) . "\"> " . $this->changeSymbolByUpDown(5) . "&nbsp" . $this->valueFormat(5) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(20) . "\"> " . $this->changeSymbolByUpDown(20) . "&nbsp" . $this->valueFormat(20) . "</td>
                        <td style=\"{$fonts} {$table_td}\"></td>
                    </tr>
                    <tr style=\"border-bottom: 1px solid #aaa;\">
                        <td style=\"{$fonts} {$table_td}\">미국채 10년</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($currentArray[16]) . "%</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(1) . "\"> " . $this->changeSymbolByUpDown(1) . "&nbsp" . $this->valueFormat(1) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(5) . "\"> " . $this->changeSymbolByUpDown(5) . "&nbsp" . $this->valueFormat(5) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(20) . "\"> " . $this->changeSymbolByUpDown(20) . "&nbsp" . $this->valueFormat(20) . "</td>
                        <td style=\"{$fonts} {$table_td}\"></td>
                    </tr>
                    <tr>
                        <th rowspan=\"4\" style=\"{$fonts} font-size: 14px;\">환율</th>
                        <td style=\"{$fonts} {$table_td}\">달러/원 USDKRW</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($currentArray[17]) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(1) . "\"> " . $this->changeSymbolByUpDown(1) . "&nbsp" . $this->valueFormat(1) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(5) . "\"> " . $this->changeSymbolByUpDown(5) . "&nbsp" . $this->valueFormat(5) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(20) . "\"> " . $this->changeSymbolByUpDown(20) . "&nbsp" . $this->valueFormat(20) . "</td>
                        <td style=\"{$fonts} {$table_td}\">고시환율</td>
                    </tr>
                    <tr>
                        <td style=\"{$fonts} {$table_td}\">유로/달러 EURUSD</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($currentArray[18]) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(1) . "\"> " . $this->changeSymbolByUpDown(1) . "&nbsp" . $this->valueFormat(1) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(5) . "\"> " . $this->changeSymbolByUpDown(5) . "&nbsp" . $this->valueFormat(5) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(20) . "\"> " . $this->changeSymbolByUpDown(20) . "&nbsp" . $this->valueFormat(20) . "</td>
                        <td style=\"{$fonts} {$table_td}\"></td>
                    </tr>
                    <tr>
                        <td style=\"{$fonts} {$table_td}\">달러/위안 USDCNY</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($currentArray[19]) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(1) . "\"> " . $this->changeSymbolByUpDown(1) . "&nbsp" . $this->valueFormat(1) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(5) . "\"> " . $this->changeSymbolByUpDown(5) . "&nbsp" . $this->valueFormat(5) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(20) . "\"> " . $this->changeSymbolByUpDown(20) . "&nbsp" . $this->valueFormat(20) . "</td>
                        <td style=\"{$fonts} {$table_td}\"></td>
                    </tr>
                    <tr style=\"border-bottom: 1px solid #aaa;\">
                        <td style=\"{$fonts} {$table_td}\">100엔/원 JPYKRW</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($currentArray[20]) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(1) . "\"> " . $this->changeSymbolByUpDown(1) . "&nbsp" . $this->valueFormat(1) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(5) . "\"> " . $this->changeSymbolByUpDown(5) . "&nbsp" . $this->valueFormat(5) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(20) . "\"> " . $this->changeSymbolByUpDown(20) . "&nbsp" . $this->valueFormat(20) . "</td>
                        <td style=\"{$fonts} {$table_td}\">100¥ 기준</td>
                    </tr>
                    <tr>
                        <th rowspan=\"3\" style=\"font-size: 14px;\">주요원자재</th>
                        <td style=\"{$fonts} {$table_td}\">국제유가 (WTI)</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($currentArray[21]) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(1) . "\"> " . $this->changeSymbolByUpDown(1) . "&nbsp" . $this->valueFormat(1) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(5) . "\"> " . $this->changeSymbolByUpDown(5) . "&nbsp" . $this->valueFormat(5) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(20) . "\"> " . $this->changeSymbolByUpDown(20) . "&nbsp" . $this->valueFormat(20) . "</td>
                        <td style=\"{$fonts} {$table_td}\">단위 : 배럴</td>
                    </tr>
                    <tr>
                        <td style=\"{$fonts} {$table_td}\">금</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($currentArray[22]) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(1) . "\"> " . $this->changeSymbolByUpDown(1) . "&nbsp" . $this->valueFormat(1) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(5) . "\"> " . $this->changeSymbolByUpDown(5) . "&nbsp" . $this->valueFormat(5) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(20) . "\"> " . $this->changeSymbolByUpDown(20) . "&nbsp" . $this->valueFormat(20) . "</td>
                        <td style=\"{$fonts} {$table_td}\">단위 : 트로이온스</td>
                    </tr>
                    <tr style=\"{$fonts} border-bottom: 2px solid #555;\">
                        <td style=\"{$fonts} {$table_td}\">니켈</td>
                        <td style=\"{$fonts} {$table_td}\"> " . $this->valueFormat($currentArray[23]) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(1) . "\"> " . $this->changeSymbolByUpDown(1) . "&nbsp" . $this->valueFormat(1) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(5) . "\"> " . $this->changeSymbolByUpDown(5) . "&nbsp" . $this->valueFormat(5) . "</td>
                        <td style=\"{$fonts} {$value_td} " . $this->changeStyleByUpDown(20) . "\"> " . $this->changeSymbolByUpDown(20) . "&nbsp" . $this->valueFormat(20) . "</td>
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
        return $value;
    }
    
}
    ?>