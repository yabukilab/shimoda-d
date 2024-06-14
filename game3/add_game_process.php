<?php
// データベース接続情報
$servername = "localhost";
$dbname = "game_database";

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
    
    if(in_array($file_ext,$extensions)=== false){
        $errors[]="extension not allowed, please choose a JPEG or PNG file.";
    }
    
    if(empty($errors)==true){
        move_uploaded_file($file_tmp,"images/".$file_name);
        $image = "images/".$file_name;
    }else{
        print_r($errors);
    }
}

// 接続確認
$conn = new mysqli($servername, "root", "", $dbname);
if ($conn->connect_error) {
    die("データベースに接続できませんでした: " . $conn->connect_error);
}

$message = "";

// ゲームをデータベースに追加するSQLクエリ
$sql = "INSERT INTO games (title, image, rating, introduction, user_code) VALUES ('$title', '$image', $rating, '$introduction', '$user_code')";

if ($conn->query($sql) === TRUE) {
    $message = "新しいゲームが追加されました";
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
