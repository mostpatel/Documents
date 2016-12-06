<?php 
$branches = listBranches();
$truck_drivers=listTruckDrivers();
$admin_branches = getBranchesForAdminId($_SESSION['edmsAdminSession']['admin_id']);
 ?>
<div class="jvp"><?php if(isset($_SESSION['cLRTaxReport']['agency_id']) && $_SESSION['cLRTaxReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cLRTaxReport']['agency_id']);  } ?></div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">General Tax Reports</h4>
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
<td>From LR Date  : </td>
				<td>
				 <input autocomplete="off" type="text"  name="start_date" id="start_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cLRTaxReport']['from'])) echo $_SESSION['cLRTaxReport']['from']; ?>" />	
                 </td>
</tr>


<tr>
<td>Up To LR Date  : </td>
				<td>
				 <input autocomplete="off" type="text"  name="end_date" id="end_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cLRTaxReport']['to'])) echo $_SESSION['cLRTaxReport']['to']; ?>"/>	
                 </td>
</tr>
<tr>
<td>From Branch<span class="requiredField">* </span> : </td>
				<td>
					 <select id="from_branch_ledger_id"  name="from_branch_ledger_id" >
                       <?php if(count($admin_branches)>1) { ?>
                    	<option value="-1" selected="selected">-- Please Select --</option>
                        <?php } ?>
                    	
                    <?php
					
					foreach($admin_branches as $branch)
					{
					?>
                    <option value="<?php echo $branch['branch_id']; ?>" <?php if($branch['ledger_id']==$_SESSION['cLRTaxReport']['from_branch_ledger_id']) { ?> selected="selected" <?php } ?>><?php echo $branch['ledger_name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select><span class="DateError customError">Please select a Branch!</span>
                            </td>
</tr>
<tr>
<td>To Branch<span class="requiredField">* </span> : </td>
				<td>
					 <select id="to_branch_ledger_id"  name="to_branch_ledger_id" >
                    	<option value="-1" selected="selected">-- Please Select --</option>
                    <?php
					
					foreach($branches as $branch)
					{
					?>
                    <option value="<?php echo $branch['ledger_id']; ?>" <?php if($branch['ledger_id']==$_SESSION['cLRTaxReport']['to_branch_ledger_id']) { ?> selected="selected" <?php } ?>><?php echo $branch['ledger_name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select><span class="DateError customError">Please select a Branch!</span>
                            </td>
</tr>

<td>Tax Type : </td>
				<td>
					<select name="lr_type[]" class="city_area selectpicker" multiple="multiple"  id="city_area1" >
                    	 <option value="-1" >--Please Select--</option>
                         <option value="1" <?php if(in_array(1,$_SESSION['cLRTaxReport']['area_id_array'])){ ?> selected="selected" <?php } ?> >Consignee</option>
                         <option value="2" <?php if(in_array(2,$_SESSION['cLRTaxReport']['area_id_array'])){ ?> selected="selected" <?php } ?>>Consigner</option>
                         <option value="3" <?php if(in_array(3,$_SESSION['cLRTaxReport']['area_id_array'])){ ?> selected="selected" <?php } ?>>Transporter</option>
                    </select>
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
 

	<div class="no_print">
 <?php if(isset($_SESSION['cLRTaxReport']['emi_array']))
{
	
	$emi_array=$_SESSION['cLRTaxReport']['emi_array'];
		
		
	 ?>    
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>     
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Lr No</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">LR Date</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">From Branch</label> 
         <input class="showCB" type="checkbox" id="5" checked="checked"  /><label class="showLabel" for="5">To Branch</label> 
        <input class="showCB" type="checkbox" id="6" checked="checked"   /><label class="showLabel" for="6">From Customer</label> 
        <input class="showCB" type="checkbox" id="7" checked="checked"  /><label class="showLabel" for="7">To Customer</label> 
         <input class="showCB" type="checkbox" id="8" checked="checked"  /><label class="showLabel" for="8">Total Freight</label> 
        <input class="showCB" type="checkbox" id="9" checked="checked"  /><label class="showLabel" for="9">To Pay</label> 
         <input class="showCB" type="checkbox" id="10" checked="checked"  /><label class="showLabel" for="10">Paid</label> 
          <input class="showCB" type="checkbox" id="11" checked="checked"  /><label class="showLabel" for="11">To Be Billed</label> 
           <input class="showCB" type="checkbox" id="12" checked="checked"  /><label class="showLabel" for="12">Tax</label> 
          <input class="showCB" type="checkbox" id="12" checked="checked"  /><label class="showLabel" for="13">Tax Payer</label>  
           <input class="showCB" type="checkbox" id="13" checked="checked"  /><label class="showLabel" for="14">Remarks</label> 
    </div>
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        	<th class="heading">No</th>
            <th class="heading">LR No</th>
            <th class="heading date">LR Date</th>
            <th class="heading">From Branch</th>
            <th class="heading">To Branch</th>
            <th class="heading">From Customer</th>
            <th class="heading">To Customer</th>
            <th class="heading">Total Freight</th>
            <th class="heading">To Pay</th>
            <th class="heading">Paid</th>
            <th class="heading">To Be Billed</th>
            <th class="heading">Tax</th>
            <th class="heading">Tax Payer</th> 
            <th class="heading">Remarks</th>
            <th class="heading no_print btnCol"></th>
           
        </tr>
    </thead>
    <tbody>
      
         <?php
		$total=0;
		$to_pay=0;
		$paid = 0;
		$to_be_billed = 0;
		$untripped_lrs=$emi_array;
		$tax=0;
		foreach($untripped_lrs as $emi)
		{
			
		 ?>
         <tr class="resultRow">
         <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR[]" value="<?php echo $emi['lr_id']; ?>" /></td>
        	<td><?php echo ++$i; ?></td>
            <td><?php echo $emi['lr_no']; ?></td>
             <td><?php echo date('d/m/Y',strtotime($emi['lr_date'])); ?></td>
              <td><?php  echo $emi['from_branch_ledger_name']; ?>
            </td>
             <td><?php  echo $emi['to_branch_ledger_name']; ?>
            </td>
             <td><?php  echo $emi['from_customer_name']; ?>
            </td>
             <td><?php  echo $emi['to_customer_name']; ?>
            </td>
            <td><?php echo $emi['total_freight']; $total = $total +  $emi['total_freight']; ?>
            </td>
            <td><?php echo $emi['to_pay']; if($emi['to_pay']>0) $to_pay = $to_pay + $emi['tax_amount']; ?>
            </td>
            <td><?php echo $emi['paid']; if($emi['paid']>0) $paid = $paid + $emi['tax_amount'] ?>
            </td>
            <td><?php echo $emi['to_be_billed']; if($emi['to_be_billed']>0) $to_be_billed = $to_be_billed + $emi['tax_amount'] ?>
            </td>
             <td><?php echo $emi['tax_amount']; $tax = $tax + $emi['tax_amount'] ?>
            </td>
            <td>
            <?php  if($emi['tax_pay_type']==1) echo "Consignee";
					else if($emi['tax_pay_type']==2) echo "Consignor";
					else if($emi['tax_pay_type']==3) echo "Transporter";
					else echo "Default"; ?>
            </td>
            <td class="payment_amount"><?php   echo $emi['remarks'] ?>
            </td>
       
             <td class="no_print"> <a target="_blank" class="myLink" href="<?php echo WEB_ROOT.'admin/transportation/lr/index.php?view=details&id='.$emi['lr_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
           
            
          
  
        </tr>
         <?php }}  ?>
            </tbody>
    </table>
    </div>
   
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
   <span class="Total" style="display:block;position:relative;width:100%;">Total Tax : <?php if(isset($tax)) echo number_format($tax); ?></span>
   <span class="Total" style="display:block;position:relative;width:100%;">Total Freight : <?php if(isset($total)) echo number_format($total); ?></span>
    <span class="Total" style="display:block;position:relative;width:15%;">To Pay Tax: <?php if(isset($to_pay)) echo number_format($to_pay); ?></span>
     <span class="Total" style="display:block;position:relative;width:15%;">Paid Tax: <?php if(isset($paid)) echo number_format($paid); ?></span>
      <span class="Total" style="display:block;position:relative;width:15%;">To Be Billed Tax: <?php if(isset($to_be_billed)) echo number_format($to_be_billed); ?></span>
<?php  ?>      
</div>
<div class="clearfix"></div>
