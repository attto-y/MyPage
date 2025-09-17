<?php
session_start();
require_once __DIR__ . '/config.php';

if (!isset($_SESSION['user_id'])) {
	header('Location: login.php');
	exit;
}

try {
	$pdo = get_pdo();
	$stmt = $pdo->prepare('SELECT * FROM login_mypage WHERE id = ?');
	$stmt->execute([$_SESSION['user_id']]);
	$user = $stmt->fetch(PDO::FETCH_ASSOC);
	$pdo = null;
	if (!$user) {
		session_unset();
		session_destroy();
		header('Location: login.php');
		exit;
	}
} catch (Exception $e) {
	exit('エラー: ' . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>会員情報編集</title>
	<link rel="stylesheet" href="mypage_hensyu.css" />
</head>
<body>
	<header>
		<img src="4eachblog_logo.jpg">
		<div class="log_out"><a href="log_out.php">ログアウト</a></div>
	</header>
	<div class="main">
		<form action="mypage_update.php" method="post" enctype="multipart/form-data">
			<div class="form_contents">
				<h2>会員情報編集</h2>
                <p>こんにちは！<?= htmlspecialchars($user['name']) ?> さん</p>
                <div class="profile_contents">
                    <div>
                        <img src="<?= htmlspecialchars($user['picture']) ?>" alt="プロフィール画像" class="profile_image">
                    </div>
                    <div class="profile_text">
                        <div>氏名：<input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required></div>
                        <div>メール：<input type="email" name="mail" value="<?= htmlspecialchars($user['mail']) ?>" required></div>
                        <div>パスワード：<input type="password" name="password" value="" placeholder="新しいパスワードを入力してください"></div>
                        <div>プロフィール画像：<input type="file" name="picture" accept="image/*"><input type="hidden" name="current_picture" value="<?= htmlspecialchars($user['picture']) ?>"></div>
                    </div>
                </div>
				<div class="comments">コメント：<br><textarea name="comments" rows="4" cols="40"><?= htmlspecialchars($user['comments']) ?></textarea></div>
				<div class="hensyu_button">
					<button type="submit" class="submit_button">更新する</button>
				</div>
			</div>
		</form>
	</div>
	<footer>© 2018 InterNous.inc. All rights reserved</footer>
</body>
</html>
