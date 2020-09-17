<?php
$numbers = array(1, 2, 2, 3, 4, 4, 5, 6, 7, 7, 8, 9, 10, 11, 12);
sort($numbers);
foreach ($numbers as $index=>$val) {
  if ($val % 2 == 0)
  echo "$val <br>"; 
}
?>
