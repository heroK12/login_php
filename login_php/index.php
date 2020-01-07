<?php
  //session有効期限　一週間
  session_set_cookie_params(604800);

  require_once('config.php'); //データベース定数化
  session_start();

  if(isset($_SESSION['nickname'])){
    echo "ようこそ、".$_SESSION['nickname']."さん！";
  }else{
    header('Location:http://192.168.33.10:8000/login.php');
    exit;
  }

  if(isset($_POST["logout"])){
    session_destroy();
    header('Location:http://192.168.33.10:8000/login.php');
  }



?>

<DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>メイン画面</title>
</head>
<body>
  <form action="" class="form-main" method="post">
    <input id="logout_btn" type="submit" name="logout" value="ログアウト">
  </form>
</body>
</html>