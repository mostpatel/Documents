<?php 
    require_once("cg.php");
    require_once("bd.php");
    require_once("common.php");
	require_once("image-functions.php");

function listHotelPackages()
    {
        try
        {
            $sql="SELECT hotel_package_id, hotel_package_name, days, nights, trl_hotel_locations.location_id,location_name, stars, thumb_href, tarriff,  trl_hotel_package.created_by, trl_hotel_package.last_updated_by, trl_hotel_package.date_added, trl_hotel_package.date_modified
			  FROM 
			  trl_hotel_package, trl_hotel_locations 
			  WHERE  trl_hotel_package.location_id = trl_hotel_locations.location_id ORDER BY hotel_package_name";
            $result=dbQuery($sql);
            
			$resultArray=dbResultToArray($result);
			
		
			
            return $resultArray;
        }

        catch(Exception $e)
        {
        }

}
	
function listHotelPackagesForLocationId($id)
    {
        try
        {
            $sql="SELECT trl_hotel_package.hotel_package_id, hotel_package_name, days, nights, location_id, stars, thumb_href, tarriff,created_by, last_updated_by, date_added, date_modified
		      FROM trl_hotel_package WHERE location_id = $id  ORDER BY hotel_package_name";
            $result=dbQuery($sql);
            
			$resultArray=dbResultToArray($result);
			
			for($i=0;$i<count($resultArray);$i++)
			{
			$hotel_package_id=$resultArray[$i]['hotel_package_id'];	
			}
			
            return $resultArray;
        }

        catch(Exception $e)
        {
        }

}
 function insertHotelPackage($name,$location_id,$days,$nights,$stars,$thumb_href,$tarriff)
    {
        try
        {
            $name=clean_data($name);
			$location_id=clean_data($location_id);
			$days=clean_data($days);
			$nights=clean_data($nights);
            $name = ucfirst(strtolower($name));
			
			
			
            if(validateForNull($name,$location_id,$thumb_href) && checkForNumeric($days,$nights,$location_id,$tarriff))
            {
				
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="INSERT INTO
		      trl_hotel_package (hotel_package_name, days, nights, location_id, stars, thumb_href, tarriff, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$name', $days, $nights, '$location_id', '$stars', '$thumb_href',$tarriff,$admin_id, $admin_id, NOW(), NOW())";
			 
                $result=dbQuery($sql);
				$hotel_package_id=dbInsertId();
				
				$img_href = addPackageThumbnail($hotel_package_id,$thumb_href);
				
				 if(!$img_href)
				 {
					 deleteHotelPackage($hotel_package_id);
					
					 return "img_error";
					 }
				else
				{
					$sql="UPDATE trl_hotel_package SET thumb_href = '$img_href' WHERE hotel_package_id=$hotel_package_id";
					dbQuery($sql);
					}	 
				
                return $hotel_package_id;
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
	

 
function deleteHotelPackage($id)
    {
        try
        {
            
            if(checkForNumeric($id))
            {
				
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="DELETE FROM
			  trl_hotel_package
			  WHERE hotel_package_id=$id";
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


function updateHotelPackage($id,$name,$location_id,$days,$nights,$stars,$thumb_href,$tarriff)
    {
        try
        {
			
            $name=clean_data($name);
			$location_id=clean_data($location_id);
			$days=clean_data($days);
			$nights=clean_data($nights);
            $name = ucfirst(strtolower($name));
			
            if(validateForNull($name,$location_id,$thumb_href) && checkForNumeric($days,$nights,$location_id,$id))
            {
         
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="UPDATE 
			   trl_hotel_package SET hotel_package_name = '$name', days = $days, nights = $nights, location_id = '$location_id', stars = $stars,last_updated_by = $admin_id, date_modified = NOW(),  tarriff = $tarriff
			  WHERE hotel_package_id=$id";
                dbQuery($sql);
				
				$img_href = addPackageThumbnail($id,$thumb_href);
				
				 if(!$img_href)
				 {
					 deleteHotelPackage($id);
					
					 return "img_error";
					 }
				else
				{
					$sql="UPDATE trl_hotel_package SET thumb_href = '$img_href' WHERE hotel_package_id=$id";
					dbQuery($sql);
					}	 
				
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




   
function getHotelPackageByID($id)
    {
        $sql="SELECT hotel_package_id, hotel_package_name, days, nights, trl_hotel_package.location_id, location_name, stars, thumb_href,tarriff, trl_hotel_package.created_by, trl_hotel_package.last_updated_by, trl_hotel_package.date_added, trl_hotel_package.date_modified
			  FROM 
			  trl_hotel_package, trl_hotel_locations 
			  WHERE hotel_package_id=$id AND trl_hotel_package.location_id = trl_hotel_locations.location_id";
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
 
  

function insertFeaturedHotelPackage($hotel_package_id)
{
	if(checkForNumeric($hotel_package_id) && getNumberOfFeaturedHotelPackages()<3)
	{
	$sql="UPDATE trl_hotel_package SET featured=1 WHERE hotel_package_id = $hotel_package_id";
	dbQuery($sql);
	return "success";
	}
	return "error";
	}

function deleteFeaturedHotelPackage($hotel_package_id)
{
	if(checkForNumeric($hotel_package_id) && getNumberOfFeaturedHotelPackages()>0)
	{
	$sql="UPDATE trl_hotel_package SET featured=0 WHERE hotel_package_id = $hotel_package_id";
	dbQuery($sql);
	return "success";
	}
	return "error";
}

function getIfHotelPackageFeaturedOrNot($hotel_package_id)
{
	if(checkForNumeric($hotel_package_id))
	{
	$sql="SELECT hotel_package_id FROM trl_hotel_package WHERE featured=1 AND hotel_package_id = $hotel_package_id";
	$result = dbQuery($sql);
	$resultArray = dbResultToArray($result);
	if(dbNumRows($result)>0)
	return true;
	}
	return false;
	
	}

function getAllFeaturedHotelPackages()
{
	
	 $sql="SELECT hotel_package_id, hotel_package_name, days, nights, location_id, stars, thumb_href,tarriff, created_by, last_updated_by, date_added, date_modified
		      FROM trl_hotel_package WHERE featured=1 ORDER BY hotel_package_name";
	$result = dbQuery($sql);
	$resultArray = dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return false;
	}	
	
function getNumberOfFeaturedHotelPackages()
{
	
	$sql="SELECT COUNT(hotel_package_id) FROM trl_hotel_package WHERE featured=1";
	$result = dbQuery($sql);
	$resultArray = dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
	else
	return 0;
	}			
?>