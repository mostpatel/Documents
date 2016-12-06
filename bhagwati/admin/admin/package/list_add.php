<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment no_print">Add Package Details</h4>

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

<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post" enctype="multipart/form-data" onsubmit="return submitOurCompany()">



<table class="insertTableStyling no_print">



<tr>

<td width="230px">Location<span class="requiredField">* </span> : </td>

				<td>

					<select id="location"  name="location[]" >

                        <option value="-1" >--Please Select--</option>

                        <?php

                            $locations = listLocations();

							

                            foreach($locations as $super)

							

                              {

                             ?>

                             

                             <option value="<?php echo $super['location_id'] ?>"><?php echo $super['location_name'] ?></option>

                             

                             <?php } ?>

                            </select> 

                    </td>

                    

                    

                  

</tr>



<tr>

<td>

Package Name<span class="requiredField">* </span> : 

</td>

<td>

<input type="text" name="name" id="name" placeholder="Only Letters!" autocomplete="off" />

</td>

</tr>



<tr>

<td>Places Included<span class="requiredField">* </span> : </td>

				<td>

				<textarea  value="" autocomplete="off"  name="places" id="plcaes" placeholder="Seperated By comma" ></textarea>

                 </td>

</tr>



<tr>

<td>Inclusions<span class="requiredField">* </span> : </td>

				<td>

				<textarea  value="" autocomplete="off"  name="inclusions" id="inclusions" placeholder="" ></textarea>

                 </td>

</tr>



<tr>

<td>Exclusions<span class="requiredField">* </span> : </td>

				<td>

				<textarea  value="" autocomplete="off"  name="exclusions" id="exclusions" placeholder="" ></textarea>

                 </td>

</tr>



<tr>

<td>Thumbnail image<br />[square image | max-width : 340px ]<span class="requiredField">* </span> : </td>

				<td>

					<input type="file" id="thumb_image" name="thumb_img"/>

                            </td>

</tr>



<tr>

<td>

Days<span class="requiredField">* </span> : 

</td>

<td>

<select name="days" id="days" onchange="alterItenary(this.value)" >

	<?php for($i=1;$i<30;$i++)

	{ ?>

    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>

    <?php } ?>

    

</select>

</td>

</tr>



<tr>

<td>

Nights<span class="requiredField">* </span> : 

</td>

<td>

<select name="nights" id="nights" >

	<?php for($i=1;$i<30;$i++)

	{ ?>

    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>

    <?php } ?>

    

</select>

</td>

</tr>

<tr>

<td>

Currency<span class="requiredField">* </span> : 

</td>

<td>

<select name="currency" id="currency" >
    <option value="1"> INR </option>
    <option value="2"> USD </option>
     <option value="3"> EURO </option>
</select>

</td>

</tr>





</table>



<hr class="firstTableFinishing" />



<h4 class="headingAlignment">Itenary Details</h4>





<table id="insertItenaryTable" class="insertTableStyling no_print">

<tbody id="day" style="display:none">



<tr>



<td colspan="2" class="firstColumnStyling">

<span class="headingAlignment">Day 1</span>

</td>





</tr>





<tr>



<td width="230px" class="firstColumnStyling">

Heading<span class="requiredField">* </span> : 

</td>



<td>

<input type="text" name="itenary_heading[]"  class="itenary_heading" placeholder="Only Letters"/>

</td>

</tr>



<tr>

<td>

Description<span class="requiredField">* </span> : 

</td>



<td>

<textarea name="itenary_description[]"  class="itenary_description" cols="5" rows="6"></textarea>

</td>

</tr>

</tbody>

<tbody id="day1">



<tr>



<td colspan="2" class="firstColumnStyling">

<span class="headingAlignment">Day 1</span>

</td>





</tr>





<tr>



<td width="230px" class="firstColumnStyling">

Heading<span class="requiredField">* </span> : 

</td>



<td>

<input type="text" name="itenary_heading[]"  class="itenary_heading" placeholder="Only Letters"/>

</td>

</tr>



<tr>

<td>

Description<span class="requiredField">* </span> : 

</td>



<td>

<textarea name="itenary_description[]"  class="itenary_description" cols="5" rows="6"></textarea>

</td>

</tr>



</tbody>

</table>



<hr class="firstTableFinishing" />



<h4 class="headingAlignment">Tariff Details</h4>





<table id="insertTariffTable" class="insertTableStyling no_print">



<tbody id="tariff">



<tr style="display:none;">



<td></td>



<td>

<span class="removeLink" style="color:#f00;font-size:12px;text-decoration:underline;cursor:pointer" onclick="removeThisProduct(this);"> Remove This Tariff </span>

</td>



</tr>





<tr>



<td width="230px" class="firstColumnStyling">

Package Type<span class="requiredField">* </span> : 

</td>



<td>

<select  name="package_types[]" class="package_type">

                        <option value="-1" >--Please Select--</option>

                        <?php

                            $locations = listPackageTypes();

                            foreach($locations as $super)

                            {

                         ?>

                             

                             <option value="<?php echo $super['package_type_id'] ?>"><?php echo $super['package_type'] ?></option>

                             

                            <?php } ?>

                            </select> 

</td>

</tr>



<tr>

<td>

Tariff<span class="requiredField">* </span> : 

</td>



<td>

<input type="text" name="tariff[]"  class="tariff" />

</td>

</tr>



<tr>

<td>

<hr class="firstTableFinishing" /></td>



<td>

<hr class="firstTableFinishing" />

</td>

</tr>



</tbody>

</table>



<table style="margin-top:10px;margin-bottom:10px;">

<tr>

<td width="260px;">  </td>

<td><input type="button" class="btn btn-success" value="+ Add Package Type" id="addCustomerProofBtn" onclick="generateProductDetails()"/></td>

</tr>     

</table>



<hr class="firstTableFinishing" />

<table>

<tr>

<td width="260px"></td>

<td>

<input type="submit" value="Add Package" id="disableSubmit" class="btn btn-warning">

</td>

</tr>

</table>

</form>



</div>

<div class="clearfix"></div>

<script>

document.package_days = 2;

</script>

<script>



function generateProductDetails()

{



var sanket=document.getElementById('tariff').innerHTML;

sanket=sanket.replace('style="display:none;"', '');

var mytbody=document.createElement('tbody');

mytbody.innerHTML=sanket;



document.getElementById('insertTariffTable').appendChild(mytbody);









}



function removeThisProduct(spanRemoveLink)

{

	var tbody=$(spanRemoveLink).parent().parent().parent();

	tbody=tbody[0];

	tbody.innerHTML="";

}



</script>