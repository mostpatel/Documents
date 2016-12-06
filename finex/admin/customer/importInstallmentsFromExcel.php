<?php 
$file_id = $_GET['id'];
if (!checkForNumeric($file_id))
{
	exit;
}


?>



<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Import Installments From Excel</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=importExcel'; ?>" method="post" enctype="multipart/form-data">

<input type="hidden" name="file_id" value="<?php echo $file_id; ?>" />

<table class="insertTableStyling no_print">



<tr>
<td> Excel File <span class="requiredField">* </span>: </td>
				<td>
                <input type="file" name="emi_file"/>
					
                            </td>
</tr>


<tr>
<td></td>
<td>
<input type="submit" value="Upload" class="btn btn-warning">
<a href="<?php echo WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$customer_id ?>">
<input type="button" value="back" class="btn btn-success" />
</a>
</td>
</tr>

</table>
</form>

       
</div>
<div class="clearfix"></div>