<?php
function order() {
    $orderList = explode(':', $_POST['orderList']);
    $price = 0;
    foreach ($_SESSION['order'] as $id) {
        $price += $_SESSION['menu'][$id]['price'];
    }

    global $SqlMgr;
    $query = array('trade'=>$_POST['orderList'], 'status'=>0, 'price'=>$price);
    $SqlMgr->insert('trade', $query);
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
