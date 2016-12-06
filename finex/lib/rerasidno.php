<?php
require_once('cg.php');
require_once('bd.php');
require_once('common.php');
require_once('file-functions.php');
require_once('loan-functions.php');
require_once('broker-functions.php');
require_once('addNewCustomer-functions.php');
require_once('our-company-function.php');
require_once('agency-functions.php');
require_once('city-functions.php');
require_once('area-functions.php');
require_once('vehicle-functions.php');
require_once('vehicle-company-functions.php');
require_once('vehicle-model-functions.php');
require_once('vehicle-type-functions.php');
require_once('vehicle-dealer-functions.php');
require_once('customer-functions.php');
require_once('loan-functions.php');
reSortRasidNo(10012,NULL,'2016-04-01','2016-11-18',1);
function reSortRasidNo($our_company_id,$agency_id,$from_date,$to_date,$from_rasid_no)
{
$current_date = $from_date;	
if(checkForNumeric($our_company_id))
$prefix = getPrefixFromOCId($our_company_id);
if(checkForNumeric($agency_id))
$prefix = getAgencyPrefixFromAgencyId($agency_id);
while($current_date<=$to_date)
{	
$sql="SELECT emi_payment_id FROM fin_loan_emi_payment, fin_loan_emi, fin_loan, fin_file WHERE fin_loan_emi_payment.loan_emi_id = fin_loan_emi.loan_emi_id AND fin_loan_emi.loan_id = fin_loan.loan_id AND fin_loan.file_id = fin_file.file_id AND payment_date='$current_date' AND rasid_identifier=0 AND file_status!=3 ";
if(checkForNumeric($our_company_id))
$sql=$sql." AND fin_file.oc_id = $our_company_id";
if(checkForNumeric($agency_id))
$sql=$sql." AND fin_file.agency_id = $agency_id";
$sql=$sql." ORDER BY fin_loan_emi_payment.date_added";
$result = dbQuery($sql);
$resultArray = dbResultToArray($result);

foreach($resultArray as $re)
{
	$rasid_no = $prefix.$from_rasid_no;
	$emi_payment_id = $re['emi_payment_id'];
	$sql="UPDATE fin_loan_emi_payment SET rasid_no = '$rasid_no' WHERE emi_payment_id = $emi_payment_id OR rasid_identifier = $emi_payment_id";
	dbQuery($sql);
	$from_rasid_no++;;
}


$sql="SELECT file_closed_id FROM fin_file_closed, fin_file WHERE fin_file_closed.file_id = fin_file.file_id AND file_close_date='$current_date'  ";
if(checkForNumeric($our_company_id))
$sql=$sql." AND fin_file.oc_id = $our_company_id";
if(checkForNumeric($agency_id))
$sql=$sql." AND fin_file.agency_id = $agency_id";
$result = dbQuery($sql);
$resultArray = dbResultToArray($result);
foreach($resultArray as $re)
{
	$rasid_no = $prefix.$from_rasid_no;
	$emi_payment_id = $re['file_closed_id'];
	$sql="UPDATE fin_file_closed SET rasid_no = '$rasid_no' WHERE file_closed_id = $emi_payment_id ";
	dbQuery($sql);
	$from_rasid_no++;;
}

$current_date = getNextDate($current_date);

}
}
?>