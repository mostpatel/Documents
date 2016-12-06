<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");

function listAllInvoiceTypes($oc_id)
{
	if(!checkForNumeric($oc_id))
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	$sql="SELECT invoice_type_id,invoice_type,invoice_type_print_name,invoice_prefix,oc_id,invoice_counter,type FROM edms_invoice_types WHERE oc_id = $oc_id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
}

function insertInvoiceType($invoice_type,$invoice_type_print_name,$invoice_counter,$invoice_prefix,$type)
{
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	if(checkForNumeric($invoice_counter,$oc_id,$type) && validateForNull($invoice_type,$invoice_prefix))
	{
		$invoice_type= clean_data($invoice_type);
		$invoice_type_print_name = clean_data($invoice_type_print_name);
		$invoice_counter = clean_data($invoice_counter);
		$invoice_prefix = clean_data($invoice_prefix);
		$oc_id = clean_data($oc_id);
		
		if(!validateForNull($invoice_type_print_name))
		$invoice_type_print_name = $invoice_type;
		
		$sql="INSERT INTO edms_invoice_types (invoice_type,invoice_type_print_name,invoice_prefix,oc_id,invoice_counter,type) VALUES ('$invoice_type','$invoice_type_print_name','$invoice_prefix',$oc_id,$invoice_counter,$type)";
		$result = dbQuery($sql);
		
		return dbInsertId();
	}
	else
	return "error";
	
}


function updateInvoiceType($invoice_type_id,$invoice_type,$invoice_type_print_name,$invoice_counter)
{
	
	if(checkForNumeric($invoice_counter,$invoice_type_id) && validateForNull($invoice_type))
	{
		$invoice_type= clean_data($invoice_type);
		$invoice_type_print_name = clean_data($invoice_type_print_name);
		$invoice_counter = clean_data($invoice_counter);
		
		if(!validateForNull($invoice_type_print_name))
		$invoice_type_print_name = $invoice_type;
		
		$sql="UPDATE edms_invoice_types SET invoice_type = '$invoice_type'	,invoice_type_print_name = '$invoice_type_print_name',invoice_counter = $invoice_counter WHERE invoice_type_id = $invoice_type_id";
		$result = dbQuery($sql);
		
		return "success";
	}
	else
	return "error";
	
}

function deleteInvoiceType($type_id)
{
	if(!checkIfInvoiceTypeInUse($type_id) && checkForNumeric($type_id))
	{
		$sql="DELETE FROM edms_invoice_types WHERE invoice_type_id = $type_id";
		dbQuery($sql);
		return "success";
	}
	return "error";
	
}

function checkIfInvoiceTypeInUse($type_id)
{
	if(checkForNumeric($type_id))
	{
		$sql="SELECT sales_id FROM edms_ac_sales WHERE retail_tax=$type_id";
		$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
	else 
	return false;	
	}
}



function getInvoiceTypeById($id)
{
	$sql="SELECT invoice_type_id,invoice_type,invoice_prefix,oc_id,invoice_counter,invoice_type_print_name,type FROM edms_invoice_types WHERE invoice_type_id = $id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	return $resultArray[0];
}

function getRetailInvoiceTypeForOcId($oc_id)
{
		$sql="SELECT invoice_type_id,invoice_type,invoice_prefix,oc_id,invoice_counter,invoice_type_print_name,type FROM edms_invoice_types WHERE type=0 AND oc_id = $oc_id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0];
}

function getTaxInvoiceTypeForOcId($oc_id)
{
		$sql="SELECT invoice_type_id,invoice_type,invoice_prefix,oc_id,invoice_counter,invoice_type_print_name,type FROM edms_invoice_types WHERE type=1 AND oc_id = $oc_id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0];
}


function getInvoiceNoForOCID($type_id,$oc_id)
{
	$sql="SELECT invoice_prefix,invoice_counter FROM
		   edms_invoice_types
		  WHERE oc_id=$oc_id AND invoice_type_id = $type_id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	return $resultArray[0][0].$resultArray[0][1];	
}

function getInvoiceCounterForOCID($type_id,$oc_id)
{
	$sql="SELECT invoice_counter FROM
		   edms_invoice_types
		   WHERE oc_id=$oc_id AND invoice_type_id = $type_id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];	
}

function incrementInvoiceNoForOCID($type_id,$oc_id)
{
	$r=getInvoiceCounterForOCID($type_id,$oc_id);
	$r++;
	$sql="UPDATE edms_invoice_types
	      SET invoice_counter=$r
		  WHERE oc_id=$oc_id AND invoice_type_id = $type_id";
	dbQuery($sql);	  
}	
	

function resetInvoiceCountersOC($oc_id)
{
		$sql="UPDATE edms_invoice_types SET  invoice_counter=1 WHERE oc_id = $oc_id";
		dbQuery($sql);
		$sql="UPDATE edms_our_company SET rasid_reset_date=NOW() WHERE our_company_id = $oc_id";
		dbQuery($sql);
		return "success";
}		


		
?>