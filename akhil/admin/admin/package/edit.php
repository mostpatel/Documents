<?php
if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}

if(!isset($_GET['access']) && $_GET['access']="approved")
{
header("Location: ".WEB_ROOT."admin/package/index.php?view=details&id=".$_GET['id']);
exit;
}
$package_id=$_GET['id'];
$package=getPackageByID($package_id);

if(is_array($package) && $package!="error")
{
	$package_types=getPackageTypeForPackage($package_id);
	$package_itenary=getItenaryForPackageId($package_id);
	$package_location = getLocationForPackage($package_id);
	$package_location_ids = getLocationIDsForPackageId($package_id);
	$package_category_ids = getPackageCategoryIDsForPackageId($package_id);
	
	$package_dates = getTourDatesForPackageId($package_id);

	$package_dates_string = "";
	for($w=0;$w<count($package_dates);$w++) { $package_dates_string = $package_dates_string."'".date('d/m/Y',strtotime($package_dates[$w]))."'"; if($w!=(count($package_dates)-1)) $package_dates_string=$package_dates_string.","; } 
	
}
else
{
	$_SESSION['ack']['msg']="Invalid File!";
	$_SESSION['ack']['type']=4; // 4 for error
	header("Location: ".WEB_ROOT."admin/search");
	
}
?>
<div class="insideCoreContent adminContentWrapper wrapper">
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" method="post" enctype="multipart/form-data" >
<input name="lid" value="<?php echo $package_id; ?>" type="hidden">

<table id="insertCustomerTable" class="insertTableStyling no_print">


<tr>
<td width="230px">Location<span class="requiredField">* </span> : </td>
				<td>
					<select id="location"  name="location[]" class="selectpicker" multiple >
                        <option value="-1" >--Please Select--</option>
                        <?php
                            $locations = listLocations();
							
                            foreach($locations as $super)
							
                              {
                             ?>
                             
                             <option value="<?php echo $super['location_id'] ?>" <?php if(in_array($super['location_id'],$package_location_ids)) { ?> selected="selected" <?php } ?>><?php echo $super['location_name'] ?></option>
                             
                             <?php } ?>
                            </select> 
                    </td>
                    
                    
                  
</tr>

<tr>

<td width="230px">Package Category<span class="requiredField">* </span> : </td>

				<td>

					<select id="package_category"  name="package_category[]" class="selectpicker" >

                       

                        <?php

                            $locations = listPackageCategory();

							

                            foreach($locations as $super)

							

                              {

                             ?>

                             

                             <option value="<?php echo $super['pkg_cat_id'] ?>" <?php if(in_array($super['pkg_cat_id'],$package_category_ids)) { ?> selected="selected" <?php } ?>><?php echo $super['pkg_cat_name'] ?></option>

                             

                             <?php } ?>

                            </select> 

                    </td>

                    

                    

                  

</tr>

<tr>
<td>
Package Name<span class="requiredField">* </span> : 
</td>
<td>
<input type="text" name="name" id="name" placeholder="Only Letters!" autocomplete="off" value="<?php echo $package['package_name']; ?>" />
</td>
</tr>

<tr>
<td>Places Included<span class="requiredField">* </span> : </td>
				<td>
				<textarea  value="" autocomplete="off"  name="places" id="plcaes" placeholder="Seperated By comma" ><?php echo $package['places']; ?></textarea>
                 </td>
</tr>

<tr>

<td>

Tour Code<span class="requiredField">* </span> : 

</td>

<td>

<input type="text" name="tour_code" id="tour_code" placeholder="Only Letters!" autocomplete="off" value="<?php echo $package['tour_code']; ?>" />

</td>

</tr>


<tr>

<td>

Departure Location<span class="requiredField">* </span> : 

</td>

<td>

<input type="text" name="from_loc" id="from_loc" placeholder="Only Letters!" autocomplete="off" value="<?php echo $package['from_location']; ?>" />

</td>

</tr>

<tr>

<td>

Arrival Location<span class="requiredField">* </span> : 

</td>

<td>

<input type="text" name="to_loc" id="to_loc" placeholder="Only Letters!" autocomplete="off" value="<?php echo $package['to_location']; ?>" />

</td>

</tr>


<tr>
<td>Inclusions<span class="requiredField">* </span> : </td>
				<td>
				<textarea  value="" autocomplete="off" class="richtextarea"   name="inclusions" id="inclusions" placeholder="" ><?php echo $package['inclusions']; ?></textarea>
                 </td>
</tr>

<tr>
<td>Exclusions<span class="requiredField">* </span> : </td>
				<td>
				<textarea  value="" autocomplete="off" class="richtextarea"   name="exclusions" id="exclusions" placeholder="" ><?php echo $package['exclusions']; ?></textarea>
                 </td>
</tr>
<tr>

<td>Terms And Conditions<span class="requiredField">* </span> : </td>

				<td>

				<textarea  value="" autocomplete="off"  class="richtextarea"  name="terms" id="terms" placeholder="" ><?php echo $package['terms_and_conditions']; ?></textarea>

                 </td>

</tr>

<tr style="display:none;">

<td >Important Note<span class="requiredField">* </span> : </td>

				<td>

				<textarea  value="" autocomplete="off"  name="imp_note" id="imp_note" placeholder="" ><?php echo $package['imp_note']; ?></textarea>

                 </td>

</tr>

<tr>
<td>Image<span class="requiredField">* </span> : </td>
				<td>
					<input type="file" id="thumb_image" name="thumb_img"/>
                            </td>
</tr>

<tr>
<td>
Days<span class="requiredField">* </span> : 
</td>
<td>
<select name="days" id="days" >
	<?php for($i=1;$i<30;$i++)
	{ ?>
    <option value="<?php echo $i; ?>" <?php if($i==$package['days']) { ?> selected="selected" <?php } ?>><?php echo $i; ?></option>
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
    <option value="<?php echo $i; ?>" <?php if($i==$package['nights']) { ?> selected="selected" <?php } ?>><?php echo $i; ?></option>
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
    <option <?php if($package['currency_id']==1) { ?> selected="selected" <?php } ?> value="1"> INR </option>
   
</select>

</td>

</tr>


<tr>

<td>

Individual cost Heading <span class="requiredField">* </span> : 

</td>

<td>

<input type="text" name="ind_cost_heading" id="ind_cost_heading" value="<?php echo $package['ind_cost_heading'] ?>" />

</td>

</tr>

<tr>

<td>

Extra Vehicle cost Heading <span class="requiredField">* </span> : 

</td>

<td>

<input type="text" name="vehicle_cost_heading" id="vehicle_cost_heading" value="<?php echo $package['vehicle_cost_heading'] ?>" />

</td>

</tr>

<tr>

<td>

Itenary Sections to generate<span class="requiredField">* </span> : 

</td>

<td>

<select name="itenary_sections" id="itenary_sections" onchange="alterItenary(this.value)" >

	<?php for($i=1;$i<30;$i++)

	{ ?>

    <option  <?php if($package['itenary_sections']==$i) { ?> selected="selected" <?php } ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>

    <?php } ?>

    

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
<span class="headingAlignment">Section 1</span>
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
<?php 
	for($i=1;$i<=$package['itenary_sections'];$i++)
	{
	 ?>
<tbody id="day<?php echo $i; ?>">

<tr>

<td colspan="2" class="firstColumnStyling">
<span class="headingAlignment">Section <?php echo $i; ?></span>
</td>


</tr>


<tr>

<td width="230px" class="firstColumnStyling">
Heading<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="itenary_heading[]"  class="itenary_heading" placeholder="Only Letters" value="<?php if(isset($package_itenary[$i-1]['itenary_heading'])) echo $package_itenary[$i-1]['itenary_heading']; ?>"/>
</td>
</tr>

<tr>
<td>
Description<span class="requiredField">* </span> : 
</td>

<td>
<textarea name="itenary_description[]"  class="itenary_description" cols="5" rows="6"><?php if(isset($package_itenary[$i-1]['itenary_description'])) echo $package_itenary[$i-1]['itenary_description']; ?></textarea>
</td>
</tr>

</tbody>
<?php } ?>


</table>


<hr class="firstTableFinishing" />
<h4 class="headingAlignment">Tour Dates</h4>
<table>

<tr>

<td width="260px">Select Tour Dates<span class="requiredField">* </span></td>

<td>

<input type="text"  id="multiyear" name="tour_dates"    />

</td>

</tr>


</table>


<hr class="firstTableFinishing" />
<table>
<tr>
<td width="260px"></td>
<td>
<input type="submit" value="Edit Package" id="disableSubmit" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/package/index.php?view=list"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>
</table>
</form>
</div>
<div class="clearfix"></div>
<script>
document.package_days = <?php echo $package['itenary_sections']+1; ?>;
document.package_dates = [<?php echo $package_dates_string; ?>];
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