<?php
require 'db.php';

$student_id = $_GET['student_id'] ?? '';

if (empty($student_id)) {
    echo "学籍番号が入力されていません。";
    exit;
}

// 当選確認
$stmt = $db->prepare("SELECT seat, time_slot FROM reservations WHERE student_id = ?");
$stmt->execute([$student_id]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($results) {
    $status = "win";
} else {
    // 落選確認
    $stmt2 = $db->prepare("SELECT time_slot FROM entries WHERE student_id = ?");
    $stmt2->execute([$student_id]);
    $entry = $stmt2->fetch(PDO::FETCH_ASSOC);

    if ($entry) {
        $status = "lose";
    } else {
        $status = "no_entry";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>抽選結果</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="result-container">
  <h2>抽選結果</h2>

<?php if ($status === "win"): ?>
    <p>以下の座席に当選しました：</p>
    <table>
      <tr>
        <th>時間帯</th>
        <th>座席</th>
      </tr>
      <?php foreach ($results as $row): ?>
      <tr>
        <td><?= htmlspecialchars($row['time_slot']) ?></td>
        <td><?= htmlspecialchars($row['seat']) ?></td>
      </tr>
      <?php endforeach; ?>
    </table>

<?php elseif ($status === "lose"): ?>
    <p>残念ながら落選となりました。</p>
<?php else: ?>
    <p>応募がありません。</p>
<?php endif; ?>

  <div class="button-row">
    <button onclick="location.href='top.php'">トップへ戻る</button>
  </div>
</div>
</body>
</html>
