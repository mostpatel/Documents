<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("image-functions.php");
require_once("common.php");
require_once("bd.php");
require_once("file-functions.php");
require_once("loan-functions.php");
require_once("customer-functions.php");
require_once("rto-work-functions.php");
		
function listVehicles(){
	
	try
	{
	}
	catch(Exception $e)
	{
	}
	
}	

function insertVehicle($model_id,$vehicle_reg_no,$vehicle_reg_date,$vehicle_engine_no,$vehicle_chasis_no,$vehicle_type_id,$model_year,$condition,$vehicle_company_id,$vehicle_delaer_id,$fitness_exp_date,$permit_exp_date,$tax_exp_date,$file_id,$customer_id,$proof_type_id_array,$proof_no_array,$proof_img_array,$scanImg){
	try
	{
		
		$vehicle_reg_no=clean_data($vehicle_reg_no);
		if($vehicle_reg_no!="NA" && $vehicle_reg_no!="na" && $vehicle_reg_no!="Na" && $vehicle_reg_no!="nA")
		$vehicle_reg_no=stripVehicleno($vehicle_reg_no);
		if(!validateForNull($vehicle_reg_no))
		$vehicle_reg_no="NA";
		$vehicle_reg_date=clean_data($vehicle_reg_date);
		$vehicle_engine_no=clean_data($vehicle_engine_no);
		$vehicle_chasis_no=clean_data($vehicle_chasis_no);
		$fitness_exp_date=clean_data($fitness_exp_date);
		$permit_exp_date=clean_data($permit_exp_date);
		$tax_exp_date=clean_data($tax_exp_date);
		
		if(checkForNumeric($model_id,$vehicle_company_id,$vehicle_delaer_id,$vehicle_type_id) && validateForNull($vehicle_engine_no,$vehicle_reg_date,$vehicle_chasis_no,$model_year)  && !checkForDuplicateVehicle($vehicle_chasis_no,$vehicle_reg_no,$file_id))
		{
			
			if(!checkForNumeric($vehicle_reg_no[0],$vehicle_reg_no[1]) && ($vehicle_reg_no[2]=='0'))
			{
				$vehicle_reg_no=substr($vehicle_reg_no,0,2).substr($vehicle_reg_no,3);
			}
			$vehicle_reg_no=strtoupper($vehicle_reg_no);	
			$admin_id=$_SESSION['adminSession']['admin_id'];
			
			$vehicle_reg_date = str_replace('/', '-', $vehicle_reg_date);
			$vehicle_reg_date=date('Y-m-d',strtotime($vehicle_reg_date));
			
			$fitness_exp_date = str_replace('/', '-', $fitness_exp_date);
			$fitness_exp_date=date('Y-m-d',strtotime($fitness_exp_date));
			
			$permit_exp_date = str_replace('/', '-', $permit_exp_date);
			$permit_exp_date=date('Y-m-d',strtotime($permit_exp_date));
			
			$tax_exp_date = str_replace('/', '-', $tax_exp_date);
			$tax_exp_date=date('Y-m-d',strtotime($tax_exp_date));
		
			
			$sql="INSERT INTO fin_vehicle
			      (model_id, vehicle_reg_no, vehicle_reg_date, vehicle_engine_no, vehicle_chasis_no, vehicle_type_id, vehicle_model, vehicle_condition, vehicle_company_id, vehicle_dealer_id, fitness_exp_date, permit_exp_date, tax_exp_Date, created_by, last_updated_by, date_added, date_modified, file_id, customer_id)
				  VALUES
				  ($model_id,'$vehicle_reg_no','$vehicle_reg_date','$vehicle_engine_no','$vehicle_chasis_no',$vehicle_type_id,'$model_year',$condition,$vehicle_company_id,$vehicle_delaer_id,'$fitness_exp_date','$permit_exp_date','$tax_exp_date',$admin_id,$admin_id,NOW(),NOW(),$file_id,$customer_id)";
				  
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
	}
	catch(Exception $e)
	{
	}
	
}	

function updateVehicle($id,$file_id,$model_id,$vehicle_reg_no,$vehicle_reg_date,$vehicle_engine_no,$vehicle_chasis_no,$vehicle_type_id,$model_year,$condition,$vehicle_company_id,$vehicle_delaer_id,$fitness_exp_date,$permit_exp_date,$tax_exp_date,$proof_type_id_array,$proof_no_array,$proof_img_array,$scanImg){
	
	try
	{
		$vehicle_reg_no=clean_data($vehicle_reg_no);
		if($vehicle_reg_no!="NA" && $vehicle_reg_no!="na" && $vehicle_reg_no!="Na" && $vehicle_reg_no!="nA")
		$vehicle_reg_no=stripVehicleno($vehicle_reg_no);
		$vehicle_reg_date=clean_data($vehicle_reg_date);
		$vehicle_engine_no=clean_data($vehicle_engine_no);
		$vehicle_chasis_no=clean_data($vehicle_chasis_no);
		$fitness_exp_date=clean_data($fitness_exp_date);
		$permit_exp_date=clean_data($permit_exp_date);	
		$tax_exp_date=clean_data($tax_exp_date);
		
		if(checkForNumeric($model_id,$vehicle_company_id,$vehicle_delaer_id,$vehicle_type_id) && validateForNull($vehicle_engine_no,$vehicle_reg_date,$vehicle_chasis_no,$model_year)  && !checkForDuplicateVehicle($vehicle_chasis_no,$vehicle_reg_no,$file_id,$id))
		{
			
			if(!checkForNumeric($vehicle_reg_no[0],$vehicle_reg_no[1]) && ($vehicle_reg_no[2]=='0'))
			{
				$vehicle_reg_no=substr($vehicle_reg_no,0,2).substr($vehicle_reg_no,3);
				}
			$vehicle_reg_no=strtoupper($vehicle_reg_no);	
			$admin_id=$_SESSION['adminSession']['admin_id'];
			
			$vehicle_reg_date = str_replace('/', '-', $vehicle_reg_date);
			$vehicle_reg_date=date('Y-m-d',strtotime($vehicle_reg_date));
			
			$fitness_exp_date = str_replace('/', '-', $fitness_exp_date);
			$fitness_exp_date=date('Y-m-d',strtotime($fitness_exp_date));
			
			$permit_exp_date = str_replace('/', '-', $permit_exp_date);
			$permit_exp_date=date('Y-m-d',strtotime($permit_exp_date));
			
			$tax_exp_date = str_replace('/', '-', $tax_exp_date);
			$tax_exp_date=date('Y-m-d',strtotime($tax_exp_date));
			
			
			
			
			$sql="UPDATE fin_vehicle
			      SET model_id = $model_id, vehicle_reg_no = '$vehicle_reg_no', vehicle_reg_date = '$vehicle_reg_date', vehicle_engine_no = '$vehicle_engine_no', vehicle_chasis_no = '$vehicle_chasis_no', vehicle_type_id = $vehicle_type_id, vehicle_model = '$model_year', vehicle_condition = $condition, vehicle_company_id = $vehicle_company_id, vehicle_dealer_id = $vehicle_delaer_id, fitness_exp_date = '$fitness_exp_date', permit_exp_date = '$permit_exp_date', tax_exp_date = '$tax_exp_date', last_updated_by = $admin_id, date_modified = NOW()
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

function checkForDuplicateVehicle($chasis_no,$reg_no,$file_id,$id=false)
{
	if((!defined('DUP_VEH_CHECK') || DUP_VEH_CHECK==1) && $reg_no!="NA" && $reg_no!="na")
	{
	$sql="SELECT vehicle_id FROM fin_vehicle,fin_file
	      WHERE 
		 ( vehicle_reg_no='$reg_no'";
	//if($chasis_no!="NA" && $chasis_no!="na" && $chasis_no!=123456)
	//$sql=$sql."	 
		//  OR vehicle_chasis_no='$chasis_no'";
	$sql=$sql." OR fin_file.file_id=$file_id )
		  AND fin_vehicle.file_id=fin_file.file_id
		  AND (file_status=1 OR file_status=5)
		  ";
	if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND vehicle_id!=$id";		  
		$result=dbQuery($sql);	
		if(dbNumRows($result)>0)
		{
			$i=0;
			$resultArray=dbResultToArray($result);
			foreach($resultArray as $vehicle)
			{
			$vehicle_id=$vehicle[0];
			$seized_and_sold=checkIfVehicleSeizedAndSold($vehicle_id);
			if(!$seized_and_sold)
			{
				$i++;
				}
			}
			if($i==0)
			return false;
			return true; //duplicate found
			} 
		else
		{
			if(NOC_FOR_DUPLICATE_VEHICLE==1)
			{
			$sql="SELECT vehicle_id FROM fin_vehicle,fin_file
	      WHERE 
		 ( vehicle_reg_no='$reg_no'";
	//if($chasis_no!="NA" && $chasis_no!="na" && $chasis_no!=123456)
	//$sql=$sql."	 
		//  OR vehicle_chasis_no='$chasis_no'";
	$sql=$sql." OR fin_file.file_id=$file_id )
		  AND fin_vehicle.file_id=fin_file.file_id
		  AND (file_status=2 OR file_status=4)
		  AND fin_file.file_id NOT IN (SELECT file_id FROM fin_file_noc)
		  ";
	if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND vehicle_id!=$id";
		
		$result=dbQuery($sql);	
		if(dbNumRows($result)>0)
		{
			return true;
		}
			}
			else
			return false;
			
			}
	}
	return false;
}
function listVehicleProofTypes(){
	
	$sql="SELECT vehicle_document_type_id, vehicle_document_type
	      FROM fin_vehicle_document_type";
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
								$sql="INSERT INTO fin_vehicle_document
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
	 $admin_id=$_SESSION['adminSession']['admin_id'];
	$imageName=clean_data($imageName);
	if(validateForNull($imageName) && checkForNumeric($proof_id))
	{
	 $sql="INSERT INTO fin_vehicle_document_img
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
	$sql="DELETE FROM fin_vehicle_document
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
	
	$sql="DELETE FROM fin_vehicle_document_img
		  WHERE vehicle_document_img_id=$proof_id";
	dbQuery($sql);	
	return "success";
}

function getRegNoFromVehicleID($id)
{
	$sql="SELECT vehicle_reg_no
	      FROM fin_vehicle
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
	      FROM fin_vehicle
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

function getVehicleDetailsByFileId($file_id)
{
	if(checkForNumeric($file_id))
	{
		$sql="SELECT vehicle_id, model_id, vehicle_reg_no, vehicle_reg_date, vehicle_engine_no, vehicle_chasis_no, vehicle_type_id, vehicle_model, vehicle_condition, vehicle_company_id, vehicle_dealer_id, fitness_exp_date, permit_exp_date, tax_exp_date
		      FROM fin_vehicle
			  WHERE file_id=$file_id";
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

function getVehicleIdByFileId($file_id)
{
	if(checkForNumeric($file_id))
	{
		$sql="SELECT vehicle_id
		      FROM fin_vehicle
			  WHERE file_id=$file_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0][0];
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
		$oc_id=$_SESSION['adminSession']['oc_id'];
		$reg_no=clean_data($reg_no);
		$sql="SELECT fin_file.file_id
		      FROM fin_vehicle,fin_file
			  WHERE vehicle_reg_no='$reg_no'
			  AND fin_file.file_id=fin_vehicle.file_id
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
function getVehicleIdFromRegNo($reg_no)
{
	$reg_no=clean_data($reg_no);
	$reg_no=stripVehicleno($reg_no);
	if(validateForNull($reg_no))
	{
		$oc_id=$_SESSION['adminSession']['oc_id'];
		$reg_no=clean_data($reg_no);
		$sql="SELECT fin_vehicle.vehicle_id
		      FROM fin_vehicle,fin_file
			  WHERE vehicle_reg_no='$reg_no'
			  AND fin_file.file_id=fin_vehicle.file_id
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
		$sql="SELECT fin_vehicle_document.vehicle_document_id,fin_vehicle_document.vehicle_document_type_id,vehicle_document_type,vehicle_document_no
		      FROM fin_vehicle,fin_vehicle_document,fin_vehicle_document_type
			  WHERE file_id=$file_id
			  AND fin_vehicle.vehicle_id=fin_vehicle_document.vehicle_id
			  AND fin_vehicle_document.vehicle_document_type_id=fin_vehicle_document_type.vehicle_document_type_id";
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
		$sql="SELECT vehicle_document_img_id,vehicle_document_img_href FROM fin_vehicle_document_img WHERE vehicle_document_id=$id";
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

function insertVehicleSeize($seize_date,$sold,$vehicle_id,$file_id,$remarks="NA",$godown_id = NULL)
{
	
	$admin_id=$_SESSION['adminSession']['admin_id'];
	$remarks=clean_data($remarks);
	if(!is_numeric($godown_id))
	$godown_id="NULL";
	if(checkForNumeric($file_id,$vehicle_id,$sold) && validateForNull($seize_date) && !checkForDuplicateVehicleSeize($vehicle_id,$file_id))
	{
	if($vehicle_id==0)
	$vehicle_id="NULL";	
	$seize_date = str_replace('/', '-', $seize_date);
	$seize_date=date('Y-m-d',strtotime($seize_date));
	
	
	$sql="INSERT INTO fin_vehicle_seize (seize_date,sold,remarks,date_added,date_modified,created_by,last_updated_by,file_id,vehicle_id,godown_id)
	      VALUES ('$seize_date','$sold','$remarks',NOW(),NOW(),$admin_id,$admin_id,$file_id,$vehicle_id,$godown_id)";
	  
	dbQuery($sql);
	return "success";
	}
	return "error";
}	

function insertReleaseSeizedVehicle($file_id, $release_date, $remarks)
{
	
	$admin_id=$_SESSION['adminSession']['admin_id'];
	$remarks=clean_data($remarks);
	
	$seize_details=getVehicleSeizeDetailsByFileId($file_id);
	$seize_id=$seize_details['seize_id'];
	
	$delete_result=deleteVehicleSeize($seize_id);
	if($delete_result!="success")
	return "error";
	$vehicle_id = $seize_details['vehicle_id'];
	if(!checkForNumeric($vehicle_id))
	$vehicle_id=0;
	$sold = $seize_details['sold'];
	$seize_remarks = $seize_details['remarks'];
	$date_added = $seize_details['date_added'];
	$date_modified = $seize_details['date_modified'];
	$created_by = $seize_details['created_by'];
	$last_updated_by = $seize_details['last_updated_by'];
	$godown_id= $seize_details['godown_id'];
	$seize_date = $seize_details['seize_date'];
	if(!checkForNumeric($godown_id))
	$godown_id="NULL";
	if(checkForNumeric($file_id,$vehicle_id,$sold,$created_by,$last_updated_by,$admin_id) && validateForNull($seize_date,$release_date))
	{
	if($vehicle_id==0)
	$vehicle_id="NULL";	
	$release_date = str_replace('/', '-', $release_date);
	$release_date=date('Y-m-d',strtotime($release_date));
	
	
	$sql="INSERT INTO fin_vehicle_seize_released(seize_date,sold,seize_remarks,date_added,date_modified,created_by,last_updated_by,file_id,vehicle_id,godown_id,release_date,released_by,remarks)
	      VALUES ('$seize_date','$sold','$seize_remarks','$date_added','$date_modified',$created_by,$last_updated_by,$file_id,$vehicle_id,$godown_id,'$release_date',$admin_id,'$remarks')";
	 
	$release_id=dbQuery($sql);
	
	return "success";
	}
	return "error";
}	

function checkForDuplicateVehicleSeize($vehicle_id,$file_id)
{
	if(checkForNumeric($file_id,$vehicle_id))
	{
		$sql="SELECT seize_id FROM fin_vehicle_seize WHERE file_id=$file_id";
		if($vehicle_id!=0)
		$sql=$sql." OR vehicle_id=$vehicle_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
}

function updateVehicleSeize($seize_id,$sold,$seize_date,$remarks="NA",$godown_id=NULL)
{
	$admin_id=$_SESSION['adminSession']['admin_id'];
	$remarks=clean_data($remarks);
	if(!is_numeric($godown_id))
	$godown_id="NULL";
	$sql="SELECT fin_vehicle.vehicle_id,vehicle_reg_no FROM fin_vehicle,fin_vehicle_seize WHERE fin_vehicle.vehicle_id=fin_vehicle_seize.vehicle_id AND seize_id=$seize_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		$i=0;
		if(dbNumRows($result)>0)
		{
			$reg_no=$resultArray[0][1];
			$or_vehicle_id=$resultArray[0][0];
			if(validateForNull($or_vehicle_id))
			{
			$vehicle_ids=getVehicleIdFromRegNo($reg_no);	
		
			foreach($vehicle_ids as $vehicle)
			{
				$vehicle_id=$vehicle['vehicle_id'];
				if($vehicle_id!=$or_vehicle_id)
				{
					
					$file_id=getFileidFromVehicleId($vehicle_id);
					$file_status=getFileStatusforFile($file_id);
					if($file_status==1)
					{
						$s=checkIfVehicleSeizedAndSold($vehicle_id);
						if(!$s) // if only seized return false 
						{
						$i++;
						}
					}
				}
			}
			}
			if(validateForNull($seize_date))
			{
			$seize_date = str_replace('/', '-', $seize_date);
			$seize_date=date('Y-m-d',strtotime($seize_date));	
			$sql="UPDATE fin_vehicle_seize SET seize_date='$seize_date',remarks = '$remarks',date_modified = NOW(), last_updated_by = $admin_id, godown_id = $godown_id ";
			if($i==0 || $sold==1)
			$sql=$sql." ,sold='$sold'";
			$sql=$sql." WHERE seize_id=$seize_id";
			dbQuery($sql);
			return "success";
			}
		
	}
	return "error";	
}

function deleteVehicleSeize($seize_id)
{
	
	if(checkForNumeric($seize_id))
	{
		$sql="SELECT fin_vehicle.vehicle_id,vehicle_reg_no FROM fin_vehicle,fin_vehicle_seize WHERE fin_vehicle.vehicle_id=fin_vehicle_seize.vehicle_id AND seize_id=$seize_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		$i=0;
		if(dbNumRows($result)>0)
		{
			$reg_no=$resultArray[0][1];
			$or_vehicle_id=$resultArray[0][0];
			if(validateForNull($or_vehicle_id))
			{
			$vehicle_ids=getVehicleIdFromRegNo($reg_no); // get vehicle ids for the same reg no
			foreach($vehicle_ids as $vehicle)
			{
				$vehicle_id=$vehicle['vehicle_id'];
				
				if($vehicle_id!=$or_vehicle_id) // vehicle id except the deletion one
				{
				
					$file_id=getFileidFromVehicleId($vehicle_id);
					$file_status=getFileStatusforFile($file_id);
					if($file_status==1)
					{
						$sold=checkIfVehicleSeizedAndSold($vehicle_id); // returns true if sold or flase if not
						if(!$sold) // if false incrrement i that is if vehicle not sold increment i
						{
						$i++;
						}
					}
				}
			}
			}
		if($i==0)
		{
		$sql="DELETE FROM fin_vehicle_seize WHERE seize_id=$seize_id";
		dbQuery($sql);
		return "success";
		}
		else if($i>0)
		{
			return "open";
			}
		}
		else
		{
			$sql="DELETE FROM fin_vehicle_seize WHERE seize_id=$seize_id";
		$result=dbQuery($sql);
		return "success";
			
			}
		
	}
		return "error";
}

function getFileidFromVehicleId($vehicle_id)
{
	if(checkForNumeric($vehicle_id))
	{
		$sql="SELECT file_id FROM fin_vehicle WHERE vehicle_id=$vehicle_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		{
			$resultArray=dbResultToArray($result);
			return $resultArray[0][0];
			}
		}
	}

function getVehicleSeizeDetailsByVehicleId($vehicle_id)
{
	if(checkForNumeric($vehicle_id))
	{
		$sql="SELECT seize_id,sold,seize_date,remarks,date_added,date_modified,created_by,last_updated_by,file_id,vehicle_id, godown_id
		      FROM fin_vehicle_seize WHERE vehicle_id=$vehicle_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		{
		$resultArray=dbResultToArray($result);	
		return $resultArray[0];
		}
		else return false;
		}
		return "error";	
}	

function getVehicleSeizeDetailsByFileId($file_id)
{
	if(checkForNumeric($file_id))
	{
		$sql="SELECT seize_id,sold,seize_date,remarks,date_added,date_modified,created_by,last_updated_by,file_id,vehicle_id, godown_id
		      FROM fin_vehicle_seize WHERE file_id=$file_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		{
		$resultArray=dbResultToArray($result);	
		return $resultArray[0];
		}
		else return false;
		}
		return "error";	
}	

function checkIFVehicleSeized($file_id)
{
	if(checkForNumeric($file_id))
	{
		
		$sql="SELECT seize_id
		      FROM fin_vehicle_seize WHERE file_id=$file_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}	
}


function checkIfVehicleSeizedAndSold($vehicle_id)
{
	if(checkForNumeric($vehicle_id))
	{
		
		$sql="SELECT seize_id
		      FROM fin_vehicle_seize WHERE vehicle_id=$vehicle_id AND sold=1";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
	}	
	
/* vehicle document functions */

function insertVehicleDocuments($vehicle_id,$rto,$passing,$permit,$insurance,$hp,$bill,$remarks,$vehicle_key,$rto_agent_id="NULL",$work_given_date="NULL",$rto_work_array=NULL,$work_completion_date="NULL",$customer_given_date="NULL",$customer_received_date="NULL")
{
	$remarks=clean_data($remarks);
		$rto_agent_id = clean_data($rto_agent_id);
		$work_given_date = clean_data($work_given_date);
		$work_completion_date = clean_data($work_completion_date);
		$customer_given_date = clean_data($customer_given_date);
		$customer_received_date = clean_data($customer_received_date);
		if(!validateForNull($work_given_date))
		$work_given_date="1970-01-01";
		else
		{
			$work_given_date = str_replace('/', '-', $work_given_date);
			$work_given_date=date('Y-m-d',strtotime($work_given_date));
		}
		if(!validateForNull($work_completion_date))
		$work_completion_date="1970-01-01";
		else
		{
			$work_completion_date = str_replace('/', '-', $work_completion_date);
			$work_completion_date=date('Y-m-d',strtotime($work_completion_date));
		}
		
		if(!validateForNull($customer_given_date))
		$customer_given_date="1970-01-01";
		else
		{
			$customer_given_date = str_replace('/', '-', $customer_given_date);
			$customer_given_date=date('Y-m-d',strtotime($customer_given_date));
		}
		if(!validateForNull($customer_received_date))
		$customer_received_date="1970-01-01";
		else
		{
			$customer_received_date = str_replace('/', '-', $customer_received_date);
			$customer_received_date=date('Y-m-d',strtotime($customer_received_date));
		}
		if(!is_numeric($rto_agent_id) || $rto_agent_id==-1)
		$rto_agent_id="NULL";
		
		
		if(checkForNumeric($rto,$passing,$permit,$insurance,$hp,$bill,$vehicle_key,$vehicle_id) && !checkForDuplicateVehicleDocs($vehicle_id))
		{
			$admin_id=$_SESSION['adminSession']['admin_id'];
			$file_id = getFileidFromVehicleId($vehicle_id);
			$customer_id = getCustomerIdByFileId($file_id);
			
			$sql="INSERT INTO fin_vehicle_docs (`vehicle_id`, `file_id`, `customer_id`, `rto_papers`, `passing`, `permit`, `insurance`, `hp`, `bill`, `remarks`, `vehicle_key`, `rto_agent_id`, `work_given_date`, `work_completion_date`, `created_by`, `last_updated_by`, `date_added`, `date_modified`, customer_given_date, customer_received_date) VALUES ($vehicle_id,$file_id,$customer_id,$rto,$passing,$permit,$insurance,$hp,$bill,'$remarks',$vehicle_key,$rto_agent_id,'$work_given_date','$work_completion_date',$admin_id,$admin_id,NOW(),NOW(),'$customer_given_date','$customer_received_date')";
			
			$result = dbQuery($sql);
			
			if(RTO_WORK==1)
			insertRtoWorkArrayForVehicle($vehicle_id,$rto_work_array);
			
			$vehicle_docs_id  = dbInsertId();
			return $vehicle_docs_id;
		}
	
}

function checkForDuplicateVehicleDocs($vehicle_id,$id=false)
{
	if(checkForNumeric($vehicle_id))
	{
	$sql="SELECT * FROM fin_vehicle_docs WHERE vehicle_id = $vehicle_id";
	if(is_numeric($id))
	$sql=$sql." AND vehicle_id!=$id";
	$result = dbQuery($sql);
	$resultArray = dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
	else
	return false;
	}
	
}

function updateVehicleDocuments($vehicle_id,$rto,$passing,$permit,$insurance,$hp,$bill,$remarks,$vehicle_key,$rto_agent_id="NULL",$work_given_date="NULL",$rto_work_array=NULL,$work_completion_date="NULL",$customer_given_date="NULL",$customer_received_date="NULL")
{
	
	$remarks = clean_data($remarks);
		$rto_agent_id = clean_data($rto_agent_id);
		$work_given_date = clean_data($work_given_date);
		if(!is_numeric($rto_agent_id) || $rto_agent_id==-1)
		$rto_agent_id="NULL";
		if(!validateForNull($work_given_date))
		$work_given_date="1970-01-01";
		else
		{
			$work_given_date = str_replace('/', '-', $work_given_date);
			$work_given_date=date('Y-m-d',strtotime($work_given_date));
		}
		if(!validateForNull($work_completion_date))
		$work_completion_date="1970-01-01";
		else
		{
			$work_completion_date = str_replace('/', '-', $work_completion_date);
			$work_completion_date=date('Y-m-d',strtotime($work_completion_date));
			
		}
		
		if(!validateForNull($customer_given_date))
		$customer_given_date="1970-01-01";
		else
		{
			$customer_given_date = str_replace('/', '-', $customer_given_date);
			$customer_given_date=date('Y-m-d',strtotime($customer_given_date));
		}
		if(!validateForNull($customer_received_date))
		$customer_received_date="1970-01-01";
		else
		{
			$customer_received_date = str_replace('/', '-', $customer_received_date);
			$customer_received_date=date('Y-m-d',strtotime($customer_received_date));
		}
		
		if(checkForNumeric($rto,$passing,$permit,$insurance,$hp,$bill,$vehicle_key,$vehicle_id))
		{
			$admin_id=$_SESSION['adminSession']['admin_id'];
			$file_id = getFileidFromVehicleId($vehicle_id);
			$customer_id = getCustomerIdByFileId($file_id);
			$sql="UPDATE fin_vehicle_docs SET rto_papers = $rto, passing = $passing, permit = $permit, insurance = $insurance, hp = $hp, bill = $bill, vehicle_key=$vehicle_key, remarks = '$remarks', rto_agent_id = $rto_agent_id, work_given_date = '$work_given_date', work_completion_date = '$work_completion_date', last_updated_by = $admin_id, date_modified = NOW(), customer_given_date = '$customer_given_date', customer_received_Date = '$customer_received_date' WHERE vehicle_id = $vehicle_id";
			
			dbQuery($sql);
			if(RTO_WORK==1)
			{
			deleteRtoWorkForVehicle($vehicle_id);	
			insertRtoWorkArrayForVehicle($vehicle_id,$rto_work_array);
			}
			return "success";
		}
		return "error";
}


function getVehicleDocsForVehicleId($vehicle_id)
{
	if(checkForNumeric($vehicle_id))
	{
		$sql="SELECT * FROM fin_vehicle_docs WHERE vehicle_id = $vehicle_id";
		$result = dbQuery($sql);
		
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else 
		return false;	
	}
	
	
}

	
?>