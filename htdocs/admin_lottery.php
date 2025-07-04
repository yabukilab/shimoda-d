<?php
$token = "your_secret_token_here";
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>抽選管理画面</title>
<link rel="stylesheet" href="style.css">
<style>
.container { 
    max-width: 600px; 
    margin: 50px auto; 
    text-align: center; 
}
#result {
    white-space: pre-wrap;
    text-align: left;
    border: 1px solid #ccc;
    padding: 10px;
    margin-top: 20px;
    background: #f9f9f9;
    display: none;
}
.btn {
    background-color: #444;
    color: white;
    border: none;
    padding: 12px 0;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
    width: 200px;                /* ✅ ボタン幅統一 */
    max-width: 80%;              /* モバイル対応 */
    margin: 10px auto;           /* 上下中央寄せ */
    display: block;              /* ブロック化で中央揃え */
}
.btn:hover {
    background-color: #666;
    transform: translateY(-2px);
}
#backButton {
    margin-top: 20px;
    display: none;
}
</style>
</head>
<body>
<div class="container">
    <h2>抽選管理画面</h2>
    <p>「抽選を実行」または「全データ削除」を管理者操作で行えます。</p>
    <button id="runLottery" class="btn">抽選を実行</button>
    <button id="clearTables" class="btn">全データ削除</button>
    <div id="result"></div>
    <button id="backButton" class="btn" onclick="location.href='admin_lottery.php'">抽選画面へ戻る</button>

</div>

<script>
function executeAction(url, confirmMsg, runningMsg) {
    if (!confirm(confirmMsg)) return;
    const btns = document.querySelectorAll('.btn');
    btns.forEach(btn => btn.disabled = true);
    const resultDiv = document.getElementById("result");
    resultDiv.textContent = runningMsg;
    resultDiv.style.display = "block";

    fetch(url, {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'token=<?=$token?>'
    })
    .then(response => response.text())
    .then(data => {
        resultDiv.textContent = data;
        document.getElementById("runLottery").style.display = "none";
        document.getElementById("clearTables").style.display = "none";
        document.getElementById("backButton").style.display = "inline-block";
    })
    .catch(err => {
        resultDiv.textContent = "エラーが発生しました: " + err;
        btns.forEach(btn => {
            btn.disabled = false;
            btn.style.display = "inline-block";
        });
        document.getElementById("backButton").style.display = "inline-block";
    });
}

document.getElementById("runLottery").addEventListener("click", function(){
    executeAction(
        'lottery_process_all.php',
        "本当に抽選を実行しますか？",
        "抽選処理を実行中..."
    );
});

document.getElementById("clearTables").addEventListener("click", function(){
    executeAction(
        'clear_tables.php',
        "本当に全データを削除しますか？（元に戻せません）",
        "データ削除を実行中..."
    );
});
</script>
</body>
</html>



