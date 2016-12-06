<?php 
require_once("cg.php");
require_once("common.php");
require_once("city-functions.php");
require_once("area-functions.php");
require_once("image-functions.php");
require_once("common.php");
require_once("bd.php");



		
function insertCustomer($name,$email="NA",$contact_no="NA", $prefix)
{	
	try
	{
		
		$name=clean_data($name);
		$email=clean_data($email);
		
		if(!validateForNull($email))
		$email="NA";
		
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		
		
		
			$name = ucwords(strtolower($name));
			
	       if(validateForNull($name,$email) && $contact_no!=null && !empty($contact_no) 
		       && !checkForDuplicateCustomerContactNo($contact_no) && ($email == "NA" || !checkForDuplicateCustomerEmail($email)))
			{
				
				$sql="INSERT INTO ems_customer(customer_name,customer_email,created_by,last_updated_by,date_added,date_modified, prefix_id)				
				      VALUES ('$name', '$email',$admin_id,$admin_id,NOW(),NOW(), $prefix)";
			    
			    
				
				
				
				$result=dbQuery($sql);
				$customer_id=dbInsertId();		
				addCutomerContactNo($customer_id, $contact_no);
				
				
				return $customer_id;
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

function deleteCustomer($id){
	
	try
	{
		if(!checkifCustomerInUse($id))
		{
		$sql="DELETE FROM ems_customer 
		      WHERE customer_id=$id";
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


function checkifCustomerInUse($id)
{
	
	if(checkForNumeric($id))
	{
	$sql="SELECT enquiry_form_id
	      FROM ems_enquiry_form
		  Where customer_id=$id";
	$result=dbQuery($sql);	  
	if(dbNumRows($result)>0)
	return true;
	else 
	return false;
	}
	
}		

function updateCustomer($id,$name,$email,$contact_no, $prefix)
{
	
	try
	{
		
		
		$name=clean_data($name);
		$email=clean_data($email);
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		
			$name = ucwords(strtolower($name));
			if(!validateForNull($email))
		    $email="NA";
			
			if(validateForNull($name) && $contact_no!=null && !empty($contact_no))
			{
				
				$sql="UPDATE ems_customer
				     SET customer_name = '$name', customer_email = '$email', last_updated_by = $admin_id, date_modified = NOW(), prefix_id = '$prefix'
					 WHERE customer_id=$id";
					
				$result=dbQuery($sql);
				
				deleteAllContactNoCustomer($id);
				addCutomerContactNo($id,$contact_no);
				
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

function getCustomerById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT customer_id, customer_name, customer_email, prefix_id
			  FROM ems_customer
			  WHERE customer_id=$id";
		
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


function getCustomerByUniqueEnquiryId($id)
{
	
	
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT customer_id
			  FROM ems_enquiry_form
			  WHERE unique_enquiry_id=$id";
			  
		
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		
		return $resultArray[0][0];
		}
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}		


function getCustomerByEnquiryId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT customer_id
			  FROM ems_enquiry_form
			  WHERE enquiry_form_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		$customerId=$resultArray[0][0];
		
		$customerDetails = getCustomerById($customerId);
		return $customerDetails;
		}
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateCustomerContactNo($phone_no_array)
{
	try
	{
		foreach($phone_no_array as $phone_no)
		{
		if(checkForNumeric($phone_no) && $phone_no!=1234567890 && $phone_no!=9999999999)
		{
		$sql="SELECT customer_id
		      FROM ems_customer_contact_no
			  WHERE 
			 customer_contact_no = $phone_no";
			 
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
	}
	catch(Exception $e)
	{
	}
	
	}	

function checkForDuplicateCustomerEmail($email_address)
{
	try
	{
		
		$sql="SELECT customer_id
		      FROM ems_customer
			  WHERE 
			  customer_email = '$email_address'";
			  
	   
		
		 
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

	
function addCutomerContactNo($customer_id,$contact_no)
{
	try
	{
		if(is_array($contact_no))
		{
			foreach($contact_no as $no)
			{
				if(checkForNumeric($no))
				{
				insertContactNoCustomer($customer_id,$no); 
				}
			}
		}
		else
		{
			
			if(checkForNumeric($contact_no))
				{
				insertContactNoCustomer($customer_id,$contact_no); 
				}
			
		}
	}
	catch(Exception $e)
	{
	}
}

function insertContactNoCustomer($id,$contact_no)
{
	try
	{
		
		if(checkForNumeric($id)==true && checkForNumeric($contact_no))
		{
			
		$sql="INSERT INTO ems_customer_contact_no
				      (customer_contact_no, customer_id)
					  VALUES
					  ('$contact_no', $id)";
		
				dbQuery($sql);
			  
		}
	}
	catch(Exception $e)
	{}
	
	
}
function deleteContactNoCustomer($id)
{
	try
	{
		$sql="DELETE FROM ems_customer_contact_no
			  WHERE customer_contact_no_id=$id";
		dbQuery($sql);	  
	}
	catch(Exception $e)
	{}
	
	
	
	}
function deleteAllContactNoCustomer($id)
{
	try
	{
		$sql="DELETE FROM ems_customer_contact_no
			  WHERE customer_id=$id";
		dbQuery($sql);
	}
	catch(Exception $e)
	{}
	
	
	
	}	
function updateContactNoCustomer($id,$contact_no)
{
	try
	{
		deleteAllContactNoCustomer($id);
		addCutomerContactNo($id,$contact_no);
	}
	catch(Exception $e)
	{}
	
	
	
	}

function getCustomerContactNo($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT customer_contact_no FROM ems_customer_contact_no
				WHERE customer_id=$id";
				$result=dbQuery($sql);	  
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
	}
    return false;
}


function getCustomerIdFromContactNo($no)
{
	if(checkForNumeric($no))
	{
		$sql="SELECT customer_id FROM ems_customer_contact_no
				WHERE customer_contact_no=$no";
				$result=dbQuery($sql);	  
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
	}
}

function getCustomerIdFromEmail($email)
{
	if(validateForNull($email))
	{
		$sql="SELECT customer_id FROM ems_customer
				WHERE customer_email='$email'";
				$result=dbQuery($sql);	  
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
	}
}

function getCustomerIdFromCustomerName($name)
{
	if(validateForNull($name))
	{
		$sql="SELECT customer_id FROM ems_customer
				WHERE customer_name='$name'";
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



function addCustomerProof($customer_id,$customer_name,$human_proof_type_id_array,$proof_no_array,$proof_img_array,$scanImgArray, $member)
{
	try
	{
		
		if($member == -1 || !validateForNull($member))
		{
		  $member = "NULL";	
		}
	
		
		
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		if(is_array($human_proof_type_id_array)) // if more than one proof submitted
		{
			$len=count($human_proof_type_id_array);
			
			for($i=0;$i<$len;$i++)
			{
								
								$human_proof_type_id=$human_proof_type_id_array[$i];
								
								if($human_proof_type_id>0 && (!(checkForNumeric($member) && getOnlyMemberProofDetailsByCustomerAndMemberIdAndProofTypeId($member,$human_proof_type_id)) || !getOnlyCustomerProofDetailsByCustomerIdAndProofTypeId($customer_id,$human_proof_type_id)) && (checkForImagesInArray($proof_img_array['name'][$i]) || ($proof_no_array[$i]!=null && $proof_no_array[$i]!="")))
								{
									$proof_no=$proof_no_array[$i];
									$proof_no=clean_data($proof_no);
									if($proof_no==null || $proof_no=="")
									$proof_no="NA";
									
								$sql="INSERT INTO ems_customer_proof
								     (human_proof_type_id, customer_proof_no, customer_id, member_id, created_by, last_updated_by, date_added, date_modified)
									 VALUES
									 ($human_proof_type_id, '$proof_no', $customer_id, $member, $admin_id, $admin_id, NOW(), NOW())";
								
								
								  $result=dbQuery($sql);
								  $proof_id=dbInsertId();
								  
							    addImagesToCustomerProof($customer_id,$customer_name,$human_proof_type_id,$proof_id,$proof_img_array,$i);
								
								if($scanImgArray!=false && isset($scanImgArray[$i]) && is_array($scanImgArray[$i]))
								{
									
									foreach($scanImgArray[$i] as $scanImage)
									{
										
									insertImageToCustomerProof($scanImage,$proof_id);
										
									}
								}
									
								}
								
							   
				
			}
			
			return "success";		
		}
		else // if only one proof submitted
		{
			if($human_proof_type_id_array>0 && (checkForImagesInArray($proof_img_array['name'][$i]) || ($proof_no_array[$i]!=null && $proof_no_array[$i]!="")))
								{
			$proof_no_array=clean_data($proof_no_array);							
			$sql="INSERT INTO ems_customer_proof
								     (human_proof_type_id, customer_proof_no, customer_id, member_id, created_by, last_updated_by, date_added, date_modified)
									 VALUES
									 ($human_proof_type_id_array, '$proof_no_array', $customer_id, $member, $admin_id, $admin_id, NOW(), NOW())";
								  
								  $result=dbQuery($sql);
								  $proof_id=dbInsertId();
								  addImagesToCustomerProof($customer_id,$customer_name,$human_proof_type_id_array,$proof_id,$proof_img_array,0);
								}
		}
	return "success";	
	}
	catch(Exception $e)
	{
		
	}
	
}	

function addImagesToCustomerProof($customer_id,$customer_name,$human_proof_type_id,$proof_id,$proof_img_array,$i){
	
	
	
	if(is_array($proof_img_array['name'][$i])) // if proof has more than one image
								  {
									 
									  $images_for_a_proof=count($proof_img_array['name'][$i]);
									  for($j=0;$j<$images_for_a_proof;$j++)
									  {
										  if($proof_img_array['name'][$i][$j]!="" &&  $proof_img_array['name'][$i][$j]!=null)
										  {
										   $imagee['name'] = $proof_img_array['name'][$i][$j];
										   $imagee['type'] = $proof_img_array['type'][$i][$j];
										   $imagee['tmp_name'] = $proof_img_array['tmp_name'][$i][$j];
										   $imagee['error'] = $proof_img_array['error'][$i][$j];
										   $imagee['size'] = $proof_img_array['size'][$i][$j];
										   
										   $imageName=addProofImage($customer_name,$customer_id,$human_proof_type_id,$imagee);
							   
							    			insertImageToCustomerProof($imageName,$proof_id);
										  }
									  }
								  }
								  else // if proof has only one image
								  {
									  
									  if($proof_img_array['name'][$i]!="" &&  $proof_img_array['name'][$i]!=null)
										  {
									       $imagee['name'] = $proof_img_array['name'][$i];
										   $imagee['type'] = $proof_img_array['type'][$i];
										   $imagee['tmp_name'] = $proof_img_array['tmp_name'][$i];
										   $imagee['error'] = $proof_img_array['error'][$i];
										   $imagee['size'] = $proof_img_array['size'][$i];
										   
										   $imageName=addProofImage($customer_name,$customer_id,$human_proof_type_id,$imagee);
							   
							  				insertImageToCustomerProof($imageName,$proof_id);
										  }
									  
								  }
	
	}
	
function insertImageToCustomerProof($imageName,$proof_id)
{
	$admin_id=$_SESSION['EMSadminSession']['admin_id'];
	if(validateForNull($imageName) && checkForNumeric($proof_id))
	{
		$imageName=clean_data($imageName);
	 $sql="INSERT INTO ems_customer_proof_img
							   		 (customer_proof_img_href, customer_proof_id, created_by, last_updated_by, date_added, date_modified)
									 VALUES
									 ('$imageName', $proof_id, $admin_id, $admin_id, NOW(), NOW())";
									 
									 dbQuery($sql);
	}
	
}	

function deleteCustomerProof($proof_id)
{
	$sql="DELETE FROM ems_customer_proof
			WHERE customer_proof_id=$proof_id";
	dbQuery($sql);	
	return "success";	
	}

function deleteCustomerProofImage($proof_image_id)
{
	$sql="DELETE FROM ems_customer_proof_img
		  WHERE customer_proof_img_id=$proof_id";
	dbQuery($sql);	
	}

function listProofTypes()
{
	$sql="SELECT human_proof_type_id, proof_type
	      FROM ems_human_proof_type";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return $resultArray;	  
}

function getCustomerProofByCustomerId($customer_id)
{
	if(checkForNumeric($customer_id))
	{
		$sql="SELECT ems_customer_proof.customer_proof_id,ems_customer_proof.human_proof_type_id,proof_type,customer_proof_no, ems_customer_proof.member_id
		      FROM ems_customer,ems_customer_proof,ems_human_proof_type
			  WHERE ems_customer.customer_id=$customer_id
			  AND ems_customer.customer_id=ems_customer_proof.customer_id
			  AND ems_customer_proof.human_proof_type_id=ems_human_proof_type.human_proof_type_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		
		if(dbNumRows($result)>0)
		{
		
		
			return $resultArray;
		}	  
		else
		{
			return "error";
			}		  
			  
		
		}
	
	
	}

	function getOnlyCustomerProofDetailsByCustomerId($customer_id)
{
	if(checkForNumeric($customer_id))
	{
		$sql="SELECT ems_customer_proof.human_proof_type_id, customer_proof_no, proof_type
		      FROM ems_customer_proof, ems_human_proof_type 
		      WHERE customer_id = $customer_id
		      AND member_id IS NULL
		      AND ems_customer_proof.human_proof_type_id = ems_human_proof_type.human_proof_type_id";

		

		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		
		if(dbNumRows($result)>0)
		{
		
		
			return $resultArray;
		}	  
		else
		{
			return "error";
		}		  
			  
		
		}
	
	
	}

	function getOnlyCustomerProofDetailsByCustomerIdAndProofTypeId($customer_id, $human_proof_type_id)
{
	if(checkForNumeric($customer_id, $human_proof_type_id))
	{
		$sql="SELECT ems_customer_proof.human_proof_type_id, customer_proof_no, proof_type, ems_customer_proof.customer_proof_id
		      FROM ems_customer_proof, ems_human_proof_type 
		      WHERE customer_id = $customer_id
		      AND member_id IS NULL
		      AND ems_customer_proof.human_proof_type_id = ems_human_proof_type.human_proof_type_id
		      AND ems_customer_proof.human_proof_type_id = $human_proof_type_id";

		
		
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
	
	
	}


function updateCustomerProofNumberByCustomerIdAndProofTypeId($customer_id, $human_proof_type_id, $proof_no)
{
	if(checkForNumeric($customer_id, $human_proof_type_id))
	{
		$sql="UPDATE ems_customer_proof
		      SET customer_proof_no = '$proof_no'
		      WHERE customer_id = $customer_id
		      AND member_id IS NULL
		      AND ems_customer_proof.human_proof_type_id = $human_proof_type_id";

		

		$result=dbQuery($sql);
	
		
		
		
		
		
			return "success";
		}	  
		else
		{
			return "error";
				  
		}
	
	
	}

	function getOnlyMemberProofDetailsByCustomerAndMemberIdAndProofTypeId($member_id, $human_proof_type_id)
{
	if(checkForNumeric($member_id, $human_proof_type_id))
	{
		$sql="SELECT ems_customer_proof.human_proof_type_id, customer_proof_no, proof_type, ems_customer_proof.customer_proof_id
		      FROM ems_customer_proof, ems_human_proof_type
		      WHERE member_id = $member_id
		      AND ems_customer_proof.human_proof_type_id = ems_human_proof_type.human_proof_type_id
		      AND ems_customer_proof.human_proof_type_id = $human_proof_type_id";
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
	
	
	}

	function updateMemberProofNumberByCustomerAndMemberIdAndProofTypeId($member_id, $human_proof_type_id, $proof_no)
{
	if(checkForNumeric($member_id, $human_proof_type_id))
	{
		$sql="UPDATE  ems_customer_proof
		      SET customer_proof_no = '$proof_no' 
		      WHERE member_id = $member_id
		      AND ems_customer_proof.human_proof_type_id = $human_proof_type_id";
		$result=dbQuery($sql);
	return "success";
		
	}
		
		else
		{
			return "error";
			}		  
			  
		
		
	
	
}

	function getOnlyMemberProofDetailsByCustomerAndMemberId($customer_id, $member_id)
{
	
	
	if(checkForNumeric($member_id, $customer_id))
	{
		$sql="SELECT ems_customer_proof.human_proof_type_id, customer_proof_no, proof_type
		      FROM ems_customer_proof, ems_human_proof_type 
		      WHERE customer_id = $customer_id
		      AND member_id = $member_id
		      AND ems_customer_proof.human_proof_type_id = ems_human_proof_type.human_proof_type_id";
			 
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

function getCustomerProofimgByProofId($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT customer_proof_img_id,customer_proof_img_href FROM ems_customer_proof_img WHERE customer_proof_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		{
		
		
			return $resultArray;
			}	  
		else
		{
			return "error";
			}		  
		}
	
	}
	
	
function getProofTypeAndProofNoByProofId($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT human_proof_type_id, customer_proof_no
		 FROM ems_customer_proof 
		 WHERE customer_proof_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		{
		
		return $resultArray[0];
			}	  
		else
		{
			return "error";
			}		  
		}
	
	}


function listAllMobileNos(){
	
	try
	{
		
		$sql="SELECT customer_contact_no_id, customer_contact_no, customer_id
		      FROM ems_customer_contact_no";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		return $resultArray; 
	}
	catch(Exception $e)
	{
	}
}
			
?>