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
    public function selectStockPriceDiff($targetStockDate)
    {

        // LIMIT과 OFFSET을 활용해서 특정 날짜로부터 n번째 날짜를 구해서 where 조건으로 넣음
        // 종가 값 간의 증감 계산을 쿼리 내에서 수행함

        // 원본 엑셀 파일에서 증감이 -0.00**일 경우 변동없음(-)으로 나타내는 것이 아니라
        // ▼ 0.00로 나타내어 미세한 변동이 있음을 보여주기 때문에 쿼리 내에서는 반올림을 하지 않았음

        $query = "
        SELECT c.STOCK_CATEGORY_ID, c.CATEGORY_NAME, s.STOCK_ID, s.STOCK_NAME, d0.STOCK_VALUE AS 'D0_VALUE', (d0.STOCK_VALUE - d1.STOCK_VALUE) AS 'D1_DIFF',
               (d0.STOCK_VALUE - d5.STOCK_VALUE) AS 'D5_DIFF', (d0.STOCK_VALUE - d20.STOCK_VALUE) AS 'D20_DIFF', s.REMARKS
        FROM stock AS s
        LEFT JOIN stock_category AS c
        ON c.STOCK_CATEGORY_ID = s.STOCK_CATEGORY_ID

        LEFT JOIN (SELECT *
                   FROM stock_price
                   WHERE STOCK_DATE = ?) AS d0
        ON d0.STOCK_ID = s.STOCK_ID
        
        LEFT JOIN (SELECT *
                   FROM stock_price
                   WHERE STOCK_DATE = (SELECT STOCK_DATE
                                       FROM stock_price
                                       WHERE STOCK_DATE <= ?
                                       GROUP BY STOCK_DATE
                                       ORDER BY STOCK_DATE DESC, STOCK_ID
                                       LIMIT 1 OFFSET 1)
                  ) AS d1
        ON s.STOCK_ID = d1.STOCK_ID
        
        LEFT JOIN (SELECT *
                   FROM stock_price
                   WHERE STOCK_DATE = (SELECT STOCK_DATE
                                       FROM stock_price
                                       WHERE STOCK_DATE <= ?
                                       GROUP BY STOCK_DATE
                                       ORDER BY STOCK_DATE DESC, STOCK_ID
                                       LIMIT 1 OFFSET 5)
                  ) AS d5
        ON s.STOCK_ID = d5.STOCK_ID
        
        LEFT JOIN (SELECT *
                   FROM stock_price
                   WHERE STOCK_DATE = (SELECT STOCK_DATE
                                       FROM stock_price
                                       WHERE STOCK_DATE <= ?
                                       GROUP BY STOCK_DATE
                                       ORDER BY STOCK_DATE DESC, STOCK_ID
                                       LIMIT 1 OFFSET 20)
                  ) AS d20
        ON s.STOCK_ID = d20.STOCK_ID
        ORDER BY s.STOCK_ID";
        $statement = $this->conn->prepare($query);

        // i : 정수형 인자를 바인딩함
        $statement->bind_param('iiii', $targetStockDate, $targetStockDate, $targetStockDate, $targetStockDate);
        $statement->execute();
        $dataArr = $statement->get_result();

        $resultArr = [];
        foreach ($dataArr as $data) {
            $dto = new StockPriceDto(
                $data['STOCK_CATEGORY_ID'],
                $data['CATEGORY_NAME'],
                $data['STOCK_ID'],
                $data['STOCK_NAME'],
                $data['D0_VALUE'],
                $data['D1_DIFF'],
                $data['D5_DIFF'],
                $data['D20_DIFF'],
                $data['REMARKS']
            );
            $resultArr[] = $dto;
        }

        return $resultArr;
    }

    /**
     * @author 서영
     * @return array stock 테이블의 모든 stock_id를 담은 배열 
     */
    public function selectStockIdAll()
    {
        $query = "SELECT STOCK_ID FROM stock";
        $dataArr = $this->conn->query($query);

        $resultArr = array();
        while ($row = $dataArr->fetch_row()) {
            // stock_id가 int가 아닌 string으로 저장되는 불편함이 있어서 int로 모두 변환한 후 반환
            $resultArr[] = intval($row[0]);
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


    public function closeConnection()
    {
        $this->conn->close();
    }
}
