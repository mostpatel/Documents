<?php 

$enquiry_id = $_GET['id'];
if (!checkForNumeric($enquiry_id))
{
	exit;
}
$selected_enquiry_group_names = getEnquiryGroupNamesByEnquiryId($enquiry_id);

foreach($selected_enquiry_group_names as $selected_enquiry_group_name)
		{
		  $selected_enquiry_group_name_array[] = $selected_enquiry_group_name['enquiry_group_name'];  
		}  
		
		
?>



<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add To Enquiry Group</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=addToEnquiryGroup'; ?>" method="post">

<input type="hidden" name="enquiry_id" value="<?php echo $enquiry_id; ?>" />

<table class="insertTableStyling no_print">



<tr>
<td> Add To Enquiry Group <span class="requiredField">* </span>: </td>
				<td>
					<select id="bs3Select" name="enquiry_group_id[]" class="selectpic show-tick form-control" multiple data-live-search="true">
                       
                        <?php
                            $enquiryGroups = listEnquiryGroups();
                            foreach($enquiryGroups as $enquiryGroup)
							
                              {
								 
                             ?>
                             
                             <option value="<?php echo $enquiryGroup['enquiry_group_id'] ?>" 
							 <?php if(in_array($enquiryGroup['enquiry_group_name'], $selected_enquiry_group_name_array)) { ?> selected="selected" <?php } ?>> <?php echo $enquiryGroup['enquiry_group_name'] ?>
                             
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
<a href="<?php echo WEB_ROOT ?>admin/customer/index.php?view=details&id=<?php echo $enquiry_id;  ?>"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>

       
</div>
<div class="clearfix"></div>