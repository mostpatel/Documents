<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
	
$enquiryDetails=getEnquiryById($_GET['lid']);
$enquiry_form_id=$_GET['lid'];
$customer_type_refernce_ids = listReferenceCustomerTypeIDString();

$kmDetails = getKMByEnquiryID($enquiry_form_id);
$km = $kmDetails['km'];
?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Enquiry Details</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=editEnquiry'; ?>" method="post">

<table class="insertTableStyling no_print">

<input type="hidden" name="lid" value="<?php echo $enquiry_form_id ?>" />

<tr>
<td width="200px">
 Enquiry Type <span class="requiredField">* </span> : 
</td>
<td>

<select type="text" name="enquiryType" id="enquiryType" onchange="isEnquiryTypeRefrence(this.value)">
	<option value="-1">-- Please Select --</option>
    <?php $customerTypes = listCustomerTypes();
	foreach($customerTypes as $customerType)
	{
	?>
    <option value="<?php echo $customerType['customer_type_id'] ?>" <?php if($customerType['customer_type_id']==$enquiryDetails['customer_type_id']) { ?> selected="selected" <?php } ?>> <?php echo $customerType['customer_type']; ?></option>
    <?php 	
		
	}
	 ?>
</select> 
</td>
</tr>



<tr>
<td> Enquiry discussion  : </td>
<td> <textarea rows="10" cols="6" name="discussion" id="discussion" ><?php echo $enquiryDetails['enquiry_discussion'];?></textarea></td> 
</tr>

<tr>

<td class="firstColumnStyling">
Budget <span class="requiredField">* </span> :
</td>

<td>

<input type="text" name="budget" id="txtName" value="<?php echo $enquiryDetails['budget']; ?>"/>
</td>
</tr>


<tr>
<td class="firstColumnStyling">
1st Reminder Date : 
</td>

<td>
<input type="text" id="datepicker" size="12" autocomplete="off" 
value="<?php 
 $firstDate = $enquiryDetails['follow_up_date'];
  $firstDate = date('d/m/Y',strtotime($firstDate));
  if($firstDate=="01/01/1970")
  {
   echo "";
   }
   else
   {
  echo $firstDate;
   }
 ?>"  
name="follow_up_date" class="datepicker2 datepick" placeholder="Click to Select!" />
<span class="customError DateError">Please select a date!</span>
</td>
</tr>


<tr>

<td class="firstColumnStyling">
KM Travelled <span class="requiredField">* </span> :
</td>

<td>

<input type="text" name="km" id="km" value="<?php echo $km; ?>"/>
</td>
</tr>



<tr>
<td></td>
<td>
<input type="submit" value="Save" class="btn btn-warning">

<a href="<?php echo WEB_ROOT.'admin/customer/index.php?view=details&id='.$enquiry_form_id ?>">
<input type="button" value="back" class="btn btn-success" />
</a>

</td>
</tr>

</table>
</form>


</div>
<div class="clearfix"></div>
<script type="text/javascript">
document.customer_refernce_types = "<?php echo $customer_type_refernce_ids ?>";
</script>