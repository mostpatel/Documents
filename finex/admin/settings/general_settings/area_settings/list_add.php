<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add a New City</h4>
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
<td>City<span class="requiredField">* </span> : </td>
				<td>
					<select id="customer_city_id" name="city_id" class="city">
                        <option value="-1" >--Please Select--</option>
                        <?php
                            $cities = listCitiesAlpha();
                            foreach($cities as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['city_id'] ?>"><?php echo $super['city_name'] ?></option					>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>


<tr>

<td class="firstColumnStyling">
Area name<span class="requiredField">* </span> :
</td>

<td>
<input type="text" id="txtlocation" name="location"/>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Secondary Area name<span class="requiredField">* </span> :
</td>

<td>
<input type="text"  name="secondary_name" id="transliterateTextarea" />
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Pincode<span class="requiredField">* </span> :
</td>

<td>
<input type="text" id="txtpincode" name="pincode"/>
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
                             <option value="<?php echo $city['grp_id'] ?>" ><?php echo $city['grp_name'] ?></option					>
                             <?php 
							 } 
							 ?>
                           
                    </select>
                            </td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" value="Add Area" class="btn btn-warning" >
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Areas</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	  <div class="no_print">
    <table id="adminContentTable" class="adminContentTable no_print">
    <thead>
    	<tr>
        	 <th class="heading">No</th>
             <th class="heading">Area Name</th>
             <th class="heading">Secondary Area Name</th>
             <th class="heading">City Name</th>
             <th class="heading">Pincode</th>
             <th class="heading">Area Group</th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$locations=listAreas();
		$i=0;
		foreach($locations as $location)
		{
		$city=getCityByID($location['city_id']);	
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$i; ?>
            </td>
            <td><?php echo $location['area_name']; ?>
            </td>
             <td><?php echo $location['secondary_area_name']; ?>
            </td>
             <td><?php echo $city['city_name']; ?>
            </td>
             <td><?php echo $location['pincode']; ?>
            </td>
            <td>
            	<?php $area_grp_id = getAreaGroupByAreaID($location['area_id']);  if($area_grp_id!="error") $area_grp_id=$area_grp_id['grp_id'];  if($area_grp_id!="error" && is_numeric($area_grp_id)) { $area_grp = getAreaGroupByID($area_grp_id);   echo $area_grp['grp_name'];} ?>
            </td>
              <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=details&lid='.$location['area_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$location['area_id'] ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$location['area_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
      </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>