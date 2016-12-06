<?php 	$suppliers_array=$_SESSION['homePage']['supplier_id_array'];
		$manufacturer_array=$_SESSION['homePage']['manufacturer_id_array'];
 ?>

<table width="100%">

<tr>
<form action="index.php?action=setFilters" method="post" >
<td><h4 class="headingAlignment no_print">Filter By</h4></td>
<td class="firstColumnStyling">
Supplier :
</td>

<td>
<select id="supplier_id" name="supplier_id[]" class="selectpicker" multiple="multiple" >	
	
	<?php $mfgs = listSuppliers(); foreach($mfgs as $mfg) { ?>	
    	<option value="<?php echo $mfg['ledger_id']; ?>" <?php if(is_array($suppliers_array) && !empty($suppliers_array)) { if(in_array($mfg['ledger_id'],$suppliers_array)) { ?> selected="selected" <?php }} ?> ><?php echo $mfg['ledger_name']; ?></option>
	<?php } ?>
</select>
</td>

<td class="firstColumnStyling">
Manufacturer :
</td>

<td>
<select id="manufacturer_id" name="manufacturer_id[]" class="selectpicker" multiple="multiple" >	
	
	<?php $mfgs = listItemManufacturers(); foreach($mfgs as $mfg) { ?>	
    	<option value="<?php echo $mfg['manufacturer_id']; ?>" <?php if(is_array($manufacturer_array) && !empty($manufacturer_array)) { if(in_array($mfg['manufacturer_id'],$manufacturer_array)) { ?> selected="selected" <?php }} ?>><?php echo $mfg['manufacturer_name']; ?></option>
	<?php } ?>
</select>

</td>
<td><input type="submit" value="Apply Filters" class="btn btn-warning"></td>

</form>
<form action="index.php?action=clearFilters" method="post" onSubmit="return clearFilters();" >
<td ><input type="submit" value="Clear Filters"  class="btn btn-danger"></td>
</form>
</tr>
</table>

		
		<div class="container">
    
			<table id="employee-grid" class="adminContentTable">
					<thead>
						<tr>
                        	<th class="heading no_sort">No</th>
							<th class="heading">Item Name</th>
							<th class="heading">SKU</th>
							<th class="heading">UPC</th>
                            <th class="heading">Qty in Stock</th>
                            <th class="heading">Shelf</th>
                            <th class="heading">Supplier</th>
                            <th class="heading">Manufacturer</th>
                            <th class="heading no_sort no_print"></th>
						</tr>
					</thead>
			</table>
		</div>

   
		<script type="text/javascript" language="javascript" >
		var dataTable_custom ;
			$(document).ready(function() {
				
				 dataTable_custom = $('#employee-grid').DataTable( {
					  dom: 'lBfrtip',
        stateSave: true,
        buttons: [
		
		'colvis',
            'copyHtml5',
            'excelHtml5'
			
			 
        ],
            
			 "sPaginationType": "full_numbers",
			
            "aaSorting": [[ 1, 'asc' ]],		 
         
			   "aLengthMenu": [[10, 25, 50, 100,200], [10, 25, 50, 100,200]],
			   "aoColumns": DontSortArray('#employee-grid'),
					"processing": true,
					"serverSide": true,
					"ajax":{
						url :"employee-grid-data.php", // json datasource
						type: "post",  // method  , by default get
						error: function(){  // error handling
							
							
						}
					},
					"fnDrawCallback": function () {
					
            
        }
				} );
			
	
			} );
		function clearFilters()
		{
	
	dataTable_custom.search( '' ).columns().search( '' ).draw();

	
		return true;
		}
		</script>

