<?php
// confirm.php

// POSTで送られてきた値を受け取る
$seat = $_POST['seat'] ?? '';
$student_id = $_POST['student_id'] ?? '';
$time_slot = $_POST['time_slot'] ?? date('Y-m-d') .; // ここは必要に応じて調整

// 入力チェック（最低限）
if ($seat === '' || $student_id === '') {
    echo "座席か学籍番号が送信されていません。";
    echo '<br><a href="seat_select.php">座席表に戻る</a>';
    exit;
}

 require 'db.php';

try {
    // データベース接続
    //$pdo = new PDO('mysql:host=localhost;dbname=ens;charset=utf8', 'testuser', 'pass');
    $pdo = $db;
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 予約済みかチェック（重複防止）
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM entries WHERE seat = ? AND time_slot = ?");
    $stmt->execute([$seat, $time_slot]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo "この座席はすでに予約されています。";
        echo '<br><a href="seat_select.php">座席表に戻る</a>';
        exit;
    }

    // データ挿入
    $stmt = $pdo->prepare("INSERT INTO entries (seat, student_id, time_slot,) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$seat, $student_id, $time_slot]);

    echo "<h2>予約完了！</h2>";
    echo "<p>座席: {$seat} を学籍番号: {$student_id} で予約しました。<br>時間帯: {$time_slot}</p>";
    echo '<br><a href="seat_select.php">座席表に戻る</a>';
    echo '<br><a href="index.php">トップページへ戻る</a>';

} catch (PDOException $e) {
    echo "データベースエラー: " . htmlspecialchars($e->getMessage());
    echo '<br><a href="seat_select.php">座席表に戻る</a>';
}
?>
