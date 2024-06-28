<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>紹介ページ</title>
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
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }
        .container img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 20px auto;
        }
        .container h2 {
            font-size: 24px;
            color: #333;
            margin-top: 20px;
        }
        .container p {
            font-size: 18px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .introduction {
            font-family: 'Georgia', serif;
            color: #444;
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #007bff;
            border-radius: 5px;
            line-height: 1.8;
            text-align: left;
            margin-bottom: 20px;
        }
        .comments {
            margin-top: 20px;
            text-align: left;
        }
        .comments p {
            font-size: 16px;
            color: #444;
            line-height: 1.6;
            background-color: #f9f9f9;
            padding: 10px;
            border-left: 4px solid #007bff;
            margin-bottom: 10px;
            border-radius: 5px;
            font-family: 'Comic Sans MS', 'Comic Sans', cursive; /* Example of a more interesting font */
        }
        .form-container {
            margin-top: 20px;
            text-align: left;
        }
        .form-container form {
            display: flex;
            flex-direction: column;
        }
        .form-container form label {
            margin-bottom: 10px;
            font-size: 16px;
            font-weight: bold;
        }
        .form-container form input[type="text"],
        .form-container form textarea,
        .form-container form input[type="submit"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
            margin-bottom: 10px;
        }
        .form-container form input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: auto;
        }
        .form-container form input[type="submit"]:hover {
            background-color: #0056b3;
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
        <h1>紹介ページ</h1>
    </div>
    <div class="container">
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
            echo "<div class='introduction'>".$row['introduction']."</div>";

            // コメント一覧を表示
            echo "<h2>コメント</h2>";
            echo "<div class='comments'>";
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
            echo "</div>";

            // コメント追加フォーム
            echo "<div class='form-container'>";
            echo "<h2>コメントを追加</h2>";
            echo "<form action='add_comment.php' method='post'>";
            echo "<input type='hidden' name='id' value='".$game_id."'>";
            echo "<label for='user_name'>ユーザー名:</label>";
            echo "<input type='text' name='user_name' id='user_name'><br>";
            echo "<label for='comment'>コメント:</label>";
            echo "<textarea name='comment' id='comment'></textarea><br>";
            echo "<input type='submit' value='追加'>";
            echo "</form>";
            echo "</div>";

            // ユーザーコードの入力フォーム
            echo "<div class='form-container'>";
            echo "<h2>編集・削除ページへ移動</h2>";
            echo "<form action='edit_delete_page.php' method='post'>";
            echo "<input type='hidden' name='id' value='".$game_id."'>";
            echo "<label for='user_code'>ユーザーコード:</label>";
            echo "<input type='text' name='user_code' id='user_code'><br>";
            echo "<input type='submit' value='編集・削除'>";
            echo "</form>";
            echo "</div>";
        } else {
            echo "<p>ゲームが見つかりませんでした</p>";
        }

        $db = null;  // 接続を閉じる
        ?>
    </div>
    <div class="footer">
        &copy; 2024 ゲーム紹介サイト. All rights reserved.
    </div>
</body>
</html>

