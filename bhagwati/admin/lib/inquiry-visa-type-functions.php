<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
	
function listInquiryVisaTypes(){
	
	try
	{
		
		$sql="SELECT visa_type_id,visa_type
		      FROM trl_inquiry_visa_type ORDER BY visa_type_id";
            $result=dbQuery($sql);
            $resultArray=dbResultToArray($result);
            return $resultArray;
	}
	catch(Exception $e)
	{
	}
	
}	

function insertInquiryVisaType($name){
	
	try
	{
		 $name=clean_data($name);
		 $name = ucfirst(strtolower($name));
		  $duplicate=checkForDuplicateInquiryVisaType($name);
          
            if(validateForNull($name)  && !$duplicate )
            {
				
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="INSERT INTO
		      trl_inquiry_visa_type (visa_type)
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

function deleteInquiryVisaType($id){
	
	try
	{
		
		 try
        {
            
            if(checkForNumeric($id) && !checkIInquiryVisaTypeInUse($id))
            {
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="DELETE FROM
			  trl_inquiry_visa_type
			  WHERE visa_type_id=$id";
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

function checkForDuplicateInquiryVisaType($name,$id=false)
{
	 $sql="SELECT visa_type_id 
			  FROM 
			  trl_inquiry_visa_type
			  WHERE visa_type='$name' ";
            
            if($id==false)$sql=$sql."";
            else $sql=$sql." AND visa_type_id!=$id";
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

function updateInquiryVisaType($name){
	
	try
	{
		 $name=clean_data($name);
		 $name = ucfirst(strtolower($name));
			
            $duplicate=checkForDuplicateInquiryVisaType($name);
	 if(validateForNull($name)  && !$duplicate)
            {
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="UPDATE trl_inquiry_visa_type
			  SET visa_type='$name'
			  WHERE visa_type_id=$id";
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

function getInquiryVisaTypeById($id){
	try
	{
		$sql="SELECT visa_type
		      FROM trl_inquiry_visa_type WHERE visa_type_id = $id  ORDER BY visa_type";
            $result=dbQuery($sql);
            $resultArray=dbResultToArray($result);
            return $resultArray[0][0];
		
	}
	catch(Exception $e)
	{
	}
	
}	

 function checkIInquiryVisaTypeInUse($id)
    {
        $sql="SELECT visa_type_id
	      FROM trl_rel_visa_type
		  WHERE visa_type_id=$id LIMIT 0, 1";
        $result=dbQuery($sql);
        
        if(dbNumRows($result)>0)
        {
            return true;
        }

        return false;
    }
	
?>