<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
	
function listInquiryLocTypes(){
	
	try
	{
		
		$sql="SELECT loc_type_id,loc_type
		      FROM trl_inquiry_loc_type ORDER BY loc_type_id";
            $result=dbQuery($sql);
            $resultArray=dbResultToArray($result);
            return $resultArray;
	}
	catch(Exception $e)
	{
	}
	
}	

function insertInquiryLocType($name){
	
	try
	{
		 $name=clean_data($name);
		 $name = ucfirst(strtolower($name));
		  $duplicate=checkForDuplicateInquiryLocType($name);
          
            if(validateForNull($name)  && !$duplicate )
            {
				
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="INSERT INTO
		      trl_inquiry_loc_type (loc_type)
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

function deleteInquiryLocType($id){
	
	try
	{
		
		 try
        {
            
            if(checkForNumeric($id) && !checkIInquiryLocTypeInUse($id))
            {
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="DELETE FROM
			  trl_inquiry_loc_type
			  WHERE loc_type_id=$id";
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

function checkForDuplicateInquiryLocType($name,$id=false)
{
	 $sql="SELECT loc_type_id 
			  FROM 
			  trl_inquiry_loc_type
			  WHERE loc_type='$name' ";
            
            if($id==false)$sql=$sql."";
            else $sql=$sql." AND loc_type_id!=$id";
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

function updateInquiryLocType($name){
	
	try
	{
		 $name=clean_data($name);
		 $name = ucfirst(strtolower($name));
			
            $duplicate=checkForDuplicateInquiryLocType($name);
	 if(validateForNull($name)  && !$duplicate)
            {
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="UPDATE trl_inquiry_loc_type
			  SET loc_type='$name'
			  WHERE loc_type_id=$id";
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

function getInquiryLocTypeById($id){
	try
	{
		$sql="SELECT loc_type
		      FROM trl_inquiry_loc_type WHERE loc_type_id = $id  ORDER BY loc_type";
            $result=dbQuery($sql);
            $resultArray=dbResultToArray($result);
            return $resultArray[0][0];
		
	}
	catch(Exception $e)
	{
	}
	
}	

 function checkIInquiryLocTypeInUse($id)
    {
        $sql="SELECT loc_type_id
	      FROM trl_rel_loc_type
		  WHERE loc_type_id=$id LIMIT 0, 1";
        $result=dbQuery($sql);
        
        if(dbNumRows($result)>0)
        {
            return true;
        }

        return false;
    }
	
?>