<?php
ini_set('display_errors', 'ON');
//ob_start("ob_gzhandler");
//error_reporting(E_ALL);
//ini_set('display_errors', True);


if(isset($_POST['session_id']))
{
   session_id($_POST['session_id']); //starts session with given session id
   session_start();
}
else {
   session_start(); //starts a new session
}
// start the session
//session_save_path('/home/content/06/11039806/html/tapandtype/ems/sessions');
session_save_path('/var/www/html/ems/sessions');
ini_set('session.gc_probability', 0);
ini_set('session.gc_maxlifetime', 604800);


// database connection config
//$dbHost = '104.238.118.24';
//$dbUser = 'root';
//$dbPass = 'Iamtnt12@gmail';
//$dbName = 'ems';

// database connection config
$dbHost = '127.0.0.1:3306';
$dbUser = 'root';
$dbPass = 'Iamtnt12@gmail';
$dbName = 'ABTTPL';

// setting up the web root and server root for
// this shopping cart application
$thisFile = str_replace('\\', '/', __FILE__);
$docRoot = $_SERVER['DOCUMENT_ROOT'];

$webRoot  = str_replace(array($docRoot, 'lib/cg.php'), '', $thisFile);
$srvRoot  = str_replace('lib/cg.php', '', $thisFile);
require_once 'bd.php';
$sql="SELECT * FROM ems_ac_main_settings";
$result=dbQuery($sql);	 
$resultArray=dbResultToArray($result);
$resultArray = $resultArray[0];
define('WEB_ROOT',$resultArray['WEB_ROOT']);
//define('WEB_ROOT',"/");
define('SRV_ROOT', $srvRoot);
define('DEF_CITY_ID', $resultArray['DEF_CITY_ID']);
define('SHOW_QUANTITY', $resultArray['SHOW_QUANTITY']);
define('SHOW_AREA', $resultArray['SHOW_AREA']);
define('SHOW_KM', $resultArray['SHOW_KM']);
define('SHOW_TOUR_REPORTS', $resultArray['SHOW_TOUR_REPORTS']);
define('ASSIGN_TO', $resultArray['ASSIGN_TO']);
define('PRODUCT_GLOBAL_VAR', $resultArray['PRODUCT_GLOBAL_VAR']);
define('QUANTITY_GLOBAL_VAR', $resultArray['QUANTITY_GLOBAL_VAR']);
define('MEETING_GLOBAL_VAR', $resultArray['MEETING_GLOBAL_VAR']);

define('SMS_URL', $resultArray['SMS_URL']);
define('SENDERID', $resultArray['SENDERID']);
define('APIKEY', $resultArray['APIKEY']);
define('sms_username', $resultArray['sms_username']);
define('sms_password', $resultArray['sms_password']);
define('company_name', $resultArray['company_name']);
define('tour_departure_date', $resultArray['tour_departure_date']);
define('tour_ending_date', $resultArray['tour_ending_date']);
define('booked', $resultArray['booked']);
define('not_booked', $resultArray['not_booked']);
define('show_booking_form', $resultArray['show_booking_form']);
define('show_supplier_email', $resultArray['show_supplier_email']);
define('QUANTITY_BOX', $resultArray['quantity_box']);
define('SHOW_PREFIX', $resultArray['show_prefix']);

function dump_error_to_file($errno, $errstr) {
	$errorDetails=debug();
    file_put_contents(SRV_ROOT.'/tmp/errors.txt', date('Y-m-d H:i:s - ') . $errstr.$errorDetails, FILE_APPEND);
}
set_error_handler('dump_error_to_file');

function debug($value=''){ 
    $btr=debug_backtrace(); 
    $line=$btr[0]['line']; 
    $file=basename($btr[0]['file']); 
	return " ".$file." : ".$line."\r\n";
   /* print"<pre>$file:$line</pre>\n"; 
    if(is_array($value)){ 
        print"<pre>"; 
        print_r($value); 
        print"</pre>\n"; 
    }elseif(is_object($value)){ 
        $value.dump(); 
    }else{ 
        print("<p>&gt;${value}&lt;</p>"); 
    } */
} 

// MECHANISM TO LOGOUT THE USER IF USER IS INACTIVE FOR MORE THAN $inactive amount of time
$inactive = 1440; // amount of seconds of inactivity after which the user should be logged out

// check to see if $_SESSION["timeout"] is set
if (isset($_SESSION["timeout"])) {
    // calculate the session's "time to live"
    $sessionTTL = time() - $_SESSION["timeout"];
    if ($sessionTTL > $inactive) {
        session_destroy();
        header("Location:".WEB_ROOT);
		exit;
    }
}
//$_SESSION["timeout"] = time();


date_default_timezone_set('Asia/Calcutta'); 

// these are the directories where we will store all
// category and product images
define('CATEGORY_IMAGE_DIR', 'images/category/');
define('PRODUCT_IMAGE_DIR',  'images/product/');
// some size limitation for the category
// and product images

// all category image width must not 
// exceed 75 pixels
define('MAX_CATEGORY_IMAGE_WIDTH', 75);

// do we need to limit the product image width?
// setting this value to 'true' is recommended
define('LIMIT_PRODUCT_WIDTH',     true);

// maximum width for all product image
define('MAX_PRODUCT_IMAGE_WIDTH', 300);

// the width for product thumbnail
define('THUMBNAIL_WIDTH',         75);

// maximum width for all product image
define('MAX_EVENT_IMAGE_WIDTH', 1000);


// since all page will require a database access
// and the common library is also used by all
// it's logical to load these library here
require_once 'common.php';

require_once 'loan-functions.php';
require_once 'EMI-functions.php';

?>