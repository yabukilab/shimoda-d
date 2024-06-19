<!DOCTYPE html>
<html>
<head>
    <title>紹介ページ一覧</title>
</head>
<body>
    <h1>紹介ページ一覧</h1>
    <form action="" method="get">
        <select name="sort">
            <option value="new">新着順</option>
            <option value="rating">評価点順</option>
            <option value="alphabetical">50音順</option>
        </select>
        <input type="submit" value="並び替え">
    </form>
    <ul>
        <?php
        // db.phpをインクルード
        require 'db.php';

        // 並び替えの種類を取得
        $sort_type = isset($_GET['sort']) ? $_GET['sort'] : 'new';

        // 並び替えに応じたSQLクエリを準備
        switch ($sort_type) {
            case 'new':
                $sql = "SELECT * FROM games ORDER BY added_date DESC";
                break;
            case 'rating':
                $sql = "SELECT * FROM games ORDER BY rating DESC";
                break;
            case 'alphabetical':
                $sql = "SELECT * FROM games ORDER BY title";
                break;
            default:
                $sql = "SELECT * FROM games ORDER BY added_date DESC";
                break;
        }

        try {
            // クエリ実行
            $stmt = $db->query($sql);

            // 結果を取得
            $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 結果を表示
            foreach ($games as $game) {
                echo "<li><a href='game_page.php?id=".$game["id"]."'>".$game["title"]."</a> - 評価点: ".$game["rating"]." - 追加日: ".$game["added_date"]."</li>";
            }
        } catch (PDOException $e) {
            echo "Error: " . h($e->getMessage());
        }

        // データベース接続を閉じる
        $db = null;
        ?>
    </ul>
</body>
</html>
