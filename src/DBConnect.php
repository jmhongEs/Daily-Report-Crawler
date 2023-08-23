<?php

namespace App\DailyReportCrawler;

use mysqli;
use App\DailyReportCrawler\StockPrice;
use App\DailyReportCrawler\StockCategoryDto;
use App\DailyReportCrawler\StockPriceDto;
use mysqli_result;

Class DBConnect{
    
    private $servername;
    private $username;
    private $password;
    private $database;
    private $port;
    private $conn;

    public function __construct(){

        $this->servername = "54.180.60.132";
        $this->username = "es_stock";
        $this->password = "f%Gb!3sT%\$Vx1a";
        $this->database = "es_stock";
        $this->port = 3306;

        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->database, $this->port);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function dataInsert($getArray){

        unset($getArray[0]);
        unset($getArray[1]);

        $getArray = array_values($getArray);

        $dateFormat = Date('Ymd', strtotime('-1 day'));
        $todayInt = intval($dateFormat);

        dump($getArray);

        foreach ($getArray as $index => $array){
            $setCategoryNo = $index + 1 ;
            $sql = "
            INSERT INTO STOCK_PRICE (STOCK_DATE,STOCK_ID,STOCK_PRICE)
            values ('$todayInt',$setCategoryNo,$array)
            ";
            $this->conn->query($sql);
        }
        
    }
    
    /**
     * @author 서영
     * @param $targetStockDate 종가 기준일, $targetCategory 지수 카테고리
     * @return StockPriceDto stockPrice 데이터 배열
     */
    public function selectStockPriceByStockDateAndStockId($targetStockDate, $targetStockId) {
        $query = "SELECT p.STOCK_DATE, p.STOCK_ID, s.STOCK_NAME, p.STOCK_VALUE, ifnull(s.REMARKS, '') AS 'REMARKS'
                  FROM stock_price AS p
                  JOIN stock AS s
                  ON p.STOCK_ID = s.STOCK_ID
                  WHERE p.STOCK_DATE = ? 
                        AND p.STOCK_ID = ?
                  ORDER BY p.STOCK_ID";
        $statement = $this->conn->prepare($query);

        // i : 정수형 인자를 바인딩함
        $statement->bind_param('ii', $targetStockDate, $targetStockId);
        $statement->execute();
        $dataArr = $statement->get_result();
        
        $resultDto = null;
        foreach ($dataArr as $data) {
            $resultDto = new StockPriceDto($data['STOCK_DATE'], $data['STOCK_ID'], $data['STOCK_NAME'], $data['STOCK_VALUE'], $data['REMARKS']);
            // stock_id를 key로 해서 price 저장
        }

        // 해당 Dto가 존재하지 않는다면 stock_value가 존재하지 않는다는 의미
        // 값이 꼬이지 않도록 -1을 담아서 저장해둠 (정상적으로 저장된종가는 항상 0 이상이므로)
        if ($resultDto == null) {
            $stockName = $this->selectStockNameByStockId($targetStockId);
            $resultDto = new StockPriceDto($targetStockDate, $targetStockId, $stockName, -1, "");
        }

        return $resultDto;
    }
    
    /**
     * @author 서영
     * @return string stockName
     * stockId를 조건으로 stockName 찾기
     */
    public function selectStockNameByStockId($stockId) {
        $query = "SELECT STOCK_NAME
                  FROM stock
                  WHERE STOCK_ID = ?";
        $statement = $this->conn->prepare($query);

        // i : 정수형 인자를 바인딩함
        $statement->bind_param('i', $stockId);
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc()['STOCK_NAME'];

        return $result;
    }

    /**
     * @author 서영
     * @return array stock 테이블의 모든 stock_id를 담은 배열 
     */
    public function selectStockIdAll() {
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
    public function selectStockCategory() {
        $query = "SELECT s.stock_category_id, MAX(c.CATEGORY_NAME) AS category_name, COUNT(*) AS 'stock_count'
                  FROM stock AS s
                  JOIN stock_category AS c
                  ON s.STOCK_CATEGORY_ID = c.STOCK_CATEGORY_ID
                  GROUP BY s.STOCK_CATEGORY_ID
                  ORDER BY stock_category_id";
        $dataArr = $this->conn->query($query)->fetch_all();

        $resultArr = [];
        foreach ($dataArr as $data) {
            $stockPrice = new StockCategoryDto($data[0], $data[1], $data[2]);
            // stock_category_id를 key로 해서 price 저장
            $resultArr[$data[0]] = $stockPrice;
        }

        return $resultArr;
    }

    /**
     * @author 서영
     * @return array
     * 해당 카테고리에 존재하는 stock_id 반환 
     */
    public function selectStockIdByCategory($categoryId) {
        $query = "SELECT STOCK_ID
                  FROM stock
                  WHERE STOCK_CATEGORY_ID = ?";
        $statement = $this->conn->prepare($query);

        // i : 정수형 인자를 바인딩함
        $statement->bind_param('i', $categoryId);
        $statement->execute();
        $dataArr = $statement->get_result()->fetch_all();

        $resultArr = [];
        foreach ($dataArr as $data) {
            $resultArr[] = $data[0];
        }

        return $resultArr;
    }



    public function closeConnection() {
        $this->conn->close();
    }


}

?>