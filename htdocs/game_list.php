<!DOCTYPE html>
<html>
<head>
    <title>紹介ページ一覧</title>
</head>
<body>
    <h1>紹介ページ一覧</h1>

    <form action="" method="get">
        <label for="search">検索:</label>
        <input type="text" id="search" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <select name="sort">
            <option value="new" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'new') echo 'selected'; ?>>新着順</option>
            <option value="rating" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'rating') echo 'selected'; ?>>評価点順</option>
            <option value="alphabetical" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'alphabetical') echo 'selected'; ?>>50音順</option>
        </select>
        <input type="submit" value="検索">
    </form>

    <ul>
        <?php
        require 'db.php';

        $sort_type = isset($_GET['sort']) ? $_GET['sort'] : 'new';
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        // 検索クエリを準備
        $search_query = $search ? "WHERE title LIKE :search" : '';

        // 並び替えに応じたSQLクエリを準備
        switch ($sort_type) {
            case 'new':
                $order_by = "ORDER BY added_date DESC";
                break;
            case 'rating':
                $order_by = "ORDER BY rating DESC";
                break;
            case 'alphabetical':
                $order_by = "ORDER BY title";
                break;
            default:
                $order_by = "ORDER BY added_date DESC";
                break;
        }

        // クエリ準備
        $sql = "SELECT * FROM games $search_query $order_by LIMIT :limit OFFSET :offset";
        $stmt = $db->prepare($sql);

        // パラメータバインド
        if ($search) {
            $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        try {
            // クエリ実行
            $stmt->execute();

            // 結果を取得
            $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 結果を表示
            foreach ($games as $game) {
                echo "<li><a href='game_page.php?id=".$game["id"]."'>".$game["title"]."</a> - 評価点: ".$game["rating"]." - 追加日: ".$game["added_date"]."</li>";
            }
        } catch (PDOException $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }

        // ページ数の計算
        $count_sql = "SELECT COUNT(*) FROM games $search_query";
        $count_stmt = $db->prepare($count_sql);
        if ($search) {
            $count_stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        }
        $count_stmt->execute();
        $total_results = $count_stmt->fetchColumn();
        $total_pages = ceil($total_results / $limit);

        // データベース接続を閉じる
        $db = null;
        ?>
    </ul>

    <div>
        <?php if ($page > 1): ?>
            <a href="?search=<?php echo urlencode($search); ?>&sort=<?php echo $sort_type; ?>&page=<?php echo $page - 1; ?>">前へ</a>
        <?php endif; ?>
        <?php if ($page < $total_pages): ?>
            <a href="?search=<?php echo urlencode($search); ?>&sort=<?php echo $sort_type; ?>&page=<?php echo $page + 1; ?>">次へ</a>
        <?php endif; ?>
    </div>
</body>
</html>

