<?php
require 'db.php';

$seat = trim($_POST['seat'] ?? '');
$time_slot = trim($_POST['time_slot'] ?? '');
$raw_student_id = trim($_POST['student_id'] ?? ''); // 表示用の元入力
$student_id = strtoupper($raw_student_id);          // DB用は大文字変換

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

// 空チェック
if ($seat === '' || $raw_student_id === '') {
    echo '情報が不足しています。<br>座席または学籍番号が空です。';
    echo '</div><div class="button-wrapper"><a href="index.php" class="back-button">トップに戻る</a></div>';
    exit;
}

// 英数字7桁のバリデーション（大文字小文字問わず）
if (!preg_match('/^[a-zA-Z0-9]{7}$/', $raw_student_id)) {
    echo '学籍番号は英数字7桁で入力してください。';
    echo '</div><div class="button-wrapper"><a href="index.php" class="back-button">トップに戻る</a></div>';
    exit;
}

try {
    // 重複チェック（DBは大文字化済みの学籍番号でチェック）
    $stmt = $db->prepare("SELECT COUNT(*) FROM entries WHERE seat = ? AND time_slot = ? AND student_id = ?");
    $stmt->execute([$seat, $time_slot, $student_id]);

    if ($stmt->fetchColumn() > 0) {
        echo 'すでにこの座席に応募済みです。';
    } else {
        // 応募データ挿入
        $stmt = $db->prepare("INSERT INTO entries (seat, student_id, time_slot) VALUES (?, ?, ?)");
        $stmt->execute([$seat, $student_id, $time_slot]);
        echo '応募が完了しました。ご応募ありがとうございました。';
    }

} catch (PDOException $e) {
    echo 'データベースエラーが発生しました。<br>';
    echo htmlspecialchars($e->getMessage());
}

echo '</div><div class="button-wrapper"><a href="index.php" class="back-button">トップに戻る</a></div>';
?>

</body>
</html>


