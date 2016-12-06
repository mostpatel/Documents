<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");

function listRtoAgents(){
	
	try
	{
		$sql="SELECT rto_agent_id, rto_agent_name, rto_agent_address, rto_agent_contact_no
		  FROM fin_rto_agent ORDER BY rto_agent_name";
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

function insertRtoAgent($rto_agent_name,$address='',$contact_no=''){
	
	try
	{
		$rto_agent_name=clean_data($rto_agent_name);
		$rto_agent_name = ucwords(strtolower($rto_agent_name));
		$address=clean_data($address);
		if($address==null || $address=="")
		{
			$address="NA";
			}
		if($contact_no==null || $contact_no=="")
		{
			$contact_no="NA";
			}	
		if(validateForNull($rto_agent_name) && !checkForDuplicateRtoAgent($rto_agent_name))
			{
			$admin_id=$_SESSION['adminSession']['admin_id'];
			$sql="INSERT INTO fin_rto_agent
					(rto_agent_name, rto_agent_address,rto_agent_contact_no ,created_by, last_updated_by, date_added, date_modified)
					VALUES
					('$rto_agent_name','$address','$contact_no',$admin_id,$admin_id,NOW(),NOW())";
					
			dbQuery($sql);
			$dealer_id=dbInsertId();
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

function insertRtoAgentIfNotDuplicate($rto_agent_name,$address='',$contact_no=''){
	
	try
	{
		$rto_agent_name=clean_data($rto_agent_name);
		$rto_agent_name = ucwords(strtolower($rto_agent_name));
		$address=clean_data($address);
		if($address==null || $address=="")
		{
			$address="NA";
			}
		if($contact_no==null || $contact_no=="")
		{
			$contact_no="NA";
			}	
		$duplicate = checkForDuplicateRtoAgent($rto_agent_name);
		if(checkForNumeric($duplicate))
		return $duplicate;	
		if(validateForNull($rto_agent_name))
			{
			$admin_id=$_SESSION['adminSession']['admin_id'];
			$sql="INSERT INTO fin_rto_agent
					(rto_agent_name, rto_agent_address,rto_agent_contact_no ,created_by, last_updated_by, date_added, date_modified)
					VALUES
					('$rto_agent_name','$address','$contact_no',$admin_id,$admin_id,NOW(),NOW())";
					
			dbQuery($sql);
			$dealer_id=dbInsertId();
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



function checkIfRtoAgentIsInUse($rto_agent_id)
{
	
	$sql="SELECT rto_agent_id FROM fin_file WHERE rto_agent_id=$rto_agent_id";
	$result=dbQuery($sql);
	
	if(dbNumRows($result)>0)
	{	
	return true;
	}
	else
	return false;
	
	}	

function deleteRtoAgent($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfRtoAgentIsInUse($id))
		{
		$sql="DELETE FROM fin_rto_agent WHERE rto_agent_id=$id";
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

function updateRtoAgent($id,$rto_agent_name,$address,$contact_no){
	
	try
	{
		$rto_agent_name=clean_data($rto_agent_name);
		$rto_agent_name = ucwords(strtolower($rto_agent_name));
		$address=clean_data($address);
		if($address==null || $address=="")
		{
			$address="NA";
			}
		if($contact_no==null || $contact_no=="")
		{
			$contact_no="NA";
			}	
		if(validateForNull($rto_agent_name)  && checkForNumeric($id))
			{
			
			$admin_id=$_SESSION['adminSession']['admin_id'];
			$sql="UPDATE fin_rto_agent
					SET rto_agent_name = '$rto_agent_name', rto_agent_address ='$address', rto_agent_contact_no='$contact_no', last_updated_by=$admin_id, date_modified=NOW()
					WHERE rto_agent_id=$id";
			
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

function getRtoAgentById($id){
	
	try
	{
		$sql="SELECT rto_agent_id, rto_agent_name, rto_agent_address, rto_agent_contact_no
		  FROM fin_rto_agent
		  WHERE rto_agent_id=$id";
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

function getRtoAgentNameFromRtoAgentId($id)
{
try
	{
		$sql="SELECT  rto_agent_name
		  FROM fin_rto_agent
		  WHERE rto_agent_id=$id";
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

function checkForDuplicateRtoAgent($rto_agent_name)
{
	if(validateForNull($rto_agent_name))
	{
	$sql="SELECT  rto_agent_id
		  FROM fin_rto_agent
		  WHERE rto_agent_name='$rto_agent_name'";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0]; 
		else
		return false;
	}
	return true;
}

function getDirectRtoAgentId()
{
	
	$sql="SELECT  rto_agent_id
		  FROM fin_rto_agent
		  WHERE rto_agent_name='Direct'";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0]; 
		else
		return false;
	
	return true;
	
}
?>