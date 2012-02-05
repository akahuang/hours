<?php
if (file_exists('hours.db')) unlink('hours.db');
include "../SQLiteMgr.php";
$dbPath = 'hours.db';
$db = new SQLiteMgr($dbPath);

$setupScript = fopen('database.setup', 'r');
if ($setupScript == null) {
    die('database.setup not found.');
}

if ($setupScript != null) {
    echo "create table..\n";
    while ($query = fgets($setupScript, 1024)) {
        echo "query: $query";
        $db->exec($query);
    }
    fclose($setupScript);
}

$tableNames = array('menu', 'group');
foreach ($tableNames as $tableName) {
    $csvFile = fopen("$tableName.csv", 'r');
    if ($csvFile == null) {
        echo "$tableName.csv is not found.";
        exit;
    }

    echo "import $tableName.csv\n";
    $title = fgetcsv($csvFile, 100);
    while ($data = fgetcsv($csvFile, 100)) {
        print_r($data);
        if (count($data) != count($title)) continue;

        $ret = array();
        for ($i=0; $i<count($title); $i=$i+1) {
            $ret["{$title[$i]}"] = $data[$i];
        }
        $db->insert($tableName, $ret);
    }
    fclose($csvFile);
}

chmod($dbPath, 0666);

?>
