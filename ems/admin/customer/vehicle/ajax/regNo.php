<?php require_once '../../../../lib/cg.php';
require_once '../../../../lib/bd.php';
require_once '../../../../lib/vehicle-functions.php';
?>

<?php
$oc_id=$_SESSION['adminSession']['oc_id'];
$value=$_GET['value'];
if(isset($_GET['vid']))
$vid=$_GET['vid'];
else
$vid=-1;
$value=stripVehicleno($value);
if($value[2]=='0' || $value[2]==0)
			{
				$value=substr($value,0,2).substr($value,3);
				}
				
	$sql = "SELECT vehicle_id FROM ems_vehicle,fin_file WHERE ems_vehicle.file_id=fin_file.file_id
		  AND file_status!=3 AND vehicle_reg_no ='$value' ";
		if($vid!=-1)
		$sql=$sql." AND vehicle_id!=$vid";
		
	$result=dbQuery($sql);


echo dbNumRows($result);
?>