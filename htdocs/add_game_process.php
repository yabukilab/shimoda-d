<?php
// db.phpをインクルード
require 'db.php';

// フォームからのデータを取得
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$rating = isset($_POST['rating']) ? trim($_POST['rating']) : '';
$introduction = isset($_POST['introduction']) ? trim($_POST['introduction']) : '';
$user_code = isset($_POST['user_code']) ? trim($_POST['user_code']) : '';

// エラーメッセージを保持する配列
$errors = array();

// ゲームタイトルのバリデーション
if (empty($title)) {
    $errors[] = "ゲームタイトルが入力されていません。";
} elseif (strlen($title) > 255) {
    $errors[] = "ゲームタイトルは255文字以内で入力してください。";
}

// 画像ファイルのバリデーション
if (!isset($_FILES['image']) || $_FILES['image']['error'] != UPLOAD_ERR_OK) {
    $errors[] = "ファイルが選択されていません。";
} else {
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_type = mime_content_type($file_tmp);

    // ファイルタイプのチェック
    if ($file_type !== 'image/jpeg' && $file_type !== 'image/png') {
        $errors[] = "画像ファイルは.jpgまたは.png形式でなければなりません。";
    } else {
        $image = file_get_contents($file_tmp);
    }
}

// 評価点のバリデーション
if (empty($rating)) {
    $errors[] = "評価点が入力されていません。";
}

// 紹介文のバリデーション
if (empty($introduction)) {
    $errors[] = "紹介文が記入されていません。";
} elseif (strlen($introduction) > 1200) {
    $errors[] = "紹介文は400文字以内で記入してください。";
}

// ユーザーコードのバリデーション
if (empty($user_code)) {
    $errors[] = "ユーザーコードを入力してください。";
} elseif (!preg_match('/^[a-zA-Z0-9]{8}$/', $user_code)) {
    $errors[] = "ユーザーコードは8桁の半角アルファベット及び半角数字の組み合わせで入力してください。";
} elseif (ctype_alpha($user_code) || ctype_digit($user_code)) {
    $errors[] = "ユーザーコードはアルファベットと数字の両方を含める必要があります。";
}

// エラーがある場合は処理を中断し、エラーメッセージを表示
if (!empty($errors)) {
    $message = implode("\n", $errors);
    // エラーが発生した場合、add_game.phpにリダイレクト
    header("refresh:3;url=add_game.php?message=" . urlencode($message));
    exit; // リダイレクト後にスクリプトを終了
}

// エラーがない場合はデータベースにゲームを追加
try {
    // ゲームをデータベースに追加するSQLクエリ
    $sql = "INSERT INTO games (title, image, rating, introduction, user_code) VALUES (:title, :image, :rating, :introduction, :user_code)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':image', $image, PDO::PARAM_LOB);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':introduction', $introduction);
    $stmt->bindParam(':user_code', $user_code);

    if ($stmt->execute()) {
        $message = "新しいゲームが追加されました";
    } else {
        throw new Exception("データベースに追加中にエラーが発生しました: " . $stmt->errorInfo()[2]);
    }
} catch (Exception $e) {
    $message = "エラー: " . $e->getMessage();
}

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
        .container.error {
            border-color: #f44336;
        }
        .message {
            font-size: 18px;
            color: #004d40;
        }
        .message.error {
            color: #f44336;
        }
        .redirect {
            font-size: 16px;
            color: #00796b;
            margin-top: 10px;
        }
        .checkmark {
            display: block;
            margin: 20px auto 0;
            width: 24px;
            height: 24px;
        }
    </style>
    <meta http-equiv="refresh" content="3;url=index.php">
</head>
<body>
    <div class="container <?php echo (strpos($message, 'エラー') !== false) ? 'error' : ''; ?>">
        <p class="message <?php echo (strpos($message, 'エラー') !== false) ? 'error' : ''; ?>"><?php echo nl2br(htmlspecialchars($message)); ?></p>
        <?php if (strpos($message, '新しいゲームが追加されました') !== false) : ?>
            <svg class="checkmark" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" fill="#4caf50"/>
                <path fill="none" stroke="#ffffff" stroke-width="2" d="M6 12l4 4l8 -8"/>
            </svg>
        <?php elseif (strpos($message, 'エラー') !== false) : ?>
            <svg class="checkmark" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" fill="#f44336"/>
                <path fill="none" stroke="#ffffff" stroke-width="2" d="M6 6l12 12M6 18L18 6"/>
            </svg>
        <?php endif; ?>
        <p class="redirect">3秒後にトップページにリダイレクトします。</p>
    </div>
</body>
</html>
