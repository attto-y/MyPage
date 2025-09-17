<?php
session_start();
require_once __DIR__ . '/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id = $_SESSION['user_id'];
$name = isset($_POST['name']) ? $_POST['name'] : '';
$mail = isset($_POST['mail']) ? $_POST['mail'] : '';
$comments = isset($_POST['comments']) ? $_POST['comments'] : '';
$current_picture = isset($_POST['current_picture']) ? $_POST['current_picture'] : '';

// 画像アップロード処理
$picture_path = $current_picture;
if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['picture']['tmp_name'];
    $original_name = basename($_FILES['picture']['name']);
    $picture_path = './image/' . $original_name;
    move_uploaded_file($tmp_name, $picture_path);
}

try {
    $pdo = get_pdo();
    $stmt = $pdo->prepare('UPDATE login_mypage SET name = ?, mail = ?, comments = ?, picture = ? WHERE id = ?');
    $stmt->execute([$name, $mail, $comments, $picture_path, $id]);
    $pdo = null;
    // セッションの表示名も更新
    $_SESSION['user_name'] = $name;
    header('Location: mypage.php');
    exit;
} catch (Exception $e) {
    exit('エラー: ' . htmlspecialchars($e->getMessage()));
}
?>
