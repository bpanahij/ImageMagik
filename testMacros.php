<?php
echo "Macro Test\n";
$connection = mysql_connect('127.0.0.1', 'root', 'panahi');
mysql_query("USE jason", $connection);
$query = "SELECT DISTINCT (macro) FROM macros WHERE user_id='7'";
$result = mysql_query($query, $connection);
while($row = mysql_fetch_row($result)) {
  $query2 = "SELECT id, step FROM macros WHERE user_id='7' AND macro='".$row[0]."' ORDER BY id;";
  $result2 = mysql_query($query2, $connection);
  echo $row[0];
  while($row2 = mysql_fetch_row($result2)) {
    echo $row2[1];
  }
 }

?>