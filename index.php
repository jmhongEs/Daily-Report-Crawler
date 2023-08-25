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

//$crawlData->forCheckPath();

// $crawlData->crawldata();

// 데이터 모으기
// $floatResultArray= $crawlData->crawldata();

// DB에 삽입
// $dbConnect->dataInsert($floatResultArray);

// 데이터 HTML 문서에 삽입 

// 기준 날짜 (오늘날짜 - 1)
// 만약 해당 날짜가 토/일이라면 그 이전 평일을 찾아야 함
// TODO : 휴일 체크는 나중에 API 받아서 활용

// 현재 스크래핑 방식 변경 중이라 20230823 데이터가 없어서 임의의 날짜로 테스트중 
$today = new DateTime('2023-08-23');
// $today = new DateTime();

$targetDate = $today->modify('-1 day');

// 일요일이라면
if ($targetDate->format('w') == 0) {
    $targetDate = $targetDate->modify('-2 day');
    // 토요일이라면
} else if ($targetDate->format('w') == 6) {
    $targetDate = $targetDate->modify('-1 day');
}

$targetDateInt = intval($targetDate->format('Ymd'));

// $targetDate에 이미 메일 발송 성공한 로그가 남아있다면 아래 코드를 실행하지 않음
// TODO : SELECT 추가

// GBN : D0, D1, D5, D20 구분
// 지수별 종가 데이터
$stockPriceDtoArr = $dbConnect->selectStockPrice();

// 카테고리의 첫 행, 마지막 행임을 알아야 함
// 카테고리별 stock 개수
$stockCountByCategoryArr = $dbConnect->selectStockCountByCategory();

$mailBody = $mailMaker->mailMake($stockCountByCategoryArr, $stockPriceDtoArr);
echo $mailBody;

exit;

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
    $dbConnect->insertEmailLog($targetDateInt, STATUS_SUCCESS);
    
    // 실패 시 실패 로그를 남김 
} else {
    // email_log 테이블에 로그 저장하기
    $dbConnect->insertEmailLog($targetDateInt, STATUS_FAIL);
    
}

// 커넥션 종료
$dbConnect->closeConnection();