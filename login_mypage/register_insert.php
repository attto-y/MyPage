<?php
mb_internal_encoding("utf8");
$dsn = "mysql:host=localhost;dbname=lesson01;charset=utf8";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, 'root', '', $options);
} catch (PDOException $e) {
    http_response_code(500);
    exit('DB接続失敗: ' . $e->getMessage());
}

$stmt->bindValue(1, $_POST['name']);
$stmt->bindValue(2, $_POST['mail']);
$stmt->bindValue(3, $_POST['password']);
$stmt->bindValue(4, $_POST['path_filename']);
$stmt->bindValue(5, $_POST['comments']);

$stmt->execute();
$pdo = null;
header('Location: after_register.php');
?>