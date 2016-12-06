<div class="addDetailsBtnStyling no_print"> </div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Check Stock For Order</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=deductStockExcel'; ?>" method="post" onSubmit="return ValidateForm(this);" enctype="multipart/form-data">

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
<hr class="firstTableFinishing" />
 

	
 <?php if(isset($_SESSION['cDeductStockReport']['emi_array']))
{
	
	$emi_array=$_SESSION['cDeductStockReport']['emi_array'];
	unset($_SESSION['cDeductStockReport']['emi_array']);	
	$instock_array = $emi_array[0];
	$out_of_stock_array = $emi_array[1];
	$error_array = $emi_array[2];
		
	 ?>    
     <div class="no_print">
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>  
   
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Date</label> 
       <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">PO No</label> 
  		<input class="showCB" type="checkbox" id="4" checked="checked"   /><label class="showLabel" for="4">Supplier</label>
        <input class="showCB" type="checkbox" id="5" checked="checked"   /><label class="showLabel" for="5">Items</label>
        <input class="showCB" type="checkbox" id="6" checked="checked"   /><label class="showLabel" for="6">Remarks</label>
    </div>
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
         <th class="heading no_print no_sort"><input type="checkbox" id="selectAllTR" name="selectAllTR"  /></th>
        	 <th class="heading">No</th>
             <th class="heading">Item Name</th>
             <th class="heading">Sku</th>
             <th class="heading">Required Qty</th>
             <th class="heading">Qty In Stock</th>
             <th class="heading">Difference</th>
             <th class="heading">Shelf</th>
             <th class="heading">Secondary Sku X Qty</th>
             <th class="heading no_print btnCol no_sort" ></th>
        </tr>
    </thead>
    <tbody>
        <?php
	
		
		foreach($error_array as $sku=>$value)
		{			
		
		 ?>
          <tr class="resultRow dangerRow" style="background:#F2DEDE;">
          <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$no; ?>
            </td>
             <td>-
            </td>
            <td><?php echo $sku; ?>
            </td>
            <td>-
            </td>
            <td>-
            </td>
           <td>-
            </td>
             <td >-
            
            </td>
    		<td></td>
            
           
            <td class="no_print"> -
            </td>
            
        </tr>
        <?php  } ?>
       <?php
	
		
		foreach($out_of_stock_array as $item_id => $item_array)
		{			
		
		 ?>
          <tr class="resultRow warningRow" style="background:#F2DEDE;">
          <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$no; ?>
            </td>
             <td><?php echo $item_array['item']['item_name']; ?>
            </td>
            <td><?php echo $item_array['item']['item_code']; ?>
            </td>
            <td><?php echo $item_array['order_qty']; ?>
            </td>
            <td><?php  echo $item_array['stock_qty']; ?>
            </td>
           <td><?php  echo $item_array['stock_qty']-$item_array['order_qty']; ?>
            </td>
             <td ><?php echo $item_array['item']['godown_name']; ?>
            
            </td>
    		<td><?php foreach($item_array['sku_wise'] as $sku_wise) { echo $sku_wise;  } ?></td>
            
           
            
           
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/settings/inventory_settings/item_settings/index.php?view=edit&lid='.$item_array['item']['item_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">E</span></button></a>
            </td>
            
        </tr>
        <?php  } ?>
        
          <?php
	
		
		foreach($instock_array as $item_id => $item_array)
		{			
		
		 ?>
          <tr class="resultRow shantiRow" style="background:#DFF0D8;">
          <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$no; ?>
            </td>
             <td><?php echo $item_array['item']['item_name']; ?>
            </td>
            <td><?php echo $item_array['item']['item_code']; ?>
            </td>
            <td><?php echo $item_array['order_qty']; ?>
            </td>
            <td><?php  echo $item_array['stock_qty']; ?>
            </td>
           <td><?php  echo $item_array['stock_qty']-$item_array['order_qty']; ?>
            </td>
             <td ><?php echo $item_array['item']['godown_name']; ?>
            
            </td>
    		<td><?php foreach($item_array['sku_wise'] as $sku_wise) { echo $sku_wise;  } ?></td>
            
           
            
           
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/settings/inventory_settings/item_settings/index.php?view=edit&lid='.$item_array['item']['item_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">E</span></button></a>
            </td>
            
        </tr>
        <?php  } ?>
         
            </tbody>
    </table>
   
    </div>
     <table class="reportFiltersTable">
    <tr>
    	<td> From : <?php if(isset($_SESSION['cDeductStockReport']['from']) && $_SESSION['cDeductStockReport']['from']!="") echo $_SESSION['cDeductStockReport']['from']; else echo "NA"; ?></td>
        <td> To : <?php if(isset($_SESSION['cDeductStockReport']['to']) && $_SESSION['cDeductStockReport']['to']!="") echo $_SESSION['cDeductStockReport']['to']; else echo "NA"; ?></td>
    </tr>
    </table> 
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 

<?php  } ?>      
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