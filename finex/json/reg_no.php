<?php require_once '../lib/cg.php';
 require_once '../lib/vehicle-functions.php';
?>
<?php
$oc_id=$_SESSION['adminSession']['oc_id'];
$regno=$_REQUEST['term'];
$regno=stripVehicleno($regno);   
	if(!checkForNumeric($regno[0],$regno[1]) && ($regno[2]=='0'))
			{
				$regno=substr($regno,0,2).substr($regno,3);
			}
				
$sql = "SELECT vehicle_reg_no FROM fin_file,fin_vehicle WHERE our_company_id=$oc_id AND vehicle_reg_no LIKE '%".$regno."%' 
       AND fin_file.file_id=fin_vehicle.file_id AND file_status!=3";
	   $our_companies=listOurCompanyIds();
	$agencies = listAgencyIds();	   
if(is_array($our_companies) && count($our_companies)>0 && is_array($agencies) && count($agencies)>0)
	{
		$our_companies_string = implode(",",$our_companies);
		$sql=$sql." AND (oc_id IN ($our_companies_string) OR ";
		$agencies_string = implode(',',$agencies);
		$sql=$sql." agency_id IN ($agencies_string))  ";
	}
	else
	{
		if(is_array($our_companies) && count($our_companies)>0)
		{
			$our_companies_string = implode(",",$our_companies);
			$sql=$sql." AND oc_id IN ($our_companies_string)  ";
		}
		else 
		$sql=$sql." AND oc_id IS NULL ";
		if(is_array($agencies) && count($agencies)>0)
		{
			$agencies_string = implode(',',$agencies);
			$sql=$sql." AND agency_id IN ($agencies_string) ";
		}
		else 
		$sql=$sql." AND agency_id IS NULL ";
		
	}
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
foreach ($resultArray as $r) 
{
    $results[] = array('label' => $r['vehicle_reg_no']);
}

echo json_encode($results);
?>