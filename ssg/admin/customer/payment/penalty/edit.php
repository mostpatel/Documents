<?php

if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}
$file_id=$_GET['id'];
$penalty_id=$_GET['state'];

{
	$loan=getLoanDetailsByFileId($file_id);
	$loan_id=$loan['loan_id'];
	$penalty=getPenaltyById($penalty_id);
	$days_left=getPenaltyDaysLeftForLoan($loan_id);
	$days_left=$days_left+$penalty['days_paid'];
	if($penalty['payment_mode']==2)
	{
		$cheque_details=getChequeDetailsPenalty($penalty_id);
		}
	else
		{
			$cheque_details=0;
			}
	
}
$ag_id_array=getAgnecyIdFromLoanId($loan_id);
if(is_numeric($ag_id_array[0]))
		{
			$agency_id=$ag_id_array[0];
			$oc_id=null;
			$rasid_no=getRasidnoForAgencyId($agency_id);
			$rasid_counter=getRasidCounterForAgencyId($agency_id);
			$rasid_prefix=getAgencyPrefixFromAgencyId($agency_id);
			}
		else if(is_numeric($ag_id_array[1]))
		{
			$oc_id=$ag_id_array[1];
			$agency_id=null;
			$rasid_no=getRasidNoForOCID($oc_id);
			$rasid_counter=getRasidCounterForOCId($oc_id);
			$rasid_prefix=getPrefixFromOCId($oc_id);
			}	


?>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment"> Edit Details </h4>
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
<form onsubmit="return submitPayment();" id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" method="post" enctype="multipart/form-data" >
<input name="loan_id" value="<?php echo $loan_id; ?>" type="hidden" />
<input name="lid" value="<?php echo $penalty_id; ?>" type="hidden" />
<input name="file_id" value="<?php echo $file_id; ?>" type="hidden" />
<table id="insertInsuranceTable" class="insertTableStyling no_print">

<tr>

<td width="220px">Penalty For Days<span class="requiredField">* </span> : </td>
				<td>
					<input value="<?php echo $penalty['days_paid']; ?>" type="text" name="days_paid" id="days" placeholder="Only Digits!" /><span class="DateError customError">Days Should less than <?php echo $days_left+1; ?> Days. !</span>
                            </td>
</tr>

<tr>
<td>Amount/Day<span class="requiredField">* </span>  : </td>
				<td>
					<input type="text" name="amount" id="amount" placeholder="Only Digits!" autocomplete="off" onchange="onchangeAmountPerDay(this.value);" value="<?php echo $penalty['amount_per_day'] ?>" />
                            </td>
</tr>

<tr>
<td>Total Amount<span class="requiredField">* </span>  : </td>
				<td>
					<input type="text" name="total_amount" id="total_amount" placeholder="Only Digits!" autocomplete="off" onchange="onchangeTotalAmount(this.value);" value="<?php echo $penalty['total_amount'] ?>" />
                            </td>
</tr>
<tr>
<td>Rasid No<span class="requiredField">* </span> : </td>
				<td>
					<span><?php echo $rasid_prefix; ?></span><input type="text" name="rasid_no" id="rasid_no_penalty" placeholder="Only Digits!" value="<?php if($penalty['rasid_no']!=$rasid_prefix."NA") echo str_replace($rasid_prefix,"",$penalty['rasid_no']); ?>"  /><span id="agerror" class="availError">Rasid Number already taken!</span>
                </td>
</tr>

<tr>

<td class="firstColumnStyling">
Paid By : 
</td>

<td>
 <input type="text"   name="paid_by" id="paid_by" value="<?php if($penalty['paid_by']!="NA") echo $penalty['paid_by'] ?>" />
</td>
</tr>



<tr>
<td>Payment Mode<span class="requiredField">* </span> : </td>
				<td>
					<table>
               <tr><td><input type="radio" onChange="checkMode(this.value);"  name="mode"  value="1" id="cash" <?php if($penalty['payment_mode']==1) { ?> checked="checked" <?php } ?>></td><td><label for="cash">Cash</label></td></tr>
            <tr><td><input type="radio" onChange="checkMode(this.value);" id="mode" name="mode"  value="2"  <?php if($penalty['payment_mode']==2) { ?> checked="checked" <?php } ?>></td><td><label for="mode">Cheque</label></td>
               </tr> 
            </table>
                            </td>
</tr>
</table>
<table id="chequePaymentTable" class="insertTableStyling no_print">
<tr>
<td width="220px">Bank Name<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="bank_name" id="bank" value="<?php if($penalty['payment_mode']==2) echo getBankNameByID($cheque_details['bank_id']); ?>" placeholder="Only Letters!" autocomplete="off" />
                            </td>
</tr>
<tr>
<td width="220px">Branch Name<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="branch_name" id="branch" placeholder="Only Letters!" autocomplete="off" value="<?php if($penalty['payment_mode']==2) echo getBranchhById($cheque_details['branch_id']); ?>" />
                            </td>
</tr>
<tr>
<td width="220px">Cheque No<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="cheque_no" id="cheque_no" placeholder="Only Digits!" value="<?php if($penalty['payment_mode']==2) echo $cheque_details['cheque_no']; ?>"  />
                            </td>
</tr>
<tr>
<td width="220px">Cheque Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="cheque_date" id="cheque_date" class="datepicker3" placeholder="click to select date!" value="<?php if($penalty['payment_mode']==2) echo date('d/m/Y',strtotime($cheque_details['cheque_date'])); ?>" /><span class="DateError customError">Please select a date!</span>
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
                     <option value="-1"><?php echo "--Please Select--"; ?></option>
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
                    <option value="<?php echo $bank_cash_ledger['ledger_id']; ?>" <?php if($cheque_details && $bank_cash_ledger['ledger_id']==$cheque_details['ledger_id']) { ?> selected="selected" <?php } else if(!$cheque_details &&isset($default_bank) && is_numeric($default_bank) && $default_bank==$bank_cash_ledger['ledger_id']) { ?> selected="selected" <?php } ?>><?php echo $bank_cash_ledger['ledger_name']; ?></option>			
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
<td width="220px">Payment Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="payment_date" value="<?php echo date('d/m/Y',strtotime($penalty['paid_date'])); ?>" id="payment_date"  class="datepicker1" placeholder="click to select date!" <?php if(isset($_SESSION['adminSession']['admin_rights']) && !(in_array(9,$admin_rights)|| in_array(7,$admin_rights))) { ?> readonly="readonly" <?php } ?> /><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>


 
</table>

<table>
<tr>
<td width="250px;"></td>
<td>
<input id="disableSubmit" type="submit" value="Edit Penalty" class="btn btn-warning">
<a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=penaltyDetails&id=<?php echo $file_id; ?>&state=<?php echo $loan_id; ?>"><input type="button" value="Back" class="btn btn-success" /></a>
</td>
</tr>

</table>

</form>
</div>
<div class="clearfix"></div>
<script>
document.balance=<?php echo 10000000; ?>;

<?php if(!(isset($_SESSION['adminSession']['admin_rights']) && (in_array(9,$admin_rights)|| in_array(7,$admin_rights)))) { ?> $(".datepicker1").datepicker('disable'); <?php } ?>

function onchangeAmountPerDay(amount_per_day)
{
	var days = document.getElementById('days').value;
	days = parseInt(days);
	var total_amount = days * amount_per_day;
	document.getElementById('total_amount').value = total_amount;
}

function onchangeTotalAmount(total_amount)
{
	var days = document.getElementById('days').value;
	days = parseInt(days);
	var amount_per_day  = total_amount/days;
	document.getElementById('amount').value = amount_per_day;
}

document.days_left=<?php echo $days_left; ?>;
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