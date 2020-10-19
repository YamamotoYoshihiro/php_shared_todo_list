<?php
class Base
{
    const DB_NAME = 'todo';
    const HOST = 'localhost';
    const USER = 'root';
    const PASS = '';
    protected $dbh;

    public function __construct()
    {
        try {
            $dsn = "mysql:dbname=" . self::DB_NAME . ";host=" . self::HOST . ";charset=utf8";
            $this->dbh = new PDO($dsn, self::USER, self::PASS);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            header("Location: ./error.php");
        }
    }
}
