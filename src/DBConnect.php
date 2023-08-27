<?php

namespace App\DailyReportCrawler;

use mysqli;
use App\DailyReportCrawler\StockPrice;
use App\DailyReportCrawler\StockCategoryDto;
use App\DailyReportCrawler\StockPriceDto;
use mysqli_result;

class DBConnect
{

    private $servername = "54.180.60.132";
    private $username = "es_stock";
    private $password = "f%Gb!3sT%\$Vx1a";
    private $database = "es_stock";
    private $port = 3306;
    private $conn;

    public function __construct()
    {

        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->database, $this->port);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function dataInsert($getArray)
    {
        foreach ($getArray as &$array) {
            $firstElement = $array[0];
            // "." 문자 제거
            $newDate = str_replace(".", "", $firstElement);
            $array[0] = $newDate;
            $secoundElement = $array[1];
            $secoundElement = str_replace(",", "", $secoundElement);
            $array[1] = $secoundElement;
        }
        dump($getArray);

        $sql = "
        SELECT STOCK_ID, MAX(STOCK_DATE) AS STOCK_DATE
        FROM STOCK_PRICE
        GROUP BY STOCK_ID;
        ";

        $selectedArray = $this->conn->query($sql);
        $previouseArray = array();
        while ($row = $selectedArray->fetch_assoc()) {
            $previouseArray[] = $row;
        }
        dump($previouseArray);

        $datesToRemove = array();

        foreach ($previouseArray as $previouseItem) {
            $datesToRemove[] = $previouseItem['STOCK_DATE'];
        }

        dump($datesToRemove);

        $newArray = array();

        $i = 0;
        foreach ($getArray as $key => $item) {
            $stockDate = $item[0];

            if ($stockDate != $datesToRemove[$i]) {
                $newArray[$key] = $item;
            }
            $i += 1;
        }

        dump($newArray);

        $sql = "
            INSERT INTO STOCK_PRICE (STOCK_DATE,STOCK_ID,STOCK_VALUE)
                VALUES
            ";

        foreach ($newArray as $key => $array) {
            $sql .= "('$array[0]',$key,'$array[1]'),";
        }
        // 마지막 쉼표 제거
        $sql = rtrim($sql, ',');

        $sql .= ";";

        $result = $this->conn->query($sql);
        if($result === TRUE){
            $valueToInsert = $this->conn->real_escape_string($getArray[1][0]);
            $sql = "
                INSERT INTO STOCK_DATE_LOG (STOCK_DATE) value($valueToInsert);
            ";
            $this->conn->query($sql);
        }else{
            echo "쿼리 실행 실패: " . $this->conn->error;
        }

    }

    /**
     * @author 서영
     * @return StockPriceDto 
     * 기준 종가와 d-1, d-5, d-20 종가의 차이 데이터를 포함한 데이터
     */
    public function selectStockPrice() {

        // LIMIT과 OFFSET을 활용해서 특정 날짜로부터 n번째 날짜를 구해서 where 조건으로 넣음

        // 원본 엑셀 파일에서는 증감이 -0.00**일 경우 변동없음(-)으로 나타내는 것이 아니라
        // ▼ 0.00로 나타내어 미세한 변동이 있음을 보여주고 있음

        // stock_id별로 가장 최근의 종가기준일을 가져옴 => 기준 종가 (D0)
        // 가장 최근 종가기준일보다 작은 stock_date 중에 가장 최근의 종가기준일을 가져옴 => D-1 종가 
        // 기준 종가일에서 -5한 날짜보다 작거나 같은 stock_date 중에 가장 최근의 종가기준일을 가져옴 => D-5 종가
        // 기준 종가일에서 -20한 날짜보다 작거나 같은 stock_date 중에 가장 최근의 종가기준일을 가져옴 => D-20 종가
        // CAST(DATE_FORMAT(NOW(), '%Y%m%d') AS UNSIGNED) : 현재 날짜를 정수 형태로
        // 오늘 날짜의 데이터가 INSERT되어 있을 경우를 대비해서 오늘 날짜의 데이터는 조회 대상에서 제외함

        $query = "
                SELECT GBN, STOCK_DATE, AA.STOCK_ID, AA.STOCK_VALUE, s.STOCK_NAME, s.REMARKS, c.STOCK_CATEGORY_ID, c.CATEGORY_NAME
                FROM (SELECT 'D0' GBN, A.STOCK_PRICE_ID, A.STOCK_DATE, A.STOCK_ID, A.STOCK_VALUE
                      FROM stock_price A
                      JOIN ( 
                            SELECT STOCK_ID, max(STOCK_DATE) MX_STOCK_DATE 
                            FROM stock_price 
                            WHERE DBSTATUS = 'A' AND STOCK_DATE < CAST(DATE_FORMAT(NOW(), '%Y%m%d') AS UNSIGNED) 
                            GROUP BY stock_id
                      ) B 
                      ON (A.STOCK_ID = B.STOCK_ID and A.STOCK_DATE = B.MX_STOCK_DATE)
                      WHERE A.DBSTATUS = 'A'
                
                      UNION ALL
        
                      SELECT 'D1' GBN, A.STOCK_PRICE_ID, A.STOCK_DATE, A.STOCK_ID, A.STOCK_VALUE 
                      FROM stock_price A
                      JOIN (
                            SELECT STOCK_ID, max(STOCK_DATE) MX_STOCK_DATE 
                            FROM stock_price 
                            WHERE STOCK_DATE < (SELECT max(STOCK_DATE) 
                                                FROM stock_price 
                                                WHERE DBSTATUS = 'A' AND STOCK_DATE < CAST(DATE_FORMAT(NOW(), '%Y%m%d') AS UNSIGNED))
                                  AND DBSTATUS = 'A' 
                            GROUP BY STOCK_ID            
                      ) B 
                      ON (A.STOCK_ID = B.STOCK_ID and A.STOCK_DATE = B.MX_STOCK_DATE)    
                      WHERE A.DBSTATUS = 'A'  
                   
                      UNION ALL
            
                      SELECT 'D5' GBN, A.STOCK_PRICE_ID, A.STOCK_DATE, A.STOCK_ID, A.STOCK_VALUE 
                      FROM stock_price A
                      JOIN (
                            SELECT STOCK_ID, max(STOCK_DATE) MX_STOCK_DATE 
                            FROM stock_price 
                            WHERE STOCK_DATE <= (SELECT distinct STOCK_DATE 
                                                 FROM stock_price
                                                 WHERE DBSTATUS = 'A' AND STOCK_DATE < CAST(DATE_FORMAT(NOW(), '%Y%m%d') AS UNSIGNED) 
                                                 ORDER BY STOCK_DATE DESC 
                                                 LIMIT 1 OFFSET 5)
                            AND DBSTATUS = 'A'
                            GROUP BY STOCK_ID            
                      ) B 
                      ON (A.STOCK_ID = B.STOCK_ID AND A.STOCK_DATE = B.MX_STOCK_DATE)
                      WHERE A.DBSTATUS = 'A'
                
                      UNION ALL
            
                      SELECT 'D20' GBN, A.STOCK_PRICE_ID, A.STOCK_DATE, A.STOCK_ID, A.STOCK_VALUE 
                      FROM stock_price A
                      JOIN (
                            SELECT STOCK_ID, max(STOCK_DATE) MX_STOCK_DATE 
                            FROM stock_price 
                            WHERE STOCK_DATE <= (SELECT distinct STOCK_DATE 
                                                 FROM stock_price
                                                 WHERE DBSTATUS = 'A' AND STOCK_DATE < CAST(DATE_FORMAT(NOW(), '%Y%m%d') AS UNSIGNED) 
                                                 ORDER BY STOCK_DATE DESC 
                                                 LIMIT 1 OFFSET 20)
                            AND DBSTATUS = 'A'
                            GROUP BY STOCK_ID            
                      ) B 
                      ON (A.STOCK_ID = B.STOCK_ID AND A.STOCK_DATE = B.MX_STOCK_DATE)
                      WHERE A.DBSTATUS = 'A'
                ) AA
                JOIN stock AS s
                ON s.STOCK_ID = AA.STOCK_ID
                JOIN stock_category AS c
                ON c.STOCK_CATEGORY_ID = s.STOCK_CATEGORY_ID
                WHERE s.DBSTATUS = 'A' AND c.DBSTATUS = 'A'";

        $statement = $this->conn->prepare($query);
        $statement->execute();
        $dataArr = $statement->get_result();

        $resultArr = [];
        foreach ($dataArr as $data) {
            $dto = new StockPriceDto($data['GBN'], $data['STOCK_CATEGORY_ID'], $data['CATEGORY_NAME'], $data['STOCK_DATE'], 
                                     $data['STOCK_ID'], $data['STOCK_NAME'], $data['STOCK_VALUE'], $data['REMARKS']);
            $resultArr[] = $dto;
        }

        return $resultArr;
    }

    /**
     * @author 서영
     * @return array 
     * stock 테이블과 category 테이블을 조인하고
     * category 그룹별 stock 개수를 구한 후, category_id를 key로 해서 배열로 반환
     */
    public function selectStockCountByCategory()
    {
        $query = "SELECT stock_category_id, COUNT(*) AS 'stock_count'
                  FROM stock AS s
                  GROUP BY s.STOCK_CATEGORY_ID
                  ORDER BY stock_category_id";
        $dataArr = $this->conn->query($query)->fetch_all();

        $resultArr = [];
        foreach ($dataArr as $data) {
            $resultArr[$data[0]] = $data[1];
        }
        return $resultArr;
    }

    /**
     * 이메일 발송 시 로그 데이터를 남김 (발송 여부와 함께)
     * @author 서영
     * @param int $stockDate 
     * @param int $status : 1(미발송), 2(발송완료)
     */
    public function insertEmailLog($stockDate, $status) {
        $query = "INSERT INTO email_log (STOCK_DATE, EMAIL_LOG_MASTER_ID)
                  VALUES (?, ?)";
        $statement = $this->conn->prepare($query);

        // i : 정수형 인자를 바인딩함
        $statement->bind_param('ii', $stockDate, $status);
        $statement->execute();
    }

    public function closeConnection()
    {
        $this->conn->close();
    }
    
}
