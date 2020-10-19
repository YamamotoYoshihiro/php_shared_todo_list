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
    echo '<pre>';
    var_dump($_POST);
    echo '</pre>';

    //入力バリデーションチェック
    if (empty($_POST['item_name'])) {
        $_SESSION['error_item_name'] = '※項目名を入力してください。';
        if (empty($_POST['expire_date'])) {
            $_SESSION['error_expire_date'] = '※日付を入力してください。';
            header('Location:./edit.php');
            exit;
        } else {
            //年、月、日をlist関数で分割
            list($Y, $m, $d) = explode('-', $expire_date);
            if (checkdate($m, $d, $Y) == true) {
                //日付が正しい場合には$_SESSION['error_expire_date']を空にする。
                $_SESSION['error_expire_date'] = '';
                header('Location:./edit.php');
            } else {
                $_SESSION['error_expire_date'] = '※正しい日付を入力してください。例 Year-month-day';
                header('Location:./edit.php');
                exit;
            }
        }
    }
    if ($item_name > 10) {
        $_SESSION['error_item_name'] = '※100文字以内で入力してください。';
        if (empty($_POST['expire_date'])) {
            $_SESSION['error_expire_date'] = '※日付を入力してください。';
            header('Location:./edit.php');
            exit;
        } else {
            //年、月、日をlist関数で分割
            list($Y, $m, $d) = explode('-', $expire_date);
            if (checkdate($m, $d, $Y) == true) {
                //日付が正しい場合には$_SESSION['error_expire_date']を空にする。
                $_SESSION['error_expire_date'] = '';
                header('Location:./edit.php');
                exit;
            } else {
                $_SESSION['error_expire_date'] = '※正しい日付を入力してください。例 Year-month-day';
                header('Location:./edit.php');
                exit;
            }
        }
    }
    if ($item_name <= 10 && $item_name > 0) {
        $_SESSION['error_item_name'] = '';
        //年、月、日をlist関数で分割
        list($Y, $m, $d) = explode('-', $_POST['expire_date']);
        //$expire_dateの形式を変換する
        if (checkdate($m, $d, $Y) == true) {
            $_SESSION['expire_date'] = '';
            $expire_date = date('Y-m-d', strtotime($_POST['expire_date']));

            if (isset($_POST['check']) == true) {
                $finished_date = date('Y-m-d');
            } else {
                $finished_date = null;
            }
            // var_dump(isset($_POST['check']));
            $TodoItems->update($_POST['id'], $_POST['user_id'], $_POST['item_name'], $_POST['expire_date'], $finished_date);
            header('Location: ./index.php');
            exit;
        } elseif (empty($_POST['expire_date'])) {
            $_SESSION['error_expire_date'] = '※日付を入力してください。';
            header('Location:./edit.php');
            exit;
        } elseif (checkdate($m, $d, $Y) == false) {
            $_SESSION['error_expire_date'] = '※正しい日付を入力してください。例 Year-month-day';
            header('Location:./edit.php');
            exit;
        }
    }
} catch (exception $e) {
    // header('Location: error.php');
    echo '<pre>';
    var_dump($e);
    echo '</pre>';
}
