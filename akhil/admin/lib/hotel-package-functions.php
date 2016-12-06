<?php 
    require_once("cg.php");
    require_once("bd.php");
    require_once("common.php");
	require_once("image-functions.php");

function listHotelPackages()
    {
        try
        {
            $sql="SELECT hotel_package_id, hotel_package_name,  trl_locations.location_id,location_name, stars,  trl_hotel_package.created_by, trl_hotel_package.last_updated_by, trl_hotel_package.date_added, trl_hotel_package.date_modified
			  FROM 
			  trl_hotel_package, trl_locations 
			  WHERE  trl_hotel_package.location_id = trl_locations.location_id ORDER BY hotel_package_name";
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

function listHotelPackagesForPackageId($id)
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

function listHotelPackagesForLocationIdString($id)
    {
        try
        {
            $sql="SELECT trl_hotel_package.hotel_package_id, hotel_package_name,  location_id, stars,created_by, last_updated_by, date_added, date_modified
		      FROM trl_hotel_package WHERE location_id IN  ($id)  ORDER BY hotel_package_name";
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
 function insertHotelPackage($name,$location_id,$stars,$thumb_href)
    {
        try
        {
            $name=clean_data($name);
			$location_id=clean_data($location_id);
            $name = ucfirst(strtolower($name));
			

            if(validateForNull($name,$location_id) && checkForNumeric($location_id))
            {
				
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="INSERT INTO
		      trl_hotel_package (hotel_package_name,  location_id, stars, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$name', '$location_id', '$stars',$admin_id, $admin_id, NOW(), NOW())";
			 
                $result=dbQuery($sql);
				$hotel_package_id=dbInsertId();
				addImagesToHotel($hotel_package_id,$thumb_href);
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

function insertHotelToPackage($package_id,$hotel_id)
    {
        try
        {
           
			

            if(checkForNumeric($package_id,$hotel_id))
            {
				
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="INSERT INTO
		      trl_rel_package_hotel (package_id,  hotel_package_id)
			  VALUES
			  ($package_id,$hotel_id)";
			 
                $result=dbQuery($sql);
				$hotel_package_id=dbInsertId();
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
	
function insertHotelsToPackage($package_id,$hotel_id_array)
    {
        try
        {
          

            if(checkForNumeric($package_id,$hotel_id_array[0]))
            {
				foreach($hotel_id_array as $hotel_id)
              	insertHotelToPackage($package_id,$hotel_id);
				
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
	

function deleteHotelsForPackage($id)
    {
        try
        {
            
            if(checkForNumeric($id))
            {
				
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="DELETE FROM
			  trl_rel_package_hotel
			  WHERE package_id=$id";
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


function updateHotelPackage($id,$name,$location_id,$stars,$thumb_href)
    {
        try
        {
			
            $name=clean_data($name);
			$location_id=clean_data($location_id);
			
            $name = ucfirst(strtolower($name));
			
            if(validateForNull($name,$location_id,$thumb_href) && checkForNumeric($location_id,$id))
            {
         
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="UPDATE 
			   trl_hotel_package SET hotel_package_name = '$name', location_id = '$location_id', stars = $stars,last_updated_by = $admin_id, date_modified = NOW()
			  WHERE hotel_package_id=$id";
                dbQuery($sql);
				
				addImagesToHotel($id,$thumb_href);
				
				
				
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
        $sql="SELECT hotel_package_id, hotel_package_name, trl_hotel_package.location_id, location_name, stars, trl_hotel_package.created_by, trl_hotel_package.last_updated_by, trl_hotel_package.date_added, trl_hotel_package.date_modified
			  FROM 
			  trl_hotel_package, trl_locations 
			  WHERE hotel_package_id=$id AND trl_hotel_package.location_id = trl_locations.location_id";
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
	
function addImagesToHotel($location_id,$img_array){
	
	
	
								if(is_array($img_array['name'])) // if proof has more than one image
								  {
									  $images_for_a_proof=count($img_array['name']);
									 
									  for($j=0;$j<$images_for_a_proof;$j++)
									  {
										  if($img_array['name'][$j]!="" &&  $img_array['name'][$j]!=null)
										  {
										   $imagee['name'] = $img_array['name'][$j];
										   $imagee['type'] = $img_array['type'][$j];
										   $imagee['tmp_name'] = $img_array['tmp_name'][$j];
										   $imagee['error'] = $img_array['error'][$j];
										   $imagee['size'] = $img_array['size'][$j];
										   
										   
										   $imageName=addHotelThumbnail($location_id,$imagee);
							   
							    			insertImageToHotel($imageName,$location_id);
										  }
										   
									  }
								  }
								 
									  
								  
	
	}	
	
function insertImageToHotel($image_name,$location_id)
{
	if(validateForNull($image_name) && checkForNumeric($location_id))
	{
		$sql="INSERT INTO trl_hotel_images (hotel_package_id , img_href) VALUES ($location_id, '$image_name')";
		
		dbQuery($sql);
		}
	
}	

function deleteImageForHotel($hotel_id,$image_id)
{
	if(checkForNumeric($hotel_id,$image_id))
	{
	$images = getCarosalImagesForHotel($hotel_id);
	if(count($images)<2)
	{
	return "number_error";	
		
	}
	else 
	{
	$sql="DELETE FROM trl_hotel_images WHERE hotel_image_id=$image_id";	
	dbQuery($sql);
	return "success";	
	}
	}
	return "error";
}

function getCarosalImagesForHotel($location_id)
{
	if(checkForNumeric($location_id))
	{
		
		$sql="SELECT hotel_image_id,img_href FROM trl_hotel_images WHERE hotel_package_id = $location_id";
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
	}		
?>