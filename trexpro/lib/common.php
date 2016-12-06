<?php
require_once('cg.php');
require_once('bd.php');


$thisFile = str_replace('\\', '/', __FILE__);
$docRoot = $_SERVER['DOCUMENT_ROOT'];
$accounts=0;
$webRoot  = str_replace(array($docRoot, 'lib/common.php'), '', $thisFile);
$srvRoot  = str_replace('lib/common.php', '', $thisFile);

define('WEB_ROOT',"/trexpro/");
define('SRV_ROOT', $srvRoot);
define('BP_ROOT',"D:\\gDrive");
define('ACCOUNT_STATUS',"1");
define('ACCOUNT_STARTING_DATE',"2015-04-01");
define('INCLUDE_PENALTY',"1");
define('INCLUDE_AC',"1");
define('MULTIPLE_DURATION',"1");
define('ITEM_CODE_PREFIX',"ITE");
define('DEF_INCOME_LED',42);
define('INVENTORY_CAL_METHOD',2); // 1 = avg , 2= fifo
define('NO_OF_PAID_LRS_IN_PAGE',30);
define('SELECT_TAX',0);
define('DEFAULT_OC_ID',1);
define('DEFAULT_ADMIN_ID',27);
define('SLAVE',0);
define('UPDATE_CONTRAINT',2);
define('BUILTY_CHARGE',5); // no of days after which updatenot allowed
define('ONLY_TO_BE_BILLED_CUSTOMERS',1);
define('CASH_MEMO_MODE',0);
define('SUMMARY_START_DATE','2015-04-01');
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
	
    $input = trim(htmlentities(strip_tags($input,",")));
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
WHERE (TABLE_SCHEMA = '$dbName') AND (TABLE_NAME = 'edms_ac_main_settings')";

$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
if(dbNumRows($result)>0)
{
	$sql="SELECT accounts_status FROM edms_ac_main_settings";
	
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
	$sql="SELECT ac_Settings_id, accounts_status, ac_starting_date, default_bank FROM edms_ac_settings WHERE agency_id=$id AND accounts_status=1";
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
	
	$sql="SELECT ac_Settings_id, accounts_status, ac_starting_date, default_bank, default_vehicle_purchase, default_vehicle_sales, default_spares_sales, default_spares_purchase  FROM edms_ac_settings WHERE our_company_id=$id AND accounts_status=1";
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

function getDefaultVehiclePurchaseLedgerForOC($id)
{
	
	if( checkForNumeric($id) && ACCOUNT_STATUS==1 && getAccountsStatus())
	{	
	
	$sql="SELECT  default_vehicle_purchase  FROM edms_ac_settings WHERE our_company_id=$id AND accounts_status=1";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		$account_settings=$resultArray[0][0];
		return $account_settings;
	}
	else return false;
	}
return false;	
}

function getDefaultVehicleSalesLedgerForOC($id)
{
	
	if( checkForNumeric($id) && ACCOUNT_STATUS==1 && getAccountsStatus())
	{	
	
	$sql="SELECT  default_vehicle_sales  FROM edms_ac_settings WHERE our_company_id=$id AND accounts_status=1";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		$account_settings=$resultArray[0][0];
		return $account_settings;
	}
	else return false;
	}
return false;	
}

function getDefaultSparesPurchaseLedgerForOC($id)
{
	
	if( checkForNumeric($id) && ACCOUNT_STATUS==1 && getAccountsStatus())
	{	
	
	$sql="SELECT  default_spares_purchase  FROM edms_ac_settings WHERE our_company_id=$id AND accounts_status=1";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		$account_settings=$resultArray[0][0];
		return $account_settings;
	}
	else return false;
	}
return false;	
}

function getDefaultSparesSalesLedgerForOC($id)
{
	
	if( checkForNumeric($id) && ACCOUNT_STATUS==1 && getAccountsStatus())
	{	
	
	$sql="SELECT  default_spares_sales  FROM edms_ac_settings WHERE our_company_id=$id AND accounts_status=1";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		$account_settings=$resultArray[0][0];
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

function getInsuranceExpiryDateFromIssueDate($date)
{
	if(isset($date) && validateForNull($date))
			{
		    $date = str_replace('/', '-', $date);
			$date=date('Y-m-d',strtotime($date));
			}
	return date('Y-m-d', strtotime($date. ' + 364 days'));	
}

function GetBaseAmountFromMRP($tax_percent,$mrp)
{
	if(checkForNumeric($tax_percent,$mrp))
	{
	    $divider = '1'.".".$tax_percent;
		if($divider>0)
		return round($mrp/$divider,2);	
		else
		return $mrp;
	}
}

function getLastDayOfMonth($month,$year)
{
	$last_date = $year."-".$month."-"."01";
	 $last_date = date('Y-m-t',strtotime($last_date));
	return $last_date;
}

function getFirstDayOfNextMonth($month,$year)
{
	
	$last_date = getLastDayOfMonth($month,$year);
	return getNextDate($last_date);
}

function getTaxOnFreightPercentage()
{
	$sql="SELECT tax_on_freight_percent FROM edms_ac_main_settings";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return $resultArray[0][0];
	
}

function getFreightAmmountForTax()
{
	$sql="SELECT tax_freight_amount FROM edms_ac_main_settings";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return $resultArray[0][0];
	
}

function getTodaysDateTimeAfterDays($minutes,$date=false)
{
	if(!$date)
	{
	$sql="SELECT NOW()";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	$date_time = $resultArray[0][0];
	}
	else
	{
	$date_time = $date;	
	}
	return date('Y-m-d H:i:s',strtotime($date_time.'+'.$minutes.'days'));
	
	}		

function getTodaysDateTimeBeforeDays($minutes,$date=false)
{
	if(!$date)
	{
	$sql="SELECT NOW()";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	$date_time = $resultArray[0][0];
	}
	else
	{
	$date_time = $date;	
	}
	return date('Y-m-d H:i:s',strtotime($date_time.'-'.$minutes.'days'));
	
	}	
	
function getServerBranchId()
{
	
	$sql="SELECT main_branch_id FROM edms_ac_main_settings";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return $resultArray[0][0];
	
}	


?>