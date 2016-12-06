<?php 
if(!isset($_GET['id']))
{

header("Location: ".WEB_ROOT."admin/accounts/");
exit;
}
$shipping_location_id=$_GET['id'];
$shipping_location=getShippingLocationForshippingLocationId($shipping_location_id);
	
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment"> Edit Shipping Location </h4>
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
<form id="addLocForm" action="<?php echo 'index.php?action=edit'; ?>" method="post" enctype="multipart/form-data" >
<input type="hidden" name="lid" value="<?php echo $shipping_location_id; ?>"  />
<input type="hidden" name="primary_location" value="<?php echo $shipping_location['primary_location']; ?>"  />
<input type="hidden" name="customer_id" value="<?php echo $shipping_location['customer_id']; ?>"  />
<table id="insertInsuranceTable" class="insertTableStyling no_print">

<tr>
<td width="230px" class="firstColumnStyling">
Name<span class="requiredField">* </span>: 
</td>

<td>
<input type="text" name="customer_name" id="customer_name" class="person_name" placeholder="Only Letters" value="<?php echo $shipping_location['shipping_name'] ?>"  <?php if($shipping_location['primary_location']==1) { ?> readonly <?php } else { ?> autofocus <?php } ?> />
</td>
</tr>

<tr>
<td>
Address Line 1<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="customer_address" id="customer_address" class="address" <?php if($shipping_location['primary_location']==1) { ?> readonly <?php } ?> value="<?php $address=str_replace(array('<pre>','</pre>'),"",$shipping_location['shipping_address']);  echo $address; ?>" />
</td>
</tr>

<tr>
<td>
Address Line 2: 
</td>

<td>
<input type="text" name="customer_address2" id="customer_address2" class="address" <?php if($shipping_location['primary_location']==1) { ?> readonly <?php } ?> value="<?php $address=str_replace(array('<pre>','</pre>'),"",$shipping_location['shipping_address2']);  echo $address; ?>" />
</td>
</tr>


<tr>
<td>City<span class="requiredField">* </span>: </td>
				<td>
					<select <?php if($shipping_location['primary_location']==1) { ?> readonly <?php } ?> id="customer_city_id" name="customer_city_id" class="city" onchange="createDropDownAreaCustomer(this.value)">
                        <option value="-1" >--Please Select--</option>
                        <?php
                            $cities = listCitiesAlpha();
                            foreach($cities as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['city_id'] ?>" <?php if($super['city_id']==$shipping_location['city_id']) { ?> selected <?php } ?>><?php echo $super['city_name'] ?></option					>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>
<td>Area<span class="requiredField">* </span> : </td>
				<td>
					<select name="customer_area" <?php if($shipping_location['primary_location']==1) { ?> readonly <?php } ?>  class="city_area" id="city_area1"  >
                    	<option value="-1">--Please Select City--</option>
                        <?php $areas = listAreasAlphaFromCity($shipping_location['city_id']);
						  foreach($areas as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['area_id'] ?>" <?php if($super['area_id']==$shipping_location['area_id']) { ?> selected <?php } ?>><?php echo $super['area_name'] ?></option					>
                             <?php } ?>
						
                    </select>
                            </td>
</tr>



<tr id="">
                <td>
                Contact Person: 
                </td>
                
                <td id="addcontactTd">
                <input <?php if($shipping_location['primary_location']==1) { ?> readonly <?php } ?> type="text" class="" id="contactPerson" name="cp_name" value="<?php echo $shipping_location['cp_name'] ?>"  /> 
                </td>
            </tr>            


<tr>

<tr id="">
                <td>
             Contact Person Contact No<span class="requiredField">* </span>: 
                </td>
                
                <td id="addcontactTd">
                <input <?php if($shipping_location['primary_location']==1) { ?> readonly <?php } ?> type="text" class="contact" id="customerContact" name="cp_con_no" placeholder="more than 6 Digits!" value="<?php echo $shipping_location['cp_contact_no'] ?>" /> 
                </td>
            </tr>
            
<tr id="">
                <td>
             Recess From (24 hr format): 
                </td>
                
                <td id="addcontactTd">
               <input type="text" name="recess_from" class="timepicker" placeholder="24 hour format hh:mm:ss" value="<?php echo $shipping_location['recess_timings_from'] ?>" <?php if($shipping_location['primary_location']==1) { ?>  autofocus <?php } ?> />
                </td>
            </tr>      
<tr id="">
                <td>
             Recess to (24 hr format): 
                </td>
                
                <td id="addcontactTd">
               <input type="text" name="recess_to" class="timepicker" placeholder="24 hour format hh:mm:ss" value="<?php echo $shipping_location['recess_timings_to'] ?>" />
                </td>
            </tr>                        
            <tr id="">
                <td>
             Goods Type: 
                </td>
                
                <td id="addcontactTd">
               <input type="text" name="goods_type" class=""  value="<?php echo $shipping_location['goods_type'] ?>" />
                </td>
            </tr>                        
            
            <tr id="">
                <td>
             Goods Weight Range: 
                </td>
                
                <td id="addcontactTd">
               <input type="text" name="goods_weight_range" class="" value="<?php echo $shipping_location['goods_weight_range'] ?>"  />
                </td>
            </tr>  

 
</table>

<table>
<tr>
<td width="250px;"></td>
<td>
<input id="disableSubmit" type="submit" value="Edit"  class="btn btn-warning">

</td>
</tr>

</table>

</form>
</div>
<div class="clearfix"></div>
<script>

 $( "#city_area1" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/city_area.php',
                { term: request.term, city_id:$('#customer_city_id').val() }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#city_area1" ).val(ui.item.label);
			return false;
		}
    });
</script>