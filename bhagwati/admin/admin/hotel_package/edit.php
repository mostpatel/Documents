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
$package=getHotelPackageByID($package_id);
if(is_array($package) && $package!="error")
{
	
	
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
					<select id="location"  name="location_id" >
                        <option value="-1" >--Please Select--</option>
                        <?php
                            $locations = listHotelLocations();
							
                            foreach($locations as $super)
							
                              {
                             ?>
                             
                             <option value="<?php echo $super['location_id'] ?>" <?php if($super['location_id']==$package['location_id']) { ?> selected="selected" <?php } ?>><?php echo $super['location_name'] ?></option>
                             
                             <?php } ?>
                            </select> 
                    </td>
                    
                    
                  
</tr>

<tr>
<td>
Hote, Name<span class="requiredField">* </span> : 
</td>
<td>
<input type="text" name="name" id="name" placeholder="Only Letters!" autocomplete="off" value="<?php echo $package['hotel_package_name']; ?>" />
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
Stars<span class="requiredField">* </span> : 
</td>
<td>
<select name="stars" id="stars"  >
	<?php for($i=1;$i<6;$i++)
	{ ?>
    <option value="<?php echo $i; ?>" <?php if($i==$package['stars']) { ?> selected="selected" <?php } ?>><?php echo $i; ?></option>
    <?php } ?>
    
</select>
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
Tariff<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="tarriff"  class="tariff" value="<?php echo $package['tarriff']; ?>" />
</td>
</tr>



<tr>
<td width="260px"></td>
<td>
<input type="submit" value="Edit Package" id="disableSubmit" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/hotel_package/index.php?view=list"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>
</table>
</form>
</div>
<div class="clearfix"></div>
<script>
document.package_days = <?php echo $package['days']+1; ?>;
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