<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("account-functions.php");
require_once("area-functions.php");
require_once("account-head-functions.php");
require_once("our-company-function.php");
require_once("adminuser-functions.php");
require_once("common.php");
require_once("bd.php");

$accounts=1;

function getPeriodForUser($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT period_id, from_period, to_period, last_updated FROM edms_ac_period_date WHERE admin_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return array($resultArray[0][1],$resultArray[0][2],$resultArray[0][3]);
	else
	return "error";
	}
}

function setPeriodForUser($id,$from,$to)
{
	if(checkForNumeric($id) && validateForNull($from,$to))
	{
	if(isset($from) && validateForNull($from))
		{
	$from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
		}
    if(isset($to) && validateForNull($to))
		{
	    $to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
		}
	$books_starting_date=getBooksStartingDateForCurrentCompanyOfUser();
	
	
	if((strtotime($books_starting_date)>strtotime($from)) || (strtotime($from)>strtotime($to)))
	return "error";
	
	
	
	if(getPeriodForUser($id)=="error")
	{	
	$sql="INSERT INTO edms_ac_period_date( from_period, to_period, admin_id, last_updated ) VALUES ('$from', '$to', $id, NOW())";
	$result=dbQuery($sql);
	return "success";
	}
	else
	{
		updatePeriodForUser($id,$from,$to);
		return "success";
		}
	return "error";	
		
}
}

function updatePeriodForUser($id,$from,$to)
{
	if(checkForNumeric($id) && validateForNull($from,$to))
	{
		if(isset($from) && validateForNull($from))
		{
	$from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
		}
if(isset($to) && validateForNull($to))
		{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
		}
		
	$sql="UPDATE edms_ac_period_date SET from_period='$from', to_period='$to', admin_id=$id, last_updated=NOW()  WHERE admin_id=$id";
	$result=dbQuery($sql);
	return "success";
	}
	return "error";
	
}
	
	

function getCurrentDateForUser($id)
{
	if(checkForNumeric($id))
	{
		
	$sql="SELECT curr_date FROM edms_ac_period_date WHERE admin_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
	else
	return "error";
	}
}

function setCurrentDateForUser($id,$curr_date)
{
	if(checkForNumeric($id) && validateForNull($curr_date))
	{
	if(isset($curr_date) && validateForNull($curr_date))
	{
	    $curr_date = str_replace('/', '-', $curr_date);
		$curr_date=date('Y-m-d',strtotime($curr_date));
	}
	
	$books_starting_date=getBooksStartingDateForCurrentCompanyOfUser();
	
	if((strtotime($books_starting_date)>strtotime($curr_date)))
	return "error";
		
		
	if(getCurrentDateForUser($id)=="error")
	{	
	$sql="INSERT INTO edms_ac_period_date( curr_date, admin_id, last_updated ) VALUES ('$curr_date', $id, NOW())";
	$result=dbQuery($sql);
	return "success";
	}
	else
	{
		updateCurrentDateForUser($id,$curr_date);
		return "success";
		}
	return "error";	
		
}
}
function updateCurrentDateForUser($id,$curr_date)
{
	if(checkForNumeric($id) && validateForNull($curr_date))
	{
	if(isset($curr_date) && validateForNull($curr_date))
	{
	$curr_date = str_replace('/', '-', $curr_date);
		$curr_date=date('Y-m-d',strtotime($curr_date));
	}	
	$sql="UPDATE edms_ac_period_date SET curr_date='$curr_date', last_updated=NOW()  WHERE admin_id=$id";
	$result=dbQuery($sql);
	return "success";
	}
	return "error";
	
}

function getCurrentCompanyForUser($id) // return an array 1 = > company or agency_id, 2 = >  0 if oc or 1 if agency
{
	if(checkForNumeric($id))
	{
	$sql="SELECT period_id, curr_company, company_type, last_updated FROM edms_ac_period_date WHERE admin_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return array($resultArray[0][1],$resultArray[0][2]);
	else
	return "error";
	}
	
	}
	
function setCurrentCompanyForUser($id,$curr_company,$company_type)
{
	if(checkForNumeric($id,$curr_company,$company_type))
	{
	if(getCurrentCompanyForUser($id)=="error")
	{	
	$sql="INSERT INTO edms_ac_period_date( curr_company, company_type, admin_id, last_updated ) VALUES ($curr_company, $company_type, $id, NOW())";
	$result=dbQuery($sql);
	 $_SESSION['edmsAdminSession']['oc_id'] = $curr_company;
	return "success";
	}
	else
	{
		updateCurrentCompanyForUser($id,$curr_company,$company_type);
		return "success";
		}
	return "error";	
		
}
}

function updateCurrentCompanyForUser($id,$curr_company,$company_type)
{
	if(checkForNumeric($id,$curr_company,$company_type))
	{
	
	$sql="UPDATE edms_ac_period_date SET curr_company=$curr_company, company_type=$company_type , admin_id=$id, last_updated=NOW()  WHERE admin_id=$id";
	$result=dbQuery($sql);
	$_SESSION['edmsAdminSession']['oc_id'] = $curr_company;
	return "success";
	}
	return "error";
	
}	

function getBooksStartingDateForCurrentCompanyOfUser() // return Y-m-d
{
	    $admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	   $current_company_array=getCurrentCompanyForUser($admin_id);
	$current_company_id=$current_company_array[0];
	$company_type=$current_company_array[1];
	
	if($company_type==0) // our_company
	{
		$book_startin_date=getBooksStartingDateForOC($current_company_id);
		}
	else if($company_type==1) // agency
	{
		$book_startin_date=getBooksStartingDateForAgency($current_company_id);
		}
	else if($company_type==2) // combined agency
	{
		
		$book_startin_date=getBooksStartingDateForCA($current_company_id);
	}
	return $book_startin_date;
        	
}

function listFilesForCombinedAgency($ca_id)
{
	if(checkForNumeric($ca_id))
	{
		    $agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);		
		
		$oc_id=$_SESSION['edmsAdminSession']['oc_id'];	
		
		$sql="SELECT edms_file.file_id, file_number, file_agreement_no, customer_id, customer_name, customer_address, city_id, area_id, opening_balance, opening_cd
		      FROM  edms_customer , edms_file
			  WHERE  our_company_id = $oc_id AND file_status!=3  AND edms_customer.file_id = edms_file.file_id ";
		if(validateForNull($agency_ids,$oc_ids))
	$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
	else if(validateForNull($agency_ids))
	$sql=$sql." agency_id IN ( ".$agency_ids.")";
	else if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		for($i=0;$i<count($resultArray);$i++)
		{
			$re=$resultArray[$i];
			$customer_id=0;
			$customer_id=$re['customer_id'];
			$contact_nos=getCustomerContactNo($customer_id);
			$resultArray[$i]['contact_no'] = $contact_nos; 
			}	  
		return $resultArray;	
		
		}
	}
?>