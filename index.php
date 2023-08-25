<?php

require_once 'bootstrap.php';

use App\DailyReportCrawler\CrawlData;
use App\DailyReportCrawler\Mailer;
use App\DailyReportCrawler\MailMaker;
use App\DailyReportCrawler\DBConnect;

$mailer = new Mailer();
$crawlData = new CrawlData();
$mailMaker = new MailMaker();
$dbConnect = new DBConnect();
const STATUS_FAIL = 1;
const STATUS_SUCCESS = 2;

// 데이터 모으기
$floatResultArray= $crawlData->forCheckPath();

// DB에 삽입
$dbConnect->dataInsert($floatResultArray);

// 데이터 HTML 문서에 삽입 
// $targetDate에 이미 메일 발송 성공한 로그가 남아있다면 아래 코드를 실행하지 않음
// TODO : SELECT 추가
// 카테고리의 첫 행, 마지막 행임을 알아야 함
// 카테고리별 stock 개수
$stockCountByCategoryArr = $dbConnect->selectStockCountByCategory();

// GBN : D0, D1, D5, D20 구분
// 지수별 종가 데이터
$stockPriceDtoArr = $dbConnect->selectStockPrice();


// mailMaker에서 사용하기 편한 형태로 정제
// 정제를 index에서 하는 이유는 종가기준일을 아래 배열의 KOSPI에 담겨 있는 d0StockDate로 하기 위함
$cleanedStockPriceArr = [];

// stock_id를 key로 하고 GBN별 종가 지수와 기타 정보들 담기
foreach ($stockPriceDtoArr as $dto) {
    // 공통
    // ??= : 해당 Key에 값이 비어있을 경우에만 값을 할당함
    $cleanedStockPriceArr[$dto->stockId]['stockCategoryId'] ??= $dto->stockCategoryId;
    $cleanedStockPriceArr[$dto->stockId]['categoryName'] ??= $dto->categoryName;
    $cleanedStockPriceArr[$dto->stockId]['stockName'] ??= $dto->stockName;
    $cleanedStockPriceArr[$dto->stockId]['remarks'] ??= $dto->remarks;

    // D-0 (기준 종가)
    if ($dto->gbn == 'D0') {
        $cleanedStockPriceArr[$dto->stockId]['d0Value'] = $dto->stockValue;
        $cleanedStockPriceArr[$dto->stockId]['d0StockDate'] = $dto->stockDate;
        
    // D-1
    } else if ($dto->gbn == 'D1') {
    $cleanedStockPriceArr[$dto->stockId]['d1Value'] = $dto->stockValue;
    $cleanedStockPriceArr[$dto->stockId]['d1StockDate'] = $dto->stockDate;
    
    // D-5
    } else if ($dto->gbn == 'D5') {
    $cleanedStockPriceArr[$dto->stockId]['d5Value'] = $dto->stockValue;
    $cleanedStockPriceArr[$dto->stockId]['d5StockDate'] = $dto->stockDate;
    
    // D-20
    } else if ($dto->gbn == 'D20') {
        $cleanedStockPriceArr[$dto->stockId]['d20Value'] = $dto->stockValue;
        $cleanedStockPriceArr[$dto->stockId]['d20StockDate'] = $dto->stockDate;
    }
}

// key 이름에 따라 정렬
ksort($cleanedStockPriceArr);

// 종가 기준일 (숫자 -> 로그 남길 때 사용, 날짜 -> html 작성 시 사용)
$targetStockDateInt = $cleanedStockPriceArr[1]['d0StockDate'];
$targetStockDate = DateTime::createFromFormat('Ymd', $targetStockDateInt);

$mailBody = $mailMaker->mailMake($stockCountByCategoryArr, $cleanedStockPriceArr, $targetStockDate);
echo $mailBody;


// 개발용, 운영용에 따라 주석 풀기
$emails = explode(';', $_ENV['REPORT_RECIPIENT_EMAIL_DEV']);
// $emails = explode(';', $_ENV['REPORT_RECIPIENT_EMAIL_PROD']);

$subject = '[TEST] ' . date("Y년 m월 d일") . ' 데일리 리포트입니다.';
// $subject = date("Y년 m월 d일") . ' 데일리 리포트입니다.';

// HTML 문서를 Mail Body에 실어서 보내기
$sendResult = $mailer->sendEmail($subject, $emails, $mailBody);

// 메일 발송 성공 시 HTML 문서로 저장
if ($sendResult) {
    
    // 저장할 경로
    $logFolder = __DIR__ . '/log/';
    
    // 로그 파일 이름
    // todo : 테스트 끝나면 test- 문자열 삭제하기
    $fileName = 'test-' . 'log'. date("Y-m-d") . '.html';
    
    // 파일 생성 (동일 이름으로 저장 시 덮어쓰기됨)
    file_put_contents($logFolder . $fileName, $mailBody);
    
    // email_log 테이블에 로그 저장하기
    // status 값(1, 2)은 상수로 선언해서 사용함
    $dbConnect->insertEmailLog($targetStockDateInt, STATUS_SUCCESS);
    
    // 실패 시 실패 로그를 남김 
} else {
    // email_log 테이블에 로그 저장하기
    $dbConnect->insertEmailLog($targetStockDateInt, STATUS_FAIL);
    
}

// 커넥션 종료
$dbConnect->closeConnection();