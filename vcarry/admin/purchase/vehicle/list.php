<div class="addDetailsBtnStyling no_print"> <a href="<?php echo WEB_ROOT; ?>admin/purchase/vehicle/"><button class="btn btn-success">+ Add Vehicle Purchase</button></a> </div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment">List of Vehicle Purchases</h4>
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
    <div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
   	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Purchase Date</th>
            <th class="heading">No Of Vehicles</th>
             <th class="heading">Chasis No</th>
            <th class="heading">Amount</th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$parties=getAllVehiclePurchases();
		$no=0;
		if($parties!=false)
		{ 
		foreach($parties as $agencyDetails)
		{
			$vehciles=getVehiclesForVehiclePurchaseId($agencyDetails['purchase_id']);
			
		 ?>
         <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php  echo date('d/m/Y',strtotime($agencyDetails['trans_date'])); ?>
            </td>
            <td><?php echo getNoOfVehiclesForPurchaseId($agencyDetails['purchase_id']); ?>
            </td> 
             <td><?php foreach($vehciles as $vehicle) { echo $vehicle['vehicle_chasis_no']." <br>";} ; ?>
            </td> 
             <td><?php echo number_format($agencyDetails['amount'],2); ?>
            </td>
             <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=details&id='.$agencyDetails['purchase_id']; ?>"><button title="View this entry" class="btn editBtn viewBtn "><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$agencyDetails['purchase_id']; ?>"><button title="Edit this entry" class="btn editBtn splEditBtn "><span class="edit">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$agencyDetails['purchase_id']; ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php } }?>
         </tbody>
    </table>
    </div>
     <table id="to_print" class="to_print adminContentTable"></table> 

</div>
<div class="clearfix"></div>