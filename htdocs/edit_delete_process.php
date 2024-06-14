<?php
// データベース接続情報
$servername = "localhost";
$dbname = "game_database";
// フォームからのデータを取得
$game_id = $_POST['game_id'];
$action = $_POST['action']; // 'edit' または 'delete' のどちらかの値を取る想定です
// 接続確認
$conn = new mysqli($servername, "root", "", $dbname);
if ($conn->connect_error) {
    $message = "データベースに接続できませんでした: " . $conn->connect_error;
    header("Location: edit_error.php?message=" . urlencode($message));
    exit();
}
// 編集処理
if ($action == 'edit') {
    $new_title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $new_rating = isset($_POST['rating']) ? $_POST['rating'] : 0;
    $new_introduction = isset($_POST['introduction']) ? trim($_POST['introduction']) : '';
    
    $errors = array();

    // タイトルのチェック
    if (empty($new_title)) {
        $errors[] = "ゲームタイトルが入力されていません。";
    }
    
    // 紹介文のチェック
    if (empty($new_introduction)) {
        $errors[] = "紹介文が入力されていません。";
    } elseif (mb_strlen($new_introduction) > 400) {
        $errors[] = "紹介文が400文字を超えています。";
    }

    // エラーがある場合は処理を中断
    if (!empty($errors)) {
        $message = implode("\n", $errors);
        header("Location: edit_error.php?message=" . urlencode($message));
        exit();
    }

    // 画像ファイルを処理
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $file_errors = array();
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $file_parts = explode('.', $_FILES['image']['name']);
        $file_ext = strtolower(end($file_parts));
        $extensions= array("jpeg","jpg","png");
        
        if(in_array($file_ext, $extensions) === false){
            $file_errors[] = "拡張子が許可されていません。JPEGまたはPNGファイルを選択してください。";
        }
        
        if(!empty($file_errors)){
            $message = implode("\n", $file_errors);
            header("Location: edit_error.php?message=" . urlencode($message));
            exit();
        } else {
            move_uploaded_file($file_tmp, "images/" . $file_name);
            $new_image = "images/" . $file_name;
        }
    } else {
        // 画像がアップロードされなかった場合、元の画像を使用
        $sql_select = "SELECT image FROM games WHERE id = $game_id";
        $result_select = $conn->query($sql_select);
        if ($result_select === false) {
            $message = "画像の取得に失敗しました: " . $conn->error;
            header("Location: edit_error.php?message=" . urlencode($message));
            exit();
        }
        $row_select = $result_select->fetch_assoc();
        $new_image = $row_select['image'];
    }
    $sql_update = "UPDATE games SET title=?, image=?, rating=?, introduction=? WHERE id=?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ssisi", $new_title, $new_image, $new_rating, $new_introduction, $game_id);
    if ($stmt->execute()) {
        echo "ゲームが編集されました";
    } else {
        echo "編集エラー: " . $stmt->error;
    }
    $stmt->close();
} elseif ($action == 'delete') {
    // 削除処理（変更なし）
    // ...
} else {
    echo "無効なアクションです";
}
$conn->close();
// 3秒後にトップページにリダイレクト
header("refresh:3;url=index.php");
echo "<br>3秒後にトップページにリダイレクトします。";
?>
