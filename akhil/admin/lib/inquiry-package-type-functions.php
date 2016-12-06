<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
	
function listInquiryPackageTypes(){
	
	try
	{
		
		$sql="SELECT pck_type_id,package_type
		      FROM trl_inquiry_pck_type ORDER BY pck_type_id";
            $result=dbQuery($sql);
            $resultArray=dbResultToArray($result);
            return $resultArray;
	}
	catch(Exception $e)
	{
	}
	
}	

function insertInquiryPackageType($name){
	
	try
	{
		 $name=clean_data($name);
		 $name = ucfirst(strtolower($name));
		  $duplicate=checkForDuplicateInquiryPackageType($name);
          
            if(validateForNull($name)  && !$duplicate )
            {
				
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="INSERT INTO
		      trl_inquiry_pck_type (package_type)
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

function deleteInquiryPackageType($id){
	
	try
	{
		
		 try
        {
            
            if(checkForNumeric($id) && !checkIInquiryPackageTypeInUse($id))
            {
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="DELETE FROM
			  trl_inquiry_pck_type
			  WHERE pck_type_id=$id";
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

function checkForDuplicateInquiryPackageType($name,$id=false)
{
	 $sql="SELECT pck_type_id 
			  FROM 
			  trl_inquiry_pck_type
			  WHERE package_type='$name' ";
            
            if($id==false)$sql=$sql."";
            else $sql=$sql." AND pck_type_id!=$id";
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

function updateInquiryPackageType($name){
	
	try
	{
		 $name=clean_data($name);
		 $name = ucfirst(strtolower($name));
			
            $duplicate=checkForDuplicateInquiryPackageType($name);
	 if(validateForNull($name)  && !$duplicate)
            {
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="UPDATE trl_inquiry_pck_type
			  SET package_type='$name'
			  WHERE pck_type_id=$id";
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

function getInquiryPackageTypeById($id){
	try
	{
		$sql="SELECT package_type
		      FROM trl_inquiry_pck_type WHERE pck_type_id = $id  ORDER BY package_type";
            $result=dbQuery($sql);
            $resultArray=dbResultToArray($result);
            return $resultArray[0][0];
		
	}
	catch(Exception $e)
	{
	}
	
}	

 function checkIInquiryPackageTypeInUse($id)
    {
        $sql="SELECT pck_type_id
	      FROM trl_rel_package_type
		  WHERE pck_type_id=$id LIMIT 0, 1";
        $result=dbQuery($sql);
        
        if(dbNumRows($result)>0)
        {
            return true;
        }

        return false;
    }
	

?>