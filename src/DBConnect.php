<?php

namespace App\DailyReportCrawler;

use mysqli;

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
        

        $this->conn->close();

    }
}

?>