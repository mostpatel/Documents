<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
require_once("customer-functions.php");
require_once("loan-functions.php");
require_once("sms-record-functions.php");
require_once("sms-functions.php");

if(SEND_EMI_DUE_SMS==1)
generateListOfLoanEmiIdsForSMS(SEND_EMI_DUE_SMS_BEFORE,SEND_EMI_DUE_SMS_COMPANY_ID);
?>