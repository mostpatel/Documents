<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");


	
function listRelations()
{
	
	try
	{
		$sql="SELECT relation_id, relation
			  FROM ems_relations";
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



function insertRelation($relation)
{
	try
	{
		$relation=clean_data($relation);
		$relation = ucwords(strtolower($relation));
		if(validateForNull($relation) && !checkDuplicateRelation($relation))
		{
			$sql="INSERT INTO 
				ems_relations (relation)
				VALUES ('$relation')";
		$result=dbQuery($sql);
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



function checkDuplicateRelation($relation,$id=false)
{
	if(validateForNull($relation))
	{
		$sql="SELECT relation_id
			  FROM ems_relations
			  WHERE relation='$relation'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND relation_id!=$id";		  
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0][0]; //duplicate found
			} 
		else
		{
			return false;
			}
	}
}		


function deleteRelation($id){
	
	try
	{
		if(1==1) //!checkifRelationInUse($id)
		{
		$sql="DELETE FROM ems_relations
		      WHERE relation_id=$id";
		
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



function checkifRelationInUse($id)
{
	
	if(checkForNumeric($id))
	{
	$sql="SELECT 
	      FROM 
		  Where";
	$result=dbQuery($sql);	  
	if(dbNumRows($result)>0)
	return true;
	else 
	return false;
	}
	
}			
		
	

function updateRelation($id,$relation)
{
	
	try
	{
		$relation=clean_data($relation);
		$relation = ucwords(strtolower($relation));
		if(validateForNull($relation) && checkForNumeric($id) && !checkDuplicateRelation($relation,$id))
		{
		$sql="UPDATE ems_relations
			  SET relation ='$relation'
			  WHERE relation_id=$id";
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


function getRelationById($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT relation_id, relation
			  FROM ems_relations
			  WHERE relation_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}



?>