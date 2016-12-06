<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");

function listGodowns(){
	
	try
	{
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
		$sql="SELECT godown_id, godown_name, godown_address
		  FROM edms_godown
		  WHERE our_company_id = $our_company_id
		  ORDER BY godown_id";
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

function insertGodown($godown_name,$godown_address,$city_id,$contact_no){
	
	try
	{
		$godown_name=clean_data($godown_name);
		$godown_name = ucwords(strtolower($godown_name));
		$godown_address=clean_data($godown_address);
		$duplcate=checkForDuplicateGodown($godown_name);
		if(!checkForNumeric($city_id))
		$city_id="NULL";
		if($godown_name!=null && $godown_name!=''   && !checkForDuplicateGodown($godown_name))
			{
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
			$sql="INSERT INTO edms_godown
					(godown_name, godown_address, city_id, our_company_id, created_by, last_updated_by, date_added, date_modified)
					VALUES
					('$godown_name','$godown_address',$city_id, $our_company_id, $admin_id,$admin_id,NOW(),NOW())";
			dbQuery($sql);
			$godown_id=dbInsertId();
			
			addGodownContactNo($godown_id,$contact_no);
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

function insertGodownIfNotDuplicate($godown_name,$godown_address,$city_id,$contact_no){
	
	try
	{
		$godown_name=clean_data($godown_name);
		$godown_name = ucwords(strtolower($godown_name));
		$godown_address=clean_data($godown_address);
		$duplcate=checkForDuplicateGodown($godown_name);
		if(!checkForNumeric($city_id))
		$city_id="NULL";
		if($godown_name!=null && $godown_name!=''   && !checkForDuplicateGodown($godown_name))
			{
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
			$sql="INSERT INTO edms_godown
					(godown_name, godown_address, city_id, our_company_id, created_by, last_updated_by, date_added, date_modified)
					VALUES
					('$godown_name','$godown_address',$city_id, $our_company_id, $admin_id,$admin_id,NOW(),NOW())";
			dbQuery($sql);
			$godown_id=dbInsertId();
			
			addGodownContactNo($godown_id,$contact_no);
			return $godown_id;
			}	
		else if(checkForNumeric($duplcate))
		return $duplcate;	
		else 
		{
			return "error";
			}	
	}
	catch(Exception $e)
	{
	}
	
}

function deleteGodown($godown_id)
{
	
	if(checkForNumeric($godown_id) && !checkIfGodownInUse($godown_id) )
	{
		$sql="DELETE FROM edms_godown WHERE godown_id=$godown_id";
		dbQuery($sql);
		return "success";
		}
	return "error";	
	}	



function updateGodown($id,$godown_name,$godown_address,$city_id,$contact_no){
	
	try
	{
		$godown_name=clean_data($godown_name);
		$godown_name = ucwords(strtolower($godown_name));
		$godown_address=clean_data($godown_address);
		if(!checkForNumeric($city_id))
		$city_id="NULL";
		if($godown_name!=NULL && $godown_name!=''  && checkForNumeric($id) && !checkForDuplicateGodown($godown_name,$id))
			{
			
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			$sql="UPDATE edms_godown
					SET godown_name = '$godown_name', godown_address ='$godown_address', city_id = $city_id, last_updated_by=$admin_id, date_modified=NOW()
					WHERE godown_id=$id";
					
			dbQuery($sql);
			
			deleteAllContactNoGodown($id);	
			addGodownContactNo($id,$contact_no);
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

function getGodownById($id){
	
	try
	{
		$sql="SELECT godown_id, godown_name, godown_address, our_company_id
		  FROM edms_godown
		  WHERE
		   godown_id=$id";
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

function getGodownNameFromGodownId($id)
{
try
	{
		$sql="SELECT  godown_name
		  FROM edms_godown
		  WHERE godown_id=$id";
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
	
function checkForDuplicateGodown($name,$id=false)
{
	$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
	$sql="SELECT godown_id
		  FROM edms_godown
		  WHERE our_company_id = $our_company_id 
		  AND godown_name='$name'
		 ";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND godown_id!=$id";		  
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0]; 
		else
		return false;
	
}

function addGodownContactNo($godown_id,$contact_no)
{
	try
	{
		if(is_array($contact_no))
		{
			foreach($contact_no as $no)
			{
				if($no!="" && $no!=null && is_numeric($no))
				{
				insertContactNoGodown($godown_id,$no); 
				}
			}
		}
		else
		{
			if($contact_no!="" && $contact_no!=null && is_numeric($contact_no))
				{
				insertContactNoGodown($godown_id,$contact_no); 
				}
			
		}
	}
	catch(Exception $e)
	{
	}
}

function insertContactNoGodown($id,$contact_no)
{
	try
	{
		
		if(checkForNumeric($id,$contact_no)==true && !checkForDuplicateContactNoGodown($id,$contact_no))
		{
		$sql="INSERT INTO edms_godown_contact_no
				      (godown_contact_no, godown_id)
					  VALUES
					  ('$contact_no', $id)";
				dbQuery($sql);	  
		}
	}
	catch(Exception $e)
	{}
	
	
}
function deleteContactNoGodown($id)
{
	try
	{
		$sql="DELETE FROM edms_godown_contact_no
			  WHERE godown_contact_no_id=$id";
		dbQuery($sql);	  
	}
	catch(Exception $e)
	{}
	
	
	
	}
function deleteAllContactNoGodown($id)
{
	try
	{
		$sql="DELETE FROM edms_godown_contact_no
			  WHERE godown_id=$id";
		dbQuery($sql);
	}
	catch(Exception $e)
	{}
	
	
	
	}	
function updateContactNoGodown($id,$contact_no)
{
	try
	{
		deleteAllContactNoGodown($id);
		addGodownContactNo($id,$contact_no);
	}
	catch(Exception $e)
	{}
	
	
	
	}	

function checkForDuplicateContactNoGodown($id,$contact_no)
{
	if(checkForNumeric($id,$contact_no))
	{
	$sql="SELECT godown_contact_no_id
	      FROM edms_godown_contact_no
		  WHERE godown_contact_no='$contact_no'
		  AND godown_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
	else
	return false;	
	}
	}	


	
function getGodownNumbersByGodownId($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT godown_contact_no
	      FROM edms_godown_contact_no
		  WHERE edms_godown_contact_no.godown_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return false;	
	}
	}	

function checkIfGodownInUse($id)
{
	$sql="SELECT vehicle_id FROM edms_vehicle WHERE godown_id=$id
	UNION 
	SELECT opening_godown_id FROM edms_inventory_item WHERE opening_godown_id = $id
	UNION 
	SELECT  godown_id FROM edms_ac_sales_item WHERE godown_id = $id
	UNION 
	SELECT  godown_id FROM edms_ac_purchase_item WHERE godown_id = $id
	UNION 
	SELECT  godown_id FROM edms_ac_debit_note_item WHERE godown_id = $id
	UNION 
	SELECT  godown_id FROM edms_ac_credit_note_item WHERE godown_id = $id
	UNION 
	SELECT  godown_id FROM edms_ac_inventory_item_jv WHERE godown_id = $id
	";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;
	
	}

?>