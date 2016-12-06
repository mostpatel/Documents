<?php
if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}
$notice_id=$_GET['id'];
$notice=getWelcomeById($notice_id);
$file_id=$notice['file_id'];
$file = getFileDetailsByFileId($file_id);
$loan=getLoanDetailsByFileId($file_id);
$vehicle=getVehicleDetailsByFileId($file_id);

?><div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment"> Update Welcome Letter </h4>
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
<input name="file_id" value="<?php echo $file_id; ?>" type="hidden" />
<input name="welcome_id" value="<?php echo $notice_id ?>" type="hidden" />
<table id="insertInsuranceTable" class="insertTableStyling no_print">
<tr>
<td>Welcome Letter Date : </td>
				<td>
					<input placeholder="Click to select Date!" type="text" id="welcome_date" name="welcome_date" value="<?php echo date('d/m/Y',strtotime($notice['welcome_date'])); ?>" disabled="disabled" class="datepicker1 date"  onchange="onChangeDate(this.value,this)" /><span class="ValidationErrors contactNoError">Please select a date!</span>
                            </td>
</tr>
<tr>
<td class="firstColumnStyling">
Customer Name : 
</td>

<td>
<input type="text" name="customer_name" id="customer_name" placeholder="Only Letters!" disabled="disabled" value="<?php  echo $notice['customer_name']; ?>"/>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Customer Address : 
</td>

<td>
 <textarea type="text"  name="customer_address" disabled="disabled" id="customer_address" ><?php echo $notice['customer_address'];  ?></textarea>
</td>
</tr>
<tr>
<td class="firstColumnStyling">
Guarantor Name : 
</td>

<td>
<input type="text" name="guarantor_name" id="guarantor_name" placeholder="Only Letters!" disabled="disabled" value="<?php   echo $notice['guarantor_name']; ?>"/>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Guarantor Address : 
</td>

<td>
 <textarea type="text"  name="guarantor_address" id="guarantor_address" disabled="disabled" ><?php echo $notice['guarantor_address']; ?></textarea>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Vehicle Model : 
</td>

<td>
<input type="text" name="vehicle_model" id="vehicle_model" placeholder="Only Letters!"  value="<?php echo $notice['vehicle_model']; ?>" disabled="disabled"/>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Welcome Letter Type : 
</td>

<td>
<select name="welcome_type" id="welcome_type" disabled="disabled" >
<option value="0" <?php if($notice['welcome_type']==0) { ?> selected="selected" <?php } ?>>Customer</option>
<option value="1" <?php if($notice['welcome_type']==1) { ?> selected="selected" <?php } ?>>Guarantor</option>
</select>


</td>
</tr>

<tr>
<td class="firstColumnStyling">
Registered Ad : 
</td>

<td>
<input type="text" name="reg_ad" id="reg_ad" value="<?php echo $notice['reg_ad']; ?>" autofocus="autofocus" />
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Received Status : 
</td>

<td>
<select name="received_status" id="received_status" onchange="onChangeRedAdReceivedStatus()" >
	<option value="0"  <?php if($notice['received']==0){ ?> selected="selected" <?php } ?>>Status Unknown</option>
    <option value="1" <?php  if($notice['received']==1){ ?> selected="selected" <?php } ?>>Received</option>
    <option value="2" <?php  if($notice['received']==2){ ?> selected="selected" <?php } ?>>Not Received </option>
    <option value="3" <?php  if($notice['received']==3){ ?> selected="selected" <?php } ?>>Resent</option>
</select>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Received / Not Received Date : 
</td>

<td>
<input type="text" name="received_date" id="received_date" value="<?php if($notice['received_date']!="1970-01-01") echo date('d/m/Y',strtotime($notice['received_date'])); else echo date('d/m/Y'); ?>" class="datepicker1" />
</td>
</tr>


<tr id="not_received_reason_tr" style="display:none;">
<td class="firstColumnStyling">
Not Received Reason : 
</td>

<td>
<select name="not_received_reason" id="not_received_reason"  >
	<?php $reasons = listRegAdNotReceivedTypes(); foreach( $reasons as $reason) { ?>
	<option value="<?php echo $reason['not_received_type_id']; ?>"  <?php if($reason['not_received_type_id']==$notice['not_received_type_id']){ ?> selected="selected" <?php } ?>><?php echo $reason['not_received_type']; ?></option>
    <?php } ?>
   
</select>
</td>
</tr>

<tr>
<td width="250px;"></td>
<td>
<input type="submit" value="Update Welcome Letter" class="btn btn-warning">
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
<script type="text/javascript">
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