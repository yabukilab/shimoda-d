<?php
// db.phpをインクルード
require 'db.php';

// フォームからのデータを取得
$game_id = $_POST['game_id'];
$action = $_POST['action']; // 'edit' または 'delete' のどちらかの値を取る想定です

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
    } elseif (strlen($new_introduction) > 400) {
        $errors[] = "紹介文が400文字を超えています。";
    }

    // エラーがある場合は処理を中断
    if (!empty($errors)) {
        $message = implode("\n", $errors);
        header("Location: edit_delete_page.php?game_id=$game_id&message=" . urlencode($message));
        exit();
    }

    // 画像ファイルを処理
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $file_errors = array();
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_size = $_FILES['image']['size'];
        $file_type = $_FILES['image']['type'];
        $file_parts = pathinfo($file_name);
        $file_ext = strtolower($file_parts['extension']);
        $extensions = array("jpeg", "jpg", "png");
        
        if (in_array($file_ext, $extensions) === false) {
            $file_errors[] = "拡張子が許可されていません。JPEGまたはPNGファイルを選択してください。";
        }
        
        if (!empty($file_errors)) {
            $message = implode("\n", $file_errors);
            header("Location: edit_delete_page.php?game_id=$game_id&message=" . urlencode($message));
            exit();
        } else {
            $new_image = file_get_contents($file_tmp);
        }
    } else {
        // 画像がアップロードされなかった場合、元の画像を使用
        $sql_select = "SELECT image FROM games WHERE id = :game_id";
        $stmt_select = $db->prepare($sql_select);
        $stmt_select->bindParam(':game_id', $game_id, PDO::PARAM_INT);
        $stmt_select->execute();
        $row_select = $stmt_select->fetch(PDO::FETCH_ASSOC);
        $new_image = $row_select['image'];
    }

    // ゲーム情報を更新するSQLクエリ
    $sql_update = "UPDATE games SET title=:new_title, image=:new_image, rating=:new_rating, introduction=:new_introduction WHERE id=:game_id";
    $stmt_update = $db->prepare($sql_update);
    $stmt_update->bindParam(':new_title', $new_title, PDO::PARAM_STR);
    $stmt_update->bindParam(':new_image', $new_image, PDO::PARAM_LOB);
    $stmt_update->bindParam(':new_rating', $new_rating, PDO::PARAM_INT);
    $stmt_update->bindParam(':new_introduction', $new_introduction, PDO::PARAM_STR);
    $stmt_update->bindParam(':game_id', $game_id, PDO::PARAM_INT);
    
    if ($stmt_update->execute()) {
        $message = "ゲームが編集されました";
    } else {
        $message = "編集エラー: " . $stmt_update->errorInfo()[2];
    }
} elseif ($action == 'delete') {
    // 削除処理
    // まず、関連するコメントを削除
    $sql_delete_comments = "DELETE FROM comments WHERE game_id=:game_id";
    $stmt_delete_comments = $db->prepare($sql_delete_comments);
    $stmt_delete_comments->bindParam(':game_id', $game_id, PDO::PARAM_INT);
    
    if ($stmt_delete_comments->execute()) {
        // 次に、ゲームを削除
        $sql_delete_game = "DELETE FROM games WHERE id=:game_id";
        $stmt_delete_game = $db->prepare($sql_delete_game);
        $stmt_delete_game->bindParam(':game_id', $game_id, PDO::PARAM_INT);
        
        if ($stmt_delete_game->execute()) {
            $message = "ゲームが削除されました";
        } else {
            $message = "ゲームの削除エラー: " . $stmt_delete_game->errorInfo()[2];
        }
    } else {
        $message = "コメントの削除エラー: " . $stmt_delete_comments->errorInfo()[2];
    }
} else {
    $message = "無効なアクションです";
}

// データベース接続を閉じる
$db = null;

// 3秒後にトップページにリダイレクト
header("refresh:3;url=index.php");
echo "<br>3秒後にトップページにリダイレクトします。";
?>
