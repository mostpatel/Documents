<?php 
    require_once("cg.php");
    require_once("bd.php");
    require_once("common.php");

function insertLedgerGroup($name,$group_array)
{
	
	$group_id=insertLedgerGroupName($name);
	
	if(checkForNumeric($group_id))
	{
	insertLedgersToGroup($group_array,$group_id);
	return "success";
	}
	else 
	return "error";
}	
		
function insertLedgerGroupName($name)
{
	if(validateForNull($name) && strlen($name)<255 && !checkForDuplicateLedgerGroup($name))
	{
		$name=trim($name);
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$sql="INSERT INTO fin_ac_ledgers_group (group_name,created_by,last_updated_by,date_added,date_modified)
		       VALUES('$name',$admin_id,$admin_id,NOW(),NOW())";
		dbQuery($sql);
		return dbInsertId();	   
		
		}
	return "error";	
	}		

function checkForDuplicateLedgerGroup($name,$id=false)
{
	$sql="SELECT group_id 
			  FROM 
			  fin_ac_ledgers_group 
			  WHERE group_name='$name'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND group_id!=$id";		  
		$result=dbQuery($sql);	
		
		if(dbNumRows($result)>0)
		{
			return true; //duplicate found
			} 
		else
		{
			return false;
			}	 
	
	
	}	
	

function editLedgerGroupName($id,$name)
{
	
	if(validateForNull($name) && strlen($name)<255 && !checkForDuplicateLedgerGroup($name,$id) && checkForNumeric($id))
	{
		$name=trim($name);
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$sql="UPDATE  fin_ac_ledgers_group
		      SET group_name='$name',last_updated_by=$admin_id,date_modified=NOW()
		      WHERE group_id=$id";
		dbQuery($sql);
		return "success";	   
		
		}
	return "error";	
	
	}	

function deleteLedgerGroup($id)
{
	if(checkForNumeric($id))
	{
		$sql="DELETE FROM fin_ac_ledgers_group WHERE group_id=$id";
		dbQuery($sql);
		return "success";
		}
	return "error";	
}	

function insertLedgersToGroup($ledgers_array,$group_id)
{
	if(count($ledgers_array)>0 && is_numeric($group_id))
	{
	foreach($ledgers_array as $ledger_id)
	{	
	$sql="INSERT INTO fin_ac_rel_ledgers_group (group_id,ledger_id)
	      VALUES($group_id,$ledger_id)";
	dbQuery($sql);
	  
	}
	return "success";	
	
	}
	return "error";
	}

function deleteRelLedgerGroupByGroupID($group_id)
{
	if(checkForNumeric($group_id))
	{
		$sql="DELETE FROM fin_ac_rel_ledgers_group WHERE group_id=$group_id";
		dbQuery($sql);
		return "success";
		}
	return "error";	
	
	}

function deleteRelLedgerGroupByLedgerID($group_id)
{
	if(checkForNumeric($group_id))
	{
		$sql="DELETE FROM fin_ac_rel_ledgers_group WHERE ledger_id=$group_id";
		dbQuery($sql);
		return "success";
		}
	return "error";	
	
	}
	
function editLedgerGroup($id,$name,$group_array)
{
	editLedgerGroupName($id,$name);
	$result1=deleteRelLedgerGroupByGroupID($id);
	$result2=insertLedgersToGroup($group_array,$id);
	if($result1=="success" && $result2=="success")
	return "success";
	else
	return "error";
	}		
	
function mergeGroup($merge_id_array,$name,$city_id)
{
	
	$name=clean_data($name);
		$name = ucfirst(strtolower($name));
		$duplicate=true;
		foreach($merge_id_array as $duplicate_id)
		{
		$duplicate1=checkForDuplicateGroup($name,$duplicate_id);
		$duplicate=$duplicate && $duplicate1;
		}
		if(checkForNumeric($city_id) && validateForNull($name) && !$duplicate)
		{
			
	$merge_id_string=implode(",",$merge_id_array);
	$group_id=insertGroup($name,$city_id);
	$sql="UPDATE fin_customer SET group_id=$group_id
	       WHERE group_id IN ($merge_id_string)";
	dbQuery($sql);
	$sql="UPDATE fin_guarantor SET group_id=$group_id
	       WHERE group_id IN ($merge_id_string)";
	dbQuery($sql);
	
	$sql="UPDATE fin_ac_rel_ledgers_group SET group_id=$group_id
	       WHERE group_id IN ($merge_id_string)";
	dbQuery($sql);
	
	foreach($merge_id_array as $merge_id)
	{
		deleteGroup($merge_id);
		}
	return "success";	
		}
	return "error";	
		   
}

function listLedgerGroups()
{
	$sql="SELECT fin_ac_ledgers_group.group_id,group_name,GROUP_CONCAT(ledger_id) as ledgers_id
	      FROM fin_ac_ledgers_group
		  LEFT JOIN fin_ac_rel_ledgers_group
		  ON fin_ac_ledgers_group.group_id=fin_ac_rel_ledgers_group.group_id
		  GROUP BY fin_ac_ledgers_group.group_id";
	$result=dbQuery($sql);	 
	if(dbNumRows($result)>0)
	return dbResultToArray($result);
	else 
	return "error"; 
	}		
function listLedgerGroupsWithRest()
{
	$sql="SELECT fin_ac_ledgers_group.group_id,group_name,GROUP_CONCAT(ledger_id) as ledgers_id
	      FROM fin_ac_ledgers_group
		  LEFT JOIN fin_ac_rel_ledgers_group
		  ON fin_ac_ledgers_group.group_id=fin_ac_rel_ledgers_group.group_id
		  GROUP BY fin_ac_ledgers_group.group_id";
	$result=dbQuery($sql);	
	$resultArray=dbResultToArray($result); 
	if(dbNumRows($result)>0)
	{
		$j=count($resultArray);
		$restGroups=getRestGroupsFromGroup();
		if($restGroups!="error")
		{
		$resultArray[$j]['group_id']=0;
		$resultArray[$j]['group_name']='Rest Groups';
		$resultArray[$j]['groups_id']=$restGroups;
		}
		return $resultArray;
		}
	else 
	return "error"; 
	}		

function getLedgerGroupByID($id)
{
	$sql="SELECT fin_ac_ledgers_group.group_id,group_name,GROUP_CONCAT(ledger_id) as ledgers_id
	      FROM fin_ac_ledgers_group
		  LEFT JOIN fin_ac_rel_ledgers_group
		  ON fin_ac_ledgers_group.group_id=fin_ac_rel_ledgers_group.group_id
		  WHERE fin_ac_ledgers_group.group_id=$id
		  GROUP BY fin_ac_ledgers_group.group_id";
	$result=dbQuery($sql);	
	$resultArray=dbResultToArray($result); 
	if(dbNumRows($result)>0)
	return $resultArray[0];
	else 
	return "error"; 
}

function getLedgerGroupNameByID($id)
{
	$sql="SELECT group_name
	      FROM fin_ac_ledgers_group
		  WHERE fin_ac_ledgers_group.group_id=$id
		 ";
	$result=dbQuery($sql);	
	$resultArray=dbResultToArray($result); 
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
	else 
	return "error"; 
}

function getRestGroupsFromGroup()
{
	$sql="SELECT ledger_id FROM fin_ac_rel_ledgers_group";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		$group_id_array=array();
		foreach($resultArray as $re)
		{
			$group_id=null;
			$group_id=$re[0];
			if(!in_array($group_id,$group_id_array))
			{
				$group_id_array[]=$group_id;
				}
			}
		}
	
	if(is_array($group_id_array) && !empty($group_id_array))	
	{
	 $groups_id=implode(",",$group_id_array);
	}
	 else 
	 $groups_id="0";
	
	if(validateForNull($groups_id))
	{
	$sql="SELECT ledger_id,ledger_name  FROM fin_ac_ledgers WHERE ledger_id NOT IN ($groups_id) ORDER BY ledger_name";
	$result2=dbQuery($sql);
	$result2Array=dbResultToArray($result2);
	if(dbNumRows($result)>0)
	{
		$returnArray=array();
		foreach($result2Array as $re2)
		{
			if(is_numeric($re2[0]))
			$returnArray[]=$re2[0];
			}
		return implode(",",$returnArray);
		}
	else
	return "error";	
	}
	}		

?>