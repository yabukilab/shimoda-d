<!DOCTYPE html>
<html>
<head>
    <title>紹介ページ</title>
</head>
<body>
    <h1>紹介ページ</h1>
    
    <?php
    // データベース接続
    require 'db.php';

    // URLパラメータからゲームIDを取得
    $game_id = $_GET['id'];

    // ゲーム情報を取得するSQLクエリ
    $sql = "SELECT * FROM games WHERE id = :game_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':game_id', $game_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // ゲームが見つかった場合、情報を表示
        echo "<h2>".$row['title']."</h2>";
        echo "<img src='data:image/jpeg;base64,".base64_encode($row['image'])."' alt='Game Image'>";  // MEDIUMBLOBから画像を表示
        echo "<p>評価点: ".$row['rating']."</p>";
        echo "<p>追加日: ".$row['added_date']."</p>";
        echo "<p>".$row['introduction']."</p>";

        // コメント一覧を表示
        echo "<h2>コメント</h2>";
        $sql_comments = "SELECT * FROM comments WHERE game_id = :game_id ORDER BY added_date DESC";
        $stmt_comments = $db->prepare($sql_comments);
        $stmt_comments->bindParam(':game_id', $game_id);
        $stmt_comments->execute();
        
        if ($stmt_comments->rowCount() > 0) {
            while($row_comment = $stmt_comments->fetch(PDO::FETCH_ASSOC)) {
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

    $db = null;  // 接続を閉じる
    ?>
</body>
</html>


