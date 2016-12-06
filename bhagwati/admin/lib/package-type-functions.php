<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
	
function listPackageTypes(){
	
	try
	{
		
		$sql="SELECT package_type_id,package_type
		      FROM trl_package_type ORDER BY package_type";
            $result=dbQuery($sql);
            $resultArray=dbResultToArray($result);
            return $resultArray;
	}
	catch(Exception $e)
	{
	}
	
}	

function insertPackageType($name){
	
	try
	{
		 $name=clean_data($name);
		 $name = ucfirst(strtolower($name));
		  $duplicate=checkForDuplicatePackageType($name);
          
            if(validateForNull($name)  && !$duplicate )
            {
				
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="INSERT INTO
		      trl_package_type (package_type)
			  VALUES
			  ('$name')";
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

function deletePackageType($id){
	
	try
	{
		
		 try
        {
            
            if(checkForNumeric($id) && !checkIPackageTypeInUse($id))
            {
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="DELETE FROM
			  trl_package_type
			  WHERE package_type_id=$id";
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

function checkForDuplicatePackageType($name,$id=false)
{
	 $sql="SELECT package_type_id 
			  FROM 
			  trl_package_type
			  WHERE package_type='$name' ";
            
            if($id==false)$sql=$sql."";
            else $sql=$sql." AND package_type_id!=$id";
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

function updatePackageType($name){
	
	try
	{
		 $name=clean_data($name);
		 $name = ucfirst(strtolower($name));
			
            $duplicate=checkForDuplicatePackageType($name);
	 if(validateForNull($name)  && !$duplicate)
            {
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="UPDATE trl_package_type
			  SET package_type='$name'
			  WHERE package_type_id=$id";
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

function getPackageTypeById($id){
	try
	{
		$sql="SELECT package_type
		      FROM trl_package_type WHERE package_type_id = $id  ORDER BY package_type";
            $result=dbQuery($sql);
            $resultArray=dbResultToArray($result);
            return $resultArray[0][0];
		
	}
	catch(Exception $e)
	{
	}
	
}	

 function checkIPackageTypeInUse($id)
    {
        $sql="SELECT package_type_id
	      FROM trl_rel_package_type
		  WHERE package_type_id=$id LIMIT 0, 1";
        $result=dbQuery($sql);
        
        if(dbNumRows($result)>0)
        {
            return true;
        }

        return false;
    }
	
function insertPackageTypesForPackage($package_type_id,$price,$package_id){
	
	try
	{
		
          
            if(validateForNull($package_type_id[0],$price[0]) && checkForNumeric($package_id))
            {
				
				for($i=0;$i<count($package_type_id);$i++)
				{
				$p_t_id=$package_type_id[$i];
				$pr=$price[$i];	
			
				insertPackageTypeForPackage($p_t_id,$pr,$package_id);	
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

function insertPackageTypeForPackage($package_type_id,$price,$package_id)
{
	
	if(checkForNumeric($package_id,$package_type_id,$price))
	{
		$sql="INSERT INTO trl_rel_package_type (package_id, package_type_id, price) VALUES($package_id,$package_type_id,$price)";
		dbQuery($sql);
		}
	
	}	

function deletePackageTypesForPackage($id){
	
	try
	{
		
		 try
        {
            
            if(checkForNumeric($id) )
            {
               
                $sql="DELETE FROM
			  trl_rel_package_type
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

function getPackageTypeForPackage($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT trl_package_type.package_type_id, package_type, price FROM trl_rel_package_type, trl_package_type WHERE package_id = $id AND trl_package_type.package_type_id = trl_rel_package_type.package_type_id";
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