<?php
include "pages.php";
include "SQLiteMgr.php";
include "operation.php";

session_start();
$SqlMgr = new SQLiteMgr('data/hours.db');
loadMenu();

if (isset($_REQUEST['op'])) {
    switch ($_REQUEST['op']) {
        case 'addOrder':
            addOrder($_POST['id']);
            break;
        case 'delOrder':
            delOrder($_POST['id']);
            break;
        case 'order':
            order();
            break;
        case 'cancelOrder':
            cancelOrder();
            break;
        case 'done':
            done($_REQUEST['id']);
            break;
        case 'paid':
            paid($_REQUEST['id']);
            break;
        case 'addFavorite':
            addFavorite($_POST['id']);
            break;
        case 'delFavorite':
            delFavorite($_POST['id']);
            break;
    }
}

if (isset($_GET['page'])) $_SESSION['page'] = $_GET['page'];
if (!isset($_SESSION['page'])) $_SESSION['page'] = 'menu';
switch($_SESSION['page']) {
    case 'menu':
        $content = menuPage();
        break;
    case 'list':
        $content = listPage();
        break;
    case 'today':
        $content = todayPage();
        break;
    case 'favorite':
        $content = favoritePage();
        break;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="viewport" content="user-scalable=no, width=device-width" />

  <title>Hours Menu</title>
  <link rel="stylesheet" type="text/css" href="style.css" media="screen" />
  <link rel="apple-touch-startup-image" href="image/hours.png" />
  <link rel="apple-touch-icon" href="image/icon.png" />
</head>
<body>
<div id=header>
    <div id=title>Hours Menu</div>
    <div id=menu>
        <a href="?page=menu">菜單</a>
        <a href="?page=list">列表</a>
        <a href="?page=today">本日交易</a>
        <a href="?page=favorite">常用清單</a>
    </div>
</div>
<?php echo $content; ?>
</body>
</html>

