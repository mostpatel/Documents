<?php 
if(!isset($_GET['id']))
header("Location: ".WEB_ROOT."admin/search");

$ind_cost_id=$_GET['id'];

$ind_cost=getIndividualPackageCostByPackageCostID($ind_cost_id);
?>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment no_print">Edit Individual Package Cost</h4>

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

<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=editIndCost'; ?>" method="post" enctype="multipart/form-data" onsubmit="return submitOurCompany()">

<input type="hidden" name="package_id" value="<?php echo $ind_cost['package_id']; ?>"/>
<input type="hidden" name="ind_cost_id" value="<?php echo $ind_cost_id; ?>"/>
<table class="insertTableStyling no_print">



<tr>

<td width="230px">From<span class="requiredField">* </span> : </td>

				<td>

					<input type="text" id="from"  name="from" class="datepicker1" value="<?php echo date('d/m/Y',strtotime($ind_cost['from_date'])); ?>" />

                       

                    </td>

                    

                    

                  

</tr>

<tr>

<td width="230px">To<span class="requiredField">* </span> : </td>

				<td>

					<input type="text" id="to"  name="to" class="datepicker2" value="<?php echo date('d/m/Y',strtotime($ind_cost['to_date'])); ?>" />

                       

                    </td>

                    

                    

                  

</tr>

<tr>

<td width="230px">Full Ticket<span class="requiredField">* </span> : </td>

				<td>

					<input type="text" id="full_ticket"  name="full_ticket" value="<?php echo $ind_cost['full_ticket']; ?>"  />

                       

                    </td>

                    

                    

                  

</tr>

<tr>

<td width="230px">Extra Person<span class="requiredField">* </span> : </td>

				<td>

					<input type="text" id="extra_person"  name="extra_person"  value="<?php echo $ind_cost['extra_person']; ?>"  />

                       

                    </td>

                    

                    

                  

</tr>


<tr>

<td width="230px">Half Ticket With Seat<span class="requiredField">* </span> : </td>

				<td>

					<input type="text" id="half_ticket_w_seat"  name="half_ticket_w_seat" value="<?php echo $ind_cost['half_ticket_with_seat']; ?>"   />

                       

                    </td>

                    

                    

                  

</tr>

<tr>

<td width="230px">Half Ticket Without Seat<span class="requiredField">* </span> : </td>

				<td>

					<input type="text" id="half_ticket_wo_seat"  name="half_ticket_wo_seat"  value="<?php echo $ind_cost['half_ticket_without_seat']; ?>" />

                       

                    </td>

                    

                    

                  

</tr>

<tr>

<td width="230px">Per Couple<span class="requiredField">* </span> : </td>

				<td>

					<input type="text" id="couple"  name="couple"  value="<?php echo $ind_cost['per_couple']; ?>" />

                       

                    </td>

                    

                    

                  

</tr>




<tr>

<td width="260px"></td>

<td>

<input type="submit" value="Update Cost" id="disableSubmit" class="btn btn-warning">

</td>

</tr>

</table>

</form>



</div>

<div class="clearfix"></div>

