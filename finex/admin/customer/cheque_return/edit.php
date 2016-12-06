<?php if(!isset($_GET['lid']))
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}
$cheque_return_id=$_GET['lid'];
$cheque_return=getChequeReturnDetailsForId($cheque_return_id);
$file_id = $cheque_return['file_id'];
if(is_array($cheque_return) && $cheque_return!="error")
{
	
	
	
}
else
{
	$_SESSION['ack']['msg']="Invalid File!";
	$_SESSION['ack']['type']=4; // 4 for error
	header("Location: ".WEB_ROOT."admin/search");
	exit;
}

 ?>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment"> Edit Cheque Return Details </h4>
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
<form  id="addNoticeForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" method="post">
<input name="cheque_return_id" value="<?php echo $cheque_return_id; ?>" type="hidden" />
<table id="insertInsuranceTable" class="insertTableStyling no_print">
<tr>
<td width="220px">Bank Name<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="bank_name" id="bank" placeholder="Only Letters!" autocomplete="off" value="<?php echo $cheque_return['bank_name'] ?>" />
                            </td>
</tr>
<tr>
<td width="220px">Branch Name<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="branch_name" id="branch" placeholder="Only Letters!" autocomplete="off" value="<?php echo $cheque_return['branch_name'] ?>"  />
                            </td>
</tr>
<tr>
<td width="220px">Cheque Amount<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="cheque_amount" id="cheque_amount" placeholder="Only Digits!" value="<?php echo $cheque_return['cheque_amount'] ?>" />
                            </td>
</tr>
<tr>
<td width="220px">Cheque No<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="cheque_no" id="cheque_no" placeholder="Only Digits!" value="<?php echo $cheque_return['cheque_no'] ?>" />
                            </td>
</tr>
<tr>
<td width="220px">Cheque Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="cheque_date" id="cheque_date" class="datepicker3" placeholder="click to select date!" value="<?php echo date('d/m/Y',strtotime($cheque_return['cheque_date'])); ?>" /><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>
<tr>
<td >
Slip No : 
</td>

<td>
<input type="text" name="slip_no" id="" placeholder="Only Numbers!" autofocus value="<?php echo $cheque_return['slip_no'] ?>" />
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Received Status : 
</td>

<td>
<select name="received_status" id="received_status" onchange="onChangeRedAdReceivedStatus()" >
	<option value="0"  <?php if($cheque_return['received']==0){ ?> selected="selected" <?php } ?>>Status Unknown</option>
    <option value="1" <?php  if($cheque_return['received']==1){ ?> selected="selected" <?php } ?>>Received</option>
    <option value="2" <?php  if($cheque_return['received']==2){ ?> selected="selected" <?php } ?>>Not Received </option>
    <option value="3" <?php  if($cheque_return['received']==3){ ?> selected="selected" <?php } ?>>Resent</option>
</select>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Received / Not Received Date : 
</td>

<td>
<input type="text" name="received_date" id="received_date" value="<?php if($cheque_return['received_date']!="1970-01-01") echo date('d/m/Y',strtotime($cheque_return['received_date'])); else echo date('d/m/Y'); ?>" class="datepicker1" />
</td>
</tr>


<tr id="not_received_reason_tr"  <?php if($cheque_return['received']!=2)  {?> style="display:none;" <?php } ?>>
<td class="firstColumnStyling">
Not Received Reason : 
</td>

<td>
<select name="not_received_reason" id="not_received_reason"  >
	<?php $reasons = listRegAdNotReceivedTypes(); foreach( $reasons as $reason) { ?>
	<option value="<?php echo $reason['not_received_type_id']; ?>"  <?php if($reason['not_received_type_id']==$cheque_return['not_received_type_id']){ ?> selected="selected" <?php } ?>><?php echo $reason['not_received_type']; ?></option>
    <?php } ?>
   
</select>
</td>
</tr>


<tr>

<td class="firstColumnStyling">
Remarks : 
</td>

<td>
 <textarea type="text"  name="remarks" id="remarks" ><?php echo $cheque_return['remarks'] ?></textarea>
</td>
</tr>

<tr>
<td width="250px;"></td>
<td>
<input type="submit" value="Edit Cheque Return" class="btn btn-warning">
<?php if(isset($_GET['from']) && $_GET['from']=='customerhome') { ?>
<a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=details&id=<?php echo $file_id; ?>"><input type="button" class="btn btn-success" value="back"></a>
<?php } else { ?>
<a href="<?php echo WEB_ROOT; ?>admin/customer/EMI/index.php?view=details&id=<?php echo $file_id; ?>"><input type="button" class="btn btn-success" value="back"></a><?php } ?>
</td>
</tr>

</table>

</form>

</div>
<div class="clearfix"></div>
<script>
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
	
	function onChangeRedAdReceivedStatus()
{
	var not_received_status = document.getElementById('received_status').value;
	
	if(not_received_status==2)
	{
		$('#not_received_reason_tr').show();	
	}
	else
	$('#not_received_reason_tr').hide();
	
}
	
</script>