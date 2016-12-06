<?php 
require_once("cg.php");
require_once("common.php");
require_once("city-functions.php");
require_once("area-functions.php");
require_once("image-functions.php");
require_once("common.php");
require_once("bd.php");

function listSuppliers()
{
	
	try
	{
		$sql="SELECT supplier_id, supplier_name, supplier_email, supplier_phone
			  FROM ems_sub_cat_suppliers";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
		  
	}
	catch(Exception $e)
	{
	}
	
}	


		
function insertSupplier($name, $email, $contact_no)
{	
	try
	{
		
		$name=clean_data($name);
		$email=clean_data($email);
		$contact_no=clean_data($contact_no);
		
		if(!validateForNull($email))
		$email="NA";
		
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		
		
		
			$name = ucwords(strtolower($name));
			
	       if(validateForNull($name,$email) && $contact_no!=null && !empty($contact_no) && !checkForDuplicateSupplierContactNo($contact_no) && ($email == "NA" || !checkForDuplicateSupplierEmail($email)))
			{
				
				$sql="INSERT INTO ems_sub_cat_suppliers(supplier_name, supplier_email, supplier_phone)				
				      VALUES ('$name', '$email', $contact_no)";
			    
			    
				$result=dbQuery($sql);
				return "success";
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

function deleteSupplier($id){
	
	try
	{
		if(!checkifSupplierInUse($id))
		{
		$sql="DELETE FROM ems_sub_cat_suppliers 
		      WHERE supplier_id=$id";
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


function checkifSupplierInUse($id)
{
	
	if(checkForNumeric($id))
	{
	$sql="";
	$result=dbQuery($sql);	  
	if(dbNumRows($result)>0)
	return true;
	else 
	return false;
	}
	
}		

function updateSupplier($id,$name,$email,$contact_no)
{
	
	try
	{
		
		
		$name=clean_data($name);
		$email=clean_data($email);
		$contact_no=clean_data($contact_no);
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		
			$name = ucwords(strtolower($name));
			if(!validateForNull($email))
		    $email="NA";
			
			if(validateForNull($name) && $contact_no!=null && !empty($contact_no))
			{
				
				$sql="UPDATE ems_sub_cat_suppliers
				     SET supplier_name = '$name', supplier_email = '$email', supplier_phone = $contact_no
					 WHERE supplier_id=$id";
					
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

function getSupplierById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT supplier_id, supplier_name, supplier_email, supplier_phone
			  FROM ems_sub_cat_suppliers
			  WHERE supplier_id=$id";
		
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return false;
		}
        return false;
	}
	catch(Exception $e)
	{
	}
	
}






function checkForDuplicateSupplierContactNo($phone_no)
{
	try
	{
		
		if(checkForNumeric($phone_no) && $phone_no!=1234567890)
		{
		$sql="SELECT supplier_id
		      FROM ems_sub_cat_suppliers
			  WHERE 
			 supplier_phone = $phone_no";
			 
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$_SESSION['error']['submit_error']="Duplicate Entry!";
			return true;
		}
		else
		{
			return false;
		}	
		
		}
		return false;
	}
	
	catch(Exception $e)
	{
	}
	
	}	

function checkForDuplicateSupplierEmail($email_address)
{
	try
	{
		
		$sql="SELECT supplier_id
		      FROM ems_sub_cat_suppliers
			  WHERE 
			  supplier_email = '$email_address'";
			  
	   
		
		 
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		
		{
			$_SESSION['error']['submit_error']="Duplicate Entry!";
			return true;
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

	

function getSupplierIdFromContactNo($no)
{
	if(checkForNumeric($no))
	{
		$sql="SELECT supplier_id FROM ems_sub_cat_suppliers
				WHERE supplier_phone=$no";
				$result=dbQuery($sql);	  
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
	}
}

function getSupplierIdFromEmail($email)
{
	if(validateForNull($email))
	{
		$sql="SELECT supplier_id FROM ems_sub_cat_suppliers
				WHERE supplier_email='$email'";
				$result=dbQuery($sql);	  
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
	}
}

function getSupplierIdFromSupplierName($name)
{
	if(validateForNull($name))
	{
		$sql="SELECT supplier_id FROM ems_sub_cat_suppliers
				WHERE supplier_name='$name'";
				$result=dbQuery($sql);	  
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0 && dbNumRows($result)==1)
		return $resultArray[0][0];
		else if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
	}
}






			
?>