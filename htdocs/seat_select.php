<?php
session_start();
require 'db.php';

$time_slot = '';
if (!empty($_POST['time_slot'])) {
    $time_slot = $_POST['time_slot'];
    $_SESSION['time_slot'] = $time_slot;
} elseif (!empty($_SESSION['time_slot'])) {
    $time_slot = $_SESSION['time_slot'];
} else {
    echo '時間帯が指定されていません。';
    exit;
}

$rows = range('a', 'l');
$cols = range(1, 16);

$emptySeats = [
  'a3','a6','a7','a8','a9','a10','a11','a14',
  'b7','b8','b9','b10',
  ...array_map(fn($n) => 'c' . $n, range(1, 16)),
  'd7','d8','d10','e7','e8','e10','f7','f8','f10','g7','g8','g10',
  ...array_map(fn($n) => 'h' . $n, range(1, 16)),
  'i3','i6','i7','i10','i11','i14','j3','j6','j7','j10','j11','j14',
  'k7','k10','l7','l10',
];

try {
    $stmt = $db->prepare('SELECT DISTINCT seat FROM entries WHERE time_slot = :slot');
    $stmt->execute(['slot' => $time_slot]);
    $appliedSeats = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    echo '<p>DB接続エラー: ' . htmlspecialchars($e->getMessage()) . '</p>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <title>座席表</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="seatmap.css">
</head>
<body>

<div class="back-button">
  <a href="time select.php">戻る</a>
</div>

<h2>座席表 (時間帯: <?= htmlspecialchars($time_slot) ?>)</h2>

<div class="seat-map">
<?php
foreach ($rows as $row) {
  foreach ($cols as $col) {
    $cellId = $row . $col;

    if (in_array($cellId, $emptySeats)) {
      echo '<div class="empty"></div>';

    } elseif (in_array($cellId, $appliedSeats)) {
      echo "<form method='GET' action='entry_form.php' style='display:inline; margin:0;'>
              <input type='hidden' name='seat' value='{$cellId}'>
              <input type='hidden' name='time_slot' value='" . htmlspecialchars($time_slot) . "'>
              <button type='submit' class='seat reserved' title='{$cellId}'></button>
            </form>";

    } else {
      echo "<form method='GET' action='entry_form.php' style='display:inline; margin:0;'>
              <input type='hidden' name='seat' value='{$cellId}'>
              <input type='hidden' name='time_slot' value='" . htmlspecialchars($time_slot) . "'>
              <button class='seat' title='{$cellId}'></button>
            </form>";
    }
  }
}
?>
</div>

</body>
</html>


