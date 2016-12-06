<?php
if(!isset($_GET['id']))
{
	header("Location: index.php");
	}
	
$followUpDetails=getFollowUpById($_GET['id']);
$enquiry_form_id=$_GET['lid'];

$follow_up_id=$_GET['id'];


?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Follow Up Details</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=editFollowUp'; ?>" method="post">

<table class="insertTableStyling no_print">

<input type="hidden" name="lid" value="<?php echo $enquiry_form_id ?>" />
<input type="hidden" name="id" value="<?php echo $follow_up_id; ?>" />

<tr>
<td class="firstColumnStyling">
Follow Up Date : 
</td>

<td>
<input type="text" id="datepicker" size="12" autocomplete="off" 
value="<?php 
 $followDate = $followUpDetails['next_follow_up_date'];
  $followDate = date('d/m/Y',strtotime($followDate));
 
  echo $followDate;
   
 ?>"  
name="follow_up_date" class="datepicker2 datepick" placeholder="Click to Select!" />
<span class="customError DateError">Please select a date!</span>
</td>
</tr>


<tr>
<td> Discussion  : </td>
<td> <textarea rows="10" cols="6" name="discussion" id="discussion" ><?php echo $followUpDetails['discussion'];?></textarea></td> 
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