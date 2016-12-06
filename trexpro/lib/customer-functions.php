<?php 
require_once("cg.php");
require_once("common.php");
require_once("city-functions.php");
require_once("area-functions.php");
require_once("image-functions.php");
require_once("account-functions.php");
require_once("common.php");
require_once("bd.php");
function listCustomer()
{
	
	$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
	$sql="SELECT customer_id,customer_name,customer_address,customer_pincode,city_id,area_id,pan_no,tin_no,notes,oc_id, our_company_id,created_by,last_updated_by,date_added,date_modified,opening_balance,opening_cd FROM edms_customer WHERE is_deleted=0 AND our_company_id = $our_company_id ORDER BY customer_name";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
	for($i=0;$i<count($resultArray);$i++)
	{
		$Customer=$resultArray[$i];
		$customer_id=$Customer['customer_id'];
		$contact_no=getCustomerContactNo($customer_id);
		$resultArray[$i]['contact_no']=$contact_no;
		}	
	return $resultArray;
	}
	else
	return false;
}

function listCustomerNames()
{
	$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
	$sql="SELECT customer_id,customer_name FROM emds_customer WHERE is_deleted=0 AND our_company_id = $our_company_id ORDER BY customer_name";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
	
	return $resultArray;
	}
	else
	return false;

	}

function insertCustomer($name,$address,$city_id,$area,$pincode,$contact_no,$human_proof_type_id,$proofno,$proofImg,$pan_no="NA",$tin_no="NA",$notes="NA",$opening_balance=0,$opening_cd=0,$bd2=false){	
	try
	{
		
		$name=clean_data($name);
		$address=clean_data($address);
		$city_id=clean_data($city_id);
		$area=clean_data($area);
		$pincode=clean_data($pincode);
		$agency_id=clean_data($agency_id);
		$pan_no=clean_data($pan_no);
		$tin_no=clean_data($tin_no);
		$notes=clean_data($notes);
		if(!validateForNull($pincode))
		$pincode=0;
		if(!validateForNull($pan_no))
		{
			$pan_no="NA";
			}
		if(!validateForNull($tin_no))
		{
			$tin_no="NA";
			}
		if(!validateForNull($notes))
		{
			$notes="NA";
			}		
		
		$original_agency_id=$agency_id;
		$our_company_id=NULL;
		$type=substr($agency_id,0,2);
		$agency_id=substr($agency_id,2);
		if($type=="ag")
		{
		$agency_id=$agency_id;
		$oc_id="NULL";
		}
		else if($type=="oc")
		{
		$oc_id=$agency_id;
		$agency_id="NULL";	
		}	

		if(!$bd2)
		{
		$our_company_id = $_SESSION['edmsAdminSession']['oc_id'];
		if(!checkForNumeric($our_company_id))
		$our_company_id = DEFAULT_OC_ID;
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		if(!checkForNumeric($admin_id))
		$admin_id = DEFAULT_ADMIN_ID;
		}
		else
		{
			$our_company_id = DEFAULT_OC_ID;
			$admin_id = DEFAULT_ADMIN_ID;
		}
		$area_id=insertArea($area,$city_id);
		$name = ucwords(strtolower($name));
		$duplicate = checkForDuplicateCustomer($name,$bd2);
		
			if(validateForNull($name,$address,$contact_no) && checkForNumeric($city_id,$pincode,$our_company_id,$area_id)  && !empty($contact_no) && !$duplicate)
			{
				$address=trim($address);
			
				$sql="INSERT INTO 	edms_customer(customer_name,customer_address,customer_pincode,city_id,area_id,pan_no,tin_no,notes,oc_id, our_company_id,created_by,last_updated_by,date_added,date_modified)				VALUES 
						('$name', '$address', $pincode, $city_id, $area_id,'$pan_no', '$tin_no', '$notes', $our_company_id, $our_company_id, $admin_id, $admin_id, NOW(), NOW())";
				
				$result=dbQuery($sql,$bd2);
				$customer_id=dbInsertId($bd2);	
				addCutomerContactNo($customer_id,$contact_no,$bd2);
				addCustomerProof($customer_id,$name,$human_proof_type_id,$proofno,$proofImg,$bd2);
				setOpeningBalanceForCustomer($customer_id,$opening_balance,$opening_cd,$bd2);
				return $customer_id;
			}
			else if(checkForNumeric($duplicate))
			return $duplicate;
			else
			{
				return false;
				}
		
		
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateCustomer($name,$bd2=false)
{
	$name = clean_data($name);
	if(validateForNull($name))
	{
		$sql="SELECT customer_id FROM edms_customer WHERE customer_name = '$name'";
		
		$result=dbQuery($sql,$bd2);
		$resultArray = dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		
		
	}
	return false;
	
}


function deletetCustomer($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="UPDATE edms_customer SET is_deleted = 1
		      WHERE customer_id=$id";
		dbQuery($sql);
		return "success";
		}
		return "error";
	}
	catch(Exception $e)
	{
	}
	
}	

function updateCustomer($id,$name,$address,$city_id,$area,$pincode,$contact_no,$human_proof_type_id,$proofno,$proofImg,$pan_no="NA",$tin_no="NA",$notes="NA",$opening_balance=0,$opening_cd=0){
	
	try
	{
		if(!validateForNull($pincode))
		$pincode=0;
		$name=clean_data($name);
		$address=clean_data($address);
		$city_id=clean_data($city_id);
		$area=clean_data($area);
		$pincode=clean_data($pincode);
		$pan_no=clean_data($pan_no);
		$tin_no=clean_data($tin_no);
		$notes=clean_data($notes);
		
		if(!validateForNull($pan_no))
		{
			$pan_no="NA";
			}
		if(!validateForNull($tin_no))
		{
			$tin_no="NA";
			}
		if(!validateForNull($notes))
		{
			$notes="NA";
			}	
		
		
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			$name = ucwords(strtolower($name));
			$area_id=insertArea($area,$city_id);
			if(validateForNull($name,$address) && checkForNumeric($city_id,$area_id) && $contact_no!=null && !empty($contact_no))
			{
				$address=trim($address);
				$current_bal = getCurrentBalanceForCustomer($id);
				
				$current_balance = $current_bal[0];
				$current_balance_cd = $current_bal[1];	
				$sql="UPDATE edms_customer
				     SET customer_name = '$name', customer_address = '$address', customer_pincode = $pincode , city_id = $city_id, area_id = $area_id,  pan_no = '$pan_no', tin_no = '$tin_no', notes='$notes', last_updated_by = $admin_id, date_modified = NOW()
					 WHERE customer_id=$id";
				
				$result=dbQuery($sql);
				
				deleteAllContactNoCustomer($id);
				addCutomerContactNo($id,$contact_no);
				addCustomerProof($id,$name,$human_proof_type_id,$proofno,$proofImg);
				setOpeningBalanceForCustomer($id,$opening_balance,$opening_cd);
				
				if($current_balance_cd==$opening_cd)
				{
					$new_current_balance = $current_balance+$opening_balance;
					$new_current_balance_cd = $current_balance_cd;
				}
				else
				{
					if($current_balance>$opening_balance)
					{
						$new_current_balance = $current_balance - $opening_balance;
						$new_current_balance_cd = $current_balance_cd;
					}
					else
					{
						$new_current_balance = $opening_balance - $current_balance;
						$new_current_balance_cd = $opening_cd;
					}
				}
				setCurrentBalanceForCustomer($id,$new_current_balance,$new_current_balance_cd);
				
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


function setOpeningBalanceForCustomer($customer_id,$opening_balnce,$opening_balance_cd,$bd2=false)
{
	
	if(checkForNumeric($customer_id,$opening_balnce,$opening_balance_cd))
	{
		
		$sql="UPDATE edms_customer SET opening_balance = $opening_balnce , opening_cd = $opening_balance_cd
		      WHERE customer_id=$customer_id";
			  
		dbQuery($sql,$bd2);
		
		return "success";	  
	}
	else return "error";	
}
function getOpeningBalanceForCustomer($customer_id)
{
	if(checkForNumeric($customer_id))
	{
		$sql="SELECT opening_balance, opening_cd FROM edms_customer WHERE customer_id=$customer_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else 
		return false;
		}
	return false;	
	}
	
function setCurrentBalanceForCustomer($customer_id,$opening_balnce,$opening_balance_cd)
{
	
	if(checkForNumeric($customer_id,$opening_balnce,$opening_balance_cd))
	{
		
		$sql="UPDATE edms_customer SET current_balance = $opening_balnce , current_balance_cd = $opening_balance_cd
		      WHERE customer_id=$customer_id";
		dbQuery($sql);
		return "success";	  
	}
	else return "error";	
}


function addCutomerContactNo($customer_id,$contact_no,$bd2=false)
{
	try
	{
		if(is_array($contact_no))
		{
			foreach($contact_no as $no)
			{
				if(checkForNumeric($no))
				{
				insertContactNoCustomer($customer_id,$no,$bd2); 
				}
			}
		}
		else
		{
			
			if(checkForNumeric($contact_no))
				{
				insertContactNoCustomer($customer_id,$contact_no,$bd2); 
				}
			
		}
	}
	catch(Exception $e)
	{
	}
}

function insertContactNoCustomer($id,$contact_no,$bd2=false)
{
	try
	{
		
		if(checkForNumeric($id)==true && checkForNumeric($contact_no))
		{
			
		$sql="INSERT INTO edms_customer_contact_no
				      (customer_contact_no, customer_id)
					  VALUES
					  ('$contact_no', $id)";
				
				dbQuery($sql,$bd2);	  
				
		}
	}
	catch(Exception $e)
	{}
	
	
}
function deleteContactNoCustomer($id)
{
	try
	{
		$sql="DELETE FROM edms_customer_contact_no
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
		$sql="DELETE FROM edms_customer_contact_no
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
		$sql="SELECT customer_contact_no FROM edms_customer_contact_no
				WHERE customer_id=$id";
				$result=dbQuery($sql);	  
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
		}
	}


function addCustomerProof($customer_id,$customer_name,$human_proof_type_id_array,$proof_no_array,$proof_img_array,$scanImgArray)
{
	try
	{
		
		
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
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
									if($proof_no==null || $proof_no=="")
									$proof_no="NA";
								$sql="INSERT INTO edms_customer_proof
								     (human_proof_type_id, customer_proof_no, customer_id, created_by, last_updated_by, date_added, date_modified)
									 VALUES
									 ($human_proof_type_id, '$proof_no', $customer_id, $admin_id, $admin_id, NOW(), NOW() )";
								  
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
			
			
		}
		else // if only one proof submitted
		{
			if($human_proof_type_id_array>0 && (checkForImagesInArray($proof_img_array['name'][$i]) || ($proof_no_array[$i]!=null && $proof_no_array[$i]!="")))
								{
			$proof_no_array=clean_data($proof_no_array);							
			$sql="INSERT INTO edms_customer_proof
								     (human_proof_type_id, customer_proof_no, customer_id, created_by, last_updated_by, date_added, date_modified)
									 VALUES
									 ($human_proof_type_id_array, '$proof_no_array', $customer_id, $admin_id, $admin_id, NOW(), NOW())";
								  
								  $result=dbQuery($sql);
								  $proof_id=dbInsertId();
								  addImagesToCustomerProof($customer_id,$customer_name,$human_proof_type_id_array,$proof_id,$proof_img_array,0);
								}
		}
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
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	if(validateForNull($imageName) && checkForNumeric($proof_id))
	{
		$imageName=clean_data($imageName);
	 $sql="INSERT INTO edms_customer_proof_img
							   		 (customer_proof_img_href, customer_proof_id, created_by, last_updated_by, date_added, date_modified)
									 VALUES
									 ('$imageName', $proof_id, $admin_id, $admin_id, NOW(), NOW())";
									 
									 dbQuery($sql);
	}
	
}	

function deleteCustomerProof($proof_id)
{
	$sql="DELETE FROM edms_customer_proof
			WHERE customer_proof_id=$proof_id";
	dbQuery($sql);	
	return "success";	
	}

function deleteCustomerProofImage($proof_image_id)
{
	$sql="DELETE FROM edms_customer_proof_img
		  WHERE customer_proof_img_id=$proof_id";
	dbQuery($sql);	
	}

function listProofTypes()
{
	$sql="SELECT human_proof_type_id, proof_type
	      FROM edms_human_proof_type";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return $resultArray;	  
}

function getCustomerProofByCustomerId($customer_id)
{
	if(checkForNumeric($customer_id))
	{
		$sql="SELECT edms_customer_proof.customer_proof_id,edms_customer_proof.human_proof_type_id,proof_type,customer_proof_no
		      FROM edms_customer_proof,edms_human_proof_type
			  WHERE customer_id=$customer_id
			  AND edms_customer_proof.human_proof_type_id=edms_human_proof_type.human_proof_type_id";
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


function getCustomerProofimgByProofId($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT customer_proof_img_id,customer_proof_img_href FROM edms_customer_proof_img WHERE customer_proof_id=$id";
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

		

function getCustomerDetailsByCustomerId($customer_id)
{
	if(checkForNumeric($customer_id))
	{
		$sql="SELECT customer_id,customer_name,customer_address,customer_pincode,city_id, area_id, oc_id, opening_balance, opening_cd, current_balance, current_balance_cd, pan_no, tin_no, notes
		      FROM edms_customer
			  WHERE customer_id=$customer_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		$contactNoArray=getCustomerContactNo($customer_id);
	
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
	return "error";
	}	

function getFullCustomerNameByCustomerID($customer_id)
{
	if(checkForNumeric($customer_id))
	{
		$sql="SELECT CONCAT('C',edms_customer.customer_id) as id, CONCAT(customer_name, ' | [C',edms_customer.customer_id,']') as full_ledger_name FROM edms_customer  WHERE edms_customer.customer_id = $customer_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		$contactNoArray=getCustomerContactNo($customer_id);
	
		if(dbNumRows($result)>0)
		{
		
		
			return $resultArray[0]['full_ledger_name'];
			}	  
		else
		{
			return "error";
			}		  
			  
		
		}
	return "error";
	}		


function getCompanyIdFromCustomerId($customer_id)
{
	$sql="SELECT oc_id FROM edms_customer WHERE customer_id = $customer_id";
	$result=dbQuery($sql);
	$resultArray = dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
}

/* reminder functions */

function addRemainder($customer_id,$date,$remarks)
{
	if(!validateForNull($date))
	{
		$date='1970-01-01';
		}
	$remarks=clean_data($remarks);	
	if(checkForNumeric($customer_id) && validateForNull($date,$remarks))
	{
	$date = str_replace('/', '-', $date);
			$date=date('Y-m-d',strtotime($date));	
	$sql="INSERT INTO edms_remainder(customer_id,date,remarks) VALUE ($customer_id,'$date','$remarks')";
	$result=dbQuery($sql);
	return "success";
	}
	else
	return "error";
}
	
function listRemainderForCustomer($customer_id)
{
	
	if(checkForNumeric($customer_id))
	{
		$sql="SELECT remainder_id,date,remarks,remainder_status FROM edms_remainder WHERE customer_id=$customer_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray;
		else return false;
	}			
}

function listRemarksForCustomer($customer_id)
{
	
	if(checkForNumeric($customer_id))
	{
		$sql="SELECT remainder_id,date,remarks,remainder_status FROM edms_remainder WHERE customer_id=$customer_id AND remainder_status=0 ORDER BY date";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray;
		else return false;
	}			
}

function listOnlyRemaindersForCustomer($customer_id)
{
	
	if(checkForNumeric($customer_id))
	{
		$sql="SELECT remainder_id,date,remarks,remainder_status FROM edms_remainder WHERE customer_id=$customer_id AND (date!='1970-01-01' AND date!='0000-00-00')";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray;
		else return false;
	}			
}


function getRemainderById($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT remainder_id,date,remarks,customer_id FROM edms_remainder WHERE remainder_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else return false;
	}		
	
	
}

function editRemainDer($id,$date,$remarks)
{
	$remarks=clean_data($remarks);
	if(checkForNumeric($id))
	{
		$date = str_replace('/', '-', $date);
			$date=date('Y-m-d',strtotime($date));
		$sql="UPDATE edms_remainder SET date='$date', remarks='$remarks' WHERE remainder_id=$id";
		$result=dbQuery($sql);
		return "success";
	}		
	else
	return "error";
	
}

function deleteRemainder($id)
{
	if(checkForNumeric($id))
	{
		
		$sql="DELETE FROM edms_remainder WHERE remainder_id=$id";
		$result=dbQuery($sql);
		return "success";
	}		
	else
	return "error";
	
}

function setDoneRemainderGeneral($id)
{
	if(checkForNumeric($id))
	{
		$sql="UPDATE edms_remainder SET remainder_status=1 WHERE remainder_id=$id";
		$result=dbQuery($sql);
		return "success";
		}
	else return "error";	
	
	}

function setUnDoneRemainderGeneral($id)
{
	if(checkForNumeric($id))
	{
		$sql="UPDATE edms_remainder SET remainder_status=0 WHERE remainder_id=$id";
		$result=dbQuery($sql);
		return "success";
		}
	else return "error";	
	
}	

function getCustomerIdFromCustomerName($name)
{
	
	if(validateForNull($name))
	{
		$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
		$name=clean_data($name);
		$sql="SELECT customer_id
		      FROM edms_customer
			  WHERE our_company_id=$oc_id
			 AND is_deleted=0
			  AND customer_name  ";
		$cond="='$name' ";
		$sq=$sql.$cond;
		$result=dbQuery($sq);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)==1)
		{
			
			return $resultArray[0][0];
		}
		else if(dbNumRows($result)>1)
		{
			return $resultArray;
		}
		else
		{ 
			
			$sql=$sql." LIKE '%".$name."%'";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)==1)
			{
			$resultArray['nameType']="like";
			return $resultArray;
			}
			else if(dbNumRows($result)>1)
			{
			$resultArray['nameType']="like";
			return $resultArray;
			}	
			else
			return "error";
		}		  
		}
}		

function getCustomerIdFromCustomerNo($no)
{
	$no = clean_data($no);
	if(checkForNumeric($no))
	{
		
		$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
		$sql="SELECT edms_customer.customer_id
		      FROM edms_customer,edms_customer_contact_no
			  WHERE customer_contact_no=$no
			  AND our_company_id=$oc_id
			  AND edms_customer.customer_id=edms_customer_contact_no.customer_id AND is_deleted=0
			 ";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)==1)
		{
			
			return $resultArray[0][0];
		}
		else if(dbNumRows($result)>1)
		{
			return $resultArray;
		}
		else
		{
			return "error";
			}		  
		}
	}
	
function getCustomerIDFromVehicleId($vehicle_id)
{
	if(checkForNumeric($vehicle_id))
	{
		$sql="SELECT customer_id FROM edms_vehicle WHERE vehicle_id = $vehicle_id";
		$result = dbQuery($sql);
		if(dbNumRows($result)>0)
		{
			$resultArray = dbResultToArray($result);
			return $resultArray[0][0];
		}
		return false;
	}
	
	}
	
function getCustomerSearchResultDetailsFromCustomerId($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT customer_id
		      FROM edms_customer
			  WHERE customer_id=$id
			  AND is_deleted=0";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$file=$resultArray[0];
			$customer=getCustomerDetailsByCustomerId($id);
			$returnArray=array();
			$returnArray['customer_array']=$customer;
			return $returnArray;
			}
		}
	}
	
function getCustomerIdFromCustomerNameAutoComplete($auto_complete)
{
	if(validateForNull($auto_complete))
	{
	$sql="SELECT customer_id, CONCAT_WS(' | C',customer_name,customer_id) as search_string FROM edms_customer HAVING search_string = '$auto_complete'";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0][0];
		}
		
		
	}
	return false;
	
}	
?>