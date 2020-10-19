<?php
require_once('./class/db/Base.php');
require_once('./class/db/Users.php');
session_start();
session_regenerate_id();

echo $_SESSION['error'] = 'エラーが発生しました';
// unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>shared_todo</title>
</head>

<body>
  <form action="./logout.php" method="post">
    <input type="submit" class="btn" value="ログアウト">
  </form>
</body>

</html>