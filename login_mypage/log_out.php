<?php
session_start();
require_once __DIR__ . '/config.php';

// Remember me CookieがあればDB上のトークンも失効させる
if (isset($_COOKIE[REMEMBER_COOKIE_NAME])) {
    try {
        [$selector, $token] = explode(':', $_COOKIE[REMEMBER_COOKIE_NAME], 2) + [null, null];
        if ($selector) {
            $pdo = get_pdo();
            $del = $pdo->prepare('DELETE FROM remember_me_tokens WHERE selector = ?');
            $del->execute([$selector]);
        }
    } catch (Throwable $e) {
        // ログアウト処理は継続
    }
    setcookie(REMEMBER_COOKIE_NAME, '', time() - 3600, '/');
}

// すべてのセッション変数を削除
$_SESSION = [];

// セッションクッキーの削除
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

// セッション破棄
session_destroy();

header('Location: login.php');
exit;
?>
