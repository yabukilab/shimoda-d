<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>抽選キャンセル</title>
  <link rel="stylesheet" href="style.css"> <!-- 上のCSSをstyle.cssに保存している想定 -->
</head>
<body>
  <h1>抽選キャンセル</h1>
  <p>下のフォームに学籍番号を入力し、「キャンセルする」ボタンを押してください。</p>

  <form action="cancel_confirm.php" method="POST">
    <input type="text" name="student_id" placeholder="学籍番号を入力" required>
    
    <div class="button-row">
      <a href="index.php" class="btn">戻る</a>
      <button type="submit" class="btn">キャンセル</button>
    </div>
  </form>
</body>
</html>

