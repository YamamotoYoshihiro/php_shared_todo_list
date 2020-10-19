<?php
require_once('Base.php');
class Users extends Base
{
  public function __construct()
  {
    parent::__construct();
  }
  public function login($user)
  {
    $sql = 'SELECT * FROM users WHERE user=:user';
    $stmt = $this->dbh->prepare($sql);
    $stmt->bindValue(':user', $user, PDO::PARAM_STR);
    $stmt->execute();
    $ret = $stmt->fetch(PDO::FETCH_ASSOC);
    return $ret;
  }
  public function loginInsert($user, $pass, $family_name, $first_name)
  {
    $sql = "INSERT INTO users(user, pass, family_name, first_name) VALUES (:user, :pass, :family_name, :first_name)";
    $stmt = $this->dbh->prepare($sql);
    $stmt->bindValue(":user", $user, PDO::PARAM_STR);
    $stmt->bindValue(":pass", $pass, PDO::PARAM_STR);
    $stmt->bindValue(":family_name", $family_name, PDO::PARAM_STR);
    $stmt->bindValue(":first_name", $first_name, PDO::PARAM_STR);
    $stmt->execute();
  }
  
}
