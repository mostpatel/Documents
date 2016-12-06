<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add a New Location</h4>
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
Parent Location<span class="requiredField">* </span> :
</td>

<td>
<select name="super_location_id" id="super_location_id">
	<option value="-1">--Please Select--</option>
    <?php $super_locations= listSuperLocations();
	foreach($super_locations as $super)
	{
	?>
	<option value="<?php echo $super['super_location_id']; ?>"><?php echo $super['super_location_name']; ?></option>
	<?php	
		
		}
	 ?>
</select>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Location name<span class="requiredField">* </span> :
</td>

<td>
<input type="text" id="txtlocation" name="location_name" />
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Longitude<span class="requiredField">* </span> :
</td>

<td>
<input type="text" id="longitude" name="longitude"/>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Latitude<span class="requiredField">* </span> :
</td>

<td>
<input type="text" id="latitude" name="latitude"/>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Why Should You Go<span class="requiredField">* </span> :
</td>

<td>
<textarea id="why_should" name="why_should"></textarea>
</td>
</tr>


<tr>

<td class="firstColumnStyling">
About Location<span class="requiredField">* </span> :
</td>

<td>
<textarea id="about" name="about"></textarea>
</td>
</tr>


<tr>

<td class="firstColumnStyling">
Location Image For Map <br />[max-width : 200px | height : 20px | Transperant PNG]<span class="requiredField">* </span> :
</td>

<td>
<input type="file" id="location_image" name="location_image"/>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Images For Slider <br />[width : 1500px | height : 500px]<span class="requiredField">* </span> :
</td>

<td>
<input type="file" id="carosal_image" name="carosal_image[]" multiple="multiple"/>
</td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" value="Add Location" class="btn btn-warning" >
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Locations</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	  <div class="no_print">
    <table id="adminContentTable" class="adminContentTable no_print">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Name</th>
            <th class="heading">Parent Location</th>
            <th class="heading">Lat</th>
            <th class="heading">Long</th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$locations=listLocations();
		$i=0;
		foreach($locations as $location)
		{
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$i; ?>
            </td>
            <td><?php echo $location['location_name']; ?>
            </td>
             <td><?php $super_locations=getSuperLocationById($location['super_location_id']); echo $super_locations['super_location_name'] ?>
            </td>
            <td><?php echo $location['latitude']; ?>
            </td>
            <td><?php echo $location['longitude']; ?>
            </td>
              <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=details&lid='.$location['location_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$location['location_id'] ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$location['location_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
      </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>

