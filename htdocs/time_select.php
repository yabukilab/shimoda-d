
<?php
session_start();
require 'db.php';

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>抽選申し込み - 時間帯選択</title>
    <link rel="stylesheet" href="style.css">
    </style>
</head>
<body>

<h1>抽選申し込み</h1>
<p>時間帯を選んでください</p>

<div class="time-grid">
<?php
$timeSlots = [
    "11:00-11:29",
    "11:30-11:59",
    "12:00-12:29",
    "12:30-12:59",
    "13:00-13:29",
    "13:30-13:59"
];

foreach ($timeSlots as $slot) {
    echo '<form action="seat_select.php" method="post" style="display:inline;">';
    echo '<input type="hidden" name="time_slot" value="' . $slot . '">';
    echo '<button class="time-button" type="submit">' . $slot . '</button>';
    echo '</form>';
}


?>
</div>

<form action="index.php" method="get">
    <button type="submit" class="back-button">戻る</button>
</form>

</body>
</html>
