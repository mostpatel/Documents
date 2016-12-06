<?php
require_once('cg.php');
require_once('bd.php');
require_once('bd1.php');
require_once('backup.php');
require_once('vehicle-type-functions.php');
require_once('loan-functions.php');
require_once('file-functions.php');
require_once('account-ledger-functions.php');
require_once('our-company-function.php');
require_once('phpExcel/PHPExcel/IOFactory.php');
set_time_limit(0); 

$srvRoot2  = str_replace('lib\restore_rasid.php', '', __FILE__);

define('RESTORE_ROOT', $srvRoot2);
function restoreRasid($file)
{

global $dbHost, $dbUser, $dbPass, $dbName;

if(isset($file['sqlFile']['tmp_name']) && trim($file['sqlFile']['tmp_name'])!="")	
{

  if (trim($file['sqlFile']['tmp_name']) != '') 
  {
        // get the image extension
        $ext = substr(strrchr($file['sqlFile']['name'], "."), 1); 
 	}
 
if($ext=="xlsx")
{  

backup_tables('*','restore_checkpoint');
		
$filename="restoreRasid".date('d_m_Y_H_i_s').".xlsx";
 move_uploaded_file($file['sqlFile']['tmp_name'],RESTORE_ROOT."excels\\".$file['sqlFile']['name']);
$inputFileName = RESTORE_ROOT."excels\\".$file['sqlFile']['name'];

//  Read your Excel workbook
try {
    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);
} catch(Exception $e) {
    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
}

//  Get worksheet dimensions
$sheet = $objPHPExcel->getSheet(0); 
$highestRow = $sheet->getHighestRow(); 
$highestColumn = $sheet->getHighestColumn();
$rowData=array();
$files_array = array();

//  Loop through each row of the worksheet in turn
for ($row = 1,$file_counter=0; $row <= $highestRow; $row++){ 
    //  Read a row of data into an array
    $rowDat = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);
   
	if($rowDat[0][0]=="loan_id")
	{
	$heading_row=$rowDat[0];
	$type=1;
	}
	else if($rowDat[0][0]=="penalty_id")
	{
	$heading_row=$rowDat[0];
	$type=2;
	$rasid=NULL;
	}
	else if($rowDat[0][0]=="file_closed_id")
	{	
	
	$heading_row=$rowDat[0];
	$type=3;
	$rasid=NULL;
	}
	else if(is_numeric($rowDat[0][0]))
	$rasid = $rowDat[0];
	else 
	$rasid=NULL;
	
	if(isset($rasid) && $type==1)
	{
		$rasid_array = array();
		
		for($i=0;$i<count($rasid);$i++)
		{
			$rasid_array[$heading_row[$i]]=$rasid[$i];			
		}
		
	   $file_id = getFileIdFromFileNo($rasid_array['file_number']);
	   $file_id1= getFileIdFromLoanId($rasid_array['loan_id']);
	   
	   $ledger_id = $rasid_array['ledger_id'];
	  
	   if($ledger_id!=0)
	   {
		$ledger_name = $rasid_array['ledger_name'];   
		if(strcmp($ledger_name,getLedgerNameFromLedgerId($ledger_id)))
		$ledger_error=true;
		else
		$ledger_error=false;   
	   }
	   else
	   $ledger_error=false;
	   if($file_id==$file_id1 && !$ledger_error)
	   {
		  
		   $agency_array=getAgencyOrCompanyIdFromFileId($file_id);
		
		if($agency_array[0]=="agency")
		{
		$agency_id=$agency_array[1];
		$our_company_id="NULL";
		$prefix=getAgencyPrefixFromAgencyId($agency_id);
		}
		else if($agency_array[0]=="oc")
		{
		$our_company_id=$agency_array[1];
		$agency_id="NULL";	
		$prefix=getPrefixFromOCId($our_company_id);
		}   
		$rasid_no = $rasid_array['rasid_no'];
		$rasid_number = str_replace($prefix,"",$rasid_no);
		
	
		  insertPayment(getOldestUnPaidEmi($rasid_array['loan_id']),$rasid_array['payment_amount'],$rasid_array['payment_mode'],date('d/m/Y',strtotime($rasid_array['payment_date'])),$rasid_number,$rasid_array['remarks'],date('d/m/Y',strtotime($rasid_array['remainder_date'])),$rasid_array['bank_name'],$rasid_array['branch_name'],$rasid_array['cheque_no'],date('d/m/Y',strtotime($rasid_array['cheque_date'])),$rasid_array['cheque_return'],$rasid_array['ledger_id'],$rasid_array['paid_by'],0);
		  }
	}
	
	if(isset($rasid) && $type==2)
	{
		$rasid_array = array();
		
		for($i=0;$i<count($rasid);$i++)
		{
			$rasid_array[$heading_row[$i]]=$rasid[$i];			
		}
		
	   $file_id = getFileIdFromFileNo($rasid_array['file_number']);
	   $file_id1= getFileIdFromLoanId($rasid_array['loan_id']);
	   
	   $ledger_id = $rasid_array['ledger_id'];
	   if($ledger_id!=0)
	   {
		$ledger_name = $rasid_array['ledger_name'];   
		if(strcmp($ledger_name,getLedgerNameFromLedgerId($ledger_id)))
		$ledger_error=true;
		else
		$ledger_error=false;   
	   }
	   else
	   $ledger_error=false;
	   if($file_id==$file_id1 && !$ledger_error)
	   {
		$agency_array=getAgencyOrCompanyIdFromFileId($file_id);
		
		if($agency_array[0]=="agency")
		{
		$agency_id=$agency_array[1];
		$our_company_id="NULL";
		$prefix=getAgencyPrefixFromAgencyId($agency_id);
		}
		else if($agency_array[0]=="oc")
		{
		$our_company_id=$agency_array[1];
		$agency_id="NULL";	
		$prefix=getPrefixFromOCId($our_company_id);
		}   
		$rasid_no = $rasid_array['rasid_no'];
		$rasid_number = str_replace($prefix,"",$rasid_no);
		if(!is_numeric($rasid_number) || $rasid_number=="")
		$rasid_number=="";
		
  addPenaltyToLoan($rasid_array['days_paid'],date('d/m/Y',strtotime($rasid_array['paid_date'])),$rasid_array['payment_mode'],$rasid_array['amount_per_day'],$rasid_number,$rasid_array['paid_by'],$rasid_array['loan_id'],$file_id,$rasid_array['total_amount'],$rasid_array['rasid_type_id'],$rasid_array['paid'],$rasid_array['bank_name'],$rasid_array['branch_name'],$rasid_array['cheque_no'],date('d/m/Y',strtotime($rasid_array['cheque_date'])),$rasid_array['ledger_id'],0);
		  }
	}
	
	if(isset($rasid) && $type==3)
	{
		$rasid_array = array();
		
		for($i=0;$i<count($rasid);$i++)
		{
			$rasid_array[$heading_row[$i]]=$rasid[$i];			
		}
		
	   $file_id = getFileIdFromFileNo($rasid_array['file_number']);
	   $file_id1= $rasid_array['file_id'];
	   
	   $ledger_id = $rasid_array['ledger_id'];
	   if($ledger_id!=0)
	   {
		$ledger_name = $rasid_array['ledger_name'];   
		if(strcmp($ledger_name,getLedgerNameFromLedgerId($ledger_id)))
		$ledger_error=true;
		else
		$ledger_error=false;   
	   }
	   else
	   $ledger_error=false;
	   if($file_id==$file_id1 && !$ledger_error)
	   {
		   $agency_array=getAgencyOrCompanyIdFromFileId($file_id);
		
		if($agency_array[0]=="agency")
		{
		$agency_id=$agency_array[1];
		$our_company_id="NULL";
		$prefix=getAgencyPrefixFromAgencyId($agency_id);
		}
		else if($agency_array[0]=="oc")
		{
		$our_company_id=$agency_array[1];
		$agency_id="NULL";	
		$prefix=getPrefixFromOCId($our_company_id);
		}   
		$rasid_no = $rasid_array['rasid_no'];
		$rasid_number = str_replace($prefix,"",$rasid_no);
		if(!is_numeric($rasid_number))
		$rasid_number=="";
		 
		  closeFile(date('d/m/Y',strtotime($rasid_array['file_close_date'])),$rasid_array['amount_paid'],$file_id,$rasid_array['mode'],$rasid_number,$rasid_array['remarks'],$rasid_array['bank_name'],$rasid_array['branch_name'],$rasid_array['cheque_no'],date('d/m/Y',strtotime($rasid_array['cheque_date'])),$rasid_array['ledger_id'],0);
		  }
	}
}

return "success";		
}

}

return "success";
}
 
?>