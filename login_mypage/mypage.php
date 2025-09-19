<?php
session_start();
require_once __DIR__ . '/config.php';

// セッションが無ければ、Remember Me Cookieで自動ログインを試行
if (!isset($_SESSION['user_id']) && isset($_COOKIE[REMEMBER_COOKIE_NAME])) {
    try {
        [$selector, $token] = explode(':', $_COOKIE[REMEMBER_COOKIE_NAME], 2) + [null, null];
        if ($selector && $token) {
            $pdo = get_pdo();
            $stmt = $pdo->prepare('SELECT * FROM remember_me_tokens WHERE selector = ? AND expires_at > NOW()');
            $stmt->execute([$selector]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && hash_equals($row['token_hash'], hash('sha256', $token))) {
                // ユーザー再取得
                $u = $pdo->prepare('SELECT * FROM login_mypage WHERE id = ?');
                $u->execute([$row['user_id']]);
                $autoUser = $u->fetch(PDO::FETCH_ASSOC);
                if ($autoUser) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $autoUser['id'];
                    $_SESSION['user_name'] = $autoUser['name'];

                    // トークンローテーション + 期限延長
                    $newToken = bin2hex(random_bytes(32));
                    $newHash = hash('sha256', $newToken);
                    $upd = $pdo->prepare('UPDATE remember_me_tokens SET token_hash = ?, expires_at = DATE_ADD(NOW(), INTERVAL 30 DAY) WHERE id = ?');
                    $upd->execute([$newHash, $row['id']]);
                    setcookie(
                        REMEMBER_COOKIE_NAME,
                        $selector . ':' . $newToken,
                        [
                            'expires' => time() + REMEMBER_COOKIE_EXPIRE,
                            'path' => REMEMBER_COOKIE_PATH,
                            'secure' => REMEMBER_COOKIE_SECURE,
                            'httponly' => true,
                            'samesite' => REMEMBER_COOKIE_SAMESITE,
                        ]
                    );
                }
            }
            $pdo = null;
        }
    } catch (Throwable $e) {
        // 何もしない（通常の未ログイン扱い）
    }
}

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