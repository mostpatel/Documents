<?php 

$enquiry_id = $_GET['lid'];

if (!checkForNumeric($enquiry_id))
{
	exit;
}
$isBoughtDetails = getIsBoughtVarEnquiryId($enquiry_id);
$enquiryDetails = getEnquiryById($enquiry_id);
$closeLeadDetails = getCloseLeadByEnquiryId($enquiry_id);


$is_bought_var = $isBoughtDetails['is_bought'];

$not_bought_id = $closeLeadDetails['not_bought_id'];
$discussion = $closeLeadDetails['discussion'];
$decline_id = $closeLeadDetails['decline_id'];

?>

<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Lead Closing Details</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=editCloseLead'; ?>" method="post">
<input type="hidden" name="enquiry_id" value="<?php echo $enquiry_id; ?>" />
<table class="insertTableStyling no_print">



<tr>
<td> Product Status <span class="requiredField">* </span> : </td> 
<td>
<table>
<tr><td>
<input type="radio" name="productStatus" value="1" <?php if($is_bought_var==1) { ?> checked="checked" <?php }?>  onchange="toggleProductStatus(this.value)" id="running"></td><td><label for="running">Bought</label></td></tr>
<tr><td>
<input type="radio" name="productStatus" value="2" <?php if($is_bought_var==2) { ?> checked="checked" <?php }?> id="completed"  onchange="toggleProductStatus(this.value)"></td><td><label for="completed">Not bought</label></td></tr></table></td>
</tr>


</table>

<table id="tourDate" class="insertTableStyling no_print" <?php if($is_bought_var==2) { ?> style="display:none;" <?php } ?>>
<tr>
<td class="firstColumnStyling" width="125px">
Tour Date : 
</td>

<td>
<input type="text" id="datepicker_tourDate" size="12" autocomplete="off"  name="purchase_date" class="datepicker2 datepick" placeholder="Click to Select!" value="<?php 
 $purchaseDate = $enquiryDetails['purchase_date'];
 $purchaseDate = date('d/m/Y',strtotime($purchaseDate));
 
  echo $purchaseDate;
   
 ?>" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>


</table>



<table id="declined" <?php if($is_bought_var==1) { ?> style="display:none;" <?php } ?>>

<tr>

<td style="width:150px;"> Reason to Decline : </td>
				<td>
					<select id="decline_id" name="decline_id">
                        <option value="-1" >--Select Reason--</option>
                        <?php
                            $reasons = listReasons();
                            foreach($reasons as $reason)
                              {
                             ?>
                             
                             <option value="<?php echo $reason['decline_id'] ?>" <?php if($reason['decline_id']==$decline_id) { ?> selected="selected" <?php } ?>><?php echo $reason['decline_reason'] ?></option					>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>



<tr>
<td> Description : </td>
<td> 
<textarea rows="10" cols="6" name="description" id="description" >
<?php echo $discussion; ?>
</textarea></td> 
</tr>

</table>




<table>

<tr>
<td style="width:195px;"></td>
<td><input type="submit" class="btn btn-warning" value="Save"/>
<a href="../index.php?view=details&id=<?php echo $enquiry_id ?>"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>


</form>

       
</div>
<div class="clearfix"></div>