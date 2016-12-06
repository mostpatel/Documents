<?php 
$branches = listBranches();
$truck_drivers=listTruckDrivers();
$admin_branches = getBranchesForAdminId($_SESSION['edmsAdminSession']['admin_id']);
 ?>
<div class="jvp"><?php if(isset($_SESSION['cTripReport']['agency_id']) && $_SESSION['cTripReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cTripReport']['agency_id']);  } ?></div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">General Trip Memo Reports</h4>
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
				 <input autocomplete="off" type="text"  name="start_date" id="start_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cTripReport']['from'])) echo $_SESSION['cTripReport']['from']; ?>" />	
                 </td>
</tr>


<tr>
<td>Up To LR Date  : </td>
				<td>
				 <input autocomplete="off" type="text"  name="end_date" id="end_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cTripReport']['to'])) echo $_SESSION['cTripReport']['to']; ?>"/>	
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
                    <option value="<?php echo $branch['branch_id']; ?>" <?php if($branch['ledger_id']==$_SESSION['cTripReport']['from_branch_ledger_id']) { ?> selected="selected" <?php } ?>><?php echo $branch['ledger_name']; ?></option>			
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
                    <option value="<?php echo $branch['ledger_id']; ?>" <?php if($branch['ledger_id']==$_SESSION['cTripReport']['to_branch_ledger_id']) { ?> selected="selected" <?php } ?>><?php echo $branch['ledger_name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select><span class="DateError customError">Please select a Branch!</span>
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
 <?php if(isset($_SESSION['cTripReport']['emi_array']))
{
	
	$emi_array=$_SESSION['cTripReport']['emi_array'];
		
		
	 ?>    
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>     
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Trip No</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Trip Date</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">From Branch</label> 
         <input class="showCB" type="checkbox" id="5" checked="checked"  /><label class="showLabel" for="5">To Branch</label> 
        <input class="showCB" type="checkbox" id="6" checked="checked"  /><label class="showLabel" for="6">LRs</label>  
        <input class="showCB" type="checkbox" id="7" checked="checked"  /><label class="showLabel" for="7">To Pay</label> 
         <input class="showCB" type="checkbox" id="8" checked="checked"  /><label class="showLabel" for="8">Paid</label> 
          <input class="showCB" type="checkbox" id="9" checked="checked"  /><label class="showLabel" for="9">To Be Billed</label> 
           <input class="showCB" type="checkbox" id="10" checked="checked"  /><label class="showLabel" for="10">Remarks</label> 
    </div>
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        	<th class="heading">No</th>
            <th class="heading">Trip No</th>
            <th class="heading date">Trip Date</th>
            <th class="heading">From Branch</th>
            <th class="heading">To Branch</th>
            <th class="heading">LRs</th>
            <th class="heading">To Pay</th>
            <th class="heading">Paid</th>
            <th class="heading">To Be Billed</th>
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
		
		foreach($untripped_lrs as $emi)
		{
			
		 ?>
         <tr class="resultRow">
         <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR[]" value="<?php echo $emi['lr_id']; ?>" /></td>
        	<td><?php echo ++$i; ?></td>
            <td><?php echo $emi['trip_memo_no']; ?></td>
             <td><?php echo date('d/m/Y',strtotime($emi['trip_date'])); ?></td>
              <td><?php  echo $emi['from_branch_ledger_name']; ?>
            </td>
             <td><?php  echo $emi['to_branch_ledger_name']; ?>
            </td>
           <td><?php echo $emi['lr_nos']; ?></td>
            <td><?php echo $emi['to_pay']; $to_pay = $to_pay + $emi['to_pay']; ?>
            </td>
            <td><?php echo $emi['paid']; $paid = $paid + $emi['paid'] ?>
            </td>
            <td><?php echo $emi['to_be_billed']; $to_be_billed = $to_be_billed + $emi['to_be_billed'] ?>
            </td>
            <td class="payment_amount"><?php   echo $emi['remarks'] ?>
            </td>
       
             <td class="no_print"> <a target="_blank" class="myLink" href="<?php echo WEB_ROOT.'admin/transportation/trip_memo/index.php?view=details&id='.$emi['trip_memo_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
           
            
          
  
        </tr>
         <?php }}  ?>
            </tbody>
    </table>
    </div>
     
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
   <span class="Total" style="display:block;position:relative;width:100%;">Total Amount : <?php  echo number_format($to_pay+$paid+$to_be_billed); ?></span>
    <span class="Total" style="display:block;position:relative;width:15%;">To Pay : <?php if(isset($to_pay)) echo number_format($to_pay); ?></span>
     <span class="Total" style="display:block;position:relative;width:15%;">Paid : <?php if(isset($paid)) echo number_format($paid); ?></span>
      <span class="Total" style="display:block;position:relative;width:15%;">To Be Billed : <?php if(isset($to_be_billed)) echo number_format($to_be_billed); ?></span>
<?php  ?>      
</div>
<div class="clearfix"></div>
