<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");

			
function listOurCompanies(){
	
	$sql="SELECT our_company_id, our_company_name, our_company_address, our_company_pincode, ems_city.city_id, city_name, 			     our_company_prefix
		  FROM ems_our_company,ems_city
		  WHERE ems_our_company.city_id=ems_city.city_id";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
	}

function listOurCompaniesNames(){
	
	$sql="SELECT our_company_id, our_company_name
	      FROM ems_our_company";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
	}


function insertOurCompany($name,$address,$pincode,$city_id,$company_prefix,$contact_no=false) // name,pincode,city_id,prefix validations
{
	try
	{
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		$name = strtoupper($name);
		$address=clean_data($address);
		$name=clean_data($name);
		$company_prefix=clean_data($company_prefix);
		$cond=checkForNumeric($pincode,$city_id);
		if($cond==true && validateForNull($name,$address,$company_prefix) && strlen($pincode)==6 && checkForAlphaNumeric($company_prefix) && strlen($company_prefix)<5 && !checkForDuplicateOurCompany($name,$address,$city_id,$pincode,$company_prefix))
		{
			
			$company_prefix=strtoupper($company_prefix);
			$sql="INSERT INTO 
			      ems_our_company(our_company_name, our_company_address, our_company_pincode, city_id, our_company_prefix, created_by, last_updated_by, date_added, date_modified)
				  VALUES
				  ('$name', '$address', '$pincode', $city_id, '$company_prefix', $admin_id, $admin_id, NOW(), NOW())";
			$result=dbQuery($sql);	  
			$ourCompanyId=dbInsertId();
			if($contact_no!=false)
			{
			addContactNoOurCompany($ourCompanyId,$contact_no);
			}
			return "success";
		}
		else
		{
			return "error";
			
		}
		return "error";
		
	}
	catch(Exception $e)
	{}
	
}
	
function deleteOurCompany($id)
{
	try{
		
		$ourCompanies=listOurCompaniesNames();
		if(checkForNumeric($id) && !checkIfOurCompanyInUse($id) && count($ourCompanies)>1)
		{
		$sql="DELETE FROM
		      ems_our_company 
			  WHERE our_company_id=$id";
		dbQuery($sql);	  
		return "success";
		}
		else if(count($ourCompanies)==1)
		{
			return "error1";
			}
		else
		{
			return "error";
			}
		}
	catch(Exception $e)
	{}
	
	}
	
function updateOurCompany($id,$name,$address,$pincode,$city_id,$company_prefix,$contact_no)
{
	try{
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		$name = strtoupper($name);
		$address=clean_data($address);
		$name=clean_data($name);
		$company_prefix=clean_data($company_prefix);
		$cond=checkForNumeric($pincode,$city_id);
		
		if($cond==true && validateForNull($name,$address,$company_prefix) && $pincode>100000 && checkForAlphaNumeric($company_prefix) && strlen($company_prefix)<5 && !checkForDuplicateOurCompany($name,$address,$city_id,$pincode,$company_prefix,$id))
		{
			$company_prefix=strtoupper($company_prefix);
			$sql="UPDATE  
			      ems_our_company
				  SET our_company_name = '$name',
				      our_company_address = '$address',
					  our_company_pincode = $pincode,
					  city_id = $city_id,
					  our_company_prefix = '$company_prefix',
					  last_updated_by = $admin_id,
					  date_modified = NOW()
				  WHERE our_company_id=$id";
			dbQuery($sql);	
			updateContactNoOurCompany($id,$contact_no);  
			return "success";
		}
		else
		{
			return "error";
			}
		
		}
	catch(Exception $e)
	{}
	
	}	


	
function getOurCompanyByID($id)
{
	$sql="SELECT our_company_id, our_company_name, our_company_address, our_company_pincode, ems_city.city_id, city_name, our_company_prefix
	      FROM ems_our_company,ems_city
		  WHERE our_company_id=$id
		  AND ems_our_company.city_id=ems_city.city_id";
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
	



			

function checkForDuplicateOurCompany($name,$address,$city,$pincode,$prefix,$id=false) // duplicate if name,address,pincode,city are all same or company prefix is same
{
	try{
		
		$sql="SELECT our_company_id
		       FROM ems_our_company
			   WHERE 
			   ((((our_company_name='$name'
			   AND our_company_address='$address'
			   AND our_company_pincode=$pincode
			   AND city_id=$city)
			   OR (our_company_prefix='$prefix')))
			   ";	   
		if($id==false)
		$sql=$sql.")";
		else
		$sql=$sql." AND (our_company_id!=$id))";	   
		$result=dbQuery($sql);	
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
	{}
	
	}	
function addContactNoOurCompany($id,$contact_no)
{
	try
	{
		if(is_array($contact_no))
		{
			
			foreach($contact_no as $no)
			{
				
				insertContactNoOurCompany($id,$no);
			}
			
		}
		else
		{

			insertContactNoOurCompany($id,$contact_no);
		}
	}
	catch(Exception $e)
	{}
	
}

function insertContactNoOurCompany($id,$contact_no)
{
	try
	{
		if(checkForNumeric($id)==true && checkForNumeric($contact_no))
		{
		$sql="INSERT INTO 
			  ems_our_company_contact_no(our_company_contact_no, our_company_id)
			  VALUES
			  ('$contact_no', $id)";
		dbQuery($sql);
		}
	}
	catch(Exception $e)
	{}
	
	
}
function deleteContactNoOurCompany($id)
{
	try
	{
		$sql="DELETE FROM ems_our_company_contact_no
			  WHERE our_company_contact_no_id=$id";
		dbQuery($sql);	  
	}
	catch(Exception $e)
	{}
	
	
	
	}
function deleteAllContactNoOurCompany($id)
{
	try
	{
		$sql="DELETE FROM ems_our_company_contact_no
			  WHERE our_company_id=$id";
		dbQuery($sql);
	}
	catch(Exception $e)
	{}
	
	
	
	}	
function updateContactNoOurCompany($id,$contact_no)
{
	try
	{
		deleteAllContactNoOurCompany($id);
		addContactNoOurCompany($id,$contact_no);
	}
	catch(Exception $e)
	{}
	
	
	
	}
function getContactNoForOurCompany($id)
{
	$sql="SELECT our_company_id,our_company_contact_no
			FROM ems_our_company_contact_no
			WHERE our_company_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);		
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return false;
}	

function checkIfOurCompanyInUse($id)
{
	
	$sql="SELECT our_company_id
			FROM fin_file
			WHERE our_company_id=$id OR oc_id=$id
			LIMIT 0, 1";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	
	}

function getPrefixFromOCId($id)
{
	$sql="SELECT our_company_prefix FROM
		   ems_our_company
		   WHERE our_company_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];	   
	
	}
	
function getEnquiryIdForOCID($id)
{
	$sql="SELECT our_company_prefix,enquiry_id_counter FROM
		   ems_our_company
		   WHERE our_company_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0].$resultArray[0][1];	
}



function getEnquiryIdCounterForOCID($id)
{
	$sql="SELECT our_company_prefix,enquiry_id_counter FROM
		   ems_our_company
		   WHERE our_company_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];	
}



function incrementEnquiryIdForOCID($id)
{
	
	
	$r=getEnquiryIdCounterForOCID($id);
	$r = intval($r);
	$r++;
	$enquiryIdLeangth = strlen($r);
	
	if($enquiryIdLeangth==1)
	{
	 $r = "000".$r;	
	}
	else if($enquiryIdLeangth==2)
	{
	 $r = "00".$r;	
	}
	else if($enquiryIdLeangth==3)
	{
	 $r = "0".$r;	
	}
	
	
	$sql="UPDATE ems_our_company
	      SET enquiry_id_counter='$r'
		  WHERE our_company_id=$id";
		  
	
	
	dbQuery($sql);	  
}

	
function resetAllEnquiryIdCountersOC()
{
		$sql="UPDATE ems_our_company SET enquiry_id_reset_date=NOW(), enquiry_id_counter=1";
		dbQuery($sql);
		return "success";
}



function getEnquiryIdResetDateOC($oc_id)
{
	$sql="SELECT enquiry_id_reset_date FROM ems_our_company WHERE our_company_id=$oc_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
}



function resetEnquiryIdCounterForOC($oc_id)
{
	$sql="UPDATE ems_our_company SET enquiry_id_reset_date=NOW(), enquiry_id_counter='0001' WHERE our_company_id=$oc_id";
		dbQuery($sql);
		return "success";
}

?>