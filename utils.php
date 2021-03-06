<?php
function genName($trade) {
    $arr = explode(':', $trade);
    $ret = '';
    $price = 0;
    foreach ($arr as $id) {
        $ret .= "{$_SESSION['menu'][$id]['name']}<br>";
        $price += $_SESSION['menu'][$id]['price'];
    }
    $ret .= "&nbsp;&nbsp;&nbsp;&nbsp;共 $price 元";
    return $ret;
}

function loadMenu($force = false) {
    if (isset($_SESSION['menu']) && !$force) return;

    global $SqlMgr;
    $_SESSION['group'] = $SqlMgr->select('group');
    $_SESSION['menu'] = $SqlMgr->select('menu');
    $_SESSION['favorite'] = $SqlMgr->select('favorite');
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
