<?php
require 'db.php';
try {
  $db = new PDO($dsn, $dbUser, $dbPass);
  # プリペアドステートメントのエミュレーションを無効にする．
  $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  # エラー→例外
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Can't connect to the database: " . h($e->getMessage());
}

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

echo '<a href="index.php" class="back-button return-button">トップに戻る</a>
</body>
</html>';
?>


