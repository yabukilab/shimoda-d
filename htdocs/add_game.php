<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ゲーム追加ページ</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header h1 {
            font-size: 36px;
            color: #333;
            margin-bottom: 10px;
        }
        .form-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 800px;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .form-container form {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 600px;
        }
        .form-container form label {
            margin-top: 10px;
            font-size: 16px;
        }
        .form-container form input[type="text"],
        .form-container form input[type="file"],
        .form-container form input[type="number"],
        .form-container form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container form input[type="submit"] {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .form-container form input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ゲーム追加ページ</h1>
    </div>
    <div class="form-container">
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
    </div>
    <div class="footer">
        &copy; 2024 ゲーム紹介サイト. All rights reserved.
    </div>
</body>
</html>
