<?php
require 'db.php';

$sql = "SELECT * FROM entries";
$stmt = $db->query($sql);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<table border='1'>";
echo "<tr><th>ID</th><th>学籍番号</th><th>座席</th><th>時間帯</th></tr>";
foreach ($result as $row) {
  $id = h($row['id']);
  $student_id = h($row['student_id']);
  $seat = h($row['seat']);
  $time_slot = h($row['time_slot']);

  echo "<tr>";
  echo "<td>{$id}</td>";
  echo "<td>{$student_id}</td>";
  echo "<td>{$seat}</td>";
  echo "<td>{$time_slot}</td>";
  echo "</tr>";
}
echo "</table>";
?>
