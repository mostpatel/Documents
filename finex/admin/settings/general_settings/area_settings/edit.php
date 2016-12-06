<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Area</h4>
<?php 
$area=getAreaByID($_GET['lid']);
$area_group = getAreaGroupByAreaID($_GET['lid']);
$area_id=$_GET['lid'];
if(isset($_SESSION['ack']['msg']) && isset($_SESSION['ack']['type']))
{
	
	$msg=$_SESSION['ack']['msg'];
	$type=$_SESSION['ack']['type'];
	
	
		if($msg!=null && $msg!="" && $type>0)
		{
?>
<div class="alert  <?php if(isset($type) && $type>0 && $type<4) echo "alert-success"; else echo "alert-error" ?>">
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" method="post">

<table class="insertTableStyling no_print">

<tr>
<td>City<span class="requiredField">* </span> : </td>
				<td>
					<select id="customer_city_id" name="city_id" class="city">
                        <option value="-1" >--Please Select--</option>
                        <?php
                            $cities = listCitiesAlpha();
                            foreach($cities as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['city_id']; ?>" <?php if($super['city_id']==$area['city_id']) { ?> selected="selected" <?php } ?>><?php echo $super['city_name'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>

<td class="firstColumnStyling">
Area name<span class="requiredField">* </span> :
</td>

<td>
<input type="hidden" name="lid" value="<?php echo $area['area_id']; ?>"/>
<input type="text" id="txtlocation" name="location" value="<?php echo $area['area_name']; ?>"/>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Seoncdary Area name<span class="requiredField">* </span> :
</td>

<td>
<input type="text"  name="secondary_name" value="<?php echo $area['secondary_area_name']; ?>" id="transliterateTextarea" />
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Pincode<span class="requiredField">* </span> :
</td>

<td>
<input type="text" id="txtpincode" name="pincode" value="<?php echo $area['pincode']; ?>"/>
</td>
</tr>

<tr>
<td>Area Group: </td>
				<td>
					<select name="area_group_id" class="city_area selectpicker"  id="city_area1" >
                    	 <option value="-1" >--Please Select--</option>
                          <?php
						  $cities=listAreaGroups();
						  foreach($cities as $city)
						 {
                             ?>
                             <option value="<?php echo $city['grp_id']; ?>" <?php if($city['grp_id']==$area_group['grp_id']) { ?> selected="selected" <?php } ?> ><?php echo $city['grp_name'] ?></option					>
                             <?php 
							 } 
							 ?>
                           
                    </select>
                            </td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" value="Edit" class="btn btn-warning">
<a href="index.php"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>
</div>
<div class="clearfix"></div>