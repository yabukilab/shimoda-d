<!DOCTYPE html>
<html>
<head>
    <title>ゲーム追加ページ</title>
</head>
<body>
    <h1>ゲーム追加ページ</h1>
    <form action="add_game_process.php" method="post" enctype="multipart/form-data">
        <label for="title">ゲームタイトル:</label>
        <input type="text" name="title" id="title"><br>
        <label for="image">画像:</label>
        <input type="file" name="image" id="image"><br>
        <label for="rating">評価点(10点満点):</label>
        <input type="number" name="rating" id="rating" min="0" max="10"><br>
        <label for="introduction">紹介文:</label><br>
        <textarea name="introduction" id="introduction" rows="4" cols="50"></textarea><br>
        <label for="user_code">ユーザコード:</label>
        <input type="text" name="user_code" id="user_code"><br>
        <input type="submit" value="追加">
    </form>
</body>
</html>
