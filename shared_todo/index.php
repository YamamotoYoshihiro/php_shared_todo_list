<?php
session_start();
session_regenerate_id();
require_once('./class/db/Base.php');
require_once('./class/db/TodoItems.php');

$token = bin2hex(openssl_random_pseudo_bytes(32));
$_SESSION['token'] = $token;

//インスタンス化
$TodoItems = new TodoItems();
$list = $TodoItems->selectJoin();

if (!isset($_SESSION['user'])) {
    header('Location: ./login.php');
}
var_dump($_SESSION['error_item_name']);
?>

<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <title>shared todo</title>
</head>

<?php if (!isset($_SESSION['user'])) : ?>
    <?php header('Location: ./login.php') ?>
<?php endif ?>

<body>
    <div class="container">
        <table>
            <tr>
                <?php if (isset($_SESSION['error'])) : ?>
                    <span style="color: #ff0000;"><?= $_SESSION['error'] ?></span><br>
                <?php endif ?>
            </tr>
            <tr>
                <td>
                    <u>
                        <h3>作業一覧</h3>
                    </u><br>
                </td>
                <td width="150"></td>
                <td>
                    ようこそ
                    <?php if (isset($_SESSION['user'])) : ?>
                        <?= $_SESSION['user']['family_name'] ?>
                        <?= $_SESSION['user']['first_name'] ?>
                        <?php endif ?>さん
                </td>
                <td>
                    <form action="./logout.php" method="post">
                        <input type="hidden" name="token" value="<?= $token ?>">
                        <input type="submit" class="btn" value="ログアウト"><br>
                    </form>
                </td>
            </tr>
            <tr>
                <td>
                    <form action="./entry.php" method="post">
                        <input type="submit" class="btn" value="作業登録">
                    </form>
                </td>
                <td></td>
                <td>
                    <form action="./search.php" method="post">
                        <input type="hidden" name="token" value="<?= $token ?>">
                        <input type="text" name="keywords" value="">
                        <input type="submit" class="btn" value="検索">
                    </form>
                </td>
            </tr>
        </table>
        <table border="1">
            <tr>
                <th>項目名</th>
                <th>担当者</th>
                <th>登録日</th>
                <th>期限日</th>
                <th>完了日</th>
                <th>操作</th>
            </tr>
            <?php foreach ($list as $k => $v) : ?>
                <?php foreach (array($k) as $value) : ?>
                    <?php if ($value % 2 == 1) : ?>
                        <tr style="background-color:#CCCCFF">
                            <?php if ($v['expire_date'] < date('Y-m-d') && !isset($v['finished_date'])) : ?>
                                <td>
                                    <span style="color:#ff0000;">
                                        <?= $v['item_name'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span style="color:#ff0000;">
                                        <?= $v['user'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span style="color:#ff0000;">
                                        <?= $v['registration_date'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span style="color:#ff0000;">
                                        <?= $v['expire_date'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (isset($v['finished_date'])) : ?>
                                        <span style="color:#ff0000;">
                                            <?= $v['finished_date'] ?>
                                        </span>
                                    <?php else : ?>
                                        <span style="color:#ff0000;">
                                            <?= '未' ?>
                                        </span>
                                    <?php endif ?>
                                </td>
                            <?php else : ?>
                                <td>
                                    <?= $v['item_name'] ?>
                                </td>
                                <td>
                                    <?= $v['user'] ?>
                                </td>
                                <td>
                                    <?= $v['registration_date'] ?>
                                </td>
                                <td>
                                    <?= $v['expire_date'] ?>
                                </td>
                                <td>
                                    <?php if (isset($v['finished_date'])) : ?>
                                        <?= $v['finished_date'] ?>
                                    <?php else : ?>
                                        <?= '未' ?>
                                    <?php endif ?>
                                </td>
                            <?php endif ?>

                        <?php else : ?>
                        <tr>
                            <?php if ($v['expire_date'] < date('Y-m-d') && !isset($v['finished_date'])) : ?>
                                <td>
                                    <span style="color:#ff0000;">
                                        <?= $v['item_name'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span style="color:#ff0000;">
                                        <?= $v['user'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span style="color:#ff0000;">
                                        <?= $v['registration_date'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span style="color:#ff0000;">
                                        <?= $v['expire_date'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (isset($v['finished_date'])) : ?>
                                        <span style="color:#ff0000;">
                                            <?= $v['finished_date'] ?>
                                        </span>
                                    <?php else : ?>
                                        <span style="color:#ff0000;">
                                            <?= '未' ?>
                                        </span>
                                    <?php endif ?>
                                </td>
                            <?php else : ?>
                                <td>
                                    <?= $v['item_name'] ?>
                                </td>
                                <td>
                                    <?= $v['user'] ?>
                                </td>
                                <td>
                                    <?= $v['registration_date'] ?>
                                </td>
                                <td>
                                    <?= $v['expire_date'] ?>
                                </td>
                                <td>
                                    <?php if (isset($v['finished_date'])) : ?>
                                        <?= $v['finished_date'] ?>
                                    <?php else : ?>
                                        <?= '未' ?>
                                    <?php endif ?>
                                </td>
                            <?php endif ?>
                        <?php endif ?>

                        <td>
                            <form action="./done.php" method="POST">
                                <input type="hidden" name="token" value="<?= $token ?>">
                                <input type="hidden" name="id" value="<?= $v['id'] ?>">
                                <input type="submit" name="done" value="完了" ?>
                            </form>
                            <form action="./edit.php" method="POST">
                                <input type="hidden" name="token" value="<?= $token ?>">
                                <input type="hidden" name="id" value="<?= $v['id'] ?>">
                                <input type="submit" name="update" value="更新">
                            </form>
                            <form action="./delete.php" method="POST">
                                <input type="hidden" name="token" value="<?= $token ?>">
                                <input type="hidden" name="id" value="<?= $v['id'] ?>">
                                <input type="submit" name="delete" value="削除">
                            </form>
                        </td>
                    <?php endforeach ?>
                <?php endforeach ?>
        </table>
    </div>
</body>

</html>