<div class="jvp"><?php if(isset($_SESSION['cJobCardWSalesReport']['agency_id']) && $_SESSION['cJobCardWSalesReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cJobCardWSalesReport']['agency_id']);  } ?></div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">JobCard Reports</h4>
<?php 
if(isset($_SESSION['ack']['msg']) && isset($_SESSION['ack']['type']))
{
	
	$msg=$_SESSION['ack']['msg'];
	$type=$_SESSION['ack']['type'];
	
	
		if($msg!=null && $msg!="" && $type>0)
		{
?>
<div class="alert no_print <?php if(isset($type) && $type>0 && $type<4) echo "alert-success"; else echo "alert-error" ?>">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <?php if(isset($type)  && $type>0 && $type<4) { ?> <strong>Success!</strong> <?php } else if(isset($type) && $type>3) { ?> <strong>Warning!</strong> <?php } ?> <?php echo $msg; ?>
</div>
<?php
		
		
		}
	if(isset($type) && $type>0)
		$_SESSION['ack']['type']=0;
	if($msg!="")
		$_SESSION['ack']['msg']=="";
}

?>
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=generateReport'; ?>" method="post" enctype="multipart/form-data" onsubmit="return submitOurCompany()">

<table class="insertTableStyling no_print">

<tr>
<td> From Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="from_date" id="from_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cJobCardWSalesReport']['from'])) echo $_SESSION['cJobCardWSalesReport']['from']; ?>"/>	
                 </td>
</tr>

<tr>
<td> To Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="to_date" id="to_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cJobCardWSalesReport']['to'])) echo $_SESSION['cJobCardWSalesReport']['to']; ?>"/>	
                 </td>
</tr>

<tr>
<td></td>
				<td>
				 <input type="submit" value="Generate" class="btn btn-warning"/>	
                </td>
</tr>

</table>

</form>

  
<hr class="firstTableFinishing" />
 

	
 <?php if(isset($_SESSION['cJobCardWSalesReport']['emi_array']))
{
	
	$emi_array=$_SESSION['cJobCardWSalesReport']['emi_array'];
		
		
	 ?>  
     <div class="no_print">  
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>     
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
         <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Name</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Job No</label> 
        
       <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Model</label> 
  		<input class="showCB" type="checkbox" id="5" checked="checked"   /><label class="showLabel" for="5">Reg No</label> 
        <input class="showCB" type="checkbox" id="6" checked="checked"   /><label class="showLabel" for="6">Amount</label> 
     <input class="showCB" type="checkbox" id="7" checked="checked"   /><label class="showLabel" for="7">Amount Received</label> 
        <input class="showCB" type="checkbox" id="8" checked="checked"   /><label class="showLabel" for="8">Amount Left</label> 
      
    </div>
    <table id="adminContentReport" class="adminContentTable no_print">
      <thead>
    	<tr>
        	  <th class="heading no_print">Print</th>
        	<th class="heading">No</th>
              <th class="heading">Name</th>
             <th class="heading">Type</th>
             <th class="heading">Date</th>
             <th class="heading">Reg No</th>
             <th class="heading">Amount</th>
             <th class="heading">Amount Received</th>
            <th class="heading">Amount Left</th>
            <th class="heading no_print btnCol" ></th>
             <th class="heading no_print btnCol" ></th>
          
           
        </tr>
    </thead>
    <tbody>
        
        <?php
	$total = 0;
	$paid =0;
	$kasar_total =0;
		$no=0;
		foreach($emi_array as $job_card)
		{
		
		if($job_card['type']=='JOB CARD')	
		{
			
		$vehicle_id = $job_card['vehicle_id'];
		$job_card_id = $job_card['auto_id'];
		
		$customer_id = getCustomerIDFromVehicleId($vehicle_id);
		$customer = getCustomerDetailsByCustomerId($customer_id);
		$vehicle = getVehicleById($vehicle_id);	
		$invoice_no = getFinalizeDetailsForJobCard($job_card_id);
		$total_amount  = getTotalAmountForJobCard($job_card_id);
		
		$receipt_amount = getReceiptAmountForJobCardId($job_card_id);
		$kasar = $receipt_amount[1];
		$receipt_amount=$receipt_amount[0];
		$total = $total + $total_amount;
		$paid = $paid + $receipt_amount;
		$kasar_total = $kasar_total + $kasar;
		 ?>
          <tr class="resultRow">
          		<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$no; ?>
            </td>
             <td><?php echo $customer['customer_name']; ?></td>
            <td><?php if($job_card['type']=="JOB CARD") echo "JOB CARD.(".$job_card['job_card_no'].")"; else echo "SALES"; ?>
            </td>
            <td><?php echo date('d/m/Y',strtotime($job_card['job_card_datetime'])); ?>
            </td>
           
            <td><?php if($job_card['type']=="JOB CARD")  echo $vehicle['vehicle_reg_no'];  else echo "-"; ?>
            </td>
             <td>Rs.<?php echo round($total_amount); ?>
            </td>
             <td align="center">Rs.<?php echo round($receipt_amount); ?>
             <br />
              <a href="<?php  echo WEB_ROOT.'admin/customer/vehicle/jobCard/receipt/index.php?view=allReceipts&id='.$job_card_id; ?>"><button style="width:120px;" title="View this entry" class="btn  btn-success"><span class="">View Payment</span></button></a>
            </td>
             <td align="center">Rs.<?php echo round($total_amount-$receipt_amount); ?>
             <br />
              <a href="<?php  echo WEB_ROOT.'admin/customer/vehicle/jobCard/receipt/index.php?&id='.$job_card_id; ?>"><button style="width:120px;" title="View this entry" class="btn  btn-warning"><span class="">Add Payment</span></button></a> 
            </td>
    			
            <td class="no_print">
            <?php if(validateForNull($invoice_no
			)) echo $invoice_no;
             ?>
             <a href="<?php if(!validateForNull($invoice_no
			)) echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=finalize&id='.$job_card_id; else echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=invoice&id='.$job_card_id; ?>"><button title="Finalize this entry" class="btn <?php if(!validateForNull($invoice_no
			)){ ?>btn-danger<?php }else { ?>btn-success<?php } ?>"><?php if(!validateForNull($invoice_no
			)) { ?>Finalize<?php } else { ?>Invoice<?php } ?></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=details&id='.$job_card_id ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
           
        </tr>
        <?php }
		else if ($job_card['type']=="SALES")
		{
			if($job_card['type']=="SALES")
		    $sales_id=$job_card['sales_id'];
			
			
			$sales=getSaleById($sales_id);
			$receipt_amount = getReceiptAndKasarAmountForSalesId($sales_id);
			$kasar = $receipt_amount[1];
			$receipt_amount=$receipt_amount[0];
			$tax_amount = getTotalTaxForSalesId($sales_id);
			
			if(is_numeric($sales['to_ledger_id']))
			{
				
			$ledger_type=getLedgerHeadType($sales['to_ledger_id']);
			
			if(is_numeric($ledger_type) && $ledger_type==0)
			{ 
		    $type =1;
			$kasar_payment=getKasarPaymentForCashSale($sales_id);
			$kasar = $kasar_payment['amount'];
			}
			else $type=0;
			}
			else
			$type=0;
			if($type==1)
			{
			$remaining_amount=0;
			$paid = $paid +$sales['amount'] + $tax_amount - $kasar;
			$kasar_total = $kasar_total + $kasar;
			}
			else
			{
		    $remaining_amount = $sales['amount'] + $tax_amount - $receipt_amount - $kasar;	
				$paid = $paid +$receipt_amount;
		
			$kasar_total = $kasar_total + $kasar;
			}
			$total = $total + $sales['amount'] + $tax_amount;
			
		?>
        <tr class="resultRow">
        <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$no; ?>
            </td>
           <td><?php if(is_numeric($job_card['to_ledger_id'])) echo $job_card['to_ledger_name']; else echo $job_card['customer_name']; ?>
            </td>
           <td><?php if($type==1) echo "CASH "; ?>SALES</td>
     
            <td><?php echo date('d/m/Y',strtotime($sales['trans_date'])); ?>
            </td>
            <td>-</td>
           
            <td><?php echo ($sales['amount']+$tax_amount)." Rs"; ?>
            </td>
            
          	 
             <td align="center">Rs.<?php if($type==1) echo $sales['amount'] + $tax_amount-$kasar;else { echo round($receipt_amount);} ?>
             <br />
             <?php if($type!=1 || !isset($type)) { ?>
              <a class="no_print" href="<?php  echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=allReceipts&id='.$sales_id; ?>"><button style="width:120px;" title="View this entry" class="btn  btn-success"><span class="">View Payment</span></button></a><?php } ?>
            </td>
           
             <td align="center" ><?php echo number_format($remaining_amount)." Rs"; ?>   
               <br />
             Kasar(<?php echo round($kasar); ?>)
             <br /> <?php if($type!=1 || !isset($type)) { ?> <a class="no_print" href="<?php echo WEB_ROOT.'admin/accounts/transactions/receipt/index.php?sales_id='.$sales['sales_id'] ?>"><button title="View this entry" class="btn viewBtn btn-warning">Add Payment</button></a> <?php } ?>
            </td>
       
             <td class="no_print"> <?php echo $sales['invoice_no']; ?> <?php if($type==1) { ?>  <a  href="<?php echo WEB_ROOT.'admin/accounts/transactions/cash_sale/index.php?view=invoice&id='.$sales['sales_id'] ?>"><button title="View this entry" class="btn viewBtn btn-success">Invoice</button></a> <?php }else{ ?> <a  href="<?php echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=invoice&id='.$sales['sales_id'] ?>"><button title="View this entry" class="btn viewBtn btn-success">Invoice</button></a><?php } ?>
            </td>
            <td class="no_print"> <?php if($type==1) { ?> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/cash_sale/index.php?view=details&id='.$sales['sales_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a> <?php }else { ?><a href="<?php echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=details&id='.$sales['sales_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a><?php } ?>            </td>
            
   
  
        </tr>
         <?php }} ?>
         </tbody>
    </table>
       <span class="Total">Total Amount : <?php echo $total; ?></span>
        <span class="Total" style="margin-left:20px;"> Amount Received: <?php echo $paid; ?></span>
     <span class="Total" style="margin-left:20px;"> Kasar: <?php echo $kasar_total; ?></span>
    <span class="Total" style="margin-left:20px;"> Amount Dues: <?php echo $total-$paid-$kasar_total; ?></span>
    </div>
     <table class="reportFiltersTable">
    <tr>
    	<td> From : <?php if(isset($_SESSION['cJobCardWSalesReport']['from']) && $_SESSION['cJobCardWSalesReport']['from']!="") echo $_SESSION['cJobCardWSalesReport']['from']; else echo "NA"; ?></td>
        <td> To : <?php if(isset($_SESSION['cJobCardWSalesReport']['to']) && $_SESSION['cJobCardWSalesReport']['to']!="") echo $_SESSION['cJobCardWSalesReport']['to']; else echo "NA"; ?></td>
    </tr>
    </table> 
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
   
<?php } ?>      
</div>
<div class="clearfix"></div>
