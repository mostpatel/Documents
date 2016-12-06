<?php 
    require_once("cg.php");
    require_once("bd.php");
    require_once("common.php");
	require_once("image-functions.php");

// -- Function Name : listCities
// -- Params : 
// -- Purpose : 
    function listHotelLocations()
    {
        try
        {
            $sql="SELECT location_id,location_name,  created_by, last_updated_by, date_added, date_modified
		      FROM trl_hotel_locations ORDER BY location_name";
            $result=dbQuery($sql);
            $resultArray=dbResultToArray($result);
            return $resultArray;
        }

        catch(Exception $e)
        {
        }

    }
	
	  function listValidHotelLocations()
    {
        try
        {
            $sql="SELECT location_id,location_name,  created_by, last_updated_by, date_added, date_modified
		      FROM trl_hotel_locations WHERE location_id IN (SELECT location_id FROM trl_hotel_package) ORDER BY location_name";
            $result=dbQuery($sql);
            $resultArray=dbResultToArray($result);
            return $resultArray;
        }

        catch(Exception $e)
        {
        }

    }
	
 function listHotelLocationsForSuperHotelLocation($super_location_id)
{
	
        try
        {
		if(checkForNumeric($super_location_id))
	{
            $sql="SELECT location_id,location_name,  created_by, last_updated_by, date_added, date_modified
		      FROM trl_hotel_locations WHERE super_location_id = $super_location_id ORDER BY location_name";
            $result=dbQuery($sql);
            $resultArray=dbResultToArray($result);
            return $resultArray;
	}
        }

        catch(Exception $e)
        {
        }

    }	

 function listSuperHotelLocations()
    {
        try
        {
            $sql="SELECT super_location_id, super_location_name
		      FROM trl_super_locations ORDER BY super_location_name";
            $result=dbQuery($sql);
            $resultArray=dbResultToArray($result);
            return $resultArray;
        }

        catch(Exception $e)
        {
        }

    }
	
function getSuperHotelLocationById($id)
{

	if(checkForNumeric($id))
	{
		
		 $sql="SELECT super_location_name,lng,lat
		      FROM trl_super_locations WHERE super_location_id = $id ORDER BY super_location_name";
			  
            $result=dbQuery($sql);
		
            $resultArray=dbResultToArray($result);
            return $resultArray[0];
		
		}
	
	}	

    
// -- Function Name : insertCity
// -- Params : $name
// -- Purpose : 
    function insertHotelLocation($name)
    {
        try
        {
			
			
            $name=clean_data($name);
			
			
            $duplicate=checkForDuplicateHotelLocation($name);
         
            if(validateForNull($name) && !$duplicate)
            {
				
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="INSERT INTO
		      trl_hotel_locations (location_name,  created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$name' , $admin_id, $admin_id, NOW(), NOW())";
                $result=dbQuery($sql);
				$location_id=dbInsertId();
				
			
				 
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

    



    

// -- Function Name : deleteCity
// -- Params : $id
// -- Purpose : 
    function deleteHotelLocation($id)
    {
        try
        {
            
            if(checkForNumeric($id) && !checkIfHotelLocationInUse($id))
            {
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="DELETE FROM
			  trl_hotel_locations
			  WHERE location_id=$id";
                dbQuery($sql);
                return  "success";
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

    

// -- Function Name : updateCity
// -- Params : $id,$name
// -- Purpose : 
    function updateHotelLocation($id,$name)
    {
        try
        {
            $name=clean_data($name);
            $name = ucfirst(strtolower($name));
			
			
            $duplicate=checkForDuplicateHotelLocation($name,$id);
           
            if(validateForNull($name) && checkForNumeric($id) && !$duplicate)
            {
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="UPDATE trl_hotel_locations
			  SET location_name='$name',  last_updated_by=$admin_id, date_modified=NOW()
			  WHERE location_id=$id";
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

    

// -- Function Name : checkForDuplicateCity
// -- Params : $name,$id=false
// -- Purpose : 
    function checkForDuplicateHotelLocation($name,$super_location_id,$id=false)
    {
        try
        {
            $sql="SELECT location_id 
			  FROM 
			  trl_hotel_locations 
			  WHERE location_name='$name' ";
            
            if($id==false)$sql=$sql."";
            else $sql=$sql." AND location_id!=$id";
            $result=dbQuery($sql);
            
            if(dbNumRows($result)>0)
            {
                $resultArray=dbResultToArray($result);
                return $resultArray[0][0];
                //duplicate found
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

    

// -- Function Name : getCityByID
// -- Params : $id
// -- Purpose : 
    function getHotelLocationByID($id)
    {
        $sql="SELECT location_id, location_name, created_by, last_updated_by, date_added, date_modified
			  FROM 
			  trl_hotel_locations 
			  WHERE location_id=$id";
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

    

// -- Function Name : checkIfCityInUse
// -- Params : $id
// -- Purpose : 
    function checkIfHotelLocationInUse($id)
    {
        $sql="SELECT location_id
	      FROM trl_hotel_package
		  WHERE location_id=$id LIMIT 0, 1";
        $result=dbQuery($sql);
        
        if(dbNumRows($result)>0)
        {
            return true;
        }

        return false;
    }		
?>