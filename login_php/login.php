<?php
//session有効期限　一週間
session_set_cookie_params(604800);

require_once('config.php'); //データベース定数化
session_start();

$errorMessage="";
$successMessage="";


if(isset($_POST["login"])){
	//名前がセットされていれば
	if(empty($_POST["email"])){
		$errorMessage = "Emailが未入力です";
	}else if(empty($_POST["password"])){
		$errorMessage = "パスワードが未入力です";
	}
	if (!empty($_POST['email']) && !empty($_POST['password'])) {
		$email = $_POST['email'];
		$password = $_POST['password'];
		
		try{
			
			//mysql 接続
			$pdo = new PDO(DSN, DB_USER, DB_PASS);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//メールアドレスとパスワードをチェック
			$sql =  "select nickname,email,password from users where email='".$email."' and password = '".$password."'";
			$ss = $pdo->query($sql);
			$row = $ss->rowCount();

			//データベースに登録されていない場合
			if($row==0){
				throw new Exception();
			}
			
			$ss_Set = $ss->fetchAll();
			$_SESSION['nickname'] = $ss_Set[0]['nickname'];
			$_SESSION['email'] = $ss_Set[0]['email'];
			$_SESSION['password'] = $ss_Set[0]['password'];
			
			header('Location: http://192.168.33.10:8000');
			$successMessage = "ログインに成功しました。";
		}catch(Exception $e){
			$errorMessage = "メールアドレスまたはパスワードが間違えています。";
		}
	}
}

function h($s){
  return htmlspecialchars($s, ENT_QUOTES, 'utf-8');
}

?>
<!DOCTYPE html>
<html lang=ja>
<head>
	<meta charset="utf-8">
	<title>ログイン画面</title>
	<link rel="stylesheet" href="login-styles.css">
</head>
<body>
	<div class="login-form">
	<h1>Login</h1>
		<form action="" class="form-main" method="post">
			<div class="email-form">
				<input class="email" type="email" name="email" placeholder="Email" required=""> 
			</div>
			<div class="pass-form">
				<input class="password" type="password" name="password" placeholder="Password" required="">
			</div>
			<input id="submit_button" type="submit" name="login" value="ログイン">
		</form>
		<p class="err_msg"><?= $errorMessage; ?></p>
		<p class="scs_msg"><?= $successMessage; ?></p>
		<div class="not-account">
			新規登録は<a href="/signup.php">こちら</a>
		</div>
	</div>
</body>
</html>