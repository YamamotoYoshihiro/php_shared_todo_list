<?php
session_start();
session_regenerate_id();
require_once('./class/db/Base.php');
require_once('./class/db/TodoItems.php');
// var_dump($_POST);

$TodoItems = new TodoItems();
if (!isset($_SESSION['token']) || $_SESSION['token'] !== $_POST['token']) {
    $_SESSION['error'] = "※不正な処理が行われました。";
    header('Location: ./index.php');
    exit;
} else {
    //$_SESSION['error]を空白にする
    $_SESSION['error'] = '';
}
var_dump($_POST['id']);
if(!empty($_POST['done']) ) {
 $date=date('Y-m-d');
 $TodoItems->updateById($_POST['id'], $date);
 header('Location: ./index.php');
 }
//  var_dump($_POST['id']);
//  var_dump($date);

