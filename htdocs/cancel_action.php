<?php
require 'db.php';

$student_id = $_POST['student_id'] ?? '';

echo '<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>キャンセル結果</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>キャンセル結果</h1>';

if (empty($student_id)) {
    echo '<p class="confirm-message">学籍番号が入力されていません。</p>';
} else {
    $stmt = $db->prepare("DELETE FROM entries WHERE student_id = ?");
    $stmt->execute([$student_id]);

    if ($stmt->rowCount() > 0) {
        echo '<p class="confirm-message">予約を正常にキャンセルしました。</p>';
    } else {
        echo '<p class="confirm-message">予約が見つかりませんでした。</p>';
    }
}

echo '<a href="top.php" class="back-button return-button">トップに戻る</a>
</body>
</html>';
?>


