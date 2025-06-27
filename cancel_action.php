<?php
require_once 'db.php';

$student_number = $_POST['student_number'] ?? '';

echo '<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>キャンセル結果</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>キャンセル結果</h1>';

if (empty($student_number)) {
    echo '<p class="confirm-message">学籍番号が入力されていません。</p>';
} else {
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE student_number = ?");
    $stmt->execute([$student_number]);

    if ($stmt->rowCount() > 0) {
        echo '<p class="confirm-message">予約を正常にキャンセルしました。</p>';
    } else {
        echo '<p class="confirm-message">予約が見つかりませんでした。</p>';
    }
}

echo '<a href="index.php" class="back-button return-button">トップに戻る</a>
</body>
</html>';
?>


