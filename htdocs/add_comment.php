<?php
// セッションの開始
session_start();

// セッション変数の取得
$id = isset($_SESSION['id']) ? $_SESSION['id'] : 'defaultID';
?>

<?php
require 'db.php';

// フォームからのデータを取得
$game_id = $_POST['id'];
$comment = $_POST['comment'];
$user_name = ($_POST['user_name'] != '') ? $_POST['user_name'] : '名無し'; // ユーザー名が入力されていない場合は「名無し」とする

$message = "";
$success = false;

// コメントが空の場合はエラーメッセージを設定
if (trim($comment) == '') {
    $message = "コメントが入力されていません\n3秒後に紹介ページにリダイレクトします。";
} else {
    // コメントをデータベースに追加するSQLクエリ
    $sql = "INSERT INTO comments (game_id, comment, user_name) VALUES (:game_id, :comment, :user_name)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':game_id', $game_id);
    $stmt->bindParam(':comment', $comment);
    $stmt->bindParam(':user_name', $user_name);

    try {
        $stmt->execute();
        $message = "コメントが追加されました。\n3秒後に紹介ページにリダイレクトします。";
        $success = true;
    } catch(PDOException $e) {
        $message = "エラー: " . $e->getMessage();
    }
}

// 3秒後にリダイレクト
header("refresh:3;url=game_page.php?id=$id");


// 接続を閉じる
$db = null;
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
            position: relative;
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
        .checkmark, .crossmark {
            display: block;
            margin: 20px auto 0;
            width: 24px;
            height: 24px;
        }
    </style>
</head>
<body>
    <div class="container">
        <p class="message"><?php echo nl2br(htmlspecialchars($message)); ?></p>
        <?php if ($success): ?>
        <svg class="checkmark" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" fill="#4caf50"/>
            <path fill="none" stroke="#ffffff" stroke-width="2" d="M6 12l4 4l8 -8"/>
        </svg>
        <?php else: ?>
        <svg class="crossmark" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" fill="#f44336"/>
            <path fill="none" stroke="#ffffff" stroke-width="2" d="M6 6l12 12M6 18L18 6"/>
        </svg>
        <?php endif; ?>
    </div>
</body>
</html>
