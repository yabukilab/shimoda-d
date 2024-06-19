<?php
// db.phpをインクルード
require 'db.php';

try {
    // ゲームテーブルのデータを取得
    $sqlGames = "SELECT * FROM games";
    $stmtGames = $db->query($sqlGames);
    $games = $stmtGames->fetchAll(PDO::FETCH_ASSOC);

    // コメントテーブルのデータを取得
    $sqlComments = "SELECT * FROM comments";
    $stmtComments = $db->query($sqlComments);
    $comments = $stmtComments->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    echo "エラー: " . $e->getMessage();
    die();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>データベース内容確認</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .table-container {
            margin-bottom: 40px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h2 {
            font-size: 24px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="table-container">
        <h2>Games Table</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Image</th>
                <th>Rating</th>
                <th>Introduction</th>
                <th>User Code</th>
                <th>Added Date</th>
            </tr>
            <?php foreach ($games as $game): ?>
            <tr>
                <td><?php echo htmlspecialchars($game['id']); ?></td>
                <td><?php echo htmlspecialchars($game['title']); ?></td>
                <td><?php echo htmlspecialchars($game['image']); ?></td>
                <td><?php echo htmlspecialchars($game['rating']); ?></td>
                <td><?php echo htmlspecialchars($game['introduction']); ?></td>
                <td><?php echo htmlspecialchars($game['user_code']); ?></td>
                <td><?php echo htmlspecialchars($game['added_date']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="table-container">
        <h2>Comments Table</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Game ID</th>
                <th>Comment</th>
                <th>User Name</th>
                <th>Added Date</th>
            </tr>
            <?php foreach ($comments as $comment): ?>
            <tr>
                <td><?php echo htmlspecialchars($comment['id']); ?></td>
                <td><?php echo htmlspecialchars($comment['game_id']); ?></td>
                <td><?php echo htmlspecialchars($comment['comment']); ?></td>
                <td><?php echo htmlspecialchars($comment['user_name']); ?></td>
                <td><?php echo htmlspecialchars($comment['added_date']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
