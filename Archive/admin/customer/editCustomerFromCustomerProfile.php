<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
	
$customerDetails=getCustomerById($_GET['lid']);
$customer_id=$_GET['lid'];
$contactNumbers = getCustomerContactNo($customer_id);

?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Customer Details</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=editCustomerFromCustomerProfile'; ?>" method="post">

<table class="insertTableStyling no_print">
<input type="hidden" name="lid" value="<?php echo $customerDetails['customer_id'] ?>" />
<tr>

<td class="firstColumnStyling">
DOB <span class="requiredField">* </span> :
</td>

<td>


<input type="text" name="name" id="txtName" value="<?php echo $customerDetails['customer_name']; ?>"/>
</td>
</tr>


       
<tr>
<td class="firstColumnStyling">
Address<span class="requiredField">* </span> :
</td>

<td>

<input type="text" name="email" id="mrp" value="<?php echo $customerDetails['customer_email']; ?>"/>
</td>
</tr>

<tr>
<td width="200px">
City<span class="requiredField">* </span> : 
</td>
<td>

<select type="text" name="city_id" id="city_id">
	<option value="-1">-- Please Select --</option>
    <?php $cities=listCities();
	foreach($cities as $city)
	{
	?>
    <option value="<?php echo $city['city_id'] ?>" <?php if($city['city_id']==$subCategory['city_id']) { ?> selected="selected" <?php } ?>> <?php echo $city['city_name']; ?></option>
    <?php 	
		
	}
	 ?>
</select> 
</td>
</tr>





<tr>
<td></td>
<td>
<input type="submit" value="Save" class="btn btn-warning">

<a href="<?php echo WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$customer_id ?>">
<input type="button" value="back" class="btn btn-success" />
</a>

</td>
</tr>

</table>
</form>


</div>
<div class="clearfix"></div>