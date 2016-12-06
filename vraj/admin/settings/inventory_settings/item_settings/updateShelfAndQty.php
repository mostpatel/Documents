<div class="addDetailsBtnStyling no_print"> </div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Update Shelf, Qty, Sku, Name</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=updateShelfAndQty'; ?>" method="post" onSubmit="return ValidateForm(this);" enctype="multipart/form-data">
<h4>Excel Columns Should in Order as follows : <ul><li>1. UPC</li><li> 2. Shelf</li><li> 3. Qty</li><li> 4. SKU</li><li> 5. Item Name</li></ul></h4>
<table id="insertItemTable" class="insertTableStyling no_print">
<tr id="">
<td>
Excel File : 
<br />(.xls,.xlsx,.csv)
</td>
<td>
<input type="file" name="excel_file" class="customerFile"  />
</td>
</tr> 

<tr>
<td></td>
<td>
<input type="submit" value="upload" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>
<?php
if(isset($_SESSION['update_shelf_non_upc']) && is_array($_SESSION['update_shelf_non_upc']))
{
	$non_upc_items = $_SESSION['update_shelf_non_upc'];
?>
<table id="adminContentTable" class="adminContentTable">
    <thead>
    <tr>
    <th class="heading">No</th>
	<th class="heading">UPC NOT IN THE DATABASE</th>
</tr>
</thead>
<tbody>
<?php	
$no=1;
	foreach($non_upc_items as $non_upc_item)
	{
	
?>
<tr class="resultRow">
	<td><?php echo $no++; ?></td>
	<td><?php  echo $non_upc_item; ?></td>
</tr>
<?php 	
	
	}
?>
</tbody>
</table>
<?php	
}
 ?>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
var _validFileExtensions = [".xls", ".xlsx", ".csv"];    
function ValidateForm(oForm) {
    var arrInputs = oForm.getElementsByTagName("input");
    for (var i = 0; i < arrInputs.length; i++) {
        var oInput = arrInputs[i];
        if (oInput.type == "file") {
            var sFileName = oInput.value;
            if (sFileName.length > 0) {
                var blnValid = false;
                for (var j = 0; j < _validFileExtensions.length; j++) {
                    var sCurExtension = _validFileExtensions[j];
                    if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                        blnValid = true;
                        break;
                    }
                }
                
                if (!blnValid) {
                    alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                    return false;
                }
            }
        }
    }
  
    return true;
}
</script>