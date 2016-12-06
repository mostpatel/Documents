<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("account-ledger-functions.php");
require_once("common.php");
require_once("bd.php");

function insertBranchCounter($branch_id,$branch_code) // name,pincode,city_id,prefix validations
{
	try
	{
		
		$branch_code = strtoupper($branch_code);
		$cond=checkForNumeric($branch_id);
		if($cond==true && validateForNull($branch_code)  && strlen($branch_code)<4 && !checkForDuplicateBranchCounter($branch_id,$branch_code))
		{
			
			$company_prefix=strtoupper($company_prefix);
			$sql="INSERT INTO 
			      edms_branch_counters(branch_id, branch_code)
				  VALUES
				  ($branch_id, '$branch_code')";
			$result=dbQuery($sql);	  
			return "success";
		}
		else
		{
			return "error";
			
		}
		return "error";
		
	}
	catch(Exception $e)
	{}
	
}

	
function deleteBranchCounter($id)
{
	try{
		
		
		if(checkForNumeric($id))
		{
		$sql="DELETE FROM
		      edms_branch_counters 
			  WHERE branch_id=$id";
		dbQuery($sql);	  
		return "success";
		}
		else if(count($ourCompanies)==1)
		{
			return "error1";
			}
		else
		{
			return "error";
			}
		}
	catch(Exception $e)
	{}
	
	}
	
function getBranchCountersByID($id)
{
	$sql="SELECT branch_id, branch_code, lr_counter, trip_memo_counter, invoice_counter, page_counter, summary_counter
	      FROM edms_branch_counters
		  WHERE branch_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		return $resultArray[0];
		}	  
	else
	{
		return false;
		}	
	}
	

function checkForDuplicateBranchCounter($branch_id,$branch_code) // duplicate if name,address,pincode,city are all same or company prefix is same
{
	try{
		
		$sql="SELECT branch_id
		       FROM edms_branch_counters
			   WHERE 
			   branch_id=$branch_id
			   OR branch_code='$branch_code'
			   ";	   
		$result=dbQuery($sql);	
		if(dbNumRows($result)>0)
		{
			$_SESSION['error']['submit_error']="Duplicate Entry!";
			return true;
			}   
		else
		{
			
			return false;
			}	
		}
	catch(Exception $e)
	{}
	
	}	


function getPrefixFromBranchId($id)
{
	$sql="SELECT branch_code FROM
		   edms_branch_counters
		   WHERE branch_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];	   
	
	}	
function getLRNoForBranchID($id)
{
	$sql="SELECT branch_code,lr_counter FROM
		   edms_branch_counters
		   WHERE branch_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0].$resultArray[0][1];	
}

function getInvoiceNoForBranchID($id)
{
	$sql="SELECT branch_code,invoice_counter FROM
		   edms_branch_counters
		   WHERE branch_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0].$resultArray[0][1];	
}

function getSummaryNoForBranchID($id)
{
	$sql="SELECT branch_code,summary_counter FROM
		   edms_branch_counters
		   WHERE branch_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0].$resultArray[0][1];	
}

function getLRCounterForBranchID($id)
{
	$sql="SELECT branch_code,lr_counter FROM
		   edms_branch_counters
		   WHERE branch_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];	
}

function getBranchCodeForBranchID($id)
{
	$sql="SELECT branch_code,lr_counter FROM
		   edms_branch_counters
		   WHERE branch_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];	
}

function incrementLRNoForBranchID($id)
{
	$r=getLRCounterForBranchID($id);
	$r++;
	$sql="UPDATE edms_branch_counters
	      SET lr_counter=$r
		  WHERE branch_id=$id";
	dbQuery($sql);	  
	
	}
	
function getPageNoForBranchID($id)
{
	$sql="SELECT branch_code,page_counter FROM
		   edms_branch_counters
		   WHERE branch_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0].$resultArray[0][1];	
}

function getPageCounterForBranchID($id)
{
	$sql="SELECT branch_code,page_counter FROM
		   edms_branch_counters
		   WHERE branch_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];	
}

function incrementPageNoForBranchID($id)
{
	$r=getPageCounterForBranchID($id);
	$r++;
	$sql="UPDATE edms_branch_counters
	      SET page_counter=$r
		  WHERE branch_id=$id";
	dbQuery($sql);	  
	
	}	
	
	
function getTripMemoCounterForBranchID($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT trip_memo_counter FROM
		   edms_branch_counters
		   WHERE branch_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{	
	return $resultArray[0][0];	
	}
	}
}

function incrementTripMemoCounterForBranchID($id)
{
	if(checkForNumeric($id))
	{
	$r=getTripMemoCounterForBranchID($id);
	$r++;
	$sql="UPDATE edms_branch_counters
	      SET trip_memo_counter=$r
		  WHERE branch_id=$id";
	dbQuery($sql);	  
	}
}			

function getInvoiceCounterForBranchID($id)
{
	$sql="SELECT branch_code,invoice_counter FROM
		   edms_branch_counters
		   WHERE branch_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];	
}

function incrementInvoiceNoForBranchID($id)
{
	$r=getInvoiceCounterForBranchID($id);
	$r++;
	$sql="UPDATE edms_branch_counters
	      SET invoice_counter=$r
		  WHERE branch_id=$id";
	dbQuery($sql);	  
	
	}		

function getSummaryCounterForBranchID($id)
{
	$sql="SELECT branch_code,summary_counter FROM
		   edms_branch_counters
		   WHERE branch_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];	
}

function incrementSummaryNoForBranchID($id)
{
	$r=getSummaryCounterForBranchID($id);
	$r++;
	$sql="UPDATE edms_branch_counters
	      SET summary_counter=$r
		  WHERE branch_id=$id";
	dbQuery($sql);	  
	
	}		

function resetPageCounterForOC($oc_id)
{
	$sql="UPDATE edms_branch_counters SET  page_counter=1 WHERE branch_id=$oc_id";
		dbQuery($sql);
		return "success";
}			
?>