<?php
// lottery_process_all.php

require 'db.php';

echo "抽選処理開始...\n";

// 使用不可席・全席設定
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

try {
    $stmt = $db->query("SELECT DISTINCT time_slot FROM entries");
    $time_slots = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($time_slots)) {
        echo "抽選対象時間帯がありません。\n";
        exit;
    }

    foreach ($time_slots as $time_slot) {
        echo "時間帯: {$time_slot} 抽選開始\n";

        // その時間帯の応募データ取得
        $stmt = $db->prepare("SELECT * FROM entries WHERE time_slot = ?");
        $stmt->execute([$time_slot]);
        $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($entries)) {
            echo "この時間帯の応募者はいません。\n";
            continue;
        }

        // 席ごとにグループ化
        $grouped = [];
        foreach ($entries as $entry) {
            $grouped[$entry['seat']][] = $entry;
        }

        foreach ($grouped as $seat => $applicants) {
            // その時間帯に既に予約済みか確認
            $stmtCheck = $db->prepare("SELECT COUNT(*) FROM reservations WHERE seat = ? AND time_slot = ?");
            $stmtCheck->execute([$seat, $time_slot]);
            $isReserved = $stmtCheck->fetchColumn() > 0;

            if ($isReserved) {
                echo "{$seat} ({$time_slot}) は既に予約済み、スキップ。\n";
                continue;
            }

            if (!in_array($seat, $availableSeatsBase)) {
                echo "{$seat} は使用不可席のためスキップ。\n";
                continue;
            }

            if (count($applicants) === 1) {
                // 応募者が1人だけ → 即当選
                $student_id = $applicants[0]['student_id'];
                $stmtInsert = $db->prepare("INSERT INTO reservations (student_id, seat, time_slot) VALUES (?, ?, ?)");
                $stmtInsert->execute([$student_id, $seat, $time_slot]);
                echo "{$student_id} が {$seat} ({$time_slot}) に即当選。\n";
            } else {
                // 複数応募 → 抽選
                $winner = $applicants[array_rand($applicants)];
                $student_id = $winner['student_id'];
                $stmtInsert = $db->prepare("INSERT INTO reservations (student_id, seat, time_slot) VALUES (?, ?, ?)");
                $stmtInsert->execute([$student_id, $seat, $time_slot]);
                echo "{$student_id} が {$seat} ({$time_slot}) に抽選当選。\n";

                // 外れた人は何もしない（落選）
            }
        }

        // 当選処理
$stmtDelete = $db->prepare("DELETE FROM entries WHERE student_id = ? AND time_slot = ?");
$stmtDelete->execute([$student_id, $time_slot]);


        echo "時間帯 {$time_slot} の抽選完了。\n";
    }

    echo "全ての抽選処理が完了しました。\n";

} catch (PDOException $e) {
    echo "DBエラー: " . htmlspecialchars($e->getMessage()) . "\n";
    exit;
}
?>

