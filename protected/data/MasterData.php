<?php

class MasterData {

    private $db_connection;

    public function openConnectDatabase() {
        $configDatabase = require(YiiBase::getPathOfAlias("webroot") . '\protected\config\database.php');
        $connectionString = split(';', $configDatabase['connectionString']);
        $dbName = (split('=', $connectionString[0]));

        $this->db_connection = mysql_connect($dbName[1], $configDatabase['username'], $configDatabase['password']);
        if (!$this->db_connection) {
            die('Could not connect: ' . mysql_error());
        } else {
            mysql_select_db('nstdascholarship', $this->db_connection);
            mysql_set_charset('utf8');
        }
    }

    public function getData($sql, $index, $value) {
        $result = mysql_query($sql);
        $dataList = [];
        $dataList[0] = "";
        if ($result) {
            while ($row = mysql_fetch_array($result)) {
                $dataList[$row[$index]] = $row[$value];
            }
        }
        return $dataList;
    }

    public
            function closeConnectDatabase() {
        mysql_close($this->db_connection);
    }

}
