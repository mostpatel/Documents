<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("image-functions.php");
require_once("common.php");
require_once("bd.php");
require_once("file-functions.php");
require_once("loan-functions.php");




		
function listVehicles(){
	
	try
	{
	}
	catch(Exception $e)
	{
	}
	
}	

function insertVehicle($model_id,$vehicle_reg_no,$vehicle_reg_date,$vehicle_engine_no,$vehicle_chasis_no,$model_year,$condition,$vehicle_company_id,$customer_id,$proof_type_id_array,$proof_no_array,$proof_img_array,$scanImg)
{
	try
	{
		$vehicle_reg_no=clean_data($vehicle_reg_no);
		$vehicle_reg_no=stripVehicleno($vehicle_reg_no);
		$vehicle_reg_date=clean_data($vehicle_reg_date);
		$vehicle_engine_no=clean_data($vehicle_engine_no);
		$vehicle_chasis_no=clean_data($vehicle_chasis_no);
		
		if(checkForNumeric($model_id,$vehicle_company_id) && validateForNull($vehicle_engine_no,$vehicle_reg_date,$vehicle_chasis_no,$model_year)  && !checkForDuplicateVehicle($vehicle_chasis_no,$vehicle_reg_no))
		{
			
			if($vehicle_reg_no[2]=='0' || $vehicle_reg_no[2]==0)
			{
				$vehicle_reg_no=substr($vehicle_reg_no,0,2).substr($vehicle_reg_no,3);
			}
			$vehicle_reg_no=strtoupper($vehicle_reg_no);	
			$admin_id=$_SESSION['EMSadminSession']['admin_id'];
			
			$vehicle_reg_date = str_replace('/', '-', $vehicle_reg_date);
			$vehicle_reg_date=date('Y-m-d',strtotime($vehicle_reg_date));
			
			
			$sql="INSERT INTO ems_vehicle
			
			      (vehicle_model_id, vehicle_reg_no, vehicle_reg_date, vehicle_engine_no, vehicle_chasis_no, vehicle_model, vehicle_condition, vehicle_company_id, created_by, last_updated_by, date_added, date_modified, customer_id)
				  
				  VALUES
				  ($model_id,'$vehicle_reg_no','$vehicle_reg_date','$vehicle_engine_no','$vehicle_chasis_no','$model_year',$condition,$vehicle_company_id,$admin_id,$admin_id,NOW(),NOW(), $customer_id)";
				  
		
		
			dbQuery($sql);
			
			$vehicle_id=dbInsertId();
			
			addVehicleProof($vehicle_id,$vehicle_reg_no,$proof_type_id_array,$proof_no_array,$proof_img_array,$scanImg);
			return "success";
		}
		else
		{return "error";}
	}
	catch(Exception $e)
	{
	}
	
}	

function deleteVehicle($id){
	
	try
	{
		
		$sql="DELETE FROM ems_vehicle
		      WHERE vehicle_id=$id";
		dbQuery($sql);
		return "success";
		
		
	}
	catch(Exception $e)
	{
	}
	
}	

function updateVehicle($id,$model_id,$vehicle_reg_no,$vehicle_reg_date,$vehicle_engine_no,$vehicle_chasis_no,$model_year,$condition,$vehicle_company_id,$proof_type_id_array,$proof_no_array,$proof_img_array,$scanImg){
	
	try
	{
		$vehicle_reg_no=clean_data($vehicle_reg_no);
		$vehicle_reg_no=stripVehicleno($vehicle_reg_no);
		$vehicle_reg_date=clean_data($vehicle_reg_date);
		$vehicle_engine_no=clean_data($vehicle_engine_no);
		$vehicle_chasis_no=clean_data($vehicle_chasis_no);
		
		if(checkForNumeric($model_id,$vehicle_company_id) && validateForNull($vehicle_engine_no,$vehicle_reg_date,$vehicle_chasis_no,$model_year)  && !checkForDuplicateVehicle($vehicle_chasis_no,$vehicle_reg_no,$id))
		{
			
			if($vehicle_reg_no[2]=='0' || $vehicle_reg_no[2]==0)
			{
				$vehicle_reg_no=substr($vehicle_reg_no,0,2).substr($vehicle_reg_no,3);
				}
			$vehicle_reg_no=strtoupper($vehicle_reg_no);	
			$admin_id=$_SESSION['EMSadminSession']['admin_id'];
			
			$vehicle_reg_date = str_replace('/', '-', $vehicle_reg_date);
			$vehicle_reg_date=date('Y-m-d',strtotime($vehicle_reg_date));
			
			
			$sql="UPDATE ems_vehicle
			      SET vehicle_model_id = $model_id, vehicle_reg_no = '$vehicle_reg_no', vehicle_reg_date = '$vehicle_reg_date', vehicle_engine_no = '$vehicle_engine_no', vehicle_chasis_no = '$vehicle_chasis_no',  vehicle_model = '$vehicle_model', vehicle_condition = $condition, vehicle_company_id = $vehicle_company_id, last_updated_by = $admin_id, date_modified = NOW()
				 WHERE vehicle_id=$id";
			dbQuery($sql);
			
			addVehicleProof($id,$vehicle_reg_no,$proof_type_id_array,$proof_no_array,$proof_img_array,$scanImg);
			return "success";
		}
		else
		{return "error";}
	}
	catch(Exception $e)
	{
	}
	
}	

function getVehicleById($id){
	
	try
	{
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateVehicle($chasis_no,$reg_no,$id=false)
{
	
			return false;
			 	  
}


function listVehicleProofTypes(){
	
	$sql="SELECT vehicle_document_type_id, vehicle_document_type
	      FROM ems_vehicle_document_type";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;	  
	}	

function addVehicleProof($vehicle_id,$vehicle_name,$human_proof_type_id_array,$proof_no_array,$proof_img_array,$scanImgArray)
{
	try
	{
		$vehicle_name=clean_data($vehicle_name);
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		
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
								$sql="INSERT INTO ems_vehicle_document
								     (vehicle_document_type_id, vehicle_document_no, vehicle_id, created_by, last_updated_by, date_added, date_modified)
									 VALUES
									 ($human_proof_type_id, '$proof_no', $vehicle_id, $admin_id, $admin_id, NOW(), NOW() )";
								  
								  $result=dbQuery($sql);
								  $proof_id=dbInsertId();
								  
							    addImagesToVehicleProof($vehicle_id,$vehicle_name,$human_proof_type_id,$proof_id,$proof_img_array,$i);
								if($scanImgArray!=false && isset($scanImgArray[$i]) && is_array($scanImgArray[$i]))
								{
									
									foreach($scanImgArray[$i] as $scanImage)
									{
										
										
										insertImageToVehicleProof($scanImage,$proof_id);
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
			$sql="INSERT INTO fin_vehicle_proof
								     (vehicle_document_type_id, vehicle_document_no, vehicle_id, created_by, last_updated_by, date_added, date_modified)
									 VALUES
									 ($human_proof_type_id_array, '$proof_no_array', $vehicle_id, $admin_id, $admin_id, NOW(), NOW() )";
								  
								  $result=dbQuery($sql);
								  $proof_id=dbInsertId();
								  addImagesToVehicleProof($vehicle_id,$vehicle_name,$human_proof_type_id_array,$proof_id,$proof_img_array,0);
								}
		}
	}
	catch(Exception $e)
	{
		
	}
	
}	

function addImagesToVehicleProof($vehicle_id,$vehicle_name,$human_proof_type_id,$proof_id,$proof_img_array,$i){
	
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
										   
										   $imageName=addProofImageVehicle($vehicle_name,$vehicle_id,$human_proof_type_id,$imagee);
							   
							    			insertImageToVehicleProof($imageName,$proof_id);
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
										   
										   $imageName=addProofImageVehicle($vehicle_name,$vehicle_id,$human_proof_type_id,$imagee);
							   
							  				insertImageToVehicleProof($imageName,$proof_id);
										  }
									  
								  }
	
	}
	
function insertImageToVehicleProof($imageName,$proof_id)
{
	 $admin_id=$_SESSION['EMSadminSession']['admin_id'];
	$imageName=clean_data($imageName);
	if(validateForNull($imageName) && checkForNumeric($proof_id))
	{
	 $sql="INSERT INTO ems_vehicle_document_img
							   		 (vehicle_document_img_href, vehicle_document_id, created_by, last_updated_by, date_added, date_modified)
									 VALUES
									 ('$imageName', $proof_id, $admin_id, $admin_id, NOW(), NOW())";
									 
									 dbQuery($sql);
	}
	
}	

function deleteVehicleProof($proof_id)
{
	if(checkForNumeric($proof_id))
	{
	$sql="DELETE FROM ems_vehicle_document
			WHERE vehicle_document_id=$proof_id";
	dbQuery($sql);
	return "success";	
	}
	else
	{
		return "error";
		}
	}

function deleteVehicleProofImage($proof_image_id)
{
	
	$sql="DELETE FROM ems_vehicle_document_img
		  WHERE vehicle_document_img_id=$proof_id";
	dbQuery($sql);	
	return "success";
}

function getRegNoFromVehicleID($id)
{
	$sql="SELECT vehicle_reg_no
	      FROM ems_vehicle
		  WHERE vehicle_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		return $resultArray[0][0];
		}	  
}

function getRegNoFromFileID($id)
{
	$sql="SELECT vehicle_reg_no
	      FROM ems_vehicle
		  WHERE file_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		return $resultArray[0][0];
		}
	else
	return false;		  
}

function getVehicleDetailsByCustomerId($customer_id)
{
	if(checkForNumeric($customer_id))
	{
		$sql="SELECT vehicle_id, vehicle_model_id, vehicle_reg_no, vehicle_reg_date, vehicle_engine_no, vehicle_chasis_no, vehicle_model, vehicle_condition, customer_id, vehicle_company_id
		      FROM ems_vehicle
			  WHERE customer_id=$customer_id";
			  
		
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

function getVehicleDetailsById($vehicle_id)
{
	if(checkForNumeric($vehicle_id))
	{
		$sql="SELECT vehicle_model_id, vehicle_reg_no, vehicle_reg_date, vehicle_engine_no, vehicle_chasis_no, vehicle_model, vehicle_condition, vehicle_company_id, created_by, last_updated_by, date_added, date_modified, customer_id
		      FROM ems_vehicle
			  WHERE vehicle_id=$vehicle_id";
			  
		
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




function getFileIdFromRegNo($reg_no)
{
	$reg_no=clean_data($reg_no);
	$reg_no=stripVehicleno($reg_no);
	if(validateForNull($reg_no))
	{
		$oc_id=$_SESSION['EMSadminSession']['oc_id'];
		$reg_no=clean_data($reg_no);
		$sql="SELECT fin_file.file_id
		      FROM ems_vehicle,fin_file
			  WHERE vehicle_reg_no='$reg_no'
			  AND fin_file.file_id=ems_vehicle.file_id
			  AND file_status!=3
			  AND our_company_id=$oc_id";
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
	
function getVehicleProofByFileId($file_id)
{
	if(checkForNumeric($file_id))
	{
		$sql="SELECT ems_vehicle_document.vehicle_document_id,ems_vehicle_document.vehicle_document_type_id,vehicle_document_type,vehicle_document_no
		      FROM ems_vehicle,ems_vehicle_document,ems_vehicle_document_type
			  WHERE file_id=$file_id
			  AND ems_vehicle.vehicle_id=ems_vehicle_document.vehicle_id
			  AND ems_vehicle_document.vehicle_document_type_id=ems_vehicle_document_type.vehicle_document_type_id";
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

function getVehicleProofimgByProofId($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT vehicle_document_img_id,vehicle_document_img_href FROM ems_vehicle_document_img WHERE vehicle_document_id=$id";
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

function stripVehicleno($reg_no)
{
	$string=$reg_no;
preg_match('#[0-9]+$#', $string, $match);
$end_number=$match[0]; // Output: 8271

$pos = strrpos($string, $end_number);

    if($pos !== false)
    {
        $start_string = substr_replace($string, "", $pos, strlen($end_number));
    }


$new_number=$str = ltrim($end_number, '0');
$new_reg_no=$start_string.$new_number;
return $new_reg_no;
	}	




	
?>