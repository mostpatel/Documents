<?php
if(!isset($_GET['id']))
{
	header("Location: ".WEB_ROOT);
	exit;
	}
$file_id=$_GET['id'];
$loan_id=getLoanIdFromFileId($file_id);

$ag_id_array=getAgencyOrCompanyIdFromFileId($file_id);
$interest_per_emi=getInterestPerEMIForLoan($loan_id);
$total_emis_paid = getTotalEmiPaidForLoan($loan_id);
$loan = getLoanDetailsByFileId($file_id);
$loan_duration = $loan['loan_duration'];
$total_emis_paid = ceil($total_emis_paid);
$no_of_emis_upto_today = getNoOfEmiUPTillTodayForLoanId($loan_id);
if($no_of_emis_upto_today<$loan_duration)
$no_of_emis_upto_today++;
$total_payment = getTotalPaymentForLoan($loan_id);
$actual_amount_array = getEMIAmountForClosure($loan_id);
$actual_amount = $actual_amount_array[0];
$actual_no_of_emis = $actual_amount_array[1];

$remaining_principal = $loan['loan_amount'] - ($actual_amount - ($actual_no_of_emis*$interest_per_emi));

$closure_amount = $actual_amount - $total_payment + $remaining_principal;
if($ag_id_array[0]=='agency')
		{
			
			$agency_id=$ag_id_array[1];
			$oc_id=null;
			$rasid_no=getRasidnoForAgencyId($agency_id);
			$rasid_counter=getRasidCounterForAgencyId($agency_id);
			$rasid_prefix=getAgencyPrefixFromAgencyId($agency_id);
			
			}
		else if($ag_id_array[0]=='oc')
		{
			$oc_id=$ag_id_array[1];
			$agency_id=null;
			$rasid_no=getRasidNoForOCID($oc_id);
			$rasid_counter=getRasidCounterForOCId($oc_id);
			$rasid_prefix=getPrefixFromOCId($oc_id);
}	
while(checkForDuplicateRasidNo($rasid_prefix.$rasid_counter,$loan_id))
{
	if($ag_id_array[0]=='agency')
		{
			
			
			$agency_id=$ag_id_array[1];
			incrementRasidCounterForAgency($agency_id);
			$oc_id=null;
			$rasid_no=getRasidnoForAgencyId($agency_id);
			$rasid_counter=getRasidCounterForAgencyId($agency_id);
			$rasid_prefix=getAgencyPrefixFromAgencyId($agency_id);
			}
		else if($ag_id_array[0]=='oc')
		{
			$oc_id=$ag_id_array[1];
			$agency_id=null;
			incrementRasidNoForOCID($oc_id);
			$rasid_no=getRasidNoForOCID($oc_id);
			$rasid_counter=getRasidCounterForOCId($oc_id);
			$rasid_prefix=getPrefixFromOCId($oc_id);
}	
	
	}	
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Premature File Closure</h4>
<?php 
if(isset($_SESSION['ack']['msg']) && isset($_SESSION['ack']['type']))
{
	
	$msg=$_SESSION['ack']['msg'];
	$type=$_SESSION['ack']['type'];
	
	
		if($msg!=null && $msg!="" && $type>0)
		{
?>
<div class="alert  <?php if(isset($type) && $type>0 && $type<4) echo "alert-success"; else echo "alert-error" ?>">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <?php if(isset($type)  && $type>0 && $type<4) { ?> <strong>Success!</strong> <?php } else if(isset($type)   && $type>3) { ?> <strong>Warning!</strong> <?php } ?> <?php echo $msg; ?>
</div>
<?php
		
		
		}
	if(isset($type) && $type>0)
		$_SESSION['ack']['type']=0;
	if($msg!="")
		$_SESSION['ack']['msg']=="";
}

?>
<div class="detailStyling">
<form onsubmit="submitClosure();" id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=close'; ?>" method="post" enctype="multipart/form-data">
<input name="lid" value="<?php echo $file_id; ?>" type="hidden">
<input name="file_id" value="<?php echo $file_id; ?>" id="file_id" type="hidden" />
<input name="auto_rasid_no" value="<?php echo $rasid_counter; ?>" id="auto_rasid_no" type="hidden"  />
<input value="<?php if($agency_id!=null) echo $agency_id; else echo 0; ?>" id="agency_id" type="hidden" />
<input  value="<?php if($oc_id!=null) echo $oc_id; else echo 0; ?>" id="oc_id" type="hidden" />
<input  value="<?php echo 0; ?>" id="old_rasid_no" type="hidden" />
<table class="insertTableStyling no_print">

<tr>
<td width="220px">Closure Date<span class="requiredField">* </span> : </td>
				<td>
					<input onchange="onChangeDate(this.value,this)"  type="text" id="closureDate" autocomplete="off" size="12"  name="close_date" class="datepicker1 datepick" placeholder="Click to Select!"  <?php if(isset($_SESSION['adminSession']['admin_rights']) && !(in_array(9,$admin_rights)|| in_array(7,$admin_rights))) { ?> readonly="readonly" <?php } ?> value="<?php echo date('d/m/Y'); ?>" /><span class="DateError customError">Please select a date!</span>
</td>
                    
                    
                  
</tr>
<tr>
<td>
Amount<span class="requiredField">* </span> : 
</td>
<td>
<input type="text"  name="amount" id="amount"/>
<input type="hidden"  name="file_id" value="<?php echo $file_id; ?>"/>
</td>
</tr>

<tr>
<td>Payment Mode<span class="requiredField">* </span> : </td>
				<td>
					<table>
               <tr><td><input type="radio" onChange="checkMode(this.value);"  name="mode" id="cash"  value="1" checked="checked"></td><td><label for="cash">Cash</label></td></tr>
            <tr><td><input type="radio" onChange="checkMode(this.value);" id="mode" name="mode"  value="2" ></td><td><label for="mode">Cheque</label></td>
               </tr> 
            </table>
                            </td>
</tr>
</table>
<table id="chequePaymentTable" class="insertTableStyling no_print">
<tr>
<td width="220px">Bank Name<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="bank_name" id="bank" placeholder="Only Letters!" autocomplete="off" />
                            </td>
</tr>
<tr>
<td width="220px">Branch Name<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="branch_name" id="branch" placeholder="Only Letters!" autocomplete="off" />
                            </td>
</tr>
<tr>
<td width="220px">Cheque No<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="cheque_no" id="cheque_no" placeholder="Only Digits!" />
                            </td>
</tr>
<tr>
<td width="220px">Cheque Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="cheque_date" id="cheque_date" class="datepicker3" placeholder="click to select date!" /><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>

<?php

if(ACCOUNT_STATUS==1)
{
	
 ?>
 <tr>
<td>By (Account)<span class="requiredField">* </span> : </td>
				<td>
					<select id="by_ledger" name="bank_account">
                    <?php
					$agency_oc_array=getAgencyOrCompanyIdFromFileId($file_id);
					$agency_oc_type=$agency_oc_array[0];
					$agency_oc_id=$agency_oc_array[1];
					if($agency_oc_type=='oc')
					{
					$ca_id=getCombinedAgencyIdForOCId($agency_oc_id);	

					if($ca_id==false)
					$bank_cash_ledgers=listAccountingLedgersForOC($agency_oc_id);	
                    else if($ca_id && checkForNumeric($ca_id))
                    $bank_cash_ledgers=listAccountingLedgersForCombinedAgency($ca_id);	
					$account_settings=getAccountSettingsForOC($agency_oc_id);	
					
					}
					else if($agency_oc_type=='agency')
					{
						$ca_id=getCombinedAgencyIdForAgencyId($agency_oc_id);	

					if($ca_id==false)
					$bank_cash_ledgers=listAccountingLedgersForAgency($agency_oc_id);	
                    else if($ca_id && checkForNumeric($ca_id))
                    $bank_cash_ledgers=listAccountingLedgersForCombinedAgency($ca_id);	
	
						$account_settings=getAccountSettingsForAgency($agency_oc_id);	
						}
					$default_bank=$account_settings['default_bank'];
					foreach($bank_cash_ledgers as $bank_cash_ledger)
					{
					?>
                    <option value="<?php echo $bank_cash_ledger['ledger_id']; ?>" <?php if(isset($default_bank) && is_numeric($default_bank) && $default_bank==$bank_cash_ledger['ledger_id']) { ?> selected="selected" <?php } ?>><?php echo $bank_cash_ledger['ledger_name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select>
                            </td>
</tr>

<?php }
else
{
 ?> 
 <input name="bank_account" value="0" type="hidden" />
<?php } ?>


</table>
<table  class="insertTableStyling no_print">


<tr>
<td width="220px">Rasid No : </td>
				<td>
					<span><?php echo $rasid_prefix; ?></span><input type="text" name="rasid_no" id="rasid_no" placeholder="Only Digits!" value="<?php echo $rasid_counter; ?>" onchange="checkRasidNo();" /><span id="agerror" class="availError">Rasid Number already taken!</span>
                </td>
</tr>

<tr>
<td>
Remarks : 
</td>
<td>
<textarea   name="remarks" id="remarks_remainder"></textarea>
</td>
</tr>

<tr>
<td></td>
<td>
<input id="disableSubmit" type="submit" value="Add" class="btn btn-warning"> <a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=details&id=<?php echo $file_id; ?>"><input type="button" class="btn btn-success" value="Back" /></a>
</td>
</tr>

</table>

</form>
</div>
<h4 class="headingAlignment no_print">Calculate Closure Amount</h4>
<form action="index.php?view=closureQuote" method="post">
<input name="file_id" value="<?php echo $file_id; ?>"  type="hidden">
<input name="loan_id" value="<?php echo $loan_id; ?>" type="hidden">
<table class="insertTableStyling no_print">

<tr>
<td width="220px">Percentage<span class="requiredField">* </span> : </td>
				<td>
					<Select name="closure_percent" id="percent" >
                    	<option value="2.5">2.5%</option>
                        <option value="3">3.0%</option>
                    </Select>
</td>
                    
                    
                  
</tr>

<tr>
<td></td>
<td>
<input type="submit" value="Calculate" class="btn btn-warning" > 
</td>
</tr>



</table>

<h4 class="headingAlignment no_print">Calculate Closure Amount</h4>
<form action="#" method="post">
<table class="insertTableStyling no_print">

<tr>
<td width="220px">Percentage<span class="requiredField">* </span> : </td>
				<td>
                	<input type="hidden" id="closure_amount" value="<?php echo $closure_amount; ?>" />
                    <input type="hidden" id="int_per_emi" value="<?php echo $interest_per_emi; ?>" />
					<Select  id="no_of_months" >
                    <?php for($i=1;$i<36;$i++){ ?>
                    	<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php } ?>
                       
                    </Select>
</td>
                    
                    
                  
</tr>

<tr>
<td></td>
<td>
<input type="button" value="Calculate" class="btn btn-warning" onclick="calculateClosureFinalAmount();" > 
</td>
</tr>

<tr>
<td>Closure Amount : </td>
<td >
 <span id="final_closure_amount"></span>
</td>
</tr>



</table>
 
</div>
<div class="clearfix"></div>
<script>
function calculateClosureFinalAmount()
{
	var closure_amount = document.getElementById('closure_amount').value;
	
	var intereset_per_emi = document.getElementById('int_per_emi').value;
	var no_of_months = document.getElementById('no_of_months');
	var no_of_month = no_of_months[no_of_months.selectedIndex].value;
	var total_interest = parseFloat(intereset_per_emi) * parseFloat(no_of_month);
	var toal_closure = parseFloat(total_interest) + parseFloat(closure_amount);
	document.getElementById('final_closure_amount').innerHTML = toal_closure;
}
<?php if(!(isset($_SESSION['adminSession']['admin_rights']) && (in_array(9,$admin_rights)|| in_array(7,$admin_rights)))) { ?> $(".datepicker1").datepicker('disable'); <?php } ?>

 $( "#bank" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/bank_name.php',
                { term: request.term }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#bank" ).val(ui.item.label);
			return false;
		}
    });
	 $( "#branch" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/branch_name.php',
                { term: request.term, bank_name:$('#bank').val() }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#branch" ).val(ui.item.label);
			return false;
		}
    });	
</script>