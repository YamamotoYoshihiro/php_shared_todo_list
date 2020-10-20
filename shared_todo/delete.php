<?php
session_start();
session_regenerate_id();
require_once('./class/db/Base.php');
require_once('./class/db/TodoItems.php');
require_once('./class/db/Users.php');

$token = bin2hex(openssl_random_pseudo_bytes(32));
$_SESSION['token'] = $token;

if (!isset($_SESSION['user'])) {
    header('Location: ./login.php');
}
$TodoItems = new TodoItems();
$TodoItem = $TodoItems->selectTodoItem($_POST['id']);
?>
<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shared todo</title>
</head>

<body>
    <h3>
        <u>削除確認</u>
    </h3>
    <?php if (isset($_SESSION['error'])) : ?>
        <span style="color: #ff0000;"><?= $_SESSION['error'] ?></span><br>
    <?php endif ?>
    <table>
        <form action="./delete_action.php" method="POST">
            <input type="hidden" name="token" value="<?= $token ?>">
            <input type="hidden" name="id" value="<?= $TodoItem[0]["id"] ?>">
            <input type="hidden" name="is_deleted" value="<?= $TodoItem[0]['is_deleted'] ?>">
            <tr>
                <td>
                    項目名
                </td>
                <td>
                    <?= $TodoItem[0]['item_name'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    担当者
                </td>
                <td>
                    <?= $TodoItem[0]['user'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    期限
                </td>
                <td>
                    <?= $TodoItem[0]['expire_date'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    完了
                </td>
                <td>
                    <?php if (!empty($TodoItem[0]['finished_date'])) : ?>完了<?php else : ?>未<?php endif ?>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" name="is_deleted" value="削除">
                </td>
                <td>
                    <form action="./index.php">
                        <input type="submit" value="キャンセル">
                    </form>
                </td>
            </tr>
        </form>
    </table>
</body>

</html>