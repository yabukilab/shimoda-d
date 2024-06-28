<!DOCTYPE html>
<html>
<head>
    <title>エラー</title>
    <meta http-equiv="refresh" content="3;url=edit_delete_page.php">
</head>
<body>
    <h1>エラーが発生しました</h1>
    <p>
        <?php
        if (isset($_GET['message'])) {
            $message = urldecode($_GET['message']);
            echo nl2br(htmlspecialchars($message)) . "<br>3秒後に元のページにリダイレクトします。";
        }
        ?>
    </p>
</body>
</html>
