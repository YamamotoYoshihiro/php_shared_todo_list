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
    $expire_date = $_POST['expire_date'];

    if (empty($_POST['item_name'])) {
        $_SESSION['error_item_name'] = '※登録内容を入力してください。';
        if (empty($_POST['expire_date'])) {
            $_SESSION['error_expire_date'] = '※日付を入力してください。';
            header('Location:./entry.php');
            exit;
        } else {
            //年、月、日をlist関数で分割
            list($Y, $m, $d) = explode('-', $expire_date);
            if (checkdate($m, $d, $Y) == true) {
                //日付が正しい場合には$_SESSION['error_expire_date']を空にする。
                $_SESSION['error_expire_date'] = '';
                header('Location:./entry.php');
                exit;
            } else {
                $_SESSION['error_expire_date'] = '※正しい日付を入力してください。例 Year-month-day';
                header('Location:./entry.php');
                exit;
            }
        }
    }
    if ($item_name > 10) {
        $_SESSION['error_item_name'] = '※100文字以内で入力してください。';
        if (isset($_POST['expire_date'])) {
            if (empty($_POST['expire_date'])) {
                $_SESSION['error_expire_date'] = '※日付を入力してください。';
                header('Location:./entry.php');
                exit;
            } else {
                //年、月、日をlist関数で分割
                list($Y, $m, $d) = explode('-', $expire_date);
                if (checkdate($m, $d, $Y) == true) {
                    //日付が正しい場合には$_SESSION['error_expire_date']を空にする。
                    $_SESSION['error_expire_date'] = '';
                    header('Location:./entry.php');
                    exit;
                } else {
                    $_SESSION['error_expire_date'] = '※正しい日付を入力してください。例 Year-month-day';
                    header('Location:./entry.php');
                    exit;
                }
            }
        }
    }

    if ($item_name <= 10 && $item_name > 0) {
        //バリデーションOKなら$_SESSION['error_item_name']を空にする。
        $_SESSION['error_item_name'] = '';
        //年、月、日をlist関数で分割
        list($Y, $m, $d) = explode('-', $expire_date);
        //$expire_dateの形式を変換する
        if (checkdate($m, $d, $Y) == true) {
            //日付が正しい場合には$_SESSION['error_expire_date']を空にする。
            $_SESSION['error_expire_date'] = '';
            var_dump($_POST['expire_date']);
            if (isset($_POST['check']) == true) {
                $finished_date = date('Y-m-d');
            } else {
                $finished_date = null;
            }
            $TodoItems->insert($_POST['user_id'], $_POST['item_name'], $_POST['registration_date'], $expire_date, $finished_date);
            header('Location: ./index.php');
            exit;
        } elseif (empty($_POST['expire_date'])) {
            $_SESSION['error_expire_date'] = '※日付を入力してください。';
            header('Location:./entry.php');
            exit;
        } elseif (checkdate($m, $d, $Y) == false) {
            $_SESSION['error_expire_date'] = '※正しい日付を入力してください。例 Year-month-day';
            header('Location:./entry.php');
            exit;
        }
    }
} catch (exception $e) {
    header('Location:error.php');
}
