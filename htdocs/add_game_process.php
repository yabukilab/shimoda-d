<?php
// db.phpをインクルード
require 'db.php';

try {
    // フォームからのデータを取得
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $rating = isset($_POST['rating']) ? trim($_POST['rating']) : '';
    $introduction = isset($_POST['introduction']) ? trim($_POST['introduction']) : '';
    $user_code = isset($_POST['user_code']) ? trim($_POST['user_code']) : '';

    // 入力チェック
    if (empty($title) || empty($rating) || empty($introduction) || empty($user_code)) {
        throw new Exception("すべてのフォームフィールドを入力してください。");
    }

    // 画像ファイルを処理
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['image']['tmp_name'];
        $image = file_get_contents($file_tmp);
    } else {
        throw new Exception("画像ファイルがアップロードされていないか、エラーが発生しました。");
    }

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
