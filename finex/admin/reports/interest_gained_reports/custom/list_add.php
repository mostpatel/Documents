<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Custom Account Reports</h4>
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
<td>From Date (Entry Date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="start_date" id="start_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cInterestReport']['from'])) echo $_SESSION['cInterestReport']['from']; ?>" />	
                 </td>
</tr>


<tr>
<td>Up To Date (Entry Date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="end_date" id="end_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cInterestReport']['to'])) echo $_SESSION['cInterestReport']['to']; ?>"/>	
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
                             
                             <option value="ag<?php echo $super['agency_id'] ?>" <?php if(isset($_SESSION['cInterestReport']['agency_id'])){ if( "ag".$super['agency_id'] == $_SESSION['cInterestReport']['agency_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['agency_name'] ?></option>
                             
                             <?php } ?>
                              
                             <?php 
							 
							 $companies = listOurCompanies();
                              foreach($companies as $com)
							
                              {
                             ?>
                             
                             <option value="oc<?php echo $com['our_company_id'] ?>" <?php if(isset($_SESSION['cInterestReport']['agency_id'])){ if( "oc".$com['our_company_id'] == $_SESSION['cInterestReport']['agency_id'] ) { ?> selected="selected" <?php }} ?> ><?php echo $com['our_company_name'] ?></option>
                             
                             <?php } ?>
                              
                         
                            </select> 
                    </td>
                    
                    
                  
</tr>



<td></td>
				<td>
				 <input type="submit" value="Generate" class="btn btn-warning"/>	
                </td>
</tr>


</table>

</form>

  
<hr class="firstTableFinishing" />
 

	<div class="no_print">
 <?php if(isset($_SESSION['cInterestReport']['remainder_array']))
{
	
	$emi_array=$_SESSION['cInterestReport']['remainder_array'];
		
		
	 ?>   
     
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>     
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">File No</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Emis Paid</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Interest Per Emi</label> <input class="showCB" type="checkbox" id="5" checked="checked"  /><label class="showLabel" for="5">Interest To be Paid</label> 
        <input class="showCB" type="checkbox" id="6" checked="checked"   /><label class="showLabel" for="6">Total Interest</label> 
        <input class="showCB" type="checkbox" id="7" checked="checked"   /><label class="showLabel" for="7">Total Payments Received</label> 
         <input class="showCB" type="checkbox" id="8" checked="checked"   /><label class="showLabel" for="8">Total Payments Left</label>         <input class="showCB" type="checkbox" id="9" checked="checked"   /><label class="showLabel" for="9">EMI</label> 
        <input class="showCB" type="checkbox" id="10" checked="checked"  /><label class="showLabel" for="10">Name</label> 
         <input class="showCB" type="checkbox" id="11" checked="checked"  /><label class="showLabel" for="11">Reg No</label> 
         
    </div>
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
            <th class="heading no_print">Print</th>
            <th class="heading">No</th>
        	<th class="heading file">File No</th>
            <th class="heading">Emis Paid</th>
            <th class="heading">Emis To Be Paid</th>
            <th class="heading">Interest Per Emi</th>
            <th class="heading">Interest To Be Paid</th>
            <th class="heading">Total Interest</th>
            <th class="heading">Total Payments Received</th>
            <th class="heading">Total Payments Left</th>
            <th class="heading">EMI</th>
            <th class="heading">Name</th>
            <th class="heading">Reg No</th>
            <th class="heading no_print btnCol"></th>
           
        </tr>
    </thead>
    <tbody>
      
        <?php
		$total=0;
		$total_to_be_paid=0;
		$total_refund = 0;
		foreach($emi_array as $emi)
		{
			if(!isset($emi['file_status']))
			{
			$total=$total+$emi['interest'];
			$interes_to_be_paid  =  number_format($emi['interest_per_emi'],2,'.','')*number_format($emi['total_emis'],2,'.','');
			$total_to_be_paid = $total_to_be_paid + $interes_to_be_paid;
			$file_status = NULL;
			}
			else if(isset($emi['file_status']) && $emi['file_status']==4)
			{
				$amount_to_be_paid = $emi['amount_to_be'];
				$amount_paid = $emi['amount_paid'];
				$refund = $emi['refund'];
				$interes_to_be_paid  =  number_format($emi['interest_per_emi'],2,'.','')*number_format($emi['total_emis'],2,'.','');
				$total_to_be_paid = $total_to_be_paid + $interes_to_be_paid;
				$total=$total+$emi['interest'];
				$total_refund = $total_refund + $refund;
				$file_status = 4;
			}
		 ?>
         <tr class="resultRow">
         	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$i; ?></td>
            
            <td><span style="display:none"><?php $infoArray=getAgencyOrCompanyIdFromFileId($emi['file_id']); 
			if($infoArray[0]=='agency') {
				$prefix=$infoArray[1];}
			else if($infoArray[0]=='oc')
			{$prefix=getTotalNoOfAgencies()+$infoArray[1]; }
			
			echo $prefix.".".preg_replace('/[a-zA-Z]+/', '', $emi['file_no']); ?></span> <?php  echo  $emi['file_no']; if(isset($file_status) && $file_status==4) echo "(FC)"; ?>
            </td>
             <td><?php if(isset($file_status) && $file_status==4) echo number_format($amount_paid,2); else  echo number_format($emi['emi_paid'],2); ?>
            </td>
             <td><?php if(isset($file_status) && $file_status==4) echo number_format($amount_to_be_paid,2); else echo number_format($emi['total_emis'],2); ?>
            </td>
             <td><?php echo number_format($emi['interest_per_emi'],2); ?>
            </td>
             <td><?php echo number_format($interes_to_be_paid,2); ?>
            </td>
            <td><?php echo number_format($emi['interest'],2); if(isset($file_status) && $file_status==4) echo "<br>".number_format($refund,2)." (R)"; ?>
            </td>
            <td>
            <?php $total_payment_received =  getTotalPaymentForLoanBetweenDate($emi['loan_id'],$_SESSION['cInterestReport']['from'],$_SESSION['cInterestReport']['to']); echo $total_payment_received; echo "<br> Principal : "; echo round($total_payment_received-$emi['interest'],2);  ?>
            </td>
              <td><?php if(isset($file_status) && $file_status==4) echo 0; else echo number_format($emi['payments_left'],2); ?>
            </td>
            <td>
              <?php
							 $loan_emi=getEmiForLoanId($emi['loan_id']); // amount if even loan or loan structure if loan is uneven
							 $loan = getLoanById($emi['loan_id']);
							 if($loan['loan_scheme']==1)
							  echo number_format($loan['emi']);
							  else
							  {
								  foreach($loan_emi as $e)
								  {
									  echo number_format($e['emi'])." X ".$e['duration']."<br>";
									  }
								  
								  } ?>	
            </td>
            
            
            <td><?php   echo $emi['customer']['customer_name']; ?></td>
              <td><?php if($emi['reg_no']!=null && $emi['reg_no']!="") echo $emi['reg_no']; else echo "NA"; ?>
            </td>   
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/index.php?view=details&id='.$emi['file_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
           
            
          
  
        </tr>
         <?php } }?>
         </tbody>
    </table>
    </div>
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
    <span class="Total">Total Interest : <?php if(isset($total)) echo number_format($total)." "; ?> </span> 
    <span class="Total"> Total Interest To be Paid : <?php if(isset($total)) echo number_format($total_to_be_paid); ?></span>
     <span class="Total"> Total Refund : <?php if(isset($total_refund)) echo number_format($total_refund); ?></span>
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