<?php
// lottery_process_all.php

require 'db.php';

###########################
# 設定：実行トークン
###########################
$token = "your_secret_token_here"; // ★任意の強固な文字列に変更

###########################
# WEB実行時の制御
###########################
if (php_sapi_name() !== 'cli') {
    echo "<pre>";
    if (!isset($_GET['token']) || $_GET['token'] !== $token) {
        echo "❌ 権限がありません。抽選処理は実行されません。\n";
        echo "</pre>";
        exit;
    }
}

###########################
# DB接続確認
###########################
if (!isset($db) || $db === null) {
    echo "❌ DB接続に失敗しています。抽選処理を中止します。\n";
    if (php_sapi_name() !== 'cli') echo "</pre>";
    exit;
}

echo "✅ 抽選処理を開始します...\n";

###########################
# 使用不可席・全席設定
###########################
$rows = range('a', 'l');
$cols = range(1, 16);
$allSeats = [];
foreach ($rows as $r) {
    foreach ($cols as $c) {
        $allSeats[] = $r . $c;
    }
}
$emptySeats = [
    'a3','a6','a7','a8','a9','a10','a11','a14',
    'b7','b8','b9','b10',
    ...array_map(fn($n) => 'c' . $n, range(1, 16)),
    'd7','d8','d10','e7','e8','e10','f7','f8','f10','g7','g8','g10',
    ...array_map(fn($n) => 'h' . $n, range(1, 16)),
    'i3','i6','i7','i10','i11','i14','j3','j6','j7','j10','j11','j14',
    'k7','k10','l7','l10',
];
$availableSeatsBase = array_diff($allSeats, $emptySeats);

###########################
# 抽選処理開始
###########################
try {
    $stmt = $db->query("SELECT DISTINCT time_slot FROM entries");
    $time_slots = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($time_slots)) {
        echo "⚠️ 抽選対象の時間帯がありません。\n";
        if (php_sapi_name() !== 'cli') echo "</pre>";
        exit;
    }

    foreach ($time_slots as $time_slot) {
        echo "\n=== 時間帯: {$time_slot} の抽選開始 ===\n";

        $stmt = $db->prepare("SELECT * FROM entries WHERE time_slot = ?");
        $stmt->execute([$time_slot]);
        $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($entries)) {
            echo "⚠️ この時間帯の応募者はいません。\n";
            continue;
        }

        # 座席ごとに応募者グループ化
        $grouped = [];
        foreach ($entries as $entry) {
            $grouped[$entry['seat']][] = $entry;
        }

        foreach ($grouped as $seat => $applicants) {
            if (!in_array($seat, $availableSeatsBase)) {
                echo "⛔ {$seat} は使用不可席のためスキップ。\n";
                continue;
            }

            $stmtCheck = $db->prepare("SELECT COUNT(*) FROM reservations WHERE seat = ? AND time_slot = ?");
            $stmtCheck->execute([$seat, $time_slot]);
            if ($stmtCheck->fetchColumn() > 0) {
                echo "⛔ {$seat} ({$time_slot}) は既に予約済みのためスキップ。\n";
                continue;
            }

            # 抽選
            if (count($applicants) === 1) {
                $winner = $applicants[0];
                echo "✅ 即当選: {$winner['student_id']} -> {$seat} ({$time_slot})\n";
            } else {
                $winner = $applicants[array_rand($applicants)];
                echo "🎯 抽選当選: {$winner['student_id']} -> {$seat} ({$time_slot})\n";
            }

            # reservationsに登録
            $stmtInsert = $db->prepare("INSERT INTO reservations (student_id, seat, time_slot) VALUES (?, ?, ?)");
            $stmtInsert->execute([$winner['student_id'], $seat, $time_slot]);

            # entriesから当選者削除
            $stmtDelete = $db->prepare("DELETE FROM entries WHERE student_id = ? AND seat = ? AND time_slot = ?");
            $stmtDelete->execute([$winner['student_id'], $seat, $time_slot]);
        }

        echo "=== 時間帯: {$time_slot} の抽選終了 ===\n";
    }

    echo "\n✅ 抽選処理が完了しました。\n";

} catch (PDOException $e) {
    echo "❌ DBエラー: " . htmlspecialchars($e->getMessage()) . "\n";
}

if (php_sapi_name() !== 'cli') echo "</pre>";
?>

