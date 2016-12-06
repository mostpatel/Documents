<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add a Tax Group</h4>
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
Tax Group Name<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="group_name" id="txtbank"/> 
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Display name<span class="requiredField">* </span> :
</td>

<td>
<input type="text" id="txtlocation" name="display_name"/>
</td>
</tr>


<tr>
<td>Taxes<span class="requiredField">* </span> : </td>
				<td>
					<select name="tax_array[]" class="city_area selectpicker" multiple="multiple"  id="city_area1" >
                    	 <option value="-1" >--Please Select--</option>
                          <?php
						  $taxes=listTaxs();
						  foreach($taxes as $city)
						 {
                           
                         
                             ?>
                             <option value="<?php echo $city['tax_id'] ?>" ><?php echo $city['tax_name']." ".$city['tax_percent']; ?></option					>
                             <?php 
						  }
							 ?>
                    </select>
                            </td>
</tr>

<?php if(TAX_CLASS==1) { ?>
<tr>
<td width="200px;">Tax Class<span class="requiredField">* </span> : </td>
				<td>
					<select id="head" name="tax_class_id">
                        <option value="-1" >--Please Select--</option>
                        <?php
                            $heads = listTaxClasses();
							
							$i=1;
                            foreach($heads as $head)
                              {
                             ?>
                             
                             <option value="<?php echo $head['tax_class_id'] ?>" ><?php echo $head['tax_class'] ?></option>
                             <?php } ?>
                              
                         </select>
                         
                            </td>
</tr>
<?php } ?>


<tr>
<td></td>
<td>
<input type="submit" value="Add Group" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Groups</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Group Name</th>
            <th class="heading">Taxes</th>
             <th class="heading">Type</th>
           <th class="heading no_print btnCol"></th>
            <th class="heading no_print btnCol"></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$banks=listTaxGroups();
		$no=0;
		foreach($banks as $bank)
		{
			
		 ?>
         <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php echo $bank['tax_group_name']; ?></span>
            </td>
             <td><?php $taxes=$bank['taxes_id'];
			 if($taxes!=null)
			 {
			 $tax_id_array=explode(",",$taxes);
			
			 if(is_array($tax_id_array) && count($tax_id_array)>0)
			 {
				 
				 foreach($tax_id_array as $tax_id)
				 {
					 $ar=getTaxByID($tax_id);
					 echo $ar['tax_name']." - ".$ar['tax_percent']."% <br> ";
					 }
				 }
			 else
			 echo 0;
			 }
			 else
			 echo 0;
			  ?>
            </td>
            <td>
            	<?php if($bank['in_out']==0) echo  "INPUT";  else if($bank['in_out']==1) echo "OUTPUT"; else if($bank['in_out']==2) echo "INCLUDE IN PURCHASE"; ?>
            </td>
             <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=details&lid='.$bank['tax_group_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$bank['tax_group_id'] ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$bank['tax_group_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
     </div>
   <table id="to_print" class="to_print adminContentTable"></table>  
</div>
<div class="clearfix"></div>