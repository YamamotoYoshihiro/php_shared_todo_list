<?php
session_start();
session_regenerate_id();
require_once('./class/db/Base.php');
require_once('./class/db/TodoItems.php');

try {
    if (!isset($_SESSION['user'])) {
        header('Location: ./login.php');
    }
    if (!isset($_SESSION['token']) || $_SESSION['token'] !== $_POST['token']) {
        $_SESSION['error'] = "※不正な処理が行われました。";
        header('Location: ./index.php');
        exit;
    } else {
        //トークンOKなら$_SESSON['error]を空にする。
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

    //入力バリデーションチェック($_POST['item_name]が空欄の時)
    if (empty($_POST['item_name']) && empty($_POST['expire_date'])) {
        $_SESSION['error_val'] = '※項目名、日付を入力してください';
        header('Location:./edit.php');
        exit;
    } elseif (empty($_POST['item_name']) && checkdate($m, $d, $Y) == false) {
        $_SESSION['error_val'] = '※項目名を入力してください<br>正しい日付を入力してください。例 Year-month-day';
        exit;
    } elseif (empty($_POST['item_name']) && checkdate($m, $d, $Y) == true) {
        $_SESSION['error_val'] = '※項目名を入力してください';
        header('Location:./edit.php');
        exit;
    }
    //バリデーションチェック($_POST['item_name]が文字数オーバーの時)
    if ($item_name > 100 && empty($_POST['expire_date'])) {
        $_SESSION['error_val'] = '※項目名は100字以内で入力してください<br>日付を入力してください';
        header('Location:./edit.php');
        exit;
    } elseif ($item_name > 100 && checkdate($m, $d, $Y) == false) {
        $_SESSION['error_val'] = '※項目名は100文字以内で入力してください<br>正しい日付を入力してください。例 Year-month-day';
        header('Location:./edit.php');
        exit;
    } elseif ($item_name > 100 && checkdate($m, $d, $Y) == true) {
        $_SESSION['error_val'] = '※項目名は100字以内で入力してください';
        header('Location:./edit.php');
        exit;
    }
    //バリデーションチェック($_POST['item_name]がOKの時)
    if ($item_name <= 100 && empty($_POST['expire_date'])) {
        $_SESSION['error_val'] = '※日付を入力してください';
        header(('Location: ./entry.php'));
        exit;
    } elseif ($item_name <= 100 && checkdate($m, $d, $Y) == false) {
        $_SESSION['error_val'] = '※正しい日付を入力してください。例 Year-month-day';
        header('Location: ./entry.php');
        exit;
    } elseif ($item_name <= 100 && checkdate($m, $d, $Y) == true) {
        //バリデーションチェックOKなら$_SESSION['error_val']を空にする。
        $_SESSION['error_val'] = '';
        //完了ボタンにチェックが入っていればその日を格納。チェックされていなければnull
        if (isset($_POST['check']) == true) {
            $finished_date = date('Y-m-d');
        } else {
            $finished_date = null;
        }
        //POSTされてきた値を更新する。
        $TodoItems->insert($_POST['user_id'], $_POST['item_name'], $_POST['registration_date'], $expire_date, $finished_date);
        header('Location: ./index.php');
        exit;
    }
} catch (exception $e) {
    header('Location:error.php');
}
