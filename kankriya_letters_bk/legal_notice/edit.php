<?php if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}
$legal_notice_id=$_GET['id'];
$legal_notice=getLegalNoticeById($legal_notice_id);
$file_id = $legal_notice['file_id'];
$file=getFileDetailsByFileId($file_id);
if(is_array($file) && $file!="error")
{
	$customer=getCustomerDetailsByFileId($file_id);
	$customer_id=$customer['customer_id'];
	$loan_id=getLoanIdFromFileId($file_id);
	
	
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

<h4 class="headingAlignment"> Edit Legal / Court Case </h4>
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
<input name="legal_notice_id" value="<?php echo $legal_notice_id; ?>" type="hidden" />
<input name="bucket" value="<?php echo $bucket; ?>" type="hidden" />
<input name="bucket_amount" value="<?php echo $bucket_amount; ?>" type="hidden" />
<input name="cheque_return_id" value="<?php if(isset($_GET['cheque_return_id']) && is_numeric($_GET['cheque_return_id']) && $_GET['cheque_return_id']>0) echo $_GET['cheque_return_id']; ?>" type="hidden" />

<table id="insertInsuranceTable" class="insertTableStyling no_print">
<tr>
<td>Case Date : </td>
				<td>
					<input placeholder="Click to select Date!" type="text" id="notice_date" name="notice_date" value="<?php echo date('d/m/Y',strtotime($legal_notice['notice_date'])); ?>" class="datepicker1 date"  onchange="onChangeDate(this.value,this)" /><span class="ValidationErrors contactNoError">Please select a date!</span>
                            </td>
</tr>

<tr>
<td class="firstColumnStyling">
Court : 
</td>

<td>
<select  name="court_id" >
	<option value="">--Please Select--</option>
	<?php $courts = listCourts();
	foreach($courts as $court)
	{
		?>
        <option value="<?php echo $court['court_id'] ?>" <?php if($court['court_id']==$legal_notice['court_id']) { ?> selected="selected" <?php } ?>><?php echo $court['court']; ?></option>
        <?php
	}
	 ?>
</select>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Case Petetionar : 
</td>

<td>
<select  name="case_petetionar_id" >
	<option value="">--Please Select--</option>
	<?php $courts = listCasePetetionars();
	foreach($courts as $court)
	{
		?>
        <option value="<?php echo $court['case_petetionar_id'] ?>" <?php if($court['case_petetionar_id']==$legal_notice['case_petetionar_id']) { ?> selected="selected" <?php } ?>><?php echo $court['case_petetionar']; ?></option>
        <?php
	}
	 ?>
</select>
</td>
</tr>


<tr>
<td class="firstColumnStyling">
Case Type : 
</td>

<td>
<select  name="case_type_id" >
	<option value="">--Please Select--</option>
	<?php $courts = listCaseTypes();
	foreach($courts as $court)
	{
		?>
        <option value="<?php echo $court['case_type_id'] ?>" <?php if($court['case_type_id']==$legal_notice['case_type_id']) { ?> selected="selected" <?php } ?>><?php echo $court['case_type']; ?></option>
        <?php
	}
	 ?>
</select>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Advocate : 
</td>

<td>
<select  name="advocate_id" >
	<option value="">--Please Select--</option>
	<?php $courts = listAdvocates();
	foreach($courts as $court)
	{
		?>
        <option value="<?php echo $court['advocate_id'] ?>"  <?php if($court['advocate_id']==$legal_notice['advocate_id']) { ?> selected="selected" <?php } ?>><?php echo $court['advocate_name']; ?></option>
        <?php
	}
	 ?>
</select>
</td>
</tr>

<tr>
<td >
Type : 
</td>

<td>
<select  name="type" id="type" >
 <?php if(!isset($_GET['type']) || $_GET['type']==0) { ?>	<option value="0"  <?php if($legal_notice['type']==0) { ?> selected="selected"<?php } ?>>Customer</option> <?php } ?>
 <?php if(!isset($_GET['type']) || $_GET['type']==1) { ?>  <option value="1" <?php if($legal_notice['type']==1) { ?> selected="selected"<?php } ?> >Guarantor</option> <?php } ?>
</select>
</td>
</tr>


<tr>
<td class="firstColumnStyling">
Case No : 
</td>

<td>
<input type="text" name="case_no" id="customer_name" placeholder="Only Letters And Numbers!" value="<?php echo $legal_notice['case_no']; ?>"/>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Warrant Status : 
</td>

<td>
<select name="warrant" id="warrant"  >
	<option value="0"  >NA</option>
    <option value="1" selected="selected" >Not Received</option>
    <option value="2"  >Received </option>
  
</select>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Stage : 
</td>

<td>
<input type="text" name="stage" id="stage" placeholder="Current Stage of Case!" value="<?php echo $legal_notice['stage']; ?>" autofocus/>
</td>
</tr>


<tr>
<td>Next Date : </td>
				<td>
					<input placeholder="Click to select Date!" type="text" id="next_date" name="next_date"  class="datepicker2 date"  value="<?php echo date('d/m/Y',strtotime($legal_notice['next_date'])); ?>" /><span class="ValidationErrors contactNoError">Please select a date!</span>
                            </td>
</tr>

<tr>

<td class="firstColumnStyling">
Remarks : 
</td>

<td>
 <textarea type="text"  name="remarks" id="customer_address" ><?php echo $legal_notice['remarks']; ?></textarea>
</td>
</tr>

<tr>
<td width="250px;"></td>
<td>
<input type="submit" value="Update Notice" class="btn btn-warning">
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