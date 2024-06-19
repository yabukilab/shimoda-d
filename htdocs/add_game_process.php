<?php
require 'db.php';

// フォームからのデータを取得
$title = $_POST['title'];
$rating = $_POST['rating'];
$introduction = $_POST['introduction'];
$user_code = $_POST['user_code'];

// 画像ファイルを処理
if(isset($_FILES['image'])){
    $errors= array();
    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_type = $_FILES['image']['type'];
    $file_parts = explode('.', $_FILES['image']['name']);
    $file_ext = strtolower(end($file_parts));

    $extensions= array("jpeg","jpg","png");
    
    if(in_array($file_ext, $extensions) === false){
        $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
    }
    
    if(empty($errors) == true){
        move_uploaded_file($file_tmp, "images/".$file_name);
        $image = "images/".$file_name;
    } else {
        print_r($errors);
        exit;
    }
}

// ゲームをデータベースに追加するSQLクエリ
$sql = "INSERT INTO games (title, image, rating, introduction, user_code) VALUES (:title, :image, :rating, :introduction, :user_code)";

try {
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':image', $image);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':introduction', $introduction);
    $stmt->bindParam(':user_code', $user_code);
    $stmt->execute();

    echo "新しいゲームが追加されました<br>3秒後にトップページにリダイレクトします。";
    header("refresh:3;url=index.php");
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
}
?>

