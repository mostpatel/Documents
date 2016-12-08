<?php

//ALL INCLUDE FILES GOES HERE/////////////////////////

require_once("../lib/trip-functions.php");
require_once("../lib/driver-functions.php");

//MAIN SWITCH CASE GOES HERE USE METHOD GET or POST (Currently using GET)

switch ($_POST["method"])
{

	case 'get_trips_by_driver_email':

		//If following method is taking any arguments as parameters please mention it
		//you can fetch arguments using $_POST['whatever_parameter_name']
		echo json_encode(getAllTripsForDriverByEmail($_POST["driver_email"]));

		break;

	
	case 'get_trip_details_by_trip_id':

		//If following method is taking any arguments as parameters please mention it
		//you can fetch arguments using $_POST['whatever_parameter_name']
		echo json_encode(getTripById($_POST["trip_id"]));

		break;


	//for changing trip status from START/STOP
	case 'edit_trip_status':

		//If following method is taking any arguments as parameters please mention it
		//you can fetch arguments using $_POST['whatever_parameter_name']
		//must send response for status to confirm is trip has started or not
		/* 
status_id	status
1			New
2			Driver Allocated
3			Loading
4			Trip started
5			Unloading
6			Finished
7			Cancelled*/
		echo json_encode(updateTripStatusForTripId($_POST["status_id"],$_POST['trip_id']));

		break;
		
	 case 'insert_trip_stop_data':
	 		return	insertTripStoppedDataFromApp($_POST['trip_id'],$_POST['start_time'],$_POST['stop_time'],$_POST['start_loc'],$_POST['stop_loc'],$_POST['distance'],$_POST['memo_amt'],$_POST['labour_amt'],$_POST['cash_received']);
		break;
	 case 'insert_trip_accpeted_data':
	       $driver_email= $_POST['driver_email'];
		 	$driver_id =  getDriverIdFromDriverEmail($driver_email);
	 		return	insertAcceptedDriverForTrip($driver_id,$_POST['trip_id'],$_POST['location'],$_POST['accpeted_time']);
		break;	
	default:

		echo("Welcome to V-Carry webservice");

}