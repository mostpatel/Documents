<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("account-ledger-functions.php");
require_once("our-company-function.php");
require_once("agency-functions.php");
require_once("account-ledger-functions.php");
require_once("account-head-functions.php");
require_once("common.php");
require_once("bd.php");

function listBrokers(){
	
	try
	{
		$sql="SELECT broker_id, broker_name, broker_address, broker_contact_no
		  FROM fin_broker ORDER BY broker_name";
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

function insertBroker($broker_name,$address='',$contact_no=''){
	
	try
	{
		$broker_name=clean_data($broker_name);
		$broker_name = ucwords(strtolower($broker_name));
		$address=clean_data($address);
		if($address==null || $address=="")
		{
			$address="NA";
			}
		if($contact_no==null || $contact_no=="")
		{
			$contact_no="NA";
			}	
		if(validateForNull($broker_name) && !checkForDuplicateBroker($broker_name))
			{
			$admin_id=$_SESSION['adminSession']['admin_id'];
			$sql="INSERT INTO fin_broker
					(broker_name, broker_address,broker_contact_no ,created_by, last_updated_by, date_added, date_modified)
					VALUES
					('$broker_name','$address','$contact_no',$admin_id,$admin_id,NOW(),NOW())";
					
			dbQuery($sql);
			$dealer_id=dbInsertId();
			
			if(BROKER_LEDGER_PALI==1)
		{
			$our_companies = listAllOurCompanies();
			
			foreach($our_companies as $our_company)
			{
				$ledger_id = insertLedger($broker_name,'','',-1,'',NULL,getBankAccountsHeadId(),'','','','',0,0,NULL,$our_company['our_company_id'],0,0,2);
				
				if(!checkForNumeric($ledger_id))
				{
					deleteBroker($dealer_id);
					return "error";
					
				}
			}
			
			$agencues = listAllAgencies();
			
			foreach($agencues as $agency)
			{
				$ledger_id = insertLedger($broker_name,'','',-1,'',NULL,getBankAccountsHeadId(),9999999999,'','','',0,0,$agency['agency_id'],NULL,0,0,2);
				if(!checkForNumeric($ledger_id))
				{
					deleteBroker($dealer_id);
					return "error";
					
				}
			}
			
			
			
		}
			
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

function insertBrokerIfNotDuplicate($broker_name,$address='',$contact_no=''){
	
	try
	{
		$broker_name=clean_data($broker_name);
		$broker_name = ucwords(strtolower($broker_name));
		$address=clean_data($address);
		if($address==null || $address=="")
		{
			$address="NA";
			}
		if($contact_no==null || $contact_no=="")
		{
			$contact_no="NA";
			}	
		$duplicate = checkForDuplicateBroker($broker_name);
		if(checkForNumeric($duplicate))
		return $duplicate;	
		if(validateForNull($broker_name))
			{
			$admin_id=$_SESSION['adminSession']['admin_id'];
			$sql="INSERT INTO fin_broker
					(broker_name, broker_address,broker_contact_no ,created_by, last_updated_by, date_added, date_modified)
					VALUES
					('$broker_name','$address','$contact_no',$admin_id,$admin_id,NOW(),NOW())";
					
			dbQuery($sql);
			$dealer_id=dbInsertId();
			if(BROKER_LEDGER_PALI==1)
		{
			$our_companies = listAllOurCompanies();
			
			foreach($our_companies as $our_company)
			{
				$ledger_id = insertLedger($broker_name,'','',-1,'',NULL,getBankAccountsHeadId(),'','','','',0,0,NULL,$our_company['our_company_id'],0,0,2);
				
				if(!checkForNumeric($ledger_id))
				{
					deleteBroker($dealer_id);
					return "error";
					
				}
			}
			
			$agencues = listAllAgencies();
			
			foreach($agencues as $agency)
			{
				$ledger_id = insertLedger($broker_name,'','',-1,'',NULL,getBankAccountsHeadId(),9999999999,'','','',0,0,$agency['agency_id'],NULL,0,0,2);
				if(!checkForNumeric($ledger_id))
				{
					deleteBroker($dealer_id);
					return "error";
					
				}
			}
			
			
			
		}
			return $dealer_id;
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



function checkIfBrokerIsInUse($broker_id)
{
	
	$sql="SELECT broker_id FROM fin_file WHERE broker_id=$broker_id";
	$result=dbQuery($sql);
	
	if(dbNumRows($result)>0)
	{	
	return true;
	}
	else
	{
		
	if(BROKER_LEDGER_PALI==1)
		{	
		$broker_name=getBrokerNameFromBrokerId($broker_id);
		$broker_ledger_ids=getAllBrokerLedgerId($broker_name);
		
		foreach($broker_ledger_ids as $ledger)
		{
			if(checkIfLedgerInUse($ledger[0]))
			return true;
		}	
		}	
	return false;
	}
	
	}	

function deleteBroker($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfBrokerIsInUse($id))
		{
		if(BROKER_LEDGER_PALI==1)
		{	
		$broker_name=getBrokerNameFromBrokerId($id);
		$broker_ledger_ids=getAllBrokerLedgerId($broker_name);
		
		foreach($broker_ledger_ids as $ledger)
		{
			deleteLedger($ledger[0]);
		}	
		}
			
		$sql="DELETE FROM fin_broker WHERE broker_id=$id";
		dbQuery($sql);
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

function updateBroker($id,$broker_name,$address,$contact_no){
	
	try
	{
		if(BROKER_LEDGER_PALI==1)
		{
			return "error";
		}
		$broker_name=clean_data($broker_name);
		$broker_name = ucwords(strtolower($broker_name));
		$address=clean_data($address);
		if($address==null || $address=="")
		{
			$address="NA";
			}
		if($contact_no==null || $contact_no=="")
		{
			$contact_no="NA";
			}	
		if(validateForNull($broker_name)  && checkForNumeric($id))
			{
			
			$admin_id=$_SESSION['adminSession']['admin_id'];
			$sql="UPDATE fin_broker
					SET broker_name = '$broker_name', broker_address ='$address', broker_contact_no='$contact_no', last_updated_by=$admin_id, date_modified=NOW()
					WHERE broker_id=$id";
			
			dbQuery($sql);
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

function getBrokerById($id){
	
	try
	{
		$sql="SELECT broker_id, broker_name, broker_address, broker_contact_no
		  FROM fin_broker
		  WHERE broker_id=$id";
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

function getBrokerNameFromBrokerId($id)
{
try
	{
		$sql="SELECT  broker_name
		  FROM fin_broker
		  WHERE broker_id=$id";
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

function checkForDuplicateBroker($broker_name)
{
	if(validateForNull($broker_name))
	{
	$sql="SELECT  broker_id
		  FROM fin_broker
		  WHERE broker_name='$broker_name'";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0]; 
		else if(BROKER_LEDGER_PALI==1)
		{
			$our_companies = listAllOurCompanies();
			
			foreach($our_companies as $our_company)
			{
				$ledger_id = checkforDuplicateLedger($broker_name,$our_company['our_company_id']);
				if(checkForNumeric($ledger_id))
				return $ledger_id;
			}
			
			$agencues = listAllAgencies();
			
			foreach($agencues as $agency)
			{
				$ledger_id = checkforDuplicateLedger($broker_name,NULL,$agency['agency_id']);
				if(checkForNumeric($ledger_id))
				return $ledger_id;
			}
			
			return false;
			
		}
		else
		return false;
	}

	return true;
}

function getDirectBrokerId()
{
	
	$sql="SELECT  broker_id
		  FROM fin_broker
		  WHERE broker_name='Direct'";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0]; 
		else
		return false;
	
	return true;
	
}

function mergeBrokers($broker_array,$name)
{
	$new_broker_id=insertBrokerIfNotDuplicate($name,'','');
	foreach($broker_array as $broker_id)
	{
		$sql="UPDATE fin_file SET broker_id = $new_broker_id WHERE broker_id = $broker_id";
		dbQuery($sql);
		deleteBroker($broker_id);
	}
}

function getBrokerLedgerId($broker_name,$oc_id,$agency_id)
{
	if(validateForNull($broker_name) && (checkForNumeric($oc_id) || checkForNumeric($agency_id)))
	{
		$sql="SELECT ledger_id FROM fin_ac_ledgers WHERE ledger_name = '$broker_name' AND";
		if(checkForNumeric($oc_id))
		$sql=$sql." oc_id = $oc_id";
		else if(checkForNumeric($agency_id))
		$sql=$sql." agency_id = $agency_id";
		
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
	}
	return false;
	
}

function getAllBrokerLedgerIdString($broker_name)
{
	if(validateForNull($broker_name))
	{
		$sql="SELECT GROUP_CONCAT(ledger_id) FROM fin_ac_ledgers WHERE ledger_name = '$broker_name' GROUP BY ledger_name ";
		if(checkForNumeric($oc_id))
		$sql=$sql." oc_id = $oc_id";
		else if(checkForNumeric($agency_id))
		$sql=$sql." agency_id = $agency_id";
		
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
	}
	return false;
	
}

function getAllBrokerLedgerId($broker_name)
{
	if(validateForNull($broker_name))
	{
		$sql="SELECT ledger_id FROM fin_ac_ledgers WHERE ledger_name = '$broker_name'  ";
		if(checkForNumeric($oc_id))
		$sql=$sql." oc_id = $oc_id";
		else if(checkForNumeric($agency_id))
		$sql=$sql." agency_id = $agency_id";
		
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
	}
	return false;
	
}
?>