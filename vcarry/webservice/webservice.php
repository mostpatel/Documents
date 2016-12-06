<?php

//ALL INCLUDE FILES GOES HERE/////////////////////////

require_once("../lib/trip-functions.php");

//MAIN SWITCH CASE GOES HERE USE METHOD GET or POST (Currently using GET)

switch ($_GET["method"])
{

	case 'get_trips_by_driver_email':

		//If following method is taking any arguments as parameters please mention it
		//you can fetch arguments using $_GET['whatever_parameter_name']
		echo json_encode(getAllTripsForDriverByEmail($_GET["driver_email"]));

		break;

	
	case 'get_trip_details_by_trip_id':

		//If following method is taking any arguments as parameters please mention it
		//you can fetch arguments using $_GET['whatever_parameter_name']
		echo json_encode(getTripById($_GET["trip_id"]));

		break;


	//for changing trip status from START/STOP
	case 'edit_trip_status':

		//If following method is taking any arguments as parameters please mention it
		//you can fetch arguments using $_GET['whatever_parameter_name']
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
		echo json_encode(updateTripStatusForTripId($_GET["status_id"],$_GET['trip_id']));

		break;

	default:

		echo("Welcome to V-Carry webservice");

}