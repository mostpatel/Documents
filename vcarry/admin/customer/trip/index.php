<?php
require_once "../../../lib/cg.php";
require_once "../../../lib/bd.php";
require_once "../../../lib/common.php";
require_once "../../../lib/customer-functions.php";
require_once "../../../lib/vehicle-functions.php";
require_once "../../../lib/currencyToWords.php";
require_once "../../../lib/account-ledger-functions.php";
require_once "../../../lib/account-period-functions.php";
require_once "../../../lib/account-payment-functions.php";
require_once "../../../lib/receipt-type-functions.php";
require_once "../../../lib/shipping-location-functions.php";
require_once "../../../lib/trip-functions.php";
require_once "../../../lib/vehicle-type-functions.php";
require_once "../../../lib/driver-functions.php";
require_once "../../../lib/route-fare-functions.php";
require_once "../../../lib/route-functions.php";
require_once "../../../lib/fare-category-functions.php";

if(isset($_SESSION['edmsAdminSession']['admin_rights']))
$admin_rights=$_SESSION['edmsAdminSession']['admin_rights'];

if(isset($_GET['view']))
{
	if($_GET['view']=='add')
	{
		$content="list_add.php";
	}
	else if($_GET['view']=='details')
	{
		
		$content="details.php";
		$showTitle=false; // to turn off the company title on the top of the page
		}
	else if($_GET['view']=='search')
	{
		$content="search.php";
		
		}	
	else if($_GET['view']=='updateDriver')
	{
		$content="updateDriver.php";
		$trip_id = $_GET['id'];
		$driver_email = $_GET['driver_email'];
		$driver_id=getDriverIdFromDriverEmail($driver_email);
		$driver_location = $_GET['loc'];
		$driver_accpetance_time = $_GET['time'];
		$driver_accepted_id=insertAcceptedDriverForTrip($driver_id,$trip_id,$driver_location,$driver_accpetance_time);
		}
	else if($_GET['view']=='updateStatus')
	{
		$content="updateTripStatus.php";
		$trip_id = $_GET['id'];
		}		
	else if($_GET['view']=='addMultiple')
	{
		$content="add_multiple.php";
		}			
	else
	{
		$content="list_add.php";
	}	
}
else
{
		$content="list_add.php";
}		
if(isset($_GET['action']))
{	
	if($_GET['action']=='add')
	{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,$admin_rights)))
			{
						
			$fare = getRouteFareForTrip($_POST['from_shipping_id'],$_POST['to_shipping_id'],$_POST['vehicle_type_id']);
			if(checkForNumeric($fare))
			{
			}			
			else
			{
				$from_shipping_location = getShippingLocationForshippingLocationId($_POST['from_shipping_id']);
                $to_shipping_location = getShippingLocationForshippingLocationId($_POST['to_shipping_id']);

				$route=getRouteByFromToAreaId($from_shipping_location['area_id'],$to_shipping_location['area_id']);
				
				if(!(is_array($route) && is_numeric($route['route_id'])))
				{
					insertRoute($from_shipping_location['area_id'],$to_shipping_location['area_id']);
					$route=getRouteByFromToAreaId($from_shipping_location['area_id'],$to_shipping_location['area_id']);
				}
				
				$route_fare=getFareForRouteAndVehicleTypeID($route['route_id'],$_POST['vehicle_type_id']);	
				if($route_fare>0)
				$fare=$route_fare;
				else
				{
					$fare_categories = listFareCategories();
					insertRouteFare($route['route_id'],$_POST['vehicle_type_id'],$_POST['route_fare'],$fare_categories[0][0],0);
					$route_fare=getFareForRouteAndVehicleTypeID($route['route_id'],$_POST['vehicle_type_id']);	
					if($route_fare>0)
				    $fare=$route_fare;
				}
				
				
				
				
			}
			if(checkForNumeric($fare) && $fare>0)
			{
				$result=insertTrip($_POST['customer_id'],$_POST['from_shipping_id'],$_POST['to_shipping_id'],$_POST['trip_date']." ".$_POST['trip_time'],$_POST['vehicle_type_id'],$_POST['driver_id'],$fare); // $cheque_return is 0 when inserting a payment			
				
			}
			else
		    {
				$_SESSION['ack']['msg']="ERROR! Fare For Given Route is not DEFINED!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/customer/trip/index.php?id=".$_POST['customer_id']."&state=".$_POST['from_shipping_id']);
				exit;
			}
				if(is_numeric($result))
				{
					$_SESSION['ack']['msg']="Trip successfully added!";
					$_SESSION['ack']['type']=1; // 1 for insert
					$_SESSION['ack']['trip_id'] = $result;
					header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_POST['customer_id']);
					exit;
				}
				else if($result=="date_error")
				{
				$_SESSION['ack']['msg']="Date Should be greater than Books starting date!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/customer/trip/index.php?id=".$_POST['customer_id']."&state=".$_POST['from_shipping_id']);
				exit;
				}
				else
				{
				$_SESSION['ack']['msg']="Invalid Input!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/customer/trip/index.php?id=".$_POST['customer_id']."&state=".$_POST['from_shipping_id']);
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/customer/trip/index.php?id=".$_POST['customer_id']."&state=".$_POST['from_shipping_id']);
				exit;
			}
		}	
	if($_GET['action']=='delete')
	{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{	
				$result=deleteShippingLocation($_GET["lid"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Shipping Location deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_GET['customer_id']);
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/customer/shipping_locations/index.php");
				exit;
			}
		}
	if($_GET['action']=='updateDriver')
	{
		
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				
				$result=updateDriverForTrip($_POST['trip_id'],$_POST['driver_id']);
				if($result)
				{	
				$driver = getDriverById($_POST['driver_id']);
				$_SESSION['ack']['msg']="Driver Allocation updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				$_SESSION['ack']['confirm_driver_trip_id'] = $_POST['trip_id'];
				$_SESSION['ack']['driver_email'] = $driver['email'];
				header("Location: ".WEB_ROOT."admin/customer/trip/index.php?view=details&id=".$_POST['trip_id']);
				exit;
				}
				else{
					
				$_SESSION['ack']['msg']="Invalid Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/customer/trip/index.php?view=details&id=".$_POST['trip_id']);
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/customer/shipping_locations/index.php?view=edit&id=".$_POST['lid']);
				exit;
			}
			
	}
	
	if($_GET['action']=='updateTripStatus')
	{
		
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				
				$result=updateTripStatusForTripId($_POST['status_id'],$_POST['trip_id']);
				if($result)
				{	
				$_SESSION['ack']['msg']="Trip Status updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				header("Location: ".WEB_ROOT."admin/customer/trip/index.php?view=details&id=".$_POST['trip_id']);
				exit;
				}
				else{
					
				$_SESSION['ack']['msg']="Invalid Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/customer/trip/index.php?view=details&id=".$_POST['trip_id']);
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/customer/trip/index.php?view=details&id=".$_POST['trip_id']);
				exit;
			}
			
	}
	
	}
?>

<?php

$pathLinks=array("Home","Registration Form","Manage Locations");
$selectedLink="accounts";
if(isset($link))
$selectedLink=$link;
$jsArray=array("jquery.validate.js","jquery-ui/js/jquery-ui.min.js","addInsuranceProof.js","customerDatePicker.js","validators/addReceipt_Payment.js","cScript.ashx","transliteration.I.js","jquery.timepicker.js","dropDown.js");
$cssArray=array("jquery-ui.css","jquery.timepicker.css");

require_once "../../../inc/template.php";
 ?>
 <script>
 $('.timepicker').timepicker({
    timeFormat: 'HH:mm:ss',
    interval: 15,
    startTime: '10:00',
    dynamic: false,
    dropdown: true,
    scrollbar: true
});
 

 </script>