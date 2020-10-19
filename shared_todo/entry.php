<?php
session_start();
session_regenerate_id();
require_once('./class/db/Base.php');
require_once('./class/db/TodoItems.php');
require_once('./class/db/Users.php');
try {
  if (!isset($_SESSION['user'])) {
    header('Location: ./login.php');
  }
  $token = bin2hex(openssl_random_pseudo_bytes(32));
  $_SESSION['token'] = $token;

  $TodoItems = new TodoItems();
  $listId = $TodoItems->selectId();
} catch (Exception $e) {
  header('Location:./error.php');
}

?>
<!-- <pre><?= var_dump($listId) ?></pre> -->
<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous"> -->
  <title>shared todo</title>
</head>


<body>
  <h3><u>作業登録</u></h3>
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
        <?php if (isset($_SESSION['error_item_name'])) : ?>
          <span style="color: #ff0000;"><?= $_SESSION['error_item_name'] ?></span>
        <?php endif ?>
      </td>
    </tr>
    <tr>
      <td>
        <?php if (isset($_SESSION['error_expire_date'])) : ?>
          <span style="color: #ff0000;"><?= $_SESSION['error_expire_date'] ?></span>
        <?php endif ?>
      </td>
    </tr>

    <form action="./entry_action.php" method="POST">
      <input type="hidden" name="token" value="<?= $token ?>">
      <input type="hidden" name="registration_date" value="<?= date('Y/m/d') ?>">
      <tr>
        <td>
          項目名
        </td>
        <td>
          <input type="text" name="item_name" value="">
        </td>
      </tr>
      <tr>
        <td>
          担当者
        </td>
        <td>
          <select name="user_id">
            <?php foreach ($listId as $key => $value) : ?>
              <option value="<?= $value['id'] ?>">
                <?= $value['user'] ?>
              </option>
            <?php endforeach ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>
          期限
        </td>
        <td>
          <!-- <input type="date" name="expire_date" value="<?php $day = new DateTime(null, new DateTimeZone('Asia/Tokyo'));
                                                            echo $day->format('Y-m-d'); ?>"> -->
          <input type="test" name="expire_date" value="">
        </td>
      </tr>
      <tr>
        <td>
          完了
        </td>
        <td>
          <input type="checkbox" name="check" value="1">
        </td>
      </tr>
      <tr>
        <td>
          <input type="submit" value="登録">
        </td>
    </form>
    <form action="./index.php">
      <td>
        <input type="submit" value="キャンセル">
      </td>
      </tr>
    </form>
  </table>
  </div>
</body>

</html>