<?php

class SQLiteMgr {
    private $mDatabase = NULL;

    function __construct($dbFilePath) {
        $this->mDatabase = new PDO("sqlite:$dbFilePath");
    }

    function __destruct() {
        $this->close();
    }

    public function close() {
        $this->mDatabase = NULL;
    }

    public function exec($query) {
        $this->mDatabase->exec($query);
    }

    public function insert($table, $elements) {
        $keyStr = "";
        $valueStr = "";
        foreach ($elements as $key => $value) {
            $keyStr .= "`$key`,";
            $valueStr .= "'$value',";
        }
        $this->removeLast($keyStr);
        $this->removeLast($valueStr);
        $query = "INSERT INTO `$table` ($keyStr) VALUES ($valueStr)";
        $this->debug($query);
        $this->mDatabase->exec($query);
    }

    public function select($table, $condition = '', $column = '*') {
        if ($condition == '') {
            $query = "SELECT $column FROM `$table`";
        } else {
            $query = "SELECT $column FROM `$table` WHERE $condition";
        }
        $this->debug($query);
        $results = $this->mDatabase->query($query);
        if ($results == null) return null;

        $ret = array();
        while ($result = $results->fetch(PDO::FETCH_ASSOC)) {
            $ret[$result['id']] = $result;
        }
        return $ret;
    }

    public function update($table, $set, $condition = '') {
        $query = "UPDATE $table SET $set WHERE $condition";
        $this->debug($query);
        $this->mResult = $this->mDatabase->query($query);
    }

    private static function removeLast(&$str) {
        $str = substr($str, 0, -1);
    }

    private static function debug($str) {
//        session_start();
//        if (isset($_GET['debug'])) {
//            $_SESSION['debug'] = $_GET['debug'];
//        }
//        if (isset($_SESSION['debug']) && $_SESSION['debug'] == 'on') {
          echo "Debug: $str<br>\n";
//        }
    }
}
?>
