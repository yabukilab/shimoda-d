<?php
session_start();

// 現在のページのURLを取得
$nowPage = $_SERVER['REQUEST_URI'];

// セッション変数に保存
$_SESSION['nowPage'] = $nowPage;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>紹介ページ一覧</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
            color: #333;
            padding-bottom: 60px;
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
        .form-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-container form {
            display: inline-block;
            margin: 0 10px;
        }
        .form-container label, .form-container select, .form-container input[type="text"], .form-container input[type="submit"] {
            padding: 10px;
            margin-right: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .form-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background-color: #ffffff;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        li a {
            text-decoration: none;
            color: #007bff;
            font-size: 18px;
        }
        li a:hover {
            text-decoration: underline;
        }
        .pagination {
            text-align: center;
            margin-top: 20px;
        }
        .pagination a {
            padding: 10px 20px;
            margin: 0 5px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .pagination a:hover {
            background-color: #0056b3;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #666;
        }
        .footer-button {
            position: fixed;
            bottom: 20px; /* ボタンの位置調整 */
            right: 20px;
            background-color: #00796b;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .footer-button:hover {
            background-color: #005a4d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>紹介ページ一覧</h1>
    </div>
    <div class="form-container">
        <form action="" method="get">
            <label for="search">検索:</label>
            <input type="text" id="search" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <input type="submit" value="検索">
        </form>
        <form action="" method="get">
            <input type="hidden" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <select name="sort">
                <option value="new" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'new') echo 'selected'; ?>>新着順</option>
                <option value="rating" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'rating') echo 'selected'; ?>>評価点順</option>
                <option value="alphabetical" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'alphabetical') echo 'selected'; ?>>50音順</option>
            </select>
            <input type="submit" value="並び替え">
        </form>
    </div>
    <ul>
        <?php
        require 'db.php';

        $sort_type = isset($_GET['sort']) ? $_GET['sort'] : 'new';
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $search_query = $search ? "WHERE title LIKE :search" : '';

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

        $sql = "SELECT * FROM games $search_query $order_by LIMIT :limit OFFSET :offset";
        $stmt = $db->prepare($sql);

        if ($search) {
            $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        try {
            $stmt->execute();
            $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($games) > 0) {
                foreach ($games as $game) {
                    echo "<li><a href='game_page.php?id=".$game["id"]."'>".$game["title"]."</a> - 評価点: ".$game["rating"]." - 追加日: ".$game["added_date"]."</li>";
                }
            } else {
                echo "<li>検索結果が見つかりませんでした</li>";
            }
        } catch (PDOException $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }

        $count_sql = "SELECT COUNT(*) FROM games $search_query";
        $count_stmt = $db->prepare($count_sql);
        if ($search) {
            $count_stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        }
        $count_stmt->execute();
        $total_results = $count_stmt->fetchColumn();
        $total_pages = ceil($total_results / $limit);

        $db = null;
        ?>
    </ul>

    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?search=<?php echo urlencode($search); ?>&sort=<?php echo $sort_type; ?>&page=<?php echo $page - 1; ?>">前へ</a>
        <?php endif; ?>
        <?php if ($page < $total_pages): ?>
            <a href="?search=<?php echo urlencode($search); ?>&sort=<?php echo $sort_type; ?>&page=<?php echo $page + 1; ?>">次へ</a>
        <?php endif; ?>
        <p>ページ <?php echo $page; ?> / <?php echo $total_pages; ?></p>
    </div>
    <div class="footer">
        &copy; 2024 ゲーム紹介サイト. All rights reserved.
    </div>
    <div>
    <a href="index.php" class="footer-button">トップへ戻る</a>
    </div>
</body>
</html>
