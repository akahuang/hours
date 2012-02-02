<?php
include "pages.php";
session_start();

if (isset($_POST['op'])) {
    switch ($_POST['op']) {
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
            done($_POST['id']);
            break;
        case 'paid':
            paid($_POST['id']);
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
</head>
<body>
<div id=header>
    <div id=title>Hours Menu</div>
    <div id=menu>
        <a href="?page=menu">Menu</a>
        <a href="?page=list">List</a>
    </div>
</div>
<?php echo $content; ?>
</body>
</html>

