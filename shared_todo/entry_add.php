<?php
session_start();
session_regenerate_id();
require_once('./class/db/Base.php');
require_once('./class/db/TodoItems.php');
var_dump($_SESSION);
try {
  if (!isset($_SESSION['token']) || $_SESSION['token'] !== $_POST['token']) {
    $_SESSION['error'] = "不正な処理が行われました。";
    header('Location: ./index.php');
    exit;
  } else {
    //$_SESSION['error]を空白にする
    $_SESSION['error'] = '';
  }
  $TodoItems = new TodoItems();

  if (isset($_POST['done'])) {
    $finished_date = date('Y/m/d');
  } else {
    $finished_date = null;
  }
  $item_name = mb_strlen($_POST['item_name']);

  if ($item_name > 0 && $item_name <= 10) {
    $_SESSION['error_item_name'] = '';
    if (!empty($_POST['expire_date'])) {
      //関数の定義
      $expire_date = $_POST['expire_date'];
      //年、月、日をlist関数で分割
      list($Y, $m, $d) = explode('/[,-]', $expire_date);

      if (checkdate($m, $d, $Y) == true) {
        $_SESSION['error_expire_date'] = '';
        //$expire_dateの形式を変換する
        $expire_date = date('Y-m-d', strtotime($_POST['expire_date']));
        $TodoItems->insert($_POST['user_id'], $_POST['registration_date'], $expire_date, $_POST['item_name'], $finished_date);
        header('Location: ./index.php');
        exit;
      } elseif (checkdate($m, $d, $Y) == false) {
        $_SESSION['error_expire_date'] = "※正しい日付を入力してください。";
        header('Location:./entry.php');
        exit;
      }
    } else {
      $_SESSION['error_expire_date'] = '※期限日を入力してください。';
      header('Location:./entry.php');
    }
  } elseif ($item_name > 10) {
    $_SESSION['error_item_name'] = '※100文字以内で入力してください。';

    if (!empty($_POST['expire_date'])) {
      //関数の定義
      $expire_date = $_POST['expire_date'];
      //年、月、日をlist関数で分割
      list($Y, $m, $d) = explode('/[,-]', $expire_date);

      if (checkdate($m, $d, $Y) == true) {
        $_SESSION['error_expire_date'] = '';
        header('Location:./entry.php');
        exit;
      } elseif (checkdate($m, $d, $Y) == false) {
        $_SESSION['error_expire_date'] = "※正しい日付を入力してください。";
        header('Location:./entry.php');
        exit;
      }
    } else {
      $_SESSION['error_expire_date'] = '※期限日をを入力してください。';
      header('Location:./entry.php');
    }
  } else {
    $_SESSION['error_item_name'] = '※登録内容を入力してください。';

    if (!empty($_POST['expire_date'])) {
      //関数の定義
      $expire_date = $_POST['expire_date'];
      //年、月、日をlist関数で分割
      list($Y, $m, $d) = explode('/[,-]', $expire_date);
      if (checkdate($m, $d, $Y) == true) {
        $_SESSION['error_expire_date'] = '';
        header('Location: ./entry.php');
        exit;
      } elseif (checkdate($m, $d, $Y) == false) {
        $_SESSION['error_expire_date'] = "※正しい日付を入力してください。";
        header('Location:./entry.php');
        exit;
      }
    } else {
      $_SESSION['error_expire_date'] = '※期限日をを入力してください。';
      header('Location:./entry.php');
    }
  }
} catch (Exception $e) {
  var_dump($e);
}
