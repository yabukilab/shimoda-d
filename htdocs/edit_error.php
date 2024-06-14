<!DOCTYPE html>
<html>
<head>
    <title>エラー</title>
</head>
<body>
    <h1>エラーが発生しました</h1>
    <p><?php
        if (isset($_GET['message'])) {
            $message = urldecode($_GET['message']);
            echo nl2br(htmlspecialchars($message));
        }
    ?></p>
    <a href="index.php">トップページに戻る</a>
</body>
</html>
