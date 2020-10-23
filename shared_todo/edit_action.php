<?php
session_start();
session_regenerate_id();
require_once('./class/db/Base.php');
require_once('./class/db/TodoItems.php');

try {
    if (!isset($_SESSION['token']) || $_SESSION['token'] !== $_POST['token']) {
        $_SESSION['error'] = "※不正な処理が行われました。";
        header('Location: ./index.php');
        exit;
    } else {
        //OKなら$_SESSON['error]を空にする。
        $_SESSION['error'] = '';
    }
    $TodoItems = new TodoItems();

    //変数の定義
    $item_name = mb_strlen($_POST['item_name']);
    //日付のフォーマットを統一
    $expire_date = date('Y-m-d',  strtotime($_POST['expire_date']));
    //年、月、日をlist関数で分割
    if (!empty($_POST['expire_date'])) {
        list($Y, $m, $d) = explode('-', $expire_date);
    }
    //バリデーション関数の設定
    $val = true;

    //入力バリデーションチェック
    if ($item_name <= 100 && $item_name !== 0) {
        $_SESSION['error_item_name'] = '';
        $val = true;
    }
    if (checkdate($m, $d, $Y) == true) {
        $_SESSION['error_expire_date'] = '';
        $val = true;
    }
    if (empty($_POST['item_name'])) {
        $_SESSION['error_item_name'] = '※項目内容を入力してください';
        $val = false;
    }
    if (empty($_POST['expire_date'])) {
        $_SESSION['error_expire_date'] = '※日付を入力してください';
        $val = false;
    }
    if ($item_name > 100) {
        $_SESSION['error_item_name'] = '※項目は100文字以内で入力してください';
        $val = false;
    }
    if (checkdate($m, $d, $Y) == false) {
        $_SESSION['error_expire_date'] = '※正しい日付を入力してください';
        $val = false;
    }
    if ($val == false) {
        header('Location: ./edit.php');
        exit;
    } elseif ($val == true) {
        //完了ボタンにチェックが入っていればその日を格納
        if (isset($_POST['check']) == true) {
            $finished_date = date('Y-m-d');
        } else {
            $finished_date = null;
        }
        $TodoItems->update($_POST['id'], $_POST['user_id'], $_POST['item_name'], $expire_date, $finished_date);
        header('Location: ./index.php');
        exit;
    }
} catch (exception $e) {
    header('Location: error.php');
}
