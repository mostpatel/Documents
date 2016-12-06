<?php require_once '../../../../lib/cg.php';
require_once '../../../../lib/bd.php';
require_once '../../../../lib/vehicle-functions.php';
?>

<?php
$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
$value=$_GET['value'];
if(strcasecmp($value,"na"))
{
	echo 0;
	}
else
{	
if(isset($_GET['vid']))
$vid=$_GET['vid'];
else
$vid=-1;
			
	$sql = "SELECT vehicle_id FROM edms_vehicle WHERE edms_vehicle.file_id=edms_file.file_id
		  AND file_status=1 AND vehicle_chasis_no ='$value' ";	
	if($vid!=-1)
		$sql=$sql." AND vehicle_id!=$vid";
	$result=dbQuery($sql);


echo dbNumRows($result);
}
return 0;
?>