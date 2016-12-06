<?php 
    require_once("cg.php");
    require_once("bd.php");
    require_once("common.php");

function checkForDuplicateCustomerToBroker($customer_id,$ledger_id)
{
	
	$sql="SELECT ledger_id FROM edms_rel_brokers_customer WHERE customer_id = $customer_id AND ledger_id = $ledger_id";
	$result=dbQuery($sql);	
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		return true;
		}
	else
	{
		return false;
		}
}


function AddCustomerToBrokers($customer_id,$broker_array)
{
	
 if(checkForNumeric($customer_id,$broker_array[0]))
 {
	 deleteRelBrokerGroupByCustomerID($customer_id);
	 
	foreach($broker_array as $ledger_id)
	{
		
		if(checkForNumeric($ledger_id) && !checkForDuplicateCustomerToBroker($customer_id,$ledger_id))
		{
			$sql="INSERT INTO edms_rel_brokers_customer(ledger_id,customer_id) VALUES ($ledger_id,$customer_id)";
			
			dbQuery($sql);
		}
	} 
	return "success";
 }	
}		

function DeleteBrokersForCustomer($customer_id)
{
	if(checkForNumeric($customer_id))
	{
		$sql="DELETE FROM edms_rel_brokers_customer WHERE customer_id = $customer_id";
		dbQuery($sql);
	}
}


function insertCustomersToBroker($files_array,$ledger_id)
{
	if(count($files_array)>0 && is_numeric($ledger_id))
	{
	foreach($files_array as $customer_id)
	{	
    
	$sql="INSERT INTO edms_rel_brokers_customer (ledger_id,customer_id)
	      VALUES($ledger_id,$customer_id)";
	dbQuery($sql);
	  
	}
	return "success";	
	
	}
	return "error";
	}

function getBrokersForCustomerId($customer_id)
{
	$sql="SELECT edms_rel_brokers_customer.ledger_id,ledger_name FROM edms_rel_brokers_customer,edms_ac_ledgers WHERE edms_ac_ledgers.ledger_id = edms_rel_brokers_customer.ledger_id AND customer_id = $customer_id";
	$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		return $resultArray;
		}
	else
	{
		return false;
		}
}


function getBrokerStringForCustomerId($customer_id)
{
	$sql="SELECT GROUP_CONCAT(ledger_id) FROM edms_rel_brokers_customer WHERE customer_id = $customer_id GROUP BY customer_id";
	$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		return $resultArray;
		}
	else
	{
		return false;
		}
}
 

function deleteRelBrokerGroupByGroupID($ledger_id)
{
	if(checkForNumeric($ledger_id))
	{
		$sql="DELETE FROM edms_rel_brokers_customer WHERE ledger_id=$ledger_id";
		dbQuery($sql);
		return "success";
		}
	return "error";	
	
	}

function deleteRelBrokerGroupByCustomerID($ledger_id)
{
	if(checkForNumeric($ledger_id))
	{
		$sql="DELETE FROM edms_rel_brokers_customer WHERE customer_id=$ledger_id";
		dbQuery($sql);
		return "success";
		}
	return "error";	
	
	}
	
?>