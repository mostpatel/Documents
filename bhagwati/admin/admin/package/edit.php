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
					<select id="location"  name="location[]" >
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
<td>Inclusions<span class="requiredField">* </span> : </td>
				<td>
				<textarea  value="" autocomplete="off"  name="inclusions" id="inclusions" placeholder="" ><?php echo $package['inclusions']; ?></textarea>
                 </td>
</tr>

<tr>
<td>Exclusions<span class="requiredField">* </span> : </td>
				<td>
				<textarea  value="" autocomplete="off"  name="exclusions" id="exclusions" placeholder="" ><?php echo $package['exclusions']; ?></textarea>
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
    <option <?php if($package['currency_id']==2) { ?> selected="selected" <?php } ?> value="2"> USD </option>
      <option <?php if($package['currency_id']==3) { ?> selected="selected" <?php } ?> value="3"> EURO </option>
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
<?php 
	for($i=1;$i<=$package['days'];$i++)
	{
	 ?>
<tbody id="day<?php echo $i; ?>">

<tr>

<td colspan="2" class="firstColumnStyling">
<span class="headingAlignment">Day <?php echo $i; ?></span>
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

<h4 class="headingAlignment">Tariff Details</h4>


<table id="insertTariffTable" class="insertTableStyling no_print">

<?php foreach($package_types as $package_type)
{ ?>
<tbody>

<tr >

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
                             
                             <option value="<?php echo $super['package_type_id'] ?>" <?php if( $super['package_type_id']==$package_type['package_type_id']) { ?> selected="selected" <?php } ?>><?php echo $super['package_type'] ?></option>
                             
                            <?php } ?>
                            </select> 
</td>
</tr>

<tr>
<td>
Tariff<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="tariff[]"  class="tariff" value="<?php echo $package_type['price']; ?>" />
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
<?php } ?>
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
<input type="submit" value="Edit Package" id="disableSubmit" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/package/index.php?view=list"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>
</table>
</form>
</div>
<div class="clearfix"></div>
<script>
document.package_days = <?php echo $package['days']; ?>;
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