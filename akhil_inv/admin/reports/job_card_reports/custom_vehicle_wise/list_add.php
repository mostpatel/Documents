<div class="jvp"><?php if(isset($_SESSION['cJobCardReport']['agency_id']) && $_SESSION['cJobCardReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cJobCardReport']['agency_id']);  } ?></div>
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
				 <input autocomplete="off" type="text"  name="from_date" id="from_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cJobCardReport']['from'])) echo $_SESSION['cJobCardReport']['from']; ?>"/>	
                 </td>
</tr>

<tr>
<td> To Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="to_date" id="to_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cJobCardReport']['to'])) echo $_SESSION['cJobCardReport']['to']; ?>"/>	
                 </td>
</tr>

<tr>
<td>Vehicle Number : </td>
				<td>
				 <input type="text"  name="reg_no" id="reg_no" placeholder="Only Letters and numbers" autocomplete="off" value="<?php if(isset($_SESSION['cJobCardReport']['reg_no'])) echo $_SESSION['cJobCardReport']['reg_no']; ?>"/>	
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
 

	
 <?php if(isset($_SESSION['cJobCardReport']['emi_array']))
{
	
	$emi_array=$_SESSION['cJobCardReport']['emi_array'];
		
		
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
             <th class="heading">Job No</th>
             <th class="heading">Date</th>
             <th class="heading">Model</th>
             <th class="heading">Reg No</th>
             <th class="heading">Amount</th>
             <th class="heading">Amount Received</th>
            <th class="heading">Amount Left</th>
            <th class="heading no_print btnCol" ></th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
           
        </tr>
    </thead>
    <tbody>
        
        <?php
	
		$no=0;
		foreach($emi_array as $job_card)
		{
		$vehicle_id = $job_card['vehicle_id'];
		$vehicle = getVehicleById($vehicle_id);	
		$customer = getCustomerDetailsByCustomerId($job_card['customer_id']);
		$vehicle_model = getVehicleModelById($vehicle['model_id']);
		$invoice_no = getFinalizeDetailsForJobCard($job_card['job_card_id']);
		$receipt_amount = getReceiptAmountForJobCardId($job_card['job_card_id']);
		 ?>
          <tr class="resultRow">
          		<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$no; ?>
            </td>
             <td><?php echo $customer['customer_name']; ?></td>
            <td><?php echo $job_card['job_card_no']; ?>
            </td>
            <td><?php echo date('d/m/Y',strtotime($job_card['job_card_datetime'])); ?>
            </td>
            <td><?php echo $vehicle_model['model_name']; ?>
            </td>
            <td><?php echo $vehicle['vehicle_reg_no']; ?>
            </td>
             <td>Rs.<?php echo round($job_card['total_amount']); ?>
            </td>
             <td align="center">Rs.<?php echo round($receipt_amount); ?>
             <br />
              <a href="<?php  echo WEB_ROOT.'admin/customer/vehicle/jobCard/receipt/index.php?view=allReceipts&id='.$job_card['job_card_id']; ?>"><button style="width:120px;" title="View this entry" class="btn  btn-success"><span class="">View Payment</span></button></a>
            </td>
             <td align="center">Rs.<?php echo round($job_card['total_amount']-$receipt_amount); ?>
             <br />
              <a href="<?php  echo WEB_ROOT.'admin/customer/vehicle/jobCard/receipt/index.php?&id='.$job_card['job_card_id']; ?>"><button style="width:120px;" title="View this entry" class="btn  btn-warning"><span class="">Add Payment</span></button></a> 
            </td>
    
            <td class="no_print"> <a href="<?php if(!validateForNull($invoice_no
			)) echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=finalize&id='.$job_card['job_card_id']; else echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=invoice&id='.$job_card['job_card_id']; ?>"><button title="Finalize this entry" class="btn <?php if(!validateForNull($invoice_no
			)){ ?>btn-danger<?php }else { ?>btn-success<?php } ?>"><?php if(!validateForNull($invoice_no
			)) { ?>Finalize<?php } else { ?>Invoice<?php } ?></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=details&id='.$job_card['job_card_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=edit&id='.$job_card['job_card_id']; ?>"><button title="Edit this entry" class="btn "><span class="delete">E</span></button></a>
            </td>
        </tr>
         <?php }?>
         </tbody>
    </table>
    </div>
     <table class="reportFiltersTable">
    <tr>
    	<td> From : <?php if(isset($_SESSION['cJobCardReport']['from']) && $_SESSION['cJobCardReport']['from']!="") echo $_SESSION['cJobCardReport']['from']; else echo "NA"; ?></td>
        <td> To : <?php if(isset($_SESSION['cJobCardReport']['to']) && $_SESSION['cJobCardReport']['to']!="") echo $_SESSION['cJobCardReport']['to']; else echo "NA"; ?></td>
    </tr>
    </table> 
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
   
<?php } ?>      
</div>
<div class="clearfix"></div>
<script>
$( "#reg_no" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/reg_no.php',
                { term: request.term }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#reg_no" ).val(ui.item.label);
			return false;
		}
    });	

</script>
