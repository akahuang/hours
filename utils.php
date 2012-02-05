<?php
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
    if (isset($_SESSION['menu'])) return;

    global $SqlMgr;
    $_SESSION['group'] = $SqlMgr->select('group');
    $_SESSION['menu'] = $SqlMgr->select('menu');
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
