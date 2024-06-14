<!DOCTYPE html>
<html>
<head>
    <title>紹介ページ</title>
</head>
<body>
    <h1>紹介ページ</h1>
    
    <?php
    // データベース接続
    $servername = "localhost";
    $username = "root"; // ユーザ名を設定してください
    $password = ""; // パスワードを設定してください
    $dbname = "game_database";

    // 接続確認
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("データベースに接続できませんでした: " . $conn->connect_error);
    }

    // URLパラメータからゲームIDを取得
    $game_id = $_GET['id'];

    // ゲーム情報を取得するSQLクエリ
    $sql = "SELECT * FROM games WHERE id = $game_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // ゲームが見つかった場合、情報を表示
        $row = $result->fetch_assoc();
        echo "<h2>".$row['title']."</h2>";
        echo "<img src='".$row['image']."' alt='Game Image'>";  // 画像を表示
        echo "<p>評価点: ".$row['rating']."</p>";
        echo "<p>追加日: ".$row['added_date']."</p>";
        echo "<p>".$row['introduction']."</p>";

        // コメント一覧を表示
        echo "<h2>コメント</h2>";
        $sql_comments = "SELECT * FROM comments WHERE game_id = $game_id ORDER BY added_date DESC";
        $result_comments = $conn->query($sql_comments);
        if ($result_comments->num_rows > 0) {
            while($row_comment = $result_comments->fetch_assoc()) {
                echo "<p>".$row_comment['user_name'].": ".$row_comment['comment']." - 追加日: ".$row_comment['added_date']."</p>";
            }
        } else {
            echo "<p>コメントはありません</p>";
        }

        // コメント追加フォーム
        echo "<h2>コメントを追加</h2>";
        echo "<form action='add_comment.php' method='post'>";
        echo "<input type='hidden' name='id' value='".$game_id."'>";
        echo "ユーザー名: <input type='text' name='user_name'><br>";
        echo "コメント: <textarea name='comment'></textarea><br>";
        echo "<input type='submit' value='追加'>";
        echo "</form>";

        // ユーザーコードの入力フォーム
        echo "<h2>編集・削除ページへ移動</h2>";
        echo "<form action='edit_delete_page.php' method='post'>";
        echo "<input type='hidden' name='id' value='".$game_id."'>";
        echo "ユーザーコード: <input type='text' name='user_code'><br>";
        echo "<input type='submit' value='編集・削除'>";
        echo "</form>";
    } else {
        echo "ゲームが見つかりませんでした";
    }

    $conn->close();
    ?>
</body>
</html>


