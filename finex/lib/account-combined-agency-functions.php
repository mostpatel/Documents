<?php
require_once('cg.php');
require_once('bd.php');
require_once('common.php');
require_once('agency-functions.php');
require_once('our-company-function.php');

function insertCombinedAgency($agency_array,$combined_agency_name)
{
	if(is_array($agency_array) && checkForNumeric($agency_array[0]) && $agency_array[0]>0 && validateForNull($combined_agency_name))
	{
		
		
		$combined_agency_name=clean_data($combined_agency_name);
		$combined_agency_name=strtoupper($combined_agency_name);
		$duplicate_agency=false;
		foreach($agency_array as $agency_oc)
		{
			
			if(substr($agency_oc, 0, 1) == 'ag') // if the pament is done to a general account ledger
			{
			$agency_id=str_replace('ag','',$agency_oc);
			$agency_id=intval($agency_id);
			$oc_id="NULL";
	        }
			else if(substr($agency_oc, 0, 1) == 'oc') // if payment is done to a customer
			{
				$oc_id=str_replace('C','',$agency_oc);
				$oc_id=intval($oc_id);
				$agency_id="NULL";
			}	
			
			$combined_agency_id=false;
			if(checkForNumeric($agency_id) && $oc_id=='NULL')
			{
			$combined_agency_id=getCombinedAgencyIdForAgencyId($agency_id);			
				if($combined_agency_id!=false && checkForNumeric($combined_agency_id) && $combined_agency_id>0)
				{
				return "duplicate_error";
				}
			}
			else if(checkForNumeric($oc_id) && $agency_id=='NULL')
			{
				$combined_agency_id=getCombinedAgencyIdForOCId($oc_id);			
				if($combined_agency_id!=false && checkForNumeric($combined_agency_id) && $combined_agency_id>0)
				{
				return "duplicate_error";
				}
			}
		}
		
		if(!$duplicate_agency)
		{
			$sql="INSERT INTO fin_ac_combined_agency (combined_agency_name) VALUES ('$combined_agency_name')";
			$result=dbQuery($sql);	 
			$combined_agency_id = dbInsertId();
			foreach($agency_array as $agency_id)
			{
				if(substr($agency_oc, 0, 1) == 'ag') // if the pament is done to a general account ledger
				{
				$agency_id=str_replace('ag','',$agency_oc);
				$agency_id=intval($agency_id);
				$oc_id="NULL";
				}
				else if(substr($agency_oc, 0, 1) == 'oc') // if payment is done to a customer
				{
					$oc_id=str_replace('C','',$agency_oc);
					$oc_id=intval($oc_id);
					$agency_id="NULL";
				}	
				
				$combined_agency_id=false;
				if(checkForNumeric($agency_id) && $oc_id=='NULL')
				{
				$combined_agency_id=getCombinedAgencyIdForAgencyId($agency_id);			
					if(!$combined_agency_id)
					{
						addAgencyToCombinedAgency($agency_id,$combined_agency_id);
					}
				}
				else if(checkForNumeric($oc_id) && $agency_id=='NULL')
				{
					$combined_agency_id=getCombinedAgencyIdForOCId($oc_id);			
					if(!$combined_agency_id)
					{
						addOCToCombinedAgency($oc_id,$combined_agency_id);
					}
				}
			}
		}	
		return "success";	
	}
	return "error";
}

function addAgencyToCombinedAgency($agency_id,$ca_id)
{
	if(checkForNumeric($ca_id,$agency_id))
	{
		$sql="INSERT INTO fin_ac_rel_agency_ca(agency_id,combined_agency_id) VALUES ($agency_id,$ca_id)";
		dbQuery($sql);
		return "success";
		}
	return "error";	
}

function addOCToCombinedAgency($oc_id,$ca_id)
{
	if(checkForNumeric($ca_id,$oc_id))
	{
		$sql="INSERT INTO fin_ac_rel_agency_ca(oc_id,combined_agency_id) VALUES ($oc_id,$ca_id)";
		dbQuery($sql);
		return "success";
		}
	return "error";	
}

function deleteCombinedAgency($ca_id)
{
	if(checkForNumeric($ca_id))
	{
		$sql="DELETE FROM fin_ac_combined_agency WHERE combined_agency_id=$ca_id";
		dbQuery($sql);
		return "success";
		}
	return "error";	
}


function getCombinedAgencyIdForAgencyId($agency_id)
{
	if(checkForNumeric($agency_id))
	{
		$sql="SELECT combined_agency_id FROM fin_ac_rel_agency_ca 
		       WHERE agency_id = $agency_id";
		$result=dbQuery($sql);	   
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
	}
	return false;
}

function getCombinedAgencyIdForOCId($agency_id)
{
	if(checkForNumeric($agency_id))
	{
		$sql="SELECT combined_agency_id FROM fin_ac_rel_agency_ca 
		       WHERE oc_id = $agency_id";
		$result=dbQuery($sql);	   
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
	}
	return false;
}

function getAgencyForCOmbinedAgency($ca_id)
{
	if(checkForNumeric($ca_id))
	{
		$sql="SELECT fin_ac_rel_agency_ca.agency_id,agency_name FROM fin_ac_rel_agency_ca, fin_agency 
		       WHERE combined_agency_id = $ca_id AND fin_ac_rel_agency_ca.agency_id = fin_agency.agency_id";
			   
	$result=dbQuery($sql);	   
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
	}
	return false;
}

function getOCForCOmbinedAgency($ca_id)
{
	if(checkForNumeric($ca_id))
	{
		$sql="SELECT our_company_id,our_company_name FROM fin_ac_rel_agency_ca, fin_our_company 
		       WHERE combined_agency_id = $ca_id AND  fin_ac_rel_agency_ca.oc_id = fin_our_company.our_company_id";
	$result=dbQuery($sql);	   
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
	}
	return false;
}
function getAgencyOCForCombinedAgency($ca_id)
{
	if(checkForNumeric($ca_id))
	{
		$agencies=getAgencyForCOmbinedAgency($ca_id);
		$ocs=getOCForCOmbinedAgency($ca_id);
		$agency_ids_array=array();
		$oc_ids_array=array();
		foreach($agencies as $agency)
		{
			$agency_ids_array[]=$agency['agency_id'];
			}
		foreach($ocs as $oc)
		{		$oc_ids_array[]=$oc['our_company_id'];
			}	
		$agency_ids_array=array_unique($agency_ids_array,SORT_NUMERIC);		
		$oc_ids_array=array_unique($oc_ids_array,SORT_NUMERIC);	
		
		return array($agency_ids_array,$oc_ids_array);	
	}
}



function listCombinedAgencies()
{
	$sql="SELECT combined_agency_id, combined_agency_name FROM fin_ac_combined_agency";
	$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else return false;
		
	}

function listIndependentAgencies(){
	
	try
	{
		$sql="SELECT fin_agency.agency_id, agency_name, agency_prefix, sub_heading, agency_contact_name, agency_contact_no, agency_address, auto_pay, auto_pay_date, file_counter, rasid_counter
		      FROM fin_agency WHERE fin_agency.agency_id NOT IN (SELECT agency_id FROM fin_ac_rel_agency_ca WHERE agency_id IS NOT NULL) ORDER BY agency_name";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else return false;
	}
	catch(Exception $e)
	{
	}
	
}	

function listIndependentOurCompanies(){
	
	try
	{
		$sql="SELECT our_company_id, our_company_name, our_company_address, our_company_pincode, sub_heading, our_company_prefix
		  FROM fin_our_company WHERE fin_our_company.our_company_id NOT IN (SELECT oc_id FROM fin_ac_rel_agency_ca WHERE oc_id IS NOT NULL) ORDER BY our_company_name";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else return false;
	}
	catch(Exception $e)
	{
	}
	
}	

function listAccountCompanies()
{
	try
	{
		$admin_id=$_SESSION['adminSession']['admin_id'];
	if(COMPANY_RESTRICTION==1)
	{
		$admin_agencies=getAgenciesForAdminId($admin_id);
	    $admin_companies=getOurCompaniesForAdminId($admin_id);
	}		
		$sql="
		SELECT CONCAT('ag',fin_agency.agency_id) as id, agency_name as name
		      FROM fin_agency WHERE fin_agency.agency_id NOT IN (SELECT agency_id FROM fin_ac_rel_agency_ca WHERE agency_id IS NOT NULL)"; 
	if(COMPANY_RESTRICTION==1)
	{
		if(is_array($admin_agencies) && count($admin_agencies)>0)
		{
			$admin_agencies_string = implode(',',$admin_agencies);
			$sql=$sql." AND agency_id IN ($admin_agencies_string)";
			
		}
	}	  	  
		$sql=$sql." UNION ALL 
		SELECT CONCAT('oc',our_company_id) as id, our_company_name as name
		  FROM fin_our_company WHERE fin_our_company.our_company_id NOT IN (SELECT oc_id FROM fin_ac_rel_agency_ca WHERE oc_id IS NOT NULL)  ";
		  if(COMPANY_RESTRICTION==1)
	{
		if(is_array($admin_companies) && count($admin_companies)>0)
		{
			$admin_companies_string = implode(',',$admin_companies);
			$sql=$sql." AND our_company_id IN ($admin_companies_string) ";
			
		}
	}
		  $sql=$sql."UNION ALL 
		  SELECT CONCAT('ca',combined_agency_id) as id, combined_agency_name as name FROM fin_ac_combined_agency
		  ORDER BY name
		  ";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		return $resultArray; 
	}
	catch(Exception $e)
	{
	}
	}
?>