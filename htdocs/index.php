<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8' />
    <title>予算管理システム</title>
  </head>
  <body>
  <p>
  ■予算管理システム<br>

  <form action="submit.php" method="post">
    品物１：<input type="text" name="goods1" size="20">
    価格：<input type="text" name="price1" size="10"><br><br>
    品物２：<input type="text" name="goods2" size="20">
    価格：<input type="text" name="price2" size="10"><br><br>
    品物３：<input type="text" name="goods3" size="20">
    価格：<input type="text" name="price3" size="10"><br><br>
    １日の予算：<input type="text" name="maxprice" size="10"><br><br>
    <input type="submit" value="送信する">
  </form>

  </p>
  </body>
</html>