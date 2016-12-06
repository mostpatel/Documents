<div class="jvp"><?php if(isset($_SESSION['cWRCReport']['agency_id']) && $_SESSION['cWRCReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cWRCReport']['agency_id']);  } ?></div>
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
				 <input autocomplete="off" type="text"  name="from_date" id="from_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cWRCReport']['to'])) echo $_SESSION['cWRCReport']['to']; ?>"/>	
                 </td>
</tr>

<tr>
<td> To Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="to_date" id="to_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cWRCReport']['to'])) echo $_SESSION['cWRCReport']['to']; ?>"/>	
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
 <?php if(isset($_SESSION['cWRCReport']['emi_array']))
{
	
	$emi_array=$_SESSION['cWRCReport']['emi_array'];
		
		
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
            <th class="heading file">Chasis No</th>
            <th class="heading">Date of Sale</th>
            <th class="heading numeric">Customer Name</th>
            <th class="heading numeric">Address</th>
             <th class="heading numeric">House No</th>
            <th class="heading numeric">Postal Code</th>
            <th class="heading">City</th>
            <th class="heading">Region</th>
              <th class="heading">Country</th>
              <th class="heading">Mobile</th>
              <th class="heading">OM No</th>
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
           <td><?php echo strtoupper($emi['vehicle_chasis_no']); ?></td>
             
            
            <td><?php   echo date('d.m.Y',strtotime($emi['delivery_date'])); ?></td>
            <td><?php echo strtoupper($emi['customer_name']); ?></td>
              <td><?php echo strtoupper($emi['area_name']); ?></td>
              
            <td></td>
              <td></td>
                <td></td>
                  <td></td>
                    <td></td>
                      <td><?php echo $emi['contact_no']; ?></td>
            <td><?php   echo strtoupper($emi['service_book']); ?></td>
             <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/delivery_challan/index.php?view=details&id='.$emi['delivery_challan_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
   
        </tr>
     
         <?php } }?>
            </tbody>
    </table>
    </div>
     <table class="reportFiltersTable">
    <tr>
    	<td> From : <?php if(isset($_SESSION['cWRCReport']['from']) && $_SESSION['cWRCReport']['from']!="") echo $_SESSION['cWRCReport']['from']; else echo "NA"; ?></td>
        <td> To : <?php if(isset($_SESSION['cWRCReport']['to']) && $_SESSION['cWRCReport']['to']!="") echo $_SESSION['cWRCReport']['to']; else echo "NA"; ?></td>
    </tr>
    </table> 
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
   
<?php  ?>      
</div>
<div class="clearfix"></div>
