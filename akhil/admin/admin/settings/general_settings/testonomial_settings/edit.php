<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
$testo=getTestonomialByID($_GET['lid']);
$testo_id=$_GET['lid'];	
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Testimonial</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="lid" value="<?php echo $testo_id; ?>"  />
<table class="insertTableStyling no_print">



<tr>

<td class="firstColumnStyling">
Person name<span class="requiredField">* </span> :
</td>

<td>
<input type="text" id="person_name" name="person_name" value="<?php echo $testo['person_name']; ?>"/>
</td>
</tr>

<tr>

<tr>

<td class="firstColumnStyling">
Person Company<span class="requiredField">* </span> :
</td>

<td>
<input type="text" id="person_company" name="person_company" value="<?php echo $testo['person_company']; ?>" />
</td>
</tr>

<tr>

<tr>

<td class="firstColumnStyling">
Person Designation<span class="requiredField">* </span> :
</td>

<td>
<input type="text" id="person_designation" name="person_designation" value="<?php echo $testo['person_designation']; ?>"/>
</td>
</tr>



<td class="firstColumnStyling">
Testimonial<span class="requiredField">* </span> :
</td>

<td>
<textarea id="testimonial" name="testonomial"><?php echo $testo['testonomial']; ?></textarea>
</td>
</tr>




<td class="firstColumnStyling">
Person Image For Testimonial <br />[width : 100-200px | height : 150-300px]<span class="requiredField">* </span> :
</td>

<td>
<input type="file" id="location_image" name="testonomial_image"/>
</td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" value="Edit Testimonial" class="btn btn-warning" >
<a href="<?php echo WEB_ROOT ?>admin/settings/general_settings/testonomial_settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>

</div>
<div class="clearfix"></div>