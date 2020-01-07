<?php
//session有効期限　一週間
session_set_cookie_params(604800);

require_once('config.php'); //データベース定数化
session_start();

/*
//すでにクッキーがあればmain.phpに飛ばす
if(isset($_SESSION['USERID'])){
	header('Location:main.php');
}
*/

$errorMessage="";
$successMessage="";

//新規登録ボタンが押された場合
//未入力は入力要求
if(isset($_POST["signup"])){
	//名前がセットされていれば
	if(empty($_POST["name"])){
		$errorMessage = "ニックネームが未入力です";
	}else if(empty($_POST["email"])){
		$errorMessage = "Emailが未入力です";
	}else if(empty($_POST["password"])){
		$errorMessage = "パスワードが未入力です";
	}
	// emailの重複とパスワードの桁数チェック
	function cheak($password,$count){
		if($count > 0){
		throw new Exception('そのメールアドレスは既に使用されています。');
		}
		if (strlen($password)< 8) {
		throw new Exception('パスワードは8桁以上で入力してください。');
		}
	}

	//入力した値が空で泣ければ　変数に代入
	if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password'])) {
		$username = $_POST['name'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		
		//データベースに接続
		try{
			$pdo = new PDO(DSN, DB_USER, DB_PASS);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//Emailで検索して検索結果があれば登録しているとみなす
			$sqlemail =  "select email from users where email='".$email."'";
			$ss = $pdo->query($sqlemail);
			$count = $ss->rowCount();
			cheak($password,$count);
			//ここから入力された値をデータベースに登録
			try {
				$stmt = $pdo -> prepare("insert into users (nickname, email, password, created) values (:username, :email, :password , now())");
				$stmt->bindParam(':username', $username);
				$stmt->bindValue(':email', $email);
				$stmt->bindValue(':password', $password);
				//実行
				$stmt->execute();
				$successMessage = '登録完了';
				header( "Location: http://192.168.33.10:8000/login.php" ) ;
			} catch (\Exception $e) {
				throw new Exception('登録済みのメールアドレスです。');
			}
		//接続できなかった場合
		} catch (Exception $e) {
			$errorMessage = $e->getMessage();
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
	<title>新規登録</title>
	<link rel="stylesheet" href="signup-styles.css">
</head>
<body>
	<div class="sign-form">
	<h1>SignUp</h1>
		<form action="" class="form-main" method="post">
			<div class="name-form">
				<input class="name" type="text" name="name" placeholder="nickname" required=""> 
			</div>
			<div class="email-form">
				<input class="email" type="email" name="email" placeholder="Email" required=""> 
			</div>
			<div class="pass-form">
				<input class="password" type="password" name="password" placeholder="Password" required="">
			</div>
			<input id="submit_button" type="submit" name="signup" value="新規登録">
		</form>
		<p class="err_msg"><?= $errorMessage; ?></p>
		<p class="scs_msg"><?= $successMessage; ?></p>
		<div class="not-account">
			アカウントをお持ちの方は<a href="login.php">こちら</a>
		</div>
	</div>
</body>
</html>