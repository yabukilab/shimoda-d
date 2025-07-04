<?php
require 'db.php';

$student_id = $_GET['student_id'] ?? '';

if (empty($student_id)) {
    echo "学籍番号が入力されていません。";
    exit;
}

// reservations から lottery_status を確認
$stmt = $db->prepare("SELECT seat, time_slot, lottery_status FROM reservations WHERE student_id = ?");
$stmt->execute([$student_id]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$status = "";
if ($results) {
    // lottery_status の確認
    if ($results[0]['lottery_status'] == 1) {
        $status = "win";
    } elseif ($results[0]['lottery_status'] == 2) {
        $status = "lose";
    } else {
        $status = "reserved"; // 管理者予約など抽選経由でない場合
    }
} else {
    // 抽選待ち確認
    $stmt2 = $db->prepare("SELECT time_slot FROM entries WHERE student_id = ?");
    $stmt2->execute([$student_id]);
    $entry = $stmt2->fetch(PDO::FETCH_ASSOC);

    if ($entry) {
        $status = "waiting";
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
  <style>
    .result-container {
        max-width: 600px;
        margin: 80px auto;
        text-align: center;
        padding: 30px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .result-container h2 {
        margin-bottom: 30px;
        font-size: 28px;
    }
    .result-message {
        font-size: 20px;
        margin-bottom: 30px;
    }
    table {
        margin: 0 auto 30px auto;
        border-collapse: collapse;
        width: 100%;
        max-width: 500px;
    }
    table th, table td {
        border: 1px solid #ccc;
        padding: 10px;
        font-size: 18px;
    }
    table th {
        background-color: #f0f0f0;
    }
    .button-row {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
    }
    .btn {
        background-color: #444;
        color: white;
        border: none;
        padding: 12px 28px;
        font-size: 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
    }
    .btn:hover {
        background-color: #666;
        transform: translateY(-2px);
    }
  </style>
</head>
<body>
<div class="result-container">
  <h2>抽選結果</h2>

<?php if ($status === "win"): ?>
    <p class="result-message">以下の座席に当選しました。</p>
    <table>
      <tr>
        <th>時間帯</th>
        <th>座席</th>
      </tr>
      <?php foreach ($results as $row): ?>
        <?php if ($row['lottery_status'] == 1): ?>
          <tr>
            <td><?= h($row['time_slot']) ?></td>
            <td><?= h($row['seat']) ?></td>
          </tr>
        <?php endif; ?>
      <?php endforeach; ?>
    </table>

<?php elseif ($status === "lose"): ?>
    <p class="result-message">残念ながら落選となりました。</p>

<?php elseif ($status === "waiting"): ?>
    <p class="result-message">抽選待ちです。結果が確定するまでお待ちください。</p>

<?php elseif ($status === "reserved"): ?>
    <p class="result-message">通常予約が登録されています。</p>
    <table>
      <tr>
        <th>時間帯</th>
        <th>座席</th>
      </tr>
      <?php foreach ($results as $row): ?>
          <tr>
            <td><?= h($row['time_slot']) ?></td>
            <td><?= h($row['seat']) ?></td>
          </tr>
      <?php endforeach; ?>
    </table>

<?php else: ?>
    <p class="result-message">応募がありません。</p>
<?php endif; ?>

  <div class="button-row">
    <button class="btn" onclick="location.href='index.php'">トップへ戻る</button>
  </div>
</div>
</body>
</html>


