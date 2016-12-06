<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
	

function insertItenarysForPackage($heading,$description,$package_id){
	
	try
	{
		
          
            if(validateForNull($heading[1],$description[1]) && checkForNumeric($package_id))
            {
				
				for($i=0;$i<count($heading);$i++)
				{
				$headin=$heading[$i];
				$desc=$description[$i];	
				insertItenaryForPackage($headin,$desc,$package_id);	
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

function insertItenaryForPackage($heading,$description,$package_id)
{
	
	if(checkForNumeric($package_id) && validateForNull($heading) && validateForNull($description))
	{
		$heading = clean_data($heading);
		$description = clean_data($description);
		$sql="INSERT INTO trl_package_itenary (itenary_heading, itenary_description, package_id) VALUES('$heading','$description',$package_id)";
		dbQuery($sql);
		}
	
	}	

function deleteItenaryForPackage($id){
	
	try
	{
		
		 try
        {
            
            if(checkForNumeric($id) )
            {
               
                $sql="DELETE FROM
			  trl_package_itenary
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
	catch(Exception $e)
	{
	}
	
}	

function getItenaryForPackageId($package_id)
{
	if(is_numeric($package_id))
	{
		
		$sql="SELECT itenary_heading, itenary_description, package_id FROM trl_package_itenary WHERE package_id=$package_id ORDER BY itenary_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		{
			$resultArray=dbResultToArray($result);
			return $resultArray;
			}
		return false;	
		}
	
	}
	
?>