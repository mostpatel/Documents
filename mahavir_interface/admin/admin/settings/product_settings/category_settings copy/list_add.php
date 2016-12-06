<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add a New Category</h4>
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
Category<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="name" id="txtName"/>
</td>
</tr>

<!--<tr>
<td>Select Super Category <span class="requiredField">* </span>: </td>
				<td>
					<select id="superCategory" name="super_cat_id">
                        <option value="-1" >--Select Super Category--</option>
                        <?php
                            $superCategories = listSuperCategories();
                            foreach($superCategories as $superCategory)
                              {
                             ?>
                             
                             <option value="<?php echo $superCategory['super_cat_id'] ?>"><?php echo $superCategory['super_cat_name'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr> -->


<tr>
<td>Image [x*y Dimentions]<span class="requiredField">* </span> : </td>
				<td>
					<input type="file" id="cat_img" name="cat_img"/>
                            </td>
</tr>

<tr>
<td width="220px" class="firstColumnStyling"> 
Description : 
</td>

<td>
<textarea id="cat_description" class="cat_description" name="cat_description"  cols="5" rows="6"></textarea>
</td>
</tr>

<tr>

<tr>
<td></td>
<td>
<input type="submit" value="Add Category" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Categories</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Category Name</th>
            <th class="heading"> Description</th>
            <th class="heading"> Image</th>
            <!-- <th class="heading"> Super Category</th> -->
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$Categories=listCategories();
		$i=0;
		foreach($Categories as $Category)
		{
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$i; ?>
            </td>
            <td><span  class="editLocationName" id="<?php echo $Category['cat_id'] ?>"><?php echo $Category['cat_name']; ?></span>
            </td>
            <td><span  class="editLocationName" id="<?php echo $Category['cat_id'] ?>"><?php echo $Category['cat_description']; ?></span>
            </td>
            <td><img src="<?php echo WEB_ROOT.'images/category/'.$Category['cat_img_path']; ?>" />
            </td>
           <!-- <td><span  class="editLocationName" id="<?php echo $Category['cat_id'] ?>"><?php  $superCatData = getSuperCategoryById($Category['super_cat_id']); echo $superCatData['super_cat_name']; ?></span>
            </td> -->
             <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=details&lid='.$Category['cat_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$Category['cat_id'] ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$Category['cat_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
    </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>