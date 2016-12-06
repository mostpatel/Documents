<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("image-functions.php");
require_once("common.php");
require_once("bd.php");


	
function listEvents()
{
	
	try
	{
		$sql="SELECT event_id, event_title, event_img_path, event_description
			  FROM ems_events";
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
	


function insertEvent($event_title, $event_img, $event_description)
{
	try
	{
		$event_title=clean_data($event_title);
		$event_title = ucwords(strtolower($event_title));
		$event_description=clean_data($event_description);
		
		if(validateForNull($event_title))
		{
			$img_path = UploadImagee($event_img, SRV_ROOT.'images/event/', $max_width=2500 , $max_height=2500, $prefix=false);
			
			$sql="INSERT INTO 
				ems_events (event_title, event_img_path, event_description)
				VALUES ('$event_title', '$img_path', '$event_description')";
			
			
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





function deleteEvent($id){
	
	try
	{
		
		$sql="DELETE FROM ems_events
		      WHERE event_id=$id";
		dbQuery($sql);
		return "success";
		
	}
	catch(Exception $e)
	{
	}
	
}	



function updateEvent($id,$event_title){
	
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		if(validateForNull($name) && checkForNumeric($id) && !checkDuplicateCategory($name,$id))
		{
		$sql="UPDATE ems_events
			  SET event_title='$event_title'
			  WHERE event_id=$id";
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



function getEventById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT event_id, event_title, event_img_path, event_description
			  FROM ems_events
			  WHERE event_id=$id";
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