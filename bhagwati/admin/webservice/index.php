<?php

include_once("config.inc.php");

include_once("PDOConnection.inc.php");

include_once("DBFunctions.inc.php");

include_once("JSONBox.inc.php");

require_once('admin/lib/package-functions.php');

require_once("admin/lib/package-type-functions.php");

$JsonBox=new JSONBox();

$PDOConnection=new PDOConnection(DB_HOST,DB_DATABASE,DB_USER,DB_PASSWORD);

$DBHandler=$PDOConnection->connect();

if($DBHandler==null)
{
	echo $JsonBox->makeJsonErrorObj($PDOConnection->getErrorMsg(),"cannot connect to server.");
	die();	
}

switch($_GET["method"])
{

	case "get_package_list":
								echo fireQuery($DBHandler,"SELECT package_id, package_name, days, nights, places, thumb_href, inclusions, exclusions FROM trl_package ORDER BY date_modified DESC",true,"package_list");
								break;
	
	case "get_single_package":
								if(isset($_GET["package_id"]))
								{
									echo fireQuery($DBHandler,"SELECT itenary_heading, itenary_description, package_id FROM trl_package_itenary WHERE package_id=".$_GET["package_id"]." ORDER BY itenary_id",true,"package");
									
									$package = getPackageByID($_GET["package_id"]);
									
									$package_type = getPackageTypeForPackage($_GET["package_id"]);
									
									if(is_array($package_type) && count($package_type)>0)
									{ 
										foreach($package_type as $pt)
										{
											echo $pt['package_type']." : ".$pt['price']." ".$package['currency']."<br>";
										}
									}
									else
									{
										echo "On Request";
									}
									
								}
	
								break;
	
	default:
			echo $JsonBox->makeJsonResponseObj("pelase provide method","method not given");
	
}



?>