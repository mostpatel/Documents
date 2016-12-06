<?php 
require_once("cg.php");
require_once("common.php");
require_once("city-functions.php");
require_once("area-functions.php");
require_once("account-head-functions.php");
require_once("customer-functions.php");
require_once("file-functions.php");
require_once("our-company-function.php");
require_once("agency-functions.php");
require_once("account-period-functions.php");
require_once("account-combined-agency-functions.php");
require_once("common.php");
require_once("bd.php");

function listLedgers(){
	
	try
	{
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$our_company_id=$_SESSION['adminSession']['oc_id'];
		$current_company=getCurrentCompanyForUser($admin_id);
		if($current_company[1]==0) // if current company is our company
		{
		$oc_id=$current_company[0];
		$agency_id="NULL";	
		}
		else if($current_company[1]==1) // if agency
		{
		$agency_id=$current_company[0];
		$oc_id="NULL";		
		}
		else if($current_company[1]==2) // if combined agency
		{
			$agency_id="NULL";	
			$oc_id="NULL";		
			$ca_id=$current_company[0];
			$agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);
			}
		$sql="SELECT ledger_id, ledger_name,address,head_id,postal_name, fin_city.city_id, area_id, pincode, pan_no, sales_no,opening_balance, opening_date, opening_cd,notes, fin_ac_ledgers.agency_id, fin_ac_ledgers.oc_id, fin_ac_ledgers.our_company_id,fin_ac_ledgers.date_added, fin_ac_ledgers.date_modified, fin_ac_ledgers.last_updated_by, city_name
		  FROM fin_ac_ledgers,fin_city";
		$sql=$sql." WHERE fin_ac_ledgers.city_id=fin_city.city_id  AND fin_ac_ledgers.our_company_id=$our_company_id AND ";
		if($oc_id=="NULL" && is_numeric($agency_id))
{
	$sql=$sql." agency_id=$agency_id  ";
}
if($agency_id=="NULL" && is_numeric($oc_id))
{
	$sql=$sql." oc_id=$oc_id  ";
}  
if($oc_id=="NULL" && $agency_id=="NULL")
{
	if(validateForNull($agency_ids,$oc_ids))
	$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
	else if(validateForNull($agency_ids))
	$sql=$sql." agency_id IN ( ".$agency_ids.")";
	else if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
}  

		  $sql=$sql." ORDER BY ledger_name";
		  
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
	}
	catch(Exception $e)
	{
	}
	
}

function getProfitAndLossLedgerId()
{
	    $pl_head_id = getProfitAndLossHeadId();
	
	    $admin_id=$_SESSION['adminSession']['admin_id'];
		$our_company_id=$_SESSION['adminSession']['oc_id'];
		$current_company=getCurrentCompanyForUser($admin_id);
		if($current_company[1]==0) // if current company is our company
		{
		$oc_id=$current_company[0];
		$agency_id="NULL";	
		}
		else if($current_company[1]==1) // if agency
		{
		$agency_id=$current_company[0];
		$oc_id="NULL";		
		}
		else if($current_company[1]==2) // if combined agency
		{
			$agency_id="NULL";	
			$oc_id="NULL";		
			$ca_id=$current_company[0];
			$agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);
		}
	
	if(is_numeric($pl_head_id))
	{
	$sql="SELECT ledger_id FROM fin_ac_ledgers WHERE head_id = $pl_head_id AND ";
	if($oc_id=="NULL" && is_numeric($agency_id))
{
	$sql=$sql." agency_id=$agency_id  ";
}
if($agency_id=="NULL" && is_numeric($oc_id))
{
	$sql=$sql." oc_id=$oc_id  ";
}  
if($oc_id=="NULL" && $agency_id=="NULL")
{
	if(validateForNull($agency_ids,$oc_ids))
	$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
	else if(validateForNull($agency_ids))
	$sql=$sql." agency_id IN ( ".$agency_ids.")";
	else if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
}  
	$result=dbQuery($sql);	 
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0]; 
	else
	{
		if($oc_id=="NULL" && is_numeric($agency_id))
		{
			createProfitAndLossLedgerForAgnecy($agency_id);
		}
		else if($agency_id=="NULL" && is_numeric($oc_id))
		{
			createProfitAndLossLedgerForOC($oc_id);
		}  
				
	}
	}
	return false;
}

function listNonAccountingLedgers() // normal ledgers without cash and bank
{
	
	try
	{
		$bank_head_id=getBankAccountsHeadId();
		$cash_head_id=getCashHeadId();
		$debtors_head_id=getSundryDebtorsId();
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$our_company_id=$_SESSION['adminSession']['oc_id'];
		$current_company=getCurrentCompanyForUser($admin_id);
		if($current_company[1]==0) // if current company is our company
		{
		$oc_id=$current_company[0];
		$agency_id="NULL";	
		}
		else if($current_company[1]==1) // if agency
		{
		$agency_id=$current_company[0];
		$oc_id="NULL";		
		}
		else if($current_company[1]==2) // if combined agency
		{
			$agency_id="NULL";	
			$oc_id="NULL";		
			$ca_id=$current_company[0];
			$agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);
			}
		$sql="SELECT ledger_id as id, ledger_name as name,head_id, fin_city.city_id, opening_balance, opening_date, opening_cd, agency_id, oc_id, our_company_id,city_name
		  FROM fin_ac_ledgers,fin_city
		  WHERE fin_ac_ledgers.city_id=fin_city.city_id  AND
		  head_id!=$bank_head_id AND head_id!=$cash_head_id AND
		   our_company_id=$our_company_id AND ";
		if($oc_id=="NULL" && is_numeric($agency_id))
{
	$sql=$sql." agency_id=$agency_id  ";
}
if($agency_id=="NULL" && is_numeric($oc_id))
{
	$sql=$sql." oc_id=$oc_id  ";
}  
		  $sql=$sql."";
if($oc_id=="NULL" && $agency_id=="NULL")
{
	if(validateForNull($agency_ids,$oc_ids))
	$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
	else if(validateForNull($agency_ids))
	$sql=$sql." agency_id IN ( ".$agency_ids.")";
	else if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
}  

		 
		  
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
	}
	catch(Exception $e)
	{
	}
	
}	

function listCustomerAndLedgers($like_term=false)
{
	try
	{
		$bank_head_id=getBankAccountsHeadId();
		$cash_head_id=getCashHeadId();
		$od_bank_head_id = getODBankAccountsHeadId();
		$debtors_head_id=getSundryDebtorsId();
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$our_company_id=$_SESSION['adminSession']['oc_id'];
		$current_company=getCurrentCompanyForUser($admin_id);
		if($current_company[1]==0) // if current company is our company
		{
		$oc_id=$current_company[0];
		$agency_id="NULL";	
		}
		else if($current_company[1]==1) // if agency
		{
		$agency_id=$current_company[0];
		$oc_id="NULL";		
			}
		else if($current_company[1]==2) // if combined agency
		{
			$agency_id="NULL";	
			$oc_id="NULL";		
			$ca_id=$current_company[0];
			$agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);
			}	
		$sql="SELECT CONCAT('L',ledger_id) as id, ledger_name as name, fin_city.city_id, agency_id, oc_id, our_company_id,city_name
		  FROM fin_ac_ledgers,fin_city
		  WHERE fin_ac_ledgers.city_id=fin_city.city_id  AND
		  head_id!=$bank_head_id AND head_id!=$cash_head_id AND head_id!=$od_bank_head_id AND
		  our_company_id=$our_company_id AND ";
		if($oc_id=="NULL" && is_numeric($agency_id))
{
	$sql=$sql." agency_id=$agency_id  ";
}
if($agency_id=="NULL" && is_numeric($oc_id))
{
	$sql=$sql." oc_id=$oc_id  ";
} 
if($oc_id=="NULL" && $agency_id=="NULL")
{
	if(validateForNull($agency_ids,$oc_ids))
	$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
	else if(validateForNull($agency_ids))
	$sql=$sql." agency_id IN ( ".$agency_ids.")";
	else if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
}  
if($like_term!=false)
{
	$sql=$sql."AND ledger_name LIKE '%$like_term%' ";
}  
		  $sql=$sql." UNION ALL 
		  SELECT CONCAT('C',fin_customer.customer_id) as id, CONCAT(customer_name,' ',file_number,' ',IFNULL(vehicle_reg_no,'')) as name, fin_city.city_id, agency_id, oc_id, our_company_id,city_name
		  FROM fin_customer
		  LEFT JOIN fin_city ON fin_customer.city_id=fin_city.city_id 
		  LEFT JOIN fin_file ON fin_customer.file_id=fin_file.file_id
		  LEFT JOIN fin_vehicle ON fin_vehicle.file_id=fin_customer.file_id
		  WHERE 
		  our_company_id=$our_company_id AND file_status!=3 AND 
		  ";
		 if($oc_id=="NULL" && is_numeric($agency_id))
{
	$sql=$sql." agency_id=$agency_id  ";
}
if($agency_id=="NULL" && is_numeric($oc_id))
{
	$sql=$sql." oc_id=$oc_id  ";
}   
if($oc_id=="NULL" && $agency_id=="NULL")
{
	if(validateForNull($agency_ids,$oc_ids))
	$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
	else if(validateForNull($agency_ids))
	$sql=$sql." agency_id IN ( ".$agency_ids.")";
	else if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
}  
if($like_term!=false)
{
	$sql=$sql."AND (customer_name LIKE '%$like_term%' OR file_number LIKE '%$like_term%' OR vehicle_reg_no LIKE '%$like_term%')";
} 
		 		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
	}
	catch(Exception $e)
	{
	}
	
	}
	
function listCustomerAndLedgersWithBankCash($like_term=false)
{
	try
	{
		$bank_head_id=getBankAccountsHeadId();
		$cash_head_id=getCashHeadId();
		$debtors_head_id=getSundryDebtorsId();
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$our_company_id=$_SESSION['adminSession']['oc_id'];
		$current_company=getCurrentCompanyForUser($admin_id);
		if($current_company[1]==0) // if current company is our company
		{
		$oc_id=$current_company[0];
		$agency_id="NULL";	
		}
		else if($current_company[1]==1) // if agency
		{
		$agency_id=$current_company[0];
		$oc_id="NULL";		
			}
		else if($current_company[1]==2) // if combined agency
		{
			$agency_id="NULL";	
			$oc_id="NULL";		
			$ca_id=$current_company[0];
			$agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);
			}	
		$sql="SELECT CONCAT('L',ledger_id) as id, ledger_name as name, fin_city.city_id, agency_id, oc_id, our_company_id,city_name
		  FROM fin_ac_ledgers,fin_city
		  WHERE fin_ac_ledgers.city_id=fin_city.city_id AND
		  our_company_id=$our_company_id AND ";
		if($oc_id=="NULL" && is_numeric($agency_id))
{
	$sql=$sql." agency_id=$agency_id  ";
}
if($agency_id=="NULL" && is_numeric($oc_id))
{
	$sql=$sql." oc_id=$oc_id  ";
} 
if($oc_id=="NULL" && $agency_id=="NULL")
{
	if(validateForNull($agency_ids,$oc_ids))
	$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
	else if(validateForNull($agency_ids))
	$sql=$sql." agency_id IN ( ".$agency_ids.")";
	else if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
}  
if($like_term!=false)
{
	$sql=$sql."AND ledger_name LIKE '%$like_term%' ";
}  
		  $sql=$sql." UNION ALL 
		  SELECT CONCAT('C',fin_customer.customer_id) as id, CONCAT(customer_name,' ',file_number,' ',IFNULL(vehicle_reg_no,'')) as name, fin_city.city_id, agency_id, oc_id, our_company_id,city_name
		  FROM fin_customer
		  LEFT JOIN fin_city ON fin_customer.city_id=fin_city.city_id 
		  LEFT JOIN fin_file ON fin_customer.file_id=fin_file.file_id
		  LEFT JOIN fin_vehicle ON fin_vehicle.file_id=fin_customer.file_id
		  WHERE 
		  our_company_id=$our_company_id AND file_status!=3 AND
		  ";
		 if($oc_id=="NULL" && is_numeric($agency_id))
{
	$sql=$sql." agency_id=$agency_id  ";
}
if($agency_id=="NULL" && is_numeric($oc_id))
{
	$sql=$sql." oc_id=$oc_id  ";
}   
if($oc_id=="NULL" && $agency_id=="NULL")
{
	if(validateForNull($agency_ids,$oc_ids))
	$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
	else if(validateForNull($agency_ids))
	$sql=$sql." agency_id IN ( ".$agency_ids.")";
	else if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
}  
if($like_term!=false)
{
	$sql=$sql."AND (customer_name LIKE '%$like_term%' OR file_number LIKE '%$like_term%' OR vehicle_reg_no LIKE '%$like_term%')";
} 

		 		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
	}
	catch(Exception $e)
	{
	}
	
	}	

function listAccountingLedgers() // bank or cash
{
	
	try
	{
		$bank_head_id=getBankAccountsHeadId();
		$cash_head_id=getCashHeadId();
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$our_company_id=$_SESSION['adminSession']['oc_id'];
		$current_company=getCurrentCompanyForUser($admin_id);
		if($current_company[1]==0) // if current company is our company
		{
		$oc_id=$current_company[0];
		$agency_id="NULL";	
		}
		else if($current_company[1]==1) // if agency
		{
		$agency_id=$current_company[0];
		$oc_id="NULL";		
			}
		else if($current_company[1]==2) // if combined agency
		{
			$agency_id="NULL";	
			$oc_id="NULL";		
			$ca_id=$current_company[0];
			$agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);
			}	
		$sql="SELECT ledger_id, ledger_name,address,head_id,postal_name, fin_city.city_id, area_id, pincode, pan_no, sales_no,opening_balance, opening_date, opening_cd,notes, agency_id, oc_id, our_company_id,fin_ac_ledgers.date_added, fin_ac_ledgers.date_modified, fin_ac_ledgers.last_updated_by, city_name
		  FROM fin_ac_ledgers,fin_city
		  WHERE fin_ac_ledgers.city_id=fin_city.city_id  AND
		  (head_id=$bank_head_id OR head_id=$cash_head_id) AND
		   our_company_id=$our_company_id AND ";
		if($oc_id=="NULL" && is_numeric($agency_id))
{
	$sql=$sql." agency_id=$agency_id  ";
}
if($agency_id=="NULL" && is_numeric($oc_id))
{
	$sql=$sql." oc_id=$oc_id  ";
}  
if($oc_id=="NULL" && $agency_id=="NULL")
{
	if(validateForNull($agency_ids,$oc_ids))
	$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
	else if(validateForNull($agency_ids))
	$sql=$sql." agency_id IN ( ".$agency_ids.")";
	else if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
}  


		  $sql=$sql." ORDER BY ledger_name";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
	}
	catch(Exception $e)
	{
	}
	
}	

function listAccountingLedgersWithOd() // bank or cash
{
	
	try
	{
		$bank_head_id=getBankAccountsHeadId();
		$cash_head_id=getCashHeadId();
		$od_bank_id = getODBankAccountsHeadId();
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$our_company_id=$_SESSION['adminSession']['oc_id'];
		$current_company=getCurrentCompanyForUser($admin_id);
		if($current_company[1]==0) // if current company is our company
		{
		$oc_id=$current_company[0];
		$agency_id="NULL";	
		}
		else if($current_company[1]==1) // if agency
		{
		$agency_id=$current_company[0];
		$oc_id="NULL";		
		}
		else if($current_company[1]==2) // if combined agency
		{
			$agency_id="NULL";	
			$oc_id="NULL";		
			$ca_id=$current_company[0];
			$agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);
			}	
		$sql="SELECT ledger_id, ledger_name,address,head_id,postal_name, fin_city.city_id, area_id, pincode, pan_no, sales_no,opening_balance, opening_date, opening_cd,notes, agency_id, oc_id, our_company_id,fin_ac_ledgers.date_added, fin_ac_ledgers.date_modified, fin_ac_ledgers.last_updated_by, city_name
		  FROM fin_ac_ledgers,fin_city
		  WHERE fin_ac_ledgers.city_id=fin_city.city_id  AND
		  (head_id=$bank_head_id OR head_id=$cash_head_id OR head_id = $od_bank_id) AND
		   our_company_id=$our_company_id AND ";
		if($oc_id=="NULL" && is_numeric($agency_id))
{
	$sql=$sql." agency_id=$agency_id  ";
}
if($agency_id=="NULL" && is_numeric($oc_id))
{
	$sql=$sql." oc_id=$oc_id  ";
}  
if($oc_id=="NULL" && $agency_id=="NULL")
{
	if(validateForNull($agency_ids,$oc_ids))
	$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
	else if(validateForNull($agency_ids))
	$sql=$sql." agency_id IN ( ".$agency_ids.")";
	else if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
}  


		  $sql=$sql." ORDER BY ledger_name";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
	}
	catch(Exception $e)
	{
	}
	
}	


function listBankAccountingLedgers() // bank or cash
{
	
	try
	{
		$bank_head_id=getBankAccountsHeadId();
	
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$our_company_id=$_SESSION['adminSession']['oc_id'];
		$current_company=getCurrentCompanyForUser($admin_id);
		if($current_company[1]==0) // if current company is our company
		{
		$oc_id=$current_company[0];
		$agency_id="NULL";	
		}
		else if($current_company[1]==1) // if agency
		{
		$agency_id=$current_company[0];
		$oc_id="NULL";		
			}
		else if($current_company[1]==2) // if combined agency
		{
			$agency_id="NULL";	
			$oc_id="NULL";		
			$ca_id=$current_company[0];
			$agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);
			}	
		$sql="SELECT ledger_id, ledger_name,address,head_id,postal_name, fin_city.city_id, area_id, pincode, pan_no, sales_no,opening_balance, opening_date, opening_cd,notes, agency_id, oc_id, our_company_id,fin_ac_ledgers.date_added, fin_ac_ledgers.date_modified, fin_ac_ledgers.last_updated_by, city_name
		  FROM fin_ac_ledgers,fin_city
		  WHERE fin_ac_ledgers.city_id=fin_city.city_id  AND
		  (head_id=$bank_head_id) AND
		   our_company_id=$our_company_id AND ";
		if($oc_id=="NULL" && is_numeric($agency_id))
{
	$sql=$sql." agency_id=$agency_id  ";
}
if($agency_id=="NULL" && is_numeric($oc_id))
{
	$sql=$sql." oc_id=$oc_id  ";
}  
if($oc_id=="NULL" && $agency_id=="NULL")
{
	if(validateForNull($agency_ids,$oc_ids))
	$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
	else if(validateForNull($agency_ids))
	$sql=$sql." agency_id IN ( ".$agency_ids.")";
	else if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
}  


		  $sql=$sql." ORDER BY ledger_name";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
	}
	catch(Exception $e)
	{
	}
	
}	

function listAccountingLedgersForCombinedAgency($ca_id) //only bank 
{
	
	try
	{
		if(checkForNumeric($ca_id))
		{
		$bank_head_id=getBankAccountsHeadId();
		$od_bank_id = getODBankAccountsHeadId();
			$agency_id="NULL";	
			$oc_id="NULL";		
		    $agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);
			
		$our_company_id=$_SESSION['adminSession']['oc_id'];
		
		$sql="SELECT ledger_id, ledger_name,address,head_id,postal_name, fin_city.city_id, area_id, pincode, pan_no, sales_no,opening_balance, opening_date, opening_cd,notes, agency_id, oc_id, our_company_id,fin_ac_ledgers.date_added, fin_ac_ledgers.date_modified, fin_ac_ledgers.last_updated_by, city_name
		  FROM fin_ac_ledgers,fin_city
		  WHERE fin_ac_ledgers.city_id=fin_city.city_id  AND
		  (head_id=$bank_head_id OR head_id = $od_bank_id) AND
		   our_company_id=$our_company_id AND ";
		
if($oc_id=="NULL" && $agency_id=="NULL")
{
	if(validateForNull($agency_ids,$oc_ids))
	$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
	else if(validateForNull($agency_ids))
	$sql=$sql." agency_id IN ( ".$agency_ids.")";
	else if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
}  
	
  
		  $sql=$sql." ORDER BY ledger_name";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}

function listAccountingLedgersForAgency($agency_id) //only bank 
{
	
	try
	{
		if(checkForNumeric($agency_id))
		{
		$bank_head_id=getBankAccountsHeadId();
		$od_bank_id = getODBankAccountsHeadId();
		$cash_head_id=getCashHeadId();
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$our_company_id=$_SESSION['adminSession']['oc_id'];
		
		$sql="SELECT ledger_id, ledger_name,address,head_id,postal_name, fin_city.city_id, area_id, pincode, pan_no, sales_no,opening_balance, opening_date, opening_cd,notes, agency_id, oc_id, our_company_id,fin_ac_ledgers.date_added, fin_ac_ledgers.date_modified, fin_ac_ledgers.last_updated_by, city_name
		  FROM fin_ac_ledgers,fin_city
		  WHERE fin_ac_ledgers.city_id=fin_city.city_id  AND
		   (head_id=$bank_head_id OR head_id = $od_bank_id) AND
		   our_company_id=$our_company_id AND ";
	      $sql=$sql." agency_id=$agency_id  ";
		  $sql=$sql." ORDER BY ledger_name";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}	



function listAccountingLedgersForOC($oc_id) //only bank
{
	
	try
	{
		if(checkForNumeric($oc_id))
		{
		$bank_head_id=getBankAccountsHeadId();
		$cash_head_id=getCashHeadId();
		$od_bank_id = getODBankAccountsHeadId();
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$our_company_id=$_SESSION['adminSession']['oc_id'];
		
		$sql="SELECT ledger_id, ledger_name,address,head_id,postal_name, fin_city.city_id, area_id, pincode, pan_no, sales_no,opening_balance, opening_date, opening_cd,notes, agency_id, oc_id, our_company_id,fin_ac_ledgers.date_added, fin_ac_ledgers.date_modified, fin_ac_ledgers.last_updated_by, city_name
		  FROM fin_ac_ledgers,fin_city
		  WHERE fin_ac_ledgers.city_id=fin_city.city_id  AND
		  (head_id=$bank_head_id OR head_id = $od_bank_id)  AND
		  our_company_id=$our_company_id AND ";
	$sql=$sql." oc_id=$oc_id  ";  
		  $sql=$sql." ORDER BY ledger_name";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}

	


function insertLedger($name,$postal_name,$address,$city_id,$area,$pincode,$head_id,$contact_no,$pan_no,$sales_no,$notes,$opening_balance,$opening_balance_cd,$agency_id=NULL,$oc_id=NULL,$in_percent=0,$out_percent=0,$type=0) // type=2 broker for pali
{
	
	try
	{
		
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$our_company_id=$_SESSION['adminSession']['oc_id'];
		if(checkForNumeric($agency_id))
		{
			$oc_id="NULL";
			}
		else if(checkForNumeric($oc_id))
		{
			$agency_id="NULL";
			}
		
		if(!(checkForNumeric($agency_id) || checkForNumeric($oc_id)))
		{		
			$current_company=getCurrentCompanyForUser($admin_id);
			if($current_company[1]==0) // if current company is our company
			{
			$oc_id=$current_company[0];
			$agency_id="NULL";	
			}
			else if($current_company[1]==1) // if agency
			{
			$agency_id=$current_company[0];
			$oc_id="NULL";		
				}
			else if($current_company[1]==2) // if combined agency
			{
				$agency_oc_ids=getAgencyOCForCombinedAgency($current_company[0]);
				if(checkForNumeric($agency_oc_ids[0][0]))
				{	
				$agency_id=$agency_oc_ids[0][0];
				$oc_id="NULL";	
				}
				else if(checkForNumeric($agency_oc_ids[1][0]))
				{	
				$oc_id=$agency_oc_ids[1][0];
				$agency_id="NULL";	
				}
			}	
		}
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		$address=clean_data($address);
		if(!checkForNumeric($opening_balance))
		$opening_balance=0;
		
		if(!validateForNull($pan_no))
		{
			$pan_no='0';
			}
		if(!validateForNull($sales_no))
		{
			$sales_no='0';
			}	
		if(!validateForNull($postal_name))
		{
			$postal_name="NA";
			}	
		if(!validateForNull($address))
		{
			$address="NA";
			}	
			
		if(!validateForNull($city_id) || $city_id==-1)
		{
			$city_id=insertCityIfNotDuplicate("NA");
			}	
		
		if(!validateForNull($pincode))
		{
			$pincode=111111;
			}		
			
		if(!validateForNull($area))
		{
			
			$area_id=insertArea("NA",$city_id);
			}
		else
		{
			$area_id=insertArea($area,$city_id);
			}		
		
		if(!validateForNull($notes))
		{
			$notes="NA";
			}	
			
		if(validateForNull($name)  && checkForNumeric($head_id,$in_percent,$out_percent,$type) && $head_id>0 && strlen($pincode)==6 && !checkforDuplicateLedger($name,$oc_id,$agency_id))
			{
		
			$opening_date=getBooksStartingDateForCurrentCompanyOfUser();
			if(!validateForNull($opening_date))
			$opening_date=ACCOUNT_STARTING_DATE;
			
			$sql="INSERT INTO fin_ac_ledgers
					(ledger_name, head_id, postal_name,  address, city_id, area_id,pincode, pan_no, sales_no,opening_balance,opening_cd, opening_date, notes, agency_id, oc_id, our_company_id, created_by, last_updated_by, date_added, date_modified, in_percent, out_percent, ledger_type)
					VALUES
					('$name',$head_id,'$postal_name','$address',$city_id,$area_id,$pincode,'$pan_no','$sales_no',$opening_balance, $opening_balance_cd, '$opening_date','$notes', $agency_id , $oc_id, $our_company_id ,$admin_id,$admin_id,NOW(),NOW(),$in_percent,$out_percent,$type)";
		
			dbQuery($sql);
			$ledger_id=dbInsertId();
			addledgerContactNo($ledger_id,$contact_no);
			return $ledger_id;
			}
		else 
		{
			return "error";
			}	
	}
	catch(Exception $e)
	{
	}
	
}

function checkforDuplicateLedger($ledger_name,$oc_id=NULL,$agency_id=NULL)
{
	if(validateForNull($ledger_name))
	{
	$admin_id=$_SESSION['adminSession']['admin_id'];	
	if(checkForNumeric($agency_id))
		{
			$oc_id="NULL";
			}
		else if(checkForNumeric($oc_id))
		{
			$agency_id="NULL";
			}
		if(!(checkForNumeric($agency_id) || checkForNumeric($oc_id)))
		{		
			$current_company=getCurrentCompanyForUser($admin_id);
			if($current_company[1]==0) // if current company is our company
			{
			$oc_id=$current_company[0];
			$agency_id="NULL";	
			}
			else if($current_company[1]==1) // if agency
			{
			$agency_id=$current_company[0];
			$oc_id="NULL";		
				}
			else if($current_company[1]==2) // if combined agency
			{
				$agency_oc_ids=getAgencyOCForCombinedAgency($current_company[0]);
				if(checkForNumeric($agency_oc_ids[0][0]))
				{	
				$agency_id=$agency_oc_ids[0][0];
				$oc_id="NULL";	
				}
				else if(checkForNumeric($agency_oc_ids[1][0]))
				{	
				$oc_id=$agency_oc_ids[1][0];
				$agency_id="NULL";	
				}
			}	
		}
		
	 $sql="SELECT ledger_id FROM fin_ac_ledgers WHERE ledger_name='$ledger_name'";
	 if(is_numeric($oc_id))
	 $sql=$sql." AND oc_id = $oc_id";
	  if(is_numeric($agency_id))
	 $sql=$sql." AND agency_id = $agency_id";
	 $result = dbQuery($sql);
	 $resultArray = dbResultToArray($result);
	 if(dbNumRows($result)>0)
	 return $resultArray[0][0];
	 else
	 return false;	
	}
}



function deleteLedger($ledger_id)
{
	
	if(checkForNumeric($ledger_id) && !checkIfLedgerInUse($ledger_id))
	{
		$sql="DELETE FROM fin_ac_ledgers WHERE ledger_id=$ledger_id";
		dbQuery($sql);
		return "success";
		}
	}
	
function checkIfLedgerInUse($ledger_id)
{
	if(checkForNumeric($ledger_id))
	{
		
		$sql="SELECT contra_id as id FROM fin_ac_contra WHERE from_ledger_id = $ledger_id OR to_ledger_id = $ledger_id
			  UNION ALL
		      SELECT jv_cd_id as id FROM fin_ac_jv_cd WHERE from_ledger_id = $ledger_id OR to_ledger_id = $ledger_id
			  UNION ALL
		      SELECT payment_id as id FROM fin_ac_payment WHERE from_ledger_id = $ledger_id OR to_ledger_id = $ledger_id
			  UNION ALL
		      SELECT receipt_id as id FROM fin_ac_receipt WHERE from_ledger_id = $ledger_id OR to_ledger_id = $ledger_id
		";
		
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
	}
}		

function updateLedger($id,$name,$postal_name,$address,$city_id,$area,$pincode,$head_id,$contact_no,$pan_no,$sales_no,$notes,$opening_balance,$opening_balance_cd,$in_percent=0,$out_percent=0){
	
	try
	{
		
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		$address=clean_data($address);
		
		if(!checkForNumeric($opening_balance))
		$opening_balance=0;
		
		if(!validateForNull($pan_no))
		{
			$pan_no='0';
			}
		if(!validateForNull($sales_no))
		{
			$sales_no='0';
			}	
		if(!validateForNull($postal_name))
		{
			$postal_name="NA";
			}	
		if(!validateForNull($address))
		{
			$address="NA";
			}		
		if(!validateForNull($city_id) || $city_id==-1)
		{
			$city_id=insertCityIfNotDuplicate("NA");
			}	
		if(!validateForNull($pincode))
		{
			$pincode=111111;
			}		
		if(!validateForNull($area))
		{
			$area_id=insertArea("NA",$city_id);
			}
		else
		{
			$area_id=insertArea($area,$city_id);
			}		
		
		if(!validateForNull($notes))
		{
			$notes="NA";
			}				
		if($name!=NULL && $name!=''  && checkForNumeric($id,$head_id))
			{
			
			$admin_id=$_SESSION['adminSession']['admin_id'];
			$sql="UPDATE fin_ac_ledgers
					SET ledger_name = '$name', head_id=$head_id, postal_name='$postal_name', address ='$address', city_id = $city_id, area_id=$area_id, pincode=$pincode, pan_no='$pan_no',sales_no='$sales_no',opening_balance=$opening_balance, opening_cd=$opening_balance_cd, notes='$notes' ,last_updated_by=$admin_id, date_modified=NOW(), in_percent = $in_percent, out_percent = $out_percent
					WHERE ledger_id=$id";
					
			dbQuery($sql);
			deleteAllContactNoLedger($id);	
			addLedgerContactNo($id,$contact_no);
			return "success";
			}
		else
		{
			return "error";
			}	
		
	}
	catch(Exception $e)
	{
	}
	
}	

function getLedgerById($id){
	
	try
	{
		$sql="SELECT ledger_id, ledger_name,address,head_id,postal_name, fin_city.city_id, area_id, pincode, pan_no, sales_no,opening_balance,opening_cd,opening_date,notes,fin_ac_ledgers.date_added, fin_ac_ledgers.date_modified, fin_ac_ledgers.last_updated_by, city_name,agency_id,oc_id, in_percent, out_percent
		  FROM fin_ac_ledgers,fin_city
		  WHERE fin_ac_ledgers.city_id=fin_city.city_id
		  AND ledger_id=$id";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0]; 
		else
		return false;
	}
	catch(Exception $e)
	{
	}
	
}

function getCompanyNameByLedgerId($id)
{
	try
	{
		$sql="SELECT ledger_id, ledger_name,address,head_id,postal_name, fin_city.city_id, area_id, pincode, pan_no, sales_no,opening_balance,opening_cd,opening_date,notes,fin_ac_ledgers.date_added, fin_ac_ledgers.date_modified, fin_ac_ledgers.last_updated_by, city_name,agency_id,oc_id
		  FROM fin_ac_ledgers,fin_city
		  WHERE fin_ac_ledgers.city_id=fin_city.city_id
		  AND ledger_id=$id";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$oc_id = $resultArray[0]['oc_id'];
			$agency_id = $resultArray[0]['agency_id'];
			if(is_numeric($oc_id))
			{
				$oc = getOurCompanyByID($oc_id);
				return $oc['our_company_name'];
			}
			else
			{
				$agency = getAgencyById($agency_id);
				return $agency['agency_name'];
				}
		} 
		else
		return false;
		
	}
	catch(Exception $e)
	{
	}
	
}

function getLedgerNameFromLedgerId($id)
{
try
	{
		$sql="SELECT  ledger_name
		  FROM fin_ac_ledgers
		  WHERE ledger_id=$id";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0]; 
		else
		return false;
	}
	catch(Exception $e)
	{
	}	
}
	


function addLedgerContactNo($ledger_id,$contact_no)
{
	try
	{
		if(is_array($contact_no))
		{
			foreach($contact_no as $no)
			{
				if($no!="" && $no!=NULL && is_numeric($no))
				{
				insertContactNoLedger($ledger_id,$no); 
				}
			}
		}
		else
		{
			if($contact_no!="" && $contact_no!=NULL && is_numeric($contact_no))
				{
				insertContactNoLedger($ledger_id,$contact_no); 
				}
			
		}
	}
	catch(Exception $e)
	{
	}
}

function insertContactNoLedger($id,$contact_no)
{
	try
	{
		if(checkForNumeric($id,$contact_no)==true && !checkForDuplicateContactNoledger($id,$contact_no))
		{
		$sql="INSERT INTO fin_ac_ledgers_contact_no
				      (contact_no, ledger_id)
					  VALUES
					  ('$contact_no', $id)";
				dbQuery($sql);	  
		}
	}
	catch(Exception $e)
	{}
	
	
}
function deleteContactNoLedger($id)
{
	try
	{
		$sql="DELETE FROM fin_ac_ledgers_contact_no
			  WHERE ledger_contact_no_id=$id";
		dbQuery($sql);	  
	}
	catch(Exception $e)
	{}
	
	
	
	}
function deleteAllContactNoLedger($id)
{
	try
	{
		$sql="DELETE FROM fin_ac_ledgers_contact_no
			  WHERE ledger_id=$id";
		dbQuery($sql);
	}
	catch(Exception $e)
	{}
	
	
	
	}	
function updateContactNoVehicleledger($id,$contact_no)
{
	try
	{
		deleteAllContactNoLedger($id);
		addLedgerContactNo($id,$contact_no);
	}
	catch(Exception $e)
	{}
	
	
	
	}	

function checkForDuplicateContactNoledger($id,$contact_no)
{
	if(checkForNumeric($id,$contact_no))
	{
	$sql="SELECT ledger_contact_no_id
	      FROM fin_ac_ledgers_contact_no
		  WHERE contact_no='$contact_no'
		  AND ledger_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
	else
	return false;	
	}
	}	

	
function getledgerNumbersByledgerId($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT contact_no
	      FROM fin_ac_ledgers_contact_no
		  WHERE fin_ac_ledgers_contact_no.ledger_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
	else
	return false;	
	}
	}	
	
function createCashLedgerForAgnecy($agency_id)
{
	
	
	$cash_head_id=getCashHeadId();
	if(checkForNumeric($agency_id))
	{
		
		$result=insertLedger('Cash','','',NULL,NULL,'',$cash_head_id,'','','','',0,0,$agency_id);
		if(checkForNumeric($result))
		return true;
		else
		return false;
		}
	return false;	
}

function createCashLedgerForOC($oc_id)
{
	
	$cash_head_id=getCashHeadId();
	if(checkForNumeric($oc_id))
	{
		
		$result=insertLedger('Cash','','',NULL,NULL,'',$cash_head_id,'','','','',0,0,NULL,$oc_id);
		if(checkForNumeric($result))
		return true;
		else
		return false;
		}
	return false;	
}

function createProfitAndLossLedgerForAgnecy($agency_id)
{
	
	$cash_head_id=getProfitAndLossHeadId();
	if(checkForNumeric($agency_id))
	{
		
		$result=insertLedger('Profit And Loss','','',NULL,NULL,'',$cash_head_id,'','','','',0,0,$agency_id);
		if(checkForNumeric($result))
		return $result;
		else
		return false;
		}
	return false;	
}

function createProfitAndLossLedgerForOC($oc_id)
{
	
	$cash_head_id=getProfitAndLossHeadId();
	if(checkForNumeric($oc_id))
	{
		
		$result=insertLedger('Profit And Loss','','',NULL,NULL,'',$cash_head_id,'','','','',0,0,NULL,$oc_id);
		if(checkForNumeric($result))
		return $result;
		else
		return false;
		}
	return false;	
}

function createAutoInterestLedgerForAgency($agency_id)
{
	
	if(defined('AUTO_INTEREST_NAME'))
	{
	 $ledger_name = AUTO_INTEREST_NAME;	
	}
	else
	$ledger_name = 'Auto Interest';
	
	if(defined('AUTO_INTEREST_TYPE') && AUTO_INTEREST_TYPE==1)
	{
		$unsecured_loans_head_id = AUTO_INTEREST_HEAD;
	}
	else
	{
	$unsecured_loans_head_id=getUnsecuredLoansId();
	}
	if(checkForNumeric($agency_id))
	{
		
		$result=insertLedger($ledger_name,'','',NULL,NULL,'',$unsecured_loans_head_id,'','','','',0,0,$agency_id);
		if(checkForNumeric($result))
		return true;
		else
		return false;
		}
	return false;	
}

function createAutoInterestLedgerForOC($oc_id)
{
	
	if(defined('AUTO_INTEREST_NAME'))
	{
	 $ledger_name = AUTO_INTEREST_NAME;	
	}
	else
	$ledger_name = 'Auto Interest';
	
	if(defined('AUTO_INTEREST_TYPE') && AUTO_INTEREST_TYPE==1)
	{
		$unsecured_loans_head_id = AUTO_INTEREST_HEAD;
	}
	else
	{
	$unsecured_loans_head_id=getUnsecuredLoansId();
	}
	
	if(checkForNumeric($oc_id))
	{
		
		$result=insertLedger($ledger_name,'','',NULL,NULL,'',$unsecured_loans_head_id,'','','','',0,0,NULL,$oc_id);
		if(checkForNumeric($result))
		return true;
		else
		return false;
		}
	return false;	
}

function createPenaltyLedgerForAgency($agency_id)
{
	
	if(defined('PENALTY_LEDGER'))
	{
	 $ledger_name = PENALTY_LEDGER;	
	}
	else
	$ledger_name = 'Penalty Income';
	
	if(defined('PENALTY_HEAD_ID') && checkForNumeric(PENALTY_HEAD_ID))
	{
		$penalty_head_id = PENALTY_HEAD_ID;
	}
	
	if(checkForNumeric($agency_id))
	{
		
		$result=insertLedger($ledger_name,'','',NULL,NULL,'',$penalty_head_id,'','','','',0,0,$agency_id);
		if(checkForNumeric($result))
		return true;
		else
		return false;
		}
	return false;	
}

function createPenaltyLedgerForOC($oc_id)
{
	
	if(defined('PENALTY_LEDGER'))
	{
	 $ledger_name = PENALTY_LEDGER;	
	}
	else
	$ledger_name = 'Penalty Income';
	
	if(defined('PENALTY_HEAD_ID') && checkForNumeric(PENALTY_HEAD_ID))
	{
		$penalty_head_id = PENALTY_HEAD_ID;
	}
	if(checkForNumeric($oc_id))
	{
		
		$result=insertLedger($ledger_name,'','',NULL,NULL,'',$penalty_head_id,'','','','',0,0,NULL,$oc_id);
		if(checkForNumeric($result))
		return true;
		else
		return false;
		}
	return false;	
}
function createIncomeLedgerForAgency($agency_id)
{
	
	if(defined('FINANCE_INCOME_NAME') && validateForNull(FINANCE_INCOME_NAME))
	{
	 $ledger_name = FINANCE_INCOME_NAME;	
	}
	else
	$ledger_name = 'Finance Income';
	
	if(defined('FINANCE_INCOME_HEAD') && checkForNumeric(FINANCE_INCOME_HEAD))
	{
		$unsecured_loans_head_id = FINANCE_INCOME_HEAD;
	}
	else
	{
	$unsecured_loans_head_id=getIndirectIncomeId();
	}
	
	
	if(checkForNumeric($agency_id))
	{
		
		$result=insertLedger($ledger_name,'','',NULL,NULL,'',$unsecured_loans_head_id,'','','','',0,0,$agency_id);
		if(checkForNumeric($result))
		return true;
		else
		return false;
		}
	return false;	
}

function createIncomeLedgerForOC($oc_id)
{
	
	if(defined('FINANCE_INCOME_NAME') && validateForNull(FINANCE_INCOME_NAME))
	{
	 $ledger_name = FINANCE_INCOME_NAME;	
	}
	else
	$ledger_name = 'Finance Income';
	
	if(defined('FINANCE_INCOME_HEAD') && checkForNumeric(FINANCE_INCOME_HEAD))
	{
		$unsecured_loans_head_id = FINANCE_INCOME_HEAD;
	}
	else
	{
	$unsecured_loans_head_id=getIndirectIncomeId();
	}
	if(checkForNumeric($oc_id))
	{
		
		$result=insertLedger($ledger_name,'','',NULL,NULL,'',$unsecured_loans_head_id,'','','','',0,0,NULL,$oc_id);
		if(checkForNumeric($result))
		return true;
		else
		return false;
		}
	return false;	
}

function getLedgerHeadType($ledger_id) // returns 0 if bank or cash else 1
{
	if(checkForNumeric($ledger_id))
	{
		$ledger=getLedgerById($ledger_id);
		$ledger_head_id=$ledger['head_id'];
		
		$bank_head_id=getBankAccountsHeadId();
		$od_bank_head_id=getODBankAccountsHeadId();
		$cash_head_id=getCashHeadId();
		
		if($ledger_head_id==$bank_head_id || $ledger_head_id==$cash_head_id || $od_bank_head_id==$ledger_head_id)
		return 0;
		else
		return 1;
		
		}
	}
function getLedgersForHeadIdAgency($head_id,$agency_id)
{
	if(checkForNumeric($head_id,$agency_id))
	{
		$sql="";
		
		}
	
	}
function getLedgersForHeadIdOC($head_id,$oc_id)
{
	
	}	

function getCompanyForLedger($ledger_id)
{
	if(checkForNumeric($ledger_id))
	{
	
		$ledger=getLedgerById($ledger_id);
		$agency_id=$ledger['agency_id'];
		$oc_id=$ledger['oc_id'];
		if(checkForNumeric($oc_id))
		return array($oc_id,0);
		else if(checkForNumeric($agency_id))
		return array($agency_id,1);
		}
	}	

function getOpeningBalanceForLedgerCustomer($id) // returns array 1: opening balance, 2: balance type credit(1) or debit(0)
{
	
	if(substr($id, 0, 1) == 'L')
	{
		$ledger_id=str_replace('L','',$id);
		$ledger_id=intval($ledger_id);
		$ledger=getLedgerById($ledger_id);
		return array($ledger['opening_balance'],$ledger['opening_cd']);
		}
	else if(substr($id, 0, 1) == 'C')
	{
		
		$customer_id=str_replace('C','',$id);
		$customer_id=intval($customer_id);
		
		$customer=getCustomerDetailsByCustomerId($customer_id);
		return array($customer['opening_balance'],$customer['opening_cd']);
		}	
	
	}
	
function getOpeningBalanceForLedgerArray($id_array) // returns array 1: opening balance, 2: balance type credit(1) or debit(0)
{
	if(is_array($id_array) && checkForNumeric($id_array[0]))
	{
		$ids=implode(',',$id_array);
		$sql="SELECT SUM(CASE WHEN opening_cd = 1 THEN opening_balance ELSE 0 END) AS CreditTotal, SUM(CASE WHEN opening_cd = 0 THEN opening_balance ELSE 0 END) AS DebitTotal
		      FROM fin_ac_ledgers
			  WHERE ledger_id IN (".$ids.")";
		$result=dbQuery($sql);
	    $result_array=dbResultToArray($result);
		}
		return $result_array[0]['DebitTotal']-$result_array[0]['CreditTotal'];
			
	
	}	
function getOpeningBalanceForCustomerArray($id_array) // returns array 1: opening balance, 2: balance type credit(1) or debit(0)
{
	
	if(is_array($id_array) && checkForNumeric($id_array[0]))
	{
		$ids=implode(',',$id_array);
		$sql="SELECT SUM(CASE WHEN opening_cd = 1 THEN opening_balance ELSE 0 END) AS CreditTotal, SUM(CASE WHEN opening_cd = 0 THEN opening_balance ELSE 0 END) AS DebitTotal
		      FROM fin_customer
			  WHERE customer_id IN (".$ids.")";
	  
		$result=dbQuery($sql);
	    $result_array=dbResultToArray($result);
		}
		return $result_array[0]['DebitTotal']-$result_array[0]['CreditTotal'];
			
	
	}		

function getBooksStartingDateForLedgerCustomer($id)
{
	if(substr($id, 0, 1) == 'L')
	{
		
		$ledger_id=str_replace('L','',$id);
		$ledger_id=intval($ledger_id);
		$ledger=getLedgerById($ledger_id);
		$company_array=getCompanyForLedger($ledger_id);
		
		$company_type=$company_array[1];
		$company_id=$company_array[0];
		
		if($company_type==1)
		$account_settings=getAccountsSettingsForAgency($company_id);
		else if($company_type==0)
		{
		$account_settings=getAccountsSettingsForOC($company_id);
		
		}
		
		
		return $account_settings['ac_starting_date'];
		}
	else if(substr($id, 0, 1) == 'C')
	{
		$customer_id=str_replace('C','',$id);
		$customer_id=intval($customer_id);
		$customer=getCustomerDetailsByCustomerId($customer_id);
		$file_id=getFileIdFromCustomerId($customer_id);
		$company_array=getAgencyOrCompanyIdFromFileId($file_id);
		
		$company_type=$company_array[0];
		$company_id=$company_array[1];
		
		if($company_type=="agency")
		$account_settings=getAccountsSettingsForAgency($company_id);
		else if($company_type=="oc")
		$account_settings=getAccountsSettingsForOC($company_id);
		
		
		return $account_settings['ac_starting_date'];
		}	
	}

function getLedgersForHeadId($head_id)
{
	
	if(checkForNumeric($head_id))
	{
		$head_id_String=$head_id;
		}
	else if(is_array($head_id))
	{
		$head_id_String=implode(',',$head_id);
		}	
	else
	return false;
	
	$admin_id=$_SESSION['adminSession']['admin_id'];
	$our_company_id=$_SESSION['adminSession']['oc_id'];
		$current_company=getCurrentCompanyForUser($admin_id);
		if($current_company[1]==0) // if current company is our company
		{
		$oc_id=$current_company[0];
		$agency_id="NULL";	
		}
		else if($current_company[1]==1) // if agency
		{
		$agency_id=$current_company[0];
		$oc_id="NULL";		
		}
		else if($current_company[1]==2) // if combined agency
		{
			$agency_id="NULL";	
			$oc_id="NULL";		
			$ca_id=$current_company[0];
			$agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);
			}
	
	$sql="SELECT ledger_id,ledger_name,head_id
	      FROM fin_ac_ledgers
		  WHERE head_id IN (".$head_id_String.") AND fin_ac_ledgers.our_company_id=$our_company_id AND ";
		if($oc_id=="NULL" && is_numeric($agency_id))
{
	$sql=$sql." agency_id=$agency_id  ";
}
if($agency_id=="NULL" && is_numeric($oc_id))
{
	$sql=$sql." oc_id=$oc_id  ";
}  
if($oc_id=="NULL" && $agency_id=="NULL")
{
	if(validateForNull($agency_ids,$oc_ids))
	$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
	else if(validateForNull($agency_ids))
	$sql=$sql." agency_id IN ( ".$agency_ids.")";
	else if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
}  

	$result=dbQuery($sql);	
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray; 
	else
	return false;  	
}

function getLedgerIdsForHeadId($head_id) // provides ledgers for head id for the current company selected for the user
{
	if(checkForNumeric($head_id))
	{
		$head_id_String=$head_id;
		}
	else if(is_array($head_id))
	{
		$head_id_String=implode(',',$head_id);
		}	
	else
	return false;
	
	$admin_id=$_SESSION['adminSession']['admin_id'];
	$our_company_id=$_SESSION['adminSession']['oc_id'];
		$current_company=getCurrentCompanyForUser($admin_id);
		if($current_company[1]==0) // if current company is our company
		{
		$oc_id=$current_company[0];
		$agency_id="NULL";	
		}
		else if($current_company[1]==1) // if agency
		{
		$agency_id=$current_company[0];
		$oc_id="NULL";		
		}
		else if($current_company[1]==2) // if combined agency
		{
			$agency_id="NULL";	
			$oc_id="NULL";		
			$ca_id=$current_company[0];
			$agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);
			}
	
	$sql="SELECT ledger_id
	      FROM fin_ac_ledgers
		  WHERE head_id IN (".$head_id_String.") AND fin_ac_ledgers.our_company_id=$our_company_id AND ";
		if($oc_id=="NULL" && is_numeric($agency_id))
{
	$sql=$sql." agency_id=$agency_id  ";
}
if($agency_id=="NULL" && is_numeric($oc_id))
{
	$sql=$sql." oc_id=$oc_id  ";
}  
if($oc_id=="NULL" && $agency_id=="NULL")
{
	if(validateForNull($agency_ids,$oc_ids))
	$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
	else if(validateForNull($agency_ids))
	$sql=$sql." agency_id IN ( ".$agency_ids.")";
	else if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
}  
	$result=dbQuery($sql);	
	$return_array=array();
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
	foreach($resultArray as $ledger)
	$return_array[]=$ledger['ledger_id'];	
	return $return_array;
	}
	else
	return false;  	
}

function listCustomers()
{
	try
	{
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$our_company_id=$_SESSION['adminSession']['oc_id'];
		$current_company=getCurrentCompanyForUser($admin_id);
		if($current_company[1]==0) // if current company is our company
		{
		$oc_id=$current_company[0];
		$agency_id="NULL";	
		}
		else if($current_company[1]==1) // if agency
		{
		$agency_id=$current_company[0];
		$oc_id="NULL";		
			}
		else if($current_company[1]==2) // if combined agency
		{
			$agency_id="NULL";	
			$oc_id="NULL";		
			$ca_id=$current_company[0];
			$agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);
			}	
		
			  $sql="SELECT CONCAT('C',fin_customer.customer_id) as id, CONCAT(customer_name,' ',file_number,' ',IFNULL(vehicle_reg_no,'')) as name, agency_id, oc_id, our_company_id
			  FROM fin_customer
			  LEFT JOIN fin_file ON fin_customer.file_id=fin_file.file_id
			  LEFT JOIN fin_vehicle ON fin_vehicle.file_id=fin_customer.file_id
			  WHERE 
			  our_company_id=$our_company_id AND 
			  ";
			 if($oc_id=="NULL" && is_numeric($agency_id))
	{
		$sql=$sql." agency_id=$agency_id  ";
	}
	if($agency_id=="NULL" && is_numeric($oc_id))
	{
		$sql=$sql." oc_id=$oc_id  ";
	}   
	if($oc_id=="NULL" && $agency_id=="NULL")
	{
		if(validateForNull($agency_ids,$oc_ids))
		$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
		else if(validateForNull($agency_ids))
		$sql=$sql." agency_id IN ( ".$agency_ids.")";
		else if(validateForNull($oc_ids))
		$sql=$sql." oc_id IN ( ".$oc_ids.")";
	}  
		 		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
	}
	catch(Exception $e)
	{
	}
	
}	

function listCustomerIDs()
{
	try
	{
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$our_company_id=$_SESSION['adminSession']['oc_id'];
		$current_company=getCurrentCompanyForUser($admin_id);
		if($current_company[1]==0) // if current company is our company
		{
		$oc_id=$current_company[0];
		$agency_id="NULL";	
		}
		else if($current_company[1]==1) // if agency
		{
		$agency_id=$current_company[0];
		$oc_id="NULL";		
			}
		else if($current_company[1]==2) // if combined agency
		{
			$agency_id="NULL";	
			$oc_id="NULL";		
			$ca_id=$current_company[0];
			$agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);
			}	
		
			  $sql="SELECT fin_customer.customer_id as id
			  FROM fin_customer
			  LEFT JOIN fin_file ON fin_customer.file_id=fin_file.file_id
			  WHERE 
			  our_company_id=$our_company_id AND file_status!=3 AND 
			  ";
			 if($oc_id=="NULL" && is_numeric($agency_id))
	{
		$sql=$sql." agency_id=$agency_id  ";
	}
	if($agency_id=="NULL" && is_numeric($oc_id))
	{
		$sql=$sql." oc_id=$oc_id  ";
	}   
	if($oc_id=="NULL" && $agency_id=="NULL")
	{
		if(validateForNull($agency_ids,$oc_ids))
		$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
		else if(validateForNull($agency_ids))
		$sql=$sql." agency_id IN ( ".$agency_ids.")";
		else if(validateForNull($oc_ids))
		$sql=$sql." oc_id IN ( ".$oc_ids.")";
	}  
		 		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
	{
	$return_array=array();	
	foreach($resultArray as $ledger)
	$return_array[]=$ledger['id'];	
	return $return_array;
	}
		else
		return false;
	}
	catch(Exception $e)
	{
	}
	
}			

function listCustomerAndLedgersWithBankCashForHeadId($head_id)
{
	try
	{
		
		if(checkForNumeric($head_id))
		{
		$head_id_String=$head_id;
		}
	else if(is_array($head_id))
	{
		$head_id_String=implode(',',$head_id);
		}	
	else
	return false;
		$bank_head_id=getBankAccountsHeadId();
		$cash_head_id=getCashHeadId();
		$debtors_head_id=getSundryDebtorsId();
		$current_assests_head_id=getCurrentAssetsId();
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$our_company_id=$_SESSION['adminSession']['oc_id'];
		$current_company=getCurrentCompanyForUser($admin_id);
		if($current_company[1]==0) // if current company is our company
		{
		$oc_id=$current_company[0];
		$agency_id="NULL";	
		}
		else if($current_company[1]==1) // if agency
		{
		$agency_id=$current_company[0];
		$oc_id="NULL";		
			}
		else if($current_company[1]==2) // if combined agency
		{
			$agency_id="NULL";	
			$oc_id="NULL";		
			$ca_id=$current_company[0];
			$agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);
			}	
		$sql="SELECT CONCAT('L',ledger_id) as id, ledger_name as name, agency_id, oc_id, our_company_id
		  FROM fin_ac_ledgers
		  WHERE head_id IN (".$head_id_String.")  AND
		  our_company_id=$our_company_id AND ";
		if($oc_id=="NULL" && is_numeric($agency_id))
{
	$sql=$sql." agency_id=$agency_id  ";
}
if($agency_id=="NULL" && is_numeric($oc_id))
{
	$sql=$sql." oc_id=$oc_id  ";
} 
if($oc_id=="NULL" && $agency_id=="NULL")
{
	if(validateForNull($agency_ids,$oc_ids))
	$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
	else if(validateForNull($agency_ids))
	$sql=$sql." agency_id IN ( ".$agency_ids.")";
	else if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
}  
if(in_array($debtors_head_id,$head_id) || in_array($current_assests_head_id,$head_id) || $head_id==$current_assests_head_id || $head_id==$debtors_head_id)
{
			  $sql=$sql." UNION ALL 
			  SELECT CONCAT('C',fin_customer.customer_id) as id, CONCAT(customer_name,' ',file_number,' ',IFNULL(vehicle_reg_no,'')) as name, agency_id, oc_id, our_company_id
			  FROM fin_customer
			  LEFT JOIN fin_file ON fin_customer.file_id=fin_file.file_id
			  LEFT JOIN fin_vehicle ON fin_vehicle.file_id=fin_customer.file_id
			  WHERE 
			  our_company_id=$our_company_id AND file_status!=3 AND 
			  ";
			 if($oc_id=="NULL" && is_numeric($agency_id))
	{
		$sql=$sql." agency_id=$agency_id  ";
	}
	if($agency_id=="NULL" && is_numeric($oc_id))
	{
		$sql=$sql." oc_id=$oc_id  ";
	}   
	if($oc_id=="NULL" && $agency_id=="NULL")
	{
		if(validateForNull($agency_ids,$oc_ids))
		$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
		else if(validateForNull($agency_ids))
		$sql=$sql." agency_id IN ( ".$agency_ids.")";
		else if(validateForNull($oc_ids))
		$sql=$sql." oc_id IN ( ".$oc_ids.")";
	}  
}
		 		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
	}
	catch(Exception $e)
	{
	}
	
	}		
	
function listDirectCustomerAndLedgersWithBankCashForHeadId($head_id)
{
	try
	{
		
		if(checkForNumeric($head_id))
		{
		$head_id_String=$head_id;
		}
	else if(is_array($head_id))
	{
		$head_id_String=implode(',',$head_id);
		}	
	else
	return false;
		$bank_head_id=getBankAccountsHeadId();
		$cash_head_id=getCashHeadId();
		$debtors_head_id=getSundryDebtorsId();
		
		$current_assests_head_id=getCurrentAssetsId();
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$our_company_id=$_SESSION['adminSession']['oc_id'];
		$current_company=getCurrentCompanyForUser($admin_id);
		if($current_company[1]==0) // if current company is our company
		{
		$oc_id=$current_company[0];
		$agency_id="NULL";	
		}
		else if($current_company[1]==1) // if agency
		{
		$agency_id=$current_company[0];
		$oc_id="NULL";		
			}
		else if($current_company[1]==2) // if combined agency
		{
			$agency_id="NULL";	
			$oc_id="NULL";		
			$ca_id=$current_company[0];
			$agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$agency_id_array=$agency_oc_id_array[0];	
			$oc_id_array=$agency_oc_id_array[1];
			$agency_ids=implode(',',$agency_id_array);
			$oc_ids=implode(',',$oc_id_array);
			}	
		$sql="SELECT CONCAT('L',ledger_id) as id, ledger_name as name, agency_id, oc_id, our_company_id
		  FROM fin_ac_ledgers
		  WHERE head_id IN (".$head_id_String.")  AND
		  our_company_id=$our_company_id AND ";
		if($oc_id=="NULL" && is_numeric($agency_id))
{
	$sql=$sql." agency_id=$agency_id  ";
}
if($agency_id=="NULL" && is_numeric($oc_id))
{
	$sql=$sql." oc_id=$oc_id  ";
} 
if($oc_id=="NULL" && $agency_id=="NULL")
{
	if(validateForNull($agency_ids,$oc_ids))
	$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
	else if(validateForNull($agency_ids))
	$sql=$sql." agency_id IN ( ".$agency_ids.")";
	else if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
}  
if($head_id==$debtors_head_id || (is_array($head_id) &&  in_array($debtors_head_id,$head_id)))
{
			  $sql=$sql." UNION ALL 
			  SELECT CONCAT('C',fin_customer.customer_id) as id, CONCAT(customer_name,' ',file_number,' ',IFNULL(vehicle_reg_no,'')) as name, agency_id, oc_id, our_company_id
			  FROM fin_customer
			  LEFT JOIN fin_file ON fin_customer.file_id=fin_file.file_id
			  LEFT JOIN fin_vehicle ON fin_vehicle.file_id=fin_customer.file_id
			  WHERE 
			  our_company_id=$our_company_id AND file_status !=3 AND
			  ";
			 if($oc_id=="NULL" && is_numeric($agency_id))
	{
		$sql=$sql." agency_id=$agency_id  ";
	}
	if($agency_id=="NULL" && is_numeric($oc_id))
	{
		$sql=$sql." oc_id=$oc_id  ";
	}   
	if($oc_id=="NULL" && $agency_id=="NULL")
	{
		if(validateForNull($agency_ids,$oc_ids))
		$sql=$sql." (agency_id IN (".$agency_ids.") OR oc_id IN (".$oc_ids.")) ";
		else if(validateForNull($agency_ids))
		$sql=$sql." agency_id IN ( ".$agency_ids.")";
		else if(validateForNull($oc_ids))
		$sql=$sql." oc_id IN ( ".$oc_ids.")";
	}  
}
	
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
	}
	catch(Exception $e)
	{
	}
	
	}	
	
function checkifHeadisInPLSheet($head_id)
{
	if(checkForNumeric($head_id))
	{
		$PLSheet_head_array=array(20,21,22,23);
		if(in_array($head_id,$PLSheet_head_array))
		{
			return true;
			}
		else return false;	
		}
	return false;
	}	
	
function getLedgerIdsArrayForLedgerNameId($ledger_name)
{
	if(validateForNull($ledger_name))
	{
		
		list($ledger_name,$ledger_id)=explode('|',$ledger_name);
		$brackets = array('[',']');
		$ledger_id = str_replace($brackets,'',$ledger_id);
	    
		
		
		
		
		
			return $ledger_id;
			
		
		return false;
	}
	return false;
}	

function getInOutPercentForLedgerId($ledger_id)	
{
	if(checkForNumeric($ledger_id))
	{
		$sql="SELECT in_percent, out_percent FROM fin_ac_ledgers WHERE ledger_id = $ledger_id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		return array($resultArray[0][0],$resultArray[0][1]);
	}
}	

function listRTOBrokers($like_term=false){
	
	try
	{
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$our_company_id=$_SESSION['adminSession']['oc_id'];
		$current_company=getCurrentCompanyForUser($admin_id);
		if($current_company[1]==0) // if current company is our company
		{
		$oc_id=$current_company[0];	
		}
		
		else if($current_company[1]==2) // if combined agency
		{
				
			$oc_id="NULL";		
			$ca_id=$current_company[0];
			$agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);		
			$oc_id_array=$agency_oc_id_array[1];
			$oc_ids=implode(',',$oc_id_array);
			}
		$sql="SELECT ledger_id, ledger_name,address,head_id,postal_name, fin_city.city_id, area_id, pincode, pan_no, sales_no,opening_balance, opening_date, opening_cd,notes,  fin_ac_ledgers.oc_id, fin_ac_ledgers.our_company_id,fin_ac_ledgers.date_added, fin_ac_ledgers.date_modified, fin_ac_ledgers.last_updated_by, city_name
		  FROM fin_ac_ledgers,fin_city";
		$sql=$sql." WHERE ledger_type = 1 AND fin_ac_ledgers.city_id=fin_city.city_id  AND ";
	
if(is_numeric($oc_id))
{
	$sql=$sql." oc_id=$oc_id  ";
}  
if($oc_id=="NULL")
{
    if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
}  
if($like_term!=false)
{
	$sql=$sql." AND ledger_name LIKE '%$like_term%' ";
}
		  $sql=$sql." ORDER BY ledger_name";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
	}
	catch(Exception $e)
	{
	}
	
}
		
?>