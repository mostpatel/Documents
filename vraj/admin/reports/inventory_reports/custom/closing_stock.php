<?php require_once '../../../../lib/cg.php';
require_once '../../../../lib/bd.php';
require_once '../../../../lib/common.php';
require_once '../../../../lib/inventory-functions.php';
require_once '../../../../lib/inventory-item-barcode-functions.php';
require_once '../../../../lib/dictionary-functions.php';

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;


$columns = array( 
// datatable column index  => database column name
	0 =>'item_id', 
	1 => 'item_name',
	2=> 'item_code',
	3=> 'mfg_item_code',
	4=> 'closing_stock'
);


	$sql="SELECT edms_inventory_item.item_id, item_name, item_code, mfg_item_code,opening_quantity + (SELECT SUM(IF(type=0,quantity,-quantity)) FROM edms_inventory_item_jv WHERE edms_inventory_item_jv.item_id =  edms_inventory_item.item_id GROUP BY edms_inventory_item_jv.item_id ) as closing_stock FROM edms_inventory_item ";
	
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	
$query=dbQuery($sql);
$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 

	$nestedData[] = $row["item_id"];
	$nestedData[] = $row["item_name"];
	$nestedData[] = $row["item_code"];
	$nestedData[] = $row["mfg_item_name"];
	$nestedData[] = $row["closing_stock"];
	
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