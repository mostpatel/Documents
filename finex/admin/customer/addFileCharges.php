<?php 
$file_id = $_GET['id'];

if (!checkForNumeric($file_id))
{
	exit;
}

$selected_customer_group_names = getFileChargesById($file_id);

?>



<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add Charges</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=addFileCharges'; ?>" method="post">

<input type="hidden" name="file_id" value="<?php echo $file_id; ?>" />

<table class="insertTableStyling no_print">



<tr>
<td> File Charge <span class="requiredField">* </span>: </td>
				<td>
					<input type="text" name="file_charges" id="file_charges"  value="<?php if(isset($selected_customer_group_names['file_charges'])) echo $selected_customer_group_names['file_charges']; else echo 0; ?>"/>
                            </td>
</tr>


<tr>
<td> Stamp Charges <span class="requiredField">* </span>: </td>
				<td>
					<input type="text" name="stamp_charges" id="stamp_charges"  value="<?php if(isset($selected_customer_group_names['stamp_charges'])) echo $selected_customer_group_names['stamp_charges']; else echo 0; ?>"/>
                            </td>
</tr>

<tr>
<td> Share Money <span class="requiredField">* </span>: </td>
				<td>
					<input type="text" name="share_money" id="share_money"  value="<?php if(isset($selected_customer_group_names['share_money'])) echo $selected_customer_group_names['share_money']; else echo 0; ?>" />
                            </td>
</tr>

<tr>
<td> Guarantor KYC <span class="requiredField">* </span>: </td>
				<td>
					<select name="gua_kyc" id="gua_kyc">
                    <option value="0" <?php if(isset($selected_customer_group_names['gua_kyc']) && $selected_customer_group_names['gua_kyc']==0) { ?> selected="selected" <?php }  ?> selected="selected" >No</option>
                    <option value="1" <?php if(isset($selected_customer_group_names['gua_kyc']) && $selected_customer_group_names['gua_kyc']==1) { ?> selected="selected" <?php }  ?>>Yes</option>
                    </select>
                            </td>
</tr>

<tr>
<td> File Received <span class="requiredField">* </span>: </td>
				<td>
					<select name="file_rec" id="file_rec">
                    <option value="0" <?php if(isset($selected_customer_group_names['file_rec']) && $selected_customer_group_names['file_rec']==0) { ?> selected="selected" <?php }  ?> selected="selected" >No</option>
                    <option value="1" <?php if(isset($selected_customer_group_names['file_rec']) && $selected_customer_group_names['file_rec']==1) { ?> selected="selected" <?php }  ?>>Yes</option>
                    </select>
                            </td>
</tr>


<tr>
<td></td>
<td>
<input type="submit" value="Add" class="btn btn-warning">
<a href="<?php echo WEB_ROOT."admin/customer/index.php?view=details&id=".$file_id ?>">
<input type="button" value="back" class="btn btn-success" />
</a>
</td>
</tr>

</table>
</form>

       
</div>
<div class="clearfix"></div>