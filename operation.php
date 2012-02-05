<?php
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

    global $SqlMgr;
    $query = array('trade'=>$orderList, 'status'=>0, 'price'=>$price);
    $SqlMgr->insert('trade', $query);
    unset($_SESSION['order']);
}

function cancelOrder() {
    unset($_SESSION['order']);
}

function done($id) {
    global $SqlMgr;
    $SqlMgr->update('trade', 'status=1', "id=$id");
}

function paid($id) {
    global $SqlMgr;
    $SqlMgr->update('trade', 'status=2', "id=$id");
}

function addFavorite($id) {
    global $SqlMgr;
    $SqlMgr->insert('favorite', array('id'=>$id));
    loadMenu(true);
}

function delFavorite($id) {
    global $SqlMgr;
    $SqlMgr->delete('favorite', "id=$id");
    loadMenu(true);
}
?>
