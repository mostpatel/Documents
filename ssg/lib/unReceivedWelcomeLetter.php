<?php require_once('cg.php');
require_once('bd.php');
require_once('adminuser-functions.php');
require_once('customer-functions.php');
require_once('guarantor-functions.php');
require_once('file-functions.php');

$admin_id=$_SESSION['adminSession']['admin_id'];
$welcome_letter_type=$_POST['unReceivedLetterType'];
$file_id=$_POST['welcome_letter_file_id'];
if($welcome_letter_type==0)
{
	$customer_id=getCustomerIdByFileId($file_id);
	header("Location: ".WEB_ROOT."admin/customer/index.php?view=editCustomer&access=approved&id=".$file_id);
	exit;
}
else
{
    $guarnator=getGuarantorDetailsByCustomerId(getCustomerIdByFileId($file_id));
	header("Location: ".WEB_ROOT."admin/customer/index.php?view=editCustomer&access=approved&id=".$file_id);
	exit;	
	
}
?>