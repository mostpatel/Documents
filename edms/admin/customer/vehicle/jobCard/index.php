<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/common.php";
require_once "../../../../lib/customer-functions.php";
require_once "../../../../lib/job-card-functions.php";
require_once "../../../../lib/job-card-remarks-functions.php";
require_once "../../../../lib/addNew-job-card-functions.php";
require_once "../../../../lib/vehicle-functions.php";
require_once "../../../../lib/vehicle-model-functions.php";
require_once "../../../../lib/currencyToWords.php";
require_once "../../../../lib/account-ledger-functions.php";
require_once "../../../../lib/account-period-functions.php";
require_once "../../../../lib/account-sales-functions.php";
require_once "../../../../lib/inventory-item-functions.php";
require_once "../../../../lib/item-type-functions.php";
require_once "../../../../lib/service-type-functions.php";
require_once("../../../../lib/service-check-functions.php");
require_once("../../../../lib/service-check-value-functions.php");
require_once "../../../../lib/item-manufacturer-functions.php";
require_once "../../../../lib/tax-functions.php";
require_once "../../../../lib/technician-functions.php";
require_once "../../../../lib/inventory-sales-functions.php";
require_once "../../../../lib/our-company-function.php";
require_once "../../../../lib/adminuser-functions.php";
require_once "../../../../lib/invoice-counter-functions.php";

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
	else if($_GET['view']=='jcard')
	{
		
		$content="jcard.php";
		$showTitle=false; // to turn off the company title on the top of the page
		}	
	else if($_GET['view']=='jcard2')
	{
		
		$content="jcard2.php";
		$showTitle=false; // to turn off the company title on the top of the page
		}		
	else if($_GET['view']=='invoiceDetails')
	{
		
		$content="details.php";
		$showTitle=false; // to turn off the company title on the top of the page
		}	
	else if($_GET['view']=='finalize')
	{
		
		$content="finalize.php";
		$showTitle=false; // to turn off the company title on the top of the page
		}
	else if($_GET['view']=='editFinalize')
	{
		
		$content="editFinalize.php";
		$showTitle=false; // to turn off the company title on the top of the page
		}		
	else if($_GET['view']=='invoice')
	{
		
		$content="invoice_brahmani.php";
		$showTitle=false; // to turn off the company title on the top of the page
		}	
			
	else if($_GET['view']=='search')
	{
		$content="search.php";
		
		}	
	else if($_GET['view']=='edit')
	{
		$content="edit.php";
		
		 if(isset($_GET['id']) && is_numeric($_GET['id']))
{
$job_card_id = $_GET['id'];

$job_card = getJobCardById($job_card_id);

$job_card_detials = $job_card['job_card_details'];
$job_card_customer_complaints=$job_card['job_card_description'];
$job_card_work_done = $job_card['job_card_work_done'] ;
$job_card_remarks = $job_card['job_card_remarks'];
$regular_items=$job_card['job_card_regular_items'];
$warranty_items=$job_card['job_card_warranty_items'];
$regular_ns_items=$job_card['job_card_ns_items'];
$outside_job_items=$job_card['job_card_outside_job'];
$service_checks=$job_card['job_card_checks'];
$sale=$job_card['job_card_sales'];
$vehicle_id = $job_card_detials['vehicle_id'];
$vehicle = getVehicleById($vehicle_id);	
$customer_id = $vehicle['customer_id'];
$customer = getCustomerDetailsByCustomerId($customer_id);
$oc_id =$admin_id=$_SESSION['edmsAdminSession']['oc_id'];
$job_card_counter = getJobCounterForOCID($oc_id);
$tax_grps = listTaxGroups();
$godowns = listGodowns();
$pageTitle = " ( ".$vehicle['vehicle_reg_no']." - ".getModelNameById($vehicle['model_id'])." )".$customer['customer_name'];
}
else
exit;
		
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
				
					
						
						$result=addNewJobCard($_POST["job_card_no"],$_POST['jb_date_time'],$_POST['service_type_id'],$_POST['free_service_no'],$_POST['date_of_sale'],$_POST['kms_covered'],$_POST['estimated_cost'],$_POST['bay_in'],$_POST['delivery_promise'],$_POST['technician_id'],$_POST['vehicle_id'],$_POST["customer_id"],$_POST['custmer_complaints'],$_POST['work_done'],$_POST['remarks'],$_POST['service_check'],$_POST['item_id'],$_POST['rate'],$_POST['quantity'],$_POST['disc'],$_POST['jb_date_time'],$_POST['to_ledger_id'],$_POST['from_ledger_id'],$_POST['remarks_gen'],$_POST['godown_id'],$_POST['tax_group_id'],$_POST['ns_item_id'],$_POST['ns_rate'],$_POST['ns_disc'],$_POST['ns_tax_group_id'],$_POST['oj_item_id'],$_POST['oj_rate'],$_POST['oj_disc'],$_POST['oj_tax_group_id'],$_POST['oj_our_rate'],$_POST['oj_provider_id'],$_POST['war_item_id'],$_POST['war_rate'],$_POST['war_quantity'],$_POST['war_disc'],$_POST['war_tax_group_id'],$_POST['war_godown_id']); // $cheque_return is 0 when inserting a payment			
				
				if(checkForNumeric($result))
				{
					
					$_SESSION['ack']['msg']="Job Card successfully added!";
					$_SESSION['ack']['type']=1; // 1 for insert
					if(isset($_POST['submit']) && $_POST['submit']=="Save")
					header("Location: ".WEB_ROOT."admin/customer/vehicle/jobCard/index.php?view=edit&id=".$result);
					else
					header("Location: ".WEB_ROOT."admin/customer/vehicle/jobCard/index.php?view=details&id=".$result);
					exit;
				}
				else if($result=="date_error")
				{
				$_SESSION['ack']['msg']="Date Should be greater than Books starting date!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/accounts/transactions/sales_inventory/index.php");
				exit;
				}
				else
				{
				$_SESSION['ack']['msg']="Invalid Input!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/accounts/transactions/sales_inventory/index.php");
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/accounts/transactions/sales_inventory/index.php");
				exit;
			}
		}	
	if($_GET['action']=='finalize')
	{
		$invoice_no =getFinalizeDetailsForJobCard($_POST['job_card_id']);
		
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && ((in_array(2,$admin_rights) && !$invoice_no) || in_array(7,$admin_rights)))
			{
				
					
						
						$result=finalizeJobCard($_POST["job_card_id"],$_POST['invoice_no'],$_POST['bay_out'],$_POST['actual_delivery'],$_POST['next_service_date'],$_POST['send_sms'],$_POST['next_service_kms']); // $cheque_return is 0 when inserting a payment			
				
							
					
					
				
				if(checkForNumeric($result))
				{
					
					$_SESSION['ack']['msg']="Job Card Finalized successfully!";
					$_SESSION['ack']['type']=1; // 1 for insert
					
					header("Location: ".WEB_ROOT."admin/customer/vehicle/jobCard/index.php?view=invoiceDetails&id=".$result);
					exit;
				}
				else if($result=="date_error")
				{
				$_SESSION['ack']['msg']="Date Should be greater than Books starting date!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/accounts/transactions/sales_inventory/index.php");
				exit;
				}
				else
				{
				$_SESSION['ack']['msg']="Invalid Input!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/accounts/transactions/sales_inventory/index.php");
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/accounts/transactions/sales_inventory/index.php");
				exit;
			}
		}			
	if($_GET['action']=='finalize_sms')
	{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,$admin_rights)))
			{
				
					
						
						$result=sendJobCardFinalizeSms($_GET["id"]); // $cheque_return is 0 when inserting a payment			
				
							
					
					
				
				if($result)
				{
					
					$_SESSION['ack']['msg']="Job Card SMS Sent successfully!";
					$_SESSION['ack']['type']=1; // 1 for insert
					
					header("Location: ".WEB_ROOT."admin/customer/vehicle/jobCard/index.php?view=details&id=".$_GET['id']);
					exit;
				}
				else if($result=="date_error")
				{
				$_SESSION['ack']['msg']="Date Should be greater than Books starting date!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/accounts/transactions/sales_inventory/index.php");
				exit;
				}
				else
				{
				$_SESSION['ack']['msg']="Job Card SMS Not Sent successfully!";
				$_SESSION['ack']['type']=4; // 4 for error
			header("Location: ".WEB_ROOT."admin/customer/vehicle/jobCard/index.php?view=details&id=".$_GET['id']);
					exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/accounts/transactions/sales_inventory/index.php");
				exit;
			}
		}			
	if($_GET['action']=='delete')
	{
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{	
				$customer_id = getCustomerIdByJobCardId($_GET["id"]);
				
				$result=deleteJobCard($_GET["id"]);
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Sales deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$customer_id);
				exit;
				}
				else if($result=="error")
				{
				$_SESSION['ack']['msg']="Sales delete Failed! Delete Receipts For JobCard";
				$_SESSION['ack']['type']=4; // 3 for delete
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$customer_id);
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/accounts/transactions/sales_inventory/index.php");
				exit;
			}
		}
	if($_GET['action']=='edit')
	{
		$invoice_no =getFinalizeDetailsForJobCard($_POST['job_card_id']);
		if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights) || (in_array(9,$admin_rights) && !$invoice_no) ))
			{
				
				$result=updateWholeJobCard($_POST['job_card_id'],$_POST["job_card_no"],$_POST['jb_date_time'],$_POST['service_type_id'],$_POST['free_service_no'],$_POST['date_of_sale'],$_POST['kms_covered'],$_POST['estimated_cost'],$_POST['bay_in'],$_POST['delivery_promise'],$_POST['technician_id'],$_POST['custmer_complaints'],$_POST['work_done'],$_POST['remarks'],$_POST['service_check'],$_POST['item_id'],$_POST['rate'],$_POST['quantity'],$_POST['disc'],$_POST['jb_date_time'],$_POST['to_ledger_id'],$_POST['from_ledger_id'],$_POST['remarks_gen'],$_POST['godown_id'],$_POST['tax_group_id'],$_POST['ns_item_id'],$_POST['ns_rate'],$_POST['ns_disc'],$_POST['ns_tax_group_id'],$_POST['oj_item_id'],$_POST['oj_rate'],$_POST['oj_disc'],$_POST['oj_tax_group_id'],$_POST['oj_our_rate'],$_POST['oj_provider_id'],$_POST['war_item_id'],$_POST['war_rate'],$_POST['war_quantity'],$_POST['war_disc'],$_POST['war_tax_group_id'],$_POST['war_godown_id']);
				if($result=="success")
				{	
				$_SESSION['ack']['msg']="Job Card updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				if(isset($_POST['submit']) && $_POST['submit']=="Save")
				header("Location: ".WEB_ROOT."admin/customer/vehicle/jobCard/index.php?view=edit&id=".$_POST['job_card_id']);
				else
				header("Location: ".WEB_ROOT."admin/customer/vehicle/jobCard/index.php?view=details&id=".$_POST['job_card_id']);
				exit;
				}
				else{
					
				$_SESSION['ack']['msg']="Invalid Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/customer/vehicle/jobCard/index.php?view=details&id=".$_POST['job_card_id']);
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/customer/vehicle/jobCard/index.php?view=details&id=".$_POST['job_card_id']);
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
$jsArray=array("jquery.validate.js","jquery-ui/js/jquery-ui.min.js","addInsuranceProof.js","customerDatePicker.js","validators/addReceipt_Payment.js","generateProductPurchase.js","getRateQuantityAndTaxForSales.js",'datetimepicker.min.js');
$cssArray=array("jquery-ui.css","datetimepicker.min.css");

require_once "../../../../inc/template.php";
 ?>
 <script type="text/javascript">
  $(function() {
    $('#datetimepicker1').datetimepicker({
      language: 'pt-BR'
    });
  });
</script>
<script type="text/javascript">
  $(function() {
    $('#datetimepicker2').datetimepicker({
      language: 'pt-BR'
    });
  });
</script>
<script type="text/javascript">
  $(function() {
    $('#datetimepicker3').datetimepicker({
      language: 'pt-BR'
    });
  });
</script>
<script type="text/javascript">
  $(function() {
    $('#datetimepicker4').datetimepicker({
      language: 'pt-BR'
    });
  });
</script>
<script type="text/javascript">
  $(function() {
    $('#datetimepicker5').datetimepicker({
      language: 'pt-BR'
    });
  });
</script>
<script type="text/javascript">
  $(function() {
    $('#datetimepicker6').datetimepicker({
      language: 'pt-BR'
    });
  });
</script>