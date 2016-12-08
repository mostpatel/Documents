<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("area-functions.php");
require_once("account-head-functions.php");
require_once("account-functions.php");
require_once("customer-functions.php");
require_once("account-period-functions.php");
require_once("account-ledger-functions.php");
require_once("common.php");
require_once("bd.php");
require_once("our-company-function.php");
function getAllJVs()
{
	$sql="SELECT edms_ac_jv.jv_id,edms_ac_jv.amount as total_amount, edms_ac_jv_cd.amount as amount,from_ledger_id,to_ledger_id,from_customer_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified,vch_no
			  FROM edms_ac_jv,edms_ac_jv_cd
			  WHERE edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return "error"; 
	}
function getAllJVsForCustomerId($customer_id)
{
	$sql="SELECT edms_ac_jv.jv_id,edms_ac_jv.amount as total_amount, edms_ac_jv_cd.amount as amount,from_ledger_id,to_ledger_id,from_customer_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified,vch_no
			  FROM edms_ac_jv,edms_ac_jv_cd
			  WHERE edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND (edms_ac_jv_cd.from_customer_id = $customer_id OR edms_ac_jv_cd.to_customer_id = $customer_id)";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return "error"; 
	}	
function getJVById($id)
{
	
	if(checkForNumeric($id))
	{
		$sql="SELECT edms_ac_jv.jv_id,edms_ac_jv.amount as amount, (SELECT  CONCAT_WS(',',CONCAT_WS(' : ',IF(from_ledger_id IS NOT NULL, CONCAT('L',from_ledger_id),CONCAT('C',from_customer_id)),amount))  FROM edms_ac_jv_cd as inner_jv_cd WHERE  inner_jv_cd.jv_id = edms_ac_jv.jv_id AND type =1 GROUP BY edms_ac_jv.jv_id) as credit_details, (SELECT  CONCAT_WS(',',CONCAT_WS(' : ',IF(to_ledger_id IS NOT NULL,CONCAT('L',to_ledger_id),CONCAT('C',to_customer_id)),amount))  FROM edms_ac_jv_cd as inner_jv_cd WHERE inner_jv_cd.jv_id = edms_ac_jv.jv_id AND type =0 GROUP BY edms_ac_jv.jv_id) as debit_details,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified,vch_no
			  FROM edms_ac_jv,edms_ac_jv_cd
			  WHERE edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND edms_ac_jv.jv_id=$id GROUP BY edms_ac_jv.jv_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return "error"; 
		
		}
}

function getJVByAutoRasidTypeAndId($auto_rasid_type,$auto_id)
{
	
	if(checkForNumeric($auto_id,$auto_rasid_type))
	{
		$sql="SELECT edms_ac_jv.jv_id,edms_ac_jv.amount as amount, (SELECT  CONCAT_WS(',',CONCAT_WS(' : ',IF(from_ledger_id IS NOT NULL, CONCAT('L',from_ledger_id),CONCAT('C',from_customer_id)),amount))  FROM edms_ac_jv_cd as inner_jv_cd WHERE  inner_jv_cd.jv_id = edms_ac_jv.jv_id AND type =1 GROUP BY edms_ac_jv.jv_id) as credit_details, (SELECT  CONCAT_WS(',',CONCAT_WS(' : ',IF(to_ledger_id IS NOT NULL,CONCAT('L',to_ledger_id),CONCAT('C',to_customer_id)),amount))  FROM edms_ac_jv_cd as inner_jv_cd WHERE inner_jv_cd.jv_id = edms_ac_jv.jv_id AND type =0 GROUP BY edms_ac_jv.jv_id) as debit_details,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified,vch_no
			  FROM edms_ac_jv,edms_ac_jv_cd
			  WHERE edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND edms_ac_jv.auto_rasid_type=$auto_rasid_type AND edms_ac_jv.auto_id = $auto_id GROUP BY edms_ac_jv.jv_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return "error"; 
		
		}
}

function getOutSideLabourJVForNonStockId($non_stock_id) // type = 3
{
	if(checkForNumeric($non_stock_id))
	{
		$sql="SELECT edms_ac_jv.jv_id,edms_ac_jv.amount as total_amount, edms_ac_jv_cd.amount as amount,MAX(from_ledger_id) as from_ledger_id,MAX(to_ledger_id) as to_ledger_id,MAX(from_customer_id) as from_customer_id,MAX(to_customer_id) as to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified,vch_no
			  FROM edms_ac_jv,edms_ac_jv_cd
			  WHERE edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND auto_id=$non_stock_id AND auto_rasid_type=3 GROUP BY edms_ac_jv.jv_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return false; 
		
		}
	
}

function getJVForFinancerReceiptId($receipt_id) // type = 1
{
	if(checkForNumeric($receipt_id))
	{
		$sql="SELECT edms_ac_jv.jv_id,edms_ac_jv.amount as total_amount, edms_ac_jv_cd.amount as amount,MAX(from_ledger_id) as from_ledger_id,MAX(to_ledger_id) as to_ledger_id,MAX(from_customer_id) as from_customer_id,MAX(to_customer_id) as to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified,vch_no
			  FROM edms_ac_jv,edms_ac_jv_cd
			  WHERE edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND auto_id=$receipt_id AND auto_rasid_type=1 GROUP BY edms_ac_jv.jv_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return false; 
		
		}
	
}

function getPurchaseJvForVehicleId($vehicle_id) // type = 2
{
	if(checkForNumeric($vehicle_id))
	{
		$sql="SELECT edms_ac_jv.jv_id,edms_ac_jv.amount as total_amount, edms_ac_jv_cd.amount as amount,MAX(from_ledger_id) as from_ledger_id,MAX(to_ledger_id) as to_ledger_id,MAX(from_customer_id) as from_customer_id,MAX(to_customer_id) as to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified,vch_no
			  FROM edms_ac_jv,edms_ac_jv_cd
			  WHERE edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND auto_id=$vehicle_id
			   AND auto_rasid_type=2 GROUP BY edms_ac_jv.jv_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false; 
		
	}
	return false;
}

function getPurchaseJvForVehicleIdAndLedgerId($vehicle_id,$ledger_id) // type = 2
{
	if(checkForNumeric($vehicle_id,$ledger_id))
	{
		$sql="SELECT edms_ac_jv.jv_id,edms_ac_jv.amount as total_amount, edms_ac_jv_cd.amount as amount,MAX(from_ledger_id) as from_ledger_id,MAX(to_ledger_id) as to_ledger_id,MAX(from_customer_id) as from_customer_id,MAX(to_customer_id) as to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified,vch_no
			  FROM edms_ac_jv,edms_ac_jv_cd
			  WHERE edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND auto_id=$vehicle_id
			   AND auto_rasid_type=2 AND (from_ledger_id = $ledger_id OR to_ledger_id = $ledger_id) GROUP BY edms_ac_jv.jv_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false; 
		
	}
	return false;
}

function getSalesJvForVehicleIdAndLedgerId($vehicle_id,$ledger_id) // type = 4
{
	if(checkForNumeric($vehicle_id,$ledger_id))
	{
		$sql="SELECT edms_ac_jv.jv_id,edms_ac_jv.amount as total_amount, edms_ac_jv_cd.amount as amount,MAX(from_ledger_id) as from_ledger_id,MAX(to_ledger_id) as to_ledger_id,MAX(from_customer_id) as from_customer_id,MAX(to_customer_id) as to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified,vch_no
			  FROM edms_ac_jv,edms_ac_jv_cd
			  WHERE edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND auto_id=$vehicle_id
			   AND auto_rasid_type=4 AND (from_ledger_id = $ledger_id OR to_ledger_id = $ledger_id ) GROUP BY edms_ac_jv.jv_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false; 
		
	}
	return false;
}

function getSalesJvForVehicleId($vehicle_id) // type =4
{
	if(checkForNumeric($vehicle_id))
	{
		$sql="SELECT edms_ac_jv.jv_id,edms_ac_jv.amount as total_amount, edms_ac_jv_cd.amount as amount,MAX(from_ledger_id) as from_ledger_id,MAX(to_ledger_id) as to_ledger_id,MAX(from_customer_id) as from_customer_id,MAX(to_customer_id) as to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified,vch_no
			  FROM edms_ac_jv,edms_ac_jv_cd
			  WHERE edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND auto_id=$vehicle_id
			   AND auto_rasid_type=4 GROUP BY edms_ac_jv.jv_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false; 
		
	}
	return false;
}

function getKasarJvForSalesId($sales_id) // type =7
{
	if(checkForNumeric($sales_id))
	{
		
		$sql="SELECT edms_ac_jv.jv_id,edms_ac_jv.amount as total_amount, edms_ac_jv_cd.amount as amount,MAX(from_ledger_id) as from_ledger_id,MAX(to_ledger_id) as to_ledger_id,MAX(from_customer_id) as from_customer_id,MAX(to_customer_id) as to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified,vch_no
			  FROM edms_ac_jv,edms_ac_jv_cd
			  WHERE edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND auto_id=$sales_id
			   AND auto_rasid_type=7 GROUP BY edms_ac_jv.jv_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		{
		return $resultArray[0];
		}
		else
		return false; 
		
	}
	return false;
}

function getIncomeJvForReceiptId($sales_id) // type =10
{
	if(checkForNumeric($sales_id))
	{
		
		$sql="SELECT edms_ac_jv.jv_id,edms_ac_jv.amount as total_amount, edms_ac_jv_cd.amount as amount,MAX(from_ledger_id) as from_ledger_id,MAX(to_ledger_id) as to_ledger_id,MAX(from_customer_id) as from_customer_id,MAX(to_customer_id) as to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified,vch_no
			  FROM edms_ac_jv,edms_ac_jv_cd
			  WHERE edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND auto_id=$sales_id
			   AND auto_rasid_type=10 GROUP BY edms_ac_jv.jv_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		{
		return $resultArray[0];
		}
		else
		return false; 
		
	}
	return false;
}

function getPaymentForCustomerJvForPaymentId($payment_id) // type =6
{
	if(checkForNumeric($payment_id))
	{
		$sql="SELECT edms_ac_jv.jv_id,edms_ac_jv.amount as total_amount, edms_ac_jv_cd.amount as amount,MAX(from_ledger_id) as from_ledger_id,MAX(to_ledger_id) as to_ledger_id,MAX(from_customer_id) as from_customer_id,MAX(to_customer_id) as to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified,vch_no
			  FROM edms_ac_jv,edms_ac_jv_cd
			  WHERE edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND auto_id=$payment_id
			   AND auto_rasid_type=6 GROUP BY edms_ac_jv.jv_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return false; 
		
	}
	return false;
}

function insertSalesJvsForVehicle($vehicle_id,$from_ledger,$trans_date,$purchase_jvs_array,$purchase_id)
{

	foreach($purchase_jvs_array as $purchase_sales_jv_id => $amount)
	{
		
		if(checkForNumeric($purchase_sales_jv_id,$amount) && $amount>0)
		{
			
			$purchase_sale_jv=getPurchaseSaleJvByID($purchase_sales_jv_id);
			
			$ledger_id = $purchase_sale_jv['ledger_id'];
			
			$ledger_name=getLedgerNameFromLedgerId($ledger_id);
			$cr_dr = $purchase_sale_jv['cr_dr'];
			if($cr_dr==0) // debit
			{
				
				addJV($amount,$trans_date,$from_ledger,'L'.$ledger_id,'Sales Jv '.$ledger_name,4,$vehicle_id);
			}
			else if($cr_dr==1) // credit
			{
				addJV($amount,$trans_date,'L'.$ledger_id,$from_ledger,'Sales Jv '.$ledger_name,4,$vehicle_id);	
			}
		}
	}	
	
}

function InsertLoanJvForVehicleId($vehicle_id,$from_ledger,$trans_date,$customer_id,$sales_id,$loan_amount) // type =5
{
	
	if(checkForNumeric($vehicle_id,$loan_amount) && $loan_amount>0 && validateForNull($from_ledger,$customer_id))
	{
		addJV($loan_amount,$trans_date,'L'.$from_ledger,$customer_id,'Loan JV',5,$vehicle_id);
	}
}


function deleteSalesJvsForVehicle($vehicle_id)
{
	if(checkForNumeric($vehicle_id))
	{
		
		$sql="DELETE FROM edms_ac_jv WHERE auto_rasid_type = 4 AND auto_id = $vehicle_id";
		dbQuery($sql);
		return "success";
	}
	return "error";
	
}
function deleteLoanJvForVehicle($vehicle_id)
{
	if(checkForNumeric($vehicle_id))
	{
		
		$sql="DELETE FROM edms_ac_jv WHERE auto_rasid_type = 5 AND auto_id = $vehicle_id";
		dbQuery($sql);
		return "success";
	}
	return "error";
	
}

function getLoanJVForVehicleId($vehicle_id)
{
	if(checkForNumeric($vehicle_id))
	{
		$sql="SELECT edms_ac_jv.jv_id,edms_ac_jv.amount as total_amount, edms_ac_jv_cd.amount as amount,MAX(from_ledger_id) as from_ledger_id,MAX(to_ledger_id) as to_ledger_id,MAX(from_customer_id) as from_customer_id,MAX(to_customer_id) as to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified,vch_no
			  FROM edms_ac_jv,edms_ac_jv_cd
			  WHERE edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND auto_id=$vehicle_id
			   AND auto_rasid_type=5 GROUP BY edms_ac_jv.jv_id";
			   
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return false; 
		
	}
	return false;
}

function insertPurchaseJvsForVehicle($vehicle_id,$from_ledger,$trans_date,$purchase_jvs_array,$sales_id)
{
	
	foreach($purchase_jvs_array as $purchase_sales_jv_id => $amount)
	{
		
		if(checkForNumeric($purchase_sales_jv_id,$amount) && $amount>0)
		{
			
			$purchase_sale_jv=getPurchaseSaleJvByID($purchase_sales_jv_id);
			
			$ledger_id = $purchase_sale_jv['ledger_id'];
			
			$ledger_name=getLedgerNameFromLedgerId($ledger_id);
			$cr_dr = $purchase_sale_jv['cr_dr'];
			if($cr_dr==0) // debit
			{
				
				addJV($amount,$trans_date,$from_ledger,'L'.$ledger_id,'Purchase Jv '.$ledger_name,2,$vehicle_id);
			}
			else if($cr_dr==1) // credit
			{
				addJV($amount,$trans_date,'L'.$ledger_id,$from_ledger,'Purchase Jv '.$ledger_name,2,$vehicle_id);	
			}
		}
	}	
	
}


function deletePurchaseJvsForVehicle($vehicle_id)
{
	if(checkForNumeric($vehicle_id))
	{
		
		$sql="DELETE FROM edms_ac_jv WHERE auto_rasid_type = 2 AND auto_id = $vehicle_id";
		dbQuery($sql);
		return "success";
	}
	return "error";
	
}


function addJV($amount,$trans_date,$to_ledger,$from_ledger,$remarks,$auto_rasid_type=0,$auto_id=0,$oc_id=NULL,$vch_no=NULL) //$from_ledger should start with C for customer or L for ledger to_ledger = by(debit) and from_ledger = to(credit)  
{
	
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];

	if(substr($from_ledger, 0, 1) == 'L') // if the pament is done to a general account ledger
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		
		$from_ledger_type=getLedgerHeadType($from_ledger); // returns 0 if bank or cash else returns 1
		
		if($from_ledger_type==0)
		return "bank_error";
		
		$from_customer="NULL";
		$current_company=getCompanyForLedger($from_ledger);
				if($current_company[1]==0)
				{
					if(!is_numeric($oc_id))
					$oc_id = $current_company[0];
					
					$accounts_settings=getAccountsSettingsForOC($oc_id);
				}
				
	}
	else if(substr($from_ledger, 0, 1) == 'C') // if payment is done to a customer
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		$customer=getCustomerDetailsByCustomerId($from_customer);
		
		
				
				if(!is_numeric($oc_id))
				$oc_id=getCompanyIdFromCustomerId($customer['customer_id']);
					
					$accounts_settings=getAccountsSettingsForOC($oc_id);
				
		
		}
	
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_ledger_type=getLedgerHeadType($to_ledger); // returns 0 if bank or cash else returns 1
		
		if($to_ledger_type==0)
		return "bank_error";
		$to_customer="NULL";
		
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		
		}	
			
	
	if(!checkForNumeric($oc_id)) // check for proper ledger and customer id, agency or oc_id
	{
		return "ledger_error";
		}
	if(isset($trans_date) && validateForNull($trans_date))
			{
		    $trans_date = str_replace('/', '-', $trans_date);
			$trans_date=date('Y-m-d',strtotime($trans_date));
			}	
	$ac_starting_date = $accounts_settings['ac_starting_date'];		
	
	if(strtotime($trans_date)<strtotime($ac_starting_date)) // payment date should be greater than books starting date
	{
		return "date_error";
	}	
	$vch_counter = getTransCounterForOCID($oc_id,7);
	if(!checkForNumeric($vch_no))
	{
	$vch_no = $vch_counter;	
	}
	if(checkForNumeric($amount,$admin_id,$vch_no) && validateForNull($trans_date))
	{
			
			$sql="INSERT INTO edms_ac_jv(amount,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified,vch_no)
			VALUES ($amount,$oc_id,$auto_rasid_type,$auto_id,'$trans_date','$remarks',$admin_id,$admin_id,NOW(),NOW(),'$vch_no')";
			
			$result=dbQuery($sql);
			$jv_id = dbInsertId();
			
			$sql="INSERT INTO edms_ac_jv_cd(type,amount,from_ledger_id,from_customer_id,to_ledger_id,to_customer_id,jv_id)
			VALUES (0,$amount,NULL,NULL,$to_ledger,$to_customer,$jv_id)";
			
			
	
			$result=dbQuery($sql);
			
			$sql="INSERT INTO edms_ac_jv_cd(type,amount,from_ledger_id,from_customer_id,to_ledger_id,to_customer_id,jv_id)
			VALUES (1,$amount,$from_ledger,$from_customer,NULL,NULL,$jv_id)";
			$result=dbQuery($sql);
			if($vch_counter==$vch_no)
			incrementTransCounterForOCID($oc_id,7);
			if(strtotime($trans_date)>=strtotime($ac_starting_date))
			{
				
				if(checkForNumeric($from_ledger) && $from_ledger>0) // credit the from account
				{
					 
					creditAccountingLedger($from_ledger,$amount);
				}
				else if(checkForNumeric($from_customer) && $from_customer>0)
				{  
					creditAccountingCustomer($from_customer,$amount);
				}
				
				if(checkForNumeric($to_ledger) && $to_ledger>0) // debit the to account
				{
					debitAccountingLedger($to_ledger,$amount);
				}
				else if(checkForNumeric($to_customer) && $to_customer>0)
				{  
				
					
					debitAccountingCustomer($to_customer,$amount);
				}
			}	
			
			return "success";
	}
	return "error";	
}



function getTotalAmountFromLedgerAmountAndIdArray($to_ledger_id_array,$to_ledger_amount_array)
{
	$total_amount = 0;
	for($i=0;$i<count($to_ledger_id_array);$i++)
			{
			$amount = $to_ledger_amount_array[$i];
			$to_ledger = $to_ledger_id_array[$i];
			$to_ledger =clean_data($to_ledger);
			if(substr($to_ledger, 0, 1) == 'L')
				{
					$to_ledger=str_replace('L','',$to_ledger);
					$to_ledger=intval($to_ledger);
					$to_ledger_type=getLedgerHeadType($to_ledger); // returns 0 if bank or cash else returns 1
					
					if($to_ledger_type==0)
					return "bank_error";
					$to_customer="NULL";
					
					}
				else if(substr($to_ledger, 0, 1) == 'C')
				{
					
					$to_ledger=str_replace('C','',$to_ledger);
					$to_customer=intval($to_ledger);
					$to_ledger="NULL";
					
					}	
				if(checkForNumeric($amount) && (checkForNumeric($to_ledger) || checkForNumeric($to_customer)))		
				{
					$total_amount = $total_amount + $amount;
				}
			}
	return $total_amount;		
}

function addMultiJV($trans_date,$to_ledger_id_array,$to_ledger_amount_array,$from_ledger_id_array,$from_ledger_amount_array,$remarks,$auto_rasid_type=0,$auto_id=0,$vch_no=NULL) //$from_ledger should start with C for customer or L for ledger to_ledger = by(debit) and from_ledger = to(credit)  
{
	
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	
	$from_ledger_id_array = convertFullLedgerNameToLedgerIDArray($from_ledger_id_array);
	$to_ledger_id_array = convertFullLedgerNameToLedgerIDArray($to_ledger_id_array);
	$from_total_amount = getTotalAmountFromLedgerAmountAndIdArray($from_ledger_id_array,$from_ledger_amount_array);
	$to_total_amount = getTotalAmountFromLedgerAmountAndIdArray($to_ledger_id_array,$to_ledger_amount_array);
	
	
	if($from_total_amount!=$to_total_amount)
	return "error";
	else
	$amount = $from_total_amount;
	$from_ledger = $from_ledger_id_array[0];
	
	
	if(substr($from_ledger, 0, 1) == 'L') // if the pament is done to a general account ledger
	{
		
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		
		$from_ledger_type=getLedgerHeadType($from_ledger); // returns 0 if bank or cash else returns 1
		
		if($from_ledger_type==0)
		return "bank_error";
		
		$from_customer="NULL";
		$current_company=getCompanyForLedger($from_ledger);
		
				if($current_company[1]==0)
				{
					if(!is_numeric($oc_id))
					$oc_id = $current_company[0];
					$accounts_settings=getAccountsSettingsForOC($oc_id);
					
				}
				
	}
	else if(substr($from_ledger, 0, 1) == 'C') // if payment is done to a customer
	{
		
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		$customer=getCustomerDetailsByCustomerId($from_customer);
			if(!is_numeric($oc_id))
				$oc_id=getCompanyIdFromCustomerId($customer['customer_id']);
					
					$accounts_settings=getAccountsSettingsForOC($oc_id);
				
		
		}
		
	       $to_ledger = $to_ledger_id_array[0];
			if(substr($to_ledger, 0, 1) == 'L')
				{
					$to_ledger=str_replace('L','',$to_ledger);
					$to_ledger=intval($to_ledger);
					$to_ledger_type=getLedgerHeadType($to_ledger); // returns 0 if bank or cash else returns 1
					
					if($to_ledger_type==0)
					return "bank_error";
					$to_customer="NULL";
					
					}
				else if(substr($to_ledger, 0, 1) == 'C')
				{
					$to_ledger=str_replace('C','',$to_ledger);
					$to_customer=intval($to_ledger);
					$to_ledger="NULL";
					
					}		
	
	
	
	if(!checkForNumeric($oc_id)) // check for proper ledger and customer id, agency or oc_id
	{
		return "ledger_error";
		}
	if(isset($trans_date) && validateForNull($trans_date))
			{
		    $trans_date = str_replace('/', '-', $trans_date);
			$trans_date=date('Y-m-d',strtotime($trans_date));
			}	
	$ac_starting_date = $accounts_settings['ac_starting_date'];		
	
	$period = getPeriodForUser($admin_id);
	$from_period_date = $period[0];
	$to_period_date = $period[1];
	
	if(strtotime($trans_date)<strtotime($ac_starting_date) || (PERIOD_RESTRICTION==1 && (strtotime($trans_date)<strtotime($from_period_date) || strtotime($trans_date)>strtotime($to_period_date) ))) // payment date should be greater than books starting date
	{
		return "date_error";
	}	
	$vch_counter = getTransCounterForOCID($oc_id,7);
	if(!checkForNumeric($vch_no))
	{
	$vch_no = $vch_counter;	
	}
	if(checkForNumeric($amount,$admin_id,$vch_no) && validateForNull($trans_date))
	{
			
			$sql="INSERT INTO edms_ac_jv(amount,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified,vch_no)
			VALUES ($amount,$oc_id,$auto_rasid_type,$auto_id,'$trans_date','$remarks',$admin_id,$admin_id,NOW(),NOW(),'$vch_no')";
			
			$result=dbQuery($sql);
			$jv_id = dbInsertId();
			if($vch_counter==$vch_no)
			incrementTransCounterForOCID($oc_id,7);
			for($i=0;$i<count($to_ledger_id_array);$i++)
			{
			$amount = $to_ledger_amount_array[$i];
			$to_ledger = $to_ledger_id_array[$i];
			$to_ledger =clean_data($to_ledger);
			if(substr($to_ledger, 0, 1) == 'L')
				{
					$to_ledger=str_replace('L','',$to_ledger);
					$to_ledger=intval($to_ledger);
					$to_ledger_type=getLedgerHeadType($to_ledger); // returns 0 if bank or cash else returns 1
					
					if($to_ledger_type==0)
					return "bank_error";
					$to_customer="NULL";
					
					}
				else if(substr($to_ledger, 0, 1) == 'C')
				{
					
					$to_ledger=str_replace('C','',$to_ledger);
					$to_customer=intval($to_ledger);
					$to_ledger="NULL";
					
					}	
				if(checkForNumeric($amount) && (checkForNumeric($to_ledger) || checkForNumeric($to_customer)))		
				{
				$sql="INSERT INTO edms_ac_jv_cd(type,amount,from_ledger_id,from_customer_id,to_ledger_id,to_customer_id,jv_id)
				VALUES (0,$amount,NULL,NULL,$to_ledger,$to_customer,$jv_id)";
				
				
				
		
				$result=dbQuery($sql);
				}
			}
			for($i=0;$i<count($from_ledger_id_array);$i++)
			{
			$amount = $from_ledger_amount_array[$i];
			$from_ledger = $from_ledger_id_array[$i];
			$from_ledger =clean_data($from_ledger);
			if(substr($from_ledger, 0, 1) == 'L') // if the pament is done to a general account ledger
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		
		$from_ledger_type=getLedgerHeadType($from_ledger); // returns 0 if bank or cash else returns 1
		
		if($from_ledger_type==0)
		return "bank_error";
		
		$from_customer="NULL";
		$current_company=getCompanyForLedger($from_ledger);
				if($current_company[1]==0)
				{
					$oc_id = $current_company[0];
					
					$accounts_settings=getAccountsSettingsForOC($oc_id);
				}
				
	}
	else if(substr($from_ledger, 0, 1) == 'C') // if payment is done to a customer
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		$customer=getCustomerDetailsByCustomerId($from_customer);		
		$agency_company_type_id=$customer['oc_id'];		
		$oc_id=$agency_company_type_id;
		$accounts_settings=getAccountsSettingsForOC($oc_id);
				
		
		}	
				if(checkForNumeric($amount) && (checkForNumeric($from_ledger) || checkForNumeric($from_customer)))		
				{
				$sql="INSERT INTO edms_ac_jv_cd(type,amount,from_ledger_id,from_customer_id,to_ledger_id,to_customer_id,jv_id)
				VALUES (1,$amount,$from_ledger,$from_customer,NULL,NULL,$jv_id)";
				
				
		
				$result=dbQuery($sql);
				}
			}
		
			
			return "success";
	}
	return "error";	
}


function updateMultiJV($id,$trans_date,$to_ledger_id_array,$to_ledger_amount_array,$from_ledger_id_array,$from_ledger_amount_array,$remarks,$auto_rasid_type=0,$auto_id=0,$vch_no=NULL) //$from_ledger should start with C for customer or L for ledger to_ledger = by(debit) and from_ledger = to(credit)  
{
	
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	
	
	
	$from_ledger_id_array = convertFullLedgerNameToLedgerIDArray($from_ledger_id_array);
	$to_ledger_id_array = convertFullLedgerNameToLedgerIDArray($to_ledger_id_array);
	$from_total_amount = getTotalAmountFromLedgerAmountAndIdArray($from_ledger_id_array,$from_ledger_amount_array);
	$to_total_amount = getTotalAmountFromLedgerAmountAndIdArray($to_ledger_id_array,$to_ledger_amount_array);
	
	
	if($from_total_amount!=$to_total_amount)
	return "error";
	else
	$amount = $from_total_amount;
	$from_ledger = $from_ledger_id_array[0];
	
	if(substr($from_ledger, 0, 1) == 'L') // if the pament is done to a general account ledger
	{
		
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		
		$from_ledger_type=getLedgerHeadType($from_ledger); // returns 0 if bank or cash else returns 1
		
		if($from_ledger_type==0)
		return "bank_error";
		
		$from_customer="NULL";
		$current_company=getCompanyForLedger($from_ledger);
				if($current_company[1]==0)
				{
					$oc_id = $current_company[0];
					$accounts_settings=getAccountsSettingsForOC($oc_id);
				}
				
	}
	else if(substr($from_ledger, 0, 1) == 'C') // if payment is done to a customer
	{
		
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		$customer=getCustomerDetailsByCustomerId($from_customer);
	
		if(!is_numeric($oc_id))
		$oc_id=getCompanyIdFromCustomerId($customer['customer_id']);
		$accounts_settings=getAccountsSettingsForOC($oc_id);
		}
		
	$to_ledger = $to_ledger_id_array[0];
			if(substr($to_ledger, 0, 1) == 'L')
				{
					$to_ledger=str_replace('L','',$to_ledger);
					$to_ledger=intval($to_ledger);
					$to_ledger_type=getLedgerHeadType($to_ledger); // returns 0 if bank or cash else returns 1
					
					if($to_ledger_type==0)
					return "bank_error";
					$to_customer="NULL";
					
					}
				else if(substr($to_ledger, 0, 1) == 'C')
				{
					$to_ledger=str_replace('C','',$to_ledger);
					$to_customer=intval($to_ledger);
					$to_ledger="NULL";
					
					}		
	
	
	
	if(!checkForNumeric($oc_id)) // check for proper ledger and customer id, agency or oc_id
	{
		return "ledger_error";
		}
	if(isset($trans_date) && validateForNull($trans_date))
			{
		    $trans_date = str_replace('/', '-', $trans_date);
			$trans_date=date('Y-m-d',strtotime($trans_date));
			}	
	$ac_starting_date = $accounts_settings['ac_starting_date'];		
	
	$period = getPeriodForUser($admin_id);
	$from_period_date = $period[0];
	$to_period_date = $period[1];
	if(strtotime($trans_date)<strtotime($ac_starting_date) || (PERIOD_RESTRICTION==1 && (strtotime($trans_date)<strtotime($from_period_date) || strtotime($trans_date)>strtotime($to_period_date) ))) // payment date should be greater than books starting date
	{
		return "date_error";
	}	
	
	if(checkForNumeric($amount,$admin_id) && validateForNull($trans_date))
	{
			
			$sql="UPDATE  edms_ac_jv SET amount = $amount,trans_date = '$trans_date',remarks = '$remarks',last_updated_by = $admin_id,date_modified = NOW()
			";
			if(checkForNumeric($vch_no))
			$sql=$sql." ,vch_no='$vch_no'  ";
			$sql=$sql."
			WHERE jv_id = $id";
			
			$result=dbQuery($sql);
			$jv_id = $id;
			$sql="DELETE FROM edms_ac_jv_cd WHERE jv_id = $id";
			dbQuery($sql);
			for($i=0;$i<count($to_ledger_id_array);$i++)
			{
			$amount = $to_ledger_amount_array[$i];
			$to_ledger = $to_ledger_id_array[$i];
			$to_ledger =clean_data($to_ledger);
			if(substr($to_ledger, 0, 1) == 'L')
				{
					$to_ledger=str_replace('L','',$to_ledger);
					$to_ledger=intval($to_ledger);
					$to_ledger_type=getLedgerHeadType($to_ledger); // returns 0 if bank or cash else returns 1
					
					if($to_ledger_type==0)
					return "bank_error";
					$to_customer="NULL";
					
					}
				else if(substr($to_ledger, 0, 1) == 'C')
				{
					
					$to_ledger=str_replace('C','',$to_ledger);
					$to_customer=intval($to_ledger);

					$to_ledger="NULL";
					
					}	
				if(checkForNumeric($amount) && (checkForNumeric($to_ledger) || checkForNumeric($to_customer)))		
				{
				$sql="INSERT INTO edms_ac_jv_cd(type,amount,from_ledger_id,from_customer_id,to_ledger_id,to_customer_id,jv_id)
				VALUES (0,$amount,NULL,NULL,$to_ledger,$to_customer,$jv_id)";
				
				
				
		
				$result=dbQuery($sql);
				}
			}
			for($i=0;$i<count($from_ledger_id_array);$i++)
			{
			$amount = $from_ledger_amount_array[$i];
			$from_ledger = $from_ledger_id_array[$i];
			$from_ledger =clean_data($from_ledger);
			if(substr($from_ledger, 0, 1) == 'L') // if the pament is done to a general account ledger
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		
		$from_ledger_type=getLedgerHeadType($from_ledger); // returns 0 if bank or cash else returns 1
		
		if($from_ledger_type==0)
		return "bank_error";
		
		$from_customer="NULL";
		$current_company=getCompanyForLedger($from_ledger);
				if($current_company[1]==0)
				{
					$oc_id = $current_company[0];
					
					$accounts_settings=getAccountsSettingsForOC($oc_id);
				}
				
	}
	else if(substr($from_ledger, 0, 1) == 'C') // if payment is done to a customer
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		$customer=getCustomerDetailsByCustomerId($from_customer);
		
		
	    if(!is_numeric($oc_id))
		$oc_id=getCompanyIdFromCustomerId($customer['customer_id']);
		$accounts_settings=getAccountsSettingsForOC($oc_id);
				
		
		}	
				if(checkForNumeric($amount) && (checkForNumeric($from_ledger) || checkForNumeric($from_customer)))		
				{
				$sql="INSERT INTO edms_ac_jv_cd(type,amount,from_ledger_id,from_customer_id,to_ledger_id,to_customer_id,jv_id)
				VALUES (1,$amount,$from_ledger,$from_customer,NULL,NULL,$jv_id)";
				
				
		
				$result=dbQuery($sql);
				}
			}
		
			
			return "success";
	}
	return "error";	
}


function removeJV($id)
{
	if(checkForNumeric($id))
	{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$old_payment=getJVById($id); // get the JV details
		$old_amount=$old_payment['amount'];
		$old_trans_date=$old_payment['trans_date'];
		$old_from_ledger_id=$old_payment['from_ledger_id'];
		$old_from_customer_id=$old_payment['from_customer_id'];
		$old_to_ledger_id=$old_payment['to_ledger_id'];
		$old_to_customer_id=$old_payment['to_ledger_id'];
		
		
	    $oc_id=$old_payment['oc_id'];
	
	 if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
		}
		
		$sql="DELETE FROM edms_ac_jv where jv_id=$id";
		dbQuery($sql);
		
		$ac_starting_date = $accounts_settings['ac_starting_date'];
			
			if(strtotime($old_trans_date)>=strtotime($ac_starting_date)) // if transactio was done after books starting date
			{
				
				if(checkForNumeric($old_from_ledger_id) && $old_from_ledger_id>0)
				{
					debitAccountingLedger($old_from_ledger_id,$old_amount);
				}
				else if(checkForNumeric($old_from_customer_id) && $old_from_customer_id>0)
				{
					debitAccountingCustomer($old_from_customer_id,$old_amount);
				}
				
				if(checkForNumeric($old_to_ledger_id) && $old_to_ledger_id>0)
				{
					creditAccountingLedger($old_to_ledger_id,$old_amount);
				}
				else if(checkForNumeric($old_to_customer_id) && $old_to_customer_id>0)
				{
					creditAccountingCustomer($old_to_customer_id,$old_amount);
				}
			}	
		return "success";
		}
		return "error";
	}
	
function updateJV($id,$amount,$trans_date,$to_ledger,$from_ledger,$remarks,$oc_id=NULL,$vch_no=NULL)
{
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	$old_payment=getJVById($id);
	
	$old_amount=$old_payment['amount'];
	$old_trans_date=$old_payment['trans_date'];
	$old_from_ledger_id=$old_payment['from_ledger_id'];
	$old_from_customer_id=$old_payment['from_customer_id'];
	$old_to_ledger_id=$old_payment['to_ledger_id'];
	$old_to_customer_id=$old_payment['to_customer_id'];
	
	if(!is_numeric($oc_id))
	$oc_id=$old_payment['oc_id'];
	
	if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
		}
		
	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		
		$from_ledger_type=getLedgerHeadType($from_ledger); // returns 0 if bank or cash else returns 1
		
		if($from_ledger_type==0)
		return "bank_error";
		
		$from_customer="NULL";
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		}	
	
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_ledger_type=getLedgerHeadType($to_ledger); // returns 0 if bank or cash else returns 1
		
		if($to_ledger_type==0)
		return "bank_error";
		$to_customer="NULL";
		
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		
		}	
	
	if(!checkForNumeric($oc_id)) // check for proper ledger and customer id as well as agency or oc id
	{
		return "ledger_error";
		}
	
	if(isset($trans_date) && validateForNull($trans_date))
			{
		    $trans_date = str_replace('/', '-', $trans_date);
			$trans_date=date('Y-m-d',strtotime($trans_date));
			}	
	$ac_starting_date = $accounts_settings['ac_starting_date'];		
	
	if(strtotime($trans_date)<strtotime($ac_starting_date)) // payment date should be greater than books starting date
	{
		return "date_error";
	}	
	
		
	if(checkForNumeric($amount,$admin_id,$id) && validateForNull($trans_date))
	{
			
			$sql="UPDATE edms_ac_jv SET amount=$amount, trans_date='$trans_date', remarks='$remarks', last_updated_by=$admin_id, date_modified=NOW()";
			if(checkForNumeric($vch_no))
			$sql=$sql." ,vch_no='$vch_no' ";
			$sql=$sql." WHERE jv_id=$id";
			$result=dbQuery($sql);
			$sql="UPDATE  edms_ac_jv_cd SET  amount=$amount, from_ledger_id=NULL, to_ledger_id=$to_ledger, from_customer_id= NULL, to_customer_id=$to_customer WHERE jv_id = $id AND type = 0";
			$result=dbQuery($sql);
			$sql="UPDATE  edms_ac_jv_cd SET  amount=$amount, from_ledger_id=$from_ledger, to_ledger_id=NULL, from_customer_id= $from_customer, to_customer_id=NULL WHERE jv_id = $id AND type = 1";
			$result=dbQuery($sql);
			
			$ac_starting_date = $accounts_settings['ac_starting_date'];
			
			if(strtotime($old_trans_date)>=strtotime($ac_starting_date)) // if transactio was done after books starting date
			{
				
				if(checkForNumeric($old_from_ledger_id) && $old_from_ledger_id>0)
				{
					
					debitAccountingLedger($old_from_ledger_id,$old_amount);
				}
				else if(checkForNumeric($old_from_customer_id) && $old_from_customer_id>0)
				{
					debitAccountingCustomer($old_from_customer_id,$old_amount);
				}
				
				if(checkForNumeric($old_to_ledger_id) && $old_to_ledger_id>0)
				{
					creditAccountingLedger($old_to_ledger_id,$old_amount);
				}
				else if(checkForNumeric($old_to_customer_id) && $old_to_customer_id>0)
				{
					
					creditAccountingCustomer($old_to_customer_id,$old_amount);
				}
			}	
			
			
			if(strtotime($trans_date)>=strtotime($ac_starting_date))
			{
				
				if(checkForNumeric($from_ledger) && $from_ledger>0) // credit the from account
				{
					
					creditAccountingLedger($from_ledger,$amount);
				}
				else if(checkForNumeric($from_customer) && $from_customer>0)
				{  
					creditAccountingCustomer($from_customer,$amount);
				}
				
				if(checkForNumeric($to_ledger) && $to_ledger>0) // debit the to account
				{
					debitAccountingLedger($to_ledger,$amount);
				}
				else if(checkForNumeric($to_customer) && $to_customer>0)
				{  
					debitAccountingCustomer($to_customer,$amount);
				}
			}	
	return "success";
	}
	
	return "error";	
	
	}	

	

function getCreditJVsForLedgerIdMonthWiseBetweenDates($from_ledger,$from=NULL,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{

	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		}	
	
	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($from_customer) ||  checkForNumeric($from_ledger))
	{
	$sql="SELECT edms_ac_jv.jv_id,SUM(edms_ac_jv_cd.amount),from_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified,vch_no
			  FROM edms_ac_jv, edms_ac_jv_cd WHERE edms_ac_jv.oc_id = $oc_id AND edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND type=1  ";
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND trans_date<='$to'
		   ";
	if(checkForNumeric($from_ledger))  
	$sql=$sql." AND from_ledger_id=$from_ledger";
	else if(checkForNumeric($from_customer))
	$sql=$sql." AND from_customer_id=$from_customer";
	$sql=$sql." GROUP BY month_year";	  		  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
	}

function getTotalCreditJVsForLedgerIdMonthWiseBetweenDates($from_ledger,$month_id,$year,$from=NULL,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{

	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		}	
	
	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if((checkForNumeric($from_customer) ||  checkForNumeric($from_ledger)) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT edms_ac_jv.jv_id,SUM(edms_ac_jv_cd.amount) as total_amount,from_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified,vch_no
			  FROM edms_ac_jv ,edms_ac_jv_cd WHERE edms_ac_jv.oc_id = $oc_id AND edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND type=1 ";
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND trans_date<='$to'
		   ";
	if(checkForNumeric($from_ledger))  
	$sql=$sql." AND from_ledger_id=$from_ledger";
	else if(checkForNumeric($from_customer))
	$sql=$sql." AND from_customer_id=$from_customer ";
	$sql=$sql." AND DATE_FORMAT(trans_date,'%c')=$month_id AND DATE_FORMAT(trans_date,'%Y') = $year GROUP BY month_year";	  		  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];
	else
	return 0; 	
	}
	return 0;
	}


function getDebitJVsForLedgerIdMonthWiseBetweenDates($to_ledger,$from=NULL,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{

	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		}
	
	if(isset($from) && validateForNull($from))
	{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($to_customer) ||  checkForNumeric($to_ledger))
	{
	$sql="SELECT edms_ac_jv.jv_id,SUM(edms_ac_jv_cd.amount),to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date, trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified,vch_no
			  FROM edms_ac_jv  ,edms_ac_jv_cd WHERE edms_ac_jv.oc_id = $oc_id AND edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND type=0 ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND trans_date<='$to'
		   ";
	if(checkForNumeric($to_ledger))  
	$sql=$sql." AND  to_ledger_id=$to_ledger";
	else if(checkForNumeric($to_customer))
	$sql=$sql." AND  to_customer_id=$to_customer";
	$sql=$sql." GROUP BY month_year";	  			  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
}

function getTotalDebitJVsForLedgerIdMonthWiseBetweenDates($to_ledger,$month_id,$year,$from=NULL,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{

	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		}
	
	if(isset($from) && validateForNull($from))
	{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if((checkForNumeric($to_customer) ||  checkForNumeric($to_ledger)) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT edms_ac_jv.jv_id,SUM(edms_ac_jv_cd.amount) as total_amount,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified,vch_no
			  FROM edms_ac_jv  ,edms_ac_jv_cd WHERE edms_ac_jv.oc_id = $oc_id AND edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND type=0 ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND trans_date<='$to'
		   ";
	if(checkForNumeric($to_ledger))  
	$sql=$sql." AND to_ledger_id=$to_ledger";
	else if(checkForNumeric($to_customer))
	$sql=$sql." AND to_customer_id=$to_customer";
	$sql=$sql." AND DATE_FORMAT(trans_date,'%c')=$month_id AND DATE_FORMAT(trans_date,'%Y') = $year GROUP BY month_year";	  			  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];
	else
	return 0; 	
	}
	return 0;
}
		
function getTotalCreditAmountForLedgerIdUptoDate($from_ledger,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{

	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		}	
	
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($from_customer) || checkForNumeric($from_ledger))
	{
	$sql="SELECT edms_ac_jv.jv_id,SUM(edms_ac_jv_cd.amount),from_ledger_id,from_customer_id
			  FROM edms_ac_jv, edms_ac_jv_cd  WHERE edms_ac_jv.oc_id = $oc_id AND edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND type = 1 ";
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND trans_date<='$to'
		   ";
	if(checkForNumeric($from_ledger))  
	$sql=$sql." AND from_ledger_id=$from_ledger GROUP BY from_ledger_id";
	else if(checkForNumeric($from_customer))  
	$sql=$sql." AND from_customer_id=$from_customer GROUP BY from_customer_id";	  		  
	$result=dbQuery($sql);
	
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];
	else
	return 0; 	
	}
	return 0;
	}	


function getNetJVAmountForLedgerIdUptoDate($from_ledger,$to=NULL)
{
	
	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		}	
	
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($from_customer) || checkForNumeric($from_ledger))
	{
	$sql="SELECT SUM(edms_ac_jv_cd.amount) as credit_amount
			  FROM edms_ac_jv , edms_ac_jv_cd  WHERE edms_ac_jv.oc_id = $oc_id AND edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND type = 1 ";
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND trans_date<='$to'
		  AND ";
	if(checkForNumeric($from_ledger))  
	$sql=$sql." AND from_ledger_id=$from_ledger GROUP BY from_ledger_id";
	else if(checkForNumeric($from_customer))  
	$sql=$sql." AND from_customer_id=$from_customer GROUP BY from_customer_id";	  	
	$sql=$sql."
	 UNION ALL
	SELECT SUM(edms_ac_jv_cd.amount) as debit_amount
			  FROM edms_ac_jv , edms_ac_jv_cd  WHERE edms_ac_jv.oc_id = $oc_id AND edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND type = 0 ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND trans_date<='$to'
		   ";
	if(checkForNumeric($from_ledger))  
	$sql=$sql." AND to_ledger_id=$from_ledger GROUP BY to_ledger_id";
	else if(checkForNumeric($from_customer)) 
	$sql=$sql." AND to_customer_id=$from_customer GROUP BY to_customer_id";		 
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[1][0]-$resultArray[0][0];
	else
	return 0; 	
	}
	return 0;
	
	}	

function getTotalDebitAmountForLedgerIdUptoDate($to_ledger,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		}
	
	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($to_customer) || checkForNumeric($to_ledger))
	{
	$sql="SELECT edms_ac_jv.jv_id,SUM(edms_ac_jv_cd.amount),to_ledger_id,to_customer_id
			  FROM edms_ac_jv , edms_ac_jv_cd  WHERE edms_ac_jv.oc_id = $oc_id AND edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND type = 0 ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND trans_date<='$to'
		   ";
	if(checkForNumeric($to_ledger))  
	$sql=$sql." AND to_ledger_id=$to_ledger GROUP BY to_ledger_id";
	else if(checkForNumeric($to_customer)) 
	$sql=$sql." AND to_customer_id=$to_customer GROUP BY to_customer_id";			  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];
	else
	return 0; 	
	}
	return 0;
	}

function getJVsForLedgerIdForMonth($from_ledger,$month_id,$year,$from=NULL,$to=NULL)
{
	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		}	
	
	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if((checkForNumeric($from_customer)  || checkForNumeric($from_ledger)) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT edms_ac_jv.jv_id,edms_ac_jv_cd.amount,(SELECT MIN(from_ledger_id) FROM edms_ac_jv_cd as inner_ac_jv_cd WHERE inner_ac_jv_cd.jv_id = edms_ac_jv.jv_id AND type = 1 GROUP BY jv_id) as from_ledger_id  , (SELECT MIN(from_customer_id) FROM edms_ac_jv_cd as inner_ac_jv_cd WHERE inner_ac_jv_cd.jv_id = edms_ac_jv.jv_id AND type = 1 GROUP BY jv_id) as from_customer_id,(SELECT MIN(to_ledger_id) FROM edms_ac_jv_cd as inner_ac_jv_cd WHERE inner_ac_jv_cd.jv_id = edms_ac_jv.jv_id AND type = 0 GROUP BY jv_id) as to_ledger_id  , (SELECT MIN(to_customer_id) FROM edms_ac_jv_cd as inner_ac_jv_cd WHERE inner_ac_jv_cd.jv_id = edms_ac_jv.jv_id AND type = 0 GROUP BY jv_id) as to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified,vch_no
			  FROM edms_ac_jv , edms_ac_jv_cd  WHERE edms_ac_jv.oc_id = $oc_id AND edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id  ";
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND trans_date<='$to'
		   ";
	if(checkForNumeric($from_ledger))  
	$sql=$sql." AND (from_ledger_id=$from_ledger OR to_ledger_id=$from_ledger) ";
	else if(checkForNumeric($from_customer))  
	$sql=$sql." AND (from_customer_id=$from_customer OR to_customer_id=$from_customer) ";
	
	$sql=$sql." AND DATE_FORMAT(trans_date,'%c')=$month_id AND DATE_FORMAT(trans_date,'%Y') = $year GROUP BY edms_ac_jv.jv_id";	  		  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();	
	}
	return array();
}			

function getJVsForLedgerIdBetweenDates($from_ledger,$from=NULL,$to=NULL)
{
	if($from_ledger!=-1)
	{
	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($from_ledger);
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		}	
	}
	else
	{
	 $current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	 $oc_id=$current_company[0];
		
	}
	
	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($from_customer) || checkForNumeric($from_ledger)  || $from_ledger==-1)
	{
	$sql="SELECT edms_ac_jv.jv_id,edms_ac_jv_cd.amount,from_ledger_id,to_ledger_id,from_customer_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified, remarks,vch_no
			  FROM edms_ac_jv,edms_ac_jv_cd  WHERE edms_ac_jv.oc_id = $oc_id AND edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id ";
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND trans_date<='$to'
		   "; 
	if($from_ledger!=-1)
	{	   
	if(checkForNumeric($from_ledger))  
	$sql=$sql." AND (from_ledger_id=$from_ledger OR to_ledger_id=$from_ledger) ";
	else if(checkForNumeric($from_customer))  
	$sql=$sql." AND (from_customer_id=$from_customer OR to_customer_id=$from_customer)";	  
	}
	else
	{
	 if(checkForNumeric($oc_id))
	$sql=$sql." AND oc_id IN ( ".$oc_id.")";	  
	}  
	if($from_ledger==-1)
	$sql=$sql." GROUP BY edms_ac_jv.jv_id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();	
	}
	return array();
}			
function getCreditJVCDsForJVID($jv_id)
{
	if(checkForNumeric($jv_id))
	{
		$sql="SELECT CONCAT_WS(' : ',IF(from_ledger_id IS NOT NULL, CONCAT('L',from_ledger_id),CONCAT('C',from_customer_id)),amount) FROM edms_ac_jv_cd as inner_jv_cd WHERE  inner_jv_cd.jv_id = $jv_id AND type =1";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return "error"; 
		
	}
	
}
function getDebitJVCDsForJVID($jv_id)
{
	if(checkForNumeric($jv_id))
	{
		$sql="SELECT CONCAT_WS(' : ',IF(to_ledger_id IS NOT NULL, CONCAT('L',to_ledger_id),CONCAT('C',to_customer_id)),amount) FROM edms_ac_jv_cd as inner_jv_cd WHERE  inner_jv_cd.jv_id = $jv_id AND type =0";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return "error"; 
		
	}
	
}	
?>