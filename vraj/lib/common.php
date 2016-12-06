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
	
	$sql="SELECT * FROM edms_ac_main_settings";
	$result = dbQuery($sql);
	$resultArray = dbResultToArray($result);
	return $resultArray[0];
}
$main_constants = getMainSettings();

define('WEB_ROOT',$main_constants['WEB_ROOT']);
define('SRV_ROOT', $srvRoot);
define('BP_ROOT',$main_constants['BP_ROOT']);
define('ACCOUNT_STATUS',$main_constants['accounts_status']);
define('ACCOUNT_STARTING_DATE',$main_constants['ACCOUNT_STARTING_DATE']);
define('ITEM_CODE_PREFIX',$main_constants['ITEM_CODE_PREFIX']);
define('SEND_SMS',$main_constants['SEND_SMS']);
define('NEXT_SERVICE_TIMING',$main_constants['NEXT_SERVICE_TIMING']);
define('HEADING_SUFFIX',$main_constants['HEADING_SUFFIX']);
define('VEHICLE_DEFAULT',$main_constants['VEHICLE_DEFAULT']);
define('INVENTORY_CAL_METHOD',$main_constants['INVENTORY_CAL_METHOD']); // 1 = avg ,$main_constants[''] 2= fifo
define('EDMS_MODE',$main_constants['EDMS_MODE']);
define('TAX_MODE',$main_constants['TAX_MODE']);
define('INVOICE_TYPE_SHOW_ALWAYS',$main_constants['INVOICE_TYPE_SHOW_ALWAYS']); // retail or tax
define('INVOICE_ITEM_DESC',$main_constants['INVOICE_ITEM_DESC']);
define('INVOICE_TAX_YEARS',$main_constants['INVOICE_TAX_YEARS']); // for diwakar bhai 
define('INVOICE_ADD_INFO',$main_constants['INVOICE_ADD_INFO']);
define('BIG_CUST_BAL',$main_constants['BIG_CUST_BAL']); // for devabhai
define('SALES_PURCHASE_INCLUDE',$main_constants['SALES_PURCHASE_INCLUDE']);
define('DELIVERY_CHALLAN_NAME',$main_constants['DELIVERY_CHALLAN_NAME']);
define('DELIVERY_NON_STOCK',$main_constants['DELIVERY_NON_STOCK']);
define('DELIVERY_STOCK',$main_constants['DELIVERY_STOCK']);
define('SALES_NON_STOCK',$main_constants['SALES_NON_STOCK']);
define('SALES_STOCK',$main_constants['SALES_STOCK']);
define('SALES_NAME',$main_constants['SALES_NAME']);
define('CUSTOMER_MULTI_COMPANY',$main_constants['CUSTOMER_MULTI_COMPANY']);
define('CASH_SALE',$main_constants['CASH_SALE']);
define('RECENT_SALES',$main_constants['RECENT_SALES']); // sales page
define('RECENT_SALES_CUSTOMER_PAGE',$main_constants['RECENT_SALES_CUSTOMER_PAGE']); // customer_page
define('BROKER_NAME',$main_constants['BROKER_NAME']);
define('INVOICE_AMOUNT_PAID_LEFT',$main_constants['INVOICE_AMOUNT_PAID_LEFT']);
define('INVOICE_ITEM_CODE',$main_constants['INVOICE_ITEM_CODE']);
define('INVOICE_TAX_COLUMN',$main_constants['INVOICE_TAX_COLUMN']);
define('DELIVERY_CHALLAN_BULK',$main_constants['DELIVERY_CHALLAN_BULK']);
define('ITEM_NAME_CORRECTION',$main_constants['ITEM_NAME_CORRECTION']);
define('PERIOD_RESTRICTION',$main_constants['PERIOD_RESTRICTION']);
define('BARCODE_COUNTER',$main_constants['BARCODE_COUNTER']);
define('DUPLICATE_ITEM_NAME',$main_constants['DUPLICATE_ITEM_NAME']);
define('DUPLICATE_CUSTOMER_NAME',$main_constants['DUPLICATE_CUSTOMER_NAME']);
define('USE_BARCODE',$main_constants['USE_BARCODE']);
define('VEHICLE_INVOICE_TYPE_ID',$main_constants['VEHICLE_INVOICE_TYPE_ID']);
define('TAX_CLASS',$main_constants['TAX_CLASS']);
define('CUSTOMER_NO',$main_constants['CUSTOMER_NO']);
define('INCOME_LEDGER',$main_constants['INCOME_LEDGER']);
define('NOTE_GUJARATI',$main_constants['NOTE_GUJARATI']);
define('TIN_FOR_TAX',$main_constants['TIN_FOR_TAX']);
define('ITEM_PROMPT',$main_constants['ITEM_PROMPT']);


if(!isset($_SESSION['back_links']) || !is_array($_SESSION['back_links']))
$_SESSION['back_links']=array();



if(isset($_GET) && !isset($_GET['action']) && ((isset($_GET['view']) && $_GET['view']!='edit') || !isset($_GET['view'])))
{
$back_links_array = $_SESSION['back_links'];
$request_uri = $_SERVER['REQUEST_URI'];
if(substr($request_uri,-1,1)=='/')
$request_uri=$request_uri."index.php";
if(((count($back_links_array)>0 && $back_links_array[count($back_links_array)-1]!=$request_uri) || count($back_links_array)==0 ) && strpos($request_uri,'admin')>0 && !strpos($request_uri,'getRateQuantityAndTaxForItem') &&  !strpos($request_uri,'getInvoiceNo.php'))	
{
if(!isset($omit_session_path))	
$_SESSION['back_links'][] = $request_uri;
}
}
else if(isset($_GET['view']) && $_GET['view']=='edit')
{
	if($_SESSION['back_links'][(count($_SESSION['back_links']) - 1 )] != $_SESSION['back_links'][(count($_SESSION['back_links']) - 2 )])
	$_SESSION['back_links'][] = $_SESSION['back_links'][(count($_SESSION['back_links']) - 1 )];
}

if(isset($_GET['action']) && $_GET['action']=='back')
{
if(count($_SESSION['back_links'])>0)	
array_pop($_SESSION['back_links']);



if(count($_SESSION['back_links'])>0)
{
$request_uri = $_SERVER['HTTP_REFERER'];	

if ($urlParts = parse_url($request_uri))
$baseUrl = $urlParts["scheme"] . "://" . $urlParts["host"];

$request_uri=str_replace($baseUrl,"",$request_uri);

if(substr($request_uri,-1,1)=='/')
$request_uri=$request_uri."index.php";



if($_SESSION['back_links'][(count($_SESSION['back_links']) - 1 )]==$request_uri)	
{
	array_pop($_SESSION['back_links']);
}
header("Location: ".array_pop($_SESSION['back_links']));
}
else
header("Location: ".WEB_ROOT);
exit;
}

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
	$input = strip_tags($input,",");
	$input = htmlentities($input);
    $input = trim($input);
    $input = mysql_real_escape_string($input);
	$input = str_ireplace('&amp;','&',$input);
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
	
	if(checkForNumeric($id) && ACCOUNT_STATUS==1 && getAccountsStatus())
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
	
function getTodaysDateTimeAfterMinute($minutes)
{
	$sql="SELECT NOW()";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return date('Y-m-d H:i:s',strtotime($resultArray[0][0].'+'.$minutes.'minutes'));
	
	}		

function getTodaysDateTimeBeforeMinute($minutes)
{
	$sql="SELECT NOW()";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return date('Y-m-d H:i:s',strtotime($resultArray[0][0].'-'.$minutes.'minutes'));
	
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
	    $divider = 1+($tax_percent/100);
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
function moneyFormatIndia($num){

$explrestunits = "" ;
$num=preg_replace('/,+/', '', $num);
$words = explode(".", $num);
$des="00";
if(count($words)<=2){
    $num=$words[0];
    if(count($words)>=2){$des=$words[1];}
    if(strlen($des)<2){$des="$des0";}else{$des=substr($des,0,2);}
}
if(strlen($num)>3){
    $lastthree = substr($num, strlen($num)-3, strlen($num));
    $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
    $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
    $expunit = str_split($restunits, 2);
    for($i=0; $i<sizeof($expunit); $i++){
        // creates each of the 2's group and adds a comma to the end
        if($i==0)
        {
            $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
        }else{
            $explrestunits .= $expunit[$i].",";
        }
    }
    $thecash = $explrestunits.$lastthree;
} else {
    $thecash = $num;
}
return "$thecash.$des"; // writes the final format where $currency is the currency symbol.

}

function checkIfDuplicateBarcodesInArray($item_id_array)
{
	
	if(is_array($item_id_array))
	{
		$unique_item_id_array=array();
		$item_id_array = array_filter($item_id_array);
		foreach($item_id_array as $item_id)
		{
			if(is_array($item_id))
			{
				if(!isset($unique_item_id_array[$item_id[1]]))
				$unique_item_id_array[$item_id[1]] = 1; 
				else
				{
				
				return true;
				}
			}
			
		}
		
	}
	return false;
}
?>