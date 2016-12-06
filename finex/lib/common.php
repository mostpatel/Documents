<?php
require_once('cg.php');
require_once('bd.php');

$thisFile = str_replace('\\', '/', __FILE__);
$docRoot = $_SERVER['DOCUMENT_ROOT'];
$accounts=0;
$webRoot  = str_replace(array($docRoot, 'lib/common.php'), '', $thisFile);
$srvRoot  = str_replace('lib/common.php', '', $thisFile);

function getMainSettings()
{
	
	$sql="SELECT * FROM fin_ac_main_settings";
	$result = dbQuery($sql);
	$resultArray = dbResultToArray($result);
	return $resultArray[0];
}
$main_constants = getMainSettings();

define('WEB_ROOT',$main_constants['web_root']);
define('SRV_ROOT', $srvRoot);
define('BP_ROOT',$main_constants['bp_root']);
define('ACCOUNT_STATUS',$main_constants['accounts_status']);
define('ACCOUNT_STARTING_DATE',$main_constants['account_starting_date']);
define('INCLUDE_PENALTY',$main_constants['include_penalty']);
define('INCLUDE_AC',$main_constants['include_ac']);
define('SHOW_PENALTY',$main_constants['show_penalty']);
define('PENALTY_CALC_PERCENT',$main_constants['penalty_calc_percent']);
define('ALPHANUM_FILENO',$main_constants['alphanum_fileno']);
define('MULTIPLE_DURATION',$main_constants['multiple_duration']);
define('AUTO_INTEREST_TYPE',$main_constants['auto_interest_type']); // 0 = use default, if 1 use auto_interest_head as head for anamat finance commision
define('DUP_VEH_CHECK',$main_constants['dup_veh_check']);
define('SEND_SMS',$main_constants['send_sms']);
define('SEND_EMI_DUE_SMS',$main_constants['send_emi_due_sms']);
define('SEND_EMI_DUE_SMS_BEFORE',$main_constants['send_emi_due_sms_before']);
define('SEND_EMI_DUE_SMS_COMPANY_ID',$main_constants['send_emi_due_sms_company_id']);
define('SEND_EMI_DUE_SMS_AGENCY_ID',$main_constants['send_emi_due_sms_agency_id']);
define('PENALTY_WITH_PAYMENT',$main_constants['penalty_with_payment']);
define('AUTO_INTEREST_HEAD',$main_constants['auto_interest_head']); // 0 = unsecured loans
define('AUTO_INTEREST_NAME',$main_constants['auto_interest_name']); 
define('COMPANY_RESTRICTION',$main_constants['company_restriction']);
define('FILE_NO_REUSE',$main_constants['file_no_reuse']);
define('DEF_CHQ_VALUES',$main_constants['def_chq_values']);
define('ADD_NEW_CUSTOMER_REDIRECT',$main_constants['add_new_customer_redirect']);
define('SECONDARY_NAME',$main_constants['secondary_name']);
define('SECONDARY_ADDRESS',$main_constants['secondary_address']);
define('PERIOD_RESTRICTION',$main_constants['period_restriction']);
define('FILE_NO_GENERATE',$main_constants['file_no_generate']);
define('KANKRIYA_REPORTS',$main_constants['kankriya_reports']);
define('PENALTY_IN_DAYS',$main_constants['penalty_in_days']);
define('NOTICE_STAGE',$main_constants['notice_stage']);
define('LEGAL_PAYMENT',$main_constants['legal_payment']);
define('SEIZE_PAYMENT',$main_constants['seize_payment']);
define('PENALTY_IN_MONTHS_LOAN_PERCENT',$main_constants['penalty_in_months_loan_percent']);
define('DAYS_OF_PENALTY_EQUAL_TO_MONTH',$main_constants['days_of_penalty_equal_to_month']);
define('MEM_NO',$main_constants['mem_no']);
define('HO_OPENING_DATE',$main_constants['ho_opening_date']);
define('SECONDARY_LANG_TRANSLATION',$main_constants['secondary_lang_translation']);
define('SECONDARY_NAME_TITLE',$main_constants['secondary_name_title']);
define('PENALTY_HEAD_ID',$main_constants['penalty_head_id']);
define('PENALTY_LEDGER',$main_constants['penalty_ledger']);
define('PENALTY_INCOME_JV',$main_constants['penalty_income_jv']);
define('SHOW_CUSTOMER_IMAGE',$main_constants['show_customer_image']);
define('ADD_NEW_VEHICLE_REDIRECT',$main_constants['add_new_vehicle_redirect']);
define('FINANCE_INCOME_HEAD',$main_constants['finance_income_head']);
define('FINANCE_INCOME_NAME',$main_constants['finance_income_name']);
define('NOC_NON_ISSUE_CUSTOMER_BALANCE',$main_constants['noc_non_issue_customer_balance']);
define('RTO_WORK',$main_constants['rto_work']);
define('NOC_FOR_DUPLICATE_VEHICLE',$main_constants['noc_for_duplicate_vehicle']);
define('FILE_CHARGES',$main_constants['file_charges']);
define('QTY_IN_JV',$main_constants['qty_in_jv']);
define('NO_OF_GUARANTOR',$main_constants['no_of_guarantor']);
define('AUTO_CHQ_NO_GEN',$main_constants['auto_chq_no_gen']);
define('SHOW_ENQUIRY_MODULE',$main_constants['show_enquiry_module']);
define('BROKER_LEDGER_PALI',$main_constants['broker_ledger_pali']);

if(ACCOUNT_STATUS==1)
{
require_once('account-ledger-functions.php');
require_once('account-combined-agency-functions.php');
}
function dump_error_to_file($errno, $errstr) {
	$errorDetails=debug();
    file_put_contents(SRV_ROOT.'/tmp/errors.txt', date('Y-m-d H:i:s')." - ".$errstr.$errorDetails, FILE_APPEND);
}
set_error_handler('dump_error_to_file');


function clean_data($input) {
	
    $input = trim(htmlentities(strip_tags($input,","),ENT_COMPAT,'UTF-8'));
	
    $input = mysql_real_escape_string($input);
    return $input;
}
function checkForNumeric() // taken n number of inputs 
{
	$arg_list = func_get_args();
	$result=true;
	foreach($arg_list as $arg)
	{
		$innerResult=is_numeric($arg);
		$result=$result && $innerResult;
		
	}
	return $result;
	
	}
function checkForImagesInArray($imgArray)
{
	if(is_array($imgArray))
	{
		if(count($imgArray)>0)
		{
			 if($imgArray[0]!="" &&  $imgArray[0]!=null)
			 return true;
			 else 
			 return false;
			}
		}
	else
	{
		 if($imgArray!="" &&  $imgArray!=null)
			 return true;
			 else 
			 return false;
	}
}	

function validateForNull()
{
	$arg_list = func_get_args();
	$result=true;
	foreach($arg_list as $arg)
	{
		if($arg==null || $arg=="")
		$result=false;
		
	}
	return $result;
}

function checkForAlphaNumeric()
{
	$arg_list = func_get_args();
	$result=true;
	foreach($arg_list as $arg)
	{
		if($arg!=null && $arg!="")
		$result=preg_match('/^[a-z0-9]+$/i', $arg);
		if($result==false)
		return false;
	}
	return $result;
	
	
	}
	
function validateForDate($input)
{
	
   if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $input))
   {
      return true; // it matched, return true
   }
   else
   {
      return false;
   }
}
function getAccountsStatus()
{
	if(ACCOUNT_STATUS==1)
	{
	global $dbName;
	
	$sql="SELECT *
FROM information_schema.TABLES
WHERE (TABLE_SCHEMA = '$dbName') AND (TABLE_NAME = 'fin_ac_main_settings')";

$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
if(dbNumRows($result)>0)
{
	$sql="SELECT accounts_status FROM fin_ac_main_settings";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		$account_status=$resultArray[0][0];
		if($account_status==1)
		return true;
		else
		return false;
	}
	else return false;
}
return false;	
	}
return false;	
}	

function getAccountsSettingsForAgency($id)
{
	if( checkForNumeric($id) && ACCOUNT_STATUS==1 && getAccountsStatus())
	{
	$sql="SELECT ac_Settings_id, accounts_status, ac_starting_date, include_loan, include_penalty, include_ac, include_fc, mercantile, default_bank,include_fc_interest FROM fin_ac_settings WHERE agency_id=$id AND accounts_status=1";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		$account_settings=$resultArray[0];
		return $account_settings;
	}
	else return false;
	}
return false;	
}	
function getAccountsSettingsForOC($id)
{
	
	if( checkForNumeric($id) && ACCOUNT_STATUS==1 && getAccountsStatus())
	{	
	$sql="SELECT ac_Settings_id, accounts_status, ac_starting_date,include_loan, include_penalty, include_ac, include_fc, mercantile, default_bank,include_fc_interest FROM fin_ac_settings WHERE our_company_id=$id AND accounts_status=1";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		$account_settings=$resultArray[0];
		return $account_settings;
	}
	else return false;
	}
return false;	
}
function getTodaysDate()
{
	$sql="SELECT NOW()";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return date('Y-m-d',strtotime($resultArray[0][0]));
	
	}
	
function getCurrentMonth()
{
	$sql="SELECT NOW()";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return date('m',strtotime($resultArray[0][0]));
	
	}	

function getTodaysDateTime()
{
	$sql="SELECT NOW()";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return date('Y-m-d H:i:s',strtotime($resultArray[0][0]));
	
	}	

function getPreviousDate($date)
{
	
	if(isset($date) && validateForNull($date))
			{
		    $date = str_replace('/', '-', $date);
			$date=date('Y-m-d',strtotime($date));
			}	
	return date('Y-m-d', strtotime('-1 day', strtotime($date)));		
	}	

function getNextDate($date)
{
	
	if(isset($date) && validateForNull($date))
			{
		    $date = str_replace('/', '-', $date);
			$date=date('Y-m-d',strtotime($date));
			}	
	return date('Y-m-d', strtotime('+1 day', strtotime($date)));		
	}		

function getMonthYearArrayFromDates($start,$end)
{
	if(validateForDate($start) && validateForDate($end))
	{
			
	 $start = str_replace('/', '-', $start);
	 $start=date('Y-m-d',strtotime($start));
	 $end = str_replace('/', '-', $end);
	 $end=date('Y-m-d',strtotime($end));

$start = date('Y-m-01',strtotime($start));
$end = date('Y-m-01',strtotime('+1 month',strtotime($end)));
$mont_year_array=array();
$i=0;
do
{

$month=date('m',strtotime($start));	
$year=date('Y',strtotime($start));
$month_year=date('M Y',strtotime($start));

	$mont_year_array[$i]['month']=$month;
	$mont_year_array[$i]['year']=$year;
	$mont_year_array[$i]['month_year']=$month_year;
    
	$start=date('Y-m-d',strtotime('+1 month',strtotime($start)));	
$i++;
	
}while(strtotime($start)!=strtotime($end));


	return $mont_year_array;
	}
	}	

function getTodaysDateTimeAfterDays($minutes)
{
	
	$sql="SELECT NOW()";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return date('Y-m-d H:i:s',strtotime($resultArray[0][0].'+'.$minutes.'days'));
	
	}		

function getTodaysDateTimeBeforeDays($minutes)
{
	$sql="SELECT NOW()";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return date('Y-m-d H:i:s',strtotime($resultArray[0][0].'-'.$minutes.'days'));
	
	}	

function getTodaysDateTimeAfterMonthsAndDays($months,$days)
{
	
	$sql="SELECT NOW()";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return date('Y-m-d H:i:s',strtotime($resultArray[0][0].'+'.$months.'month +'.$days.'days'));
	
	}		

function getTodaysDateTimeBeforeMonthsAndDays($months,$days)
{
	$sql="SELECT NOW()";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return date('Y-m-d H:i:s',strtotime($resultArray[0][0].'-'.$months.'month +'.'-'.$days.'days'));
	
	}	
	
function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}	

		
?>