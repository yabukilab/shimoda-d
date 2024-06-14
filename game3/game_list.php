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
        // データベース接続
        $servername = "localhost";
        $username = "root"; // ユーザ名を設定してください
        $password = ""; // パスワードを設定してください
        $dbname = "game_database";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // 接続確認
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // 並び替えの種類を取得
        $sort_type = isset($_GET['sort']) ? $_GET['sort'] : 'new';

        // 並び替えに応じたSQLクエリを作成
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

        // クエリ実行
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // データがある場合、結果を表示
            while($row = $result->fetch_assoc()) {
                echo "<li><a href='game_page.php?id=".$row["id"]."'>".$row["title"]."</a> - 評価点: ".$row["rating"]." - 追加日: ".$row["added_date"]."</li>";
            }
        } else {
            echo "0 results";
        }

        $conn->close();
        ?>
    </ul>
</body>
</html>
