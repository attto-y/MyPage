<?php
// DB接続設定
define('DB_DSN', 'mysql:host=localhost;dbname=lesson01;charset=utf8');
define('DB_USER', 'root');
define('DB_PASS', '');

// Remember me 設定
define('REMEMBER_COOKIE_NAME', 'remember_me');
define('REMEMBER_COOKIE_EXPIRE', 60 * 60 * 24 * 30); // 30日
define('REMEMBER_COOKIE_PATH', '/');
define('REMEMBER_COOKIE_SECURE', false); 
define('REMEMBER_COOKIE_SAMESITE', 'Lax');

function get_pdo(): PDO {
    $pdo = new PDO(DB_DSN, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}
?>