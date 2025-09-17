<?php
mb_internal_encoding("utf8");
require_once __DIR__ . '/config.php';

try {
    $pdo = get_pdo();
} catch (PDOException $e) {
    http_response_code(500);
    exit('DB接続失敗: ' . $e->getMessage());
}

$hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$stmt = $pdo->prepare('INSERT INTO login_mypage (name, mail, password, picture, comments) VALUES (?, ?, ?, ?, ?)');
$stmt->bindValue(1, $_POST['name']);
$stmt->bindValue(2, $_POST['mail']);
$stmt->bindValue(3, $hashed_password);
$stmt->bindValue(4, $_POST['path_filename']);
$stmt->bindValue(5, $_POST['comments']);

$stmt->execute();
$pdo = null;
header('Location: after_register.html');
