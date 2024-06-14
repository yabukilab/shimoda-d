<?php
// データベース接続情報
$servername = "localhost";
$dbname = "game_database";

// 接続確認
$conn = new mysqli($servername, "root", "", $dbname);
if ($conn->connect_error) {
    die("データベースに接続できませんでした: " . $conn->connect_error);
}
//test
// フォームからのデータを取得
$game_id = $_POST['id'];
$comment = $_POST['comment'];
$user_name = ($_POST['user_name'] != '') ? $_POST['user_name'] : '名無し'; // ユーザー名が入力されていない場合は「名無し」とする

$message = "";

// コメントをデータベースに追加するSQLクエリ
$sql = "INSERT INTO comments (game_id, comment, user_name) VALUES ($game_id, '$comment', '$user_name')";

if ($conn->query($sql) === TRUE) {
    $message = "コメントが追加されました";
    header("refresh:3;url=index.php");
} else {
    $message = "エラー: " . $sql . "<br>" . $conn->error;
}

// 接続を閉じる
$conn->close();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>リダイレクト中</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7fa;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            border: 2px solid #00796b;
        }
        .message {
            font-size: 18px;
            color: #004d40;
        }
        .redirect {
            font-size: 16px;
            color: #00796b;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <p class="message"><?php echo $message; ?></p>
        <p class="redirect">3秒後にトップページにリダイレクトします。</p>
    </div>
</body>
</html>
