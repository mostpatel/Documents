<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("image-functions.php");
require_once("vehicle-functions.php");
require_once("vehicle-insurance-functions.php");
require_once("delivery-challan-functions.php");
require_once("our-company-function.php");
require_once("common.php");
require_once("bd.php");
		
function listSalesCertificateForCustomer($id){
	try
	{
		
		if(checkForNumeric($id))
		{
		$sql="SELECT sale_cert_id,cert_date, delivery_challan_id, vehicle_id, customer_id, created_by, last_updated_by, date_added, date_modified FROM edms_vehicle_sale_cert WHERE customer_id = $id";
		$result = dbQuery($sql);
		$resultArray =dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		{
			return 	$resultArray;
		}
		else return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}	

function insertSaleCertificateForVehicle($delivery_challan_id,$cert_date){
	try
	{
		
	   
		$vehicle_id = getVehicleIdFromDeliveryChallan($delivery_challan_id);
		$customer_id =getCustomerIdFromDeliveryChallan($delivery_challan_id);
		
		if(checkForNumeric($delivery_challan_id,$vehicle_id,$customer_id) && validateForNull($cert_date)  && !checkForDuplicateSaleCertificate($delivery_challan_id))
		{
			
			
			
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
			
		
			
			$cert_date = str_replace('/', '-', $cert_date);
			$cert_date=date('Y-m-d',strtotime($cert_date));
			
			
			
			$sql="INSERT INTO edms_vehicle_sale_cert
			      (cert_date, delivery_challan_id,  vehicle_id, customer_id, created_by, last_updated_by, date_added, date_modified)
				  VALUES
				  ('$cert_date',$delivery_challan_id,$vehicle_id,$customer_id,$admin_id,$admin_id,NOW(),NOW())";
			
			dbQuery($sql);
			$sale_cert_id=dbInsertId();
			
			return $sale_cert_id;
		}
		else
		{return "error";}
	}
	catch(Exception $e)
	{
	}
	
}	

function deleteSaleCertificateById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="DELETE FROM edms_vehicle_sale_cert WHERE delivery_challan_id = $id";
		dbQuery($sql);
		return "success";
		}
		else return "error";
	}
	catch(Exception $e)
	{
	}
	
}	

function getVehicleIdFromSaleCert($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT vehicle_id FROM edms_vehicle_sale_cert WHERE sale_cert_id = $id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
	}
	
}

function updateSaleCert($delivery_challan_id,$cert_date){
	try
	{
		
	 
		
		if(validateForNull($cert_date) && checkForNumeric($delivery_challan_id))
		{
			
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
			
			$cert_date = str_replace('/', '-', $cert_date);
			$cert_date=date('Y-m-d',strtotime($cert_date));
			
			
			
			$sql="UPDATE edms_vehicle_sale_cert
			      SET cert_date = '$cert_date',  last_updated_by = $admin_id, date_modified = NOW()
				 WHERE delivery_challan_id=$delivery_challan_id";
			dbQuery($sql);
			
			
			return "success";
		}
		else
		{return "error";}
	}
	catch(Exception $e)
	{
	}
	
}	

function getSaleCertByVehicleId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT cert_date, delivery_challan_id, vehicle_id, customer_id, created_by, last_updated_by, date_added, date_modified FROM edms_vehicle_sale_cert WHERE vehicle_id = $id";
		$result = dbQuery($sql);
		$resultArray =dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		{
			return 	$resultArray[0];
		}
		else return false;
		}
		
	}
	catch(Exception $e)
	{
	}
	
}	

function getSaleCertByDeliveryChallanId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT cert_date, delivery_challan_id, vehicle_id, customer_id, created_by, last_updated_by, date_added, date_modified FROM edms_vehicle_sale_cert WHERE delivery_challan_id = $id";
		$result = dbQuery($sql);
		$resultArray =dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		{
			return 	$resultArray[0];
		}
		else return false;
		}
		return false;
	}
	catch(Exception $e)
	{
	}
	
}	


function getSaleCertById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT cert_date, delivery_challan_id, vehicle_id, customer_id, created_by, last_updated_by, date_added, date_modified FROM edms_vehicle_sale_cert WHERE sale_cert_id = $id";
		$result = dbQuery($sql);
		$resultArray =dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		{
			return 	$resultArray[0];
		}
		else return false;
		}
		
	}
	catch(Exception $e)
	{
	}
	
}	


function checkForDuplicateSaleCertificate($id)
{
	$sql="SELECT sale_cert_id FROM edms_vehicle_sale_cert
	      WHERE delivery_challan_id=$id";
		  		  
		$result=dbQuery($sql);	
		if(dbNumRows($result)>0)
		{
			return true; //duplicate found
			} 
		else
		{
			return false;
			}	 	  
	}

?>