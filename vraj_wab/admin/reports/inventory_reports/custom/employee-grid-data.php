<?php
require_once('../../../../lib/cg.php');
require_once('../../../../lib/bd.php');
require_once('../../../../lib/common.php');
require_once('../../../../lib/inventory-functions.php');
/* Database connection start */


$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName) or die("Connection failed: " . mysqli_connect_error());

/* Database connection end */


// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;


$columns = array( 
// datatable column index  => database column name
	1 => 'item_name', 
	2 => 'item_code',
	3=>  'mfg_item_code',
	4 => 'qty_in_stock', 
	5 => 'godown_name',
	6=>  'ledger_name',
	7=>  'manufacturer_name'
);
$suppliers_string=$_SESSION['homePage']['supplier_id_string'];
$manufacturer_string=$_SESSION['homePage']['manufacturer_id_string'];
// getting total number records without any search
$sql = "SELECT item_name, item_code, mfg_item_code ";
$sql.=" FROM edms_inventory_item";

$query=mysqli_query($conn, $sql) or die("employee-grid-data.php: get employees");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT item_id,item_name, item_code, mfg_item_code, opening_quantity + IFNULL((SELECT SUM(IF(type=0,quantity,-quantity))  FROM edms_inventory_item_jv WHERE edms_inventory_item_jv.item_id = edms_inventory_item.item_id GROUP BY edms_inventory_item_jv.item_id ),0) as qty_in_stock, godown_name, opening_quantity, manufacturer_name, ledger_name ";
$sql.=" FROM edms_inventory_item LEFT JOIN edms_godown ON edms_godown.godown_id = edms_inventory_item.opening_godown_id  LEFT JOIN edms_item_manufacturer ON edms_item_manufacturer.manufacturer_id = edms_inventory_item.manufacturer_id LEFT JOIN edms_ac_ledgers ON edms_ac_ledgers.ledger_id = edms_inventory_item.supplier_id WHERE 1=1
 ";
if(validateForNull($suppliers_string))
{
	$sql.=" AND edms_inventory_item.supplier_id IN ($suppliers_string) ";
} 
if(validateForNull($manufacturer_string))
{
	$sql.=" AND edms_item_manufacturer.manufacturer_id IN ($manufacturer_string) ";
} 
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( item_name LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR item_code LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR mfg_item_code LIKE '%".$requestData['search']['value']."%' )";
}

$query=mysqli_query($conn, $sql) or die("employee-grid-data.php: get employees");
$totalFiltered = mysqli_num_rows($query);
if( !empty($requestData['order'][0]) ) {
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
}
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	

$query=mysqli_query($conn, $sql) or die("employee-grid-data.php: get employees");
 // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$data = array();
$i=1;
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 
	$item_id = $row['item_id'];
	$nestedData[] = $i++;
	$item_name = utf8_encode($row["item_name"]);
	if(validateForNull($item_name))
	$nestedData[] = $item_name;
	else
	$nestedData[] = "INVALID NAME";
	$nestedData[] = $row["item_code"];
	$nestedData[] = $row["mfg_item_code"];
	$nestedData[] = $row["qty_in_stock"];
	$nestedData[] = "<span class='editable' id='$item_id'>".$row["godown_name"]."</span>";
	$nestedData[] = $row["ledger_name"];
	$nestedData[] = $row["manufacturer_name"];
	$url= WEB_ROOT.'admin/settings/inventory_settings/item_settings/index.php?view=edit&lid='.$row['item_id'];
	$nestedData[] = "<a href='$url'><button title='Edit this entry' class='btn viewBtn'><span class='view'>E</span></button></a>";
	$data[] = $nestedData;
}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval($totalData),  // total number of records
			"recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format

?>
