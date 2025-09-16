<?php
// register.php 会員登録フォーム
?>
<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>ログイン</title>
	<link rel="stylesheet" type="text/css" href="login.css" />
</head>

<body>
	<header>
		<img src="4eachblog_logo.jpg">
		<div class="login"><a href="login.php">ログイン</a></div>
	</header>

	<main>
		<form method="post">
			<div class="form-contents">
				<div class="mail">
					<label>メールアドレス</label><br>
					<input type="email" class="formbox" name="mail" size="40" pattern="^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required />
				</div>
				<div class="password">
					<label>パスワード</label><br>
					<input type="password" class="formbox" id="password" name="password" size="40" pattern="^[a-zA-Z0-9]{6,}$" required />
				</div>
                <div class="checkbox">
                    <input type="checkbox" class="formbox" name="login_keep" />ログイン状態を保持する
                </div>
				<div class="login_button">
					<input type="submit" class="submit_button" size="35" value="ログイン">
				</div>
			</div>
		</form>
	</main>

	<footer>© 2018 InterNous.inc. All rights reserved</footer>
	<script>

	</script>
</body>

</html>