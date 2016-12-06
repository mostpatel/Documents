<?php
if(!isset($_GET['lid']))
{
header("Location: index.php");
exit;
}
$grp_id=$_GET['lid'];
$grp=getTaxGroupByID($grp_id);

$areas=$grp['taxes_id'];
			 if($areas!=null)
			 {
			 $area_id_array=explode(",",$areas);
			 }
			 
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Bank Details</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" method="post">
<table class="insertTableStyling no_print">

<tr>

<td class="firstColumnStyling">
Group Name<span class="requiredField">* </span> : 
</td>

<td>
<input type="hidden" name="lid"  value="<?php echo $grp_id; ?>"/>
<input type="text" name="grp_name" id="txtbank" value="<?php echo $grp['unprefixed_name']; ?>"/> 
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Display name<span class="requiredField">* </span> :
</td>

<td>
<input type="text" id="txtlocation" name="display_name" value="<?php echo $grp['display_name']; ?>" />
</td>
</tr>


<tr>
<td>Taxes<span class="requiredField">* </span> : </td>
				<td>
					<select name="tax_array[]" class="city_area selectpicker" multiple="multiple"  id="city_area1" >
                    	 <option value="-1" >--Please Select--</option>
                          <?php
						  $taxes=listTaxs();
						  foreach($taxes as $city)
						 {
                           
                         
                             ?>
                             <option value="<?php echo $city['tax_id'] ?>" <?php if(in_array($city['tax_id'],$area_id_array)) { ?> selected="selected" <?php } ?> ><?php echo $city['tax_name']." ".$city['tax_percent']; ?></option					>
                             <?php 
						  }
							 ?>
                    </select>
                            </td>
</tr>

<?php if(TAX_CLASS==1) { ?>
<tr>
<td width="200px;">Tax Class<span class="requiredField">* </span> : </td>
				<td>
					<select id="head" name="tax_class_id">
                        <option value="-1" >--Please Select--</option>
                        <?php
                            $heads = listTaxClasses();
							
							$i=1;
                            foreach($heads as $head)
                              {
                             ?>
                             
                             <option value="<?php echo $head['tax_class_id']; ?>" <?php if($head['tax_class_id']==$grp['tax_class_id']) { ?> selected="selected" <?php } ?> ><?php echo $head['tax_class'] ?></option>
                             <?php } ?>
                              
                         </select>
                         
                            </td>
</tr>
<?php } ?>


<tr>
<td></td>
<td>
<input type="submit" value="Edit" class="btn btn-warning">
<a href="index.php"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>


</table>
</form>

</div>
<div class="clearfix"></div>
