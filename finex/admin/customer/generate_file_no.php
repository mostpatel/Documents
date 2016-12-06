<?php require_once "../../lib/cg.php";
require_once "../../lib/common.php";
require_once "../../lib/bd.php";
require_once "../../lib/loan-functions.php";
require_once "../../lib/agency-functions.php";
require_once "../../lib/our-company-function.php";
require_once "../../lib/file-functions.php";
require_once "../../lib/customer-functions.php";
require_once "../../lib/vehicle-functions.php";
require_once "../../lib/currencyToWords.php";
require_once "../../lib/account-ledger-functions.php";
require_once "../../lib/account-period-functions.php";

if(defined('FILE_NO_GENERATE') && FILE_NO_GENERATE==1)
{

$agency_id=$_GET['p'];

$oc_id=$_SESSION['adminSession']['oc_id'];	

$type=substr($agency_id,0,2);
$agency_id=substr($agency_id,2);
if($type=="ag")
{
$agency_id=$agency_id;
$our_company_id="NULL";
$prefix=getAgencyPrefixFromAgencyId($agency_id);
}
else if($type=="oc")
{
$our_company_id=$agency_id;
$agency_id="NULL";	
$prefix=getPrefixFromOCId($our_company_id);
}	

$sql="SELECT DISTINCT fin_file.file_number FROM fin_file,fin_file_noc WHERE fin_file.file_id = fin_file_noc.file_id AND our_company_id = $oc_id ";
if(is_numeric($our_company_id))
$sql=$sql." AND fin_file.oc_id = '$our_company_id'";
if(is_numeric($agency_id))
$sql=$sql." AND fin_file.agency_id = '$agency_id' ";
$sql=$sql." ORDER BY noc_id ASC";
$result = dbQuery($sql);
$result_array = dbResultToArray($result);
$return_array = array();

foreach($result_array as $re)
{
	$file_no= $re['file_number'];
	
	$sql="SELECT fin_file.file_id FROM fin_file WHERE file_number = '$file_no' AND fin_file.file_id NOT IN (SELECT fin_file_noc.file_id FROM fin_file_noc WHERE fin_file.file_id=fin_file_noc.file_id) AND file_status!=3 ";
	
	$result = dbQuery($sql);
	$result_array2 = dbResultToArray($result);
	if(dbNumRows($result)==0)
	{
		
		$return_array[] = intval(str_replace($prefix,'',$file_no));
	}
	
	if(count($return_array)>4)
	break;
}
if(count($return_array)<5)
{
$sql="SELECT REPLACE(file_number,'$prefix','') as file_no_digit FROM fin_file WHERE  our_company_id = $oc_id AND file_status!=3";
if(is_numeric($our_company_id))
$sql=$sql." AND fin_file.oc_id = '$our_company_id'";
if(is_numeric($agency_id))
$sql=$sql." AND fin_file.agency_id = '$agency_id' ";

$result = dbQuery($sql);
$result_array3 = dbResultToArray($result);

$file_no_array = array();
foreach($result_array3 as $re)
{

	$file_no_array[] = $re[0];
}
$arr2 = range(1,max($file_no_array)+5);

$missing = array_diff($arr2,$file_no_array);

foreach($missing as $m)
{
	
	$return_array[] = $m;
	if(count($return_array)>4)
	break;

}
}

echo json_encode($return_array); 	
}
else echo 0;
 ?>