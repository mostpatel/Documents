<?php


$date1="2014-01-06 13:30:00";
$date2=date('Y-m-d H:i:s');
echo $date2;
echo strtotime($date2)-strtotime($date1);

 ?>