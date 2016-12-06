<?php 

    require_once("cg.php");

    require_once("bd.php");

    require_once("common.php");

	require_once("image-functions.php");

	require_once("package-itenary-functions.php");

	require_once("package-type-functions.php");



function listPackages()

    {

        try

        {

            $sql="SELECT package_id, package_name, days, nights, currency, places, thumb_href, created_by, last_updated_by, date_added, date_modified, currency

		      FROM trl_package ORDER BY package_name";

            $result=dbQuery($sql);

            

			$resultArray=dbResultToArray($result);

			

			for($i=0;$i<count($resultArray);$i++)

			{

			$package_id=$resultArray[$i]['package_id'];	

			$locations=getLocationsForPackageId($package_id);

			$resultArray[$i]['locations']=$locations;	

			}

			

            return $resultArray;

        }



        catch(Exception $e)

        {

        }



}

	

function listPackagesForLocationId($id)

    {

        try

        {

            $sql="SELECT trl_package.package_id, package_name, days, nights, currency, places, thumb_href, created_by, last_updated_by, date_added, date_modified, currency

		      FROM trl_package, trl_rel_package_location WHERE location_id = $id AND trl_package.package_id = trl_rel_package_location.package_id ORDER BY package_name";

            $result=dbQuery($sql);

            

			$resultArray=dbResultToArray($result);

			

			for($i=0;$i<count($resultArray);$i++)

			{

			$package_id=$resultArray[$i]['package_id'];	

			}

			

            return $resultArray;

        }



        catch(Exception $e)

        {

        }



}



function getLocationsForPackageId($id)

{

	if(checkForNumeric($id))

	{

		$sql="SELECT location_id FROM trl_rel_package_location WHERE package_id =$id";

		$result=dbQuery($sql);

		$resultArray=dbResultToArray($result);

		return $resultArray;

		}

	

}	



function getLocationIDsForPackageId($id)

{

	if(checkForNumeric($id))

	{

		$sql="SELECT location_id FROM trl_rel_package_location WHERE package_id =$id";

		$result=dbQuery($sql);

		$resultArray=dbResultToArray($result);

		$returnArray = array();

		

		foreach($resultArray as $location)

		{

			$location_id = $location['location_id'];

			$returnArray[]=$location_id;	

		}

		

		return $returnArray;

	}

	

}	



	





    function insertPackage($name,$location_array,$places,$days,$nights, $currency, $thumb_img,$itenary_heading,$itenary_description,$package_types,$price_array,$inclusions,$exclusions)

    {

        try

        {

			

            $name=clean_data($name);

			$places=clean_data($places);

			$days=clean_data($days);

			$nights=clean_data($nights);

            $name = ucfirst(strtolower($name));

			$inclusions = clean_data($inclusions);

			$exclusions = clean_data($exclusions);

			

			

            if(validateForNull($name,$places) && checkForNumeric($days,$nights,$location_array[0]))

            {

				

                $admin_id=$_SESSION['adminSession']['admin_id'];

                $sql="INSERT INTO

		      trl_package (package_name, days, nights, currency, places, thumb_href, inclusions, exclusions, created_by, last_updated_by, date_added, date_modified)

			  VALUES

			  ('$name', $days, $nights, $currency, '$places', 'NA', '$inclusions', '$exclusions',  $admin_id, $admin_id, NOW(), NOW())";

			 

                $result=dbQuery($sql);

				$package_id=dbInsertId();

				insertPackageTypesForPackage($package_types,$price_array,$package_id);

				addLocationsToPackage($package_id,$location_array);

				insertItenarysForPackage($itenary_heading,$itenary_description,$package_id);

				

				$img_href = addPackageThumbnail($package_id,$thumb_img);

				 

				 if(!$img_href)

				 {

					 deletePackage($package_id);

					

					 return "img_error";

					 }

				else

				{

					$sql="UPDATE trl_package SET thumb_href = '$img_href' WHERE package_id=$package_id";

					dbQuery($sql);

					}	 

                return $package_id;

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

	

function addLocationsToPackage($id,$location_array)

{

	if(checkForNumeric($id))

	{

		foreach($location_array as $location)

		{

			insertLocationToPackage($id,$location);

			}

		

		}

	

	}	



function insertLocationToPackage($package_id,$location_id)

{

	if(checkForNumeric($location_id,$package_id))

	{

	$sql="INSERT INTO trl_rel_package_location (package_id,location_id) VALUES ($package_id,$location_id)";	

	dbQuery($sql);

	}

}	



function deleteLocationsForPackage($id)

{

	if(checkForNumeric($id))

	{

		$sql="DELETE FROM trl_rel_package_location WHERE package_id = $id";

		dbQuery($sql);

		}

	

	} 

 

function deletePackage($id)

    {

        try

        {

            

            if(checkForNumeric($id))

            {

				deleteItenaryForPackage($id);

				deleteFeaturedPackage($id);

				deleteLocationsForPackage($id);

				deletePackageTypesForPackage($id);

                $admin_id=$_SESSION['adminSession']['admin_id'];

                $sql="DELETE FROM

			  trl_package

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





function updatePackage($id,$name,$location_array,$places,$days,$nights,$thumb_img,$itenary_heading,$itenary_description,$package_types,$price_array,$inclusions,$exclusions,$currency)

    {

        try

        {

			

            $name=clean_data($name);

			$places=clean_data($places);

			$days=clean_data($days);

			$nights=clean_data($nights);

            $name = ucfirst(strtolower($name));

			$inclusions = clean_data($inclusions);

			$exclusions = clean_data($exclusions);

			

            if(validateForNull($name,$places) && checkForNumeric($days,$nights,$location_array[0],$id,$currency))

            {

         

                $admin_id=$_SESSION['adminSession']['admin_id'];

                $sql="UPDATE 

			   trl_package SET package_name = '$name', days = $days, nights = $nights, places = '$places', inclusions = '$inclusions', exclusions = '$exclusions', last_updated_by = $admin_id, date_modified = NOW(), currency = $currency

			  WHERE package_id=$id";

                dbQuery($sql);

				

				deletePackageTypesForPackage($id);

				insertPackageTypesForPackage($package_types,$price_array,$id);

				

				deleteLocationsForPackage($id);

				addLocationsToPackage($id,$location_array);

				

				deleteItenaryForPackage($id);

				insertItenarysForPackage($itenary_heading,$itenary_description,$id);

				

				

				if(isset($thumb_img['tmp_name']) && validateForNull($thumb_img['tmp_name']))

				{

					$img_href = addPackageThumbnail($id,$thumb_img);

				 

				 if(!$img_href)

				 {

					 deletePackage($id);

					 return "img_error";

					 }

				else

				{

					$sql="UPDATE trl_package SET thumb_href = '$img_href' WHERE package_id=$id";

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





function checkForDuplicatePackage($name,$location_array,$id=false)

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



   

function getPackageByID($id)

    {

        $sql="SELECT package_id, package_name, days, nights, places, thumb_href, inclusions, exclusions, created_by, last_updated_by, date_added, date_modified, IF(currency=1,'INR',IF(currency=2,'USD','EURO')) as currency, currency as  currency_id

			  FROM 

			  trl_package 

			  WHERE package_id=$id";

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

 

    function checkIfPackageInUse($id)

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



function insertFeaturedPackage($package_id)

{

	if(checkForNumeric($package_id) && getNumberOfFeaturedPackages()<3)

	{

	$sql="UPDATE trl_package SET featured=1 WHERE package_id = $package_id";

	dbQuery($sql);

	return "success";

	}

	return "error";

	}



function deleteFeaturedPackage($package_id)

{

	if(checkForNumeric($package_id) && getNumberOfFeaturedPackages()>0)

	{

	$sql="UPDATE trl_package SET featured=0 WHERE package_id = $package_id";

	dbQuery($sql);

	return "success";

	}

	return "error";

}



function getIfPackageFeaturedOrNot($package_id)

{

	if(checkForNumeric($package_id))

	{

	$sql="SELECT package_id FROM trl_package WHERE featured=1 AND package_id = $package_id";

	$result = dbQuery($sql);

	$resultArray = dbResultToArray($result);

	if(dbNumRows($result)>0)

	return true;

	}

	return false;

	

	}



function getAllFeaturedPackages()

{

	

	 $sql="SELECT package_id, package_name, days, nights, places, thumb_href, created_by, last_updated_by, date_added, date_modified

		      FROM trl_package WHERE featured=1 ORDER BY package_name";

	$result = dbQuery($sql);

	$resultArray = dbResultToArray($result);

	if(dbNumRows($result)>0)

	return $resultArray;

	else

	return false;

	}	

	

function getNumberOfFeaturedPackages()

{

	

	$sql="SELECT COUNT(package_id) FROM trl_package WHERE featured=1";

	$result = dbQuery($sql);

	$resultArray = dbResultToArray($result);

	if(dbNumRows($result)>0)

	return $resultArray[0][0];

	else

	return 0;

	}			

?>