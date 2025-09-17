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
    $pdo = null; // DB切断
    if (!$user) {
        // セッション情報が不正ならログイン画面へ
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
    <title>マイページ</title>
    <link rel="stylesheet" href="mypage.css" />
</head>
<body>
    <header>
        <img src="4eachblog_logo.jpg">
        <div class="log_out"><a href="log_out.php">ログアウト</a></div>
    </header>
    <div class="main">
        <form action="mypage_hensyu.php" method="get">
            <div class="form_contents">
                <h2>会員情報</h2>
                <p>こんにちは！<?= htmlspecialchars($user['name']) ?> さん</p>
                <div class="profile_contents">
                    <div>
                        <img src="<?= htmlspecialchars($user['picture']) ?>" alt="プロフィール画像" class="profile_image">
                    </div>
                    <div class="profile_text">
                        <div>氏名：<?= htmlspecialchars($user['name']) ?></div>
                        <div>メール：<?= htmlspecialchars($user['mail']) ?></div>
                        <div>ハッシュされたパスワード：<br><?= htmlspecialchars($user['password']) ?></div>
                    </div>
                </div>
                <div class="comments"><?= nl2br(htmlspecialchars($user['comments'])) ?></div>
                <div class="hensyu_button">
                    <button type="submit" class="submit_button" size="35">編集する</button>
                </div>
            </div>
        <form>
    </div>
    <footer>© 2018 InterNous.inc. All rights reserved</footer>
</body>
</html>