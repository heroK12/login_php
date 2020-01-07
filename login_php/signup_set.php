<?php
//データベースに接続するかどうか
class signup{
  function __construct() {
    try{
      $pdo = new PDO(DSN, DB_USER, DB_PASS);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
    } catch (Exception $e) {
      $errorMessage = $e;
      return $errorMessage;
    }
  }
}