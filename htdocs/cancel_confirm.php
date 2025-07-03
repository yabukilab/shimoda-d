<?php
// ✅ デバッグ用エラー表示（運用開始後は削除または無効化）
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';

$student_id = $_POST['student_id'] ?? '';

// HTML開始
echo '<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>キャンセル確認</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>キャンセル確認</h1>';

// ✅ 学籍番号未入力チェック
if (empty($student_id)) {
    echo '<p class="confirm-message">学籍番号が入力されていません。</p>';
    echo '<a href="index.php" class="back-button return-button">トップに戻る</a>';
    echo '</body></html>';
    exit;
}

// ✅ entriesテーブルから該当データ取得
$stmt_entries = $db->prepare("SELECT seat, time_slot FROM entries WHERE student_id = ?");
$stmt_entries->execute([$student_id]);
$entries = $stmt_entries->fetchAll(PDO::FETCH_ASSOC);

// ✅ reservationsテーブルから該当データ取得
$stmt_reservations = $db->prepare("SELECT seat, time_slot FROM reservations WHERE student_id = ?");
$stmt_reservations->execute([$student_id]);
$reservations = $stmt_reservations->fetchAll(PDO::FETCH_ASSOC);

// ✅ データが無い場合
if (empty($entries) && empty($reservations)) {
    echo '<p class="confirm-message">該当する予約が見つかりませんでした。</p>';
    echo '<a href="index.php" class="back-button return-button">トップに戻る</a>';
    echo '</body></html>';
    exit;
}

// ✅ データ表示
echo '<p class="confirm-message">以下の予約をキャンセルしますか？</p>';
echo '<table border="1" cellpadding="8">';
echo '<tr><th>学籍番号</th><th>座席</th><th>時間帯</th></tr>';

// entriesの表示
foreach ($entries as $row) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($student_id) . '</td>';
    echo '<td>' . htmlspecialchars($row['seat']) . '</td>';
    echo '<td>' . htmlspecialchars($row['time_slot']) . '</td>';
    echo '</tr>';
}

// reservationsの表示
foreach ($reservations as $row) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($student_id) . '</td>';
    echo '<td>' . htmlspecialchars($row['seat']) . '</td>';
    echo '<td>' . htmlspecialchars($row['time_slot']) . '</td>';
    echo '</tr>';
}

echo '</table>';

// ✅ キャンセル実行フォーム
echo '<form method="post" action="cancel_execute.php" style="margin-top:20px;">
        <input type="hidden" name="student_id" value="' . htmlspecialchars($student_id) . '">
        <button type="submit" class="btn confirm-btn">キャンセルする</button>
      </form>';

// 戻るボタン
echo '<a href="index.php" class="back-button return-button">トップに戻る</a>';

echo '</body></html>';
?>



