<?php
// db.phpをインクルード
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'initialize_table') {
    try {
        // トランザクションを開始
        $db->beginTransaction();
        
        // コメントテーブルから削除
        $db->exec("DELETE FROM comments");

        // ゲームテーブルから削除
        $db->exec("DELETE FROM games");

        // コミット
        $db->commit();

        echo "テーブルの初期化が成功しました。";

    } catch (PDOException $e) {
        // ロールバック
        $db->rollBack();
        echo "エラー: " . htmlspecialchars($e->getMessage());
    }
}

try {
    // ゲームのデータを取得
    $game_sql = "SELECT * FROM games";
    $game_stmt = $db->query($game_sql);
    $games = $game_stmt->fetchAll(PDO::FETCH_ASSOC);

    // コメントのデータを取得
    $comment_sql = "SELECT * FROM comments";
    $comment_stmt = $db->query($comment_sql);
    $comments = $comment_stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h1>Games Table</h1>";
    if (count($games) > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Title</th><th>Image</th><th>Rating</th><th>Introduction</th><th>User Code</th><th>Added Date</th></tr>";
        foreach ($games as $game) {
            echo "<tr>";
            echo "<td>".$game['id']."</td>";
            echo "<td>".$game['title']."</td>";
            echo "<td><img src='data:image/jpeg;base64,".base64_encode($game['image'])."' width='100' /></td>";
            echo "<td>".$game['rating']."</td>";
            echo "<td>".$game['introduction']."</td>";
            echo "<td>".$game['user_code']."</td>";
            echo "<td>".$game['added_date']."</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No games found.</p>";
    }

    echo "<h1>Comments Table</h1>";
    if (count($comments) > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Game ID</th><th>Comment</th><th>User Name</th><th>Added Date</th></tr>";
        foreach ($comments as $comment) {
            echo "<tr>";
            echo "<td>".$comment['id']."</td>";
            echo "<td>".$comment['game_id']."</td>";
            echo "<td>".$comment['comment']."</td>";
            echo "<td>".$comment['user_name']."</td>";
            echo "<td>".$comment['added_date']."</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No comments found.</p>";
    }

} catch (PDOException $e) {
    echo "エラー: " . htmlspecialchars($e->getMessage());
}

// 接続を閉じる
$db = null;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>テーブルの初期化</title>
</head>
<body>

<!-- ここに既存のコンテンツ -->

<!-- フォームを追加 -->
<form method="post">
    <input type="hidden" name="action" value="initialize_table">
    <button type="submit">テーブルの初期化</button>
</form>

</body>
</html>
