<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset='utf-8' />
  <title>（実験・演習用）</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>

<h1>学食内座席抽選システム</h1>
<p>利用する機能を選択してください</p>

<?php
echo '<form action="time_select.php" method="get">';
echo '<button class="menu-button">抽選申し込み</button>';
echo '</form>';

echo '<form action="cancel.php" method="get">';
echo '<button class="menu-button">抽選キャンセル</button>';
echo '</form>';

echo '<form action="result_form.php" method="get">';
echo '<button class="menu-button">抽選結果</button>';
echo '</form>';
?>


</body>

</html>
