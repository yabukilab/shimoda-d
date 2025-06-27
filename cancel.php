<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>予約キャンセル</title>
  <link rel="stylesheet" href="style.css"> <!-- 上のCSSをstyle.cssに保存している想定 -->
</head>
<body>
  <h1>予約キャンセル</h1>
  <p>下のフォームに学籍番号を入力し、「キャンセルする」ボタンを押してください。</p>

  <form action="cancel_action.php" method="POST">
    <input type="text" name="student_number" placeholder="学籍番号を入力" required>
    
    <div class="button-row">
      <button type="submit" class="btn">キャンセルする</button>
      <a href="index.php" class="btn">戻る</a>
    </div>
  </form>
</body>
</html>

