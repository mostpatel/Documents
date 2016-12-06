<?php
require_once "../../../lib/cg.php";
require_once "../../../lib/bd.php";
require_once "../../../lib/loan-functions.php";
require_once "../../../lib/file-functions.php";
require_once "../../../lib/customer-functions.php";
require_once "../../../lib/vehicle-functions.php";
require_once "../../../lib/bank-functions.php";
require_once "../../../lib/currencyToWords.php";
require_once "../../../lib/agency-functions.php";
require_once "../../../lib/our-company-function.php";
require_once "../../../lib/account-ledger-functions.php";
require_once "../../../lib/sms-functions.php";
require_once "../../../lib/sms-record-functions.php";
require_once "../../../lib/welcome-functions.php";
require_once "../../../lib/cheque-functions.php";
unset($_SESSION['ack']);


if(isset($_SESSION['adminSession']['admin_rights']))
$admin_rights=$_SESSION['adminSession']['admin_rights'];

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
	else if($_GET['view']=='edit')
	{
		$content="edit.php";
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
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,$admin_rights)))
			{
						$result=insertLoanCheques($_POST['file_id'],$_POST['bank_name'],$_POST['branch_name'],$_POST['required_cheques'],$_POST['cheques_received'],$_POST['used_cheques'],$_POST['unused_cheques'],$_POST['customer_id'],$_POST['remarks'],$_POST['cheque_no'],$_POST['ac_no']); // $cheque_return is 0 when inserting a payment			
						
				if(is_numeric($result))
				{
				$_SESSION['ack']['msg']="Cheques Successfully Added";
				$_SESSION['ack']['type']=1; // 4 for error
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_POST['file_id']);
				exit;	
				}
				else
				{
				$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_POST['file_id']);
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_POST['file_id']);
				exit;
			}
		}
	if($_GET['action']=='delete')
	{
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(4,$admin_rights) || in_array(7,					$admin_rights)))
			{	
			
			    $result = deleteLoanCheques($_GET['id']);
				
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Loan Cheques deleted Successfuly!";
				$_SESSION['ack']['type']=3; // 3 for delete
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_GET['id']);
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_GET['id']);
				exit;
			}
		}
	if($_GET['action']=='edit')
	{
		
		if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(3,$admin_rights) || in_array(7,					$admin_rights)))
			{
				$result=updateLoanCheques($_POST['file_id'],$_POST['bank_name'],$_POST['branch_name'],$_POST['required_cheques'],$_POST['cheques_received'],$_POST['used_cheques'],$_POST['unused_cheques'],$_POST['customer_id'],$_POST['remarks'],$_POST['cheque_no'],$_POST['ac_no']); 
				if($result=="success")
				{
				$_SESSION['ack']['msg']="Loan Cheques updated Successfuly!";
				$_SESSION['ack']['type']=2; // 2 for update
				header("Location: ".WEB_ROOT."admin/customer/loan_cheques/index.php?view=details&id=".$_POST['file_id']);
				exit;
				}
				else{
					
				$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_POST['file_id']);
				exit;
				}
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".WEB_ROOT."admin/customer/index.php?view=EMIdetails&id=".$_POST['file_id']."&state=".$_POST['emi_id']);
				exit;
			}
			
	}
}

$pathLinks=array("Home","Registration Form","Manage Locations");
$selectedLink="searchCustomer";
if(isset($link))
$selectedLink=$link;
$jsArray=array("jquery.validate.js","jquery-ui/js/jquery-ui.min.js","addInsuranceProof.js","customerDatePicker.js","validators/addPayment.js");
$cssArray=array("jquery-ui.css");
require_once "../../../inc/template.php";
if(isset($showModal) && $showModal==1)
{
?>
<script type="text/javascript">
$('#unReceivedWelcomeLetterModal').modal('show');
</script>
<?php	
}
?>