<?php 
    require_once("cg.php");
    require_once("bd.php");
    require_once("common.php");
	require_once("image-functions.php");

// -- Function Name : listCities
// -- Params : 
// -- Purpose : 
    function listLocations()
    {
        try
        {
            $sql="SELECT location_id,location_name, super_location_id, longitude, latitude, img_href, created_by, last_updated_by, date_added, date_modified
		      FROM trl_locations ORDER BY location_name";
            $result=dbQuery($sql);
            $resultArray=dbResultToArray($result);
            return $resultArray;
        }

        catch(Exception $e)
        {
        }

    }
	
 function listLocationsForSuperLocation($super_location_id)
{
	
        try
        {
		if(checkForNumeric($super_location_id))
	{
            $sql="SELECT location_id,location_name, super_location_id, longitude, latitude, img_href, created_by, last_updated_by, date_added, date_modified
		      FROM trl_locations WHERE super_location_id = $super_location_id ORDER BY location_name";
            $result=dbQuery($sql);
            $resultArray=dbResultToArray($result);
            return $resultArray;
	}
        }

        catch(Exception $e)
        {
        }

    }	

 function listSuperLocations()
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
	
function getSuperLocationById($id)
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
    function insertLocation($name,$super_location_id,$long,$lat,$img,$why_should,$about,$carosal_images)
    {
        try
        {
			
			
            $name=clean_data($name);
			$super_location_id=clean_data($super_location_id);
			$long=clean_data($long);
			$lat = clean_data($lat);
			$about = clean_data($about);
			$why_should = clean_data($why_should);
            $name = ucfirst(strtolower($name));
			
            $duplicate=checkForDuplicateLocation($name,$super_location_id);
         
            if(validateForNull($name,$why_should,$about) && checkForNumeric($lat,$long,$super_location_id) && !$duplicate && $super_location_id>0)
            {
				
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="INSERT INTO
		      trl_locations (location_name, super_location_id, longitude, latitude, img_href, why_should, about, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$name', $super_location_id, $long, $lat, 'NA', '$why_should', '$about' , $admin_id, $admin_id, NOW(), NOW())";
                $result=dbQuery($sql);
				$location_id=dbInsertId();
				
				 $img_href= addLocationImage($location_id,$name,$img);
				 addCarosalImagesToLocation($location_id,$carosal_images);
				 $no_of__images_carosal = getTotalNumberOfImagesInCarosalForLocation($location_id);
				 if(!$img_href || $no_of__images_carosal<1)
				 {
					
					 deleteLocation($location_id);
					 return "img_error";
					 }
				else
				{
					$sql="UPDATE trl_locations SET img_href = '$img_href' WHERE location_id=$location_id";
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

    



    

// -- Function Name : deleteCity
// -- Params : $id
// -- Purpose : 
    function deleteLocation($id)
    {
        try
        {
            
            if(checkForNumeric($id) && !checkIfLocationInUse($id))
            {
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="DELETE FROM
			  trl_locations
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
    function updateLocation($id,$name,$super_location_id,$long,$lat,$img,$why_should,$about,$carosal_images)
    {
        try
        {
            $name=clean_data($name);
            $name = ucfirst(strtolower($name));
			
			$super_location_id=clean_data($super_location_id);
			$long=clean_data($long);
			$lat=clean_data($lat);
			$about = clean_data($about);
			$why_should = clean_data($why_should);
            $duplicate=checkForDuplicateLocation($name,$super_location_id,$id);
           
            if(validateForNull($name,$why_should,$about) && checkForNumeric($id,$super_location_id,$long,$lat) && !$duplicate)
            {
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="UPDATE trl_locations
			  SET location_name='$name', super_location_id = $super_location_id, longitude = $long, latitude = $lat, why_should = '$why_should', about = '$about', last_updated_by=$admin_id, date_modified=NOW()
			  WHERE location_id=$id";
                dbQuery($sql);
				
				
				 addCarosalImagesToLocation($id,$carosal_images);
				 
				 if(isset($img['tmp_name']) && validateForNull($img['tmp_name']))
				 {
				 	$img_href= addLocationImage($id,$name,$img);
					 if(!$img_href)
					 {
						 return "img_error";
						 }
					else
					{
						$sql="UPDATE trl_locations SET img_href = '$img_href' WHERE location_id=$id";
						dbQuery($sql);
					}	 
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

    

// -- Function Name : checkForDuplicateCity
// -- Params : $name,$id=false
// -- Purpose : 
    function checkForDuplicateLocation($name,$super_location_id,$id=false)
    {
        try
        {
            $sql="SELECT location_id 
			  FROM 
			  trl_locations 
			  WHERE location_name='$name' AND super_location_id=$super_location_id ";
            
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
    function getLocationByID($id)
    {
        $sql="SELECT location_id, location_name, super_location_id, longitude, latitude, img_href, why_should, about, created_by, last_updated_by, date_added, date_modified
			  FROM 
			  trl_locations 
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
    function checkIfLocationInUse($id)
    {
        $sql="SELECT location_id
	      FROM trl_rel_package_location
		  WHERE location_id=$id LIMIT 0, 1";
        $result=dbQuery($sql);
        
        if(dbNumRows($result)>0)
        {
            return true;
        }

        return false;
    }
	
function addCarosalImagesToLocation($location_id,$img_array){
	
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
										   
										   
										   $imageName=addLocationCarosalImage($location_id,$imagee);
							   
							    			insertCarosalImageToLocation($imageName,$location_id);
										  }
										   
									  }
								  }
								 
									  
								  
	
	}	
	
function insertCarosalImageToLocation($image_name,$location_id)
{
	if(validateForNull($image_name) && checkForNumeric($location_id))
	{
		$sql="INSERT INTO trl_location_images (location_id , img_href) VALUES ($location_id, '$image_name')";
		dbQuery($sql);
		}
	
}

function getCarosalImagesForLocation($location_id)
{
	if(checkForNumeric($location_id))
	{
		
		$sql="SELECT location_image_id,img_href FROM trl_location_images WHERE location_id = $location_id";
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

function getLocationForPackage($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT  location_name FROM trl_rel_package_location, trl_locations WHERE package_id = $id AND trl_locations.location_id = trl_rel_package_location.location_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		{
			$resultArray=dbResultToArray($result);
			return $resultArray;
			}
		return false;	
		}
		
}

function getTotalNumberOfImagesInCarosalForLocation($location_id)
{
	if(checkForNumeric($location_id))
	{
		$sql="SELECT COUNT(location_image_id) FROM trl_location_images WHERE location_id = $location_id";
		$result=dbQuery($sql);
		$resultArray = dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else 
		return 0;
	}
	
	}

function deleteCarosalImage($id,$location_id)
{
	
	if(checkForNumeric($id,$location_id) && getTotalNumberOfImagesInCarosalForLocation($location_id)>1)
	{
		
		$sql="DELETE FROM trl_location_images WHERE location_image_id=$id";
		dbQuery($sql);
		return "success";
		
	}
	return "error";
	}	
		
?>