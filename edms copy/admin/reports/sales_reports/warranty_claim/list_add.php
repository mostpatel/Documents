<div class="jvp"><?php if(isset($_SESSION['cWarrantyClaimReport']['agency_id']) && $_SESSION['cWarrantyClaimReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cWarrantyClaimReport']['agency_id']);  } ?></div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">WRC Reports</h4>
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
				 <input autocomplete="off" type="text"  name="from_date" id="from_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cWarrantyClaimReport']['to'])) echo $_SESSION['cWarrantyClaimReport']['to']; ?>"/>	
                 </td>
</tr>

<tr>
<td> To Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="to_date" id="to_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cWarrantyClaimReport']['to'])) echo $_SESSION['cWarrantyClaimReport']['to']; ?>"/>	
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
 <?php if(isset($_SESSION['cWarrantyClaimReport']['emi_array']))
{
	
	$emi_array=$_SESSION['cWarrantyClaimReport']['emi_array'];
		
		
	 ?>    
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>     
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Chasis No</label> 
       <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Date of Sale</label> 
  		<input class="showCB" type="checkbox" id="4" checked="checked"   /><label class="showLabel" for="4">Customer Name</label> 
        <input class="showCB" type="checkbox" id="5" checked="checked"   /><label class="showLabel" for="5">Address</label> 
     <input class="showCB" type="checkbox" id="6" checked="checked"   /><label class="showLabel" for="6">House No</label> 
        <input class="showCB" type="checkbox" id="7" checked="checked"   /><label class="showLabel" for="7">Postal Code</label> 
        <input class="showCB" type="checkbox" id="8" checked="checked"  /><label class="showLabel" for="8">City</label> 
        <input class="showCB" type="checkbox" id="9" checked="checked"  /><label class="showLabel" for="9">Region</label> 
        <input class="showCB" type="checkbox" id="10" checked="checked"  /><label class="showLabel" for="10">Country</label> 
        <input class="showCB" type="checkbox" id="11" checked="checked"  /><label class="showLabel" for="11">Mobile</label>
        <input class="showCB" type="checkbox" id="12" checked="checked"  /><label class="showLabel" for="12">OM No</label> 
    </div>
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        	<th class="heading">No</th>
            <th class="heading file">Claim No</th>
            <th class="heading">Chasis No</th>
            <th class="heading numeric">Engine No</th>
            <th class="heading numeric">Dealer Code</th>
             <th class="heading numeric">Date Of Sale</th>
            <th class="heading numeric">Failure Date</th>
            <th class="heading">Failure KM</th>
            <th class="heading">Part No</th>
              <th class="heading">Part Name</th>
              <th class="heading">Quantity</th>
             
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
      
        <?php
	
		$total=0;
		$total_emi_amount=0;
		$customer_id_array = array();
		foreach($emi_array as $emi)
		{
			
			
		 ?>
         
         <tr class="resultRow">
         	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><div style="page-break-inside:avoid;"><?php echo ++$i; ?></div></td>
            <td></td>
           <td><?php echo strtoupper($emi['vehicle_chasis_no']); ?></td>
               <td><?php echo strtoupper($emi['vehicle_engine_no']); ?></td>
            <td></td>
            <td><?php  if(validateForNull($emi['delivery_date'])) echo date('d.m.Y',strtotime($emi['delivery_date'])); else echo date('d.m.Y',strtotime($emi['vehicle_reg_date']));  ?></td>
            <td><?php echo date('d.m.Y',strtotime($emi['job_card_datetime']));; ?></td>
            <td><?php echo $emi['kms_covered']; ?></td>
              <td><?php echo strtoupper($emi['mfg_item_code']); ?></td>
              <td><?php echo strtoupper($emi['item_name']); ?></td>
               <td><?php echo strtoupper($emi['quantity']); ?></td>
           
             <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=details&id='.$emi['job_card_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
   
        </tr>
     
         <?php } }?>
            </tbody>
    </table>
    </div>
     <table class="reportFiltersTable">
    <tr>
    	<td> From : <?php if(isset($_SESSION['cWarrantyClaimReport']['from']) && $_SESSION['cWarrantyClaimReport']['from']!="") echo $_SESSION['cWarrantyClaimReport']['from']; else echo "NA"; ?></td>
        <td> To : <?php if(isset($_SESSION['cWarrantyClaimReport']['to']) && $_SESSION['cWarrantyClaimReport']['to']!="") echo $_SESSION['cWarrantyClaimReport']['to']; else echo "NA"; ?></td>
    </tr>
    </table> 
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
   
<?php  ?>      
</div>
<div class="clearfix"></div>
