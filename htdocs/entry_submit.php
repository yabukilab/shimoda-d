<?php
require 'db.php';

$seat = $_POST['seat'] ?? '';
$time_slot = $_POST['time_slot'] ?? '';
$student_id = $_POST['student_id'] ?? '';

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>応募結果</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .message-box {
      max-width: 600px;
      margin: 80px auto 40px auto;
      padding: 20px;
      font-size: 20px;
      border-radius: 8px;
      background-color: #fff;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      text-align: center;
      line-height: 1.6;
    }

    .button-wrapper {
      text-align: center;
      margin-top: 40px;
    }

    .back-button {
      display: inline-block;
      background-color: #444;
      color: white;
      border: none;
      padding: 12px 28px;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s, transform 0.2s;
      text-decoration: none;
    }

    .back-button:hover {
      background-color: #666;
      transform: translateY(-2px);
    }
  </style>
</head>
<body>

<?php
echo '<div class="message-box">';

if ($seat === '' || $student_id === '') {
    echo '情報が不足しています。<br>座席または学籍番号が空です。';
    echo '</div><div class="button-wrapper"><a href="index.php" class="back-button">トップに戻る</a></div>';
    exit;
}

try {
    // 重複チェック
    $stmt = $db->prepare("SELECT COUNT(*) FROM entries WHERE seat = ? AND time_slot = ? AND student_id = ?");
    $stmt->execute([$seat, $time_slot, $student_id]);

    if ($stmt->fetchColumn() > 0) {
        echo 'すでにこの座席に応募済みです。';
    } else {
        $stmt = $db->prepare("INSERT INTO entries (seat, student_id, time_slot) VALUES (?, ?, ?)");
        $stmt->execute([$seat, $student_id, $time_slot]);
        echo '応募が完了しました。ご応募ありがとうございました。';
    }

} catch (PDOException $e) {
    echo 'データベースエラーが発生しました。<br>';
    echo h($e->getMessage());
}

echo '</div><div class="button-wrapper"><a href="index.php" class="back-button">トップに戻る</a></div>';
?>

</body>
</html>
