<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add a New Attribute Name</h4>
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
<td> Select Super Category : </td>
<td>
<select id="bs3Select" class="selectpic selectpic1 show-tick form-control" multiple data-live-search="true" name="super_cat_id[]" onchange="loadAttrType()">
       
       <?php
	                 $superCats = listSuperCategories();
						 
						 foreach($superCats as $superCat)
						 {
							 
						?>
                      <option value="<?php echo $superCat['super_cat_id'] ?>"><?php echo $superCat['super_cat_name'] ?></option>
                            
                          <?php
						 }
						  ?> 
</select>


    </td>
    
    <td>
    <input type="button" id="select_all" name="select_all" value="Select All">
    </td>
    
</tr>

<tr>
<td> Select Category : </td>
<td>
<select id="bs4Select" class="selectpic selectpic2 show-tick form-control" multiple data-live-search="true" name="cat_id[]" onchange="loadAttrType()">
       
       <?php
	                 $Cats = listCategories();
						 
						 foreach($Cats as $Cat)
						 {
							 
						?>
                      <option value="<?php echo $Cat['cat_id'] ?>"><?php echo $Cat['cat_name'] ?></option>
                            
                          <?php
						 }
						  ?> 
</select>
    </td>
    
    <td>
    <input type="button" id="select_all2" name="select_all" value="Select All">
    </td>
</tr>

<tr>
<td> Select Sub Category : </td>
<td>
<select id="bs5Select" class="selectpic selectpic3 show-tick form-control" multiple data-live-search="true" name="sub_cat_id[]" onchange="loadAttrType()">
       
       <?php
	                 $subCats = listSubCategories();
						 
						 foreach($subCats as $subCat)
						 {
							 
						?>
                      <option value="<?php echo $subCat['sub_cat_id'] ?>"><?php echo $subCat['sub_cat_name'] ?></option>
                            
                          <?php
						 }
						  ?> 
</select>
    </td>
    <td>
    <input type="button" id="select_all3" name="select_all" value="Select All">
    </td>
</tr>




<tr>
<td> Attribute Type : </td>
				<td>
					<select id="attribute_type_id" name="attribute_type_id">
                        <option value="-1" >--Select Attribute Type--</option>
                        
                              
                         
                            </select> 
                            </td>
</tr>


<tr>

<td class="firstColumnStyling">
Attribute Name<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="name" id="txtName"/>
</td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" value="Add Attribute Name" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Attribute Names</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Attribute Type</th>
            <th class="heading">Attribute Name</th>
            <th class="heading"> Super Category </th>
            <th class="heading"> Category </th>
            <th class="heading"> Sub Category </th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            
        </tr>
    </thead>
    <tbody>
        
        <?php
		$attributeTypes = listAttributesTypes();
		$i=0;
		foreach($attributeTypes as $attributeType)
		{
			$attrTypeId = $attributeType['attribute_type_id'];
			$nameArray = getAttributeNameByAttributeTypeId($attrTypeId);
			
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$i; ?>
            </td>
            
            <td><span  class="editLocationName" id="<?php echo $attributeType['attribute_type_id'] ?>"><?php echo $attributeType['attribute_type']; ?></span>
            </td>
            
            <td>
            <?php
			if($nameArray)
			{
			foreach($nameArray as $na)
			{
				$attrNameId = $na['attribute_name_id'];
			?>
            <span  class="editLocationName" id="<?php echo $na['attribute_name_id'] ?>"><?php echo $na['attribute_name']; ?>
            
            </span> <br />
            <?php
			}}
			else
			{
			  echo "No Names Available Yet!";	
			}
			?>
            </td>
            
            <td>
            <span class="editLocationName">
           <?php 
		   
		  $superCat_array = getSuperCatIdsByAttributeTypeIdAndAttributeNameId($attrTypeId, $attrNameId);
		   
		   foreach($superCat_array as $superCat)
		   {
			    
			    $superCatDetails = getSuperCategoryById($superCat);
				echo $superCatDetails['super_cat_name']; ?> <br />
			<?php   
		   }
		   ?>
            </td>
            
            <td>
            <span class="editLocationName">
           <?php 
		   
		  $cat_array = getCatIdsByAttributeTypeIdAndAttributeNameId($attrTypeId, $attrNameId);
		   
		   foreach($cat_array as $cat)
		   {
			    
			    $catDetails = getCategoryById($cat);
				echo $catDetails['cat_name']; ?> <br />
			<?php   
		   }
		   ?>
            </td>
            
            <td>
            <span class="editLocationName">
           <?php 
		   
		  $subCat_array = getSubCatIdsByAttributeTypeIdAndAttributeNameId($attrTypeId, $attrNameId);
		   
		   foreach($subCat_array as $subCat)
		   {
			    
			    $subCatDetails = getSubCategoryById($subCat);
				echo $subCatDetails['sub_cat_name']; ?> <br />
			<?php   
		   }
		   ?>
            </td>
            
            
            
             <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=details&lid='.$attributeType['attribute_type_id']  ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$attributeType['attribute_type_id']  ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
            
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
    </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div>




<div class="clearfix"></div>




<script>


$('#select_all').click(function() {
	
    $('.selectpic1').selectpicker('selectAll');
});
</script>




<script>

$('#select_all2').click(function() {
	
    $('.selectpic2').selectpicker('selectAll');
});
</script>

<script>

$('#select_all3').click(function() {
	
    $('.selectpic3').selectpicker('selectAll');
});
</script>