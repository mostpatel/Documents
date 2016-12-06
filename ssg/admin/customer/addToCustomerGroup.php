<?php 
$customer_id = $_GET['id'];
if (!checkForNumeric($customer_id))
{
	exit;
}

$selected_customer_group_names = getGroupsForFileId($customer_id);

foreach($selected_customer_group_names as $selected_customer_group_name)
		
		{
		  $selected_customer_group_name_array[] = $selected_customer_group_name['group_id'];  
		}  

?>



<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add To Customer Group</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=addToCustomerGroup'; ?>" method="post">

<input type="hidden" name="file_id" value="<?php echo $customer_id; ?>" />

<table class="insertTableStyling no_print">



<tr>
<td> Add To Customer Group <span class="requiredField">* </span>: </td>
				<td>
					<select id="bs3Select" name="customer_group_id[]" data-live-search="true" class="city_area selectpicker" multiple="multiple" >
                       
                        <?php
                            $listFileGroups = listFileGroups();
                            foreach($listFileGroups as $customerGroup)
							
                              {
								 
                             ?>
                             
                             <option value="<?php echo $customerGroup['group_id'] ?>" <?php if(in_array($customerGroup['group_id'], $selected_customer_group_name_array)) { ?> selected="selected" <?php } ?>> <?php echo $customerGroup['group_name'] ?>
                             
                             </option>
                             <?php 
							 } 
							 ?>
                              
                         
                            </select> 
                            </td>
</tr>


<tr>
<td></td>
<td>
<input type="submit" value="Add" class="btn btn-warning">
<a href="<?php echo WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$customer_id ?>">
<input type="button" value="back" class="btn btn-success" />
</a>
</td>
</tr>

</table>
</form>

       
</div>
<div class="clearfix"></div>