<?php
include "utils.php";

function menuPage() {
    $ret = '';
    foreach ($_SESSION['group'] as $group) {
        $ret .= "<h2>{$group['name']}</h2>";
        foreach ($_SESSION['menu'] as $menu) {
            if ($menu['group_id'] == $group['id']) {
                $isOrder = isset($_SESSION['order'][$menu['id']]);
                $name = findName($menu['id']);
                $ret .= makeButton($menu['id'], $name, "addOrder", $isOrder);
            }
        }
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
    global $SqlMgr;
    $todoList = $SqlMgr->select('trade', 'status=0');
    $topaidList = $SqlMgr->select('trade', 'status=1');

    $leftContent = '<h2>待作清單</h2>';
    $rightContent = '<h2>未付款清單</h2>';
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
    global $SqlMgr;
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
?>
