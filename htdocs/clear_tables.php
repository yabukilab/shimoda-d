<?php
require 'db.php';

$token = "your_secret_token_here";

header('Content-Type: text/plain; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "❌ POSTリクエスト以外は受け付けていません。";
    exit;
}

if (!isset($_POST['token']) || $_POST['token'] !== $token) {
    echo "❌ 権限がありません。削除処理は実行されません。";
    exit;
}

if (!isset($db) || $db === null) {
    echo "❌ DB接続に失敗しています。削除処理を中止します。";
    exit;
}

try {
    $db->exec("DELETE FROM entries");
    $db->exec("DELETE FROM reservations");
    echo "✅ entries と reservations の全データを削除しました。";
} catch (PDOException $e) {
    echo "❌ DBエラー: " . htmlspecialchars($e->getMessage());
    exit;
}
?>
