<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add a New Sub Category</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post" enctype="multipart/form-data">

<table class="insertTableStyling no_print">

<tr>

<td class="firstColumnStyling">
Sub Category<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="name" id="txtName"/>
</td>
</tr>



<tr>
<td>Select Category : </td>
				<td>
					<select id="category" name="cat_id[]" multiple class="select_picker" style="min-height:250px;">
                        <option value="-1" >--Select Category--</option>
                        <?php
                            $categories = listCategories();
                            foreach($categories as $category)
                              {
                             ?>
                             
                             <option value="<?php echo $category['cat_id'] ?>"><?php echo $category['cat_name'] ?></option					>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>
<td>Image [500x400 Px]<span class="requiredField">* </span> : </td>
				<td>
					<input type="file" id="subCat_img" name="subCat_img"/>
                            </td>
</tr>

<!-- <tr>
<td>Specification Sheet [Excel] : </td>
				<td>
					<input type="file" id="spec_sheet" name="spec_sheet"/>
                            </td>
</tr> -->

<tr>

<td class="firstColumnStyling">
Website Link :
</td>

<td>
<input type="text" name="youtube_link" id="youtube_link"/>
</td>
</tr>

<tr>
<td width="220px" class="firstColumnStyling"> 
Description : 
</td>

<td>
<textarea id="sub_cat_description" class="sub_cat_description" name="sub_cat_description"  cols="5" rows="6"></textarea>
</td>
</tr>




<tr>

<tr>
<td></td>
<td>
<input type="submit" value="Add Product" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Sub Categories</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Sub Category Name</th>
            <th class="heading">Description</th>
            <th class="heading">Category Name</th>
            <th class="heading">Image</th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$subCategories=listSubCategories();
		$i=0;
		foreach($subCategories as $subCategory)
		{
			$catId=$subCategory['cat_id'];
			$catDetails=getCategoryBySubCategoryId($subCategory['sub_cat_id']);
		?>
          <tr class="resultRow">
        	<td><?php echo ++$i; ?>
            </td>
            
            <td><span  class="editLocationName" id="<?php echo $subCategory['sub_cat_id'] ?>"><?php echo $subCategory['sub_cat_name']; ?></span>
            </td>
            
            <td><span  class="editLocationName" id="<?php echo $subCategory['sub_cat_id'] ?>">
			<?php 
			echo $subCategory['sub_cat_description']; 
			?>
            </span>
            </td>
            
            <td>
            <span  class="editLocationName" id="<?php echo $subCategory['cat_id'] ?>">
			<?php 
			foreach($catDetails as $cat)
			{  echo $cat['cat_name']."<br> <hr>";}
			?>
            </span>
            </td>
            
            <td>
            <span  class="editLocationName" id="<?php echo $subCategory['super_cat_id'] ?>">
			 
			<img src="<?php echo WEB_ROOT.'images/category/'.$subCategory['sub_cat_img_path']; ?>" />
            </span>
            </td>
            
            
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=details&lid='.$subCategory['sub_cat_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$subCategory['sub_cat_id'] ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$subCategory['sub_cat_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
    </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>