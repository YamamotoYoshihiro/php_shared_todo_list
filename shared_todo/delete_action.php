<?php
session_start();
session_regenerate_id();
require_once('./class/db/Base.php');
require_once('./class/db/TodoItems.php');

try {
  // var_dump($_SESSION['token']);
  // var_dump($_POST);
  if (!isset($_SESSION['user'])) {
    header('Location: ./login.php');
  }
  if (!isset($_SESSION['token']) || $_SESSION['token'] !== $_POST['token']) {
    $_SESSION['error'] = "※不正な処理が行われました。";
    header('Location: ./index.php');
    exit;
  } else {
    //OKなら$_SESSON['error]を空にする。
    $_SESSION['error'] = '';
  }

  $Todoitems = new TodoItems();

  if (isset($_POST["is_deleted"])) {
    $Todoitems->updateByDeleted($_POST['id'], $_POST['is_deleted']);
    header('Location:./index.php');
  }
} catch (Exception $e) {
  // header(('Location:./error.php'));
  echo '<pre>';
  var_dump($e);
  echo '</pre>';
}
