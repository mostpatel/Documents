<?php
if(!isset($_GET['id']))
header("Location: index.php");

$vehicle_id=$_GET['id'];
$customer_id=$_GET['lid'];

$vehicleDetails = getVehicleDetailsById($vehicle_id);

?>
<div class="insideCoreContent adminContentWrapper wrapper">

<?php 
if(isset($_SESSION['ack']['msg']) && isset($_SESSION['ack']['type']))
{
	
	$msg=$_SESSION['ack']['msg'];
	$type=$_SESSION['ack']['type'];
	
	
		if($msg!=null && $msg!="" && $type>0)
		{
?>
<div class="alert no_print  <?php if(isset($type) && $type>0 && $type<4) echo "alert-success"; else echo "alert-error" ?>">
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


<div class="detailStyling">

<h4 class="headingAlignment">Vehicle Details</h4>

<table class="insertTableStyling detailStylingTable">



<tr>
<td>
Vehicle Company : 
</td>
<td>
 
                             <?php 
							  $vehicle_company_id = $vehicleDetails['vehicle_company_id'];
							  $vehicle_company_details = getVehicleCompanyById($vehicle_company_id);
							  echo $vehicle_company_details['vehicle_company_name'];
							 ?>					
                             

</td>
</tr>

<tr>
<td> Vehicle Model : </td>
				<td>
				            <?php
                             $vehicle_model_id = $vehicleDetails['vehicle_model_id'];
							  $vehicle_model_details = getVehicleModelById($vehicle_model_id);
							  echo $vehicle_model_details['vehicle_model_name'];					
                            ?>

                 </td>
</tr>

<tr>
<td> Vehicle Condition : </td>
				<td>
				
                             <?php 
							   $vehicle_condition = $vehicleDetails['vehicle_condition'];
							   if($vehicle_condition==1)
							   {
								 echo "New";   
							   }
							   else if($vehicle_condition==0)
							   {
								 echo "Old";   
							   }
							 ?>					
                            

                 </td>
</tr>

<tr>
<td> Vehicle Model Year : </td>
				<td>
				
                             <?php 
							 $vehicle_model_year = $vehicleDetails['vehicle_model'];
							 echo $vehicle_model_year; 
							 ?>					
                            

                 </td>
</tr>

<tr>
<td>Registration Number : </td>
				<td>
				
                             <?php 
						$vehicle_reg_no = $vehicleDetails['vehicle_reg_no']; 
						echo $vehicle_reg_no;
							 ?>					
                            

                 </td>
</tr>

<tr>
<td> Registration Date : </td>
				<td>
				
                             <?php 
							 $vehicle_reg_date = $vehicleDetails['vehicle_reg_date'];
							
                             $vehicle_reg_date = date('d/m/Y',strtotime($vehicle_reg_date)); 
						     echo $vehicle_reg_date;
							 ?>					
                            

                 </td>
</tr>

<tr>
<td> Engine No : </td>
				<td>
				
                             <?php 
							 $vehicle_engine_no = $vehicleDetails['vehicle_engine_no']; 
						     echo $vehicle_engine_no;
							 ?>						
                            

                 </td>
</tr>

<tr>
<td> Chasis No : </td>
				<td>
				
                             <?php 
							 $vehicle_chasis_no = $vehicleDetails['vehicle_chasis_no']; 
						     echo $vehicle_chasis_no;
							 ?>						
                            

                 </td>
</tr>

<tr class="no_print">
<td></td>
<td >
<a href="<?php echo $_SERVER['PHP_SELF'].'?view=editVehicle&lid='.$vehicle_id.'&id='.$customer_id ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
<a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$vehicle_id.'&id='.$customer_id ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
<a href="<?php echo  WEB_ROOT ?>admin/customer/index.php?view=customerDetails&id=<?php echo $customer_id ?>"><input type="button" value="back" class="btn btn-success" /></a>

</td>
</tr>


</table>

</div>



</div>
<div class="clearfix"></div>