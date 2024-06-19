<?php
// db.phpをインクルード
require 'db.php';

try {
    // フォームからのデータを取得
    $title = $_POST['title'];
    $rating = $_POST['rating'];
    $introduction = $_POST['introduction'];
    $user_code = $_POST['user_code'];

    // 画像ファイルを処理
    if (isset($_FILES['image'])) {
        $errors = array();
        $file_tmp = $_FILES['image']['tmp_name'];
        $image = file_get_contents($file_tmp);
    } else {
        throw new Exception("画像ファイルがアップロードされていません。");
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
}

// 接続を閉じる
$db = null;
?>
