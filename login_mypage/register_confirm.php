<?php
mb_internal_encoding("utf8");
$temp_pic_name = $_FILES['picture']['tmp_name'];
$original_pic_name = $_FILES['picture']['name'];
$path_filename = './image/'.$original_pic_name;
move_uploaded_file($temp_pic_name, $path_filename);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <title>マイページ登録確認</title>
    <link rel="stylesheet" type="text/css" href="register_confirm.css" />
</head>
<body>
    <header>
        <img src="4eachblog_logo.jpg">
        <div class="login"><a href="login.php">ログイン</a></div>
    </header>
    <div class="main">
        <form action="register_complete.php" method="post">
            <h2>会員登録 確認</h2>
            <p>こちらの内容で登録してもよろしいでしょうか？</p>
            <div class="form_contents">
                <div class="name">名前: <?php echo htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="mail">メール: <?php echo htmlspecialchars($_POST['mail'], ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="password">パスワード: <?php echo htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="picture">プロフィール画像: <img src="<?php echo htmlspecialchars($path_filename, ENT_QUOTES, 'UTF-8'); ?>" alt="プロフィール画像" /></div>
                <div class="comments">コメント: <?php echo nl2br(htmlspecialchars($_POST['comments'], ENT_QUOTES, 'UTF-8')); ?></div>
            </div>
            <div class="toroku">
                <input type="submit" value="登録" class="submit_button" />
            </div>
        </form>
    </div>
    <footer>
        <p>&copy; 2023 MyPage</p>
    </footer>
</body>
</html>