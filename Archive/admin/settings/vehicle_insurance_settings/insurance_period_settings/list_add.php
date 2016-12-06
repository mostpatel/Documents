<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add an Insurance Period</h4>
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
<td>Vehicle Type<span class="requiredField">* </span> : </td>
				<td>
					<select id="vehicle_type" name="vehicle_type_id" onchange="createDropDownModelCompany(this.value)">
                        <option value="-1" >--Select Vehicle Type--</option>
                        <?php
                            $vehicleTypes = listVehicleTypes();
                            foreach($vehicleTypes as $vehicleType)
                              {
                             ?>
                             
                             <option value="<?php echo $vehicleType['vehicle_type_id'] ?>"><?php echo $vehicleType['vehicle_type'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>


<tr>
<td class="firstColumnStyling">
Period <span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="period" id="txtName"/>
</td>
</tr>


<tr>
<td>Min Range<span class="requiredField">* </span> : </td>
				<td>
					<select id="min_range" name="min_range">
                        <option value="-1" >--Select Min Range--</option>
                        <?php
                            for($i=0; $i<=15; $i++)
							{
                             ?>
                             
                             <option value="<?php echo $i ?>">
							 <?php echo $i; ?>
                             </option>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>
<td>Max Range<span class="requiredField">* </span> : </td>
				<td>
					<select id="max_range" name="max_range">
                        <option value="-1" >--Select Max Range--</option>
                        <?php
                            for($i=5; $i<=25; $i++)
							{
                             ?>
                             
                             <option value="<?php echo $i ?>">
							 <?php echo $i; ?>
                             </option>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" value="Add Period" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Periods</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Vehicle Type</th>
            <th class="heading">Period</th>
            <th class="heading">Minimum Range</th>
            <th class="heading">Maximum Range</th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$periods=listInsurancePeriod();
		$i=0;
		foreach($periods as $period)
		{
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$i; ?>
            </td>
            
            <td>
            <span  class="editLocationName" id="<?php echo $period['vehicle_type_id'] ?>">
			<?php 
			 $vId = $period['vehicle_type_id'];
			 $vehicleTypeDetails = getVehicleTypeById($vId); 
			 echo $vehicleTypeDetails['vehicle_type'];
			?>
            </span>
            </td>
            
            <td><span  class="editLocationName" id="<?php echo $period['period_id'] ?>"><?php echo $period['period']; ?></span>
            </td>
            
            <td><span  class="editLocationName" id="<?php echo $period['min_range'] ?>"><?php echo $period['min_range']; ?></span>
            </td>
            
            <td><span  class="editLocationName" id="<?php echo $period['max_range'] ?>"><?php echo $period['max_range']; ?></span>
            </td>
            
            
            
             <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=details&lid='.$period['period_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$period['period_id'] ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$period['period_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
    </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>