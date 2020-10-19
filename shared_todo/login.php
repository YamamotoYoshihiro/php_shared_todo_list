<?php
session_start();
session_regenerate_id();
$token = bin2hex(openssl_random_pseudo_bytes(32));
$_SESSION['token'] = $token;

// var_dump($_SESSION);
?>

<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous"> -->
  <title>shared_todo</title>
</head>

<body>
  <h3><u>ログイン</u></h3>
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
        <form action="./login_action.php" method="POST">
          <input type="hidden" name="token" value="<?= $token ?>">
          <label>ユーザー名</label>
      </td>
      <td>
        <input type="text" class="form-control" name="user">
      </td>
    </tr>
    <tr>
      <td>
        <label>パスワード</label>
      </td>
      <td>
        <input type="password" class="form-control" name="pass">
      </td>
    </tr>
    <tr>
      <td></td>
      <td>
        <input type="submit" class="btn" value="ログイン">
      </td>
    </tr>
    </form>
  </table>
</body>

</html>