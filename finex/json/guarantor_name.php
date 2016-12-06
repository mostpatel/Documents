<?php require_once '../lib/cg.php';
?>

<?php
$oc_id=$_SESSION['adminSession']['oc_id'];

      
$sql = "SELECT guarantor_name FROM fin_file,fin_guarantor WHERE our_company_id=$oc_id AND file_status!=3 AND  guarantor_name LIKE '%".$_REQUEST['term']."%' 
       AND fin_file.file_id=fin_guarantor.file_id ";
	   $our_companies=listOurCompanyIds();
	$agencies = listAgencyIds();	   
if(is_array($our_companies) && count($our_companies)>0 && is_array($agencies) && count($agencies)>0)
	{
		$our_companies_string = implode(",",$our_companies);
		$sql=$sql." AND (oc_id IN ($our_companies_string) OR ";
		$agencies_string = implode(',',$agencies);
		$sql=$sql."agency_id IN ($agencies_string))  ";
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
			$sql=$sql." AND agency_id IN ($agencies_string)  ";
		}
		else 
		$sql=$sql." AND agency_id IS NULL ";
		
	} 
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
foreach ($resultArray as $r) 
{
    $results[] = array('label' => $r['guarantor_name']);
}


	
echo json_encode($results); 

   




?>