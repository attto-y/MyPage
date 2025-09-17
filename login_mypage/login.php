<?php
session_start();
require_once __DIR__ . '/config.php';

// エラーメッセージ初期化
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_id = isset($_POST['mail']) ? $_POST['mail'] : '';
    $login_pass = isset($_POST['password']) ? $_POST['password'] : '';

    try {
        $pdo = get_pdo();

        $stmt = $pdo->prepare('SELECT * FROM login_mypage WHERE mail = ?');
        $stmt->execute([$login_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($login_pass, $user['password'])) {
            // 認証成功
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            // ログイン状態を保持（Remember me）: セレクタ + トークン方式
            if (!empty($_POST['login_keep'])) {
                $selector = bin2hex(random_bytes(6)); // 12文字
                $token = bin2hex(random_bytes(32));   // 64文字
                $tokenHash = hash('sha256', $token);
                $expiresAt = (new DateTimeImmutable('+' . (REMEMBER_COOKIE_EXPIRE) . ' seconds'));

                // DB保存
                $ins = $pdo->prepare('INSERT INTO remember_me_tokens (user_id, selector, token_hash, expires_at) VALUES (?, ?, ?, ?)');
                $ins->execute([$user['id'], $selector, $tokenHash, $expiresAt->format('Y-m-d H:i:s')]);

                // Cookieに保存（selector:token）
                $cookieValue = $selector . ':' . $token;
                setcookie(
                    REMEMBER_COOKIE_NAME,
                    $cookieValue,
                    [
                        'expires' => time() + REMEMBER_COOKIE_EXPIRE,
                        'path' => REMEMBER_COOKIE_PATH,
                        'secure' => REMEMBER_COOKIE_SECURE,
                        'httponly' => true,
                        'samesite' => REMEMBER_COOKIE_SAMESITE,
                    ]
                );
            } else {
                // 未チェックならCookie削除
                setcookie(REMEMBER_COOKIE_NAME, '', time() - 3600, '/');
            }
            header('Location: mypage.php');
            exit;
        } else {
            // 認証失敗
            $error = 'IDまたはパスワードが間違っています';
        }
    } catch (PDOException $e) {
        $error = 'データベース接続エラー: ' . $e->getMessage();
    }
}
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
        <form method="post" action="">
            <div class="form-contents">
                <?php if ($error): ?>
                    <p class="error"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
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