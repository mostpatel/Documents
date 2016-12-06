<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("agency-functions.php");
require_once("our-company-function.php");
require_once('EMI-functions.php');
require_once("common.php");
require_once("bd.php");
require_once("bank-functions.php");
require_once("payment-functions.php");
require_once("penalty-functions.php");
require_once("loan-functions.php");
$file_id=$_GET['file_id'];
displayAllRasidForFileID($file_id);
function displayAllRasidForFileID($file_id)
{
	$loan_id = getLoanIdFromFileId($file_id);
	$all_rasid=getAllRasidForLoanId($loan_id);
	$i=1;
	foreach($all_rasid as $r)
	echo $i++." ".$r['payment_amount']." ".$r['payment_date']." ".$r['rasid_no']." <br>";
}

?>