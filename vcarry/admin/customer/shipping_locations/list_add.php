<?php if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/accounts/");
exit;
}
$customer_id=$_GET['id'];
if(!is_numeric($customer_id))
{ ?>
<script>
  window.history.back()
</script>
<?php
}
$shipping_locations = listShippingLocationForCustomerId($customer_id);
$customer = getCustomerDetailsByCustomerId($customer_id);
?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment"> Add Shipping Location </h4>
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
<form  id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post" enctype="multipart/form-data" >
<input type="hidden" name="customer_id" value="<?php echo $customer_id ?>"/>
<table id="insertInsuranceTable" class="insertTableStyling no_print">

<tr>
<td width="230px" class="firstColumnStyling">
Name<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="customer_name" id="customer_name" class="person_name" placeholder="Only Letters" onblur="checkForDuplicateCustomerName(this.value);" autofocus />
</td>
</tr>

<tr>
<td>
Address line 1<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="customer_address" id="customer_address" class="address" />
</td>
</tr>


<tr>
<td>
Address line 2 : 
</td>

<td>
<input type="text" name="customer_address2" id="customer_address2" class="address" />
</td>
</tr>


<tr>
<td>City<span class="requiredField">* </span> : </td>
				<td>
					<select id="customer_city_id" name="customer_city_id" class="city" onchange="createDropDownAreaCustomer(this.value)">
                        <option value="-1" >--Please Select--</option>
                        <?php
                            $cities = listCitiesAlpha();
                            foreach($cities as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['city_id'] ?>"><?php echo $super['city_name'] ?></option					>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>
<td>Area<span class="requiredField">* </span> : </td>
				<td>
					<select name="customer_area" class="city_area" id="city_area1"  >
                    	 <option value="-1" >--Please Select City--</option>
                    </select>
                            </td>
</tr>

<tr id="">
                <td>
                Contact Person : 
                </td>
                
                <td id="addcontactTd">
                <input type="text" class="" id="contactPerson" name="cp_name"  /> 
                </td>
            </tr>            


<tr>

<tr id="">
                <td>
             Contact Person Contact No<span class="requiredField">* </span> : 
                </td>
                
                <td id="addcontactTd">
                <input type="text" class="contact" id="customerContact" name="cp_con_no" placeholder="more than 6 Digits!" onblur="checkForDuplicateContactNo(this.value);" /> 
                </td>
            </tr>
            
<tr id="">
                <td>
             Recess From (24 hr format): 
                </td>
                
                <td id="addcontactTd">
               <input type="text" name="recess_from" class="timepicker" placeholder="24 hour format hh:mm:ss" />
                </td>
            </tr>      
<tr id="">
                <td>
             Recess to (24 hr format): 
                </td>
                
                <td id="addcontactTd">
               <input type="text" name="recess_to" class="timepicker" placeholder="24 hour format hh:mm:ss" />
                </td>
            </tr>                        
            <tr id="">
                <td>
             Goods Type: 
                </td>
                
                <td id="addcontactTd">
               <input type="text" name="goods_type" class=""  />
                </td>
            </tr>                        
            
            <tr id="">
                <td>
             Goods Weight Range: 
                </td>
                
                <td id="addcontactTd">
               <input type="text" name="goods_weight_range" class=""  />
                </td>
            </tr>                        
            
            
            

 
</table>

<table>
<tr>
<td width="250px;"></td>
<td>
<input id="disableSubmit" type="submit" value="Add Shipping Location"  class="btn btn-warning">
<a href="<?php echo WEB_ROOT."admin/customer/index.php?view=details&id=".$customer_id; ?>"><input type="button" class="btn btn-success" value="Back"/></a>
</td>
</tr>

</table>

</form>
<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Shipping Locations</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
             <th class="heading">Name</th>
              <th class="heading">Address</th>
             <th class="heading">City</th>
              <th class="heading">Area</th>
              <th class="heading">CP Name</th>
              <th class="heading">Contact</th>
              <th class="heading">Recess</th>
              <th class="heading">Goods Type</th>
              <th class="heading">Weight Range</th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		$no=0;
		foreach($shipping_locations as $receipt)
		{
			
			
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
           
            <td><?php echo $receipt['shipping_name']; ?>
            </td>
            <td><?php echo $receipt['shipping_address']; ?>
            </td>
            <td><?php echo $receipt['city_name']; ?>
            </td>
          	 <td><?php echo $receipt['area_name']; ?></td>
             <td><?php echo $receipt['cp_name']; ?>
            </td>
            <td><?php echo $receipt['cp_contact_no']; ?>
            </td>
             <td><?php echo $receipt['recess_timings_from']." - ".$receipt['recess_timings_to']; ?>
            </td>
             <td><?php echo $receipt['goods_type']; ?>
            </td>
            <td><?php echo $receipt['goods_weight_range']; ?>
            </td>
        
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/shipping_locations/index.php?view=edit&id='.$receipt['shipping_location_id']; ?>"><button title="Edit this entry" class="btn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo WEB_ROOT.'admin/customer/shipping_locations/index.php?action=delete&lid='.$receipt['shipping_location_id'].'&customer_id='.$customer_id;  ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
     </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
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