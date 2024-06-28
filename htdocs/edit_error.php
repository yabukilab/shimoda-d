<!DOCTYPE html>
<html>
<head>
    <title>エラー</title>
    <?php
    session_start();
    // セッションから前ページのURLを取得
    $previousPage = isset($_SESSION['previous_page']) ? $_SESSION['previous_page'] : 'index.php';
    ?>
    <meta http-equiv="refresh" content="3;url=<?php echo htmlspecialchars($previousPage); ?>">
</head>
<body>
    <h1>エラーが発生しました</h1>
    <p><?php
        if (isset($_GET['message'])) {
            $message = urldecode($_GET['message']);
            echo nl2br(htmlspecialchars($message)) . "<br>3秒後に前のページに戻ります。";
        }
    ?></p>
</body>
</html>

