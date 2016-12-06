<?php if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/accounts/");
exit;
}
$trip_id=$_GET['id'];
$trip=getTripById($trip_id);
if($trip=="error")
{ ?>
<script>
  window.history.back()
</script>
<?php
}
$customer_id=$trip['customer_id'];
$vehicle_type = getVehicleTypeNameById($trip['vehicle_type_id']);
$driver_name=getDriverNameFromDriverId($trip['driver_id']);
$from_location=getShippingLocationForshippingLocationId($trip['from_shipping_location_id']);
$to_location=getShippingLocationForshippingLocationId($trip['to_shipping_location_id']);
if(validateForNull($customer_id) && is_numeric($customer_id))
{
	$customer=getCustomerDetailsByCustomerId($customer_id);
	
}
?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment"> Trip Details </h4>
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
<div class="detailStyling" >
<table class="detailStylingTable insertTableStyling ">

<tr >
<td>Date : </td>
				<td>
					<?php echo date('d/m/Y H:i:s',strtotime($trip['trip_datetime'])); ?>
                            </td>
</tr>

<tr>
<td>Status :</td>
 <td><?php echo $trip['status']; ?>
            </td>
            </tr>

<tr>

<td> Customer Name : </td>
				<td>
					<a href="<?php echo WEB_ROOT ?>admin/customer/index.php?view=details&id=<?php echo $customer_id; ?>"><?php echo $customer['customer_name']; ?></a>
                            </td>
</tr>
                         
<tr>
<td width="220px"> Amount : </td>
				<td>
					<?php echo "Rs. ".number_format($trip['fare'])." /- "; ?>
                    </td>
</tr>
<td colspan="2" class="headingAlignment">Vehicle & Driver Details</td>            
            <tr>
<td>Vehicle Type :</td>
            <td > 
            
            <?php echo $vehicle_type; ?>
            </td>
            </tr>

<tr>
            <tr>
<td>Driver Name :</td>
            <td > 
            
            <?php echo $driver_name; ?>
            </td>
            </tr>






</table>

<table class="no_print">
<tr>
<td width="250px;"></td>
<td>
 <?php if($trip['trip_status']==1) { ?>
 <a href="<?php echo 'index.php?view=updateDriver&id='.$trip_id; ?>"><button title="Edit this entry" class="btn editBtn">Update Driver</button></a>
 <?php }else if($trip['trip_status']>1 && $trip['trip_status']<6) { ?>
  <a href="<?php echo 'index.php?view=updateStatus&id='.$trip_id; ?>"><button title="Edit this entry" class="btn editBtn">Update Status</button></a>
<?php } ?>
<a href="<?php echo '../index.php?view=details&id='.$customer_id; ?>"><button class="btn btn-warning" >Back to customer</button></a>
</td>
</tr>
</table>
</div>
<div class="detailStyling" >
<table class="detailStylingTable insertTableStyling ">
<tr >

<td colspan="2" class="headingAlignment">From Location Details</td>
</tr>
<tr>
<td>Name : </td>
<td><?php echo $trip['from_shipping_location']; ?>
            </td>
            </tr>
 <tr >
<td>Address : </td>
<td><?php echo $from_location['shipping_address']." <br>".$from_location['shipping_address2']."<br>".$from_location['area_name'].", ".$from_location['city_name']; ?>
            </td>
            </tr>  
  <tr >
<td>Contact Person : </td>
<td><?php echo $from_location['cp_name']; ?>
           (<?php echo $from_location['cp_contact_no']; ?>)
            
            </td>
            </tr>    
  <tr>
  <td>Recess :</td>
  <td><?php echo $from_location['recess_timings_from']." - ".$from_location['recess_timings_to']; ?>
            </td>
  </tr>                   
            
             
            <td colspan="2" class="headingAlignment">To Location Details</td>       
           <tr>
<td>Name : </td>
<td><?php echo $trip['to_shipping_location']; ?>
            </td>
            </tr>
 <tr >
<td>Address : </td>
<td><?php echo $to_location['shipping_address']." <br>".$to_location['shipping_address2']."<br>".$to_location['area_name'].", ".$to_location['city_name']; ?>
            </td>
            </tr>  
  <tr >
<td>Contact Person : </td>
<td><?php echo $to_location['cp_name']; ?>
           (<?php echo $to_location['cp_contact_no']; ?>)
            
            </td>
            </tr>    
  <tr>
  <td>Recess :</td>
  <td><?php echo $to_location['recess_timings_from']." - ".$to_location['recess_timings_to']; ?>
            </td>
  </tr>  

            


</table>

</div>
</div>
<div class="clearfix"></div>
