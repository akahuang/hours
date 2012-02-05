<?php
include "utils.php";

function menuPage() {
    if (isset($_SESSION['order'])) {
        $transaction = genName(arrEncode($_SESSION['order']));
    } else {
        $transaction = '';
    }

    // Add favorite group first.
    $ret = '<center><h2>常點清單</h2></center>';
    foreach ($_SESSION['favorite'] as $menu) {
        $id = $menu['id'];
        $isOrder = isset($_SESSION['order'][$id]);
        $ret .= makeButton($id, "addOrder", $isOrder);
    }

    foreach ($_SESSION['group'] as $group) {
        $ret .= "<center><h2>{$group['name']}</h2></center>";
        foreach ($_SESSION['menu'] as $menu) {
            if ($menu['group_id'] == $group['id']) {
                $isOrder = isset($_SESSION['order'][$menu['id']]);
                $ret .= makeButton($menu['id'], "addOrder", $isOrder);
            }
        }
    }

    return <<<EOD
<div id="content">
    <div id="menuList">
        $transaction
        <center>
        <form method="POST" action="index.php?page=list">
            <input type="hidden" name="op" value="order">
            <input type="submit" value="點菜" />
        </form>
        <form method="POST">
            <input type="hidden" name="op" value="cancelOrder">
            <input type="submit" value="重置" />
        </form>
        </center>
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
        <ul>
        $leftContent
        </ul>
    </div>
    <div id="rightList">
        <ul>
        $rightContent
        </ul>
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

function favoritePage() {
    $ret = '';
    foreach ($_SESSION['group'] as $group) {
        $ret .= "<center><h2>{$group['name']}</h2></center>";
        foreach ($_SESSION['menu'] as $menu) {
            if ($menu['group_id'] == $group['id']) {
                $id = $menu['id'];
                $isOrder = isset($_SESSION['favorite'][$id]);
                $op = ($isOrder) ? 'delFavorite' : 'addFavorite';
                $ret .= makeButton($id, $op, $isOrder);
            }
        }
    }

    return <<<EOD
<div id="content">
    <div id="menuList">
        $ret
    </div>
</div>
EOD;


}

function makeLink($id, $name, $op) {
    return <<<EOD
    <li><a href="?page=list&op=$op&id=$id">$name</a></li>
EOD;
}

function makeButton($id, $op, $isOrder = false) {
    $orderStr = $isOrder ? 'class=order' : '';
    $name = $_SESSION['menu'][$id]['name'];
    return <<<EOD
<form method="POST">
    <input type=hidden name="op" value="$op">
    <input type=hidden name="id" value="$id">
    <input $orderStr type=submit value="$name">
</form>
EOD;
}
?>
