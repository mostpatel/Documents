<?php 
require_once("cg.php");
require_once("common.php");
require_once("image-functions.php");
require_once("city-functions.php");
require_once("bd.php");
		
function listExtraCustomers(){
	
	try
	{
	}
	catch(Exception $e)
	{
	}
	
}	

function insertExtraCustomer($name,$address,$city_id,$area,$pincode,$file_id,$customer_id,$contact_no,$human_proof_type_id,$proofno,$proofImg,$scanImg=false,$secondary_name="",$secondary_address="") // city_id,file_id,customer_id,name,address,contact compulsory
{
	
	try
	{
		
			if(!validateForNull($pincode))
		    $pincode=0;
			$name=clean_data($name);
			$secondary_name=clean_data($secondary_name);
			$secondary_address=clean_data($secondary_address);
			$address=clean_data($address);
			$admin_id=$_SESSION['adminSession']['admin_id'];
			$name = ucwords(strtolower($name));
			$area_id=insertArea($area,$city_id);
			if(checkForNumeric($city_id,$file_id,$customer_id) && $name!=null && $name!="" && $address!=null && $address!="" && $contact_no!=null && !empty($contact_no)  && !checkForDuplicateExtraCustomer($name, $address, $city_id, $file_id, $customer_id))
			{
				$address=trim($address);
				
				$sql="INSERT INTO 	
				fin_extra_customer(extra_customer_name, extra_customer_address, extra_customer_pincode, city_id, area_id, file_id, customer_id, created_by, last_updated_by, date_added, date_modified,secondary_extra_customer_name,secondary_extra_customer_address)				VALUES 
						('$name', '$address', $pincode, $city_id, $area_id, $file_id, $customer_id, $admin_id, $admin_id, NOW(), NOW(),'$secondary_name','$secondary_address')";
			
				$result=dbQuery($sql);
				
				$extra_customer_id=dbInsertId();		
				addExtraCustomerContactNo($extra_customer_id,$contact_no);
			//	addExtraCustomerProof($extra_customer_id,$name,$human_proof_type_id,$proofno,$proofImg,$scanImg);
				return "success";
			}
			return "error";
				
	}
	catch(Exception $e)
	{
	}
	
}	

function deletetExtraCustomer($id){
	
	try
	{
	}
	catch(Exception $e)
	{
	}
	
}	

function updateExtraCustomer($id,$name,$address,$city_id,$area,$pincode,$contact_no,$human_proof_type_id,$proofno,$proofImg,$scanImg,$secondary_customer_name="",$secondary_customer_address=""){
	
	try
	{
		if(!validateForNull($pincode))
		$pincode=0;
		$name=clean_data($name);
		$address=clean_data($address);
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$area_id=insertArea($area,$city_id);
			$name = ucwords(strtolower($name));
		if(!validateForNull($secondary_customer_name))	
		{
		$secondary_customer_name="";	
		}
		else
		{
		$secondary_customer_name=clean_data($secondary_customer_name);	
		}
		if(!validateForNull($secondary_customer_address))	
		{
		$secondary_customer_address="";	
		}
		else
		{
		$secondary_customer_address=clean_data($secondary_customer_address);	
		}
			if(validateForNull($name,$address) && checkForNumeric($city_id) && $contact_no!=null && !empty($contact_no))
			{
				$address=trim($address);
			
				$sql="UPDATE fin_extra_customer
				     SET extra_customer_name = '$name', extra_customer_address = '$address',  secondary_extra_customer_name = '$secondary_customer_name', secondary_extra_customer_address = '$secondary_customer_address', extra_customer_pincode = $pincode , city_id = $city_id, area_id = $area_id, last_updated_by = $admin_id, date_modified = NOW()
					 WHERE extra_customer_id=$id";
				$result=dbQuery($sql);
				
				deleteAllContactNoExtraCustomer($id);
				addExtraCustomerContactNo($id,$contact_no);
		//		addExtraCustomerProof($id,$name,$human_proof_type_id,$proofno,$proofImg,$scanImg);
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

function getExtraCustomerById($id){
	
	try
	{
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateExtraCustomer($name,$address,$city_id,$file_id,$customer_id,$id=false)
{
	try
	{
		
		$sql="SELECT extra_customer_id
		      FROM fin_extra_customer
			  WHERE  file_id=$file_id";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND extra_customer_id!=$id";	  	  
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
	
function addExtraCustomerContactNo($extra_customer_id,$contact_no)
{
	try
	{
		if(is_array($contact_no))
		{
			foreach($contact_no as $no)
			{
				if($no!="" && $no!=null)
				{
				insertContactNoExtraCustomer($extra_customer_id,$no); 
				}
			}
		}
		else
		{
			if($contact_no!="" && $contact_no!=null)
				{
				insertContactNoExtraCustomer($extra_customer_id,$contact_no); 
				}
			
		}
	}
	catch(Exception $e)
	{
	}
}

function insertContactNoExtraCustomer($id,$contact_no)
{
	try
	{
		if(checkForNumeric($id,$contact_no)==true)
		{
		$sql="INSERT INTO fin_extra_customer_contact_no
				      (extra_customer_contact_no, extra_customer_id)
					  VALUES
					  ('$contact_no', $id)";
				dbQuery($sql);	  
		}
	}
	catch(Exception $e)
	{}
	
	
}
function deleteContactNoExtraCustomer($id)
{
	try
	{
		$sql="DELETE FROM fin_extra_customer_contact_no
			  WHERE extra_customer_contact_no_id=$id";
		dbQuery($sql);	  
	}
	catch(Exception $e)
	{}
	
	
	
	}
function deleteAllContactNoExtraCustomer($id)
{
	try
	{
		$sql="DELETE FROM fin_extra_customer_contact_no
			  WHERE extra_customer_id=$id";
		dbQuery($sql);
	}
	catch(Exception $e)
	{}
	
	
	
	}	
function updateContactNoExtraCustomer($id,$contact_no)
{
	try
	{
		deleteAllContactNoExtraCustomer($id);
		addExtraCustomerContactNo($id,$contact_no);
	}
	catch(Exception $e)
	{}
	
	
	
	}
	
function getExtraCustomerContactNo($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT extra_customer_contact_no FROM fin_extra_customer_contact_no
				WHERE extra_customer_id=$id";
				$result=dbQuery($sql);	  
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
		}
	}	
	
function addExtraCustomerProof($extra_customer_id,$extra_customer_name,$human_proof_type_id_array,$proof_no_array,$proof_img_array,$scanImgArray)
{
	try
	{
		
		$admin_id=$_SESSION['adminSession']['admin_id'];
		if(is_array($human_proof_type_id_array)) // if more than one proof submitted
		{
			$len=count($human_proof_type_id_array);
			for($i=0;$i<$len;$i++)
			{
								
								$human_proof_type_id=$human_proof_type_id_array[$i];
								
								if($human_proof_type_id>0 && (checkForImagesInArray($proof_img_array['name'][$i]) || ($proof_no_array[$i]!=null && $proof_no_array[$i]!="")))
								{
								
								
								$proof_no=$proof_no_array[$i];
								$proof_no=clean_data($proof_no);
								
								$sql="INSERT INTO fin_extra_customer_proof
								     (human_proof_type_id, extra_customer_proof_no, extra_customer_id, created_by, last_updated_by, date_added, date_modified)
									 VALUES
									 ($human_proof_type_id, '$proof_no', $extra_customer_id, $admin_id, $admin_id, NOW(), NOW() )";
								  
								  $result=dbQuery($sql);
								  $proof_id=dbInsertId();
								  
							    addImagesToExtraCustomerProof($extra_customer_id,$extra_customer_name,$human_proof_type_id,$proof_id,$proof_img_array,$i);
								if($scanImgArray!=false && isset($scanImgArray[$i]) && is_array($scanImgArray[$i]))
								{
									
									foreach($scanImgArray[$i] as $scanImage)
									{
										insertImageToExtraCustomerProof($scanImage,$proof_id);
										}
									}
									
									
								
								}
							   
				
			}
			
		}
		else // if only one proof submitted
		{
			if($human_proof_type_id_array>0 && (checkForImagesInArray($proof_img_array['name'][$i]) || ($proof_no_array[$i]!=null && $proof_no_array[$i]!="")))
								{
			$proof_no_array=clean_data($proof_no_array);							
			$sql="INSERT INTO fin_extra_customer_proof
								     (human_proof_type_id, extra_customer_proof_no, extra_customer_id, created_by, last_updated_by, date_added, date_modified)
									 VALUES
									 ($human_proof_type_id_array, '$proof_no_array', $extra_customer_id, $admin_id, $admin_id, NOW(), NOW() )";
								  
								  $result=dbQuery($sql);
								  $proof_id=dbInsertId();
								  addImagesToExtraCustomerProof($extra_customer_id,$extra_customer_name,$human_proof_type_id_array,$proof_id,$proof_img_array,0);
								}
		}
	}
	catch(Exception $e)
	{
		
	}
	
}	

function addImagesToExtraCustomerProof($extra_customer_id,$extra_customer_name,$human_proof_type_id,$proof_id,$proof_img_array,$i){
	
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
										   
										   $imageName=addProofImageExtraCustomer($extra_customer_name,$extra_customer_id,$human_proof_type_id,$imagee);
							   
							    			insertImageToExtraCustomerProof($imageName,$proof_id);
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
										   
										   $imageName=addProofImageExtraCustomer($extra_customer_name,$extra_customer_id,$human_proof_type_id,$imagee);
							   
							  				insertImageToExtraCustomerProof($imageName,$proof_id);
										  }
									  
								  }
	
	}
	
function insertImageToExtraCustomerProof($imageName,$proof_id)
{
	 $admin_id=$_SESSION['adminSession']['admin_id'];
	
	if(validateForNull($imageName) && checkForNumeric($proof_id))
	{
		
	 $imageName=clean_data($imageName);
	 $sql="INSERT INTO fin_extra_customer_proof_img
							   		 (extra_customer_proof_img_href, extra_customer_proof_id, created_by, last_updated_by, date_added, date_modified)
									 VALUES
									 ('$imageName', $proof_id, $admin_id, $admin_id, NOW(), NOW())";
									 
									 dbQuery($sql);
									
									
	}
	
}	

function deleteExtraCustomerProof($proof_id)
{
	$sql="DELETE FROM fin_extra_customer_proof
			WHERE extra_customer_proof_id=$proof_id";
	dbQuery($sql);	
	return "success";		
	}

function deleteExtraCustomerProofImage($proof_image_id)
{
	$sql="DELETE FROM fin_extra_customer_proof_img
		  WHERE extra_customer_proof_img_id=$proof_id";
	dbQuery($sql);	
	}	

function getExtraCustomerDetailsByFileId($file_id)
{
	if(checkForNumeric($file_id))
	{
		$sql="SELECT extra_customer_id, extra_customer_name, extra_customer_address, extra_customer_pincode, fin_extra_customer.city_id,fin_city_area.area_id, area_name ,secondary_area_name, file_id, customer_id, secondary_extra_customer_name, secondary_extra_customer_address
		      FROM fin_extra_customer, fin_city_area
			  WHERE file_id=$file_id AND fin_extra_customer.area_id = fin_city_area.area_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		{
			$contactNoArray=getExtraCustomerContactNo($resultArray[0]['extra_customer_id']);
		$resultArray[0][]=$contactNoArray;
		$resultArray[0]['contact_no']=$contactNoArray;
		
			return $resultArray[0];
			}	  
		else
		{
			return "error";
			}		  
			  
		
		}
	
	}	

function getExtraCustomerDetailsByCustomerId($customer_id)
{
	if(checkForNumeric($customer_id))
	{
		$sql="SELECT extra_customer_id, extra_customer_name, extra_customer_address, extra_customer_pincode, city_id, area_id, file_id, customer_id, secondary_extra_customer_name, secondary_extra_customer_address
		      FROM fin_extra_customer
			  WHERE customer_id=$customer_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		$contactNoArray=getExtraCustomerContactNo($resultArray[0]['extra_customer_id']);
		if(dbNumRows($result)>0)
		{
		$resultArray[0][]=$contactNoArray;
		$resultArray[0]['contact_no']=$contactNoArray;
		
			return $resultArray[0];
			}	  
		else
		{
			return "error";
			}		  
			  
		
		}
	
	}	

function getExtraCustomerProofByFileId($file_id)
{
	if(checkForNumeric($file_id))
	{
		$sql="SELECT fin_extra_customer_proof.extra_customer_proof_id,fin_extra_customer_proof.human_proof_type_id,proof_type,extra_customer_proof_no
		      FROM fin_extra_customer,fin_extra_customer_proof,fin_human_proof_type
			  WHERE file_id=$file_id
			  AND fin_extra_customer.extra_customer_id=fin_extra_customer_proof.extra_customer_id
			  AND fin_extra_customer_proof.human_proof_type_id=fin_human_proof_type.human_proof_type_id";
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

function getExtraCustomerProofimgByProofId($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT extra_customer_proof_img_id,extra_customer_proof_img_href FROM fin_extra_customer_proof_img WHERE extra_customer_proof_id=$id";
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
?>