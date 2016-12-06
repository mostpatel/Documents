<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Map Suppliers to The Sector</h4>
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
<td> Sector <span class="requiredField">* </span>: </td>
				<td>
					<select id="sub_cat_id" name="sub_cat_id">
                     <option value="-1" > -- Select Sector -- </option>
                        <?php
                            $sectors = listSubCategories();
                            foreach($sectors as $sector)
                              {
                             ?>
                             
                             <option value="<?php echo $sector['sub_cat_id'] ?>"><?php echo $sector['sub_cat_name'] ?></option					>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>


<tr>
<td> Choose Suppliers <span class="requiredField">* </span>: </td>
				<td>
					<select id="supplier_id" name="supplier_id_array[]" class="selectpic show-tick form-control" multiple data-live-search="true">
                       
                        <?php
                            $suppliers = listSuppliers();
                            foreach($suppliers as $supplier)
                              {
                             ?>
                             
                             <option value="<?php echo $supplier['supplier_id'] ?>"><?php echo $supplier['supplier_name'] ?></option					>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>


<tr>
<td></td>
<td>
<input type="submit" value="Map Supplier" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Supplier-Sector Mapping</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Sector</th>
            <th class="heading">Suppliers</th>
            
             
            <!--<th class="heading no_print btnCol" ></th>-->
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$suppliers = getSectorWithSuppliers();
		$i=0;
		foreach($suppliers as $supplier)
		{
			
			
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$i; ?>
            </td>
            
           <td><span  class="editLocationName" id="<?php echo $supplier['sub_cat_id'] ?>"><?php echo $supplier['sub_cat_name']; ?></span>
            </td>
            
            <td><span  class="editLocationName" id="<?php $supplier['sub_cat_id'] ?>">
			<?php 
			echo $supplier['supplier_names']; 
			?>
            </span>
            </td>
            
    
            
           <!-- <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$supplier['sub_cat_id'] ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td> -->
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$supplier['sub_cat_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
    </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>