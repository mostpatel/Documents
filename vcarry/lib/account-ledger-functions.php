<?php 
require_once("cg.php");
require_once("common.php");
require_once("city-functions.php");
require_once("area-functions.php");
require_once("tax-functions.php");
require_once("account-head-functions.php");
require_once("customer-functions.php");
require_once("account-period-functions.php");
require_once("account-combined-agency-functions.php");
require_once("bd.php");

function listPurchaseLedgers($like_term=false,$oc_id=NULL)
{
		$purchase_head_id=getPurchaseHeadId();
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		if(!checkForNumeric($oc_id))
		{
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
			
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
		}
			
		$sql="SELECT ledger_id, ledger_name,address,head_id,postal_name, edms_city.city_id, area_id, pincode, pan_no, sales_no,opening_balance, opening_date, opening_cd,notes, oc_id, our_company_id,edms_ac_ledgers.date_added, edms_ac_ledgers.date_modified, edms_ac_ledgers.last_updated_by, city_name, tax_class_id
		  FROM edms_ac_ledgers,edms_city
		  WHERE edms_ac_ledgers.city_id=edms_city.city_id  AND
		  (head_id=$purchase_head_id) AND ";
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
	$sql=$sql."AND ledger_name LIKE '%$like_term%' ";
}

		  $sql=$sql." ORDER BY ledger_name";
		
		$result=dbQuery($sql);
			 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
}

function setLedgerOpeningBalance($opening_balance,$opening_cd,$ledger_id)
{
	if(checkForNumeric($opening_balance,$opening_cd
	,$ledger_id))
	{
	$sql="UPDATE edms_ac_ledgers SET opening_balance = $opening_balance, opening_cd = $opening_cd WHERE ledger_id = $ledger_id";
	dbQuery($sql);
	return true;
	}
	return false;
}

function setLedgerCurrentBalance($current_balance,$current_cd,$ledger_id)
{
	if(checkForNumeric($current_balance,$current_cd
	,$ledger_id))
	{
	$sql="UPDATE edms_ac_ledgers SET current_balance = $current_balance, current_balance_cd = $current_cd WHERE ledger_id = $ledger_id";
	dbQuery($sql);
	return true;
	}
	return false;
}
	
function listSalesLedgers($like_term=false,$oc_id=NULL)
{
	    $sales_head_id=getSalesHeadId();
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		
		if(!validateForNull($oc_id) || !checkForNumeric($oc_id))
		{
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
			
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
		}
		
		$sql="SELECT ledger_id, ledger_name,address,head_id,postal_name, edms_city.city_id, area_id, pincode, pan_no, sales_no,opening_balance, opening_date, opening_cd,notes, oc_id, our_company_id,edms_ac_ledgers.date_added, edms_ac_ledgers.date_modified, edms_ac_ledgers.last_updated_by, city_name, tax_class_id
		  FROM edms_ac_ledgers,edms_city
		  WHERE edms_ac_ledgers.city_id=edms_city.city_id  AND
		  (head_id=$sales_head_id)  AND ";
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
	$sql=$sql."AND ledger_name LIKE '%$like_term%' ";
}
		  $sql=$sql." ORDER BY ledger_name";
		
		$result=dbQuery($sql);
			 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
	
	}

function listLedgers($like_term=false){
	
	try
	{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
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
		$sql="SELECT ledger_id, ledger_name,address,head_id,postal_name, edms_city.city_id, area_id, pincode, pan_no, sales_no,opening_balance, opening_date, opening_cd,notes,  edms_ac_ledgers.oc_id, edms_ac_ledgers.our_company_id,edms_ac_ledgers.date_added, edms_ac_ledgers.date_modified, edms_ac_ledgers.last_updated_by, city_name, tax_class_id
		  FROM edms_ac_ledgers,edms_city";
		$sql=$sql." WHERE edms_ac_ledgers.city_id=edms_city.city_id   AND ";
	
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
	$sql=$sql."AND ledger_name LIKE '%$like_term%' ";
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

function listSuppliers($like_term=false){
	
	try
	{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
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
		$sql="SELECT ledger_id, ledger_name,address,head_id,postal_name, edms_city.city_id, area_id, pincode, pan_no, sales_no,opening_balance, opening_date, opening_cd,notes,  edms_ac_ledgers.oc_id, edms_ac_ledgers.our_company_id,edms_ac_ledgers.date_added, edms_ac_ledgers.date_modified, edms_ac_ledgers.last_updated_by, city_name, tax_class_id
		  FROM edms_ac_ledgers,edms_city";
		$sql=$sql." WHERE ledger_type = 1 AND edms_ac_ledgers.city_id=edms_city.city_id  AND ";
	
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
	$sql=$sql."AND ledger_name LIKE '%$like_term%' ";
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

function listOutsideLabourProviders($like_term=false){
	
	try
	{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
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
		$sql="SELECT ledger_id, ledger_name,address,head_id,postal_name, edms_city.city_id, area_id, pincode, pan_no, sales_no,opening_balance, opening_date, opening_cd,notes,  edms_ac_ledgers.oc_id, edms_ac_ledgers.our_company_id,edms_ac_ledgers.date_added, edms_ac_ledgers.date_modified, edms_ac_ledgers.last_updated_by, city_name
		  FROM edms_ac_ledgers,edms_city";
		$sql=$sql." WHERE ledger_type = 4 AND edms_ac_ledgers.city_id=edms_city.city_id   AND ";
	
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
	$sql=$sql."AND ledger_name LIKE '%$like_term%' ";
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

function listSalesman($like_term=false){
	
	try
	{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
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
		$sql="SELECT ledger_id, ledger_name,address,head_id,postal_name, edms_city.city_id, area_id, pincode, pan_no, sales_no,opening_balance, opening_date, opening_cd,notes,  edms_ac_ledgers.oc_id, edms_ac_ledgers.our_company_id,edms_ac_ledgers.date_added, edms_ac_ledgers.date_modified, edms_ac_ledgers.last_updated_by, city_name
		  FROM edms_ac_ledgers,edms_city";
		$sql=$sql." WHERE ledger_type = 2 AND edms_ac_ledgers.city_id=edms_city.city_id   AND ";
	
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
	$sql=$sql."AND ledger_name LIKE '%$like_term%' ";
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

function listFinancers($like_term=false){
	
	try
	{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
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
		$sql="SELECT ledger_id, ledger_name,address,head_id,postal_name, edms_city.city_id, area_id, pincode, pan_no, sales_no,opening_balance, opening_date, opening_cd,notes,  edms_ac_ledgers.oc_id, edms_ac_ledgers.our_company_id,edms_ac_ledgers.date_added, edms_ac_ledgers.date_modified, edms_ac_ledgers.last_updated_by, city_name
		  FROM edms_ac_ledgers,edms_city";
		$sql=$sql." WHERE ledger_type = 3 AND edms_ac_ledgers.city_id=edms_city.city_id  AND ";
	
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
	$sql=$sql."AND ledger_name LIKE '%$like_term%' ";
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

function listDealers($like_term=false){
	
	try
	{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
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
		$sql="SELECT ledger_id, ledger_name,address,head_id,postal_name, edms_city.city_id, area_id, pincode, pan_no, sales_no,opening_balance, opening_date, opening_cd,notes,  edms_ac_ledgers.oc_id, edms_ac_ledgers.our_company_id,edms_ac_ledgers.date_added, edms_ac_ledgers.date_modified, edms_ac_ledgers.last_updated_by, city_name
		  FROM edms_ac_ledgers,edms_city";
		$sql=$sql." WHERE ledger_type = 5 AND edms_ac_ledgers.city_id=edms_city.city_id AND ";
	
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
	$sql=$sql."AND ledger_name LIKE '%$like_term%' ";
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

function listBrokers($like_term=false){
	
	try
	{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
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
		$sql="SELECT ledger_id, ledger_name,address,head_id,postal_name, edms_city.city_id, area_id, pincode, pan_no, sales_no,opening_balance, opening_date, opening_cd,notes,  edms_ac_ledgers.oc_id, edms_ac_ledgers.our_company_id,edms_ac_ledgers.date_added, edms_ac_ledgers.date_modified, edms_ac_ledgers.last_updated_by, city_name
		  FROM edms_ac_ledgers,edms_city";
		$sql=$sql." WHERE ledger_type = 6 AND edms_ac_ledgers.city_id=edms_city.city_id  AND ";
	
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
	$sql=$sql."AND ledger_name LIKE '%$like_term%' ";
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

function getLedgerNameFromLedgerNameLedgerId($name)
{
	if(validateForNull($name))
	{
		$sql="SELECT ledger_id, CONCAT(ledger_name, ' | [',ledger_id,']') as full_ledger_name from edms_ac_ledgers HAVING full_ledger_name='$name'";	
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0]; 
		else
		return false;
	}
	
}

function getCustomerLedgerIDFromLedgerNameLedgerId($name)
{
	if(validateForNull($name))
	{
		$name = clean_data($name);
		$last_pos=strrpos($name,'[');
		$prefix = substr($name,$last_pos+1,1);
		
		if($prefix=="L")
		$sql="SELECT CONCAT('L',ledger_id) as id, CONCAT(ledger_name, ' | [L',ledger_id,']') as full_ledger_name from edms_ac_ledgers HAVING full_ledger_name='$name'";
		else
		$sql="SELECT CONCAT('C',edms_customer.customer_id) as id, CONCAT(customer_name,IFNULL(CONCAT(' ',vehicle_reg_no),''), ' | [C',edms_customer.customer_id,']') as full_ledger_name FROM edms_customer LEFT JOIN edms_vehicle ON edms_vehicle.customer_id = edms_customer.customer_id HAVING full_ledger_name='$name'";
		
		$result=dbQuery($sql);	 
		
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray[0][0]; 
		else
		return false;
	}
	
}

function getCustomerLedgerNameFromLedgerNameLedgerId($id)
{
	if(validateForNull($id))
	{
		$id = clean_data($id);
		
		$prefix = substr($id,0,1);
		$suffix = substr($id,1);
		if($prefix=="L")
		$sql="SELECT CONCAT('L',ledger_id) as id, CONCAT(ledger_name, ' | [L',ledger_id,']') as full_ledger_name from edms_ac_ledgers WHERE ledger_id = $suffix";
		else
		$sql="SELECT CONCAT('C',edms_customer.customer_id) as id, CONCAT(customer_name,IFNULL(CONCAT(' ',vehicle_reg_no),''), ' | [C',edms_customer.customer_id,']') as full_ledger_name FROM edms_customer LEFT JOIN edms_vehicle ON edms_vehicle.customer_id = edms_customer.customer_id WHERE edms_customer.customer_id = $suffix";
		
		$result=dbQuery($sql);	 
		
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray[0][1]; 
		else
		return false;
	}
	
}



function listFinancersDealersBrokers($like_term=false,$oc_id=NULL){
	
	try
	{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		if(!checkForNumeric($oc_id))
		{
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
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
		}
		$sql="SELECT ledger_id, ledger_name,address,head_id,postal_name, edms_city.city_id, area_id, pincode, pan_no, sales_no,opening_balance, opening_date, opening_cd,notes,  edms_ac_ledgers.oc_id, edms_ac_ledgers.our_company_id,edms_ac_ledgers.date_added, edms_ac_ledgers.date_modified, edms_ac_ledgers.last_updated_by, city_name
		  FROM edms_ac_ledgers,edms_city";
		$sql=$sql." WHERE (ledger_type = 3 OR ledger_type = 5 OR ledger_type = 6) AND edms_ac_ledgers.city_id=edms_city.city_id AND ";
	
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
	$sql=$sql."AND ledger_name LIKE '%$like_term%' ";
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

function listNonAccountingLedgers($like_term=false,$oc_id=NULL) // normal ledgers without cash and bank
{
	
	try
	{
		$bank_head_id=getBankAccountsHeadId();
		$cash_head_id=getCashHeadId();
		$debtors_head_id=getSundryDebtorsId();
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		if(!checkForNumeric($oc_id))
		{
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
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
		}
		$sql="SELECT ledger_id as id,ledger_id,ledger_name, ledger_name as name,head_id, edms_city.city_id, opening_balance, opening_date, opening_cd, oc_id, our_company_id,city_name
		  FROM edms_ac_ledgers,edms_city
		  WHERE edms_ac_ledgers.city_id=edms_city.city_id  AND
		  head_id!=$bank_head_id AND head_id!=$cash_head_id  AND ";
		
if(is_numeric($oc_id))
{
	$sql=$sql." oc_id=$oc_id  ";
}  
		  $sql=$sql."";
if($oc_id=="NULL")
{
	if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
}  
if($like_term!=false)
{
	$sql=$sql."AND ledger_name LIKE '%$like_term%' ";
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

function listCustomerAndLedgers($like_term=false,$oc_id=NULL)
{
	try
	{
		$bank_head_id=getBankAccountsHeadId();
		$cash_head_id=getCashHeadId();
		$debtors_head_id=getSundryDebtorsId();
		$bank_od_head_id = getODBankAccountsHeadId();
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		if(!checkForNumeric($oc_id))
		{
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
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
		}
		$sql="SELECT CONCAT('L',ledger_id) as id, ledger_name as name, edms_city.city_id,  oc_id, our_company_id,city_name
		  FROM edms_ac_ledgers,edms_city
		  WHERE edms_ac_ledgers.city_id=edms_city.city_id  AND
		  head_id!=$bank_head_id AND head_id!=$cash_head_id AND head_id!=$bank_od_head_id AND ";
		
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
		  $sql=$sql." UNION ALL 
		  SELECT CONCAT('C',edms_customer.customer_id) as id, CONCAT(customer_name,IFNULL(CONCAT(' ',vehicle_reg_no),'')) as name, edms_city.city_id,  oc_id, our_company_id,city_name
		  FROM edms_customer
		  LEFT JOIN edms_city ON edms_customer.city_id=edms_city.city_id
		  LEFT JOIN edms_vehicle ON edms_vehicle.customer_id=edms_customer.customer_id
		  WHERE 
		  1=1   
		  ";
		
if(is_numeric($oc_id) && defined('CUSTOMER_MULTI_COMPANY') && CUSTOMER_MULTI_COMPANY==0)
{
	$sql=$sql." AND oc_id=$oc_id  ";
}   
if($oc_id=="NULL" && defined('CUSTOMER_MULTI_COMPANY') && CUSTOMER_MULTI_COMPANY==0)
{
    if(validateForNull($oc_ids))
	$sql=$sql." AND oc_id IN ( ".$oc_ids.") ";
}  
if($like_term!=false)
{
	$sql=$sql." AND (customer_name LIKE '%$like_term%' OR vehicle_reg_no LIKE '%$like_term%')";
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
function listCustomerLegders($like_term=false,$oc_id=NULL)
{
		$bank_head_id=getBankAccountsHeadId();
		$cash_head_id=getCashHeadId();
		$debtors_head_id=getSundryDebtorsId();
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		if(!checkForNumeric($oc_id))
		{
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
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
		}
	$sql=$sql."  SELECT CONCAT('C',edms_customer.customer_id) as id, CONCAT(customer_name,IFNULL(CONCAT(' ',vehicle_reg_no),'')) as name, edms_city.city_id,  oc_id, our_company_id,city_name
		  FROM edms_customer
		  LEFT JOIN edms_city ON edms_customer.city_id=edms_city.city_id
		  LEFT JOIN edms_vehicle ON edms_vehicle.customer_id=edms_customer.customer_id
		  WHERE 
		  1=1   
		  ";
		
if(is_numeric($oc_id) && defined('CUSTOMER_MULTI_COMPANY') && CUSTOMER_MULTI_COMPANY==0)
{
	$sql=$sql." AND oc_id=$oc_id  ";
}   
if($oc_id=="NULL" && defined('CUSTOMER_MULTI_COMPANY') && CUSTOMER_MULTI_COMPANY==0)
{
    if(validateForNull($oc_ids))
	$sql=$sql." AND oc_id IN ( ".$oc_ids.") ";
}  
if($like_term!=false)
{
	$sql=$sql." AND (customer_name LIKE '%$like_term%' OR vehicle_reg_no LIKE '%$like_term%')";
} 
			
		 		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
	
	
}	

function listCustomerAndLedgersWithoutPurchaseAndSales($like_term=false,$oc_id=NULL)
{
	try
	{
		$bank_head_id=getBankAccountsHeadId();
		$cash_head_id=getCashHeadId();
		$purchase_head_id=getPurchaseHeadId();
		$sales_head_id=getSalesHeadId();
		$debtors_head_id=getSundryDebtorsId();
		$tax_head_id=getTaxHeadId();
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		if(!checkForNumeric($oc_id))
		{
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
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
		}
		$sql="SELECT CONCAT('L',ledger_id) as id, ledger_name as name, edms_city.city_id,  oc_id, our_company_id,city_name
		  FROM edms_ac_ledgers,edms_city
		  WHERE edms_ac_ledgers.city_id=edms_city.city_id   AND head_id!=$sales_head_id AND head_id!=$purchase_head_id AND head_id!=$tax_head_id AND
		   ";
		
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
	$sql=$sql."AND ledger_name LIKE '%$like_term%' ";
}  
		  $sql=$sql." UNION ALL 
		  SELECT CONCAT('C',edms_customer.customer_id) as id, CONCAT(customer_name,IFNULL(CONCAT(' ',vehicle_reg_no),'')) as name, edms_city.city_id,  oc_id, our_company_id,city_name
		  FROM edms_customer
		  LEFT JOIN edms_city ON edms_customer.city_id=edms_city.city_id
		  LEFT JOIN edms_vehicle ON edms_vehicle.customer_id=edms_customer.customer_id
		  WHERE 1=1
		    
		  ";
		  
	
if(is_numeric($oc_id) && defined('CUSTOMER_MULTI_COMPANY') && CUSTOMER_MULTI_COMPANY==0)
{
	$sql=$sql." AND oc_id=$oc_id  ";
}   
if($oc_id=="NULL" && defined('CUSTOMER_MULTI_COMPANY') && CUSTOMER_MULTI_COMPANY==0)
{
    if(validateForNull($oc_ids))
	$sql=$sql."  AND oc_id IN ( ".$oc_ids.")  ";
}  
if($like_term!=false)
{
	$sql=$sql." AND (customer_name LIKE '%$like_term%' OR vehicle_reg_no LIKE '%$like_term%')";
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
	
function listCustomerAndLedgersWithBankCash($like_term=false,$oc_id=NULL)
{
	try
	{
		$bank_head_id=getBankAccountsHeadId();
		$cash_head_id=getCashHeadId();
		$debtors_head_id=getSundryDebtorsId();
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		if(!checkForNumeric($oc_id))
		{
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
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
		}
		$sql="SELECT CONCAT('L',ledger_id) as id, ledger_name as name, edms_city.city_id,  oc_id, our_company_id,city_name
		  FROM edms_ac_ledgers,edms_city
		  WHERE edms_ac_ledgers.city_id=edms_city.city_id  AND ";
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
	$sql=$sql."AND ledger_name LIKE '%$like_term%' ";
}  
		  $sql=$sql." UNION ALL 
		  SELECT CONCAT('C',edms_customer.customer_id) as id, CONCAT(customer_name,IFNULL(CONCAT(' ',vehicle_reg_no),'')) as name, edms_city.city_id,  oc_id, our_company_id,city_name
		  FROM edms_customer
		  LEFT JOIN edms_city ON edms_customer.city_id=edms_city.city_id 
		  LEFT JOIN edms_vehicle ON edms_vehicle.customer_id=edms_customer.customer_id
		  WHERE 
		   1=1 
		  ";
		
if(is_numeric($oc_id) && defined('CUSTOMER_MULTI_COMPANY') && CUSTOMER_MULTI_COMPANY==0)
{
	$sql=$sql." AND  oc_id=$oc_id  ";
}   
if($oc_id=="NULL" && defined('CUSTOMER_MULTI_COMPANY') && CUSTOMER_MULTI_COMPANY==0)
{
	if(validateForNull($oc_ids))
	$sql=$sql." AND oc_id IN ( ".$oc_ids.") ";
}  
if($like_term!=false)
{
	$sql=$sql."  AND (customer_name LIKE '%$like_term%' OR vehicle_reg_no LIKE '%$like_term%')";
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

function listAccountingLedgers($oc_id=NULL) // bank or cash
{
	
	try
	{
		
		$bank_head_id=getBankAccountsHeadId();
		$bank_od_head_id = getODBankAccountsHeadId();
		$cash_head_id=getCashHeadId();
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		if(!checkForNumeric($oc_id))
		{
			$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
				
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
		}
			
		$sql="SELECT ledger_id, ledger_name,address,head_id,postal_name, edms_city.city_id, area_id, pincode, pan_no, sales_no,opening_balance, opening_date, opening_cd,notes, oc_id, our_company_id,edms_ac_ledgers.date_added, edms_ac_ledgers.date_modified, edms_ac_ledgers.last_updated_by, city_name
		  FROM edms_ac_ledgers,edms_city
		  WHERE edms_ac_ledgers.city_id=edms_city.city_id  AND
		  (head_id=$bank_head_id OR head_id=$cash_head_id OR head_id = $bank_od_head_id)  AND ";
if(is_numeric($oc_id))
{
	$sql=$sql." oc_id=$oc_id  ";
}  
if($oc_id=="NULL")
{
if(validateForNull($oc_ids))
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

function listAccountingLedgerIDs($oc_id=NULL) // bank or cash
{
	
	try
	{
		
		$bank_head_id=getBankAccountsHeadId();
		$cash_head_id=getCashHeadId();
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		if(!checkForNumeric($oc_id))
		{
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
			
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
		}
		$sql="SELECT ledger_id, ledger_name,address,head_id,postal_name, edms_city.city_id, area_id, pincode, pan_no, sales_no,opening_balance, opening_date, opening_cd,notes, oc_id, our_company_id,edms_ac_ledgers.date_added, edms_ac_ledgers.date_modified, edms_ac_ledgers.last_updated_by, city_name
		  FROM edms_ac_ledgers,edms_city
		  WHERE edms_ac_ledgers.city_id=edms_city.city_id  AND
		  (head_id=$bank_head_id OR head_id=$cash_head_id) AND ";
if(is_numeric($oc_id))
{
	$sql=$sql." oc_id=$oc_id  ";
}  
if($oc_id=="NULL")
{
if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
}  


		  $sql=$sql." ORDER BY ledger_name";
		
		$result=dbQuery($sql);
		$return_array = array();	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		foreach($resultArray as $re)	
		$return_array[] = $re['ledger_id'];
		return $return_array;
		}
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
				
			$oc_id="NULL";		
		    $agency_oc_id_array=getAgencyOCForCombinedAgency($ca_id);	
			$oc_id_array=$agency_oc_id_array[1];
			$oc_ids=implode(',',$oc_id_array);
			
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
		
		$sql="SELECT ledger_id, ledger_name,address,head_id,postal_name, edms_city.city_id, area_id, pincode, pan_no, sales_no,opening_balance, opening_date, opening_cd,notes,  oc_id, our_company_id,edms_ac_ledgers.date_added, edms_ac_ledgers.date_modified, edms_ac_ledgers.last_updated_by, city_name
		  FROM edms_ac_ledgers,edms_city
		  WHERE edms_ac_ledgers.city_id=edms_city.city_id  AND
		  head_id=$bank_head_id AND ";
		
if($oc_id=="NULL")
{
	if(validateForNull($oc_ids))
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



function listAccountingLedgersForOC($oc_id) //only bank
{
	
	try
	{
		if(checkForNumeric($oc_id))
		{
		$bank_head_id=getBankAccountsHeadId();
		$cash_head_id=getCashHeadId();
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
		
		$sql="SELECT ledger_id, ledger_name,address,head_id,postal_name, edms_city.city_id, area_id, pincode, pan_no, sales_no,opening_balance, opening_date, opening_cd,notes,  oc_id, our_company_id,edms_ac_ledgers.date_added, edms_ac_ledgers.date_modified, edms_ac_ledgers.last_updated_by, city_name
		  FROM edms_ac_ledgers,edms_city
		  WHERE edms_ac_ledgers.city_id=edms_city.city_id  AND
		  head_id=$bank_head_id   AND ";
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

	


function insertLedger($name,$postal_name,$address,$city_id,$area,$pincode,$head_id,$contact_no,$pan_no,$sales_no,$notes,$opening_balance,$opening_balance_cd,$oc_id=NULL,$type=0,$tax_class_id="NULL",$cst_no="",$service_tax_no=""){
	
	try
	{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
		
		
		if(!(checkForNumeric($oc_id)))
		{		
			$current_company=getCurrentCompanyForUser($admin_id);
			if($current_company[1]==0) // if current company is our company
			{
			$oc_id=$current_company[0];	
			}
			else if($current_company[1]==2) // if combined agency
			{
				$agency_oc_ids=getAgencyOCForCombinedAgency($current_company[0]);
				if(checkForNumeric($agency_oc_ids[1][0]))
				{	
				$oc_id=$agency_oc_ids[1][0];
				}
			}	
		}
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		$address=clean_data($address);
		if(!checkForNumeric($opening_balance))
		$opening_balance=0;
		if(!checkForNumeric($opening_balance_cd))
		$opening_balance_cd=0;
		
		$purchase_head_id = getPurchaseHeadId();
		$sales_head_id = getSalesHeadId();
		
		if(TAX_CLASS==0)
		$tax_class_id="NULL";
		else if((!checkForNumeric($tax_class_id) || $tax_class_id<1) && ($head_id==$purchase_head_id || $head_id==$sales_head_id))
		return "error";
		
		if(!checkForNumeric($tax_class_id) || $tax_class_id<1)
		$tax_class_id="NULL";
		echo $tax_class_id;
	
		if(!validateForNull($pan_no))
		{
			$pan_no='0';
			}
		if(!validateForNull($sales_no))
		{
			$sales_no='0';
			}	
		if(!validateForNull($cst_no))
		{
			$cst_no='0';
			}
		if(!validateForNull($service_tax_no))
		{
			$service_tax_no='0';
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
			
		if($name!=null && $name!=''  && checkForNumeric($head_id) && strlen($pincode)==6 && !checkForDuplicateLedger($name))
			{
			
			
			$opening_date=getBooksStartingDateForCurrentCompanyOfUser();
			if(!validateForNull($opening_date))
			$opening_date=ACCOUNT_STARTING_DATE;
			
			$sql="INSERT INTO edms_ac_ledgers
					(ledger_name, head_id, postal_name,  address, city_id, area_id,pincode, pan_no, sales_no,cst_no, service_tax_no,opening_balance,opening_cd, opening_date, notes,  oc_id, our_company_id, created_by, last_updated_by, date_added, date_modified, ledger_type,tax_class_id)
					VALUES
					('$name',$head_id,'$postal_name','$address',$city_id,$area_id,$pincode,'$pan_no','$sales_no','$cst_no','$service_tax_no',$opening_balance, $opening_balance_cd, '$opening_date','$notes' , $oc_id, $our_company_id ,$admin_id,$admin_id,NOW(),NOW(), $type,$tax_class_id)";
			
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

function getLedgerIdsArrayForAutoCompleteName($ledger_name)
{
	if(validateForNull($ledger_name))
	{
		list($ledger_name,$ledger_id)=explode('|',$ledger_name);
		$brackets = array('[',']');
		$ledger_id = str_replace($brackets,'',$ledger_id);
		if(!checkForNumeric($ledger_id))
		return false;
		$sql="SELECT ledger_id FROM edms_ac_ledgers WHERE ledger_name = '$ledger_name'";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$ledger_id_array=array();
			
			foreach($resultArray as $ledger_id_row)
			{
				$ledger_id_array[]=$ledger_id_row[0];
			}
			return $ledger_id_array;
			
		}
		return false;
	}
	return false;
}

function checkforDuplicateLedger($ledger_name,$oc_id=NULL)
{
	if(validateForNull($ledger_name))
	{
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];	
	if(!(checkForNumeric($oc_id)))
		{		
			$current_company=getCurrentCompanyForUser($admin_id);
			if($current_company[1]==0) // if current company is our company
			{
			$oc_id=$current_company[0];	
		
			}
			else if($current_company[1]==2) // if combined agency
			{
				$agency_oc_ids=getAgencyOCForCombinedAgency($current_company[0]);
				if(checkForNumeric($agency_oc_ids[1][0]))
				{	
				$oc_id=$agency_oc_ids[1][0];
				}
			}	
		}
		
	 $sql="SELECT ledger_id FROM edms_ac_ledgers WHERE ledger_name='$ledger_name' AND oc_id = $oc_id ";
	
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
	
	if(checkForNumeric($ledger_id) && !checkifLedgerInUse($ledger_id))
	{
		$sql="DELETE FROM edms_ac_ledgers WHERE ledger_id=$ledger_id";
		dbQuery($sql);
		return "success";
		}
	}
	
function checkifLedgerInUse($ledger_id)
{
	if(checkForNumeric($ledger_id))
	{
		$sql="SELECT payment_id FROM edms_ac_payment WHERE from_ledger_id = $ledger_id OR to_ledger_id = $ledger_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		return true;
		
		$sql="SELECT receipt_id FROM edms_ac_receipt WHERE from_ledger_id = $ledger_id OR to_ledger_id = $ledger_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		return true;
		
		$sql="SELECT contra_id FROM edms_ac_contra WHERE from_ledger_id = $ledger_id OR to_ledger_id = $ledger_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		return true;
		
		$sql="SELECT jv_id FROM edms_ac_jv_cd WHERE from_ledger_id = $ledger_id OR to_ledger_id = $ledger_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		return true;
		
		$sql="SELECT purchase_id FROM edms_ac_purchase WHERE from_ledger_id = $ledger_id OR to_ledger_id = $ledger_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		return true;
		
		$sql="SELECT sales_id FROM edms_ac_sales WHERE from_ledger_id = $ledger_id OR to_ledger_id = $ledger_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		return true;
		
		$sql="SELECT tax_id FROM edms_tax WHERE tax_ledger_id = $ledger_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		return true;
		
		return false;
		
	}	
}		

function updateLedger($id,$name,$postal_name,$address,$city_id,$area,$pincode,$head_id,$contact_no,$pan_no,$sales_no,$notes,$opening_balance,$opening_balance_cd,$tax_class_id,$cst_no,$service_tax_no){
	
	try
	{
		
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		$address=clean_data($address);
		
		if(!checkForNumeric($opening_balance))
		$opening_balance=0;
		
		$purchase_head_id = getPurchaseHeadId();
		$sales_head_id = getSalesHeadId();
		
		if(TAX_CLASS==0 || ($head_id!=$purchase_head_id && $head_id!=$sales_head_id))
		$tax_class_id="NULL";
		else
		if(!checkForNumeric($tax_class_id) || $tax_class_id<1)
		return "error";
		
		if(!validateForNull($pan_no))
		{
			$pan_no='0';
			}
		if(!validateForNull($sales_no))
		{
			$sales_no='0';
			}	
		if(!validateForNull($cst_no))
		{
			$cst_no='0';
			}
		if(!validateForNull($service_tax_no))
		{
			$service_tax_no='0';
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
		
				
		if($name!=null && $name!=''  && checkForNumeric($id,$head_id))
			{
			
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			$sql="UPDATE edms_ac_ledgers
					SET ledger_name = '$name', head_id=$head_id, postal_name='$postal_name', address ='$address', city_id = $city_id, area_id=$area_id, pincode=$pincode, pan_no='$pan_no',sales_no='$sales_no',cst_no='$cst_no',service_tax_no='$service_tax_no',opening_balance=$opening_balance, opening_cd=$opening_balance_cd, notes='$notes' ,last_updated_by=$admin_id, date_modified=NOW(), tax_class_id = $tax_class_id
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
		$sql="
		SELECT ledger_id, ledger_name,address,head_id,postal_name, edms_city.city_id, area_id, pincode, pan_no, sales_no,opening_balance,opening_cd,opening_date,current_balance,current_balance_cd,notes,edms_ac_ledgers.date_added, edms_ac_ledgers.date_modified, edms_ac_ledgers.last_updated_by, city_name,oc_id, tax_class_id
		  FROM edms_ac_ledgers,edms_city
		  WHERE edms_ac_ledgers.city_id=edms_city.city_id
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

function getLedgerNameFromLedgerId($id)
{
try
	{
		$sql="SELECT  ledger_name
		  FROM edms_ac_ledgers
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
				if($no!="" && $no!=null && is_numeric($no))
				{
				insertContactNoLedger($ledger_id,$no); 
				}
			}
		}
		else
		{
			if($contact_no!="" && $contact_no!=null && is_numeric($contact_no))
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
		$sql="INSERT INTO edms_ac_ledgers_contact_no
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
		$sql="DELETE FROM edms_ac_ledgers_contact_no
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
		$sql="DELETE FROM edms_ac_ledgers_contact_no
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
	      FROM edms_ac_ledgers_contact_no
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
	      FROM edms_ac_ledgers_contact_no
		  WHERE edms_ac_ledgers_contact_no.ledger_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
	else
	return false;	
	}
	}	
	

function createCashLedgerForOC($oc_id)
{
	
	$cash_head_id=getCashHeadId();
	if(checkForNumeric($oc_id))
	{
		
		$result=insertLedger('Cash','','',null,null,'',$cash_head_id,'','','','',0,0,null,$oc_id);
		if($result=="success")
		return true;
		else
		return false;
		}
	return false;	
}

function createKasarLedgerForOC($oc_id)
{
	
	$cash_head_id=getDirectExpensesId();
	if(checkForNumeric($oc_id))
	{
		
		$result=insertLedger('Kasar','','',null,null,'',$cash_head_id,'','','','',0,0,null,$oc_id);
		if($result=="success")
		return true;
		else
		return false;
		}
	return false;	
}



function createAutoInterestLedgerForOC($oc_id)
{
	
	$unsecured_loans_head_id=getUnsecuredLoansId();
	if(checkForNumeric($oc_id))
	{
		
		$result=insertLedger('Auto Interest','','',null,null,'',$unsecured_loans_head_id,'','','','',0,0,null,$oc_id);
		if($result=="success")
		return true;
		else
		return false;
		}
	return false;	
}

function createOutSideJobLedgerForOC($oc_id)
{
	
	$unsecured_loans_head_id=getDirectExpensesId();
	if(checkForNumeric($oc_id))
	{
		
		$result=insertLedger('OutSide Job','','',null,null,'',$unsecured_loans_head_id,'','','','',0,0,null,$oc_id);
		if($result=="success")
		return true;
		else
		return false;
		}
	return false;	
}


function createIncomeLedgerForOC($oc_id)
{
	
	$unsecured_loans_head_id=getDirectIncomeId();
	if(checkForNumeric($oc_id))
	{
		
		$result=insertLedger(INCOME_LEDGER,'','',null,null,'',$unsecured_loans_head_id,'','','','',0,0,null,$oc_id);
		if(checkForNumeric($result))
		return $result;
		else
		return false;
		}
	return false;	
}

function getInomeLedgerForOC($oc_id)
{
	$duplicate = checkforDuplicateLedger(INCOME_LEDGER,$oc_id);

	if(!checkForNumeric($duplicate)) 
	$duplicate = createIncomeLedgerForOC($oc_id);

	return $duplicate;
}

function getLedgerHeadType($ledger_id) // returns 0 if bank or cash  2 = tax_head_id or 3 = purchase or 4 = sales else 1
{
	if(checkForNumeric($ledger_id))
	{
		$ledger=getLedgerById($ledger_id);
		
		$ledger_head_id=$ledger['head_id'];
		
		$bank_head_id=getBankAccountsHeadId();
		$cash_head_id=getCashHeadId();
		$purchase_head_id = getPurchaseHeadId();
		$sales_head_id = getSalesHeadId();
		$tax_head_id = getTaxHeadId();
		$od_bank_account_head_id = getODBankAccountsHeadId();
		
		if($ledger_head_id==$bank_head_id || $ledger_head_id==$cash_head_id || $ledger_head_id==$od_bank_account_head_id)
		return 0;
		else if($ledger_head_id==$tax_head_id)
		return 2;
		else if($ledger_head_id==$purchase_head_id)
		return 3;
		else if($ledger_head_id==$sales_head_id)
		return 4;
		else
		return 1;
		
		}
	}
function getCompanyForLedger($ledger_id)
{
	if(checkForNumeric($ledger_id))
	{
	
		$ledger=getLedgerById($ledger_id);
		$oc_id=$ledger['oc_id'];
		if(checkForNumeric($oc_id))
		return array($oc_id,0);
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
		      FROM edms_ac_ledgers
			  WHERE ledger_id IN (".$ids.")";
		$result=dbQuery($sql);
	    $result_array=dbResultToArray($result);
		}
		return $result_array[0]['DebitTotal']-$result_array[0]['CreditTotal'];
			
	
	}	
function getOpeningBalanceForCustomerArray($id_array) // returns array 1: opening balance, 2: balance type credit(1) or debit(0)
{
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(is_array($id_array) && checkForNumeric($id_array[0]))
	{
		$ids=implode(',',$id_array);
		$sql="SELECT SUM(CASE WHEN opening_cd = 1 THEN opening_balance ELSE 0 END) AS CreditTotal, SUM(CASE WHEN opening_cd = 0 THEN opening_balance ELSE 0 END) AS DebitTotal
		      FROM edms_customer_opening_balance
			  WHERE customer_id IN (".$ids.") AND oc_id = $oc_id";
	  
		$result=dbQuery($sql);
	    $result_array=dbResultToArray($result);
		}
		if(dbNumRows($result)>0)
		return $result_array[0]['DebitTotal']-$result_array[0]['CreditTotal'];
		else
		return 0;
			
	
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
		
	
		$company_id=$customer['oc_id'];
		
		
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
	
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
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
	
	$sql="SELECT ledger_id,ledger_name,head_id
	      FROM edms_ac_ledgers
		  WHERE head_id IN (".$head_id_String.") AND ";
		
if( is_numeric($oc_id))
{
	$sql=$sql." oc_id=$oc_id  ";
}  
if($oc_id=="NULL")
{
    if(validateForNull($oc_ids))
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
	
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
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
	
	$sql="SELECT ledger_id
	      FROM edms_ac_ledgers
		  WHERE head_id IN (".$head_id_String.")  AND ";
	
if( is_numeric($oc_id))
{
	$sql=$sql." oc_id=$oc_id  ";
}  
if($oc_id=="NULL")
{
	 if(validateForNull($oc_ids))
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
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
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
		
			  $sql="SELECT CONCAT('C',edms_customer.customer_id) as id, CONCAT(customer_name,IFNULL(CONCAT(' ',vehicle_reg_no),'')) as name, oc_id, our_company_id
			  FROM edms_customer
			  LEFT JOIN edms_file ON edms_customer.file_id=edms_file.file_id
			  LEFT JOIN edms_vehicle ON edms_vehicle.file_id=edms_customer.file_id
			  WHERE 
			  1=1 
			  ";
		
	if(is_numeric($oc_id) && defined('CUSTOMER_MULTI_COMPANY') && CUSTOMER_MULTI_COMPANY==0)
{
	$sql=$sql." AND oc_id=$oc_id  ";
}   
if($oc_id=="NULL" && defined('CUSTOMER_MULTI_COMPANY') && CUSTOMER_MULTI_COMPANY==0)
{
    if(validateForNull($oc_ids))
	$sql=$sql." AND oc_id IN ( ".$oc_ids.") ";
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
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
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
		
			  $sql="SELECT edms_customer.customer_id as id
			  FROM edms_customer
			  WHERE 
			 1=1
			  ";
			
	if(is_numeric($oc_id) && defined('CUSTOMER_MULTI_COMPANY') && CUSTOMER_MULTI_COMPANY==0)
{
	$sql=$sql." AND oc_id=$oc_id  ";
}   
if($oc_id=="NULL" && defined('CUSTOMER_MULTI_COMPANY') && CUSTOMER_MULTI_COMPANY==0)
{
    if(validateForNull($oc_ids))
	$sql=$sql." AND oc_id IN ( ".$oc_ids.") ";
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
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
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
		$sql="SELECT CONCAT('L',ledger_id) as id, ledger_name as name, oc_id, our_company_id
		  FROM edms_ac_ledgers
		  WHERE head_id IN (".$head_id_String.")  AND ";
		
if(is_numeric($oc_id))
{
	$sql=$sql." oc_id=$oc_id  ";
} 
if($oc_id=="NULL")
{
	if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
}  
if(in_array($debtors_head_id,$head_id) || in_array($current_assests_head_id,$head_id) || $head_id==$current_assests_head_id || $head_id==$debtors_head_id)
{
			  $sql=$sql." UNION ALL 
			  SELECT CONCAT('C',edms_customer.customer_id) as id, CONCAT(customer_name,IFNULL(CONCAT(' ',vehicle_reg_no),'')) as name,  oc_id, our_company_id
			  FROM edms_customer
			  LEFT JOIN edms_file ON edms_customer.file_id=edms_file.file_id
			  LEFT JOIN edms_vehicle ON edms_vehicle.file_id=edms_customer.file_id
			  WHERE 
			1=1  
			  ";
			
	if(is_numeric($oc_id) && defined('CUSTOMER_MULTI_COMPANY') && CUSTOMER_MULTI_COMPANY==0)
{
	$sql=$sql." AND  oc_id=$oc_id  ";
}   
if($oc_id=="NULL" && defined('CUSTOMER_MULTI_COMPANY') && CUSTOMER_MULTI_COMPANY==0)
{
    if(validateForNull($oc_ids))
	$sql=$sql." AND oc_id IN ( ".$oc_ids.") ";
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
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
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
		$sql="SELECT CONCAT('L',ledger_id) as id, ledger_name as name,  oc_id, our_company_id
		  FROM edms_ac_ledgers
		  WHERE head_id IN (".$head_id_String.")   AND ";
		
if(is_numeric($oc_id))
{
	$sql=$sql." oc_id=$oc_id  ";
} 
if($oc_id=="NULL")
{
	if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
}  
if($head_id==$debtors_head_id || (is_array($head_id) &&  in_array($debtors_head_id,$head_id)))
{
			  $sql=$sql." UNION ALL 
			  SELECT CONCAT('C',edms_customer.customer_id) as id, customer_name as name,  oc_id, our_company_id
			  FROM edms_customer
			  WHERE 
			  1=1 
			  ";
			
	if(is_numeric($oc_id) && defined('CUSTOMER_MULTI_COMPANY') && CUSTOMER_MULTI_COMPANY==0)
{
	$sql=$sql." AND oc_id=$oc_id  ";
}   
if($oc_id=="NULL" && defined('CUSTOMER_MULTI_COMPANY') && CUSTOMER_MULTI_COMPANY==0)
{
    if(validateForNull($oc_ids))
	$sql=$sql." AND oc_id IN ( ".$oc_ids.") ";
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


function listDirectLedgersWithBankCashForHeadId($head_id)
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
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
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
		$sql="SELECT ledger_id as id
		  FROM edms_ac_ledgers
		  WHERE head_id IN (".$head_id_String.") AND ";
		
if(is_numeric($oc_id))
{
	$sql=$sql." oc_id=$oc_id  ";
} 
if($oc_id=="NULL")
{
	if(validateForNull($oc_ids))
	$sql=$sql." oc_id IN ( ".$oc_ids.")";
}  
	
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$returnArray = array();
		foreach($resultArray as $re)
		$returnArray[] = $re[0];	
		return $returnArray;
		}
		else
		return false;
	}
	catch(Exception $e)
	{
	}
	
}


function listDirectCustomerForHeadId($head_id)
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
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
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
		
if($head_id==$debtors_head_id || (is_array($head_id) &&  in_array($debtors_head_id,$head_id)))
{
			  $sql=$sql."
			  SELECT edms_customer.customer_id as id
			  FROM edms_customer
			  WHERE 
			1=1 
			  ";
			
	if(is_numeric($oc_id) && defined('CUSTOMER_MULTI_COMPANY') && CUSTOMER_MULTI_COMPANY==0)
{
	$sql=$sql." AND oc_id=$oc_id  ";
}   
if($oc_id=="NULL" && defined('CUSTOMER_MULTI_COMPANY') && CUSTOMER_MULTI_COMPANY==0)
{
    if(validateForNull($oc_ids))
	$sql=$sql." AND oc_id IN ( ".$oc_ids.") ";
}   

	$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$returnArray = array();
		foreach($resultArray as $re)
		$returnArray[] = $re[0];
		
		return $returnArray;
		}
		else
		return false; 
}
	
		
		return false;
	}
	catch(Exception $e)
	{
	}
	
}


function getTaxLedgerForTaxID($tax_id)
{
	if(checkForNumeric($tax_id))
	{
		
		$tax = getTaxByID($tax_id);
		$tax_head_id = getTaxHeadId();
		if($tax['in_out']==0)
		$tax_name = "IN ".$tax['tax_name'];
		else if($tax['in_out']==1)
		$tax_name = "OUT ".$tax['tax_name'];
		else if($tax['in_out']==2)
		$tax_name = "IN PURCHASE ".$tax['tax_name'];
		
		if(checkForNumeric($tax_id,$tax_head_id) && validateForNull($tax_name))
		{
			$tax_ledger_id = $tax['tax_ledger_id'];
			
			if(checkForNumeric($tax_ledger_id))
			{
				return $tax_ledger_id;
			}	
			else
			{
				$ledger_id=insertLedger($tax_name,"","",-1,"","",$tax_head_id,"","","","",0,0);
				if(checkForNumeric($ledger_id))
				{
					$sql="UPDATE edms_tax SET tax_ledger_id = $ledger_id WHERE tax_id = $tax_id";
					dbQuery($sql);
				}
				return $ledger_id;
			}
		}
		return false;
		
		}
	
	}	
	
function checkifHeadisInPLSheet($head_id)
{
	if(checkForNumeric($head_id))
	{
		$PLSheet_head_array=array(20,21,22,23,38,39);
		if(in_array($head_id,$PLSheet_head_array))
		{
			return true;
			}
		else return false;	
		}
	return false;
}

function convertFullLedgerNameToLedgerIDArray($ledger_id_name_array)
{
	$ledger_id_array = array();
	foreach($ledger_id_name_array as $ledger_id_name)
	{
		$ledger_id=getLedgerIdsArrayForLedgerNameId($ledger_id_name);
	    $ledger_id = clean_data($ledger_id);
		$ledger_id_array[] = $ledger_id;
	}
	return $ledger_id_array;
	
}	
		
function getLedgerIdsArrayForLedgerNameId($ledger_name)
{
	if(validateForNull($ledger_name))
	{
		list($ledger_name,$ledger_id)=explode('|',$ledger_name);
		$brackets = array('[',']');
		$ledger_id = str_replace($brackets,'',$ledger_id);
	    return $ledger_id;
	}
	return false;
}	

					
?>