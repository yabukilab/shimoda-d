
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
}

// 画像ファイルのバリデーション
if (!isset($_FILES['image']) || $_FILES['image']['error'] != UPLOAD_ERR_OK) {
    $errors[] = "ファイルが選択されていません。";
} else {
    $file_tmp = $_FILES['image']['tmp_name'];
    $image = file_get_contents($file_tmp);
}

// 評価点のバリデーション
if (empty($rating)) {
    $errors[] = "評価点が入力されていません。";
}

// 紹介文のバリデーション
if (empty($introduction)) {
    $errors[] = "紹介文が記入されていません。";
} elseif (strlen($introduction) > 400) {
    $errors[] = "紹介文は400文字以内で記入してください。";
}

// ユーザーコードのバリデーション
if (empty($user_code)) {
    $errors[] = "ユーザーコードを入力してください。";
} elseif (!preg_match('/^[a-zA-Z0-9]{8}$/', $user_code)) {
    $errors[] = "ユーザーコードは8桁の半角アルファベット及び半角数字の組み合わせで入力してください。";
}

// エラーがある場合は処理を中断
if (!empty($errors)) {
    $message = implode("\n", $errors);
    header("Location: add_game_error.php?message=" . urlencode($message));
    exit();
}

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
        echo "新しいゲームが追加されました<br>3秒後にトップページにリダイレクトします。";
        header("refresh:3;url=index.php");
    } else {
        throw new Exception("データベースに追加中にエラーが発生しました: " . $stmt->errorInfo()[2]);
    }

} catch (Exception $e) {
    echo "エラー: " . $e->getMessage();
    echo "<br>3秒後に追加ページに戻ります。";
    header("refresh:3;url=add_game.php");
}

// 接続を閉じる
$db = null;
?>