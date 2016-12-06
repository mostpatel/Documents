<div class="jvp"><?php if(isset($_SESSION['cToBCReport']['agency_id']) && $_SESSION['cToBCReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cToBCReport']['agency_id']);  } ?></div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Vehicle Outstanding Reports</h4>
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
<td>Up To Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="end_date" id="end_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cToBCReport']['to'])) echo $_SESSION['cToBCReport']['to']; ?>"/>	
                 </td>
</tr>

<tr>
<td>Customer Outstanding (>=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="customer_outstanding" id="customer_outstanding" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cToBCReport']['customer_outstanding'])) echo $_SESSION['cToBCReport']['customer_outstanding']; ?>"/>	
                 </td>
</tr>

<tr>
<td>City : </td>
				<td>
					<select id="customer_city_id" name="city_id" class="city"   onchange="createDropDownAreaReports(this.value)">
                        <option value="-1" >--Please Select--</option>
                        <?php
                            $cities = listCitiesAlpha();
                            foreach($cities as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['city_id'] ?>" <?php if(isset($_SESSION['cToBCReport']['city_id'])){ if( $super['city_id'] == $_SESSION['cToBCReport']['city_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['city_name'] ?></option					>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>
<td>Area : </td>
				<td>
					<select name="area[]" class="city_area selectpicker" multiple="multiple"  id="city_area1" >
                    	 <option value="-1" >--Please Select--</option>
                          <?php
						  if(isset($_SESSION['cToBCReport']['city_id'])){
                            $areas = listAreasFromCityIdWithGroups($_SESSION['cToBCReport']['city_id']);
                            foreach($areas as $area)
                              {
                             ?>
                             
                             <option value="<?php echo $area['area_id'] ?>" <?php if(isset($_SESSION['cToBCReport']['area_id_array'])){ if(in_array($area['area_id'],$_SESSION['cToBCReport']['area_id_array'])) { ?> selected="selected" <?php }} ?>><?php echo $area['area_name'] ?></option					>
                             <?php } 
						  }
							 ?>
                    </select>
                            </td>
</tr>

<tr>
<td>Broker Name : </td>
				<td>
					<select name="broker[]" class="broker selectpicker" multiple="multiple"  id="broker" >
                    	 <option value="-1" disabled="disabled">--Please Select--</option>
                          <?php
						 $brokers = listFinancersDealersBrokers();
						  
                          
                            foreach($brokers as $broker)
                              {
                             ?>
                             <option value="<?php echo $broker['ledger_id'] ?>" <?php if(isset($_SESSION['cToBCReport']['broker_id_array'])){ if(in_array($broker['ledger_id'],$_SESSION['cToBCReport']['broker_id_array'])) { ?> selected="selected" <?php }} ?>><?php echo $broker['ledger_name'] ?></option					>
                             <?php } 
						  
							 ?>
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
 <?php if(isset($_SESSION['cToBCReport']['emi_array']))
{
	
	$emi_array=$_SESSION['cToBCReport']['emi_array'];
		$outstanding_greater = $_SESSION['cToBCReport']['customer_outstanding'];
		if(!is_numeric($outstanding_greater))
		$outstanding_greater=0;
	 ?>    
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>     
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Reg No</label> 
       <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Model</label> 
  		<input class="showCB" type="checkbox" id="4" checked="checked"   /><label class="showLabel" for="4">Vehicle DR</label> 
        <input class="showCB" type="checkbox" id="5" checked="checked"   /><label class="showLabel" for="5">Vehicle CR</label> 
     <input class="showCB" type="checkbox" id="6" checked="checked"   /><label class="showLabel" for="6">Vehicle DR</label> 
        <input class="showCB" type="checkbox" id="7" checked="checked"   /><label class="showLabel" for="7">Vehicle CR</label> 
        <input class="showCB" type="checkbox" id="8" checked="checked"  /><label class="showLabel" for="8">Name</label> 
         <input class="showCB" type="checkbox" id="9" checked="checked"  /><label class="showLabel" for="9">Address</label> 
          <input class="showCB" type="checkbox" id="10" checked="checked"  /><label class="showLabel" for="10">Contact No</label> 
    </div>
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        	<th class="heading">No</th>
            <th class="heading file">Reg No</th>
            <th class="heading">Model</th>
            <th class="heading numeric">Vehicle DR</th>
            <th class="heading numeric">Vehcile CR</th>
             <th class="heading numeric">Total DR</th>
            <th class="heading numeric">Total CR</th>
             <th class="heading">Broker</th>
            <th class="heading">Name</th>
            <th width="10%" class="heading">Address</th>
            <th class="heading">Contact No</th>
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
			
			$total_vehcile_outstanding = 0;
			$total_customer_outstanding = 0;
			
			if(isset($emi['vehicle_opening_balance']) && is_numeric($emi['vehicle_opening_balance']))
			$total_vehcile_outstanding = $total_vehcile_outstanding + $emi['vehicle_opening_balance'];
			
			if(isset($emi['vehicle_jv_amount']) && is_numeric($emi['vehicle_jv_amount']))
			$total_vehcile_outstanding = $total_vehcile_outstanding + $emi['vehicle_jv_amount'];
			
			if(isset($emi['vehicle_receipt_amount']) && is_numeric($emi['vehicle_receipt_amount']))
			$total_vehcile_outstanding = $total_vehcile_outstanding + $emi['vehicle_receipt_amount'];
			
			if(isset($emi['vehicle_payment_amount']) && is_numeric($emi['vehicle_payment_amount']))
			$total_vehcile_outstanding = $total_vehcile_outstanding + $emi['vehicle_payment_amount'];
			
			if(isset($emi['vehicle_sale_amount']) && is_numeric($emi['vehicle_sale_amount']))
			$total_vehcile_outstanding = $total_vehcile_outstanding + $emi['vehicle_sale_amount'];
			
			if(isset($emi['vehicle_tax_amount']) && is_numeric($emi['vehicle_tax_amount']))
			$total_vehcile_outstanding = $total_vehcile_outstanding + $emi['vehicle_tax_amount'];
			
			if(isset($emi['vehicle_exchange_amount']) && is_numeric($emi['vehicle_exchange_amount']))
			$total_vehcile_outstanding = $total_vehcile_outstanding + $emi['vehicle_exchange_amount'];
			
			if(isset($emi['customer_opening_balance']) && is_numeric($emi['customer_opening_balance']))
			$total_customer_outstanding = $total_customer_outstanding + $emi['customer_opening_balance'];
			
			if(isset($emi['customer_payment_amount']) && is_numeric($emi['customer_payment_amount']))
			$total_customer_outstanding = $total_customer_outstanding + $emi['customer_payment_amount'];
			
			if(isset($emi['customer_receipt_amount']) && is_numeric($emi['customer_receipt_amount']))
			$total_customer_outstanding = $total_customer_outstanding + $emi['customer_receipt_amount'];
			
			if(isset($emi['customer_debit_jv_amount']) && is_numeric($emi['customer_debit_jv_amount']))
			$total_customer_outstanding = $total_customer_outstanding + $emi['customer_debit_jv_amount'];
			
			if(isset($emi['customer_credit_jv_amount']) && is_numeric($emi['customer_credit_jv_amount']))
			$total_customer_outstanding = $total_customer_outstanding + $emi['customer_credit_jv_amount'];
			
			if(isset($emi['customer_purchase_amount']) && is_numeric($emi['customer_purchase_amount']))
			$total_customer_outstanding = $total_customer_outstanding + $emi['customer_purchase_amount'];
			
			if(isset($emi['customer_sales_amount']) && is_numeric($emi['customer_sales_amount']))
			$total_customer_outstanding = $total_customer_outstanding + $emi['customer_sales_amount'];
			
			if(isset($emi['customer_debit_note_amount']) && is_numeric($emi['customer_debit_note_amount']))
			$total_customer_outstanding = $total_customer_outstanding + $emi['customer_debit_note_amount'];
			
			if(isset($emi['customer_credit_note_amount']) && is_numeric($emi['customer_credit_note_amount']))
			$total_customer_outstanding = $total_customer_outstanding + $emi['customer_credit_note_amount'];
			
			if(isset($emi['customer_purchase_tax_amount']) && is_numeric($emi['customer_purchase_tax_amount']))
			$total_customer_outstanding = $total_customer_outstanding + $emi['customer_purchase_tax_amount'];
			
			if(isset($emi['customer_sales_tax_amount']) && is_numeric($emi['customer_sales_tax_amount']))
			$total_customer_outstanding = $total_customer_outstanding + $emi['customer_sales_tax_amount'];
			
			if(isset($emi['customer_debit_note_tax_amount']) && is_numeric($emi['customer_debit_note_tax_amount']))
			$total_customer_outstanding = $total_customer_outstanding + $emi['customer_debit_note_tax_amount'];
			
			if(isset($emi['customer_credit_note_tax_amount']) && is_numeric($emi['customer_credit_note_tax_amount']))
			$total_customer_outstanding = $total_customer_outstanding + $emi['customer_credit_note_tax_amount'];
			
			if(!in_array($emi['customer_id'],$customer_id_array))
			{
				$total = $total + $total_customer_outstanding;
				$customer_id_array[]=$emi['customer_id'];
			}
			$total_emi_amount = $total_emi_amount + $total_vehcile_outstanding;
			if($total_customer_outstanding>=$outstanding_greater)
			{
		 ?>
         
         <tr class="resultRow">
         	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><div style="page-break-inside:avoid;"><?php echo ++$i; ?></div></td>
           
              <td><?php if($emi['vehicle_reg_no']!=null && $emi['vehicle_reg_no']!="") echo $emi['vehicle_reg_no']; else echo "NA"; ?>
            </td>
            <td><?php   echo $emi['model_name']; ?></td>
            <td width="160px"><?php if($total_vehcile_outstanding>=0) echo number_format($total_vehcile_outstanding,2,'.',''); ?>
          </td>
             <td width="160px"><?php if($total_vehcile_outstanding<0) echo number_format(-$total_vehcile_outstanding,2,'.',''); ?>
          </td>
           <td width="160px"><?php if($total_customer_outstanding>=0) echo number_format($total_customer_outstanding,2,'.',''); ?>
          </td>
             <td width="160px"><?php if($total_customer_outstanding<0) echo number_format(-$total_customer_outstanding,2,'.',''); ?>
          </td>
           <td><?php   echo $emi['broker_name']; ?></td>
            <td><?php   echo $emi['customer_name']; ?></td>
             <td><?php   echo $emi['customer_address']; ?></td>
              <td><?php   echo $emi['contact_no']; ?></td>
            
             <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/index.php?view=details&id='.$emi['customer_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
   
        </tr>
     
         <?php } } }?>
            </tbody>
    </table>
    </div>
     <table class="reportFiltersTable">
    <tr>
    	<td> From : <?php if(isset($_SESSION['cToBCReport']['from']) && $_SESSION['cToBCReport']['from']!="") echo $_SESSION['cToBCReport']['from']; else echo "NA"; ?></td>
        <td> To : <?php if(isset($_SESSION['cToBCReport']['to']) && $_SESSION['cToBCReport']['to']!="") echo $_SESSION['cToBCReport']['to']; else echo "NA"; ?></td>
    	
        <td> City : <?php if(isset($_SESSION['cToBCReport']['city_id']) && $_SESSION['cToBCReport']['city_id']!="") {$city=getCityByID($_SESSION['cToBCReport']['city_id']); echo $city['city_name']; } else echo "NA"; ?></td>
       
        <td> Agency : <?php if(isset($_SESSION['cToBCReport']['agency_id']) && $_SESSION['cToBCReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cToBCReport']['agency_id']);  } else echo "NA"; ?></td>
        <td> File Status : <?php if(isset($_SESSION['cToBCReport']['file_status']) && $_SESSION['cToBCReport']['file_status']!="") { if($_SESSION['cToBCReport']['file_status']==1) echo "OPEN";else if($_SESSION['cToBCReport']['file_status']==2) echo "CLOSED";  } else echo "BOTH"; ?></td>
    </tr>
    </table> 
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
   <span class="Total">Total Customer Outstanding : <?php if(isset($total)) { if($total>=0) echo number_format($total)." DR"; else echo  number_format(-$total)." CR";  }?></span>
   <span class="Total" style="clear:both">Total Vehicle Outstanding : <?php if(isset($total_emi_amount)) { if($total_emi_amount>=0) echo number_format($total_emi_amount)." DR"; else echo  number_format(-$total_emi_amount)." CR";  }?></span>
<?php  ?>      
</div>
<div class="clearfix"></div>
