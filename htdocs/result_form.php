<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>抽選結果確認</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
  <h2>抽選結果確認</h2>
  <p class="confirm-message">学籍番号を入力してください</p>
  <form method="GET" action="show_result.php">
    <div class="input-group">
      <label for="student_id">学籍番号</label><br>
      <input type="text" id="student_id" name="student_id" required>
    </div>

    <div class="button-row">
      <button type="button" class="btn back-btn" onclick="location.href='top.php'">戻る</button>
      <button type="submit" class="btn confirm-btn">確認</button>
    </div>
  </form>
</div>
</body>
</html>

