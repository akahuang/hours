<?php
include "SQLiteMgr.php";
$SqlMgr = new SQLiteMgr('data/hours.db');

function menuPage() {
    loadMenu();
    $ret = '';
    $group = 0; // start from 1
    foreach ($_SESSION['menu'] as $menu) {
        if ($group != $menu['group_id']) {
            $group = $menu['group_id'];
            $ret .= "<h2>{$_SESSION['group'][$group]['name']}</h2>";
        }
        $ret .= makeButton($menu['id'], $menu['name'], "addOrder");
    }
    $orderList = makeOrderList();
    return makePage($ret, $orderList);
}

function listPage() {

}

function makePage($leftContent, $rightContent) {
    return <<<EOD
<div id="content">
    <div id="left">
        $leftContent
    </div>
    <div id="right">
        $rightContent
    </div>
</div>
EOD;
}

function makeOrderList() {
    if (!isset($_SESSION['order'])) return null;

    $ret = '';
    foreach ($_SESSION['order'] as $id => $value) {
        $ret .= makeButton($id, $_SESSION['menu'][$id]['name'] . "*$value", 'delOrder');
    }
    return <<<EOD
        <h2>Order List</h2>
        $ret
        <form method="get">
            <input type="hidden" name="op" value="order">
            <center><input type="submit" value="Order!" /><br></center>
        </form>
EOD;
}

function makeButton($id, $name, $op) {
    return <<<EOD
<form method="POST">
    <input type=hidden name="op" value="$op">
    <input type=hidden name="id" value="$id">
    <input type=submit value="$name">
</form>
EOD;
}

function loadMenu() {
    global $SqlMgr;
    if (isset($_SESSION['menu'])) return; 
        
    echo 'loadMenu';
    $_SESSION['group'] = $SqlMgr->select('group');
    $_SESSION['menu'] = $SqlMgr->select('menu');
}

function addOrder($id) {
    if (!isset($_SESSION['order'])) $_SESSION['order'] = array();
    if (!isset($_SESSION['order'][$id])) $_SESSION['order'][$id] = 0;
    $_SESSION['order'][$id] += 1;
}

function delOrder($id) {
    unset($_SESSION['order'][$id]);
}
?>
