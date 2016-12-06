<?php
require_once "../../lib/cg.php";
require_once "../../lib/bd.php";
require_once "../../lib/location-functions.php";
require_once "../../lib/package-functions.php";
require_once "../../lib/hotel-package-functions.php";
require_once "../../lib/package-category-functions.php";
require_once "../../lib/package-type-functions.php";
require_once "../../lib/vehicle-type-functions.php";
require_once "../../lib/package-itenary-functions.php";
require_once "../../lib/package-cost-functions.php";



if(isset($_SESSION['adminSession']['admin_rights']))

$admin_rights=$_SESSION['adminSession']['admin_rights'];



if(isset($_GET['view']))

{

	if($_GET['view']=='add')

	{

		$content="list_add.php";

	}
	else if($_GET['view']=='addIndCost')

	{

		$content="list_add_ind_cost.php";

	}
	else if($_GET['view']=='addVehicleCost')

	{

		$content="list_add_vehicle_cost.php";

	}
	else if($_GET['view']=='updateHotels')

	{

		$content="addHotels.php";

	}
	else if($_GET['view']=='details')

	{

		$content="details.php";

		$link="viewPackage";

		}	

	else if($_GET['view']=='list')

	{

		$content="list.php";

		$link="viewPackage";

		}	

	else if($_GET['view']=='edit')

	{

		$content="edit.php";

		$link="viewPackage";

		}
	else if($_GET['view']=='editIndCost')

	{

		$content="edit_ind_cost.php";

		$link="viewPackage";

		}	
	else if($_GET['view']=='editVehicleCost')
	{

		$content="edit_vehicle_cost.php";

		$link="viewPackage";

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

		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,$admin_rights)))

			{

				

				$result=insertPackage($_POST["name"],$_POST['location'],$_POST['places'],$_POST['days'],$_POST['nights'], $_POST['currency'], $_FILES['thumb_img'],$_POST['itenary_heading'],$_POST['itenary_description'],$_POST['tour_dates'],$_POST['inclusions'],$_POST['exclusions'],$_POST['package_category'],$_POST['terms'],$_POST['imp_note'],$_POST['itenary_sections'],$_POST['tour_code'],$_POST['ind_cost_heading'],$_POST['vehicle_cost_heading'],$_POST['from_loc'],$_POST['to_loc']);

				

				if(is_numeric($result))

				{

				$_SESSION['ack']['msg']="Package successfully added!";

				$_SESSION['ack']['type']=1; // 1 for insert

				header("Location: ".WEB_ROOT."admin/package/index.php?view=details&id=".$result);

				exit;

				}

				else{

					

				$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";

				$_SESSION['ack']['type']=4; // 4 for error

				}

				

				header("Location: ".$_SERVER['PHP_SELF']);

				exit;

			}

			else

			{	

					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";

					$_SESSION['ack']['type']=5; // 5 for access

					header("Location: ".$_SERVER['PHP_SELF']);

			exit;

			}

		}
		if($_GET['action']=='addHotels')

	{

		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,$admin_rights)))

			{

				
				deleteHotelsForPackage($_POST['package_id']);
				$result=insertHotelsToPackage($_POST["package_id"],$_POST['hotel_package_id']);

				if($result="success")

				{

				$_SESSION['ack']['msg']="Package successfully added!";

				$_SESSION['ack']['type']=1; // 1 for insert

				header("Location: ".WEB_ROOT."admin/package/index.php?view=details&id=".$_POST['package_id']);

				exit;

				}

				else{

					

				$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";

				$_SESSION['ack']['type']=4; // 4 for error

				}

				

				header("Location: ".$_SERVER['PHP_SELF']);

				exit;

			}

			else

			{	

					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";

					$_SESSION['ack']['type']=5; // 5 for access

					header("Location: ".$_SERVER['PHP_SELF']);

			exit;

			}

		}
if($_GET['action']=='addIndCost')

	{

		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,$admin_rights)))

			{

				
				
				$result=insertIndividualPackageCost($_POST["package_id"],$_POST['from'],$_POST['to'],$_POST['full_ticket'],$_POST['extra_person'], $_POST['half_ticket_w_seat'],$_POST['half_ticket_wo_seat'],$_POST['couple']);

				

				if(is_numeric($result))

				{

				$_SESSION['ack']['msg']="Individual Package Cost successfully added!";

				$_SESSION['ack']['type']=1; // 1 for insert

				header("Location: ".WEB_ROOT."admin/package/index.php?view=details&id=".$_POST['package_id']);

				exit;

				}

				else{

					

				$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";

				$_SESSION['ack']['type']=4; // 4 for error

				}

				

				header("Location: ".$_SERVER['PHP_SELF']);

				exit;

			}

			else

			{	

					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";

					$_SESSION['ack']['type']=5; // 5 for access

					header("Location: ".$_SERVER['PHP_SELF']);

			exit;

			}

		}
		
		if($_GET['action']=='addVehicleCost')

	{

		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,$admin_rights)))

			{
				
				$result=insertVehiclePackageCostArray($_POST["package_id"],$_POST['from'],$_POST['to'],$_POST['pax_2'],$_POST['pax_3'], $_POST['pax_4'],$_POST['pax_6'],$_POST['pax_9']);

			
				if(is_numeric($result))

				{

				$_SESSION['ack']['msg']="Vehicle Package Cost successfully added!";

				$_SESSION['ack']['type']=1; // 1 for insert

				header("Location: ".WEB_ROOT."admin/package/index.php?view=details&id=".$_POST['package_id']);

				exit;

				}

				else{

					

				$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";

				$_SESSION['ack']['type']=4; // 4 for error

				}

				

				header("Location: ".$_SERVER['PHP_SELF']);

				exit;

			}

			else

			{	

					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";

					$_SESSION['ack']['type']=5; // 5 for access

					header("Location: ".$_SERVER['PHP_SELF']);

			exit;

			}

		}
	if($_GET['action']=='addFeatured')

	{

		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,$admin_rights)))

			{

				

				$result=insertFeaturedPackage($_GET["id"]);

				

				if($result=="success")

				{

				$_SESSION['ack']['msg']="Featured Package successfully added!";

				$_SESSION['ack']['type']=1; // 1 for insert

				header("Location: ".WEB_ROOT."admin/package/index.php?view=list");

				exit;

				}

				else{

					

				$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";

				$_SESSION['ack']['type']=4; // 4 for error

				}

				

				header("Location: ".$_SERVER['PHP_SELF']);

				exit;

			}

			else

			{	

					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";

					$_SESSION['ack']['type']=5; // 5 for access

					header("Location: ".$_SERVER['PHP_SELF']);

			exit;

			}

	}		

	if($_GET['action']=='delFeatured')

	{

		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,$admin_rights)))

			{

				

				$result=deleteFeaturedPackage($_GET["id"]);

				

				if($result=="success")

				{

				$_SESSION['ack']['msg']="Featured Package successfully Removed!";

				$_SESSION['ack']['type']=1; // 1 for insert

				header("Location: ".WEB_ROOT."admin/package/index.php?view=list");

				exit;

				}

				else{

					

				$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";

				$_SESSION['ack']['type']=4; // 4 for error

				}

				

				header("Location: ".$_SERVER['PHP_SELF']);

				exit;

			}

			else

			{	

					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";

					$_SESSION['ack']['type']=5; // 5 for access

					header("Location: ".$_SERVER['PHP_SELF']);

			exit;

			}

	}		

	if($_GET['action']=='edit')

	{

		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))

			{

				$result=updatePackage($_POST['lid'],$_POST["name"],$_POST['location'],$_POST['places'],$_POST['days'],$_POST['nights'], $_POST['currency'], $_FILES['thumb_img'],$_POST['itenary_heading'],$_POST['itenary_description'],$_POST['tour_dates'],$_POST['inclusions'],$_POST['exclusions'],$_POST['package_category'],$_POST['terms'],$_POST['imp_note'],$_POST['itenary_sections'],$_POST['tour_code'],$_POST['ind_cost_heading'],$_POST['vehicle_cost_heading'],$_POST['from_loc'],$_POST['to_loc']);

				if($result=="success")

				{

				$_SESSION['ack']['msg']="Package updated Successfuly!";

				$_SESSION['ack']['type']=2; // 2 for update

				}

				else

				{

					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";

					$_SESSION['ack']['type']=4; // 4 for error

					

					}

				header("Location: ".$_SERVER['PHP_SELF']."?view=fileDetails&id=".$_POST["lid"]);

				exit;

			}

			else

			{	

					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";

					$_SESSION['ack']['type']=5; // 5 for access

					header("Location: ".$_SERVER['PHP_SELF']);

			exit;

			}

			

		}		
		if($_GET['action']=='editIndCost')

	{

		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))

			{
				
				$result=updateIndividualPackageCost($_POST['ind_cost_id'],$_POST['package_id'],$_POST['from'],$_POST['to'],$_POST['full_ticket'],$_POST['extra_person'], $_POST['half_ticket_w_seat'],$_POST['half_ticket_wo_seat'],$_POST['couple']);

				if($result=="success")

				{

				$_SESSION['ack']['msg']="Package Cost updated Successfuly!";

				$_SESSION['ack']['type']=2; // 2 for update

				}

				else

				{

					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";

					$_SESSION['ack']['type']=4; // 4 for error

					

					}

				header("Location: ".$_SERVER['PHP_SELF']."?view=details&id=".$_POST["package_id"]);

				exit;

			}

			else

			{	

					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";

					$_SESSION['ack']['type']=5; // 5 for access

					header("Location: ".$_SERVER['PHP_SELF']);

			exit;

			}

			

		}	
			if($_GET['action']=='editVehicleCost')

	{

		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))

			{
				
				$result=updateVehiclePackageCostArray($_POST['package_id'],$_POST['from'],$_POST['to'],$_POST['pax_2'],$_POST['pax_3'], $_POST['pax_4'],$_POST['pax_6'],$_POST['pax_9'],$_POST['parent_id']);

				if($result=="success")

				{

				$_SESSION['ack']['msg']="Package Cost updated Successfuly!";

				$_SESSION['ack']['type']=2; // 2 for update

				}

				else

				{

					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";

					$_SESSION['ack']['type']=4; // 4 for error

					

					}

				header("Location: ".$_SERVER['PHP_SELF']."?view=details&id=".$_POST["package_id"]);

				exit;

			}

			else

			{	

					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";

					$_SESSION['ack']['type']=5; // 5 for access

					header("Location: ".$_SERVER['PHP_SELF']);

			exit;

			}

			

		}	

	if($_GET['action']=='delete')

	{

		

		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))

			{

				

				$result=deletePackage($_GET["id"]);

				if($result=="success")

				{

				$_SESSION['ack']['msg']="Package Deleted Successfuly!";

				$_SESSION['ack']['type']=2; // 2 for update

				}

				else

				{

					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";

					$_SESSION['ack']['type']=4; // 4 for error

					

					}

				header("Location: ".$_SERVER['PHP_SELF']);

				exit;

			}

			else

			{	

					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";

					$_SESSION['ack']['type']=5; // 5 for access

					header("Location: ".$_SERVER['PHP_SELF']);

			exit;

			}

			

		}					

								

}



$pathLinks=array("Home","Registration Form","Manage Locations");

$selectedLink="addPackage";

if(isset($link))

$selectedLink=$link;

$jsArray=array("jquery.validate.js",'package.js','jquery-ui.multidatespicker.js','customerDatePicker.js');

$cssArray=array("jquery-ui.css");

require_once "../../inc/template.php";

 ?>