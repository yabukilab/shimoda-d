<?php

require 'db.php';
<<<<<<< HEAD
=======

>>>>>>> a93ee4b625b0469e97a2346aa275908ead83c9e9

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
     <a href="cancel.php" class="btn">戻る</a>
     <button type="submit" class="btn">キャンセルする</button>
    </div>
  </form>
</body>
</html>

