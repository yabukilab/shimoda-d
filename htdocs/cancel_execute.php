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
    $stmt_entries = $db->prepare("DELETE FROM entries WHERE student_id = ?");
    $stmt_entries->execute([$student_id]);

    $stmt_reservations = $db->prepare("DELETE FROM reservations WHERE student_id = ?");
    $stmt_reservations->execute([$student_id]);

    if ($stmt_entries->rowCount() > 0 || $stmt_reservations->rowCount() > 0) {
        echo '<p class="confirm-message">予約をキャンセルしました。</p>';
    } else {
        echo '<p class="confirm-message">キャンセル対象の予約が見つかりませんでした。</p>';
    }
}

echo '<a href="index.php" class="back-button return-button">トップに戻る</a>';
echo '</body></html>';
?>

