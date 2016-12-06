<?php 
require_once("cg.php");
require_once("common.php");
require_once("city-functions.php");
require_once("customer-functions.php");
require_once("common.php");
require_once("bd.php");



function listTeams()
{
	try
	{
		$sql="SELECT team_id, team_name, created_by
			  FROM ems_team";
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
	

function insertTeam($team_name, $admin_id_array)
{	
	try
	{
		
		
		$team_name=clean_data($team_name);
		$team_name = ucwords(strtolower($team_name));
		
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		
		
			if(validateForNull($team_name))
			{
				
				
				$sql="INSERT INTO ems_team (team_name, created_by, date_added, date_modified)				
				      VALUES ('$team_name', $admin_id, NOW(), NOW())";
				
				$result=dbQuery($sql);
				$team_id=dbInsertId();
				
				insertRelTeamMembers($team_id, $admin_id_array);
			    
				return "success";	
			
			}
			else
			{
				return false;
			}
		
		
	}
	catch(Exception $e)
	{
	}
	
}	

function insertRelTeamMembers($team_id, $admin_id_array)
{	

	try
	{
		
		if(checkForNumeric($team_id))
		{
				foreach($admin_id_array as $admin_id)
		{
			
				$sql="INSERT INTO ems_rel_team_admin_user (admin_id, team_id)				
				      VALUES ($admin_id, $team_id)";
				
				$result=dbQuery($sql);
		}
		
           return "success";
		}
			else
			{
				return false;
			}
		
		
	}
	catch(Exception $e)
	{
	}
	
}

function insertTeamLeader($team_id, $admin_id_array)
{	
	try
	{
				
			if(checkForNumeric($team_id))
			{
				foreach($admin_id_array as $admin_id)
				{
				
				$sql="INSERT INTO ems_rel_team_team_leader (team_id, admin_id)				
				      VALUES ($team_id, $admin_id)";
				
				
				$result=dbQuery($sql);
				}
			    
				return "success";	
			
			}
			else
			{
				return false;
			}
		
		
	}
	catch(Exception $e)
	{
	}
	
}


function getTeamNameByTeamId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT team_id, team_name
			  FROM ems_team
			  WHERE team_id=$id";
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




function getTeamIdByAdminId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT team_id
			  FROM ems_rel_team_admin_user
			  WHERE admin_id=$id";
			  
		
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
	


function getTeamWithMembers(){
	
	try
	{
		
		$sql="SELECT ems_team.team_id, team_name, admin_name, created_by, 
		(SELECT GROUP_CONCAT(DISTINCT ems_admin.admin_name SEPARATOR '<br>') FROM ems_rel_team_admin_user, ems_admin 
		WHERE ems_rel_team_admin_user.team_id = ems_team.team_id AND ems_admin.admin_id = ems_rel_team_admin_user.admin_id GROUP BY ems_team.team_id ) as member_names
		
			  FROM ems_team
			  
			  JOIN ems_rel_team_admin_user
			  ON ems_rel_team_admin_user.team_id = ems_team.team_id
			  
			  JOIN ems_admin
			  ON ems_team.created_by = ems_admin.admin_id GROUP BY ems_team.team_id";
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


function getTeamDetailsByTeamId($team_id)
{
	
	try
	{
		
		$sql="SELECT ems_team.team_id, team_name, ems_admin.admin_id, admin_name, created_by, 
		(SELECT GROUP_CONCAT(DISTINCT ems_admin.admin_name SEPARATOR ' <br> ') FROM ems_rel_team_admin_user, ems_admin 
		WHERE ems_rel_team_admin_user.team_id = ems_team.team_id AND ems_admin.admin_id = ems_rel_team_admin_user.admin_id GROUP BY ems_team.team_id ) as member_names, 
		(SELECT GROUP_CONCAT(DISTINCT ems_admin.admin_id SEPARATOR ' , ') FROM ems_rel_team_admin_user, ems_admin 
		WHERE ems_rel_team_admin_user.team_id = ems_team.team_id AND ems_admin.admin_id = ems_rel_team_admin_user.admin_id GROUP BY ems_team.team_id ) as member_ids
		
			  FROM ems_team
			  
			  JOIN ems_rel_team_admin_user
			  ON ems_rel_team_admin_user.team_id = ems_team.team_id
			  
			  JOIN ems_admin
			  ON ems_team.created_by = ems_admin.admin_id 
			  
			  WHERE ems_team.team_id = $team_id
			  
			  GROUP BY ems_team.team_id";
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




function getTeamWithLeaders(){
	
	try
	{
		
		$sql="SELECT team_name, ems_team.team_id, 
		(SELECT GROUP_CONCAT(DISTINCT ems_admin.admin_name SEPARATOR '<br>') FROM ems_rel_team_team_leader, ems_admin 
		WHERE ems_rel_team_team_leader.team_id = ems_team.team_id AND ems_admin.admin_id = ems_rel_team_team_leader.admin_id GROUP BY ems_team.team_id) as member_names
		
			  FROM ems_team
			  
			  JOIN ems_rel_team_team_leader
			  ON ems_rel_team_team_leader.team_id = ems_team.team_id GROUP BY ems_team.team_id";
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


function getTeamWithLeadersByTeamId($team_id)
{
	
	try
	{
		
		$sql="SELECT team_name,  
		(SELECT GROUP_CONCAT(DISTINCT ems_admin.admin_name SEPARATOR '<br>') FROM ems_rel_team_team_leader, ems_admin 
		WHERE ems_rel_team_team_leader.team_id = ems_team.team_id AND ems_admin.admin_id = ems_rel_team_team_leader.admin_id GROUP BY ems_team.team_id) as member_names,
		(SELECT GROUP_CONCAT(DISTINCT ems_admin.admin_id SEPARATOR ' , ') FROM ems_rel_team_team_leader, ems_admin 
		WHERE ems_rel_team_team_leader.team_id = ems_team.team_id AND ems_admin.admin_id = ems_rel_team_team_leader.admin_id GROUP BY ems_team.team_id ) as leader_ids
		
			  FROM ems_team
			  
			  JOIN ems_rel_team_team_leader
			  ON ems_rel_team_team_leader.team_id = ems_team.team_id 
			  
			  WHERE ems_team.team_id = $team_id
			  
			  GROUP BY ems_team.team_id";
			  
	   
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

function deletRelTeamAdminUser($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
			
		$sql="DELETE FROM ems_rel_team_admin_user
		      WHERE team_id=$id";
		
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


function deleteATeam($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
			
		deletRelTeamAdminUser($id);
		deleteRelTeamAndTeamLeader($id);
			
		$sql="DELETE FROM ems_team
		      WHERE team_id=$id";
		
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

function deleteRelTeamAndTeamLeader($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
			
		$sql="DELETE FROM ems_rel_team_team_leader
		      WHERE team_id=$id";
		
		
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


function checkDuplicateTeam($name,$id=false)
{
	if(validateForNull($name))
	{
		$sql="SELECT team_id
			  FROM ems_team
			  WHERE team_name='$name'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND team_id!=$id";		  
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

function updateATeamName($id, $name)
{
	
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		if(validateForNull($name) && checkForNumeric($id) && !checkDuplicateTeam($name,$id))
		{
		$sql="UPDATE ems_team
			  SET team_name='$name'
			  WHERE team_id=$id";
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


function updateATeam($team_id, $team_name, $admin_id_array)
{
	
	try
	{
		if(checkForNumeric($team_id))
		{
			
		updateATeamName($team_id, $team_name);
		deletRelTeamAdminUser($team_id);
		insertRelTeamMembers($team_id, $admin_id_array);
		
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

function updateTeamLeader($team_id, $admin_id_array)
{
	
	try
	{
		
		
		if(checkForNumeric($team_id))
		{
			
		deleteRelTeamAndTeamLeader($team_id);
		insertTeamLeader($team_id, $admin_id_array);
		
		
		
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



function getTeamIdsForAdminId($admin_id)
{
	
	try
	{
		
		$sql="SELECT team_id 
		
		      FROM ems_rel_team_team_leader
			  
			  WHERE admin_id = $admin_id";
		
		
		
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		
		$returnArray=array();
		foreach($resultArray as $teamIds)
		{
			$returnArray[]=$teamIds['team_id'];
			
		}
		return $returnArray;
		
		}
		else
		return false;
		}
	
	catch(Exception $e)
	{
	}
	
}		


function getTeamMemberIdsForTeamId($team_id)
{
	
	try
	{
		
		$sql="SELECT admin_id 
		
		      FROM ems_rel_team_admin_user
			  
			  WHERE team_id = $team_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		
		$returnArray=array();
		foreach($resultArray as $adminIds)
		{
			$returnArray[]=$adminIds['admin_id'];
			
		}
		return $returnArray;
		
		}
		else
		return false;
		}
	
	catch(Exception $e)
	{
	}
	
}	



	
function getHisTeamMemberIdsForAnAdminId($admin_id)

{
	$teamIdArray = getTeamIdsForAdminId($admin_id);
	
	$hisMemberIdArray = array();
	
	$adminIdsUnion = array();
	
	foreach($teamIdArray as $teamId)
	{
	  $hisMemberIdArray = getTeamMemberIdsForTeamId($teamId);
	  $adminIdsUnion[] = $hisMemberIdArray;
	}
	
	$finalReturnArray = array();
	
	foreach($adminIdsUnion as $adminIds)
	{
		foreach($adminIds as $adminId)
		{
			$finalReturnArray[] = $adminId;
		}
		
	}
	$finalReturnArray[] = $admin_id;
	return array_unique($finalReturnArray);
}		

?>