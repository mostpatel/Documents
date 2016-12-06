<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
$advocate=getAdvocateById($_GET['lid']);

$advocate_id=$_GET['lid'];	
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit advocate</h4>
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
<form id="addAgencyForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" method="post" onsubmit="return checkCheckBox()">
<input type="hidden" name="lid" value="<?php echo $advocate['advocate_id'] ?>" />
<table class="insertTableStyling no_print">

<tr>

<td class="firstColumnStyling">
advocate Name<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="name" id="name" value="<?php echo $advocate['advocate_name'] ?>"/>
</td>
</tr>

<?php if(defined('SECONDARY_NAME') && SECONDARY_NAME==1) { ?>
<tr>

<td width="230px" class="firstColumnStyling">
Secondary Customer's Name<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="secondary_name" id="transliterateTextarea" class="person_name" placeholder="Only Letters" value="<?php echo $advocate['secondary_advocate_name'] ?>"/>
</td>
</tr>
<?php } ?>

<tr>
<td>
Address : 
</td>

<td>
<textarea name="address" cols="5" rows="6" id="address"><?php echo $advocate['advocate_address'] ?></textarea>
</td>
</tr>




<tr>
<td> Contact Number : </td>
<td> <input type="text" name="contactNo" value="<?php echo $advocate['contact_no']; ?>"/> </tr>
</tr>

<tr>
<td> Contact Number (2) : </td>
<td> <input type="text" name="contactNo2" value="<?php echo $advocate['contact_no2']; ?>"/> </tr>
</tr>


<tr>
<td></td>
<td>
<input type="submit" value="Edit" class="btn btn-warning">
<a href="index.php"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>
</table>
</form>


</div>
<div class="clearfix"></div>