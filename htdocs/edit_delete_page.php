<!DOCTYPE html>
<html>
<head>
    <title>編集・削除ページ</title>
</head>
<body>
    <h1>編集・削除ページ</h1>
    
    <?php
    // db.phpをインクルード
    require 'db.php';

    // URLパラメータからゲームIDを取得
    if (!isset($_POST['id']) || !isset($_POST['user_code'])) {
        $message = "ゲームIDまたはユーザーコードが設定されていません。";
        header("Location: edit_error.php?message=" . urlencode($message));
        exit();
    }

    $game_id = $_POST['id'];
    $user_code = $_POST['user_code'];

    $sql = "SELECT * FROM games WHERE id = :game_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':game_id', $game_id, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        $message = "ゲームが見つかりませんでした。";
        header("Location: edit_error.php?message=" . urlencode($message));
        exit();
    }

    if ($row["user_code"] != $user_code) {
        $message = "コードが違います。";
        header("Location: edit_error.php?message=" . urlencode($message));
        exit();
    }

    // ゲームが見つかった場合、フォームを表示
    echo "<form action='edit_delete_process.php' method='post' enctype='multipart/form-data'>";
    echo "ゲームタイトル: <input type='text' name='title' value='".$row['title']."'><br>";
    echo "画像: <input type='file' name='image'><br>";
    echo "評価点(10点満点): <input type='number' name='rating' min='0' max='10' value='".$row['rating']."'><br>";
    echo "紹介文: <textarea name='introduction' id='introduction'>".$row['introduction']."</textarea><br>";
    echo "<input type='hidden' name='game_id' value='".$game_id."'>";
    echo "<input type='hidden' name='action' value='edit'>"; // 編集アクション
    echo "<input type='submit' name='submit' value='編集'>";
    echo "</form>";
    echo "<br>"; // フォーム間のスペース
    echo "<form action='edit_delete_process.php' method='post'>";
    echo "<input type='hidden' name='game_id' value='".$game_id."'>";
    echo "<input type='hidden' name='action' value='delete'>"; // 削除アクション
    echo "<input type='submit' name='submit' value='削除'>";
    echo "</form>";

    // データベース接続を閉じる
    $db = null;
    ?>
</body>
</html>

