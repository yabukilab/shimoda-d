<?php

require 'db.php';


$student_id = $_POST['student_id'] ?? '';
if (empty($student_id)) {
  header("Location: cancel.php"); // 学籍番号がない場合は戻す
  exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>キャンセル確認</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>抽選キャンセルの確認</h1>
  <p class="confirm-message">学籍番号「<strong><?php echo htmlspecialchars($student_id); ?></strong>」の予約を本当にキャンセルしますか？</p>

  <h2>キャンセルしますか？</h2>
  <form action="cancel_action.php" method="POST">
    <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>">
    <div class="button-row">
      <button type="submit" class="btn">キャンセル</button>
      <a href="cancel.php" class="btn">戻る</a>
    </div>
  </form>
</body>
</html>

