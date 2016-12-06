<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("image-functions.php");
require_once("common.php");
require_once("bd.php");
		
function listInsurances(){
	
	try
	{
	}
	catch(Exception $e)
	{
	}
	
}	

function insertInsurance($issue_date,$issue_exp_date,$idv,$premium,$insurance_company_id,$vehicle_id,$customer_id,$insurance_img_array,$scanImg,$delivery_challan_id="NULL"){
	
	try
	{
		$issue_date=clean_data($issue_date);
		$issue_exp_date=clean_data($issue_exp_date);
		$issue_date = str_replace('/', '-', $issue_date);
		$issue_date=date('Y-m-d',strtotime($issue_date));
		$issue_exp_date = str_replace('/', '-', $issue_exp_date);
		$issue_exp_date=date('Y-m-d',strtotime($issue_exp_date));
		if(checkForNumeric($idv,$premium,$insurance_company_id,$vehicle_id,$customer_id) && validateForNull($issue_date,$issue_exp_date) && !checkForDuplicateInsurance($issue_date,$vehicle_id,$issue_exp_date))
		{
			
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];		
		$sql="INSERT INTO edms_vehicle_insurance
		      (insurance_issue_date, insurance_expiry_date, idv, insurance_premium, insurance_company_id,  vehicle_id, customer_id, created_by, last_updated_by, date_added, date_modified, delivery_challan_id)
			  VALUES ('$issue_date', '$issue_exp_date', $idv, $premium, $insurance_company_id,  $vehicle_id, $customer_id, $admin_id, $admin_id, NOW(), NOW(),$delivery_challan_id)";
		dbQuery($sql);
		$insurance_id=dbInsertId();
		if(checkForNumeric($insurance_id))
		{
		
		addInsuranceProof($insurance_id,$insurance_img_array,$scanImg);
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

function deleteInsuranceForDeliveryChallan($id){
	
	try
	{
		
		if(checkForNumeric($id))
		{
		$sql="DELETE FROM edms_vehicle_insurance WHERE delivery_challan_id = $id";
		dbQuery($sql);
		return "success";
		}
		return "error";
	}
	catch(Exception $e)
	{
	}
	
}	

function updateInsurance($id,$vehicle_id,$issue_date,$issue_exp_date,$idv,$premium,$insurance_company_id,$insurance_img_array,$scanImg){
	
	try
	{
		
		
		$issue_date=clean_data($issue_date);
		$issue_exp_date=clean_data($issue_exp_date);
		$issue_date = str_replace('/', '-', $issue_date);
		$issue_date=date('Y-m-d',strtotime($issue_date));
		$issue_exp_date = str_replace('/', '-', $issue_exp_date);
		$issue_exp_date=date('Y-m-d',strtotime($issue_exp_date));
		
		
		if(checkForNumeric($idv,$premium,$insurance_company_id) && validateForNull($issue_date,$issue_exp_date) && !checkForDuplicateInsurance($issue_date,$vehicle_id,$issue_exp_date,$id))
		{
		
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];		
		$sql="UPDATE edms_vehicle_insurance
		      SET insurance_issue_date = '$issue_date', insurance_expiry_date = '$issue_exp_date', idv = $idv, insurance_premium = $premium, insurance_company_id = $insurance_company_id, last_updated_by = $admin_id,  date_modified = NOW()
			  WHERE insurance_id=$id";
		dbQuery($sql);
		$insurance_id=$id;
		if(checkForNumeric($insurance_id))
		{
		addInsuranceProof($insurance_id,$insurance_img_array,$scanImg);
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

function getInsuranceById($id){
	
	try
	{
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateInsurance($issue_date,$vehicle_id,$expiry_date,$id=false)
{
	
	$sql="SELECT insurance_id
	      FROM edms_vehicle_insurance
		  WHERE ((insurance_issue_date='$issue_date' AND vehicle_id=$vehicle_id) OR (insurance_expiry_date='$expiry_date' AND vehicle_id=$vehicle_id))";
	if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND insurance_id!=$id";	  
	
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	{
		return true;
		}
	else
	{
		return false;
		}	
	}	
	
function addInsuranceProof($insurance_id,$proof_img_array,$scanImgArray)
{
	try
	{
		
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		
		if(is_array($proof_img_array)) // if more than one proof submitted
		{
			
			$len=count($proof_img_array);
			for($i=0;$i<$len;$i++)
			{
								
								
								if(checkForImagesInArray($proof_img_array['name'][$i]))
								{
								  
							    addImagesToInsuranceProof($insurance_id,$proof_img_array,$i);
								}
							   
				
			}
			
		if(is_array($scanImgArray))
								{
								
									if($scanImgArray!=false && isset($scanImgArray[$i]) && is_array($scanImgArray[$i]))
								{
									
									foreach($scanImgArray[$i] as $scanImage)
									{
										
										
										insertImageToInsuranceProof($scanImage,$proof_id);
										}
									}
									
									}
									
			
		}
	}
	catch(Exception $e)
	{
		
	}
	
}	

function addImagesToInsuranceProof($insurance_id,$proof_img_array,$i){
	
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
										   
										   $imageName=addProofImageInsurance($insurance_id,$imagee);
							   
							    			insertImageToInsuranceProof($imageName,$insurance_id);
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
										   
										    $imageName=addProofImageInsurance($insurance_id,$imagee);
							   
							    			insertImageToInsuranceProof($imageName,$insurance_id);
										  }
									  
								  }
	
	}
	
function insertImageToInsuranceProof($imageName,$insurance_id)
{
	 $admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	$imageName=clean_data($imageName);
	if(validateForNull($imageName) && checkForNumeric($insurance_id))
	{
	 $sql="INSERT INTO edms_insurance_img
							   		 (insurance_img_href, insurance_id, created_by, last_updated_by, date_added, date_modified)
									 VALUES
									 ('$imageName',$insurance_id, $admin_id, $admin_id, NOW(), NOW())";
									 
									 dbQuery($sql);

	}
	
}	


function deleteInsuranceProofImage($proof_image_id)
{
	if(checkForNumeric($proof_image_id))
	{
	$sql="DELETE FROM edms_insurance_img
		  WHERE insurance_img_id=$proof_image_id";
	dbQuery($sql);	
	return "success";
	}
	else
	return "error";
} 	


function getInsuranceDetailsFromInsuranceId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT insurance_id,insurance_issue_date, insurance_expiry_date, idv, insurance_premium, insurance_company_id, vehicle_id, customer_id
			  FROM edms_vehicle_insurance
			  WHERE insurance_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		$resultArray[0]['insurance_image']=getInsuranceImgForInsuranceId($id);
		return $resultArray[0];
		}
		else
		return "error";
		}
	}
	catch(Exception $e)
	{
	}
	
}	

function getInsuranceImgForInsuranceId($id)
{
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT insurance_img_id,insurance_img_href
			  FROM edms_insurance_img
			  WHERE insurance_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return "error";
		}
	}
	catch(Exception $e)
	{
	}
	}

function getLatestInsuranceIdForVehicleID($id)
{
	$sql="SELECT max(insurance_expiry_date)
	      FROM edms_vehicle_insurance
		  WHERE vehicle_id=$id
		  GROUP BY vehicle_id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);

	if(dbNumRows($result)>0)
	{
		 $exp_date=$resultArray[0][0];
		 $sql="SELECT insurance_id
	      FROM edms_vehicle_insurance
		  WHERE vehicle_id=$id
		  AND insurance_expiry_date='$exp_date'";
	$result2=dbQuery($sql);
	$resultArray2=dbResultToArray($result2);
	if(dbNumRows($result2)>0)
	{
		return $resultArray2[0][0];
		}
		}
	
	}	
	
function getLatestInsuranceDetailsForVehicleID($id)
{
	$sql="SELECT max(insurance_expiry_date)
	      FROM edms_vehicle_insurance
		  WHERE vehicle_id=$id
		  GROUP BY vehicle_id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);

	if(dbNumRows($result)>0)
	{
		 $exp_date=$resultArray[0][0];
		 $sql="SELECT insurance_id
	      FROM edms_vehicle_insurance
		  WHERE vehicle_id=$id
		  AND insurance_expiry_date='$exp_date'";
	$result2=dbQuery($sql);
	$resultArray2=dbResultToArray($result2);
	if(dbNumRows($result2)>0)
	{
		return getInsuranceDetailsFromInsuranceId($resultArray2[0][0]);
		}
	}
	return false;
	}	
	
function getInsurancesDetailsForVehicleID($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT insurance_id,insurance_issue_date, insurance_expiry_date, idv, insurance_premium, insurance_company_id, vehicle_id, customer_id
			  FROM edms_vehicle_insurance
			  WHERE vehicle_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{	
		
		return $resultArray;
		}
		else
		return "error";
	}
	return "error";
	}			

function getInsurancesForVehicleID($id)
{
	$sql="SELECT *
	      FROM edms_vehicle_insurance
		  WHERE vehicle_id=$id
		  ORDER BY insurance_expiry_date DESC";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);

	if(dbNumRows($result)>0)
	{
		
		return $resultArray;
		
		}
	return "error";	
	
	}

function getInsuranceForDeliveryChallanID($id)
{
	$sql="SELECT insurance_id,insurance_issue_date, insurance_expiry_date, idv, insurance_premium, edms_insurance_company.insurance_company_id, insurance_company_name, vehicle_id, customer_id
	      FROM edms_vehicle_insurance, edms_insurance_company
		  WHERE delivery_challan_id=$id
		  AND edms_insurance_company.insurance_company_id = edms_vehicle_insurance.insurance_company_id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);

	if(dbNumRows($result)>0)
	{
		
		return $resultArray[0];
		
		}
	return "error";	
	
	}			
?>