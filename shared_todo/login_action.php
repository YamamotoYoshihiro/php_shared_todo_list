<?php
require_once('./class/db/Base.php');
require_once('./class/db/Users.php');
session_start();
session_regenerate_id();

try {
  if (!isset($_SESSION['token']) || $_SESSION['token'] !== $_POST['token']) {
    $_SESSION['error'] = "不正な処理が行われました。";
    header('Location: ./login.php');
    exit;
  } else {
    //$_SESSION['error]を空白にする
    $_SESSION['error'] = '';
  }
  $Users = new Users();

  $ret = $Users->login($_POST['user']);
  // echo "<pre>";
  // var_dump($ret);
  // echo "</pre>";

  //3回以上ログインできないとerror.phpへ
  if (isset($_SESSION["loginCount"]) == true) {
    $_SESSION["loginCount"]++;
  } else {
    $_SESSION["loginCount"] = 1;
  }
  if ($_SESSION['loginCount'] > 3) {
    header('Location: ./error.php');
    exit;
  }

  if (empty($_POST['user']) && empty($_POST['pass'])) {
    $_SESSION['error'] = '※ユーザー名、パスワードを入力してください';
    header('Location: ./login.php');
  } elseif (empty($_POST['user']) || empty($_POST['pass'])) {
    $_SESSION['error'] = "※ユーザー名またはパスワードを入力してください";
    header('Location: ./login.php');
    exit;
  } elseif (isset($ret) && password_verify($_POST['pass'], $ret['pass']) == true) {
    $_SESSION['user'] = $ret;
    $_SESSION['loginCount'] = 0;
    header('Location: ./index.php');
    exit;
  } elseif ($ret['user'] !== $_POST['user'] || password_verify($_POST['pass'], $ret['pass']) == false) {
    $_SESSION['error'] = "※ユーザー名またはパスワードに誤りがあります";
    header('Location: ./login.php');
    exit;
  }
} catch (Exception $e) {
  header('Location: ./error.php');
  // echo "<pre>";
  // var_dump($e);
  // echo "</pre>";
}
