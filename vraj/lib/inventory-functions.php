<?php
require_once("cg.php");
require_once("city-functions.php");
require_once("account-ledger-functions.php");
require_once("account-head-functions.php");
require_once("account-payment-functions.php");
require_once("account-receipt-functions.php");
require_once("account-purchase-functions.php");
require_once("account-sales-functions.php");
require_once("account-debit-note-functions.php");
require_once("account-credit-note-functions.php");
require_once("inventory-purchase-functions.php");
require_once("inventory-sales-functions.php");
require_once("account-jv-functions.php");
require_once("tax-functions.php");
require_once("vehicle-functions.php");
require_once("vehicle-model-functions.php");
require_once("account-contra-functions.php");
require_once("account-functions.php");
require_once("inventory-item-functions.php");
require_once("inventory-jv-functions.php");
require_once("account-combined-agency-functions.php");
require_once("common.php");
require_once("bd.php");

function getAllTransactionsForItemIdMonthWise($id,$transaction_type_array=NULL,$from=NULL,$to=NULL,$model=false)
{
	
	$month_year_array=getMonthYearArrayFromDates($from,$to);
	
	$return_array=array();
	foreach($month_year_array as $month_year)
	{
	
		$month=$month_year['month'];
		$year=$month_year['year'];
		$month_year=$month_year['month_year'];
		
		$first_day_next_month = getFirstDayOfNextMonth($month,$year);
		
	if((validateForNull($transaction_type_array) && in_array(1,$transaction_type_array)) || !validateForNull($transaction_type_array))
	{	
	$purchase=getTotalPurchaseForItemIdForMonth($id,$month,$year,$from,$to,$model);
	$purchase_amount = $purchase[0];
	$purchase_rate = round($purchase[1],2);
	$purchase_quantity = $purchase[2];
	}
	
	
	if((validateForNull($transaction_type_array) && in_array(2,$transaction_type_array)) || !validateForNull($transaction_type_array))
	{
	$sale=getTotalSaleForItemIdForMonth($id,$month,$year,$from,$to,$model);
	$sale_amount = $sale[0];
	$sale_rate = round($sale[1],2);
	$sale_quantity = $sale[2];
	}
	
	$closing_balance = getOpeningBalanceForItemForDate($id,$first_day_next_month,$model);
	$closing_balance_amount = round($closing_balance[0],2);
	$closing_balance_rate = round($closing_balance[2],2);
	$closing_balance_quantity = $closing_balance[1];
	
	$net_amount=$purchase_amount-$sale_amount;
	$return_array[$month_year]=array($purchase_amount,$purchase_quantity,$purchase_rate,$sale_amount,$sale_quantity,$sale_rate,$closing_balance_amount,$closing_balance_quantity,$closing_balance_rate,$month,$year);
	
	
	
	}
	return $return_array;	
		
}
	
function getAllTransactionsForItemId($id,$transaction_type_array=NULL,$from=NULL,$to=NULL,$model=false)
{
	
	if((validateForNull($transaction_type_array) && in_array(1,$transaction_type_array)) || !validateForNull($transaction_type_array))
	$payments=getPurchasesForLedgerIdBetweenDates($id,$from,$to);
	
	
	if((validateForNull($transaction_type_array) && in_array(2,$transaction_type_array)) || !validateForNull($transaction_type_array))
	{
	$receipts=getReceiptsForLedgerIdBetweenDates($id,$from,$to);
	}
	
	if(substr($id, 0, 1) == 'L')
	{
		$ledger_id=str_replace('L','',$id);
		$ledger_id=intval($ledger_id);
		$head_type=getLedgerHeadType($ledger_id);
		}
	else if(substr($id, 0, 1) == 'C')
	{
		$head_type=1;
		}	
	
	
	if($head_type==0)
	{
		if((validateForNull($transaction_type_array) && in_array(4,$transaction_type_array)) || !validateForNull($transaction_type_array))
		$contras=getContrasForLedgerIdBetweenDates($id,$from,$to);
		if(!is_array($payments)) $payments=array();
		if(!is_array($receipts)) $receipts=array();
		if(!is_array($contras)) $contras=array();
		$returnArray=array_merge($payments,$receipts,$contras);
		uasort($returnArray,'TransDateComparator');
		
		return array($returnArray,$head_type);
		}
	else if($head_type==1)
	{
		if((validateForNull($transaction_type_array) && in_array(3,$transaction_type_array)) || !validateForNull($transaction_type_array))
		$jvs=getJVsForLedgerIdBetweenDates($id,$from,$to);
		if(!is_array($payments)) $payments=array();
		if(!is_array($receipts)) $receipts=array();
		if(!is_array($contras)) $contras=array();
		$returnArray=array_merge($payments,$receipts,$jvs);
		uasort($returnArray,'TransDateComparator');
		return array($returnArray,$head_type);
		}
	return false;	
		
	}	

function getAllTransactionsForItemIdForMonth($id,$month,$year,$transaction_type_array=NULL,$from=NULL,$to=NULL,$model=false)
{
	if(checkForNumeric($id))
	{
	if((validateForNull($transaction_type_array) && in_array(1,$transaction_type_array)) || !validateForNull($transaction_type_array))
	$purchases=getPurchasesForItemIdForMonth($id,$month,$year,$from,$to,$model);

	if((validateForNull($transaction_type_array) && in_array(2,$transaction_type_array)) || !validateForNull($transaction_type_array))
	$sales=getSalesForItemIdForMonth($id,$month,$year,$from,$to,$model);
	
	
	
		$returnArray=array_merge($purchases,$sales);
	
		uasort($returnArray,'TransDateComparator');
		return $returnArray;
	}
	return false;	
		
}		

function getOpeningBalanceForItemForDate($id,$date,$model=false) // return opening balance on date for id, id should start with L or C
{
	if(checkForNumeric($id))
	{	
	$to=getPreviousDate($date); // return d-m-y
	if(!$model)
	{
	$opening_balance_array=getOpeningBalanceForItem($id);	
	$opening_balance_item=$opening_balance_array[0];
	$opening_balance_quantity=$opening_balance_array[1];
	$opening_balance_rate=$opening_balance_array[2];
	}
	else
	{
		
	$opening_balance_array=GetOpeningBalanceAndQuantityForModel($id);
	$opening_balance_item=$opening_balance_array[1];
	$opening_balance_quantity=$opening_balance_array[0];
	$opening_balance_rate=$opening_balance_array[2];
	}
	
    $total_purchase=getTotalPurchaseForItemIdUptoDate($id,$to,$model);
	$total_debit_note = getTotalDebitNoteForItemIdUptoDate($id,$to,$model);		
	$total_sale = getTotalSaleForItemIdUptoDate($id,$to,$model);
	$total_credit_note = getTotalCreditNoteForItemIdUptoDate($id,$to,$model);
	$total_debit_jv = getTotalDebitJvForItemIdUptoDate($id,$to,$model);	
	$total_credit_jv = getTotalCreditJvForItemIdUptoDate($id,$to,$model,$godown_id);
	
	if($total_debit_note==0)
	{
	$total_debit_note_amount = 0;
	$total_debit_note_quantity =0;
	$total_debit_note_avg_rate =0;
	}
	else if(is_array($total_debit_note))
	{
	$total_debit_note_amount = $total_debit_note['total_amount'];
	$total_debit_note_quantity = $total_debit_note['quantity'];
	$total_debit_note_rate = $total_debit_note['avg_rate'];
	}
		
	if($total_purchase==0)
	{
	$total_purchase_amount = 0;
	$total_purchase_quantity =0;
	$total_purchase_avg_rate =0;
	}
	else if(is_array($total_purchase))
	{
	$total_purchase_amount = $total_purchase['total_amount'];
	$total_purchase_quantity = $total_purchase['quantity'];
	$total_purchase_avg_rate = $total_purchase['avg_rate'];
	}
	
	if(is_array($total_debit_jv))
		 {	
		$total_debit_jv_amount = $total_debit_jv['total_amount'];
		$total_debit_jv_quantity = $total_debit_jv['quantity'];
		$total_debit_jv_avg_rate = $total_debit_jv['avg_rate'];
		 }
		 else
		 {
		$total_debit_jv_amount = 0;
		$total_debit_jv_quantity = 0;
		$total_debit_jv_avg_rate = 0;
		 }
	
	if($total_credit_jv==0)
	{
	$total_credit_jv_amount = 0;
	$total_credit_jv_quantity = 0;
	$total_credit_jv_avg_rate = 0;
	}
	else if(is_array($total_credit_jv))
	{
	$total_credit_jv_amount = $total_credit_jv['total_amount'];
	$total_credit_jv_quantity = $total_credit_jv['quantity'];
	$total_credit_jv_avg_rate = $total_credit_jv['avg_rate'];
	}
	
	$total_purchase_amount = $total_purchase_amount - $total_debit_note_amount + $total_debit_jv_amount;
	$total_purchase_quantity = $total_purchase_quantity - $total_debit_note_quantity +  $total_debit_jv_quantity;
	
	
	if(($opening_balance_quantity + $total_purchase_quantity)>0)
	{	
	
		if(defined('INVENTORY_CAL_METHOD') && INVENTORY_CAL_METHOD==1) // average_method
		{
		$avg_rate = ($total_purchase_amount + $opening_balance_item) / ($opening_balance_quantity + $total_purchase_quantity);
		$avg_rate = round($avg_rate,2);
		}
		else if(defined('INVENTORY_CAL_METHOD') && INVENTORY_CAL_METHOD==2) // fifo
		{
	
		$avg_rate = getPurchaseAvgRateFifoUpToDate($id,$to,$model,$total_purchase,$total_sale);
		$avg_rate = round($avg_rate,2);
		}
		else 
		{
		$avg_rate = getPurchaseAvgRateFifoUpToDate($id,$to,$model,$total_purchase,$total_sale);
		$avg_rate = round($avg_rate,2);
		}
		
	}
	else
	$avg_rate=0;
	
	
	if($total_credit_note==0)
	{
	$total_credit_note_amount = 0;
	$total_credit_note_quantity =0;
	$total_credit_note_avg_rate =0;
	}
	else if(is_array($total_credit_note))
	{
	$total_credit_note_amount = $total_credit_note['total_amount'];
	$total_credit_note_quantity = $total_credit_note['quantity'];
	$total_credit_note_rate = $total_credit_note['avg_rate'];
	}
	
	
	if($total_sale==0)
	{
	$total_sale_amount = 0;
	$total_sale_quantity = 0;
	$total_sale_avg_rate = 0;
		
	}
	else if(is_array($total_sale))
	{
	$total_sale_amount = $total_sale['total_amount'];
	$total_sale_quantity = $total_sale['quantity'];
	$total_sale_avg_rate = $total_sale['avg_rate'];
	}
	
	$total_sale_amount = $total_sale_amount - $total_credit_note_amount + $total_credit_jv_amount;
	$total_sale_quantity = $total_sale_quantity - $total_credit_note_quantity + $total_credit_jv_quantity;
	$total_sale_avg_rate = $total_sale_amount / $total_sale_quantity;
	
	$net_quantity = 0;
	
	$net_quantity = $opening_balance_quantity + $total_purchase_quantity - $total_sale_quantity;
	
	if($total_purchase_quantity==0 && $net_quantity<0)
	$avg_rate = $total_sale_avg_rate;
	
	$net_amount = $net_quantity * $avg_rate;

	return array($net_amount,$net_quantity,$avg_rate);
	}
}

function getPurchaseAvgRateFifoUpToDate($id,$to,$model=false,$total_purchase=false,$total_sale=false,$godown_id=false) // $total_purchase and $total_debit_note are arrays
{	
	if(!checkForNumeric($godown_id))
	$godown_id=NULL;
	
	$item = getInventoryItemById($id);
	
	$our_company_id = $item['our_company_id'];
	
	$books_starting_date= getBooksStartingDateForOC($our_company_id);

	if(date('m',strtotime($books_starting_date))>3)
	$current_financial_year = date('Y',strtotime($books_starting_date));
	else
	$current_financial_year = date('Y',strtotime($books_starting_date))-1;
	
	if($total_purchase==false)
	$total_purchase = getTotalPurchaseForItemIdUptoDate($id,$to,$model,$godown_id);
	$total_debit_note = getTotalDebitNoteForItemIdUptoDate($id,$to,$model,$godown_id);
	$total_debit_jv = getTotalDebitJvForItemIdUptoDate($id,$to,$model,$godown_id);
	
	if(!$model)
	{
	$item_opening_balance = getOpeningBalanceForItem($id);
	$opening_quantity = $item_opening_balance[1];
	
	}
	else
	{
		
	$model_opening_balance=GetOpeningBalanceAndQuantityForModel($id);	
	$opening_quantity = $model_opening_balance[0];
	$total_opening_balance = $model_opening_balance[1];
	$avg_opening_rate = $model_opening_balance[2];
	
	
	}
	if($total_purchase==0 && $total_debit_note==0 && $opening_quantity==0)
	{
	return 0;
	}
	else if(is_array($total_purchase) || is_array($total_debit_note) || is_array($total_debit_jv))
	{
		if(is_array($total_purchase))
		 {	
		$total_purchase_amount = $total_purchase['total_amount'];
		$total_purchase_quantity = $total_purchase['quantity'];
		$total_purchase_avg_rate = $total_purchase['avg_rate'];
		 }
		 else
		 {
		$total_purchase_amount = 0;
		$total_purchase_quantity = 0;
		$total_purchase_avg_rate = 0;
		 }

		if(is_array($total_debit_note))
		 {	
		$total_debit_note_amount = $total_debit_note['total_amount'];
		$total_debit_note_quantity = $total_debit_note['quantity'];
		$total_debit_note_avg_rate = $total_debit_note['avg_rate'];
		 }
		 else
		 {
		$total_debit_note_amount = 0;
		$total_debit_note_quantity = 0;
		$total_debit_note_avg_rate = 0;
		 }

		if(is_array($total_debit_jv))
		 {	
		$total_debit_jv_amount = $total_debit_jv['total_amount'];
		$total_debit_jv_quantity = $total_debit_jv['quantity'];
		$total_debit_jv_avg_rate = $total_debit_jv['avg_rate'];
		 }
		 else
		 {
		$total_debit_jv_amount = 0;
		$total_debit_jv_quantity = 0;
		$total_debit_jv_avg_rate = 0;
		 }
		
	}
	else 
	{
		$total_purchase_amount = 0;
		$total_purchase_quantity = 0;
		$total_purchase_avg_rate = 0;
		$total_debit_note_amount = 0;
		$total_debit_note_quantity = 0;
		$total_debit_note_avg_rate = 0;
		$total_debit_jv_amount = 0;
		$total_debit_jv_quantity = 0;
		$total_debit_jv_avg_rate = 0;
	}
	if(!$total_sale)
	$total_sale = getTotalSaleForItemIdUptoDate($id,$to,$model,$godown_id);
	$total_credit_note = getTotalCreditNoteForItemIdUptoDate($id,$to,$model,$godown_id);
	$total_credit_jv = getTotalCreditJvForItemIdUptoDate($id,$to,$model,$godown_id);
	if($total_sale==0)
	{
	$total_sale_amount = 0;
	$total_sale_quantity = 0;
	$total_sale_avg_rate = 0;
	}
	else if(is_array($total_sale))
	{
	$total_sale_amount = $total_sale['total_amount'];
	$total_sale_quantity = $total_sale['quantity'];
	$total_sale_avg_rate = $total_sale['avg_rate'];
	}
	
	if($total_credit_note==0)
	{
	$total_credit_note_amount = 0;
	$total_credit_note_quantity = 0;
	$total_credit_note_avg_rate = 0;
	}
	else if(is_array($total_credit_note))
	{
	$total_credit_note_amount = $total_credit_note['total_amount'];
	$total_credit_note_quantity = $total_credit_note['quantity'];
	$total_credit_note_avg_rate = $total_credit_note['avg_rate'];
	}
	
	if($total_credit_jv==0)
	{
	$total_credit_jv_amount = 0;
	$total_credit_jv_quantity = 0;
	$total_credit_jv_avg_rate = 0;
	}
	else if(is_array($total_credit_jv))
	{
	$total_credit_jv_amount = $total_credit_jv['total_amount'];
	$total_credit_jv_quantity = $total_credit_jv['quantity'];
	$total_credit_jv_avg_rate = $total_credit_jv['avg_rate'];
	}
	
	$difference_quantity = $opening_quantity + $total_purchase_quantity + $total_debit_jv_quantity - $total_sale_quantity - $total_debit_note_quantity + $total_credit_note_quantity - $total_credit_jv_quantity;
	
	
	if($difference_quantity>0 && !$model)
	{
		$sql="SELECT  trans_date, date_added, IF(type<4,@purchase_quantity := @purchase_quantity + quantity,@purchase_quantity) as quantity ,(IF(type<4,@purchase_amount := @purchase_amount + net_amount,@purchase_amount)) as net_amount,IF(type<4 AND (@purchase_amount/@purchase_quantity) IS NOT NULL,@purchase_rate := (@purchase_amount/@purchase_quantity),@purchase_rate) as rate , @closing_quantity := @closing_quantity + quantity as closing_quantity,
		(@row_index := @row_index + 1) as row_index, @current_year
  FROM
(
   SELECT purchase_item_id as id, 0 AS type, edms_ac_purchase_item.quantity AS quantity , edms_ac_purchase_item.net_amount AS  net_amount , edms_ac_purchase_item.net_amount/edms_ac_purchase_item.quantity AS  rate , edms_ac_purchase.trans_date AS trans_date ,edms_ac_purchase.date_added as  date_added 
    FROM  edms_ac_purchase_item  INNER JOIN edms_ac_purchase ON edms_ac_purchase_item.purchase_id = edms_ac_purchase.purchase_id 	     WHERE item_id = $id AND trans_date <= '$to'
	UNION ALL
	SELECT debit_note_item_id as id, 1 AS type, -edms_ac_debit_note_item.quantity AS quantity , -edms_ac_debit_note_item.net_amount AS  net_amount , edms_ac_debit_note_item.net_amount/edms_ac_debit_note_item.quantity AS  rate , edms_ac_debit_note.trans_date AS trans_date ,edms_ac_debit_note.date_added as  date_added 
    FROM  edms_ac_debit_note_item INNER JOIN edms_ac_debit_note ON edms_ac_debit_note_item.debit_note_id = edms_ac_debit_note.debit_note_id  WHERE item_id = $id AND trans_date <= '$to'
	UNION ALL
	SELECT inventory_item_jv_id as id, 3 AS type, edms_inventory_item_jv.quantity AS quantity , edms_inventory_item_jv.amount AS  net_amount , edms_inventory_item_jv.amount/edms_inventory_item_jv.quantity AS  rate , edms_inventory_jv.trans_date AS trans_date ,edms_inventory_jv.date_added as  date_added 
    FROM  edms_inventory_item_jv INNER JOIN edms_inventory_jv ON edms_inventory_item_jv.inventory_jv_id = edms_inventory_jv.inventory_jv_id  WHERE item_id = $id AND trans_date <= '$to' AND edms_inventory_item_jv.type=0
	UNION ALL
	SELECT item_id as id, 2 AS type, opening_quantity as quantity, opening_quantity*opening_rate AS net_amount, opening_rate as rate,
	'1970-01-01' as trans_date, date_added
	FROM edms_inventory_item WHERE item_id = $id
	UNION SELECT sales_item_id as id, 4 AS type, -edms_ac_sales_item.quantity AS quantity , -edms_ac_sales_item.net_amount AS  net_amount , edms_ac_sales_item.net_amount/edms_ac_sales_item.quantity AS  rate , edms_ac_sales.trans_date AS trans_date ,edms_ac_sales.date_added as  date_added 
    FROM  edms_ac_sales_item  INNER JOIN edms_ac_sales ON edms_ac_sales_item.sales_id = edms_ac_sales.sales_id AND edms_ac_sales_item.sales_id IS NOT NULL WHERE edms_ac_sales_item.sales_id IS NOT NULL AND item_id = $id AND edms_ac_sales.trans_date <= '$to'
	
	 UNION SELECT sales_item_id as id, 5 AS type, -edms_ac_sales_item.quantity AS quantity , -edms_ac_sales_item.net_amount AS  net_amount , edms_ac_sales_item.net_amount/edms_ac_sales_item.quantity AS  rate , edms_ac_delivery_challan.trans_date AS trans_date ,edms_ac_delivery_challan.date_added as  date_added 
    FROM  edms_ac_sales_item  INNER JOIN edms_ac_delivery_challan ON edms_ac_sales_item.delivery_challan_id = edms_ac_delivery_challan.delivery_challan_id AND edms_ac_sales_item.sales_id IS NULL WHERE edms_ac_sales_item.sales_id IS NULL AND item_id = $id AND trans_date <= '$to'
	
	UNION ALL
	SELECT credit_note_item_id as id, 6 AS type, edms_ac_credit_note_item.quantity AS quantity , edms_ac_credit_note_item.net_amount AS  net_amount , edms_ac_credit_note_item.net_amount/edms_ac_credit_note_item.quantity AS  rate , edms_ac_credit_note.trans_date AS trans_date ,edms_ac_credit_note.date_added as  date_added 
    FROM  edms_ac_credit_note_item INNER JOIN edms_ac_credit_note ON edms_ac_credit_note_item.credit_note_id = edms_ac_credit_note.credit_note_id  WHERE item_id = $id AND trans_date <= '$to'
	
	UNION ALL
	SELECT inventory_item_jv_id as id, 7 AS type, -edms_inventory_item_jv.quantity AS quantity , -edms_inventory_item_jv.amount AS  net_amount , edms_inventory_item_jv.amount/edms_inventory_item_jv.quantity AS  rate , edms_inventory_jv.trans_date AS trans_date ,edms_inventory_jv.date_added as  date_added 
    FROM  edms_inventory_item_jv INNER JOIN edms_inventory_jv ON edms_inventory_item_jv.inventory_jv_id = edms_inventory_jv.inventory_jv_id  WHERE item_id = $id AND trans_date <= '$to' AND edms_inventory_item_jv.type=1

	 ORDER BY trans_date ASC, date_added ASC
) q CROSS JOIN (SELECT @purchase_quantity := 0,@purchase_amount := 0,@purchase_rate := 0,@current_year:=$current_financial_year,@closing_quantity:=0,@row_index:=0) i 
WHERE IF(MONTH(trans_date)>3 && @current_year < YEAR(trans_date),
		CASE
			WHEN (@purchase_quantity := @closing_quantity) IS NULL THEN NULL
			WHEN (@purchase_amount := (@closing_quantity * @purchase_rate)) IS NULL THEN NULL
			WHEN (@current_year := YEAR(trans_date)) IS NULL THEN NULL
			ELSE  @current_year
		END,		
		0
		) IS NOT NULL
		ORDER BY row_index DESC LIMIT 0,1
";
	
 	$result = dbQuery($sql);
	$resultArray = dbResultToArray($result);
	
    if(dbNumRows($result)>0)
	{
		return $resultArray[0]['rate'];
	}
	}
	else if($difference_quantity>0 && $model && checkForNumeric($id))
	{
		$sql="SELECT edms_vehicle.vehicle_id, vehicle_engine_no, vehicle_chasis_no, is_purchased, basic_price, trans_date
  FROM edms_vehicle INNER JOIN edms_ac_purchase_vehicle ON edms_vehicle.vehicle_id = edms_ac_purchase_vehicle.vehicle_id INNER JOIN edms_ac_purchase ON edms_ac_purchase.purchase_id = edms_ac_purchase_vehicle.purchase_id WHERE is_purchased=1 AND model_id = $id AND trans_date<='$to' ";
		

 	$result = dbQuery($sql);
	$resultArray = dbResultToArray($result);
    if(dbNumRows($result)>0)
	{
		$total_purchase_net_amount=$total_opening_balance;
		if(!checkForNumeric($total_purchase_net_amount))
		$total_purchase_net_amount=0;
		
		$total_quantity = $opening_quantity;
		if(!checkForNumeric($total_quantity))
		$total_quantity=0;
		
		$i=1;
		foreach($resultArray as $purchase)
		{
			$purchase_net_amount= $purchase['basic_price'];
			$total_purchase_net_amount = $total_purchase_net_amount + $purchase_net_amount;
			$total_quantity = $total_quantity + 1;
			
		}
		return round($total_purchase_net_amount/$total_quantity,2);
	}
	else
	{
		return round($total_opening_balance/$opening_quantity,2);
		
	}
	}
	else
	return 0;
	
}

function getRemainingQuanityForItemForDate($id,$godown_id,$model=false,$date=false) // return opening balance on date for id, id should start with L or C
{
	if(checkForNumeric($id,$godown_id))
	{
	if(!$date)
	$date=date('d/m/Y',strtotime(getTodaysDate()));
		
	$to=$date; // return d-m-y
	
	
	$opening_balance_array=getOpeningBalanceForItem($id);	
	
	$opening_balance_item=$opening_balance_array[0];
	$opening_balance_quantity=$opening_balance_array[1];
	$opening_balance_rate=$opening_balance_array[2];
	$opening_balance_godown_id=$opening_balance_array[3];
	
	
	
    $total_purchase=getTotalPurchaseForItemIdUptoDate($id,$to,$model);
	$total_debit_jv = getTotalDebitJvForItemIdUptoDate($id,$to,$model);
	$total_debit_note = getTotalDebitNoteForItemIdUptoDate($id,$to,$model);
	
	
	
	if($total_purchase==0)
	{
	$total_purchase_amount = 0;
	$total_purchase_quantity =0;
	$total_purchase_avg_rate =0;
	}
	else if(is_array($total_purchase))
	{
	$total_purchase_amount = $total_purchase['total_amount'];
	$total_purchase_quantity = $total_purchase['quantity'];
	$total_purchase_avg_rate = $total_purchase['avg_rate'];
	}
	if($total_debit_jv==0)
	{
	$total_debit_jv_amount = 0;
	$total_debit_jv_quantity =0;
	$total_debit_jv_avg_rate =0;
	}
	else if(is_array($total_debit_jv))
	{
	
	$total_debit_jv_amount = $total_debit_jv['total_amount'];
	$total_debit_jv_quantity = $total_debit_jv['quantity'];

	$total_debit_jv_avg_rate = $total_debit_jv['avg_rate'];
	}
	
	if($total_debit_note==0)
	{
	$total_debit_note_amount = 0;
	$total_debit_note_quantity =0;
	$total_debit_note_avg_rate =0;
	}
	else if(is_array($total_debit_note))
	{
	$total_debit_note_amount = $total_debit_note['total_amount'];
	$total_debit_note_quantity = $total_debit_note['quantity'];
	$total_debit_note_avg_rate = $total_debit_note['avg_rate'];
	}
	
	$total_credit_note = getTotalCreditNoteForItemIdUptoDate($id,$to,$model);
	
	if($total_credit_note==0)
	{
	$total_credit_note_amount = 0;
	$total_credit_note_quantity =0;
	$total_credit_note_avg_rate =0;
	}
	else if(is_array($total_credit_note))
	{
	$total_credit_note_amount = $total_credit_note['total_amount'];
	$total_credit_note_quantity = $total_credit_note['quantity'];
	$total_credit_note_avg_rate = $total_credit_note['avg_rate'];
	}
	
	if(($opening_balance_quantity + $total_purchase_quantity + $total_debit_jv_quantity + $total_credit_note_quantity)>0)
	{
	$avg_rate = ($total_purchase_amount + $opening_balance_item + $total_debit_jv_amount + $total_credit_note_amount) / ($opening_balance_quantity + $total_purchase_quantity + $total_debit_jv_quantity + $total_credit_note_quantity);

	$avg_rate = round($avg_rate,2);
	}
	
	else
	$avg_rate=0;
	
	$total_sale = getTotalSaleForItemIdUptoDate($id,$to,$model);
	$total_credit_jv = getTotalCreditJvForItemIdUptoDate($id,$to,$model);
	
	
	if($total_sale==0)
	{
	$total_sale_amount = 0;
	$total_sale_quantity = 0;
	$total_sale_avg_rate = 0;
		
	}
	else if(is_array($total_sale))
	{
	$total_sale_amount = $total_sale['total_amount'];
	$total_sale_quantity = $total_sale['quantity'];
	$total_sale_avg_rate = $total_sale['avg_rate'];
	}
	
	if($total_credit_jv==0)
	{
	$total_credit_jv_amount = 0;
	$total_credit_jv_quantity =0;
	$total_credit_jv_avg_rate =0;
	}
	else if(is_array($total_credit_jv))
	{
	$total_credit_jv_amount = $total_credit_jv['total_amount'];
	$total_credit_jv_quantity = $total_credit_jv['quantity'];
	$total_credit_jv_avg_rate = $total_credit_jv['avg_rate'];
	}
	
	if(($total_sale_quantity + $total_credit_jv_quantity + $total_debit_note_quantity)>0)
	{
	$sale_avg_rate = ($total_sale_amount +  $total_credit_jv_amount + $total_debit_note_amount) / ($total_sale_quantity + $total_credit_jv_quantity + $total_debit_note_quantity);

	$sale_avg_rate = round($avg_rate,2);
	}
	
	$net_quantity = 0;
	if($opening_balance_godown_id==$godown_id)
	$net_quantity = $opening_balance_quantity + $total_purchase_quantity + $total_debit_jv_quantity - $total_debit_note_quantity - $total_sale_quantity - $total_credit_jv_quantity + $total_credit_note_quantity;
	else
	$net_quantity = $opening_balance_quantity + $total_purchase_quantity + $total_debit_jv_quantity - $total_debit_note_quantity - $total_sale_quantity - $total_credit_jv_quantity + $total_credit_note_quantity;
	
	if($total_purchase_quantity==0 && $net_quantity<0)
	$avg_rate = $sale_avg_rate;

	$net_amount = $net_quantity * $avg_rate;
	return $net_quantity;
	}
}



function getFirstPageClosingStockSheet($to)
{
	$to=getNextDate($to); // return Y-m-d	
			
	$items=listInventoryItems();
	$models = listVehicleModels();
	
    $return_array=array();
	$i=0;
	$pl_sheet_balance=0;
	foreach($items as $ite)
	{
		
		$item_id=$ite['item_id'];
		
		$item = getInventoryItemById($item_id);
		
		$closing_balance_item_array = getOpeningBalanceForItemForDate($item_id,$to);	
			
		$opening_balance_item=$closing_balance_item_array[0];
		$opening_balance_quantity=$closing_balance_item_array[1];
		$opening_balance_rate=$closing_balance_item_array[2];
	
		$return_array[$i]['item_id']=$item_id;
		$return_array[$i]['item_type']=0; // item
		$return_array[$i]['item_name']=$item['item_name'];
		$return_array[$i]['closing_balance']=$opening_balance_item;
		$return_array[$i]['closing_quantity']=$opening_balance_quantity;
		$return_array[$i]['closing_rate']=$opening_balance_rate;
		$i++;
	}
	
	foreach($models as $model)
	{
		
		$item_id=$model['model_id'];
		
		$item = getVehicleModelById($item_id);
		
		$closing_balance_item_array = getOpeningBalanceForItemForDate($item_id,$to,true);	
			
		$opening_balance_item=$closing_balance_item_array[0];
		$opening_balance_quantity=$closing_balance_item_array[1];
		$opening_balance_rate=$closing_balance_item_array[2];
	
		$return_array[$i]['item_id']=$item_id;
		$return_array[$i]['item_type']=1; // vehicle
		$return_array[$i]['item_name']=$item['model_name'];
		$return_array[$i]['closing_balance']=$opening_balance_item;
		$return_array[$i]['closing_quantity']=$opening_balance_quantity;
		$return_array[$i]['closing_rate']=$opening_balance_rate;
		$i++;
	}
	
	return $return_array;
}

function getClosingStockForDate($to)
{
	$to=getNextDate($to); // return Y-m-d	
	
	$closing_balance=0;		
	$items=listInventoryItemsForStockCalculation();
	
	$models = listVehicleModels();
	
    $return_array=array();
	$i=0;
	$pl_sheet_balance=0;
	foreach($items as $ite)
	{
		
		$item_id=$ite['item_id'];

		$closing_balance_item_array = getOpeningBalanceForItemForDate($item_id,$to);	
		
		$opening_balance_item=$closing_balance_item_array[0];
		$opening_balance_quantity=$closing_balance_item_array[1];
		$opening_balance_rate=$closing_balance_item_array[2];
		
		$closing_balance = $closing_balance + $opening_balance_item;
		$return_array[$i]['item_id']=$item_id;
		$return_array[$i]['item_name']=$ite['item_name'];
		$return_array[$i]['closing_balance']=$opening_balance_item;
		$return_array[$i]['closing_quantity']=$opening_balance_quantity;
		$return_array[$i]['closing_rate']=$opening_balance_rate;

		$i++; 
	}
		
	foreach($models as $model)
	{
		
		$item_id=$model['model_id'];
		
		$item = getVehicleModelById($item_id);
	
		$closing_balance_item_array = getOpeningBalanceForItemForDate($item_id,$to,true);	
		
		$opening_balance_item=$closing_balance_item_array[0];
		$opening_balance_quantity=$closing_balance_item_array[1];
		$opening_balance_rate=$closing_balance_item_array[2];
	
	   $closing_balance = $closing_balance + $opening_balance_item;
		$return_array[$i]['item_id']=$item_id;
		$return_array[$i]['item_type']=1; // vehicle
		$return_array[$i]['item_name']=$item['model_name'];
		$return_array[$i]['closing_balance']=$opening_balance_item;
		$return_array[$i]['closing_quantity']=$opening_balance_quantity;
		$return_array[$i]['closing_rate']=$opening_balance_rate;
		
		$i++;
	} 
	
	return $closing_balance;
}


function getClosingStockJson($start=NULL,$no_of_records=NULL,$search_sku=NULL)
{
	$search_sku = clean_data($search_sku);
	$sql="SELECT edms_inventory_item.item_id, item_name, item_code, mfg_item_code,opening_quantity + (SELECT SUM(IF(type=0,quantity,-quantity)) FROM edms_inventory_item_jv WHERE edms_inventory_item_jv.item_id =  edms_inventory_item.item_id GROUP BY edms_inventory_item_jv.item_id ) as closing_stock FROM edms_inventory_item ";
	if(validateForNull($search_sku))
	$sql=$sql." item_code = '%$search_sku%'";
	$sql=$sql." ORDER BY closing_stock DESC ";
	if(checkForNumeric($start,$no_of_records))
	$sql=$sql." LIMIT ".$start." ,".$no_of_records." ";
	
	$result=dbQuery($sql);
	$result_array = dbResultToArray($result,MYSQL_NUM);
	return $result_array;
}


function getClosingStockForPeriod($from,$to)
{
	$to=getNextDate($to); // return Y-m-d	
	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
	$closing_balance=0;		
	$items=listInventoryItemsForStockCalculation();
	
	$models = listVehicleModels();
	
    $return_array=array();
	$i=0;
	$pl_sheet_balance=0;
	foreach($items as $ite)
	{
		
		$item_id=$ite['item_id'];

		$closing_balance_item_array = getOpeningBalanceForItemForDate($item_id,$to);	
		$opening_balance_item_array = getOpeningBalanceForItemForDate($item_id,$from);
		$closing_balance_item=$closing_balance_item_array[0];
		$closing_balance_quantity=$closing_balance_item_array[1];
		$closing_balance_rate=$closing_balance_item_array[2];
		
		$opening_balance_item=$closing_balance_item_array[0];
		$opening_balance_quantity=$closing_balance_item_array[1];
		$opening_balance_rate=$closing_balance_item_array[2];
		
		$closing_balance = $closing_balance + $opening_balance_item;
		$return_array[$i]['item_id']=$item_id;
		$return_array[$i]['item_name']=$ite['item_name'];
		$return_array[$i]['closing_balance']=$closing_balance_item;
		$return_array[$i]['closing_quantity']=$closing_balance_quantity;
		$return_array[$i]['closing_rate']=$closing_balance_rate;
		
		$return_array[$i]['opening_balance']=$opening_balance_item;
		$return_array[$i]['opening_quantity']=$opening_balance_quantity;
		$return_array[$i]['opening_rate']=$opening_balance_rate;

		$i++; 
	}
		
	foreach($models as $model)
	{
		
		$item_id=$model['model_id'];
		
		$item = getVehicleModelById($item_id);
	
		$closing_balance_item_array = getOpeningBalanceForItemForDate($item_id,$to,true);	
		
		$opening_balance_item=$closing_balance_item_array[0];
		$opening_balance_quantity=$closing_balance_item_array[1];
		$opening_balance_rate=$closing_balance_item_array[2];
	
	   $closing_balance = $closing_balance + $opening_balance_item;
		$return_array[$i]['item_id']=$item_id;
		$return_array[$i]['item_type']=1; // vehicle
		$return_array[$i]['item_name']=$item['model_name'];
		$return_array[$i]['closing_balance']=$opening_balance_item;
		$return_array[$i]['closing_quantity']=$opening_balance_quantity;
		$return_array[$i]['closing_rate']=$opening_balance_rate;
		
		$i++;
	} 
	
	return $closing_balance;
}


function getClosingStockForDateItemwise($to)
{
	$to=getNextDate($to); // return Y-m-d	
	
	$closing_balance=0;		
	$items=listInventoryItemsForStockCalculation();
	
	$models = listVehicleModels();
	
    $return_array=array();
	$i=0;
	$pl_sheet_balance=0;
	foreach($items as $ite)
	{
		
		$item_id=$ite['item_id'];
		
		
		$closing_balance_item_array = getOpeningBalanceForItemForDate($item_id,$to);	
		
		$opening_balance_item=$closing_balance_item_array[0];
		$opening_balance_quantity=$closing_balance_item_array[1];
		$opening_balance_rate=$closing_balance_item_array[2];
		
		$closing_balance = $closing_balance + $opening_balance_item;
		$return_array[$i]['item_id']=$item_id;
		$return_array[$i]['item_name']=$ite['item_name'];
		$return_array[$i]['item_code']=$ite['item_code'];
		$return_array[$i]['mfg_item_code']=$ite['mfg_item_code'];
		$return_array[$i]['closing_balance']=$opening_balance_item;
		$return_array[$i]['closing_quantity']=$opening_balance_quantity;
		$return_array[$i]['closing_rate']=$opening_balance_rate;
		$i++; 
	}
		
	foreach($models as $model)
	{
		
		$item_id=$model['model_id'];
		
		$item = getVehicleModelById($item_id);
	
		$closing_balance_item_array = getOpeningBalanceForItemForDate($item_id,$to,true);	
		
		$opening_balance_item=$closing_balance_item_array[0];
		$opening_balance_quantity=$closing_balance_item_array[1];
		$opening_balance_rate=$closing_balance_item_array[2];
	
	   $closing_balance = $closing_balance + $opening_balance_item;
		$return_array[$i]['item_id']=$item_id;
		$return_array[$i]['item_type']=1; // vehicle
		$return_array[$i]['item_name']=$item['model_name'];
		$return_array[$i]['closing_balance']=$opening_balance_item;
		$return_array[$i]['closing_quantity']=$opening_balance_quantity;
		$return_array[$i]['closing_rate']=$opening_balance_rate;
		
		$i++;
	} 
	
	return $return_array;
}

function getTotalDebitCreditQtyForItemIdForPeriod($item_id,$from,$to,$model=NULL)
{
	
	$godown_id=NULL;
	if(isset($from) && validateForNull($from))
    {
	$from = str_replace('/', '-', $from);
	$from = date('Y-m-d',strtotime($from));
	}
	
	if(isset($to) && validateForNull($to))
	{
	$to = str_replace('/', '-', $to);
	$to=date('Y-m-d',strtotime($to));
	}
    $current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	$sql="SELECT edms_ac_purchase_item.purchase_item_id as id,SUM(net_amount) as total_amount, SUM(quantity) as quantity, SUM(net_amount)/SUM(quantity) as avg_rate
			  FROM edms_ac_purchase_item, edms_ac_purchase WHERE edms_ac_purchase.oc_id = $oc_id AND edms_ac_purchase_item.purchase_id = edms_ac_purchase.purchase_id AND ";
			  
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(validateForNull($godown_id) && checkForNumeric($godown_id))
	$sql=$sql." godown_id = $godown_id AND ";	  
	if(!$model)	  
	$sql=$sql." item_id=$item_id GROUP BY item_id";
	else if($model)
	$sql=$sql." model_id=$item_id GROUP BY model_id";
	
	$sql=$sql." UNION ALL SELECT edms_ac_credit_note_item.credit_note_item_id as id,SUM(net_amount) as total_amount, SUM(quantity) as quantity, SUM(net_amount)/SUM(quantity) as avg_rate
			  FROM edms_ac_credit_note_item, edms_ac_credit_note WHERE edms_ac_credit_note.oc_id = $oc_id AND edms_ac_credit_note_item.credit_note_id = edms_ac_credit_note.credit_note_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(validateForNull($godown_id) && checkForNumeric($godown_id))
	$sql=$sql." godown_id = $godown_id AND ";		  
	if(!$model)	  
	$sql=$sql." item_id=$item_id GROUP BY item_id";
	else if($model)
	$sql=$sql." model_id=$item_id GROUP BY model_id";
	
	if(!$model)
	{
		$sql=$sql." UNION ALL SELECT edms_inventory_item_jv.item_id as id,SUM(amount) as total_amount, SUM(quantity) as quantity, SUM(amount)/SUM(quantity) as avg_rate
			  FROM edms_inventory_jv, edms_inventory_item_jv WHERE edms_inventory_jv.oc_id = $oc_id AND  type = 0 AND  edms_inventory_item_jv.inventory_jv_id = edms_inventory_jv.inventory_jv_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(validateForNull($godown_id) && checkForNumeric($godown_id))
	$sql=$sql." godown_id = $godown_id AND ";	  
	if(!$model)	  
	$sql=$sql." item_id=$item_id GROUP BY item_id";
	else if($model)
	$sql=$sql." model_id=$item_id GROUP BY model_id";
		
	}
	$total_debit_qty=0;
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	{
		foreach($resultArray as $re)
		{
			$quantity = $re['quantity'];
			$total_debit_qty =  $total_debit_qty + $quantity;
		}
	}
	
	
	$sql="SELECT edms_ac_sales_item.sales_item_id as id,SUM(net_amount) as total_amount, SUM(quantity) as quantity, SUM(net_amount)/SUM(quantity) as avg_rate
			  FROM edms_ac_sales_item LEFT JOIN edms_ac_sales ON edms_ac_sales_item.sales_id = edms_ac_sales.sales_id AND edms_ac_sales_item.delivery_challan_id IS NULL AND edms_ac_sales.oc_id = $oc_id LEFT JOIN edms_ac_delivery_challan ON edms_ac_sales_item.delivery_challan_id = edms_ac_delivery_challan.delivery_challan_id AND edms_ac_sales_item.delivery_challan_id IS NOT NULL AND edms_ac_delivery_challan.oc_id = $oc_id WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql." IF(edms_ac_delivery_challan.delivery_challan_id IS NOT NULL,edms_ac_delivery_challan.trans_date,edms_ac_sales.trans_date)>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." IF(edms_ac_delivery_challan.delivery_challan_id IS NOT NULL,edms_ac_delivery_challan.trans_date,edms_ac_sales.trans_date)<='$to'
		  AND ";
	if(validateForNull($godown_id) && checkForNumeric($godown_id))
	$sql=$sql." godown_id = $godown_id AND ";	  
	if(!$model)	  
	$sql=$sql." item_id=$item_id GROUP BY item_id";
	else if($model)
	$sql=$sql." model_id=$item_id GROUP BY model_id";
	
	$sql=$sql." UNION ALL SELECT edms_ac_debit_note_item.debit_note_item_id as id,SUM(net_amount) as total_amount, SUM(quantity) as quantity, SUM(net_amount)/SUM(quantity) as avg_rate
			  FROM edms_ac_debit_note_item, edms_ac_debit_note WHERE edms_ac_debit_note.oc_id = $oc_id AND edms_ac_debit_note_item.debit_note_id = edms_ac_debit_note.debit_note_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(validateForNull($godown_id) && checkForNumeric($godown_id))
	$sql=$sql." godown_id = $godown_id AND ";		  
	if(!$model)	  
	$sql=$sql." item_id=$item_id GROUP BY item_id";
	else if($model)
	$sql=$sql." model_id=$item_id GROUP BY model_id";
	
	if(!$model)
	{
		$sql=$sql." UNION ALL SELECT edms_inventory_item_jv.item_id as id,SUM(amount) as total_amount, SUM(quantity) as quantity, SUM(amount)/SUM(quantity) as avg_rate
			  FROM edms_inventory_jv, edms_inventory_item_jv WHERE edms_inventory_jv.oc_id = $oc_id AND type = 1 AND  edms_inventory_item_jv.inventory_jv_id = edms_inventory_jv.inventory_jv_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(validateForNull($godown_id) && checkForNumeric($godown_id))
	$sql=$sql." godown_id = $godown_id AND ";	  
	if(!$model)	  
	$sql=$sql." item_id=$item_id GROUP BY item_id";
	else if($model)
	$sql=$sql." model_id=$item_id GROUP BY model_id";
		
	}
	$total_credit_qty=0;
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	{
		foreach($resultArray as $re)
		{
			$quantity = $re['quantity'];
			$total_credit_qty =  $total_credit_qty + $quantity;
		}
	} 	
	
	return array($total_debit_qty,$total_credit_qty);
}

function getTotalDebitCreditQtyForItemIdForPeriodMonthWise($item_id,$from,$to,$model=NULL)
{
	
	$godown_id=NULL;
	if(isset($from) && validateForNull($from))
    {
	$from = str_replace('/', '-', $from);
	$from = date('Y-m-d',strtotime($from));
	}
	
	if(isset($to) && validateForNull($to))
	{
	$to = str_replace('/', '-', $to);
	$to=date('Y-m-d',strtotime($to));
	}
    $current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	$sql="SELECT edms_ac_purchase_item.purchase_item_id as id,SUM(net_amount) as total_amount, SUM(quantity) as quantity, SUM(net_amount)/SUM(quantity) as avg_rate, MONTHNAME(trans_date)
			  FROM edms_ac_purchase_item, edms_ac_purchase WHERE edms_ac_purchase.oc_id = $oc_id AND edms_ac_purchase_item.purchase_id = edms_ac_purchase.purchase_id AND ";
			
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(validateForNull($godown_id) && checkForNumeric($godown_id))
	$sql=$sql." godown_id = $godown_id AND ";	  
	if(!$model)	  
	$sql=$sql." item_id=$item_id GROUP BY MONTHNAME(trans_date)";
	else if($model)
	$sql=$sql." model_id=$item_id GROUP BY model_id";
	
	$sql=$sql." UNION ALL SELECT edms_ac_credit_note_item.credit_note_item_id as id,SUM(net_amount) as total_amount, SUM(quantity) as quantity, SUM(net_amount)/SUM(quantity) as avg_rate, MONTHNAME(trans_date)
			  FROM edms_ac_credit_note_item, edms_ac_credit_note WHERE edms_ac_credit_note.oc_id = $oc_id AND edms_ac_credit_note_item.credit_note_id = edms_ac_credit_note.credit_note_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(validateForNull($godown_id) && checkForNumeric($godown_id))
	$sql=$sql." godown_id = $godown_id AND ";		  
	if(!$model)	  
	$sql=$sql." item_id=$item_id GROUP BY item_id GROUP BY MONTHNAME(trans_date)";
	else if($model)
	$sql=$sql." model_id=$item_id GROUP BY model_id";
	
	if(!$model)
	{
		$sql=$sql." UNION ALL SELECT edms_inventory_item_jv.item_id as id,SUM(amount) as total_amount, SUM(quantity) as quantity, SUM(amount)/SUM(quantity) as avg_rate,MONTHNAME(trans_date)
			  FROM edms_inventory_jv, edms_inventory_item_jv WHERE edms_inventory_jv.oc_id = $oc_id AND  type = 0 AND  edms_inventory_item_jv.inventory_jv_id = edms_inventory_jv.inventory_jv_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(validateForNull($godown_id) && checkForNumeric($godown_id))
	$sql=$sql." godown_id = $godown_id AND ";	  
	if(!$model)	  
	$sql=$sql." item_id=$item_id GROUP BY item_id GROUP BY MONTHNAME(trans_date)";
	else if($model)
	$sql=$sql." model_id=$item_id GROUP BY model_id";
		
	}
	$total_debit_qty=0;
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	{
		foreach($resultArray as $re)
		{
			$quantity = $re['quantity'];
			$total_debit_qty =  $total_debit_qty + $quantity;
		}
	}
	
	
	$sql="SELECT edms_ac_sales_item.sales_item_id as id,SUM(net_amount) as total_amount, SUM(quantity) as quantity, SUM(net_amount)/SUM(quantity) as avg_rate , IF(edms_ac_delivery_challan.delivery_challan_id IS NOT NULL,MONTHNAME(edms_ac_delivery_challan.trans_date),MONTHNAME(edms_ac_sales.trans_date)) as trans_date_month
			  FROM edms_ac_sales_item LEFT JOIN edms_ac_sales ON edms_ac_sales_item.sales_id = edms_ac_sales.sales_id AND edms_ac_sales_item.delivery_challan_id IS NULL AND edms_ac_sales.oc_id = $oc_id LEFT JOIN edms_ac_delivery_challan ON edms_ac_sales_item.delivery_challan_id = edms_ac_delivery_challan.delivery_challan_id AND edms_ac_sales_item.delivery_challan_id IS NOT NULL AND edms_ac_delivery_challan.oc_id = $oc_id WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql." IF(edms_ac_delivery_challan.delivery_challan_id IS NOT NULL,edms_ac_delivery_challan.trans_date,edms_ac_sales.trans_date)>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." IF(edms_ac_delivery_challan.delivery_challan_id IS NOT NULL,edms_ac_delivery_challan.trans_date,edms_ac_sales.trans_date)<='$to'
		  AND ";
	if(validateForNull($godown_id) && checkForNumeric($godown_id))
	$sql=$sql." godown_id = $godown_id AND ";	  
	if(!$model)	  
	$sql=$sql." item_id=$item_id GROUP BY item_id ";
	else if($model)
	$sql=$sql." model_id=$item_id GROUP BY model_id";
	
	$sql=$sql." UNION ALL SELECT edms_ac_debit_note_item.debit_note_item_id as id,SUM(net_amount) as total_amount, SUM(quantity) as quantity, SUM(net_amount)/SUM(quantity) as avg_rate
			  FROM edms_ac_debit_note_item, edms_ac_debit_note WHERE edms_ac_debit_note.oc_id = $oc_id AND edms_ac_debit_note_item.debit_note_id = edms_ac_debit_note.debit_note_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(validateForNull($godown_id) && checkForNumeric($godown_id))
	$sql=$sql." godown_id = $godown_id AND ";		  
	if(!$model)	  
	$sql=$sql." item_id=$item_id GROUP BY item_id";
	else if($model)
	$sql=$sql." model_id=$item_id GROUP BY model_id";
	
	if(!$model)
	{
		$sql=$sql." UNION ALL SELECT edms_inventory_item_jv.item_id as id,SUM(amount) as total_amount, SUM(quantity) as quantity, SUM(amount)/SUM(quantity) as avg_rate
			  FROM edms_inventory_jv, edms_inventory_item_jv WHERE edms_inventory_jv.oc_id = $oc_id AND type = 1 AND  edms_inventory_item_jv.inventory_jv_id = edms_inventory_jv.inventory_jv_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(validateForNull($godown_id) && checkForNumeric($godown_id))
	$sql=$sql." godown_id = $godown_id AND ";	  
	if(!$model)	  
	$sql=$sql." item_id=$item_id GROUP BY item_id";
	else if($model)
	$sql=$sql." model_id=$item_id GROUP BY model_id";
		
	}
	$total_credit_qty=0;
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	{
		foreach($resultArray as $re)
		{
			$quantity = $re['quantity'];
			$total_credit_qty =  $total_credit_qty + $quantity;
		}
	} 	
	
	return array($total_debit_qty,$total_credit_qty);
}

function getTransactionForItemIDForPeriod($id,$from,$to)
{
	if(checkForNumeric($id) && validateForNull($from,$to))
	{
		
		if(isset($from) && validateForNull($from))
    {
	$from = str_replace('/', '-', $from);
	$from = date('Y-m-d',strtotime($from));
	}
	
	if(isset($to) && validateForNull($to))
	{
	$to = str_replace('/', '-', $to);
	$to=date('Y-m-d',strtotime($to));
	}
		$our_company_id = $_SESSION['edmsAdminSession']['oc_id'];
	
	    $books_starting_date= getBooksStartingDateForOC($our_company_id);
		
		
		
		$sql="SELECT purchase_item_id as id, 1 AS type, SUM(edms_ac_purchase_item.quantity) AS quantity , SUM(edms_ac_purchase_item.net_amount) AS  net_amount , SUM(edms_ac_purchase_item.net_amount)/SUM(edms_ac_purchase_item.quantity) AS  rate , edms_ac_purchase.trans_date AS trans_date ,edms_ac_purchase.date_added as  date_added , edms_ac_purchase.purchase_id as trans_id
    FROM  edms_ac_purchase_item  INNER JOIN edms_ac_purchase ON edms_ac_purchase_item.purchase_id = edms_ac_purchase.purchase_id 	     WHERE item_id = $id AND trans_date <= '$to' AND trans_date >= '$from' GROUP BY edms_ac_purchase.purchase_id
	UNION ALL
	SELECT debit_note_item_id as id, 6 AS type, -SUM(edms_ac_debit_note_item.quantity) AS quantity , -SUM(edms_ac_debit_note_item.net_amount) AS  net_amount , SUM(edms_ac_debit_note_item.net_amount)/SUM(edms_ac_debit_note_item.quantity) AS  rate , edms_ac_debit_note.trans_date AS trans_date ,edms_ac_debit_note.date_added as  date_added  , edms_ac_debit_note.debit_note_id as trans_id
    FROM  edms_ac_debit_note_item INNER JOIN edms_ac_debit_note ON edms_ac_debit_note_item.debit_note_id = edms_ac_debit_note.debit_note_id  WHERE item_id = $id AND trans_date <= '$to' AND trans_date >= '$from' GROUP BY edms_ac_debit_note.debit_note_id
	UNION ALL
	SELECT inventory_item_jv_id as id, 5 AS type, SUM(edms_inventory_item_jv.quantity) AS quantity , SUM(edms_inventory_item_jv.amount) AS  net_amount , SUM(edms_inventory_item_jv.amount)/SUM(edms_inventory_item_jv.quantity) AS  rate , edms_inventory_jv.trans_date AS trans_date ,edms_inventory_jv.date_added as  date_added , edms_inventory_jv.inventory_jv_id as trans_id
    FROM  edms_inventory_item_jv INNER JOIN edms_inventory_jv ON edms_inventory_item_jv.inventory_jv_id = edms_inventory_jv.inventory_jv_id  WHERE item_id = $id AND trans_date <= '$to' AND trans_date >= '$from' AND edms_inventory_item_jv.type=0 GROUP BY edms_inventory_jv.inventory_jv_id
	";
	if(strtotime($from)<=strtotime($books_starting_date) && strtotime($to)>=($books_starting_date))
	$sql=$sql."UNION ALL
	SELECT item_id as id, 7 AS type, opening_quantity as quantity, opening_quantity*opening_rate AS net_amount, opening_rate as rate,
	'$books_starting_date' as trans_date, date_added, item_id as trans_id
	FROM edms_inventory_item WHERE item_id = $id ";
	
	$sql=$sql."UNION ALL
	 SELECT sales_item_id as id, 2 AS type, -SUM(edms_ac_sales_item.quantity) AS quantity , -SUM(edms_ac_sales_item.net_amount) AS  net_amount , SUM(edms_ac_sales_item.net_amount)/SUM(edms_ac_sales_item.quantity) AS  rate , edms_ac_sales.trans_date AS trans_date ,edms_ac_sales.date_added as  date_added , edms_ac_sales.sales_id as trans_id
    FROM  edms_ac_sales_item  INNER JOIN edms_ac_sales ON edms_ac_sales_item.sales_id = edms_ac_sales.sales_id AND edms_ac_sales_item.sales_id IS NOT NULL WHERE edms_ac_sales_item.sales_id IS NOT NULL AND item_id = $id AND edms_ac_sales.trans_date <= '$to' AND trans_date >= '$from' GROUP BY edms_ac_sales.sales_id
	
	 UNION ALL
	  SELECT sales_item_id as id, 4 AS type, -SUM(edms_ac_sales_item.quantity) AS quantity , -SUM(edms_ac_sales_item.net_amount) AS  net_amount , SUM(edms_ac_sales_item.net_amount)/SUM(edms_ac_sales_item.quantity) AS  rate , edms_ac_delivery_challan.trans_date AS trans_date ,edms_ac_delivery_challan.date_added as  date_added , edms_ac_delivery_challan.delivery_challan_id
    FROM  edms_ac_sales_item  INNER JOIN edms_ac_delivery_challan ON edms_ac_sales_item.delivery_challan_id = edms_ac_delivery_challan.delivery_challan_id AND edms_ac_sales_item.sales_id IS NULL WHERE edms_ac_sales_item.sales_id IS NULL AND item_id = $id AND trans_date <= '$to' AND trans_date >= '$from' GROUP BY edms_ac_delivery_challan.delivery_challan_id
	
	UNION ALL
	SELECT credit_note_item_id as id, 3 AS type, SUM(edms_ac_credit_note_item.quantity) AS quantity , SUM(edms_ac_credit_note_item.net_amount) AS  net_amount , SUM(edms_ac_credit_note_item.net_amount)/SUM(edms_ac_credit_note_item.quantity) AS  rate , edms_ac_credit_note.trans_date AS trans_date ,edms_ac_credit_note.date_added as  date_added , edms_ac_credit_note.credit_note_id
    FROM  edms_ac_credit_note_item INNER JOIN edms_ac_credit_note ON edms_ac_credit_note_item.credit_note_id = edms_ac_credit_note.credit_note_id  WHERE item_id = $id AND trans_date <= '$to' AND trans_date >= '$from' GROUP BY edms_ac_credit_note.credit_note_id
	
	UNION ALL
	SELECT inventory_item_jv_id as id, 8 AS type, -SUM(edms_inventory_item_jv.quantity) AS quantity , -SUM(edms_inventory_item_jv.amount) AS  net_amount , SUM(edms_inventory_item_jv.amount)/SUM(edms_inventory_item_jv.quantity) AS  rate , edms_inventory_jv.trans_date AS trans_date ,edms_inventory_jv.date_added as  date_added, edms_inventory_jv.inventory_jv_id 
    FROM  edms_inventory_item_jv INNER JOIN edms_inventory_jv ON edms_inventory_item_jv.inventory_jv_id = edms_inventory_jv.inventory_jv_id  WHERE item_id = $id AND trans_date <= '$to' AND trans_date >= '$from' AND edms_inventory_item_jv.type=1 GROUP BY edms_inventory_jv.inventory_jv_id

	 ORDER BY trans_date ASC, date_added ASC";	
	 
	 $result = dbQuery($sql);
	 $resultArray = dbResultToArray($result);
	 return $resultArray;
	 
	}
	
	
	
}
?>