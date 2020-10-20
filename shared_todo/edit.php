<?php
session_start();
session_regenerate_id();
require_once('./class/db/Base.php');
require_once('./class/db/TodoItems.php');
require_once('./class/db/Users.php');
try {
    $token = bin2hex(openssl_random_pseudo_bytes(32));
    $_SESSION['token'] = $token;

    $TodoItems = new TodoItems();
    $listId = $TodoItems->selectId();
    if (!empty($_POST['id'])) {
        $TodoItem = $TodoItems->selectTodoItem($_POST['id']);
    }

    if (!isset($_SESSION['user'])) {
        header('Location: ./login.php');
    }
} catch (Exception $e) {
    header('Location: ./error.php');
}

?>
<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shared todo</title>
</head>


<body>
    <div class="container">
        <h3><u>作業更新</u></h3>
        <table>
            <tr>
                <td>
                    <?php if (isset($_SESSION['error'])) : ?>
                        <span style="color: #ff0000;"><?= $_SESSION['error'] ?></span>
                    <?php endif ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php if (isset($_SESSION['error_val'])) : ?>
                        <span style="color: #ff0000;"><?= $_SESSION['error_val'] ?></span>
                    <?php endif ?>
                </td>
            </tr>

            <form action="./edit_action.php" method="POST">
                <input type="hidden" name="token" value="<?= $token ?>">
                <input type="hidden" name="id" value="<?= $TodoItem[0]['id'] ?>">
                <tr>
                    <td>
                        項目名
                    </td>
                    <td>
                        <input type="text" name="item_name" value="<?php if (!empty($TodoItem[0]['item_name'])) echo $TodoItem[0]['item_name']; ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        担当者
                    </td>
                    <td>
                        <select name="user_id">
                            <?php foreach ($listId as $key => $value) : ?>
                                <option value="<?= $value['id'] ?>" <?php if (!empty($TodoItem[0]['expire_date']) && $TodoItem[0]['user_id'] == $value['id']) echo 'selected' ?>>
                                    <?= $value['user'] ?>
                                </option>
                            <?php endforeach ?>
                            <?php var_dump($v); ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        期限
                    </td>
                    <td>
                        <input type="text" name="expire_date" value="<?php if (!empty($TodoItem[0]['expire_date'])) echo $TodoItem[0]['expire_date'] ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        完了
                    </td>
                    <td>
                        <input type="checkbox" name="check" <?php if (!empty($TodoItem[0]['finished_date'])) : ?> checked <?php endif ?>>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" value="更新">
                    </td>
            </form>
            <form action="./index.php">
                <td>
                    <input type="submit" value="キャンセル">
                </td>
            </form>
            </tr>
        </table>
    </div>
</body>

</html>