<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>編集・削除ページ</title>
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
        .container form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container form label,
        .container form input[type="text"],
        .container form input[type="file"],
        .container form input[type="number"],
        .container form textarea,
        .container form input[type="submit"],
        .container form input[type="button"] {
            margin-bottom: 10px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }
        .container form label {
            font-weight: bold;
            border: 2px solid #333;
        }
        .container form input[type="submit"],
        .container form input[type="button"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .container form input[type="submit"]:hover,
        .container form input[type="button"]:hover {
            background-color: #0056b3;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #666;
        }
    </style>
    <script type="text/javascript">
        function confirmDelete() {
            if (confirm("本当に削除しますか？")) {
                document.getElementById("deleteForm").submit();
            }
        }
    </script>
</head>
<body>
    <div class="header">
        <h1>編集・削除ページ</h1>
    </div>
    <div class="container">
        <?php
        // db.phpをインクルード
        require 'db.php';

        // URLパラメータからゲームIDを取得
        if (!isset($_POST['id']) || !isset($_POST['user_code']) || trim($_POST['user_code']) == '') {
            $message = "";
            if (!isset($_POST['id'])) {
                $message = "ゲームIDが設定されていません。";
            } elseif (!isset($_POST['user_code']) || trim($_POST['user_code']) == '') {
                $message = "ユーザーコードが入力されていません。";
            }
            header("Location: edit_error.php?message=" . urlencode($message) . "&id=" . urlencode($_POST['id']) . "&user_code=" . urlencode($_POST['user_code']));
            exit();
        }

        $game_id = $_POST['id'];
        $user_code = $_POST['user_code'];

        $sql = "SELECT * FROM games WHERE id = :game_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':game_id', $game_id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            $message = "ゲームが見つかりませんでした。";
            header("Location: edit_error.php?message=" . urlencode($message));
            exit();
        }

        if ($row["user_code"] != $user_code) {
            $message = "コードが違います。";
            header("Location: edit_error.php?message=" . urlencode($message));
            exit();
        }

        // ゲームが見つかった場合、フォームを表示
        echo "<form action='edit_delete_process.php' method='post' enctype='multipart/form-data'>";
        echo "<label for='title'>ゲームタイトル:</label>";
        echo "<input type='text' name='title' id='title' value='".$row['title']."'><br>";
        echo "<label for='image'>画像:</label>";
        echo "<input type='file' name='image' id='image'><br>";
        echo "<label for='rating'>評価点(10点満点):</label>";
        echo "<input type='number' name='rating' id='rating' min='0' max='10' value='".$row['rating']."'><br>";
        echo "<label for='introduction'>紹介文:</label>";
        echo "<textarea name='introduction' id='introduction' rows='4'>".$row['introduction']."</textarea><br>";
        echo "<input type='hidden' name='game_id' value='".$game_id."'>";
        echo "<input type='hidden' name='action' value='edit'>"; // 編集アクション
        echo "<input type='submit' name='submit' value='編集'>";
        echo "</form>";
        echo "<br>"; // フォーム間のスペース
        echo "<form id='deleteForm' action='edit_delete_process.php' method='post'>";
        echo "<input type='hidden' name='game_id' value='".$game_id."'>";
        echo "<input type='hidden' name='action' value='delete'>"; // 削除アクション
        echo "<input type='button' value='削除' onclick='confirmDelete()'>";
        echo "</form>";

        // データベース接続を閉じる
        $db = null;
        ?>
    </div>
    <div class="footer">
        &copy; 2024 ゲーム紹介サイト. All rights reserved.
    </div>
</body>
</html>
