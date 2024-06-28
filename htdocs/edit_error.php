<?php
$nowPage = isset($_GET['id']) && isset($_GET['user_code']) 
    ? "edit_delete.php" 
    : (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php');

$formParams = "";
if (isset($_GET['id']) && isset($_GET['user_code'])) {
    $formParams = "id=" . urlencode($_GET['id']) . "&user_code=" . urlencode($_GET['user_code']);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <title>エラー</title>
    <meta http-equiv="refresh" content="3;url=<?php echo htmlspecialchars($nowPage); ?>?<?php echo $formParams; ?>">
</head>
<body>
    <h1>エラーが発生しました</h1>
    <p>
    <?php
    if (isset($_GET['message'])) {
        $message = urldecode($_GET['message']);
        echo nl2br(htmlspecialchars($message)) . "<br>3秒後に前のページに戻ります。";
    }
    ?>
    </p>
    <?php if ($nowPage === "edit_delete.php"): ?>
    <form action="<?php echo htmlspecialchars($nowPage); ?>" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>">
        <input type="hidden" name="user_code" value="<?php echo htmlspecialchars($_GET['user_code'] ?? ''); ?>">
        <input type="submit" value="すぐに編集ページに戻る">
    </form>
    <?php endif; ?>
</body>
</html>

