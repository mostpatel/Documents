<?php
if(!isset($_GET['id']))
{
	header("Location: index.php");
	}
	
$noteDetails=getNoteById($_GET['id']);

$note_id=$_GET['id'];
$enquiry_form_id=$_GET['lid'];


?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Note</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=editNote'; ?>" method="post">

<table class="insertTableStyling no_print">

<input type="hidden" name="lid" value="<?php echo $enquiry_form_id ?>" />
<input type="hidden" name="id" value="<?php echo $note_id ?>" />




<tr>
<td> Note  : </td>
<td> <textarea rows="10" cols="6" name="note" id="note" >
<?php echo $noteDetails['note'];?>
</textarea>
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