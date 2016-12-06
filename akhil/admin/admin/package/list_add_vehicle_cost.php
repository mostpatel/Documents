<?php 
if(!isset($_GET['id']))
header("Location: ".WEB_ROOT."admin/search");

$package_id=$_GET['id'];
$package=getPackageByID($package_id);
$vehicle_types = listVehicleTypes();
?>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment no_print">Add Vehicle Package Cost</h4>

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

<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=addVehicleCost'; ?>" method="post" enctype="multipart/form-data" onsubmit="return submitOurCompany()">


<input type="hidden" name="package_id" value="<?php echo $package_id; ?>"/>
<table class="insertTableStyling no_print">



<tr>

<td width="230px">From<span class="requiredField">* </span> : </td>

				<td>

					<input type="text" id="from"  name="from" class="datepicker1" />

                       

                    </td>

                    

                    

                  

</tr>

<tr>

<td width="230px">To<span class="requiredField">* </span> : </td>

				<td>

					<input type="text" id="to"  name="to" class="datepicker2" />

                       

                    </td>

                    

                    

                  

</tr>
<?php foreach($vehicle_types as $vehicle_type) { ?>
<tr>

<td colspan="2" style="font-size:18px;font-weight:bold;"  class="headingAlignment"><?php echo $vehicle_type['vehicle_type']; ?> </td>

				

                    

                    

                  

</tr>
<tr>

<td width="230px">2 Pax<span class="requiredField">* </span> : </td>

				<td>

					<input type="text"   name="pax_2[<?php echo $vehicle_type['vehicle_id']; ?>]" value="0"  />

                       

                    </td>

                    

                    

                  

</tr>

<tr>

<td width="230px">3 Pax<span class="requiredField">* </span> : </td>

				<td>

					<input type="text" name="pax_3[<?php echo $vehicle_type['vehicle_id']; ?>]" value="0"  />

                       

                    </td>

                    

                    

                  

</tr>

<tr>

<td width="230px">4 Pax<span class="requiredField">* </span> : </td>

				<td>

					<input type="text"   name="pax_4[<?php echo $vehicle_type['vehicle_id']; ?>]" value="0"   />

                       

                    </td>

                    

                    

                  

</tr>

<tr>

<td width="230px">6 Pax<span class="requiredField">* </span> : </td>

				<td>

					<input type="text"   name="pax_6[<?php echo $vehicle_type['vehicle_id']; ?>]" value="0"   />

                       

                    </td>

                    

                    

                  

</tr>

<tr>

<td width="230px">9 Pax<span class="requiredField">* </span> : </td>

				<td>

					<input type="text"  name="pax_9[<?php echo $vehicle_type['vehicle_id']; ?>]" value="0"   />

                       

                    </td>

                    

                    

                  

</tr>

<?php } ?>


<tr>

<td width="260px"></td>

<td>

<input type="submit" value="Add Cost" id="disableSubmit" class="btn btn-warning">

</td>

</tr>

</table>

</form>



</div>

<div class="clearfix"></div>

