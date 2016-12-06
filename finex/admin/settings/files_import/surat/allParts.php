<hr class="firstTableFinishing" />

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
            <th class="heading">SKU</th>
            <th class="heading">MFG</th>
            <th class="heading">UPC</th>
            <th class="heading">Shelf</th>
           
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
             <td><?php if(validateForNull($item['unit_name'])) echo $item['unit_name']; else echo "NA"; ?>
            </td>
             <td><?php echo $item['item_code']; ?>
            </td>
             <td><?php echo $item['manufacturer_name']; ?>
            </td>
             <td><?php echo $item['mfg_item_code']; ?>
            </td>
              <td><?php echo $item['godown_name']; ?>
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
       <table id="to_print" class="to_print adminContentTable"></table> 