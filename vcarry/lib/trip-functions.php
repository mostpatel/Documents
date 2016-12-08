<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("customer-functions.php");
require_once("vehicle-insurance-functions.php");
require_once("account-sales-functions.php");
require_once("insurance-company-functions.php");
require_once("vehicle-functions.php");
require_once("account-functions.php");
require_once("driver-functions.php");
require_once("account-jv-functions.php");
require_once("account-ledger-functions.php");
require_once("vehicle-sales-functions.php");
require_once("inventory-sales-functions.php");
require_once("inventory-item-functions.php");
require_once("nonstock-sales-functions.php");
require_once("common.php");
require_once("bd.php");

function insertTrip($customer_id,$from_shipping_id,$to_shipping_id,$trip_datetime,$vehicle_type_id,$driver_id,$fare)
{
		if(isset($trip_datetime) && validateForNull($trip_datetime))
    {
	$trip_datetime = str_replace('/', '-', $trip_datetime);
	$trip_datetime=date('Y-m-d H:i:s',strtotime($trip_datetime));
	}
	if(!checkForNumeric($driver_id) || $driver_id<0)
	$driver_id="NULL";
	if(checkForNumeric($customer_id,$from_shipping_id,$to_shipping_id,$vehicle_type_id,$fare) && validateForNull($trip_datetime))
	{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_trip ( `trip_datetime`, `from_shipping_location_id`, `to_shipping_location_id`, `vehicle_type_id`, `driver_id`, `fare`, `created_by`, `last_modified_by`, `date_added`, `date_modified`, `trip_status`, `customer_id`) VALUES ('$trip_datetime',$from_shipping_id,$to_shipping_id,$vehicle_type_id,$driver_id,$fare,$admin_id,$admin_id,NOW(),NOW(),1,$customer_id)";
		dbQuery($sql);
		return dbInsertId();
	}
	
	return "error";
}

function updateTrip($trip_id,$customer_id,$from_shipping_id,$to_shipping_id,$trip_datetime,$vehicle_type_id,$driver_id,$fare,$trip_status)
{
	if(checkForNumeric($trip_id,$customer_id,$from_shipping_id,$to_shipping_id,$vehicle_type_id,$driver_id,$fare) && validateForNull($trip_datetime))
	{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="UPDATE edms_trip SET `trip_datetime` = '$trip_datetime', `from_shipping_location_id` = $from_shipping_id, `to_shipping_location_id` = $to_shipping_id, `vehicle_type_id` = $vehicle_type_id, `driver_id` = $driver_id, `fare` = $fare,  `last_modified_by` = $admin_id,  `date_modified` = NOW(), `trip_status` = $trip_status WHERE trip_id = $trip_id";
		dbQuery($sql);
		return "success";
	}
	
	return "error";
}

function updateDriverForTrip($trip_id,$driver_id)
{
	if(checkForNumeric($trip_id,$driver_id))
	{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="UPDATE edms_trip SET driver_id = $driver_id,`last_modified_by` = $admin_id,  `date_modified` = NOW() WHERE trip_id = $trip_id";
		dbQuery($sql);
		$driver_allocated_trip_status_id = getTripDriverAllocatedStatusId();
	    updateTripStatusForTripId($driver_allocated_trip_status_id,$trip_id);
		return true;
	}
	return false;
}

function deleteTrip($trip_id)
{
	if(checkForNumeric($trip_id))
	{
	$sql="DELETE FROM edms_trip WHERE trip_id = $trip_id";	
	dbQuery($sql);	
	}
}

function getTripById($trip_id)
{
	if(checkForNumeric($trip_id))
	{
		$sql="SELECT `trip_id`, `trip_datetime`, `from_shipping_location_id`, `to_shipping_location_id`,from_location.shipping_name as from_shipping_location,from_location.city_id as from_city_id, from_location.area_id as from_area_id , to_location.shipping_name as to_shipping_location, to_location.city_id as to_city_id,  to_location.area_id as to_area_id,  edms_vehicle_type.`vehicle_type_id`, edms_driver.`driver_id`, driver_name, `fare`, `edms_trip`.`created_by`, `edms_trip`.`last_modified_by`, `edms_trip`.`date_added`, `edms_trip`.`date_modified`, `trip_status`, edms_trip.`customer_id`, status, customer_name, (SELECT MIN(customer_contact_no) FROM edms_customer_contact_no WHERE edms_customer_contact_no.customer_id = edms_customer.customer_id GROUP BY edms_customer_contact_no.customer_id) as customer_contact_no FROM `edms_trip` 
		INNER JOIN edms_customer ON edms_customer.customer_id = edms_trip.customer_id
INNER JOIN edms_shipping_locations as from_location ON edms_trip.from_shipping_location_id = from_location.shipping_location_id 
INNER JOIN edms_shipping_locations as to_location ON edms_trip.to_shipping_location_id = to_location.shipping_location_id  
INNER JOIN edms_trip_status ON edms_trip_status.status_id = edms_trip.trip_status
INNER JOIN edms_vehicle_type ON edms_vehicle_type.vehicle_type_id = edms_trip.vehicle_type_id
LEFT JOIN edms_driver ON edms_driver.driver_id = edms_trip.driver_id
 WHERE edms_trip.trip_id = $trip_id";

$result = dbQuery($sql);
$resultArray = dbResultToArray($result);
return $resultArray[0];		
	}
}


function getAllTripsForCustomer($customer_id,$trip_status_in_string=false)
{
	if(checkForNumeric($customer_id))
	{
		$sql="SELECT `trip_id`, `trip_datetime`, `from_shipping_location_id`, from_location.shipping_name as from_shipping_location,from_location.city_id as from_city_id, from_location.area_id as from_area_id, `to_shipping_location_id`, to_location.shipping_name as to_shipping_location, to_location.city_id as to_city_id,  to_location.area_id as to_area_id,  `vehicle_type_id`, `driver_id`, `fare`, `edms_trip`.`created_by`, `edms_trip`.`last_modified_by`, `edms_trip`.`date_added`, `edms_trip`.`date_modified`, `trip_status`, edms_trip.`customer_id`, status 
FROM `edms_trip` 
INNER JOIN edms_shipping_locations as from_location ON edms_trip.from_shipping_location_id = from_location.shipping_location_id 
INNER JOIN edms_shipping_locations as to_location ON edms_trip.to_shipping_location_id = to_location.shipping_location_id 
INNER JOIN edms_trip_status ON edms_trip_status.status_id = edms_trip.trip_status
  WHERE edms_trip.customer_id = $customer_id";
  if(validateForNull($trip_status_in_string))
  $sql=$sql." AND trip_status IN ($trip_status_in_string)";
$result = dbQuery($sql);
$resultArray = dbResultToArray($result);
return $resultArray;	
		
	}
	
}


function getAllTripsForDriver($driver_id)
{
	if(checkForNumeric($driver_id))
	{
		$sql="SELECT `trip_id`, `trip_datetime`, `from_shipping_location_id`, from_location.shipping_name as from_shipping_location,from_location.city_id as from_city_id, from_location.area_id as from_area_id, `to_shipping_location_id`, to_location.shipping_name as to_shipping_location, to_location.city_id as to_city_id,  to_location.area_id as to_area_id,  edms_vehicle_type.`vehicle_type_id`, edms_driver.`driver_id`, `fare`, `edms_trip`.`created_by`, `edms_trip`.`last_modified_by`, `edms_trip`.`date_added`, `edms_trip`.`date_modified`, `trip_status`, edms_trip.`customer_id`, status, customer_name 
FROM `edms_trip` 
INNER JOIN edms_shipping_locations as from_location ON edms_trip.from_shipping_location_id = from_location.shipping_location_id 
INNER JOIN edms_shipping_locations as to_location ON edms_trip.to_shipping_location_id = to_location.shipping_location_id 
INNER JOIN edms_trip_status ON edms_trip_status.status_id = edms_trip.trip_status
INNER JOIN edms_driver  ON edms_driver.`driver_id` = edms_trip.trip_id
INNER JOIN edms_vehicle_type ON edms_vehicle_type.vehicle_type_id = edms_trip.vehicle_type_id
INNER JOIN edms_customer ON edms_customer.customer_id = edms_trip.customer_id
  WHERE edms_trip.driver_id = $driver_id";
$result = dbQuery($sql);
$resultArray = dbResultToArray($result);
return $resultArray;	
		
	}
	
}


function getAllTripsForDriverByEmail($email)
{
	if(validateForNull($email))
	{
		$sql="SELECT `trip_id`, `trip_datetime`, `from_shipping_location_id`, from_location.shipping_name as from_shipping_location,from_location.city_id as from_city_id, from_location.area_id as from_area_id, `to_shipping_location_id`, to_location.shipping_name as to_shipping_location, to_location.city_id as to_city_id,  to_location.area_id as to_area_id,  edms_vehicle_type.`vehicle_type_id`, edms_driver.`driver_id`, `fare`, `edms_trip`.`created_by`, `edms_trip`.`last_modified_by`, `edms_trip`.`date_added`, `edms_trip`.`date_modified`, `trip_status`, edms_trip.`customer_id`, status, customer_name FROM `edms_trip` 
INNER JOIN edms_shipping_locations as from_location ON edms_trip.from_shipping_location_id = from_location.shipping_location_id 
INNER JOIN edms_shipping_locations as to_location ON edms_trip.to_shipping_location_id = to_location.shipping_location_id 
INNER JOIN edms_trip_status ON edms_trip_status.status_id = edms_trip.trip_status
INNER JOIN edms_driver  ON edms_driver.`driver_id` = edms_trip.driver_id
INNER JOIN edms_vehicle_type ON edms_vehicle_type.vehicle_type_id = edms_trip.vehicle_type_id
INNER JOIN edms_customer ON edms_customer.customer_id = edms_trip.customer_id
  WHERE edms_driver.email = '$email'";

$result = dbQuery($sql);
$resultArray = dbResultToArray($result);
return $resultArray;	
		
	}
	
}


function getAllUnAssignedTrips()
{
	
		$sql="SELECT `trip_id`, `trip_datetime`, `from_shipping_location_id`, from_location.shipping_name as from_shipping_location,from_location.city_id as from_city_id, from_location.area_id as from_area_id, `to_shipping_location_id`, to_location.shipping_name as to_shipping_location, to_location.city_id as to_city_id,  to_location.area_id as to_area_id,  `vehicle_type_id`, `driver_id`, `fare`, `edms_trip`.`created_by`, `edms_trip`.`last_modified_by`, `edms_trip`.`date_added`, `edms_trip`.`date_modified`, `trip_status`, edms_trip.`customer_id`, customer_name, status FROM `edms_trip` INNER JOIN edms_shipping_locations as from_location ON edms_trip.from_shipping_location_id = from_location.shipping_location_id 
INNER JOIN edms_shipping_locations as to_location ON edms_trip.to_shipping_location_id = to_location.shipping_location_id  INNER JOIN edms_customer ON edms_customer.customer_id =  edms_trip.customer_id INNER JOIN edms_trip_status ON edms_trip_status.status_id = edms_trip.trip_status WHERE edms_trip.driver_id IS NULL ORDER BY trip_datetime";
$result = dbQuery($sql);
$resultArray = dbResultToArray($result);
return $resultArray;	
		

}

function getAllTripStatus()
{
	$sql="SELECT * FROM edms_trip_status";
	$result = dbQuery($sql);
	$resultArray = dbResultToArray($result);
	return $resultArray;
}

function getTripDriverAllocatedStatusId()
{
	
	$sql="SELECT * FROM edms_trip_status WHERE status='Driver Allocated'";
	$result = dbQuery($sql);
	$resultArray = dbResultToArray($result);
	return $resultArray[0][0];
	
}

function getAllUnfinishedTripStatuses()
{
	
	return array(1,2,3,4,5);
}
function getAllfinishedTripStatuses()
{
	
	return array(6);
}
function getAllCancelledTripStatuses()
{
	
	return array(7);
}
		
function updateTripStatusForTripId($trip_status,$trip_id)
{
	if(checkForNumeric($trip_status,$trip_id))
	{
		$sql="UPDATE edms_trip SET trip_status = $trip_status WHERE trip_id = $trip_id";
		dbQuery($sql);
		return "success";
	}
}	

function insertAcceptedDriverForTrip($driver_id,$trip_id,$loc,$acc_time)
{
	$duplicate=checkForDuplicateTripDriverAccepted($driver_id, $trip_id);
	if(checkForNumeric($driver_id,$trip_id,$acc_time) && validateForNull($loc) && !$duplicate)
	{
	$acc_time = date('Y-m-d',strtotime($acc_time));	
	$sql="INSERT INTO edms_trip_driver_accepted(`driver_id`, `trip_id`, `location`, `acceptance_time`) VALUES ($driver_id,$trip_id,'$loc','$acc_time')";	
	$result = dbQuery($sql);
	$driver_accepted_id = dbInsertId();
	
	return $driver_accepted_id;	
	}
}

function checkForDuplicateTripDriverAccepted($driver_id, $trip_id, $id=false)
{
	try{
		if(checkForNumeric($driver_id,$trip_id))
		{
		$sql="SELECT driver_accepted_id 
			  FROM 
			  edms_trip_driver_accepted 
			  WHERE driver_id=$driver_id AND trip_id = $trip_id";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND driver_accepted_id!=$id";		  
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
		return false;
		}
	catch(Exception $e)
	{
		
		}
	
	}


function getAllDriversForTrip($trip_id)
{
	if(checkForNumeric($trip_id))
	{
		
		$sql="SELECT edms_trip_driver_accepted.driver_id, `driver_name`, `contact_no_1`, `contact_no_2`, `vehicle_type_id`, edms_driver.`area_id`, `type`, `fixed_amount`, `share_expense`, edms_driver.`created_by`, edms_driver.`last_updated_by`, edms_driver.`date_added`, edms_driver.`date_modified`, edms_driver.`ledger_id`, email, location, acceptance_time FROM edms_trip_driver_accepted INNER JOIN edms_driver ON edms_driver.driver_id = edms_trip_driver_accepted.driver_id WHERE trip_id = $trip_id GROUP BY edms_trip_driver_accepted.driver_id";
	$result = dbQuery($sql);
	$resultArray = dbResultToArray($result);
	return $resultArray;
	}
}

function getSalesIdForTripId($trip_id)
{
	$sql="SELECT sales_id FROM edms_ac_sales WHERE auto_rasid_type=4 AND auto_id = $trip_id";
	$result = dbQuery($sql);
	$resultArray = dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
	else
	return false;
}

function insertTripStoppedDataFromApp($trip_id,$start_time,$stop_time,$start_latlong,$stop_latlong,$trip_distance,$memo_amount,$labour_amount,$cash_received)
{
	if(checkForNumeric($trip_id,$start_time,$stop_time,$trip_distance,$memo_amount,$labour_amount,$cash_received) && validateForNull($start_latlong,$stop_latlong))
	{
		$sql="INSERT INTO `edms_trip_stopped_appdata`( `trip_id`, `trip_start_time`, `trip_stop_time`, `trip_start_latlong`, `trip_stop_latlong`, `trip_distance`, `memo_amount`, `labour_amount`, `cash_received`, date_added) VALUES ($trip_id,'$start_time' , '$stop_time','$start_latlong','$stop_latlong',$trip_distance,$memo_amount,$labour_amount,$cash_received, NOW())";		
		dbQuery($sql);
		return dbInsertId();
	}
	return "error";
}

function insertDriverJVForTrip($trip_id)
{
	$trip = getTripById($trip_id);
	$sales_id = getSalesIdForTripId($trip_id);
	$sales=getSaleById($sales_id);
	$amount = $sales['amount'];
	$driver = getDriverById($trip['driver_id']);
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	if($driver['type']==2)
	{
		$driver_share = $driver['share_expense'];
		$driver_share_amount = $amount*($driver_share/100);
		$driver_expense_ledger = getDriverExpenseLedgerForOC($oc_id);
		$driver_ledger = $driver['ledger_id'];
		deleteDriverJvForTripId($trip_id);
		$driver_jv=getDriverJvForTrip($trip_id);
		if(!$driver_jv)
		return addJV($driver_share_amount,date('d/m/Y',strtotime($sales['trans_date'])),'L'.$driver_ledger,'L'.$driver_expense_ledger,'Driver Trip Commision',11,$trip_id,$oc_id);
	}
	return "error";
}

function deleteDriverJvForTripId($trip_id)
{
	$driver_jv=getDriverJvForTrip($trip_id);
	if(checkForNumeric($driver_jv['jv_id']))
	removeJV($driver_jv['jv_id']);
}


function getDriverJvForTrip($trip_id)
{
	$driver_jv=getJVByAutoRasidTypeAndId(11,$trip_id);
	if(checkForNumeric($driver_jv['jv_id']))
	return $driver_jv;
	else
	return false;
}
?>