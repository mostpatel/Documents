<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add a New Truck</h4>
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
Truck Name<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="name" id="txtName"/>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Truck Number<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="truck_no" id="truck_no"/>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Owner<span class="requiredField">* </span> :
</td>

<td>
<select id="customer_city_id" name="owner_ledger_id" class="city">
                        <option value="-1" >--Please Select--</option>
                        <?php
                            $cities = listTruckOwners();
                            foreach($cities as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['ledger_id'] ?>"><?php echo $super['ledger_name'] ?></option					>
                             <?php } ?>
                              
                         
                            </select> 
</td>
</tr>


<tr>

<td class="firstColumnStyling">
Remarks<span class="requiredField">* </span> :
</td>

<td>
<textarea  name="remarks" id="remarks"></textarea>
</td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" value="Add vehicle Type" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Products</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Name </th>
            <th class="heading">Truck Number </th>
            <th class="heading">Truck Owner </th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$vehicles=listTrucks();
		$no=0;
		foreach($vehicles as $vehicleType)
		{
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php echo $vehicleType['truck_name']; ?>
            </td>
            <td><?php echo $vehicleType['truck_no']; ?>
            </td>
            
            <td><?php echo getLedgerNameFromLedgerId($vehicleType['owner_ledger_id']); ?>
            </td>
              <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=details&lid='.$vehicleType['truck_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$vehicleType['truck_id'] ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$vehicleType['truck_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
        </tr>
         <?php }?>
         </tbody>
    </table>
     </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>