<?php
require_once "../../lib/cg.php";
require_once "../../lib/bd.php";
require_once "../../lib/common.php";
require_once "../../lib/account-functions.php";
require_once "../../lib/account-ledger-functions.php";
require_once "../../lib/agency-functions.php";
require_once "../../lib/our-company-function.php";
require_once "../../lib/account-combined-agency-functions.php";



$id=$_GET['p'];
$type=substr($id,0,2);
$id=substr($id,2);
if($type=="ag")
{
$account_settings=getAccountSettingsForAgency($id);	
$ca_id=getCombinedAgencyIdForAgencyId($id);
if($ca_id==false)
$banks=listAccountingLedgersForAgency($id);	
else if($ca_id && checkForNumeric($ca_id))
$banks=listAccountingLedgersForCombinedAgency($ca_id);

}
else if($type=="oc")
{
$account_settings=getAccountSettingsForOC($id);	
$ca_id=getCombinedAgencyIdForOCId($id);	

if($ca_id==false)
$banks=listAccountingLedgersForOC($id);	
else if($ca_id && checkForNumeric($ca_id))
$banks=listAccountingLedgersForCombinedAgency($ca_id);

}
$str="";
$default_bank_id=$account_settings['default_bank'];
if(isset($default_bank_id) && validateForNull($default_bank_id))
{
	$ledger=getLedgerById($default_bank_id);
	$str=$str . "\"$ledger[ledger_id]\"".",". "\"$ledger[ledger_name]\"".",";
	}
if($type=="ag")
$company_type=2;
else
$company_type=1;

if(isset($banks) && is_array($banks))
{
foreach($banks as $bank){
	if(isset($default_bank_id) && validateForNull($default_bank_id) && $bank['ledger_id']==$default_bank_id)
{
}
else
$str=$str . "\"$bank[ledger_id]\"".",". "\"$bank[ledger_name]\"".",";
}
}
if(strlen($str)>3)
$str=substr($str,0,(strLen($str)-1)); // Removing the last char , from the string
echo "new Array($str)";
?>