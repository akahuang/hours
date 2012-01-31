<?php

class SQLiteMgr {
    private $db = NULL;

    function __construct($dbFilePath) {
        $this->db = new PDO("sqlite:$dbFilePath");
    }

    function __destruct() {
        $this->close();
    }

    public function close() {
        $this->db = NULL;
    }

    public function exec($query) {
        $this->db->exec($query);
    }

    public function insert($table, $elements) {
        $keyStr = "";
        $valueStr = "";
        foreach ($elements as $key => $value) {
            $keyStr .= "$key,";
            $valueStr .= "'$value',";
        }
        $this->removeLast($keyStr);
        $this->removeLast($valueStr);
        $query = "INSERT INTO $table($keyStr) VALUES ($valueStr)";
        $this->debug($query);
        $this->db->exec($query);
    }

    public function select($table, $condition = '', $others = '') {
        $query = "SELECT * FROM '$table'";
        if ($condition != '') $query .= " WHERE $condition";
        if ($others != '') $query .= " $others";
        $this->debug($query);
        $results = $this->db->query($query);
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
        $this->db->exec($query);
    }

    private static function removeLast(&$str) {
        $str = substr($str, 0, -1);
    }

    private static function debug($str) {
        if (false) echo "Debug: $str<br>\n";
    }
}
?>
