<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
	
function listInquiryHotelTypes(){
	
	try
	{
		
		$sql="SELECT hotel_type_id,hotel_type
		      FROM trl_inquiry_hotel_type ORDER BY hotel_type_id";
            $result=dbQuery($sql);
            $resultArray=dbResultToArray($result);
            return $resultArray;
	}
	catch(Exception $e)
	{
	}
	
}	

function insertInquiryHotelType($name){
	
	try
	{
		 $name=clean_data($name);
		 $name = ucfirst(strtolower($name));
		  $duplicate=checkForDuplicateInquiryHotelType($name);
          
            if(validateForNull($name)  && !$duplicate )
            {
				
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="INSERT INTO
		      trl_inquiry_hotel_type (hotel_type)
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

function deleteInquiryHotelType($id){
	
	try
	{
		
		 try
        {
            
            if(checkForNumeric($id) && !checkIInquiryHotelTypeInUse($id))
            {
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="DELETE FROM
			  trl_inquiry_hotel_type
			  WHERE hotel_type_id=$id";
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

function checkForDuplicateInquiryHotelType($name,$id=false)
{
	 $sql="SELECT hotel_type_id 
			  FROM 
			  trl_inquiry_hotel_type
			  WHERE hotel_type='$name' ";
            
            if($id==false)$sql=$sql."";
            else $sql=$sql." AND hotel_type_id!=$id";
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

function updateInquiryHotelType($name){
	
	try
	{
		 $name=clean_data($name);
		 $name = ucfirst(strtolower($name));
			
            $duplicate=checkForDuplicateInquiryHotelType($name);
	 if(validateForNull($name)  && !$duplicate)
            {
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="UPDATE trl_inquiry_hotel_type
			  SET hotel_type='$name'
			  WHERE hotel_type_id=$id";
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

function getInquiryHotelTypeById($id){
	try
	{
		$sql="SELECT hotel_type
		      FROM trl_inquiry_hotel_type WHERE hotel_type_id = $id  ORDER BY hotel_type";
            $result=dbQuery($sql);
            $resultArray=dbResultToArray($result);
            return $resultArray[0][0];
		
	}
	catch(Exception $e)
	{
	}
	
}	

 function checkIInquiryHotelTypeInUse($id)
    {
        $sql="SELECT hotel_type_id
	      FROM trl_rel_hotel_type
		  WHERE hotel_type_id=$id LIMIT 0, 1";
        $result=dbQuery($sql);
        
        if(dbNumRows($result)>0)
        {
            return true;
        }

        return false;
    }
	
	
?>