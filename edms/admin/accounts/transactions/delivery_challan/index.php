<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/common.php";
require_once "../../../../lib/customer-functions.php";
require_once "../../../../lib/customer-group-functions.php";
require_once "../../../../lib/vehicle-functions.php";
require_once "../../../../lib/currencyToWords.php";
require_once "../../../../lib/account-ledger-functions.php";
require_once "../../../../lib/account-functions.php";
require_once "../../../../lib/account-period-functions.php";
require_once "../../../../lib/account-sales-functions.php";
require_once "../../../../lib/account-delivery-challan-functions.php";
require_once "../../../../lib/account-receipt-functions.php";
require_once "../../../../lib/inventory-item-functions.php";
require_once "../../../../lib/item-type-functions.php";
require_once "../../../../lib/item-unit-functions.php";
require_once "../../../../lib/product-desc-functions.php";
require_once "../../../../lib/invoice-counter-functions.php";
require_once "../../../../lib/item-manufacturer-functions.php";
require_once "../../../../lib/tax-functions.php";
require_once "../../../../lib/report-functions.php";
require_once "../../../../lib/inventory-sales-functions.php";
require_once "../../../../lib/nonstock-sales-functions.php";
require_once "../../../../lib/our-company-function.php";
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
		$sales_id = $_GET['id'];	
		$sale=getACDeliveryChallanByACDeliveryChallanId($sales_id);
		if(!is_numeric($sale['delivery_challan_id']))
		{
		if(count($_SESSION['back_links'])>1)
		{
		array_pop($_SESSION['back_links']);	
		header("Location: ".array_pop($_SESSION['back_links']));
		}
		else
		header("Location: ".WEB_ROOT);
		exit;	
		}
		$content="details.php";
		$showTitle=false; // to turn off the company title on the top of the page
		}
	else if($_GET['view']=='search')
	{
		$content="search.php";
		
		}
	else if($_GET['view']=='delivery_challan')
	{
		$content="delivery_challan.php";
		$showTitle=false;
		}		
	else if($_GET['view']=='edit')
	{
		$sales_id = $_GET['id'];	
		$sale=getACDeliveryChallanByACDeliveryChallanId($sales_id);
		if(!is_numeric($sale['delivery_challan_id']))
		{
		if(count($_SESSION['back_links'])>1)
		{
		array_pop($_SESSION['back_links']);	
		header("Location: ".array_pop($_SESSION['back_links']));
		}
		else
		header("Location: ".WEB_ROOT);
		exit;	
		}
		$content="edit.php";
		}	
	else if($_GET['view']=='addMultiple')
	{
		$content="add_multiple.php";
		}
	else if($_GET['view']=="allReceipts")	
	{
		$content="AllReceiptDetails.php";
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
					if($_POST['bulk']==0)
					{
						$to_ledger_id=getCustomerLedgerIDFromLedgerNameLedgerId($_POST['to_ledger_id']);
					$result=insertACDeliveryChallan($_POST['payment_date'],$to_ledger_id,$_POST['remarks'],$_POST['challan_no'],$_POST["item_id"],$_POST['quantity'],$_POST['godown_id'],$_POST['item_desc'],$_POST["ns_item_id"],$_POST['ns_item_desc'],$_POST['oc_id'],$_POST['delivery_note'], $_POST['terms_of_payment'], $_POST['supplier_ref_no'], $_POST['other_reference'], $_POST['buyers_order_no'], $_POST['order_date'], $_POST['despatch_doc_no'], $_POST['despatch_dated'], $_POST['despatched_through'], $_POST['destination'], $_POST['terms_of_delivery'],$_POST['consignee_address'],$_POST['unit_id']); // $cheque_return is 0 when inserting a payment	
					}
					else if($_POST['bulk']==1)
					{
					$to_ledger_id=getCustomerGroupIdNameByName($_POST['customer_group_id']);	
					
					$result=insertACDeliveryChallanForCustomerGroup($_POST['payment_date'],$to_ledger_id,$_POST['remarks'],$_POST['challan_no'],$_POST["item_id"],$_POST['quantity'],$_POST['godown_id'],$_POST['item_desc'],$_POST["ns_item_id"],$_POST['ns_item_desc'],$_POST['oc_id']);
						
					}
					else if($_POST['bulk']==2)
					{
						
					
					$result=insertACDeliveryChallanForCustomerIdArray($_POST['payment_date'],$_POST['selectTR'],$_POST['remarks'],$_POST['challan_no'],$_POST["item_id"],$_POST['quantity'],$_POST['godown_id'],$_POST['item_desc'],$_POST["ns_item_id"],$_POST['ns_item_desc'],$_POST['oc_id']);
						
					}
				
							
				
				if(checkForNumeric($result) || $result=="success")
				{
					
					$_SESSION['ack']['msg']="Delivery Challan successfully added!";
					$_SESSION['ack']['type']=1; // 1 for insert
					
					if(isset($_POST['customer_redirect']) && $_POST['customer_redirect']>0)
					{
					
					header("Location: ".WEB_ROOT."admin/accounts/transactions/delivery_challan/index.php?cid=".$_POST['customer_redirect']);
					exit;
					}
					
					header("Location: ".WEB_ROOT."admin/accounts/transactions/delivery_challan/index.php");
					exit;
				}
				else if($result=="date_error")
				{
				$_SESSION['ack']['msg']="Date Should be greater than Books starting date!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/accounts/transactions/delivery_challan/index.php");
				exit;
				}
				else
				{
				$_SESSION['ack']['msg']="Invalid Input!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/accounts/transactions/delivery_challan/index.php");
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/accounts/transactions/delivery_challan/index.php");
				exit;
			}
		}	
	if($_GET['action']=='delete')
	{
		$sale = getACDeliveryChallanByACDeliveryChallanId($_GET['lid']);
			if(checkForNumeric($sale['to_customer_id']))
			{
			$customer_id = $sale['to_customer_id'];
			$params = "?cid=".$customer_id;
			}
			else
			$params="";
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{	
			
				$result=deleteACDeliveryChallan($_GET["lid"]);
				
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Delivery Challan deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				header("Location: ".WEB_ROOT."admin/accounts/transactions/delivery_challan/index.php?view=details&id=".$_GET['lid']);
				exit;
				}
				else 
				{
				$_SESSION['ack']['msg']="Delivery Challan Could Not deleted!";
				$_SESSION['ack']['type']=3; // 5 for delete
				header("Location: ".WEB_ROOT."admin/accounts/transactions/delivery_challan/index.php?view=details&id=".$_GET['lid']);
				exit;
				}
				
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					if(count($_SESSION['back_links'])>1)
					{
				    header("Location: ".array_pop($_SESSION['back_links']));
					}
					else
					header("Location: ".WEB_ROOT."admin/accounts/transactions/delivery_challan/index.php".$params);
				exit;
			}
		}
	if($_GET['action']=='edit')
	{
		
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				
					$to_ledger_id=getCustomerLedgerIDFromLedgerNameLedgerId($_POST['to_ledger_id']);
					
				$result=updateACDeliveryChallan($_POST["id"],$_POST['payment_date'],$to_ledger_id,$_POST['remarks'],$_POST["challan_no"],$_POST["item_id"],$_POST['quantity'],$_POST['godown_id'],$_POST['item_desc'],$_POST["ns_item_id"],$_POST['ns_item_desc'],$_POST['oc_id'],$_POST['delivery_note'], $_POST['terms_of_payment'], $_POST['supplier_ref_no'], $_POST['other_reference'], $_POST['buyers_order_no'], $_POST['order_date'], $_POST['despatch_doc_no'], $_POST['despatch_dated'], $_POST['despatched_through'], $_POST['destination'], $_POST['terms_of_delivery'],$_POST['consignee_address'],$_POST['unit_id']);
				if($result=="success")
				{	
				$_SESSION['ack']['msg']="Delivery Challan updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				header("Location: ".WEB_ROOT."admin/accounts/transactions/delivery_challan/index.php?view=details&id=".$_POST['id']);
				exit;
				}
				else{
					
				$_SESSION['ack']['msg']="Invalid Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/accounts/transactions/delivery_challan/index.php?view=details&id=".$_POST['id']);
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/accounts/transactions/delivery_challan/index.php?view=details&id=".$_POST['lid']);
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
$jsArray=array("jquery.validate.js","jquery-ui/js/jquery-ui.min.js","addInsuranceProof.js","customerDatePicker.js","validators/addDeliveryChallan.js","generateProductPurchase.js","getRateQuantityAndTaxForSales.js","cScript.ashx","transliteration.I.js","getUnitsForItem.js");
$cssArray=array("jquery-ui.css");

require_once "../../../../inc/template.php";
?>