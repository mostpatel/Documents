<?php
require_once "../../../lib/cg.php";
require_once "../../../lib/bd.php";
require_once "../../../lib/route-functions.php";
require_once "../../../lib/route-fare-functions.php";
require_once "../../../lib/shipping-location-functions.php";




if(is_numeric($_GET['from_id']) && is_numeric($_GET['to_id'])){
$from_shipping_id=$_GET['from_id'];
$to_shipping_id =$_GET['to_id'];

$vehicle_type_id = $_GET['vehicle_type_id'];
$result=array();
$from_shipping_location = getShippingLocationForshippingLocationId($from_shipping_id);
$to_shipping_location = getShippingLocationForshippingLocationId($to_shipping_id);

$route=getRouteByFromToAreaId($from_shipping_location['area_id'],$to_shipping_location['area_id']);

if(is_array($route) && is_numeric($route['route_id']))
{
$route_fare=getFareForRouteAndVehicleTypeID($route['route_id'],$vehicle_type_id);	
if($route_fare>0)
echo $route_fare;
else
echo 0;
}
else
echo 0;

}

?>