<?php
$actual_date = '2015-02-28';
$today = '2015-03-31';
		$datetime1 = date_create($actual_date);
				$datetime2 = date_create($today);
				$interval = date_diff($datetime1,$datetime2);
				echo $interval->format('%m months and %d days');				
 ?>