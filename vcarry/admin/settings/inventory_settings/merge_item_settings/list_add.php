<div class="addDetailsBtnStyling no_print"> </div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Merge Items</h4>
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

<table class="insertTableStyling no_print">

<tr>
<td class="firstColumnStyling">
Merge Items<span class="requiredField">* </span> :
</td>

<td>
<select name="item_ids[]" class="city_area selectpicker" multiple="multiple"  id="city_area1" data-live-search="true" >
                    	 <option value="-1" >--Please Select--</option>
                          <?php
						 
                            $areas = listInventoryItems();
                            foreach($areas as $item)
                              {
                             ?>
                             
                             <option value="<?php echo $item['item_id'] ?>"><?php echo $item['item_name']." | ".$item['alias']." | ".$item['item_code']." | ".$item['manufacturer_name']." | ".$item['mfg_item_code']." | ".$item['mrp']; ?></option					>
                             <?php } 
						  
							 ?>
                    </select>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
New Item :
</td>

<td>
<select name="new_item_id" class="city_area selectpicker"   id="city_area1" data-live-search="true" >
                    	 <option value="-1" >--Please Select--</option>
                          <?php
						 
                           
                            foreach($areas as $item)
                              {
                             ?>
                             
                             <option value="<?php echo $item['item_id'] ?>"><?php echo $item['item_name']." | ".$item['alias']." | ".$item['item_code']." | ".$item['manufacturer_name']." | ".$item['mfg_item_code']." | ".$item['mrp']; ?></option					>
                             <?php } 
						  
							 ?>
                    </select>
</td>
</tr>


<tr>
<td></td>
<td>
<input type="submit" value="Merge Items" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>

<!-- <hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Items</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Name</th>
            <th class="heading">Alias</th>
            <th class="heading">Type</th>
            <th class="heading">Unit</th>
            <th class="heading">Item Code</th>
            <th class="heading">MFG</th>
            <th class="heading">MFG Code</th>
             <th class="heading">MOQ</th>
            <th class="heading">Dealer Price</th>
            <th class="heading">MRP</th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$items=listInventoryItems();
		$no=0;
		foreach($items as $item)
		{
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php echo $item['item_name']; ?>
            </td>
             <td><?php if(validateForNull($item['alias'])) echo $item['alias']; else echo "NA"; ?>
            </td>
             <td><?php echo $item['item_type']; ?>
            </td>
             <td><?php if(checkForNumeric($item['item_unit_id'])) echo getItemUnitNameById($item['item_unit_id']); else echo "NA"; ?>
            </td>
             <td><?php echo $item['item_code']; ?>
            </td>
             <td><?php echo $item['manufacturer_name']; ?>
            </td>
             <td><?php echo $item['mfg_item_code']; ?>
            </td>
              <td><?php echo $item['min_quantity_purchase']; ?>
            </td>
              <td><?php echo $item['dealer_price']; ?>
            </td>
             <td><?php echo $item['mrp']; ?>
            </td>
              <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=details&lid='.$item['item_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$item['item_id'] ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$item['item_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
     </div>
       <table id="to_print" class="to_print adminContentTable"></table> -->
</div>
<div class="clearfix"></div>