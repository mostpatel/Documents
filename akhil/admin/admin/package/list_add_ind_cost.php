<?php 
if(!isset($_GET['id']))
header("Location: ".WEB_ROOT."admin/search");

$package_id=$_GET['id'];
$package=getPackageByID($package_id);
?>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment no_print">Add Individual Package Cost</h4>

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

<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=addIndCost'; ?>" method="post" enctype="multipart/form-data" onsubmit="return submitOurCompany()">


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

<tr>

<td width="230px">Full Ticket<span class="requiredField">* </span> : </td>

				<td>

					<input type="text" id="full_ticket"  name="full_ticket" value="0"  />

                       

                    </td>

                    

                    

                  

</tr>

<tr>

<td width="230px">Extra Person<span class="requiredField">* </span> : </td>

				<td>

					<input type="text" id="extra_person"  name="extra_person" value="0"  />

                       

                    </td>

                    

                    

                  

</tr>

<tr>

<td width="230px">Half Ticket With Seat<span class="requiredField">* </span> : </td>

				<td>

					<input type="text" id="half_ticket_w_seat"  name="half_ticket_w_seat" value="0"   />

                       

                    </td>

                    

                    

                  

</tr>

<tr>

<td width="230px">Half Ticket Without Seat<span class="requiredField">* </span> : </td>

				<td>

					<input type="text" id="half_ticket_wo_seat"  name="half_ticket_wo_seat" value="0"   />

                       

                    </td>

                    

                    

                  

</tr>

<tr>

<td width="230px">Per Couple<span class="requiredField">* </span> : </td>

				<td>

					<input type="text" id="couple"  name="couple" value="0"   />

                       

                    </td>

                    

                    

                  

</tr>




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

