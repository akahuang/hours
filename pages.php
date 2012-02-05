<?php
include "SQLiteMgr.php";

function menuPage() {
    loadMenu();
    $ret = '';
    $group = 0; // start from 1
    foreach ($_SESSION['menu'] as $menu) {
        if ($group != $menu['group_id']) {
            $group = $menu['group_id'];
            $ret .= "<h2>{$_SESSION['group'][$group]['name']}</h2>";
        }
        $isOrder = isset($_SESSION['order'][$menu['id']]);
        $name = findName($menu['id']);
        $ret .= makeButton($menu['id'], $name, "addOrder", $isOrder);
    }
    return <<<EOD
<div id="content">
    <div id="menuList">
        <center><form method="POST" action="index.php?page=list">
            <input type="hidden" name="op" value="order">
            <input type="submit" value="點菜" />
        </form>
        <form method="POST">
            <input type="hidden" name="op" value="cancelOrder">
            <input type="submit" value="重置" />
        </form></center>
        <hr>
        $ret
    </div>
</div>
EOD;

}

function listPage() {
    loadMenu();
    $SqlMgr = new SQLiteMgr('data/hours.db');
    $todoList = $SqlMgr->select('trade', 'status=0');
    $topaidList = $SqlMgr->select('trade', 'status=1');

    $leftContent = '<h2>Todo List</h2>';
    $rightContent = '<h2>ToPaid List</h2>';
    foreach ($todoList as $item) {
        $str = genName($item['trade']);
        $leftContent .= makeLink($item['id'], $str, 'done');
    }
    foreach ($topaidList as $item) {
        $str = genName($item['trade']);
        $rightContent .= makeLink($item['id'], $str, 'paid');
    }

    return <<<EOD
<div id="content">
    <div id="leftList">
        $leftContent
    </div>
    <div id="rightList">
        $rightContent
    </div>
</div>
EOD;
}

function todayPage() {
    loadMenu();
    $SqlMgr = new SQLiteMgr('data/hours.db');
    $trades = $SqlMgr->select('trade', 'status=2 and date(time) = date("now")');

    $ret = '<table border="1">';
    $ret .= '<tr><td>時間</td><td>交易</td></tr>';
    foreach($trades as $trade) {
        $transaction = genName($trade['trade']);
        $ret .= "<tr><td>{$trade['time']}</td><td>$transaction</td></tr>";
    }
    $ret .= '</table>';
    return $ret;
}
function makeLink($id, $name, $op) {
    return <<<EOD
    <li><a href="?page=list&op=$op&id=$id">$name</a>
EOD;
}

function makeButton($id, $name, $op, $isOrder = false) {
    $orderStr = $isOrder ? 'class=order' : '';
    return <<<EOD
<form method="POST">
    <input type=hidden name="op" value="$op">
    <input type=hidden name="id" value="$id">
    <input $orderStr type=submit value="$name">
</form>
EOD;
}

function findName($id) {
    if (!isset($_SESSION['order'][$id])) return $_SESSION['menu'][$id]['name'];

    $num = $_SESSION['order'][$id];
    return "{$_SESSION['menu'][$id]['name']}*$num";
}

function genName($trade) {
    $arr = arrDecode($trade);
    $ret = '';
    $price = 0;
    foreach ($arr as $id => $num) {
        $ret .= "{$_SESSION['menu'][$id]['name']}*$num ";
        $price += $_SESSION['menu'][$id]['price'] * $num;
    }
    $ret .= "共 $price 元";
    return $ret;
}

function loadMenu() {
    //if (isset($_SESSION['menu'])) return;

    $SqlMgr = new SQLiteMgr('data/hours.db');
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

function order() {
    if (!isset($_SESSION['order'])) return;

    $orderList = arrEncode($_SESSION['order']);
    $price = 0;
    foreach ($_SESSION['order'] as $id => $num) {
        $price += $_SESSION['menu'][$id]['price'] * $num;
    }

    $SqlMgr = new SQLiteMgr('data/hours.db');
    $query = array('trade'=>$orderList, 'status'=>0, 'price'=>$price);
    $SqlMgr->insert('trade', $query);
    unset($_SESSION['order']);
}

function cancelOrder() {
    unset($_SESSION['order']);
}

function done($id) {
    $SqlMgr = new SQLiteMgr('data/hours.db');
    $SqlMgr->update('trade', 'status=1', "id=$id");
}

function paid($id) {
    $SqlMgr = new SQLiteMgr('data/hours.db');
    $SqlMgr->update('trade', 'status=2', "id=$id");
}

function arrEncode($arr) {
    $combine = array();
    foreach($arr as $key=>$value) {
        array_push($combine, "$key*$value");
    }
    return implode(':', $combine);
}

function arrDecode($str) {
    $combine = explode(':', $str);
    $ret = array();
    foreach($combine as $item){
        list($key, $value) = explode('*', $item);
        $ret[$key] = $value;
    }
    return $ret;
}
?>
