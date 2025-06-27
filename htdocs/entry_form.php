<?php
// entry_form.php
$seat = $_GET['seat'] ?? '';
$time_slot = $_GET['time_slot'] ?? '';

if ($seat === '' || $time_slot === '') {
    echo "席情報が不足しています。";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>抽選応募</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-container">
  <h2>抽選応募フォーム</h2>
  <p class="confirm-message">座席: <?= htmlspecialchars($seat) ?> / 時間帯: <?= htmlspecialchars($time_slot) ?></p>

  <form method="POST" action="entry_submit.php">
    <div class="input-group">
      <label for="student_id">学籍番号</label><br>
      <input type="text" id="student_id" name="student_id" required>
    </div>

    <input type="hidden" name="seat" value="<?= htmlspecialchars($seat) ?>">
    <input type="hidden" name="time_slot" value="<?= htmlspecialchars($time_slot) ?>">

    <div class="button-row">
      <button type="button" class="btn back-btn" onclick="history.back()">戻る</button>
      <button type="submit" class="btn confirm-btn">応募する</button>
    </div>
  </form>
</div>

</body>
</html>


