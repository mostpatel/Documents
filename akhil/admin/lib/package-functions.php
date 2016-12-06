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



function listPackagesForPackageCategoryId($id)

    {

        try

        {

            $sql="SELECT trl_package.package_id, package_name, days, nights, currency, places, thumb_href, created_by, last_updated_by, date_added, date_modified, currency, from_location, to_location, tour_code
		      FROM trl_package, trl_rel_package_package_category WHERE pkg_cat_id = $id AND trl_package.package_id = trl_rel_package_package_category.package_id ORDER BY package_name";
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



function getPackageCategoriesForPackageId($id)

{

	if(checkForNumeric($id))

	{

		$sql="SELECT pkg_cat_id FROM trl_rel_package_package_category WHERE package_id =$id";

		$result=dbQuery($sql);

		$resultArray=dbResultToArray($result);

		return $resultArray;

		}

	

}	



function getPackageCategoryIDsForPackageId($id)

{

	if(checkForNumeric($id))

	{

		$sql="SELECT pkg_cat_id FROM trl_rel_package_package_category WHERE package_id =$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		$returnArray = array();
		foreach($resultArray as $location)
		{

			$location_id = $location['pkg_cat_id'];
			$returnArray[]=$location_id;	

		}
		return $returnArray;

	}

	

}	


function listPackagesForHotelId($id)

    {

        try

        {

            $sql="SELECT trl_package.package_id, package_name, days, nights, currency, places, thumb_href, created_by, last_updated_by, date_added, date_modified, currency
		      FROM trl_package, trl_rel_package_hotel WHERE hotel_package_id = $id AND trl_package.package_id = trl_rel_package_hotel.package_id ORDER BY package_name";
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



function getHotelsForPackageId($id)

{

	if(checkForNumeric($id))

	{

		$sql="SELECT hotel_package_id FROM trl_rel_package_hotel WHERE package_id =$id";

		$result=dbQuery($sql);

		$resultArray=dbResultToArray($result);

		return $resultArray;

		}

	

}	



function getHotelIDsForPackageId($id)

{

	if(checkForNumeric($id))

	{

		$sql="SELECT hotel_package_id FROM trl_rel_package_hotel WHERE package_id =$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		$returnArray = array();
		foreach($resultArray as $location)
		{

			$location_id = $location['hotel_package_id'];
			$returnArray[]=$location_id;	

		}
		return $returnArray;

	}

	

}	

function getTourDatesForPackageId($id)

{

	if(checkForNumeric($id))

	{

		$sql="SELECT package_date FROM trl_package_dates WHERE package_id =$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		$returnArray = array();
		foreach($resultArray as $location)
		{

			$location_id = $location['package_date'];
			$returnArray[]=$location_id;	

		}
		return $returnArray;

	}

	

}	

function getTourDatesForPackageIdBetweenDates($id,$from,$to)

{

	if(checkForNumeric($id))

	{

		$sql="SELECT GROUP_CONCAT(DAY(package_date)) as dates, MONTHNAME(package_date) as month_name, YEAR(package_date) as year FROM trl_package_dates WHERE package_id =$id AND package_date >= '$from' AND package_date <='$to' GROUP BY MONTH(package_date), YEAR(package_date) ORDER BY  YEAR(package_date), MONTH(package_date)";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		$returnArray = array();
		return $resultArray;

	}

	

}	


function getTourDatesStringForPackageId($id)

{

	if(checkForNumeric($id))

	{

		$sql="SELECT package_date FROM trl_package_dates WHERE package_id =$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		$returnArray = array();
		foreach($resultArray as $location)
		{
			$location_id = $location['package_date'];
			$returnArray[]=date('d/m/Y',strtotime($location_id));	

		}
	
		return implode(',',$returnArray);

	}

	

}	


function getTourDatesStringForPackageIdDatePickerFormat($id)

{

	if(checkForNumeric($id))

	{

		$sql="SELECT package_date FROM trl_package_dates WHERE package_id =$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		$returnArray = array();
		foreach($resultArray as $location)
		{
			$location_id = $location['package_date'];
			$returnArray[]=date('m/d/Y',strtotime($location_id));	

		}
	
		return implode(',',$returnArray);

	}

	

}	


	





    function insertPackage($name,$location_array,$places,$days,$nights, $currency, $thumb_img,$itenary_heading,$itenary_description,$package_dates,$inclusions,$exclusions,$package_category_array,$terms,$imp_note,$itenary_sections,$tour_code,$ind_cost_heading,$vehicle_cost_heading,$from_loc,$to_loc)

    {

        try

        {

			

            $name=clean_data($name);

			$places=clean_data($places);

			$days=clean_data($days);

			$nights=clean_data($nights);

            $name = ucfirst(strtolower($name));

			$inclusions = mysql_real_escape_string($inclusions);
			$exclusions = mysql_real_escape_string($exclusions);
			$terms = mysql_real_escape_string($terms);
			
			$from_loc = clean_data($from_loc);
			$to_loc = clean_data($to_loc);
			
			$ind_cost_heading = clean_data($ind_cost_heading);
			
			$vehicle_cost_heading = clean_data($vehicle_cost_heading);
			
			$tour_code = clean_data($tour_code);
			
			if(!validateForNull($places))
			$places="NA";

			
			
			$package_dates_array = explode(',',$package_dates);
			
            if(validateForNull($name,$places) && checkForNumeric($days,$nights,$location_array[0]))

            {

				

                $admin_id=$_SESSION['adminSession']['admin_id'];

                $sql="INSERT INTO

		      trl_package (package_name, days, nights, itenary_sections, currency, places, thumb_href, inclusions, exclusions, terms_and_conditions, imp_note, created_by, last_updated_by, date_added, date_modified, `tour_code`, `ind_cost_heading`, `vehicle_cost_heading`, `from_location`, `to_location`)

			  VALUES

			  ('$name', $days, $nights, $itenary_sections, $currency, '$places', 'NA', '$inclusions', '$exclusions', '$terms', '$imp_note',  $admin_id, $admin_id, NOW(), NOW() , '$tour_code', '$ind_cost_heading', '$vehicle_cost_heading', '$from_loc', '$to_loc')";

			 
				
                $result=dbQuery($sql);

				$package_id=dbInsertId();

				insertPackageTypesForPackage($package_types,$price_array,$package_id);
				addLocationsToPackage($package_id,$location_array);
				addPackageCategoriesToPackage($package_id,$package_category_array);
				addPackageDatesToPackage($package_id,$package_dates_array);
				insertItenarysForPackage($itenary_heading,$itenary_description,$package_id);

				$location_name=str_replace(" - ","_",$name);
				$location_name=str_replace("-","_",$name);
				$location_name=str_replace(" ","",$name);
				$img_href = addPackageThumbnail($location_name."_".$package_id,$thumb_img);

				 

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
	
	

function addPackageCategoriesToPackage($id,$package_category_array)
{

	if(checkForNumeric($id))

	{

		foreach($package_category_array as $location)
		{

			insertPackageCategoryToPackage($id,$location);

			}

		

		}

	

	}	



function insertPackageCategoryToPackage($package_id,$location_id)

{

	if(checkForNumeric($location_id,$package_id))
	{
	$sql="INSERT INTO trl_rel_package_package_category (package_id,pkg_cat_id) VALUES ($package_id,$location_id)";	
	dbQuery($sql);

	}

}	



function deletePackageCategoriesForPackage($id)

{

	if(checkForNumeric($id))
	{

		$sql="DELETE FROM trl_rel_package_package_category WHERE package_id = $id";
		dbQuery($sql);

		}

	

	} 
	
function addPackageDatesToPackage($id,$location_array)
{

	if(checkForNumeric($id))

	{

		foreach($location_array as $location)

		{

			insertPackageDateToPackage($id,$location);

			}

		

		}

	

	}	



function insertPackageDateToPackage($package_id,$date)

{

	if(checkForNumeric($package_id) && validateForNull($date))
	{
		
	if(isset($date) && validateForNull($date))
{
	    $date = str_replace('/', '-', $date);
		$date=date('Y-m-d',strtotime($date));
	}	

	$sql="INSERT INTO trl_package_dates (package_id,package_date) VALUES ($package_id,'$date')";	

	dbQuery($sql);

	}

}	



function deletePackageDatesForPackage($id)

{

	if(checkForNumeric($id))

	{

		$sql="DELETE FROM trl_package_dates WHERE package_id = $id";

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
				deletePackageCategoriesForPackage($id);
				deletePackageDatesForPackage($id);
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





function updatePackage($id,$name,$location_array,$places,$days,$nights, $currency, $thumb_img,$itenary_heading,$itenary_description,$package_dates,$inclusions,$exclusions,$package_category_array,$terms,$imp_note,$itenary_sections,$tour_code,$ind_cost_heading,$vehicle_cost_heading,$from_loc,$to_loc)
    {

        try

        {

			

            $name=clean_data($name);

			$places=clean_data($places);

			$days=clean_data($days);

			$nights=clean_data($nights);

            $name = ucfirst(strtolower($name));

			$inclusions = mysql_real_escape_string($inclusions);
			$exclusions = mysql_real_escape_string($exclusions);
			$terms = mysql_real_escape_string($terms);
			
			$from_loc = clean_data($from_loc);
			$to_loc = clean_data($to_loc);
			
			$ind_cost_heading = clean_data($ind_cost_heading);
			
			$vehicle_cost_heading = clean_data($vehicle_cost_heading);
			
			$tour_code = clean_data($tour_code);

			
			$package_dates_array = explode(',',$package_dates);
            if(validateForNull($name,$places) && checkForNumeric($days,$nights,$location_array[0],$id,$currency))
            {

        

                $admin_id=$_SESSION['adminSession']['admin_id'];

                $sql="UPDATE 

			   trl_package SET package_name = '$name', days = $days, nights = $nights, itenary_sections = $itenary_sections, places = '$places', inclusions = '$inclusions', exclusions = '$exclusions', terms_and_conditions = '$terms', imp_note = '$imp_note', last_updated_by = $admin_id, date_modified = NOW(), currency = $currency, `tour_code` = '$tour_code', `ind_cost_heading` = '$ind_cost_heading', `vehicle_cost_heading` = '$vehicle_cost_heading', `from_location` = '$from_loc', `to_location` = '$to_loc'

			  WHERE package_id=$id";

                dbQuery($sql);

				

				deletePackageTypesForPackage($id);
				insertPackageTypesForPackage($package_types,$price_array,$id);
				

				deleteLocationsForPackage($id);
				addLocationsToPackage($id,$location_array);

				deletePackageCategoriesForPackage($id);
				addPackageCategoriesToPackage($id,$package_category_array);
				
				deletePackageDatesForPackage($id);
				addPackageDatesToPackage($id,$package_dates_array);
				

				deleteItenaryForPackage($id);
				insertItenarysForPackage($itenary_heading,$itenary_description,$id);

				

				

				if(isset($thumb_img['tmp_name']) && validateForNull($thumb_img['tmp_name']))

				{
					$location_name=str_replace(" - ","_",$name);
				$location_name=str_replace("-","_",$name);
				$location_name=str_replace(" ","",$name);
					$img_href = addPackageThumbnail($location_name."_".$id,$thumb_img);

				 

				 if(!$img_href)

				 {

				//	 deletePackage($id);

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

        $sql="SELECT package_id, package_name, days, nights, places, thumb_href, inclusions, exclusions, itenary_sections, created_by, last_updated_by, date_added, date_modified, IF(currency=1,'INR',IF(currency=2,'USD','EURO')) as currency, currency as  currency_id, terms_and_conditions, imp_note, tour_code, from_location, to_location, ind_cost_heading, vehicle_cost_heading

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

function getUpcomingFivePackages()
{


	 $sql="SELECT pkg_cat_id, MIN(package_date) as upcoming_package_date
		      FROM trl_package, trl_rel_package_package_category, trl_package_dates
 WHERE trl_package.package_id=trl_rel_package_package_category.package_id AND trl_package.package_id = trl_package_dates.package_id AND package_date > NOW() GROUP BY trl_rel_package_package_category.pkg_cat_id ORDER BY package_date  LIMIT 0,5";

	$result = dbQuery($sql);

	$resultArray = dbResultToArray($result);
$i=0;
	foreach($resultArray  as $re)
	{
		
		 $sql="SELECT trl_package.package_id
		      FROM trl_package, trl_rel_package_package_category, trl_package_dates
 WHERE trl_package.package_id=trl_rel_package_package_category.package_id AND trl_package.package_id = trl_package_dates.package_id AND package_date  = '".$re['upcoming_package_date']."' AND trl_rel_package_package_category.pkg_cat_id = ".$re['pkg_cat_id']." ";
    $result = dbQuery($sql);

	$reArray = dbResultToArray($result);
	if(checkForNumeric($reArray[0][0]))
	$resultArray[$i]['package_id'] = $reArray[0][0];	
	$i++;
	}
	
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