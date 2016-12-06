<?php if(!isset($_GET['id']) || !isset($_GET['state']))
{
if(isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_GET['id']);
exit;
}
else
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}
}
$file_id=$_GET['id'];
$customer=getCustomerNameANDCoByFileId($file_id);
$reg_no=getRegNoFromFileID($file_id);
$vehicle_id = getVehicleIdByFileId($file_id);

$emi_id=$_GET['state'];
if(isset($_GET['return']))
$return=$_GET['return'];
else
$return=0;

$cheque_no_details=getChequeNumbersForFileIdForEmiNo($file_id,$emi_id);

$emi=getEmiForLoanEmiId($emi_id);
$loan_id=getLoanIdFromEmiId($emi_id);
$balance=getBalanceForLoan($loan_id);
$paid_by=getPaidByForFileID($file_id);
$ag_id_array=getAgnecyIdFromEmiId($emi_id);
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
while(checkForDuplicateRasidNo($rasid_prefix.$rasid_counter,$loan_id))
{
	if(is_numeric($ag_id_array[0]))
		{
			
			$agency_id=$ag_id_array[0];
			incrementRasidCounterForAgency($agency_id);
			$oc_id=null;
			$rasid_no=getRasidnoForAgencyId($agency_id);
			$rasid_counter=getRasidCounterForAgencyId($agency_id);
			$rasid_prefix=getAgencyPrefixFromAgencyId($agency_id);
			}
		else if(is_numeric($ag_id_array[1]))
		{
			$oc_id=$ag_id_array[1];
			$agency_id=null;
			incrementRasidNoForOCID($oc_id);
			$rasid_no=getRasidNoForOCID($oc_id);
			$rasid_counter=getRasidCounterForOCId($oc_id);
			$rasid_prefix=getPrefixFromOCId($oc_id);
}	
	
	}
$form_identifier=uniqid("",true).strtotime(date());
$unreceived_welcome_letters = listUnreceivedWelcomesForFileID($file_id);
$unreceived_notice_letters = listUnreceivedNoticesForFileID($file_id);
$vehicle_docs = getVehicleDocsForVehicleId($vehicle_id);
if(is_array($vehicle_docs))
{
	if($vehicle_docs['rto_papers']==3 || $vehicle_docs['passing']==3 || $vehicle_docs['permit']==3 || $vehicle_docs['insurance']==3 || $vehicle_docs['hp']==3 || $vehicle_docs['bill']==3 || $vehicle_docs['vehicle_key']==3)
{
	$docs_with_customer=1;
	}
	else
	$docs_with_customer=0;
	
}
else
$docs_with_customer=0;
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment"> Add Payment </h4>
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
<div class="detailStyling">

<form onsubmit="return submitPayment();" id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post" enctype="multipart/form-data" >
<input name="emi_id" value="<?php echo $emi_id; ?>" id="emi_id" type="hidden"  />
<input name="auto_rasid_no" value="<?php echo $rasid_counter; ?>" id="auto_rasid_no" type="hidden"  />
<input name="file_id" value="<?php echo $file_id; ?>" id="file_id" type="hidden" />
<input type="hidden" name="form_identifier" value="<?php echo $form_identifier ?>"  />
<input value="<?php if($agency_id!=null) echo $agency_id; else echo 0; ?>" id="agency_id" type="hidden" />
<input  value="<?php if($oc_id!=null) echo $oc_id; else echo 0; ?>" id="oc_id" type="hidden" />
<input  value="<?php echo 0; ?>" id="old_rasid_no" type="hidden" />
<input name="return" value="<?php echo $return; ?>" type="hidden" />
<table id="insertInsuranceTable" class="insertTableStyling no_print">

<tr>
<td width="220px">Payment Amount<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="amount" id="amount" placeholder="Only Digits!" value="<?php echo $emi; ?>" autofocus /><span class="DateError customError">Amount Should less than <?php echo -$balance; ?> Rs. !</span>
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
					<input type="text" name="bank_name" id="bank" placeholder="Only Letters!" autocomplete="off" value="<?php if($cheque_no_details) { if(isset($cheque_no_details['bank_name'])) echo $cheque_no_details['bank_name'];  } else if(defined("DEF_CHQ_VALUES") && DEF_CHQ_VALUES==1) echo "NA"; ?>" />
                            </td>
</tr>
<tr>
<td width="220px">Branch Name<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="branch_name" id="branch" placeholder="Only Letters!" autocomplete="off" value="<?php  if($cheque_no_details) { if(isset($cheque_no_details['branch_name'])) echo $cheque_no_details['branch_name'];  } else if(defined("DEF_CHQ_VALUES") && DEF_CHQ_VALUES==1) echo "NA"; ?>" />
                            </td>
</tr>
<tr>
<td width="220px">Cheque No<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="cheque_no" id="cheque_no" placeholder="Only Digits!" value="<?php if($cheque_no_details) { if(isset($cheque_no_details['cheque_no'])) echo $cheque_no_details['cheque_no'];  } else  if(defined("DEF_CHQ_VALUES") && DEF_CHQ_VALUES==1) echo "000000"; ?>" />
                            </td>
</tr>
<tr>
<td width="220px">Cheque Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="cheque_date" value="<?php echo date('d/m/Y'); ?>" id="cheque_date" class="datepicker3" placeholder="click to select date!"  /><span class="DateError customError">Please select a date!</span>
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
<td width="220px">Payment Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="payment_date" id="payment_date"   class="datepicker1"  placeholder="click to select date!" value="<?php echo date('d/m/Y'); ?>" <?php if(isset($_SESSION['adminSession']['admin_rights']) && !(in_array(9,$admin_rights)|| in_array(7,$admin_rights))) { ?> readonly <?php } ?> onChange="changeChequeDate(this.value)" /><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>

<tr>
<td>Rasid No<span class="requiredField">* </span> : </td>
				<td>
					<span><?php echo $rasid_prefix; ?></span><input type="text" name="rasid_no" id="rasid_no" placeholder="Only Digits!" value="<?php echo $rasid_counter; ?>" onblur="checkRasidNo();" onchange="checkRasidNo();" /><span id="agerror" class="availError">Rasid Number already taken!</span>
                </td>
</tr>

<tr>

<td class="firstColumnStyling">
Paid By : 
</td>

<td>
 <input type="text"   name="paid_by" id="paid_by" value="<?php echo $paid_by ?>"  />
</td>
</tr>
<?php if(defined('PENALTY_WITH_PAYMENT') && PENALTY_WITH_PAYMENT==1 )
					{ ?>
<tr>

<td class="firstColumnStyling">
Penalty Days : 
</td>

<td>
 <input type="text"   name="days_paid" id="days_paid" value="<?php echo 1; ?>"  />
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Amount / Day : 
</td>

<td>
 <input type="text"   name="amount_per_day" id="amount_per_day"  />
</td>
</tr>
<?php } ?>
<?php if(defined('SEND_SMS') && SEND_SMS==1)
					{ ?>
<tr>
<td>Send SMS<span class="requiredField">* </span> : </td>
				<td>
					<table>
               <tr><td><input type="radio"   name="send_sms" id="send_sms"  value="1" checked="checked"></td><td><label for="send_sms">Yes</label></td></tr>
            <tr><td><input type="radio"  id="sens_sms_n" name="send_sms"  value="0" ></td><td><label for="mode">No</label></td>
               </tr> 
            </table>
                            </td>
</tr>
<?php } ?>
<tr>
<td class="firstColumnStyling">
Remarks : 
</td>

<td>
<textarea name="remarks" id="remarks"></textarea>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Remainder Date : 
</td>

<td>
 <input type="text" class="datepicker2"  name="remainder_date" id="remainder_date" placeholder="Click to select date!" /><span class="DateError customError">Please select a date!</span>
</td>
</tr>


 
</table>

<table>
<tr>
<td width="250px;"></td>
<td>
<input id="disableSubmit" type="submit" value="Add Payment"  class="btn btn-warning">
<?php if(isset($_SERVER['HTTP_REFERER'])) { ?><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><input type="button" class="btn btn-success" value="Back"/></a><?php } ?>
</td>
</tr>

</table>

</form>
</div>
<div class="detailStyling">
<table class="insertTableStyling detailStylingTable">
<tr>
<td width="250px;">Reg No : </td>
<td>
<?php echo $reg_no; ?>
</td>
</tr>
<tr>
<td width="250px;">Customer Name : </td>
<td>
<?php echo $customer['customer_name']; ?>
</td>
</tr>
</table>

</div>
<?php if(isset($docs_with_customer) && is_numeric($docs_with_customer) && $docs_with_customer==1)
{
	$showVehicleModal=1;
	 ?>
<script type="text/javascript">

document.getElementById('vehicle_docs_file_id').value = <?php echo $file_id; ?>;
</script> 
<?php } ?>
<?php if(is_array($unreceived_welcome_letters) && is_numeric($unreceived_welcome_letters[0]['welcome_id']))
{
	$showModal=1;
	 ?>
<script type="text/javascript">document.getElementById('unReceivedLetterType').value=<?php echo $unreceived_welcome_letters[0]['welcome_type']; ?>;
document.getElementById('welcome_letter_file_id').value = <?php echo $file_id; ?>;
</script>     
<div class="detailStyling">

<h4 class="headingAlignment">Unreceived Welcome Letter Details </h4>


<table class="insertTableStyling detailStylingTable">
<?php foreach($unreceived_welcome_letters as $unreceived_welcome_letter) { ?>
<tr>
    <td class="firstColumnStyling">
    Welcome Date :</td>
    <td> 
		<?php echo date('d/m/Y',strtotime($unreceived_welcome_letter['welcome_date'])); ?>		
    </td>
    <tr>
    <td>
     
  Welcome Type: </td>
   <td>                          
   		<?php  if($unreceived_welcome_letter['welcome_type']==0) echo "Customer"; else echo "Guarantor"; ?>					
                               
    </td>
</tr>

  <tr>
    <td>
     
  Unreceived Reason: </td>
   <td>                          
   		<?php  echo $unreceived_welcome_letter['not_received_type']; ?>					
                               
    </td>
</tr>

<?php 
}


 ?>
<tr>
	<td></td>
  <td class="no_print"> <a href="<?php echo WEB_ROOT; ?>admin/customer/welcome/index.php?&id=<?php echo $file_id; ?>&from=customerhome"><button title="View this entry" class="btn viewBtn"><span class="view">View All</span></button></a>
   </td>        
</tr> 

</table>
</div>
<?php } else if(is_array($unreceived_notice_letters) && is_numeric($unreceived_notice_letters[0]['notice_id']))
{
	$showModal=1;
	 ?>
<script type="text/javascript">document.getElementById('unReceivedLetterType').value=<?php echo $unreceived_notice_letters[0]['notice_type']; ?>;
document.getElementById('welcome_letter_file_id').value = <?php echo $file_id; ?>;
</script>     
<div class="detailStyling">

<h4 class="headingAlignment">Unreceived Notice Details </h4>


<table class="insertTableStyling detailStylingTable">
<?php foreach($unreceived_notice_letters as $unreceived_welcome_letter) { ?>
<tr>
    <td class="firstColumnStyling">
     Date :</td>
    <td> 
		<?php echo date('d/m/Y',strtotime($unreceived_welcome_letter['notice_date'])); ?>		
    </td>
    <tr>
    <td>
     
   Type: </td>
   <td>                          
   		<?php  if($unreceived_welcome_letter['notice_type']==0) echo "Customer"; else echo "Guarantor"; ?>					
                               
    </td>
</tr>

  <tr>
    <td>
     
  Unreceived Reason: </td>
   <td>                          
   		<?php  echo $unreceived_welcome_letter['not_received_type']; ?>					
                               
    </td>
</tr>

<?php }


 ?>
 <tr>
	<td></td>
  <td class="no_print"> <a href="<?php echo WEB_ROOT; ?>admin/customer/welcome/index.php?&id=<?php echo $file_id; ?>&from=customerhome"><button title="View this entry" class="btn viewBtn"><span class="view">View All</span></button></a>
   </td>        
</tr> 

</table>
</div>
<?php } ?>
</div>
<div class="clearfix"></div>
<script>
document.balance=<?php echo -$balance; ?>;
<?php if(!(isset($_SESSION['adminSession']['admin_rights']) && (in_array(9,$admin_rights)|| in_array(7,$admin_rights)))) { ?> $(".datepicker1").datepicker('disable'); <?php } ?>
$( "#paid_by" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/broker_name.php',
                { term: request.term }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#paid_by" ).val(ui.item.label);
			return false;
		}
    });
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
	
	function changeChequeDate(payment_date)
	{
		$('#cheque_date').val(payment_date);
	}

</script>