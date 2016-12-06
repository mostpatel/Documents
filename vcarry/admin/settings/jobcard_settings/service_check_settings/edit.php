<?php
if(!isset($_GET['lid']))
{
header("Location: index.php");
exit;
}
$bank_id=$_GET['lid'];
$bank=getServiceCheckById($bank_id);
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Service Check Details</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" method="post">
<table class="insertTableStyling no_print">

<tr>

<td class="firstColumnStyling">
Service Check Name<span class="requiredField">* </span> : 
</td>

<td>
<input type="hidden" name="lid"  value="<?php echo $bank['service_check_id']; ?>"/>
<input type="text" name="service_check" id="txtbank" value="<?php echo $bank['service_check']; ?>"/> 
</td>
</tr>
 <tr>
<td>
Type<span class="requiredField">* </span> : 
</td>

<td>
<select name="check_type" id="txtbranch">
	<option value="0" <?php if($bank['check_type']==0) { ?> selected="selected" <?php } ?>>Single Value Selection</option>
    <option value="1"  <?php if($bank['check_type']==1) { ?> selected="selected" <?php } ?> >Multiple Value Selection</option>
    <option value="2"  <?php if($bank['check_type']==2) { ?> selected="selected" <?php } ?>>Yes or No</option>
</select> 
</td>
</tr> 

<tr>
<td></td>
<td>
<input type="submit" value="Edit" class="btn btn-warning">
<a href="index.php"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

<?php $branches=listServiceCheckValuesForServiceCheck($bank['service_check_id']);

if(count($branches)>0)
{

?>
<tr>
<td class="headingAlignment">Values</td>
<td></td>
</tr>
<?php	

foreach($branches as $branch)
{
?>
<tr>
<td>
<?php if($bank['check_type']!=2) { ?><span  class="editBranchName" id="<?php echo $branch['service_check_value_id'] ?>"><?php } ?><?php echo $branch['service_check_value']; ?><?php if($bank['check_type']!=2) { ?></span><?php } ?></td>
<td><?php if($bank['check_type']!=2) { ?><span class="editBranchBtn btn btn-warning">Edit</span> <a href="<?php echo $_SERVER['PHP_SELF'].'?action=deleteBranch&lid='.$branch['service_check_value_id']."&bid=".$bank_id; ?>"><span class="deleteBranchBtn btn btn-danger">Delete</span></a><?php } ?>
</td>
</tr>
<?php }} ?>


</table>
</form>
<?php if($bank['check_type']!=2) { ?>
<hr class="firstTableFinishing" />

<h4 class="headingAlignment no_print">Add Value</h4>
<form id="addBranchForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=addValue'; ?>" method="post">
<table class="insertTableStyling no_print">

<tr>

<td class="firstColumnStyling">
Value<span class="requiredField">* </span> : 
</td>

<td>
<input type="hidden" name="lid"  value="<?php echo $bank['service_check_id']; ?>"/>
<input type="text" name="service_check_value" id="txtbranch"/> 
</td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" value="Add Value" class="btn btn-warning">
</td>
</tr>
</table>
</form>
<?php } ?>
</div>
<div class="clearfix"></div>
