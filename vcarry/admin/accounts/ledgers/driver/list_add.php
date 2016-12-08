<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment">Add a New Driver</h4>
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
<form id="addAgencyForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post" onsubmit="return checkCheckBox()">
<table class="insertTableStyling no_print">

<tr>

<td class="firstColumnStyling">
Driver Name<span class="requiredField">*  : 
</td>

<td>
<input type="text" name="name" id="name" />
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Vehicle Type<span class="requiredField">*</span>  :
</td>

<td>
<select  name="vehicle_type_id" id="vehicle_type_id">
	<?php $route_groups = listVehicleTypes(); foreach($route_groups as $route_group)
	{
	 ?>
     <option value="<?php echo $route_group['vehicle_type_id'] ?>"><?php echo $route_group['vehicle_type']; ?></option>
     <?php } ?>
</select>
</td>
</tr>

<tr>
<td> Contact Number<span class="requiredField">*</span> : </td>
<td> <input type="text" name="contact_no_1" placeholder="10 Digit Mobile Number"/> </tr>
</tr>


<tr>
<td> Email<span class="requiredField">*</span> : </td>
<td> <input type="email" name="email" placeholder="Unique GMAIL Address"/> </tr>
</tr>

<tr>

<td class="firstColumnStyling">
Base Area<span class="requiredField">*  :
</td>

<td>
<select  name="area_id" id="from_area_id">
	<?php $areas = listAreas(); foreach($areas as $area)
	{
	 ?>
     <option value="<?php echo $area['area_id'] ?>"><?php echo $area['area_name'] ?></option>
     <?php } ?>
</select>
</td>

</tr>

<tr>

<td class="firstColumnStyling">
Type <span class="requiredField">*  :
</td>

<td>
<select  name="type" id="type">
     <option value="0">Managed</option>
     <option value="1">Associated</option>
     <option value="2">Outsourced</option>
</select>
</td>

</tr>

<tr>

<td class="firstColumnStyling">
Multi Trip <span class="requiredField">*  :
</td>

<td>
<select  name="multi_trip" id="multi_trip">
     <option value="0">No</option>
     <option value="1">Yes</option>
</select>
</td>

</tr>

<tr>

<td class="firstColumnStyling">
Fixed Amount <span class="requiredField">*  :
</td>

<td>
<input type="text"  name="fixed_amount" id="fixed_amount" placeholder="Only digits Allowed" /> 
   
</td>

</tr>

<tr>

<td class="firstColumnStyling">
Share % / Expense per km <span class="requiredField">*  :
</td>

<td>
<input type="text" name="share_expense" id="share_expense" placeholder="Only digits Allowed" /> 
   
</td>

</tr>


<tr>
<td></td>
<td>
<input type="submit" value="Add Driver" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>
</table>
</form>
	
    <hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Drivers</h4>
    <div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
   	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Name</th>
            <th class="heading">Vehicle</th>
            <th class="heading">Area</th>
            <th class="heading">Contact Number</th>
            <th class="heading">Type</th> 
            <th class="heading">Fixed Amount</th> 
            <th class="heading">Share / Expense</th> 
           
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$dealers=listDrivers();
		$no=0;
		foreach($dealers as $agencyDetails)
		{
		 ?>
         <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php echo $agencyDetails['driver_name']; ?>
            </td>
             <td><?php  echo getVehicleTypeNameById($agencyDetails['vehicle_type_id']); ?>
            </td> 
            <td><?php   $city=getAreaByID($agencyDetails['area_id']); echo $city['area_name']; ?>
            </td>
            
           <td><?php   echo $agencyDetails['contact_no_1']  ?>
            </td>
             <td><?php if($agencyDetails['type']==0) echo "Managed"; else echo "Associated"; ?>
            </td>
              <td><?php echo $agencyDetails['fixed_amount']." Rs"; ?>
            </td>
             <td><?php echo $agencyDetails['share_expense']; if($agencyDetails['type']==0) echo "%"; else echo " Rs / Km"; ?>
            </td>
            
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$agencyDetails['ledger_id'] ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$agencyDetails['ledger_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</button></a>
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
 $( "#city_area1" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/city_area.php',
                { term: request.term, city_id:$('#city').val() }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#city_area1" ).val(ui.item.label);
			return false;
		}
    });
</script>	