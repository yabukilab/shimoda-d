<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>エラー</title>
    <meta http-equiv="refresh" content="3;url=index.php">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
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
            border: 2px solid #ff0000;
        }
        .message {
            font-size: 18px;
            color: #333;
        }
        .redirect {
            font-size: 16px;
            color: #ff0000;
            margin-top: 10px;
        }
        .cross {
            display: block;
            margin: 20px auto 0;
            width: 24px;
            height: 24px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>エラーが発生しました</h1>
        <p class="message">
            <?php
            if (isset($_GET['message'])) {
                $message = urldecode($_GET['message']);
                echo nl2br(htmlspecialchars($message)) . "<br>";
            }
            ?>
            <span class="redirect">3秒後にトップページに戻ります。</span>
        </p>
        <svg class="cross" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" fill="#f44336"/>
            <path fill="none" stroke="#ffffff" stroke-width="2" d="M6 6l12 12M6 18L18 6"/>
        </svg>
    </div>
</body>
</html>
