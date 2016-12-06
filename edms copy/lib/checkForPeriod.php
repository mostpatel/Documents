<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("area-functions.php");
require_once("account-head-functions.php");
require_once("account-period-functions.php");
require_once("our-company-function.php");
require_once("adminuser-functions.php");
require_once("common.php");
require_once("bd.php");

if(isset($_POST))
{
	$from=$_POST['from_period'];
	$to=$_POST['to_period'];
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	$current_Date=$_POST['current_date'];
	$agency_id=$_POST['agency_id'];
	
		$original_agency_id=$agency_id;
	$our_company_id=NULL;
	$type=substr($agency_id,0,2);
	$agency_id=substr($agency_id,2);
	
	if($type=="ag")
	{
	$company_type=1;
	}
	else if($type=="oc")
	{
	$company_type=0;
	}	
	else if($type=="ca")
	{
	$company_type=2;
	}
	
	if(checkForNumeric($admin_id,$agency_id,$company_type))
	{
	$result=setCurrentCompanyForUser($admin_id,$agency_id,$company_type);	
	}	
	if(validateForDate($from) && validateForDate($to) && checkForNumeric($admin_id))
	{
	$result1=setPeriodForUser($admin_id,$from,$to);	
	}
	
	if(validateForDate($current_Date)  && checkForNumeric($admin_id))
	{
	$result2=setCurrentDateForUser($admin_id,$current_Date);	
	}
	
}

if($result1=="error" || $result1=="error" || $result2=="error")
{
$_SESSION['ack']['msg']="Invalid Input, Settings not updated!";
$_SESSION['ack']['type']=4; // 4 for error
}
header("location: ".$_SERVER['HTTP_REFERER']);
exit;

?>