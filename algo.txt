<?php
$n = [0,1,2, 3];
$m = 11;
$x = 2;

$index = -1;

while ($m >= 0) {
    $m -= $x;

    $x++;
    $index++;
    
}

echo $index . " n: " . count($n) . "<br>";
echo  $index % count($n);

?>