<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Cheque Return Reports</h4>
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
<td>From Date (Payment Date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="start_date" id="start_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cheque_return_report']['from'])) echo $_SESSION['cheque_return_report']['from']; ?>" />	
                 </td>
</tr>


<tr>
<td>Up To Date (Payment Date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="end_date" id="end_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cheque_return_report']['to'])) echo $_SESSION['cheque_return_report']['to']; ?>"/>	
                 </td>
</tr>

<tr>
<td width="220px">Agency Name : </td>
				<td>
					<select id="agency_id" name="agency_id">
                        <option value="-1" >--Please Select--</option>
                        <?php
                            $agencies = listAgencies();
							$companies = listOurCompanies();
                            foreach($agencies as $super)
							
                              {
                             ?>
                             
                             <option value="ag<?php echo $super['agency_id'] ?>" <?php if(isset($_SESSION['cheque_return_report']['agency_id'])){ if( "ag".$super['agency_id'] == $_SESSION['cheque_return_report']['agency_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['agency_name'] ?></option>
                             
                             <?php } ?>
                              
                             <?php 
							 
							 $companies = listOurCompanies();
                              foreach($companies as $com)
							
                              {
                             ?>
                             
                             <option value="oc<?php echo $com['our_company_id'] ?>" <?php if(isset($_SESSION['cheque_return_report']['agency_id'])){ if( "oc".$com['our_company_id'] == $_SESSION['cheque_return_report']['agency_id'] ) { ?> selected="selected" <?php }} ?> ><?php echo $com['our_company_name'] ?></option>
                             
                             <?php } ?>
                              
                         
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
     <?php if(isset($_SESSION['cheque_return_report']['return_array']))
{
	
	$emi_array=$_SESSION['cheque_return_report']['return_array'];
		
		
	 ?>
     <div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
      <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Rasid No</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">File No</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Cheque No</label> 
        <input class="showCB" type="checkbox" id="5" checked="checked"   /><label class="showLabel" for="5">Amount</label> 
        <input class="showCB" type="checkbox" id="6" checked="checked"  /><label class="showLabel" for="6">Payment Date</label> 
         <input class="showCB" type="checkbox" id="7" checked="checked"  /><label class="showLabel" for="7">Cheque Dat</label> 
        <input class="showCB" type="checkbox" id="8" checked="checked"  /><label class="showLabel" for="8">Bank</label> 
         <input class="showCB" type="checkbox" id="9" checked="checked"  /><label class="showLabel" for="9">Name</label> 
           <input class="showCB" type="checkbox" id="10" checked="checked"  /><label class="showLabel" for="10">Address</label> 
          <input class="showCB" type="checkbox" id="11" checked="checked"  /><label class="showLabel" for="11">Contact No</label> 
           <input class="showCB" type="checkbox" id="12" checked="checked"  /><label class="showLabel" for="12">Broker</label> 
          <input class="showCB" type="checkbox" id="13" checked="checked"  /><label class="showLabel" for="12">Returned By</label> 
    </div>    
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        	<th class="heading">No</th>
            <th class="heading">Rasid No</th>
            <th class="heading file">File No</th>
            <th class="heading">Cheque No</th>
              <th class="heading">Cheque Amount</th>
             <th class="heading date">Payment Date</th>
            <th class="heading date">Cheque Date</th>
            <th class="heading">Bank</th>
            <th class="heading">Name</th>
           <th class="heading">Address</th>
           <th class="heading">Contact no</th>
           <th class="heading">Broker</th>
            <th class="heading">Returned By</th>
            <th class="heading no_print btnCol"></th>
           
        </tr>
    </thead>
    <tbody>
        
        <?php
		$total=0;
		$total_agencies=getTotalNoOfAgencies();
		foreach($emi_array as $emi)
		{
			
		 ?>
         <tr class="resultRow">
         <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$i; ?></td>
            <td><span style="display:none"><?php $infoArray=getAgencyOrCompanyIdFromFileId($emi['file_id']); 
			if($infoArray[0]=='agency') {
				$prefix=$infoArray[1];}
			else if($infoArray[0]=='oc')
			{$prefix=$total_agencies+$infoArray[1]; }
			
			echo $prefix.".".preg_replace('/[a-zA-Z]+/', '', $emi['rasid_no']); ?></span> <?php  echo  $emi['rasid_no']; ?>
            </td>
            <td><span style="display:none"><?php  
			if($infoArray[0]=='agency') {
				$prefix=$infoArray[1];}
			else if($infoArray[0]=='oc')
			{$prefix=$total_agencies+$infoArray[1]; }
			echo $prefix.".".preg_replace('/[a-zA-Z]+/', '', $emi['file_number']); ?></span><?php echo $emi['file_number']; ?>
            </td>
              <td><?php  echo $emi['cheque_no']; ?>
            </td>
            <td><?php  echo $emi['cheque_amount']; ?>
            </td>
            <td><?php echo date('d/m/Y',strtotime($emi['payment_date'])); ?>
            </td>
            <td><?php echo date('d/m/Y',strtotime($emi['cheque_date'])); ?>
            </td>
            <td><?php echo  getBankNameByID($emi['bank_id']); ?>
            </td>
            <td><?php   echo $emi['customer_name']; ?></td>
             <td><?php   echo $emi['customer_address']; ?></td>
             <td><?php   echo $emi['contact_no']; ?></td>
             <td><?php   echo $emi['broker_name']; ?></td>
             <td><?php echo $emi['admin_name']; ?></td>
             <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/index.php?view=EMIdetails&id='.$emi['file_id']."&state=".$emi['loan_emi_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
          
          
  
        </tr>
         <?php } }?>
         </tbody>
    </table>
    </div>
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
  <?php if(isset($total) && $total>0) { ?> <span class="Total">Total Amount : <?php echo number_format($total); ?></span><?php } ?>
<?php  ?>      
</div>
<div class="clearfix"></div>
<script>
 $( "#city_area1" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/city_area.php',
                { term: request.term, city_id:$('#customer_city_id').val() }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#city_area1" ).val(ui.item.label);
			return false;
		}
    });

</script>