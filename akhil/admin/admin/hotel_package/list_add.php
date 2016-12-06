<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add Hotel Details</h4>
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
					<select id="location"  name="location_id" >
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
Hotel Name<span class="requiredField">* </span> : 
</td>
<td>
<input type="text" name="name" id="name" placeholder="Only Letters!" autocomplete="off" />
</td>
</tr>

<tr>
<td>
Stars<span class="requiredField">* </span> : 
</td>
<td>
<select id="stars"  name="stars" >
                        <option value="-1" >--Please Select--</option>
                        <?php
                          
							
                            for($l=1;$l<6;$l++)
                              {
                             ?>
                             
                             <option value="<?php echo $l; ?>"><?php echo $l; ?></option>
                             
                             <?php } ?>
                            </select> 
</td>
</tr>



<tr>
<td>Images (multiple at once)<span class="requiredField">* </span> : </td>
				<td>
					<input type="file" id="thumb_image" name="thumb_img[]" multiple/>
                            </td>
</tr>


<tr>
<td width="260px"></td>
<td>
<input type="submit" value="Add Hotel" id="disableSubmit" class="btn btn-warning">
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