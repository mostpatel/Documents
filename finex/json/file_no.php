<?php require_once '../lib/cg.php';
require_once '../lib/common.php';
require_once '../lib/file-functions.php';
?>
<?php
$oc_id=$_SESSION['adminSession']['oc_id'];
$or_file_no = $_REQUEST['term'];
$file_no=stripFileNo($_REQUEST['term']);
$file_no=clean_data($file_no);  
$sql = "SELECT file_number FROM fin_file WHERE our_company_id=$oc_id AND file_status!=3 AND ( file_number LIKE '%".$file_no."%' OR file_number LIKE '%".$or_file_no."%' )";
if(FILE_NO_REUSE==1 && !(isset($_GET['from']) && $_GET['from']==1))
$sql=$sql. " AND file_status!=2 AND file_status !=4 ";
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
			$sql=$sql." AND oc_id IN ($our_companies_string)   ";
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
	
      $sql=$sql." ORDER BY file_number";
	  
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
foreach ($resultArray as $r) 
{
    $results[] = array('label' => $r['file_number']);
}


	
echo json_encode($results); ?>