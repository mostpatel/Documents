<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("area-functions.php");
require_once("account-head-functions.php");
require_once("account-receipt-details-functions.php");
require_once("account-period-functions.php");
require_once("account-ledger-functions.php");
require_once("account-jv-functions.php");
require_once("account-sales-functions.php");
require_once("account-functions.php");
require_once("customer-functions.php");
require_once("job-card-functions.php");
require_once("common.php");
require_once("bd.php");
require_once("our-company-function.php");

function getReconciliationDateForReceiptId($id)
{
	$sql="SELECT reconciliation_date FROM edms_ac_receipt WHERE receipt_id = $id AND reconciliation_date!='1970-01-01'";
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
	else
	return  false;
}
function getAllReceipts()
{
	$sql="SELECT receipt_id,receipt_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,created_by,last_updated_by,date_added,date_modified,remarks,parent_id
			  FROM edms_ac_receipt";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return "error"; 
	}
	
function getAllNormalReceiptsForCustomer($customer_id) // type = 0 or type >100
{
	$sql="SELECT receipt_id,receipt_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,created_by,last_updated_by,date_added,date_modified,remarks,parent_id
			  FROM edms_ac_receipt WHERE (auto_rasid_type=0 OR auto_rasid_type>100) AND to_customer_id=$customer_id";
			  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return "error"; 
	}	

function insertReceiptForJobCardId($job_card_id,$amount,$trans_date,$to_ledger,$from_ledger,$remarks,$kasar_amount,$kasar_type) // type =3
{
	
	if(checkForNumeric($job_card_id))
	{
			$result=insertReceiptReturnTypeRasidNo($amount,$trans_date,$to_ledger,$from_ledger,$remarks,3,$job_card_id);
			
			if(checkForNumeric($result) && $kasar_amount>0) // finanacer rasid
			{
				
				$kasar_ledger=getKasarLedgerIdForOC($_SESSION['edmsAdminSession']['oc_id']);
				
				if($kasar_type==0)
				addJV($kasar_amount,$trans_date,"L".$kasar_ledger,$to_ledger,$remarks,7,$result);
				else if($kasar_type==1)
				addJV($kasar_amount,$trans_date,$to_ledger,"L".$kasar_ledger,$remarks,7,$result);
			}
			
			return $result;
		
	}
	return "error";
}

function insertReceiptForVehicleId($vehicle_id,$amount,$trans_date,$to_ledger,$from_ledger,$remarks) // type =4
{
	
	if(checkForNumeric($vehicle_id) && $vehicle_id>0)
	{
		
	
		if($amount>0)
		{
			$result=insertReceipt($amount,$trans_date,$to_ledger,$from_ledger,$remarks,4,$vehicle_id);
			return $result;
		}
	}
	return "error";
}

function insertReceiptForSalesId($sales_id,$amount,$trans_date,$to_ledger,$from_ledger,$remarks,$kasar_amount,$kasar_type,$oc_id=NULL,$payment_mode_id=-1,$chq_date="01/01/1970",$chq_no="000000",$bank_name="NA",$branch_name="NA") // type =5
{
	
	if(checkForNumeric($sales_id))
	{
		
	if(!is_numeric($oc_id))
	$oc_id = $sales['oc_id'];
		if($amount>0)
		{
			
				
				
			$sales = getSaleById($sales_id);
			$result=insertReceiptReturnTypeRasidNo($amount,$trans_date,$to_ledger,$from_ledger,$remarks,5,$sales_id,'NA',0,$oc_id,$payment_mode_id,$chq_date,$chq_no,$bank_name,$branch_name);
			
			if(TAX_MODE==1)
			{
			$income_ledger=getInomeLedgerForOC($oc_id);
				
				addJV($amount,$trans_date,$to_ledger,'L'.$income_ledger,'SALES RECEIPT AUTO JV',10,$result,$oc_id);
				
			}
			
			if(checkForNumeric($result) && $kasar_amount>0) // finanacer rasid
			{
				
				$kasar_ledger=getKasarLedgerIdForOC($_SESSION['edmsAdminSession']['oc_id']);
				
				if($kasar_type==0)
				addJV($kasar_amount,$trans_date,"L".$kasar_ledger,$to_ledger,$remarks,7,$result,$oc_id);
				else if($kasar_type==1)
				addJV($kasar_amount,$trans_date,$to_ledger,"L".$kasar_ledger,$remarks,7,$result,$oc_id);
			}
			
			return $result;
		}
	}
	return "error";
}


function insertReceiptForCustomerId($amount,$trans_date,$to_ledger,$from_ledger,$remarks,$rasid_type,$financer=NULL) // type = 1 and type= 2
{
	
	if(checkForNumeric($rasid_type) && ($rasid_type==1 || validateForNull($financer)))
	{
		
		$to_ledger_customer=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger_customer);
			
			$result=insertReceiptReturnTypeRasidNo($amount,$trans_date,$to_ledger,$from_ledger,$remarks,$rasid_type,$to_customer);
			
			if(checkForNumeric($result) && $rasid_type==2) // finanacer rasid
			{
				addJV($amount,$trans_date,$to_ledger,$financer,$remarks,1,$result);
			}
			
			return $result;
		
	}
	return "error";
}



function updateReceiptForCustomerIdFinancer($receipt_id,$amount,$trans_date,$to_ledger,$from_ledger,$remarks,$financer=NULL)
{
	
	if(validateForNull($financer) && checkForNumeric($receipt_id))
		{
		$to_ledger_customer=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger_customer);
			
			$result=updateReceipt($receipt_id,$amount,$trans_date,$to_ledger,$from_ledger,$remarks);
			
			if($result=="success") // finanacer rasid
			{
				$jv=getJVForFinancerReceiptId($receipt_id);
				updateJV($jv['jv_id'],$amount,$trans_date,$to_ledger,$financer,$remarks);
			}
			
			return $result;
		}
	
	return "error";
}

function updateReceiptJobCardId($job_card_id,$id,$amount,$trans_date,$to_ledger,$from_ledger,$remarks,$kasar_amount=0,$kasar_type=0) // $to_ledger should start with C for customer or L for ledger
{
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		
	if(checkForNumeric($id,$job_card_id))
		{
			
			$result=updateReceipt($id,$amount,$trans_date,$to_ledger,$from_ledger,$remarks);
		
			$kasar_jv=getKasarJvForSalesId($id);
			removeJV($kasar_jv['jv_id']);
			if(checkForNumeric($id) && $kasar_amount>0) // finanacer rasid
			{
				
				$kasar_ledger=getKasarLedgerIdForOC($_SESSION['edmsAdminSession']['oc_id']);
				
				if($kasar_type==0)
				addJV($kasar_amount,$trans_date,"L".$kasar_ledger,$to_ledger,$remarks,7,$id);
				else if($kasar_type==1)
				addJV($kasar_amount,$trans_date,$to_ledger,"L".$kasar_ledger,$remarks,7,$id);
			}
			
			
			return $result;
		}
	
	return "error";		
}

function updateReceiptForSalesId($sales_id,$id,$amount,$trans_date,$to_ledger,$from_ledger,$remarks,$kasar_amount=0,$kasar_type=0,$oc_id=NULL,$payment_mode_id=-1,$chq_date="01/01/1970",$chq_no="000000",$bank_name="NA",$branch_name="NA",$ref="NA",$ref_type=0) // $to_ledger should start with C for customer or L for ledger
{
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		
	if(checkForNumeric($id,$sales_id))
		{
			
			$result = updateReceipt($id,$amount,$trans_date,$to_ledger,$from_ledger,$remarks,$ref,$ref_type,$oc_id,$payment_mode_id,$chq_date,$chq_no,$bank_name,$branch_name);
		
			$kasar_jv=getKasarJvForSalesId($id);
			$income_jv = getIncomeJvForReceiptId($id);
			$receipt=getReceiptById($id);
			removeJV($income_jv['jv_id']);
			if(TAX_MODE==1)
			{
			   $income_ledger=getInomeLedgerForOC($receipt['oc_id']);
				
				addJV($amount,$trans_date,$to_ledger,'L'.$income_ledger,'SALES RECEIPT AUTO JV',10,$id,$receipt['oc_id']);
				
			}
			
			removeJV($kasar_jv['jv_id']);
			if(checkForNumeric($id) && $kasar_amount>0) // finanacer rasid
			{
				$sales = getSaleById($sales_id);
				$kasar_ledger=getKasarLedgerIdForOC($sales['oc_id']);
					
				if($kasar_type==0)
				addJV($kasar_amount,$trans_date,"L".$kasar_ledger,$to_ledger,$remarks,7,$id,$receipt['oc_id']);
				else if($kasar_type==1)
				addJV($kasar_amount,$trans_date,$to_ledger,"L".$kasar_ledger,$remarks,7,$id,$receipt['oc_id']);
			}
			
			
			return $result;
		}
	
	return "error";		
}


function updateReceiptForVehicleId($vehicle_id,$receipt_id,$amount,$trans_date,$to_ledger,$from_ledger,$remarks)
{
	
	if(checkForNumeric($receipt_id,$vehicle_id))
		{
		
			$result=updateReceipt($receipt_id,$amount,$trans_date,$to_ledger,$from_ledger,$remarks);
			updateAutoIdForReceiptId($receipt_id,$vehicle_id);
			return $result;
		}
	
	return "error";
}

function deleteReceiptForCustomerIdFinancer($receipt_id)
{
	
	if(checkForNumeric($receipt_id))
		{
		
			$jv=getJVForFinancerReceiptId($receipt_id);
			removeJV($jv['jv_id']);
			deleteReceipt($receipt_id);
			return "success";
		}
	
	return "error";
}


function getReceiptsForJobCardId($job_card_id)
{
	if(checkForNumeric($job_card_id))
	{
		$sql="SELECT receipt_id,receipt_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,created_by,last_updated_by,date_added,date_modified,remarks,parent_id
			  FROM edms_ac_receipt
			  WHERE auto_id=$job_card_id AND auto_rasid_type=3";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return "error"; 	  
		
	}
}

function getReceiptsForSalesId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$sql="SELECT receipt_id,receipt_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,created_by,last_updated_by,date_added,date_modified,remarks,parent_id
			  FROM edms_ac_receipt
			  WHERE auto_id=$sales_id AND auto_rasid_type=5";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return "error"; 	  
		
	}
}

function getReceiptsForVehicleId($job_card_id)
{
	if(checkForNumeric($job_card_id))
	{
		$sql="SELECT receipt_id,receipt_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,created_by,last_updated_by,date_added,date_modified,remarks,parent_id
			  FROM edms_ac_receipt
			  WHERE auto_id=$job_card_id AND auto_rasid_type=4";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return "error"; 	  
		
	}
}

function getReceiptsForVehicleForCustomerId($customer_id)
{
	if(checkForNumeric($customer_id))
	{
		$vehicle_ids_array = listVehicleIdsForCustomer($customer_id);
		if(!$vehicle_ids_array)
		return "error";
		
		$vehicle_ids_string = implode(",",$vehicle_ids_array);
		$sql="SELECT receipt_id,receipt_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,created_by,last_updated_by,date_added,date_modified,remarks,parent_id
			  FROM edms_ac_receipt
			  WHERE auto_id IN ($vehicle_ids_string) AND auto_rasid_type=4";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return "error"; 	  
		
	}
}

function getReceiptsForUchakCustomerId($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT receipt_id,receipt_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,created_by,last_updated_by,date_added,date_modified,remarks,parent_id
			  FROM edms_ac_receipt
			  WHERE auto_id=$id AND auto_rasid_type=1";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return "error"; 	  
		
	}
}

function getReceiptsForCustomerId($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT receipt_id,receipt_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,created_by,last_updated_by,date_added,date_modified,remarks,parent_id
			  FROM edms_ac_receipt
			  WHERE auto_id=$id AND (auto_rasid_type=1 || auto_rasid_type=2)";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return "error"; 	  
		
	}
}



function getReceiptsForFinancerCustomerId($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT receipt_id,receipt_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,created_by,last_updated_by,date_added,date_modified,remarks,parent_id
			  FROM edms_ac_receipt
			  WHERE auto_id=$id AND auto_rasid_type=2";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return "error"; 	  
		
	}
}

function getReceiptAmountForJobCardId($job_card_id)
{
	if(checkForNumeric($job_card_id))
	{
		$sql="SELECT SUM(edms_ac_receipt.amount) as total_amount, SUM(edms_ac_jv.amount) as total_kasar
			  FROM edms_ac_receipt LEFT JOIN edms_ac_jv ON (edms_ac_jv.auto_id = receipt_id AND edms_ac_jv.auto_rasid_type=7)
			  WHERE edms_ac_receipt.auto_id=$job_card_id AND edms_ac_receipt.auto_rasid_type=3 GROUP BY edms_ac_receipt.auto_id";
			 
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		 if(checkForNumeric($resultArray[0][1]))
		return $resultArray[0][0] + $resultArray[0][1];
		if(checkForNumeric($resultArray[0][0]))
		return $resultArray[0][0];
	
		}
		else
		return 0; 	  
		
	}
}

function getReceiptAmountAndKasarAmountForJobCardId($job_card_id,$from=null,$to=null)
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
	
	if(checkForNumeric($job_card_id))
	{
		$sql="SELECT SUM(edms_ac_receipt.amount) as total_amount, SUM(edms_ac_jv.amount) as total_kasar
			  FROM edms_ac_receipt LEFT JOIN edms_ac_jv ON (edms_ac_jv.auto_id = receipt_id AND edms_ac_jv.auto_rasid_type=7)
			  WHERE edms_ac_receipt.auto_id=$job_card_id AND edms_ac_receipt.auto_rasid_type=3 ";
			 
			 	if(isset($from) && validateForNull($from))
	$sql=$sql." AND edms_ac_receipt.trans_date >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND edms_ac_receipt.trans_date<='$to' ";
	 $sql=$sql." GROUP BY edms_ac_receipt.auto_id";
	 
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		 if(checkForNumeric($resultArray[0][1]))
		return array($resultArray[0][0], $resultArray[0][1]);
		if(checkForNumeric($resultArray[0][0]))
		return array($resultArray[0][0],0);
	
		}
		else
		return 0; 	  
		
	}
}


function getReceiptAmountForSalesId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$sale = getSaleById($sales_id);
		$kasar_ledger_id=getKasarLedgerIdForOC($sale['oc_id']);
		
		$sql="SELECT SUM(edms_ac_receipt.amount) as total_amount, SUM(edms_ac_jv.amount) as total_kasar
			  FROM edms_ac_receipt LEFT JOIN edms_ac_jv ON (edms_ac_jv.auto_id = receipt_id AND edms_ac_jv.auto_rasid_type=7)
			  WHERE edms_ac_receipt.auto_id=$sales_id AND edms_ac_receipt.auto_rasid_type=5 GROUP BY edms_ac_receipt.auto_id";
			  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		{
		 if(checkForNumeric($resultArray[0][1]))
		return $resultArray[0][0] + $resultArray[0][1];
		if(checkForNumeric($resultArray[0][0]))
		return $resultArray[0][0];	
			}
		else
		return 0; 	  
		
	}
}

function getReceiptAndKasarAmountForSalesId($sales_id,$from=null,$to=null)
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
	if(checkForNumeric($sales_id))
	{
		$sql="SELECT SUM(edms_ac_receipt.amount) as total_amount, SUM(edms_ac_jv.amount) as total_kasar
			  FROM edms_ac_receipt LEFT JOIN edms_ac_jv ON (edms_ac_jv.auto_id = receipt_id AND edms_ac_jv.auto_rasid_type=7)
			  WHERE edms_ac_receipt.auto_id=$sales_id AND edms_ac_receipt.auto_rasid_type=5 ";
			   	if(isset($from) && validateForNull($from))
	$sql=$sql." AND edms_ac_receipt.trans_date >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND edms_ac_receipt.trans_date<='$to' ";
	 $sql=$sql." GROUP BY edms_ac_receipt.auto_id";
			  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		 if(checkForNumeric($resultArray[0][1]))
		return array($resultArray[0][0] , $resultArray[0][1]);
		if(checkForNumeric($resultArray[0][0]))
		return array($resultArray[0][0],0);	
			}
		else
		return 0; 	  
		
	}
}

function getReceiptAmountForUchakCustomerId($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT SUM(amount) as total_amount
			  FROM edms_ac_receipt
			  WHERE auto_id=$id AND auto_rasid_type=1 GROUP BY auto_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return 0; 	  
		
	}
}


function getReceiptForFileClosureId($id)
{
	
	if(checkForNumeric($id))
	{
		$sql="SELECT receipt_id,receipt_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_receipt
			  WHERE auto_id=$id AND auto_rasid_type=4";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return "error"; 
		
		}
	}		

function getReceiptForPenaltyId($id)
{
	
	if(checkForNumeric($id))
	{
		$sql="SELECT receipt_id,receipt_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_receipt
			  WHERE auto_id=$id AND auto_rasid_type=3";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return "error"; 
		
		}
}		
	
function getReceiptById($id)
{

	if(checkForNumeric($id))
	{
		$id = getParentIdForReceiptId($id);
		
		$sql="SELECT receipt_id,receipt_ref,receipt_ref_type,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,created_by,last_updated_by,date_added,date_modified,remarks,parent_id
			  FROM edms_ac_receipt
			  WHERE receipt_id=$id OR parent_id=$id";
		
		$result=dbQuery($sql);
		  
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)==1)
		return $resultArray[0];
		else if(dbNumRows($result)>1)
		{
		return $resultArray;
		}
		else
		return "error"; 
		
		}
}

function getReceiptIdsForParentReceiptId($parent_id)
{
	if(checkForNumeric($parent_id))
	{
		$sql="SELECT GROUP_CONCAT(receipt_id) FROM edms_ac_receipt WHERE parent_id = $parent_id GROUP BY parent_id ";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)==1)
		return $resultArray[0][0];
		else
		return "error"; 
	}
}

function getParentIdForReceiptId($id)
{
	
	if(checkForNumeric($id))
	{
		$sql="SELECT parent_id
			  FROM edms_ac_receipt
			  WHERE receipt_id=$id";
			
			  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)==1)
		return $resultArray[0][0];
		else
		return "error"; 
	}
}
	

function insertReceipt($amount,$trans_date,$to_ledger,$from_ledger,$remarks,$auto_rasid_type=0,$auto_id=0,$receipt_ref="NA",$receipt_ref_type=0,$oc_id=NULL,$payment_mode_id=-1,$chq_date="01/01/1970",$chq_no="000000",$bank_name="NA",$branch_name="NA",$parent_id=0,$vch_no=NULL) // $to_ledger should start with C for customer or L for ledger to_ledger: credit, from_ledger: debit
{
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$current_company=getCompanyForLedger($to_ledger);
				if($current_company[1]==0)
				{
					if(!is_numeric($oc_id))
					$oc_id = $current_company[0];
					$accounts_settings=getAccountsSettingsForOC($oc_id);
				}
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		
		$customer=getCustomerDetailsByCustomerId($to_customer);
		$customer_id=$customer['customer_id'];
		if(!is_numeric($oc_id))
		$oc_id=getCompanyIdFromCustomerId($customer_id);
		
	
		$accounts_settings=getAccountsSettingsForOC($oc_id);
				
		
		}	
		
	if( (!(checkForNumeric($to_ledger) || checkForNumeric($to_customer))) || !checkForNumeric($oc_id) )
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
	
	$bank_accounts_head_id=getBankAccountsHeadId();
	$to_ledger_array=getLedgerById($from_ledger);
	$to_ledger_head_id = $to_ledger_array['head_id'];
	
	if($receipt_ref_type==2 && checkForNumeric($receipt_ref) && $receipt_ref>0)
	{
			$auto_rasid_type=5;
			$auto_id=$receipt_ref;	
			$sales = getSaleById($auto_id);	
	        $receipt_amount = getReceiptAmountForSalesId($auto_id);
			$tax_amount = getTotalTaxForSalesId($auto_id);
			$total_amount = $sales['amount'] + $tax_amount;
			$remaining_amount = $total_amount - $receipt_amount;
			if($amount>$remaining_amount)
			return "sales_against_amount_greater_error";	
	}
	else if($receipt_ref_type==2 && !is_numeric($receipt_ref) && $receipt_ref<1)
	return "sales_against_selection_error";	
	
	$vch_counter = getTransCounterForOCID($oc_id,6);
	if(!checkForNumeric($vch_no))
	{
	$vch_no = $vch_counter;	
	}
	
	if(checkForNumeric($amount,$from_ledger,$admin_id,$vch_no) && $from_ledger>0 && validateForNull($trans_date) && (($receipt_ref_type==2 && $receipt_ref>0) || $receipt_ref_type!=2) )
	{
		
			$sql="INSERT INTO edms_ac_receipt (receipt_ref_type,receipt_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified,parent_id,vch_no)
			VALUES ($receipt_ref_type,'$receipt_ref',$amount,$from_ledger,$to_ledger,$to_customer,$oc_id,$auto_rasid_type,$auto_id,'$trans_date','$remarks',$admin_id,$admin_id,NOW(),NOW(),$parent_id,'$vch_no')";
			
			$result=dbQuery($sql);
			
			$receipt_id =dbInsertId();
			if($parent_id==0)
			{
			$sql="UPDATE edms_ac_receipt SET parent_id = $receipt_id WHERE receipt_id = $receipt_id";
			dbQuery($sql);
			}
			
			if($vch_counter==$vch_no)
			incrementTransCounterForOCID($oc_id,6);
			
			$ac_starting_date = $accounts_settings['ac_starting_date'];
			if($bank_accounts_head_id==$to_ledger_head_id)
			{
				addReceiptDetails($receipt_id,$payment_mode_id,$chq_date,$chq_no,$bank_name,$branch_name);
			}
			if(strtotime($trans_date)>=strtotime($ac_starting_date))
			{
				
				if(checkForNumeric($to_ledger) && $to_ledger>0)
				{
					creditAccountingLedger($to_ledger,$amount);
					debitAccountingLedger($from_ledger,$amount);
				}
				else if(checkForNumeric($to_customer) && $to_customer>0)
				{
					
					debitAccountingLedger($from_ledger,$amount);
					creditAccountingCustomer($to_customer,$amount);
				}
			}	
			
			
			return "success";
	}
	return "error";	
}

function checkforInputsMultiReceipt($amount_array,$trans_date,$to_ledger_id_array,$from_ledger,$receipt_ref_array,$receipt_ref_type_array)
{
	
	for($i=1;$i<count($to_ledger_id_array);$i++)
	{
		$amount = $amount_array[$i];
		$to_ledger_id = $to_ledger_id_array[$i];
		$receipt_ref = $receipt_ref_array[$i];
		$receipt_ref_type = $receipt_ref_type_array[$i];
		
		if(checkForNumeric($amount,$from_ledger,$receipt_ref_type) && $amount>0 && $from_ledger>0 && validateForNull($to_ledger_id,$trans_date) && (($receipt_ref_type==2 && checkForNumeric($receipt_ref)) || $receipt_ref_type!=2) )
		{
			return true;
		}
	}
	return false;
}


function insertMultiReceipt($amount_array,$trans_date,$to_ledger_array,$from_ledger,$remarks,$auto_rasid_type=0,$auto_id=0,$receipt_ref_array="NA",$receipt_ref_type_array=0,$oc_id=NULL,$payment_mode_id=-1,$chq_date="01/01/1970",$chq_no="000000",$bank_name="NA",$branch_name="NA",$vch_no=NULL) // $to_ledger should start with C for customer or L for ledger to_ledger: credit, from_ledger: debit
{
	
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	$to_ledger_id_array = convertFullLedgerNameToLedgerIDArray($to_ledger_array);	
	if(checkforInputsMultiReceipt($amount_array,$trans_date,$to_ledger_id_array,$from_ledger,$receipt_ref_array,$receipt_ref_type_array))
	{
		$vch_counter = getTransCounterForOCID($oc_id,6);
		if(!checkForNumeric($vch_no))
		{
		$vch_no = $vch_counter;	
		}
		for($i=1;$i<count($to_ledger_id_array);$i++)
		{
			if($i==1)
			$parent_id=0;
			if(!is_numeric($parent_id))
			$parent_id=0;
			
			
			
			$parent_id=insertReceiptReturnTypeRasidNo($amount_array[$i],$trans_date,$to_ledger_id_array[$i],$from_ledger
			,$remarks,0,0,$receipt_ref_array[$i],$receipt_ref_type_array[$i],$oc_id,$payment_mode_id,$chq_date,$chq_no,$bank_name,$branch_name,$parent_id,$vch_no);
			
		}
		if(is_numeric($parent_id) && $parent_id>0)
		{
		if($vch_counter==$vch_no)
		incrementTransCounterForOCID($oc_id,6);	
		return "success";
		}
	}
}

function updateMultiReceipt($receipt_id,$amount_array,$trans_date,$to_ledger_array,$from_ledger,$remarks,$auto_rasid_type=0,$auto_id=0,$receipt_ref_array="NA",$receipt_ref_type_array=0,$oc_id=NULL,$payment_mode_id=-1,$chq_date="01/01/1970",$chq_no="000000",$bank_name="NA",$branch_name="NA",$vch_no=NULL) // $to_ledger should start with C for customer or L for ledger to_ledger: credit, from_ledger: debit
{
	
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	$to_ledger_id_array = convertFullLedgerNameToLedgerIDArray($to_ledger_array);	
	
	if(checkForNumeric($receipt_id) && checkforInputsMultiReceipt($amount_array,$trans_date,$to_ledger_id_array,$from_ledger,$receipt_ref_array,$receipt_ref_type_array))
	{
		$receipt = getReceiptById($receipt_id);
		$vch_no = $receipt['vch_no'];
		if(!checkForNumeric($vch_no))
		$vch_no=0;
		$receipt_id = getParentIdForReceiptId($receipt_id);
		deleteReceipt($receipt_id);
		for($i=1;$i<count($to_ledger_id_array);$i++)
		{
			if($i==1)
			$parent_id=0;
			if(!is_numeric($parent_id))
			$parent_id=0;
		
			$new_parent_id=insertReceiptReturnTypeRasidNo($amount_array[$i],$trans_date,$to_ledger_id_array[$i],$from_ledger
			,$remarks,0,0,$receipt_ref_array[$i],$receipt_ref_type_array[$i],$oc_id,$payment_mode_id,$chq_date,$chq_no,$bank_name,$branch_name,$parent_id,$vch_no);
			
				if($parent_id==0)
				$parent_id = $new_parent_id;
			
		}
		if(is_numeric($parent_id) && $parent_id>0)
		return $parent_id;
	}
}

function insertReceiptReturnTypeRasidNo($amount,$trans_date,$to_ledger,$from_ledger,$remarks,$auto_rasid_type=0,$auto_id=0,$receipt_ref="NA",$receipt_ref_type=0,$oc_id=NULL,$payment_mode_id=-1,$chq_date="01/01/1970",$chq_no="000000",$bank_name="NA",$branch_name="NA",$parent_id=0,$vch_no=NULL) // $to_ledger should start with C for customer or L for ledger to_ledger: credit, from_ledger: debit
{
	
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$current_company=getCompanyForLedger($to_ledger);
				if($current_company[1]==0)
				{
					if(!is_numeric($oc_id))
					$oc_id = $current_company[0];
					$accounts_settings=getAccountsSettingsForOC($oc_id);
				}
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		
		$customer=getCustomerDetailsByCustomerId($to_customer);
		$customer_id=$customer['customer_id'];
		if(!is_numeric($oc_id))
		$oc_id=getCompanyIdFromCustomerId($customer_id);
		
					$accounts_settings=getAccountsSettingsForOC($oc_id);
				
		
		}	
		
	if( (!(checkForNumeric($to_ledger) || checkForNumeric($to_customer))) || !checkForNumeric($oc_id) )
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
	$bank_accounts_head_id=getBankAccountsHeadId();
	$to_ledger_array=getLedgerById($from_ledger);
	$to_ledger_head_id = $to_ledger_array['head_id'];	
		
	if($receipt_ref_type==2 && checkForNumeric($receipt_ref) && $receipt_ref>0)
	{
	        $auto_rasid_type=5;
	        $auto_id=$receipt_ref;	
			$sales = getSaleById($auto_id);	
	        $receipt_amount = getReceiptAmountForSalesId($auto_id);
			$tax_amount = getTotalTaxForSalesId($auto_id);
			$total_amount = $sales['amount'] + $tax_amount;
			
			$remaining_amount = $total_amount - $receipt_amount;
			
			if($amount>$remaining_amount)
			return "sales_against_amount_greater_error";	
	}
	else if($receipt_ref_type==2 && !is_numeric($receipt_ref) && $receipt_ref<1)
	return "sales_against_selection_error";		
	
	if(checkForNumeric($amount,$from_ledger,$admin_id,$vch_no) && $from_ledger>0 && validateForNull($trans_date))
	{
		
			$sql="INSERT INTO edms_ac_receipt (receipt_ref_type,receipt_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified,parent_id,vch_no)
			VALUES ($receipt_ref_type,'$receipt_ref',$amount,$from_ledger,$to_ledger,$to_customer,$oc_id,$auto_rasid_type,$auto_id,'$trans_date','$remarks',$admin_id,$admin_id,NOW(),NOW(),$parent_id,'$vch_no')";
			$result=dbQuery($sql);
			$receipt_id=dbInsertId();
			if($parent_id==0)
			{
			$sql="UPDATE edms_ac_receipt SET parent_id = $receipt_id WHERE receipt_id = $receipt_id";
			dbQuery($sql);
			}
			$ac_starting_date = $accounts_settings['ac_starting_date'];
			
			if($bank_accounts_head_id==$to_ledger_head_id)
			{
				addReceiptDetails($receipt_id,$payment_mode_id,$chq_date,$chq_no,$bank_name,$branch_name);
			}
			
			if(strtotime($trans_date)>=strtotime($ac_starting_date))
			{
				
				if(checkForNumeric($to_ledger) && $to_ledger>0)
				{
					creditAccountingLedger($to_ledger,$amount);
					debitAccountingLedger($from_ledger,$amount);
				}
				else if(checkForNumeric($to_customer) && $to_customer>0)
				{
					
					debitAccountingLedger($from_ledger,$amount);
					creditAccountingCustomer($to_customer,$amount);
				}
			}	
			
			
			return $receipt_id;
	}
	return "error";	
}


function deleteReceipt($id)
{
	if(checkForNumeric($id))
	{
		
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$old_payment=getReceiptById($id);
		$old_amount=$old_payment['amount'];
		$old_trans_date=$old_payment['trans_date'];
		$old_to_ledger_id=$old_payment['to_ledger_id'];
		$old_to_customer_id=$old_payment['to_customer_id'];
		$old_from_ledger_id=$old_payment['from_ledger_id'];
		
		
	$oc_id=$old_payment['oc_id'];
	
	 if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
		}
		
		$sql="DELETE FROM edms_ac_receipt where receipt_id=$id OR parent_id = $id";
		dbQuery($sql);
		
		$ac_starting_date = $accounts_settings['ac_starting_date'];
			
			if(strtotime($old_trans_date)>=strtotime($ac_starting_date))
			{
				
				if(checkForNumeric($old_from_ledger_id) && $old_from_ledger_id>0)
				{
					
					debitAccountingLedger($old_to_ledger_id,$old_amount);
					creditAccountingLedger($old_from_ledger_id,$old_amount);
				}
				else if(checkForNumeric($old_from_customer_id) && $old_from_customer_id>0)
				{
					
					debitAccountingCustomer($old_to_customer_id,$old_amount);
					creditAccountingLedger($old_from_ledger_id,$old_amount);
				}
			}	
		
		return "success";
		}
		return "error";
	}

function updateAutoRasidTypeForReceiptId($receipt_id,$auto_rasid_type)
{
	if(checkForNumeric($receipt_id,$auto_rasid_type))
	{
		$sql="UPDATE edms_ac_receipt SET auto_rasid_type = $auto_rasid_type WHERE receipt_id = $receipt_id ";
		dbQuery($sql);
		return true;
	}
	return false;
}

function updateAutoIdForReceiptId($receipt_id,$auto_id)
{
	if(checkForNumeric($receipt_id,$auto_id))
	{
		$sql="UPDATE edms_ac_receipt SET auto_id = $auto_id WHERE receipt_id = $receipt_id ";
		dbQuery($sql);
		return true;
	}
	return false;
}

function updateReceipt($id,$amount,$trans_date,$to_ledger,$from_ledger,$remarks,$receipt_ref="NA",$receipt_ref_type=0,$oc_id=NULL,$payment_mode_id=-1,$chq_date="01/01/1970",$chq_no="000000",$bank_name="NA",$branch_name="NA",$vch_no=NULL) // $to_ledger should start with C for customer or L for ledger
{
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	
	$old_payment=getReceiptById($id);
		$old_amount=$old_payment['amount'];
		$old_trans_date=$old_payment['trans_date'];
		$old_to_ledger_id=$old_payment['to_ledger_id'];
		$old_to_customer_id=$old_payment['to_customer_id'];
		$old_from_ledger_id=$old_payment['from_ledger_id'];
		
	if(!is_numeric($oc_id))	
	$oc_id=$old_payment['oc_id'];
	
	if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
		}
	
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
	if((!(checkForNumeric($to_ledger) || checkForNumeric($to_customer))) || !checkForNumeric($oc_id) )
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
	
	$bank_accounts_head_id=getBankAccountsHeadId();
	$to_ledger_array=getLedgerById($from_ledger);
	$to_ledger_head_id = $to_ledger_array['head_id'];
	
	
	if(checkForNumeric($amount,$from_ledger,$admin_id,$id) && validateForNull($trans_date))
	{
			
			$sql="UPDATE edms_ac_receipt SET receipt_ref_type=$receipt_ref_type, receipt_ref = '$receipt_ref', amount=$amount, from_ledger_id=$from_ledger, to_ledger_id=$to_ledger, to_customer_id=$to_customer, trans_date='$trans_date', remarks='$remarks', last_updated_by=$admin_id, date_modified=NOW()";
			if(checkForNumeric($vch_no))
			$sql=$sql.",vch_no = $vch_no";
			
			$sql=$sql."WHERE receipt_id=$id";
		
			$result=dbQuery($sql);
			
			$ac_starting_date = $accounts_settings['ac_starting_date'];
			
			if($bank_accounts_head_id==$to_ledger_head_id)
			{
			addReceiptDetails($id,$payment_mode_id,$chq_date,$chq_no,$bank_name,$branch_name);
			}
			else
			removeReceiptDetails($id);
			
			if(strtotime($old_trans_date)>=strtotime($ac_starting_date))
			{
				
				if(checkForNumeric($old_from_ledger_id) && $old_from_ledger_id>0)
				{
					
					debitAccountingLedger($old_to_ledger_id,$old_amount);
					creditAccountingLedger($old_from_ledger_id,$old_amount);
				}
				else if(checkForNumeric($old_from_customer_id) && $old_from_customer_id>0)
				{
					
					debitAccountingCustomer($old_to_customer_id,$old_amount);
					creditAccountingLedger($old_from_ledger_id,$old_amount);
				}
			}	
			
				if(strtotime($trans_date)>=strtotime($ac_starting_date))
			{
				
				if(checkForNumeric($to_ledger) && $to_ledger>0)
				{
					creditAccountingLedger($to_ledger,$amount);
					debitAccountingLedger($from_ledger,$amount);
				}
				else if(checkForNumeric($to_customer) && $to_customer>0)
				{
					
					debitAccountingLedger($from_ledger,$amount);
					creditAccountingCustomer($to_customer,$amount);
				}
			}	
			
			return "success";
	}
	return "error";	
	
	}	
	

function deleteReceiptForJobCard($receipt_id)
{
	if(checkForNumeric($receipt_id))
	{
		$kasar_jv=getKasarJvForSalesId($receipt_id);
		
		if(is_array($kasar_jv) && is_numeric($kasar_jv['jv_id']))
		removeJV($kasar_jv['jv_id']);
		
		deleteReceipt($receipt_id);		
		return "success";
	}
	return "error";
}

function deleteReceiptForSales($receipt_id)
{
	if(checkForNumeric($receipt_id))
	{
		$kasar_jv=getKasarJvForSalesId($receipt_id);
		if(is_array($kasar_jv) && is_numeric($kasar_jv['jv_id']))
		removeJV($kasar_jv['jv_id']);
		deleteReceipt($receipt_id);		
		return "success";
	}
}

function getReceiptsForLedgerIdMonthWise($to_ledger,$from=NULL,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{
	
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$head_type=getLedgerHeadType($to_ledger);
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
	if(checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger)) && $head_type!=3 && $head_type!=4)
	{
	$sql="SELECT receipt_id,SUM(amount),from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_receipt WHERE oc_id = $oc_id AND ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." from_ledger_id=$to_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==2))  
	$sql=$sql." to_ledger_id=$to_ledger";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$to_customer";
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
	
function getTotalReceiptAmountForLedgerIdUptoDate($to_ledger,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$head_type=getLedgerHeadType($to_ledger);
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
	if(checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger)) && $head_type!=3 && $head_type!=4)
	{
	$sql="SELECT receipt_id,SUM(amount),from_ledger_id,to_ledger_id,to_customer_id
			  FROM edms_ac_receipt WHERE oc_id = $oc_id AND ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." from_ledger_id=$to_ledger GROUP BY from_ledger_id";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==2))  
	$sql=$sql." to_ledger_id=$to_ledger GROUP BY to_ledger_id";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$to_customer GROUP BY to_customer_id";			  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];
	else
	return 0; 	
	}
	return 0;
	}	

function getReceiptsForLedgerIdForMonth($to_ledger,$month_id,$year,$from=NULL,$to=NULL)
{
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$head_type=getLedgerHeadType($to_ledger);
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
	if((checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger) && $head_type!=3 && $head_type!=4)) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT receipt_id,";
	if($head_type==0)
	$sql=$sql."SUM(amount) as amount ";
	else
	$sql=$sql."SUM(amount) as amount";
	$sql=$sql.",from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_receipt WHERE oc_id = $oc_id AND ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." from_ledger_id=$to_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==2))  
	$sql=$sql." to_ledger_id=$to_ledger";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$to_customer";
	$sql=$sql."  AND DATE_FORMAT(trans_date,'%c')=$month_id AND DATE_FORMAT(trans_date,'%Y') = $year";
	if( !isset($head_type))
	$sql=$sql."   GROUP BY parent_id,to_customer_id";	  
	else if($head_type==0)
	$sql=$sql." GROUP BY parent_id,from_ledger_id";	  
	else if($head_type>0 && !checkForNumeric($to_customer))
	$sql=$sql." GROUP BY parent_id,to_ledger_id";  			  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
	}	
	
function getTotalReceiptForLedgerIdForMonth($to_ledger,$month_id,$year,$from=NULL,$to=NULL)
{
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$head_type=getLedgerHeadType($to_ledger);
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
	if((checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger) && $head_type!=3 && $head_type!=4)) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT receipt_id,SUM(amount) as total_amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_receipt WHERE oc_id = $oc_id AND ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." from_ledger_id=$to_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==2))  
	$sql=$sql." to_ledger_id=$to_ledger";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$to_customer";
	
	$sql=$sql."  AND DATE_FORMAT(trans_date,'%c')=$month_id AND DATE_FORMAT(trans_date,'%Y') = $year GROUP BY month_year";	  			  
	$result=dbQuery($sql);
	
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];
	else
	return 0; 	
	}
	return 0;
	}		

function getReceiptsForLedgerIdBetweenDates($to_ledger,$from=NULL,$to=NULL)
{
	
	if($to_ledger!=-1)
	{
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$head_type=getLedgerHeadType($to_ledger);
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		
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
	if(checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger) && $head_type!=3 && $head_type!=4)  || $to_ledger==-1)
	{
	$sql="SELECT receipt_id,";
	if(isset($head_type) && $head_type==0)
	$sql=$sql."SUM(amount) as amount";
	else
	$sql=$sql."SUM(amount) as amount";
	$sql=$sql.",from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified, remarks
			  FROM edms_ac_receipt WHERE oc_id = $oc_id AND ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if($to_ledger!=-1)
	{	  
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==0)  	  
	$sql=$sql." from_ledger_id=$to_ledger GROUP BY parent_id,from_ledger_id ";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==2))  
	$sql=$sql." to_ledger_id=$to_ledger GROUP BY parent_id,to_ledger_id";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$to_customer GROUP BY parent_id,to_customer_id";
	}
	else
	{
	if(checkForNumeric($oc_id))
	$sql=$sql." oc_id IN (".$oc_id.") GROUP BY parent_id,from_ledger_id ";	  
	}  
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
	}				
?>