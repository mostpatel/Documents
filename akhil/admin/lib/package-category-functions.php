<?php 
    require_once("cg.php");
    require_once("bd.php");
    require_once("common.php");
	require_once("image-functions.php");

// -- Function Name : listCities
// -- Params : 
// -- Purpose : 
function listPackageCategory($type=false)
    {
        try
        {
            $sql="SELECT pkg_cat_id,pkg_cat_name, created_by, last_updated_by, date_added, date_modified, type
		      FROM trl_package_category";
			  if(checkForNumeric($type))
			  $sql=$sql." WHERE type = $type ";
			  $sql=$sql." ORDER BY pkg_cat_name";
			  
            $result=dbQuery($sql);
            $resultArray=dbResultToArray($result);
            return $resultArray;
        }

        catch(Exception $e)
        {
        }

    }
	


    
// -- Function Name : insertCity
// -- Params : $name
// -- Purpose : 
function insertPackageCategory($name,$about,$carosal_images,$type)
    {
        try
        {
			
			
            $name=clean_data($name);
			$about = clean_data($about);
			$name = ucfirst(strtolower($name));		
            $duplicate=checkForDuplicatePackageCategory($name);
			
            if(validateForNull($name,$about)  && !$duplicate && checkForNumeric($type))
            {
				
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="INSERT INTO
		      trl_package_category (pkg_cat_name, about, created_by, last_updated_by, date_added, date_modified,type)
			  VALUES
			  ('$name', '$about' , $admin_id, $admin_id, NOW(), NOW(),$type)";
			  
			  
                $result=dbQuery($sql);
				$pkg_cat_id=dbInsertId();
				$location_name=str_replace(" - ","_",$name);
				$location_name=str_replace("-","_",$name);
				$location_name=str_replace(" ","",$name);
				 addCarosalImagesToPackageCategory($pkg_cat_id,$carosal_images,$location_name);
				
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
    function deletePackageCategory($id)
    {
        try
        {
            
            if(checkForNumeric($id) && !checkIfPackageCategoryInUse($id))
            {
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="DELETE FROM
			  trl_package_category
			  WHERE pkg_cat_id=$id";
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
    function updatePackageCategory($id,$name,$about,$carosal_images,$type)
    {
        try
        {
            $name=clean_data($name);
            $name = ucfirst(strtolower($name));
			
			
			$about = clean_data($about);
			
            $duplicate=checkForDuplicatePackageCategory($name,$id);
           
            if(validateForNull($name,$about) && checkForNumeric($id,$type) && !$duplicate)
            {
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="UPDATE trl_package_category
			  SET pkg_cat_name='$name', about = '$about', last_updated_by=$admin_id, date_modified=NOW(), type=$type
			  WHERE pkg_cat_id=$id";
                dbQuery($sql);
				
				$location_name=str_replace(" - ","_",$name);
				$location_name=str_replace("-","_",$name);
				$location_name=str_replace(" ","",$name);
				 addCarosalImagesToPackageCategory($id,$carosal_images,$location_name);
				 
				 
				
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
    function checkForDuplicatePackageCategory($name,$id=false)
    {
        try
        {
            $sql="SELECT pkg_cat_id 
			  FROM 
			  trl_package_category
			  WHERE pkg_cat_name='$name'  ";
            
            if($id==false)$sql=$sql."";
            else $sql=$sql." AND pkg_cat_id!=$id";
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
    function getPackageCategoryByID($id)
    {
        $sql="SELECT pkg_cat_id, pkg_cat_name, about, created_by, last_updated_by, date_added, date_modified, type
			  FROM 
			  trl_package_category 
			  WHERE pkg_cat_id=$id";
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
    function checkIfPackageCategoryInUse($id)
    {
        $sql="SELECT pkg_cat_id
	      FROM trl_rel_package_package_category
		  WHERE pkg_cat_id=$id LIMIT 0, 1";
        $result=dbQuery($sql);
        
        if(dbNumRows($result)>0)
        {
            return true;
        }

        return false;
    }
	
function addCarosalImagesToPackageCategory($pkg_cat_id,$img_array,$location_name){
	
	
	
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
										   
										   
										   $imageName=addPackageCategoryCarosalImage($location_name."_".$pkg_cat_id,$imagee,$location_name);
							   
							    			insertCarosalImageToPackageCategory($imageName,$pkg_cat_id);
										  }
										   
									  }
								  }
								 
									  
								  
	
	}	
	
function insertCarosalImageToPackageCategory($image_name,$pkg_cat_id)
{
	if(validateForNull($image_name) && checkForNumeric($pkg_cat_id))
	{
		$sql="INSERT INTO trl_package_category_images (pkg_cat_id , img_href) VALUES ($pkg_cat_id, '$image_name')";
		
		dbQuery($sql);
		}
	
}

function getCarosalImagesForPackageCategory($pkg_cat_id)
{
	if(checkForNumeric($pkg_cat_id))
	{
		
		$sql="SELECT pkg_cat_image_id,img_href FROM trl_package_category_images WHERE pkg_cat_id = $pkg_cat_id";
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

function getPackageCategoryForPackage($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT  trl_package_category.pkg_cat_id,pkg_cat_name FROM trl_rel_package_package_category, trl_package_category WHERE package_id = $id AND trl_package_category.pkg_cat_id = trl_rel_package_package_category.pkg_cat_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		{
			$resultArray=dbResultToArray($result);
			return $resultArray;
			}
		return false;	
		}
		
}

function getTotalNumberOfImagesInCarosalForPackageCategory($pkg_cat_id)
{
	if(checkForNumeric($pkg_cat_id))
	{
		$sql="SELECT COUNT(pkg_cat_image_id) FROM trl_package_category_images WHERE pkg_cat_id = $pkg_cat_id";
		$result=dbQuery($sql);
		$resultArray = dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else 
		return 0;
	}
	
	}

function deletePackageCategoryCarosalImage($id,$pkg_cat_id)
{
	
	if(checkForNumeric($id,$pkg_cat_id) && getTotalNumberOfImagesInCarosalForPackageCategory($pkg_cat_id)>1)
	{
		
		$sql="DELETE FROM trl_package_category_images WHERE pkg_cat_image_id=$id";
		dbQuery($sql);
		return "success";
		
	}
	return "error";
	}	
		
?>