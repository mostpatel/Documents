<?php

require_once '../lib/cg.php';
require_once '../lib/bd.php';
require_once '../lib/common.php';
?>

<?php

$emailAddress=$_GET['q'];
      
$sql = "SELECT customer_id FROM ems_customer WHERE customer_email ='".$emailAddress."'";
                                                
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
if(dbNumRows($result)>0)
	{
		$return_id=$resultArray[0][0];
		
echo $return_id; 
	}
else
echo 0;



   
?>