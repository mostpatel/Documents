<?php 
require_once("cg.php");
require_once("common.php");
require_once("product-functions.php");
require_once("customer-functions.php");
require_once("our-company-function.php");
require_once("account-ledger-functions.php");
require_once("account-receipt-functions.php");
require_once("bd.php");
require_once("account-period-functions.php");
require_once("branch-counter-function.php");

	
function checkForProductsInArray($product_id_array,$qty_no_array,$packing_unit_id_array)
{
	$total_amount=0;
	$has_items=false;
	if(is_array($product_id_array) && count($product_id_array)>0)
	{	
		for($i=0;$i<count($product_id_array);$i++)
		{
			$product_id=$product_id_array[$i];
			$qty_no=$qty_no_array[$i];
			$packing_unit_id = $packing_unit_id_array[$i];
			if(!is_numeric($qty_no))
			$qty_no=0;
			if(checkForNumeric($product_id,$qty_no,$packing_unit_id) && $product_id>0 && $qty_no>=0 && $packing_unit_id>=0)
			{
				$total_amount = $total_amount + 1;
				$has_items = $total_amount;
			}	
			
		}
				
	}
	return $has_items;
	
	}

function ConvertProductNameArrayInToIdArray($item_name_array)
{
	$item_id_array = array();
	foreach($item_name_array as $item_name){
		
		$item_id=insertProductIfNotDuplicate($item_name);
		if(checkForNumeric($item_id))
		$item_id_array[]=$item_id;
		else
		$item_id_array[]="";
	}
	return $item_id_array;
	
	}		
	
function insertLR($lr_date,$lr_no,$from_branch_ledger_id,$to_branch_ledger_id,$from_customer_name,$to_customer_name,$product_name_array,$product_qty_no_array,$product_qty_wt,$builty_charge,$tempo_fare,$rebooking_chares,$product_packing_unit_id_array,$tax_group_id,$total_freight,$remarks,$to_pay,$paid,$to_be_billed,$tax_pay_type,$delivery_at,$lr_type=0){
	
	try
	{
		$lr_no=clean_data($lr_no);
		$from_branch_ledger_id=clean_data($from_branch_ledger_id);
		$to_branch_ledger_id=clean_data($to_branch_ledger_id);
		
		if(!checkForNumeric($lr_type))
		$lr_type=0;
		
		$from_customer_name=clean_data($from_customer_name);
		$to_customer_name=clean_data($to_customer_name);
		$total_freight = clean_data($total_freight);
		$builty_charge = clean_data($builty_charge);
		$remarks = clean_data($remarks);
		$to_pay = clean_data($to_pay);
		$paid = clean_data($paid);
		$to_be_billed = clean_data($to_be_billed);
		
		if($total_freight==0)
		$builty_charge=0;
		if(!validateForNull($delivery_at))
		$delivery_at="";
		if(!is_numeric($product_qty_wt))
		$product_qty_wt=0;
		
		$from_customer_id = getCustomerIdFromCustomerNameAutoComplete($from_customer_name);
		if(!is_numeric($from_customer_id))
		$from_customer_id=insertCustomer($from_customer_name,'NA',3,2,NULL,'9999999999',NULL,NULL,NULL,NULL,NULL,'',0,0);
		
		$oc_id=getCompanyIdFromCustomerId($from_customer_id);
		$to_customer_id = getCustomerIdFromCustomerNameAutoComplete($to_customer_name);
		if(!is_numeric($to_customer_id))
		$to_customer_id=insertCustomer($to_customer_name,'NA',3,2,NULL,'9999999999',NULL,NULL,NULL,NULL,NULL,'',0,0);
		$from_customer = getCustomerDetailsByCustomerId($from_customer_id);
		
		$oc_id = $from_customer['oc_id'];
		
		$lr_counter = getLRCounterForBranchID($from_branch_ledger_id);
		$branch_code = getBranchCodeForBranchID($from_branch_ledger_id);
		$product_id_array = ConvertProductNameArrayInToIdArray($product_name_array);
		
        //	$product_id_array = $product_name_array;
	
		$has_products = checkForProductsInArray($product_id_array,$product_qty_no_array,$product_packing_unit_id_array);
		
	if(isset($lr_date) && validateForNull($lr_date))
    {
	$lr_date = str_replace('/', '-', $lr_date);
	$lr_date=date('Y-m-d',strtotime($lr_date));
	}

	if(!is_numeric($to_pay))
	$to_pay=0;
	if(!is_numeric($tempo_fare))
	$tempo_fare=0;
	if(!is_numeric($rebooking_chares))
	$rebooking_chares=0;
	if(!is_numeric($paid))
	$paid=0;
	if(!is_numeric($to_be_billed))
	$to_be_billed=0;
	
	$lr_noo = $branch_code.$lr_no;
	
	$total_collection_to_done = $to_pay + $to_be_billed + $paid;
	$freight = $total_freight;
	$total_freight = $total_freight + $builty_charge + $tempo_fare + $rebooking_chares;
	if(!is_numeric($tax_pay_type) || ($tax_pay_type<0 && $freight<=getFreightAmmountForTax()))
	$tax_pay_type=0;
	
		if(validateForNull($lr_date) && (checkForNumeric($from_branch_ledger_id,$to_branch_ledger_id,$from_customer_id,$to_customer_id,$total_freight,$tempo_fare,$rebooking_chares,$lr_type)) && (checkForNumeric($to_pay) || checkForNumeric($paid) || checkForNumeric($to_be_billed)) && !checkForDuplicateLR($lr_noo,$from_branch_ledger_id) && $has_products>0 && $from_branch_ledger_id>0 && $to_branch_ledger_id>0 && $from_customer_id>0 && $to_customer_id>0 && $tax_pay_type>=0)
		{
		
		if($total_collection_to_done!=$total_freight)
		return "freight_error";
		
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_lr
		      (from_branch_ledger_id,to_branch_ledger_id,delivery_at,from_customer_id,to_customer_id,freight,total_freight,builty_charge,tempo_fare,rebooking_charges,weight,remarks,lr_date,lr_no,to_pay,paid,to_be_billed,tax_pay_type, created_by, last_updated_by, date_added, date_modified, sync_lr_id,lr_updation_status,lr_type)
			  VALUES
			  ($from_branch_ledger_id, $to_branch_ledger_id,'$delivery_at',$from_customer_id,$to_customer_id,$freight,$total_freight,$builty_charge,$tempo_fare,$rebooking_chares,$product_qty_wt,'$remarks','$lr_date','$lr_noo',$to_pay,$paid,$to_be_billed,$tax_pay_type,$admin_id, $admin_id, NOW(), NOW(),-1,0,$lr_type)";
		dbQuery($sql);
		$lr_id = dbInsertId();
			  
		if(checkForNumeric($lr_id))
		{
			
			 insertProductsToLr($lr_id,$product_id_array,$product_qty_no_array,$product_packing_unit_id_array);
			 if($tax_pay_type==3 && $tax_group_id>0)
			 {
			 insertTaxToLr($lr_id,$tax_group_id,$freight);
			 }
			 if($lr_no==$lr_counter)
			 incrementLRNoForBranchID($from_branch_ledger_id);
			 
			 $sql="UPDATE edms_lr SET sync_lr_id=NULL WHERE lr_id = $lr_id";
			 dbQuery($sql);
			 return $lr_id;
		}	  
		return "error";
		}
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}	

function insertDeletedLR($lr_date,$lr_no,$from_branch_ledger_id,$to_branch_ledger_id,$from_customer_id,$to_customer_id,$product_qty_wt,$builty_charge,$tempo_fare,$rebooking_chares,$total_freight,$remarks,$to_pay,$paid,$to_be_billed,$tax_pay_type,$delivery_at,$sync_lr_id,$bd2=false){
	
	try
	{
		$lr_no=clean_data($lr_no);
		$from_branch_ledger_id=clean_data($from_branch_ledger_id);
		$to_branch_ledger_id=clean_data($to_branch_ledger_id);
		
		$from_customer_id=clean_data($from_customer_id);
		$to_customer_id=clean_data($to_customer_id);
		$total_freight = clean_data($total_freight);
		$builty_charge = clean_data($builty_charge);
		$remarks = clean_data($remarks);
		$to_pay = clean_data($to_pay);
		$paid = clean_data($paid);
		$to_be_billed = clean_data($to_be_billed);
		
		
		
		
		if(!checkForNumeric($sync_lr_id))
		$sync_lr_id="NULL";
	if(isset($lr_date) && validateForNull($lr_date))
    {
	$lr_date = str_replace('/', '-', $lr_date);
	$lr_date=date('Y-m-d',strtotime($lr_date));
	}

	
	
	
	
		if(validateForNull($lr_date,$lr_no) && (checkForNumeric($from_branch_ledger_id,$to_branch_ledger_id,$from_customer_id,$to_customer_id,$total_freight,$tempo_fare,$rebooking_chares)) && (checkForNumeric($to_pay) || checkForNumeric($paid) || checkForNumeric($to_be_billed))   && $from_branch_ledger_id>0 && $to_branch_ledger_id>0 && $from_customer_id>0 && $to_customer_id>0 && $tax_pay_type>=0)
		{
		
		
		
		
			
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_lr_deleted
		      (from_branch_ledger_id,to_branch_ledger_id,delivery_at,from_customer_id,to_customer_id,freight,total_freight,builty_charge,tempo_fare,rebooking_charges,weight,remarks,lr_date,lr_no,to_pay,paid,to_be_billed,tax_pay_type, created_by, last_updated_by, date_added, date_modified,sync_lr_id)
			  VALUES
			  ($from_branch_ledger_id, $to_branch_ledger_id,'$delivery_at',$from_customer_id,$to_customer_id,$total_freight,$total_freight,$builty_charge,$tempo_fare,$rebooking_chares,$product_qty_wt,'$remarks','$lr_date','$lr_no',$to_pay,$paid,$to_be_billed,$tax_pay_type,$admin_id, $admin_id, NOW(), NOW(),$sync_lr_id)";
		dbQuery($sql,$bd2);	 
		return $lr_id;
		}
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}	

function insertProductsToLr($lr_id,$product_id_array,$qty_no_array,$packing_unit_id_array)
{
	if(is_array($product_id_array) && count($product_id_array)>0)
	{
		for($i=0;$i<count($product_id_array);$i++)
		{
			$product_id=$product_id_array[$i];
			$qty_no=$qty_no_array[$i];
			$packing_unit_id = $packing_unit_id_array[$i];
			if(!is_numeric($qty_no))
			$qty_no=0;
			if(checkForNumeric($product_id,$qty_no,$packing_unit_id) && $product_id>0 && $qty_no>=0  && $packing_unit_id>=0)
			{
					
				$lr_product_id=insertProductToLr($lr_id,$product_id,$qty_no,$packing_unit_id);
				
			}	
			
		}
	
				
	}
	
}
function insertProductToLr($lr_id,$product_id,$qty_no,$packing_unit_id,$bd2=false)
{
	if(!is_numeric($qty_no))
	$qty_no=0;
	if(checkForNumeric($product_id,$qty_no,$packing_unit_id) && $product_id>0 && $qty_no>=0 && $packing_unit_id>=0)
	{
		if($packing_unit_id==0)
		$packing_unit_id="NULL";
		
		$sql="INSERT INTO edms_lr_product (product_id,lr_id,qty_no,packing_unit_id) VALUES ($product_id,$lr_id,$qty_no,$packing_unit_id)";
		dbQuery($sql,$bd2);
		
		$lr_product_id = dbInsertId($bd2);
		return $lr_product_id;
	}
	return false;
	
}

function deleteProductToLr($lr_id,$bd2=false)
{
	if(checkForNumeric($lr_id))
	{
		$sql="DELETE FROM edms_lr_product WHERE lr_id = $lr_id";
		dbQuery($sql,$bd2);
		return true;
	}
	return false;
}

function insertTaxToLr($lr_id,$tax_group_id,$freight,$bd2=false)
{
	$freight_cap=getFreightAmmountForTax();
	if(checkForNumeric($lr_id,$tax_group_id,$freight) && $lr_id>0  && $tax_group_id>0 && $freight>0 && $freight>=$freight_cap)
	{
		$taxes = listTaxsFromTaxGroupId($tax_group_id);
		
		foreach($taxes as $tax)
		{
		
		$tax_id = $tax['tax_id'];
		$tax_percent = $tax['tax_percent'];
		$tax_ledger_id = getTaxLedgerForTaxID($tax_id);
		$tax_amount = $freight * ($tax_percent/100)*(getTaxOnFreightPercentage()/100);
		$tax_amount = round($tax_amount,0,PHP_ROUND_HALF_UP);
		$tax= getTaxByID($tax_id);
		$tax_in_out = $tax['in_out'];
		
		$sql="INSERT INTO edms_lr_tax (lr_id, tax_group_id,  tax_id, tax_amount) VALUES ($lr_id,$tax_group_id,$tax_id,$tax_amount)";
		dbQuery($sql,$bd2);	
		
		}
		
	}
	
}

function deleteLR($id,$bd2=false){
	
	try
	{
		
		if(checkForNumeric($id) && !checkIfLRInUse($id,$bd2))
		{
		$lr = getLRById($id,$bd2);

		$sql="DELETE FROM edms_lr
		      WHERE lr_id=$id";
		

		dbQuery($sql,$bd2);

		insertDeletedLR($lr['lr_date'],$lr['lr_no'],$lr['from_branch_ledger_id'],$lr['to_branch_ledger_id'],$lr['from_customer_id'],$lr['to_customer_id'],$lr['weight'],$lr['builty_charge'],$lr['tempo_fare'],$lr['rebooking_charges'],$lr['total_freight'],$lr['remarks'],$lr['to_pay'],$lr['paid'],$lr['to_be_billed'],$lr['tax_pay_type'],$lr['delivery_at'],$lr['sync_lr_id'],$bd2);
		return "success";	  
		}
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}	

function deleteTaxForLR($id,$bd2=false){
	
	try
	{
		if(checkForNumeric($id) && !checkIfLRInUse($id,$bd2))
		{
		$sql="DELETE FROM edms_lr_tax
		      WHERE lr_id=$id";
		dbQuery($sql,$bd2);
		return "success";	  
		}
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}	

function updateLr($lr_id,$lr_date,$lr_no,$from_branch_ledger_id,$to_branch_ledger_id,$from_customer_name,$to_customer_name,$product_name_array,$product_qty_no_array,$product_qty_wt,$builty_charge,$tempo_fare,$rebooking_chares,$product_packing_unit_id_array,$tax_group_id,$total_freight,$remarks,$to_pay,$paid,$to_be_billed,$tax_pay_type,$delivery_at,$lr_type=0){
	
	try
	{
		$lr = getLRById($lr_id);
		if(SLAVE==1)
		{
		$lr_added_date = $lr['date_added'];
		$edit_date_allowed = getTodaysDateTimeAfterDays(UPDATE_CONTRAINT,$lr_added_date);
		
		if(strtotime($edit_date_allowed)<strtotime(getTodaysDateTime()))
		{
			
			return "error";
		}
		}
		
		if(!checkForNumeric($lr_type))
		$lr_type=0;
		
		$lr_no=clean_data($lr_no);
		$from_branch_ledger_id=clean_data($from_branch_ledger_id);
		$to_branch_ledger_id=clean_data($to_branch_ledger_id);
		$from_customer_name=clean_data($from_customer_name);
		$to_customer_name=clean_data($to_customer_name);
		$total_freight = clean_data($total_freight);
		$remarks = clean_data($remarks);
		$to_pay = clean_data($to_pay);
		$paid = clean_data($paid);
		$to_be_billed = clean_data($to_be_billed);
		if(!is_numeric($tax_pay_type))
		$tax_pay_type=0;
		if(!is_numeric($product_qty_wt))
		$product_qty_wt=0;
		$from_customer_id = getCustomerIdFromCustomerNameAutoComplete($from_customer_name);
		if(!is_numeric($from_customer_id))
		$from_customer_id=insertCustomer($from_customer_name,'NA',3,2,NULL,'9999999999',NULL,NULL,NULL,NULL,NULL,'',0,0);
		
		$oc_id=getCompanyIdFromCustomerId($from_customer_id);
		$to_customer_id = getCustomerIdFromCustomerNameAutoComplete($to_customer_name);
		if(!is_numeric($to_customer_id))
		$to_customer_id=insertCustomer($to_customer_name,'NA',3,2,NULL,'9999999999',NULL,NULL,NULL,NULL,NULL,'',0,0);
	
		
		if(!is_numeric($tax_pay_type) || $tax_pay_type<0)
		$tax_pay_type=0;
		
		if($total_freight==0)
		$builty_charge=0;
		
		$product_id_array = ConvertProductNameArrayInToIdArray($product_name_array);
		$has_products = checkForProductsInArray($product_id_array,$product_qty_no_array,$product_packing_unit_id_array);
		
	if(isset($lr_date) && validateForNull($lr_date))
    {
	$lr_date = str_replace('/', '-', $lr_date);
	$lr_date=date('Y-m-d',strtotime($lr_date));
	}

	if(!is_numeric($to_pay))
	$to_pay=0;
	if(!is_numeric($paid))
	$paid=0;
	if(!is_numeric($to_be_billed))
	$to_be_billed=0;
	if(!is_numeric($tempo_fare))
	$tempo_fare=0;
	if(!is_numeric($rebooking_chares))
	$rebooking_chares=0;
	$branch_code=getBranchCodeForBranchID($from_branch_ledger_id);
	$lr_noo = $branch_code.$lr_no;
		
		if(validateForNull($lr_date) && (checkForNumeric($lr_id,$from_branch_ledger_id,$to_branch_ledger_id,$from_customer_id,$to_customer_id,$total_freight,$builty_charge,$tempo_fare,$rebooking_chares,$product_qty_wt,$lr_type)) && (checkForNumeric($to_pay) || checkForNumeric($paid) || checkForNumeric($to_be_billed)) && !checkForDuplicateLR($lr_noo,$from_branch_ledger_id,$lr_id) && $has_products>0 && $from_branch_ledger_id>0 && $to_branch_ledger_id>0 && $from_customer_id>0 && $to_customer_id>0 && !checkIfInvoiceGeneratedForLR($lr_id) && $tax_pay_type>=0)
		{
		$total_collection_to_done = $to_pay + $to_be_billed + $paid;
		
		$freight = $total_freight;
		$total_freight = $total_freight + $builty_charge + $tempo_fare + $rebooking_chares;
		
		if($total_collection_to_done!=$total_freight)
		return "freight_error";
		
		$from_customer = getCustomerDetailsByCustomerId($from_customer_id);
		$oc_id = $from_customer['our_company_id'];
			
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		
			
		$sql="UPDATE edms_lr
		      SET from_branch_ledger_id = $from_branch_ledger_id, to_branch_ledger_id = $to_branch_ledger_id, delivery_at='$delivery_at',from_customer_id = $from_customer_id, to_customer_id = $to_customer_id, freight = $freight, total_freight = $total_freight, builty_charge = $builty_charge, tempo_fare = $tempo_fare, rebooking_charges = $rebooking_chares, weight = $product_qty_wt, remarks = '$remarks' ,lr_no='$lr_noo' , lr_date = '$lr_date' , to_pay = $to_pay , paid = $paid, to_be_billed = $to_be_billed, tax_pay_type = $tax_pay_type, last_updated_by = $admin_id, date_modified = NOW(), lr_type=$lr_type
			  WHERE lr_id=$lr_id";
		dbQuery($sql);	  
		deleteProductToLr($lr_id);
		insertProductsToLr($lr_id,$product_id_array,$product_qty_no_array,$product_packing_unit_id_array);
		deleteTaxForLR($lr_id);
		if($tax_pay_type==3 && $tax_group_id>0)
		insertTaxToLr($lr_id,$tax_group_id,$freight);
		
		$sql="UPDATE edms_lr SET lr_updation_status = 1 WHERE lr_id = $lr_id";
		dbQuery($sql);
		return "success";
		}
		else if(checkIfInvoiceGeneratedForLR($lr_id))
		return "invoice_error";
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}	

function getLRById($id,$bd2=false){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT  edms_lr.lr_id,from_branch_ledger_id,to_branch_ledger_id,from_customer_id,from_customer.customer_name as from_customer_name,to_customer_id,edms_customer.customer_name as to_customer_name,freight,total_freight,weight,builty_charge,tempo_fare,rebooking_charges,remarks,lr_date,lr_no,to_pay,paid,to_be_billed, tax_pay_type, edms_lr.created_by, edms_lr.last_updated_by, edms_lr.date_added, edms_lr.date_modified, SUM(tax_amount) as total_tax,delivery_at, tax_group_id, tax_pay_type, sync_lr_id, lr_updation_status,lr_type
		      FROM edms_lr
			  LEFT JOIN edms_lr_tax ON edms_lr.lr_id = edms_lr_tax.lr_id
			  LEFT JOIN edms_customer as from_customer ON edms_lr.from_customer_id = from_customer.customer_id
			  LEFT JOIN edms_customer ON edms_customer.customer_id = edms_lr.to_customer_id
			  WHERE edms_lr.lr_id=$id GROUP BY edms_lr.lr_id";	  
		$result=dbQuery($sql,$bd2);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
		}
		return false;
	}
	catch(Exception $e)
	{
	}
	
}	

function getLRIdByLrNo($id){
	
	try
	{
		if(validateForNull($id))
		{
		$sql="SELECT      edms_lr.lr_id,from_branch_ledger_id,to_branch_ledger_id,from_customer_id,to_customer_id,freight,total_freight,weight,builty_charge,tempo_fare,rebooking_charges,remarks,lr_date,lr_no,to_pay,paid,to_be_billed, tax_pay_type, created_by, last_updated_by, date_added, date_modified, SUM(tax_amount) as total_tax,delivery_at
		      FROM edms_lr
			  LEFT JOIN edms_lr_tax ON edms_lr.lr_id = edms_lr_tax.lr_id
			  WHERE edms_lr.lr_no='$id' GROUP BY edms_lr.lr_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)==1)
		return $resultArray[0][0];
		else if(dbNumRows($result)>1)
		return $resultArray;
		else return false;	 
		}
		return false;
	}
	catch(Exception $e)
	{
	}
	
}	

function getProductsByLRId($id,$bd2=false)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT edms_lr_product.lr_product_id,edms_product.product_id,product_name,edms_lr_product.lr_id,qty_no,edms_lr_product.packing_unit_id, packing_unit FROM edms_lr_product INNER JOIN edms_product ON edms_product.product_id = edms_lr_product.product_id LEFT JOIN edms_packing_unit ON edms_lr_product.packing_unit_id = edms_packing_unit.packing_unit_id WHERE  edms_lr_product.lr_id = $id GROUP BY edms_lr_product.lr_product_id ";
		
	$result=dbQuery($sql,$bd2);
		$resultArray=dbResultToArray($result);
	
		if(dbNumRows($result)>0)
		return $resultArray;	 
		}
		return false;
	
}

function getQuantityByLRId($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT SUM(qty_no) FROM edms_lr_product  WHERE  edms_lr_product.lr_id = $id GROUP BY edms_lr_product.lr_id ";
		
	$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
	
		if(dbNumRows($result)>0)
		return $resultArray[0][0];	 
		}
		return false;
	
}

function checkForDuplicateLR($lr_no,$from_branch_ledger_id,$id=false,$bd2=false)
{
	
	    if(validateForNull($lr_no,$from_branch_ledger_id))
		{
		$sql="SELECT lr_id
		      FROM edms_lr
			  WHERE lr_no='$lr_no' AND from_branch_ledger_id = $from_branch_ledger_id AND lr_updation_status !=-1 ";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND lr_id!=$id";		  	  
		
		$result=dbQuery($sql,$bd2);
		
			
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
	}	
function checkIfLRInUse($id,$bd2=false)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT lr_id FROM
			edms_trip_lr,edms_invoice_trip_memo
			WHERE edms_trip_lr.trip_memo_id = edms_invoice_trip_memo.trip_memo_id AND edms_trip_lr.lr_id=$id
			UNION ALL
			SELECT lr_id FROM edms_rel_paid_lr WHERE lr_id = $id";
	$result=dbQuery($sql,$bd2);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
	}
function checkIfInvoiceGeneratedForLR($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT invoice_id FROM
			edms_invoice_trip_memo,edms_trip_lr
			WHERE edms_invoice_trip_memo.trip_memo_id = edms_trip_lr.trip_memo_id and lr_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
}	
	
function getUnTrippedLrs()
{
$admin_id = $_SESSION['edmsAdminSession']['admin_id'];
$current_company=getCurrentCompanyForUser($admin_id);	
$oc_id = $current_company[0];

$sql="SELECT lr_id,from_branch_ledger_id,from_ledger.ledger_name as from_branch_ledger_name,to_branch_ledger_id,to_ledger.ledger_name as to_branch_ledger_name,from_customer_id,from_customer.customer_name as from_customer_name,to_customer_id ,to_customer.customer_name as to_customer_name,total_freight,remarks,lr_date,lr_no,lr_type FROM edms_lr 
INNER JOIN edms_ac_ledgers as from_ledger ON from_ledger.ledger_id = edms_lr.from_branch_ledger_id 
INNER JOIN edms_ac_ledgers as to_ledger ON to_ledger.ledger_id = edms_lr.to_branch_ledger_id
INNER JOIN edms_customer as from_customer ON from_customer.customer_id = edms_lr.from_customer_id
INNER JOIN edms_customer as to_customer ON to_customer.customer_id = edms_lr.to_customer_id
WHERE from_ledger.oc_id = $oc_id AND lr_id NOT IN (SELECT lr_id FROM edms_trip_lr) AND lr_updation_status!=-1";

	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
		if(dbNumRows($result)>0)
		return $resultArray;	 
		else
		return false;
	
}	

function getUnTrippedLrIDsFromBranchToBranch($from_branch,$to_branch)
{
$admin_id = $_SESSION['edmsAdminSession']['admin_id'];
$current_company=getCurrentCompanyForUser($admin_id);	
$oc_id = $current_company[0];

$sql="SELECT lr_id,from_branch_ledger_id,from_ledger.ledger_name as from_branch_ledger_name,to_branch_ledger_id,to_ledger.ledger_name as to_branch_ledger_name,from_customer_id,from_customer.customer_name as from_customer_name,to_customer_id ,to_customer.customer_name as to_customer_name,total_freight,remarks,lr_date,lr_no,lr_type FROM edms_lr 
INNER JOIN edms_ac_ledgers as from_ledger ON from_ledger.ledger_id = edms_lr.from_branch_ledger_id 
INNER JOIN edms_ac_ledgers as to_ledger ON to_ledger.ledger_id = edms_lr.to_branch_ledger_id
INNER JOIN edms_customer as from_customer ON from_customer.customer_id = edms_lr.from_customer_id
INNER JOIN edms_customer as to_customer ON to_customer.customer_id = edms_lr.to_customer_id
WHERE from_ledger.oc_id = $oc_id AND lr_id NOT IN (SELECT lr_id FROM edms_trip_lr) AND lr_updation_status!=-1 AND edms_lr.from_branch_ledger_id =$from_branch AND edms_lr.to_branch_ledger_id = $to_branch";

	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
		if(dbNumRows($result)>0)
		{
		$return_array = array();
		foreach($resultArray as $re)
		$return_array[]=$re['lr_id'];	
		return $return_array;
		}
		else
		return false;
	
}	

function getUnPaidLRsForCustomer($customer_id)
{
	
	
}	

function getFromLRsForCustomer($customer_id)
{
	if(checkForNumeric($customer_id))
	{
	$sql="SELECT    edms_lr.lr_id,from_branch_ledger_id,from_ledger.ledger_name as from_branch_ledger_name,to_branch_ledger_id,to_ledger.ledger_name as to_branch_ledger_name,from_customer_id,from_customer.customer_name as from_customer_name,to_customer_id ,to_customer.customer_name as to_customer_name,total_freight,remarks,lr_date,lr_no,to_pay,paid,to_be_billed, edms_lr.created_by, edms_lr.last_updated_by, edms_lr.date_added, edms_lr.date_modified, (SELECT SUM(tax_amount) FROM edms_lr_tax WHERE edms_lr.lr_id = edms_lr_tax.lr_id GROUP BY edms_lr_tax.lr_id )  as total_tax
		      FROM edms_lr
			  INNER JOIN edms_ac_ledgers as from_ledger ON from_ledger.ledger_id = edms_lr.from_branch_ledger_id 
			  INNER JOIN edms_ac_ledgers as to_ledger ON to_ledger.ledger_id = edms_lr.to_branch_ledger_id
INNER JOIN edms_customer as from_customer ON from_customer.customer_id = edms_lr.from_customer_id
INNER JOIN edms_customer as to_customer ON to_customer.customer_id = edms_lr.to_customer_id
			  WHERE edms_lr.from_customer_id=$customer_id";
	$result = dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
		if(dbNumRows($result)>0)
		return $resultArray;	 
		else
		return false;
	}
	
	
}	

function getToLRsForCustomer($customer_id)
{
	if(checkForNumeric($customer_id))
	{
	$sql="SELECT      edms_lr.lr_id,from_branch_ledger_id,from_ledger.ledger_name as from_branch_ledger_name,to_branch_ledger_id,to_ledger.ledger_name as to_branch_ledger_name,from_customer_id,from_customer.customer_name as from_customer_name,to_customer_id ,to_customer.customer_name as to_customer_name,total_freight,remarks,lr_date,lr_no,to_pay,paid,to_be_billed, edms_lr.created_by, edms_lr.last_updated_by, edms_lr.date_added, edms_lr.date_modified, (SELECT SUM(tax_amount) FROM edms_lr_tax WHERE edms_lr.lr_id = edms_lr_tax.lr_id GROUP BY edms_lr_tax.lr_id )  as total_tax
		      FROM edms_lr
			  INNER JOIN edms_ac_ledgers as from_ledger ON from_ledger.ledger_id = edms_lr.from_branch_ledger_id 
			  INNER JOIN edms_ac_ledgers as to_ledger ON to_ledger.ledger_id = edms_lr.to_branch_ledger_id
INNER JOIN edms_customer as from_customer ON from_customer.customer_id = edms_lr.from_customer_id
INNER JOIN edms_customer as to_customer ON to_customer.customer_id = edms_lr.to_customer_id
			  WHERE edms_lr.to_customer_id=$customer_id";	
	$result = dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
		if(dbNumRows($result)>0)
		return $resultArray;	 
		else
		return false;
	}
}	

function getJvAmountsAndIdsForLr($lr_id)
{
	if(checkForNumeric($lr_id))
	{
		$sql="SELECT from_branch_ledger_id,to_branch_ledger_id,from_customer_id, to_pay,paid,to_be_billed,tax_pay_type,  (SELECT SUM(tax_amount) FROM edms_lr_tax WHERE edms_lr.lr_id = edms_lr_tax.lr_id GROUP BY edms_lr_tax.lr_id )  as total_tax FROM edms_lr WHERE lr_id = $lr_id";
		$result = dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	$debit_array = array();
	$credit_array = array();
		if(dbNumRows($result)>0)
		{
			$lr = $resultArray[0];
			if($lr['paid']>0)
			$debit_array[] = array($lr['from_branch_ledger_id'],$lr['paid'],"L");
			if($lr['to_pay']>0)
			$debit_array[] = array($lr['to_branch_ledger_id'],$lr['to_pay'],"L");
			if($lr['to_be_billed']>0)
			$debit_array[] = array($lr['from_customer_id'],$lr['to_be_billed'],"C");
			
			$max_value = max($lr['paid'],$lr['to_pay'],$lr['to_be_billed']);
			if($lr['total_tax']>0)
			{
				if($lr['tax_pay_type']==0)
				{
			/*	if($max_value==$lr['paid'])
				$debit_array[] = array($lr['from_branch_ledger_id'],$lr['total_tax'],"L");
				else if($max_value==$lr['to_pay'])
				$debit_array[] = array($lr['to_branch_ledger_id'],$lr['total_tax'],"L");
				else 
				$debit_array[] = array($lr['from_customer_id'],$lr['total_tax'],"C"); */
				}
				else if($lr['tax_pay_type']==1)
				{
		//			$debit_array[] = array($lr['to_branch_ledger_id'],$lr['total_tax'],"L");
					}
				else if($lr['tax_pay_type']==2)
				{
			//			$debit_array[] = array($lr['from_branch_ledger_id'],$lr['total_tax'],"L");

					}
				else if($lr['tax_pay_type']==3)
				{
					if($max_value==$lr['paid'])
				$debit_array[] = array($lr['from_branch_ledger_id'],$lr['total_tax'],"L");
				else if($max_value==$lr['to_pay'])
				$debit_array[] = array($lr['to_branch_ledger_id'],$lr['total_tax'],"L");
				else 
				$debit_array[] = array($lr['from_customer_id'],$lr['total_tax'],"C");
				
				$tax_array = getTaxForLr($lr_id);
				foreach($tax_array as $t)
				{
					$credit_array[] = array($t['tax_ledger_id'],$t['tax_amount'],'L');
				}
				
					}
				
			}
		}
	return array("debit" => $debit_array,"credit" => $credit_array);		
	}
}

function getTaxForLr($lr_id,$bd2=false)
{
	if(checkForNumeric($lr_id))
	{
		$sql="SELECT tax_amount,edms_lr_tax.tax_id,tax_ledger_id,tax_group_id FROM edms_lr_tax,edms_tax WHERE edms_lr_tax.tax_id = edms_tax.tax_id AND lr_id = $lr_id";
		$result = dbQuery($sql,$bd2);
	$resultArray=dbResultToArray($result);
	return $resultArray;
	}
	
}

?>