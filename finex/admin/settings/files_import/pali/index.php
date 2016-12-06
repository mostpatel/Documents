<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/common.php";
require_once "../../../../lib/image-functions.php";
require_once("../../../../lib/our-company-function.php");
require_once("../../../../lib/insertFilesFromExcelPaliRanu.php");


if(isset($_SESSION['adminSession']['admin_rights']))
$admin_rights=$_SESSION['adminSession']['admin_rights'];

if(isset($_GET['view']))
{
	if($_GET['view']=='add')
	{
		$content="add_excel.php";
	}
	else if($_GET['view']=='upload')
	{
		$content="add_excel.php";
	}
	else if($_GET['view']=='downloadExcel')
	{
		$content="downloadExportFile.php";
	}
	else if($_GET['view']=='uploadSkus')
	{
		$content="updateSecondarySku.php";
	}
	else if($_GET['view']=='updateShelfAndQty')
	{
		$content="updateShelfAndQty.php";
	}
	else if($_GET['view']=='details')
	{
		$content="details.php";
		}
	else if($_GET['view']=='edit')
	{
		$content="edit.php";
		}
	else if($_GET['view']=='all')
	{
		$content="allParts.php";
		}		
	else
	{
		$content="add_excel.php";
	}	
}
else
{
		$content="add_excel.php";
}		
if(isset($_GET['action']))
{
	if($_GET['action']=='add')
	{
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,					$admin_rights)))
			{
			
				$result=insertInventoryItem($_POST["name"],$_POST["alias"],$_POST["item_code"],$_POST["item_unit_id"],$_POST["manufacturer_id"],$_POST["mfg_item_code"],$_POST["dealer_price"],$_POST["mrp"],$_POST["opening_quantity"],$_POST["opening_rate"],$_POST["remarks"],$_POST["item_type_id"],$_POST["min_quantity"],$_POST["godown_id"],$_POST["tax_group_id"],$_POST["use_barcode"],$_POST["barcode_prefix"],$_POST["barcode_counter"],$_POST['item_barcode'],$_POST['gen_barcode'],$_POST['supplier_id'],$_POST['expiration_date'],$_POST['sku']);
				
				if(is_numeric($result))
				{
				$_SESSION['ack']['msg']="Item successfully added!";
				$_SESSION['ack']['type']=1; // 1 for insert
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
		if($_GET['action']=='addExcel')
	{
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,					$admin_rights)))
			{
				$total = count($_FILES['excel_file']['name']);

// Loop through each file
for($i=0; $i<$total; $i++) {
  //Get the temp file path
			    $tmpFilePath = $_FILES['excel_file']['tmp_name'][$i];
				$file_type = $_FILES['excel_file']['type'][$i];
				$file=UploadExcel(array('tmp_name'=>$tmpFilePath,'type'=>$file_type),'lib/');
				$non_insert_item_rows=insertFileFromExcelPali('../../../../lib/'.$file,$_POST['agency_id']);
			
}

				if(empty($non_insert_item_rows))
				{
				$_SESSION['ack']['msg']="Files successfully added!";
				$_SESSION['ack']['type']=1; // 1 for insert
				}
				else{
				$non_insert_item_rows_string = implode(",",$non_insert_item_rows);	
				$_SESSION['ack']['msg']="Files successfully added! Error in $non_insert_item_rows_string Number Rows!";
				$_SESSION['ack']['type']=1; // 4 for error
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
		if($_GET['action']=='updateSkus')
	{
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,					$admin_rights)))
			{
			
				$file=UploadExcel($_FILES['excel_file'],'lib/');
				$non_insert_item_rows=updateSecondarySkusExcel('../../../../lib/'.$file);
				if(empty($non_insert_item_rows))
				{
				$_SESSION['ack']['msg']="Items successfully updated!";
				$_SESSION['ack']['type']=1; // 1 for insert
				}
				else{
				$non_insert_item_rows_string = implode(",",$non_insert_item_rows);	
				$_SESSION['ack']['msg']="Items successfully updated! Error in $non_insert_item_rows_string Number Rows!";
				$_SESSION['ack']['type']=1; // 4 for error
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
		if($_GET['action']=='updateShelfAndQty')
	{
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,					$admin_rights)))
			{
			
				$file=UploadExcel($_FILES['excel_file'],'lib/');
				$non_insert_item_rows=updateShelfAndQuantityExcel('../../../../lib/'.$file);
				if(empty($non_insert_item_rows))
				{
				$_SESSION['ack']['msg']="Items successfully updated!";
				$_SESSION['ack']['type']=1; // 1 for insert
				}
				else{
				$non_insert_item_rows_string = implode(",",$non_insert_item_rows);
				$_SESSION['update_shelf_non_upc'] = $non_insert_item_rows;	
				$_SESSION['ack']['msg']="Items successfully updated! Error in UPCs Listed Below!";
				$_SESSION['ack']['type']=4; // 4 for error
				}
				
				header("Location: ".WEB_ROOT."admin/settings/inventory_settings/item_settings/index.php?view=updateShelfAndQty");
				exit;
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/settings/inventory_settings/item_settings/index.php?view=updateShelfAndQty");
			exit;
			}
		}
	if($_GET['action']=='delete')
	{
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{	
				$result=deleteInventoryItem($_GET["lid"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Item deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				}
				else
				{
					$_SESSION['ack']['msg']="Cannot delete Item! Item already in use!";
				$_SESSION['ack']['type']=6; // 6 for inUse
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
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,$admin_rights)))
			{
				
				$result=updateInventoryItem($_POST["lid"],$_POST["name"],$_POST["alias"],$_POST["item_code"],$_POST["item_unit_id"],$_POST["manufacturer_id"],$_POST["mfg_item_code"],$_POST["dealer_price"],$_POST["mrp"],$_POST["opening_quantity"],$_POST["opening_rate"],$_POST["remarks"],$_POST["item_type_id"],$_POST["min_quantity"],$_POST["godown_id"],$_POST["tax_group_id"],$_POST["use_barcode"],$_POST["barcode_prefix"],$_POST["barcode_counter"],$_POST['item_barcode'],$_POST['supplier_id'],$_POST['expiration_date'],$_POST['sku']);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Item updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				header("Location: ".$_SERVER['PHP_SELF']);
				exit;
				}
				else
				{
					$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
					$_SESSION['ack']['type']=4; // 4 for error
					header("Location: ".$_SERVER['PHP_SELF']."?view=edit&lid=".$_POST["lid"]);
					exit;
					}
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
?>

<?php

$pathLinks=array("Home","Registration Form","Manage Locations");
$selectedLink="settings";
$jsArray=array("jquery.validate.js","validators/addInventoryItem.js","generateBarcodeItemPage.js","customerDatePicker.js","generateContactNoCustomer.js");
$cssArray=array("jquery-ui.css");
require_once "../../../../inc/template.php";
 ?>