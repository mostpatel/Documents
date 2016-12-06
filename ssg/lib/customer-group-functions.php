<?php 
    require_once("cg.php");
    require_once("bd.php");
    require_once("common.php");

function checkForDuplicateFileToGroup($file_id,$group_id)
{
	
	$sql="SELECT group_id FROM fin_rel_groups_file WHERE file_id = $file_id AND group_id = $group_id";
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



function insertFileGroup($name,$group_array)
{
	
	$group_id=insertFileGroupName($name);
	
	if(checkForNumeric($group_id))
	{
	insertFilesToGroup($group_array,$group_id);
	return "success";
	}
	else 
	return "error";
}	
		
function insertFileGroupName($name)
{
	if(validateForNull($name) && strlen($name)<255 && !checkForDuplicateFileGroup($name))
	{
		$name=trim($name);
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$sql="INSERT INTO fin_groups (group_name,created_by,last_updated_by,date_added,date_modified)
		       VALUES('$name',$admin_id,$admin_id,NOW(),NOW())";
		dbQuery($sql);
		return dbInsertId();	   
		
		}
	return "error";	
}

function AddFileToGroups($file_id,$group_array)
{
 if(checkForNumeric($file_id,$group_array[0]))
 {
	foreach($group_array as $group_id)
	{
		if(checkForNumeric($group_id) && !checkForDuplicateFileToGroup($file_id,$group_id))
		{
			$sql="INSERT INTO fin_rel_groups_file(group_id,file_id) VALUES ($group_id,$file_id)";
			dbQuery($sql);
		}
	} 
	return "success";
 }	
}		

function DeleteGroupsForFile($file_id)
{
	if(checkForNumeric($file_id))
	{
		$sql="DELETE FROM fin_rel_groups_file WHERE file_id = $file_id";
		dbQuery($sql);
	}
}

function checkForDuplicateFileGroup($name,$id=false)
{
	$sql="SELECT group_id 
			  FROM 
			  fin_groups 
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
	

function editFileGroupName($id,$name)
{
	
	if(validateForNull($name) && strlen($name)<255 && !checkForDuplicateFileGroup($name,$id) && checkForNumeric($id))
	{
		$name=trim($name);
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$sql="UPDATE  fin_groups
		      SET group_name='$name',last_updated_by=$admin_id,date_modified=NOW()
		      WHERE group_id=$id";
		dbQuery($sql);
		return "success";	   
		
		}
	return "error";	
	
	}	

function deleteFileGroup($id)
{
	if(checkForNumeric($id))
	{
		$sql="DELETE FROM fin_groups WHERE group_id=$id";
		dbQuery($sql);
		return "success";
		}
	return "error";	
}	

function insertFilesToGroup($files_array,$group_id)
{
	if(count($files_array)>0 && is_numeric($group_id))
	{
	foreach($files_array as $file_id)
	{	
    
	$sql="INSERT INTO fin_rel_groups_file (group_id,file_id)
	      VALUES($group_id,$file_id)";
	dbQuery($sql);
	  
	}
	return "success";	
	
	}
	return "error";
	}

function getGroupsForFileId($file_id)
{
	$sql="SELECT group_id FROM fin_rel_groups_file WHERE file_id = $file_id";
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


function getGroupsStringForFileId($file_id)
{
	$sql="SELECT GROUP_CONCAT(group_id) FROM fin_rel_groups_file WHERE file_id = $file_id GROUP BY file_id";
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
 

function deleteRelFileGroupByGroupID($group_id)
{
	if(checkForNumeric($group_id))
	{
		$sql="DELETE FROM fin_rel_groups_file WHERE group_id=$group_id";
		dbQuery($sql);
		return "success";
		}
	return "error";	
	
	}

function deleteRelFileGroupByFileID($group_id)
{
	if(checkForNumeric($group_id))
	{
		$sql="DELETE FROM fin_rel_groups_file WHERE file_id=$group_id";
		dbQuery($sql);
		return "success";
		}
	return "error";	
	
	}
	
function editFileGroup($id,$name,$group_array)
{
	editFileGroupName($id,$name);
	$result1=deleteRelFileGroupByGroupID($id);
	$result2=insertFilesToGroup($group_array,$id);
	if($result1=="success" && $result2=="success")
	return "success";
	else
	return "error";
	}		
	

function listFileGroups()
{
	$sql="SELECT fin_groups.group_id,group_name,GROUP_CONCAT(file_id) as files_id
	      FROM fin_groups
		  LEFT JOIN fin_rel_groups_file
		  ON fin_groups.group_id=fin_rel_groups_file.group_id
		  GROUP BY fin_groups.group_id";
	$result=dbQuery($sql);	 
	if(dbNumRows($result)>0)
	return dbResultToArray($result);
	else 
	return "error"; 
	}		
function listFileGroupsWithRest()
{
	$sql="SELECT fin_groups.group_id,group_name,GROUP_CONCAT(file_id) as files_id
	      FROM fin_groups
		  LEFT JOIN fin_rel_groups_file
		  ON fin_groups.group_id=fin_rel_groups_file.group_id
		  GROUP BY fin_groups.group_id";
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

function getFileGroupByID($id)
{
	$sql="SELECT fin_groups.group_id,group_name,GROUP_CONCAT(file_id) as files_id
	      FROM fin_groups
		  LEFT JOIN fin_rel_groups_file
		  ON fin_groups.group_id=fin_rel_groups_file.group_id
		  WHERE fin_groups.group_id=$id
		  GROUP BY fin_groups.group_id";
	$result=dbQuery($sql);	
	$resultArray=dbResultToArray($result); 
	if(dbNumRows($result)>0)
	return $resultArray[0];
	else 
	return "error"; 
}

function getFileGroupNameByID($id)
{
	$sql="SELECT group_name
	      FROM fin_groups
		  WHERE fin_groups.group_id=$id
		 ";
	$result=dbQuery($sql);	
	$resultArray=dbResultToArray($result); 
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
	else 
	return "error"; 
}

	

?>