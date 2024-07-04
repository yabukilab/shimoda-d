<?php
require 'db.php';

// 最新のゲームを取得するSQLクエリ
$sql = "SELECT id, title, introduction FROM games ORDER BY id DESC LIMIT 3";
$result = $db->query($sql);
$latest_games = [];

if ($result->rowCount() > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $latest_games[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ゲーム紹介サイト</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header h1 {
            font-size: 36px;
            color: #333;
            margin-bottom: 10px;
        }
        .header p {
            font-size: 18px;
            color: #666;
        }
        .content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: 20px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .card h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }
        .card p {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }
        .card a {
            text-decoration: none;
            color: #ffffff;
            font-size: 18px;
            display: block;
            margin-top: 10px;
            padding: 10px;
            background-color: #007bff;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .card a:hover {
            background-color: #0056b3;
        }
        .latest-games {
            margin-top: 40px;
        }
        .latest-game-card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px;
            padding: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .latest-game-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .latest-game-card h3 {
            font-size: 22px;
            color: #333;
            margin-bottom: 10px;
        }
        .latest-game-card p {
            font-size: 16px;
            color: #666;
        }
        .latest-game-card a {
            text-decoration: none;
            color: #ffffff;
            font-size: 18px;
            display: block;
            margin-top: 10px;
            padding: 10px;
            background-color: #28a745;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .latest-game-card a:hover {
            background-color: #218838;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ゲーム紹介サイト</h1>
        <p>最新のゲーム情報をチェックしよう！</p>
    </div>
    <div class="content">
        <div class="card">
            <h2>ゲーム追加ページ</h2>
            <p>新しいゲームを追加して、皆と共有しましょう！</p>
            <a href="add_game.php">詳細を見る</a>
        </div>
        <div class="card">
            <h2>紹介ページ一覧</h2>
            <p>既存のゲームを見て、レビューをチェックしましょう！</p>
            <a href="game_list.php">詳細を見る</a>
        </div>
    </div>
    /*
    <div class="latest-games">
        <?php foreach ($latest_games as $game): ?>
            <div class="latest-game-card">
                <h3><?php echo h($game['title']); ?></h3>
                <p><?php echo h($game['introduction']); ?></p>
                <a href="game_page.php?id=<?php echo h($game['id']); ?>">詳細を見る</a>
            </div>
        <?php endforeach; ?>
    </div>
    */
    <div class="footer">
        &copy; 2024 ゲーム紹介サイト. All rights reserved.
    </div>
</body>
</html>