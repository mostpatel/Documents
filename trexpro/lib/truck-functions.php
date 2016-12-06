<?php 
require_once("cg.php");
require_once("common.php");
require_once("account-ledger-functions.php");
require_once("bd.php");
		
function listTrucks(){
	
	try
	{
		$sql="SELECT truck_id, truck_name, truck_no,remarks, owner_ledger_id, created_by, last_updated_by, date_added, date_modified
		      FROM edms_trucks
			  ORDER BY truck_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfTrucks()
{
	$sql="SELECT count(truck_id)
		      FROM edms_trucks
			  ORDER BY truck_name";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertTruckIfNotDuplicate($name,$truck_no,$remarks,$owner_ledger_id=NULL,$bd2=false){
	
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		$truck_no = clean_data($truck_no);
		$remarks = clean_data($remarks);
		$owner_ledger_id = clean_data($owner_ledger_id);
	//	$truck_no =stripVehicleno($truck_no);
		$duplicate = checkForDuplicateTruck($truck_no,false,$bd2);
		if(validateForNull($name,$truck_no) && !$duplicate && (checkForNumeric($owner_ledger_id) || !validateForNull($owner_ledger_id)))
		{
		if($owner_ledger_id==-1 || !validateForNull($owner_ledger_id))
		$owner_ledger_id="NULL";
		
		if(!$bd2)
		{
		$our_company_id = $_SESSION['edmsAdminSession']['oc_id'];
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		}
		else
		{
			$our_company_id = DEFAULT_OC_ID;
			$admin_id = DEFAULT_ADMIN_ID;
		}
		
		$sql="INSERT INTO edms_trucks
		      (truck_name, truck_no,remarks, owner_ledger_id, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$name', '$truck_no', '$remarks', $owner_ledger_id, $admin_id, $admin_id, NOW(), NOW())";
		dbQuery($sql,$bd2);	  
		return dbInsertId($bd2);
		}
		else if($duplicate)
		return $duplicate;
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}	

function deleteTruck($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfTruckInUse($id))
		{
		$sql="DELETE FROM edms_trucks
		      WHERE truck_id=$id";
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

function updateTruck($id,$name,$truck_no,$remarks,$owner_ledger_id){
	
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		$truck_no = clean_data($truck_no);
		$remarks = clean_data($remarks);
		$owner_ledger_id = clean_data($owner_ledger_id);
		$truck_no =stripVehicleno($truck_no);
		if(checkForNumeric($id,$owner_ledger_id) && validateForNull($name,$truck_no) && !checkForDuplicateTruck($truck_no,$id))
		{
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="UPDATE edms_trucks
		      SET truck_name = '$name', truck_no = '$truck_no',remarks = '$remarks', owner_ledger_id = $owner_ledger_id,last_updated_by=$admin_id, date_modified = NOW()
			  WHERE truck_id=$id";
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

function getTruckById($id,$bd2=false){
	
	try
	{
		$sql="SELECT truck_id, truck_name, truck_no,remarks, owner_ledger_id,created_by,last_updated_by, date_added, date_modified 
		      FROM edms_trucks
			  WHERE truck_id=$id";
		$result=dbQuery($sql,$bd2);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	
function getTruckNoById($id){
	
	try
	{
		$sql="SELECT truck_id, truck_no
		      FROM edms_trucks
			  WHERE truck_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateTruck($truck_no,$id=false,$bd2=false)
{
	    if(validateForNull($truck_no))
		{
		$sql="SELECT truck_id
		      FROM edms_trucks
			  WHERE truck_no='$truck_no'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND truck_id!=$id";		  	  
		$result=dbQuery($sql,$bd2);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
		}
	}	
function checkIfTruckInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT truck_id FROM
			edms_trip_memo
			WHERE truck_id=$id	";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
	}	
	
function stripVehicleno($reg_no)
{
	$string=$reg_no;
preg_match('#[0-9]+$#', $string, $match);
$end_number=$match[0]; // Output: 8271
$pos = strrpos($string, $end_number);

    if($pos !== false)
    {
        $start_string = substr_replace($string, "", $pos, strlen($end_number));
    }


$new_number=$str = ltrim($end_number, '0');
$new_reg_no=$start_string.$new_number;
return $new_reg_no;
}		
?>