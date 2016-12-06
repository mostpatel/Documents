<?php 

$enquiry_id = $_GET['id'];
if (!checkForNumeric($enquiry_id))
{
	exit;
}

?>

<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Close the Lead</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post">
<input type="hidden" name="enquiry_id" value="<?php echo $enquiry_id; ?>" />
<table class="insertTableStyling no_print">


<tr>
<td> Send SMS? <span class="requiredField" class="firstColumnStyling" width="125px">* </span>: </td>
				<td>
					<select id="sms_status" name="sms_status">
                    
                    <option value="1"> Yes </option>
                    <option value="0"> No </option>        
                              
                    </select> 
                     
                </td>
</tr>

<tr>
<td> Status <span class="requiredField">* </span> : </td> 
<td>
<table><tr><td><input type="radio" name="productStatus" value="1" checked="checked"  onchange="toggleProductStatus(this.value)" id="running"></td><td><label for="running"><?php echo booked ?></label></td></tr>
<tr><td>
<input type="radio" name="productStatus" value="2" id="completed" onchange="toggleProductStatus(this.value)"></td><td><label for="completed"><?php echo not_booked ?></label></td></tr></table></td>
</tr>

</table>

<table id="tourDate" class="insertTableStyling no_print">
<tr>
<td class="firstColumnStyling" width="125px">
<?php echo tour_departure_date ?> :
</td>

<td>
<input type="text" id="datepicker_tourDate" size="12" autocomplete="off"  name="purchase_date" class="datepicker2 datepick purchase_date" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>

<tr>
<td class="firstColumnStyling" width="125px">
<?php echo tour_ending_date ?> :
</td>

<td>
<input type="text" id="datepicker" size="12" autocomplete="off"  name="tour_ending_date" class="datepicker2 datepick expiry_date" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>


</table>


<table id="declined" style="display:none;">

<tr>

<td style="width:150px;"> Reasons to Decline : </td>
				<td>
					<select id="category" name="decline_id">
                        <option value="-1" >--Select Category--</option>
                        <?php
                            $reasons = listReasons();
                            foreach($reasons as $reason)
                              {
                             ?>
                             
                             <option value="<?php echo $reason['decline_id'] ?>"><?php echo $reason['decline_reason'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>



<tr>
<td> Description : </td>
<td> <textarea rows="10" cols="6" name="description" id="description" ></textarea></td> 
</tr>

</table>




<table>

<tr>
<td style="width:155px;"></td>
<td><input type="submit" value="Close Lead" class="btn btn-warning">
<a href="../index.php?view=details&id=<?php echo $enquiry_id ?>"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>


</form>

       
</div>
<div class="clearfix"></div>