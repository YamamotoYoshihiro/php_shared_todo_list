<?php
session_start();
session_regenerate_id();
// エラーメッセージの初期化
if (!isset($_SESSION['token']) || $_SESSION['token'] !== $_POST['token']) {
  $_SESSION['error'] = "不正な処理が行われました。";
  header('Location: ./login.php');
  exit;
} else {
  //$_SESSION['error]を空白にする
  $_SESSION['error'] = '';
}
$_SESSION = array();

session_destroy();
header('Location: ./login.php');
