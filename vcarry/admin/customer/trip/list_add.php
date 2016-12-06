<?php if(!isset($_GET['id']) || !isset($_GET['state']))
{
header("Location: ".WEB_ROOT."admin/accounts/");
exit;
}
$customer_id=$_GET['id'];
$from_shipping_id = $_GET['state'];
if(!is_numeric($customer_id) || !is_numeric($from_shipping_id))
{ ?>
<script>
  window.history.back()
</script>
<?php
}

$from_shipping_location = getShippingLocationForshippingLocationId($from_shipping_id);
$shipping_locations = listShippingLocationForCustomerId($customer_id,$from_shipping_id);

$customer = getCustomerDetailsByCustomerId($customer_id);
?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment"> Add Trip </h4>
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

<td class="firstColumnStyling">
Follow Up Date <span class="requiredField">* </span>: 
</td>

<td>
<input type="text" id="reminder_date" size="12" autocomplete="off"  name="trip_date" class="datepicker1 datepick reminder_date" placeholder="Click to Select!" value="<?php echo date('d/m/Y',strtotime(getTodaysDateTime())); ?>" /><span class="customError DateError">Please select a date!</span> 
</td>
</tr>


<tr>

<tr>

<td class="firstColumnStyling">
Time <span class="requiredField">* </span>: 
</td>

<td>
<div class="demo">
                
                <p>
                    <input id="setTimeExample" type="text" class="time"  name="trip_time" value="<?php echo date('H:i:s',strtotime(getTodaysDateTime())); ?>"/>
                    
                </p>
            </div>

            <script>
                $(function() {
                    $('#setTimeExample').timepicker({
						'timeFormat': 'H:i:s',
						 'showDuration': true,
						
						 'step': 15
		
		
        
    });
                  
                        
                    });
               
            </script>

            
</td>
</tr>

<tr>
<td>
Vehicle Type<span class="requiredField">* </span> : 
</td>

<td>
<select id="vehicle_type_id" name="vehicle_type_id" class="vehicle_type" onchange="createDropDownVehicleTypeDriver(this.value)">
                        <option value="-1" >--Please Select--</option>
                        <?php
                            $cities = listVehicleTypes();
                            foreach($cities as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['vehicle_type_id'] ?>"><?php echo $super['vehicle_type'] ?></option					>
                             <?php } ?>
                              
                         
                            </select> 
</td>
</tr>
<tr>
<td width="230px" class="firstColumnStyling">
From Shipping Location<span class="requiredField">* </span> : 
</td>
<td></td>
</tr>



<tr>
<td colspan="2">
<input type="hidden" name="from_shipping_id" id="from_shipping_id" value="<?php echo $from_shipping_id ?>" /> 
<input type="hidden" name="from_area_id" id="from_area_id" value="<?php echo $from_shipping_location['area_id']; ?>" /> 
 <table  class="adminContentTable">
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
           
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		$no=0;
		
		{
			
			
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
           
            <td><?php echo $from_shipping_location['shipping_name']; ?>
            </td>
            <td><?php echo $from_shipping_location['shipping_address']; ?>
            </td>
            <td><?php echo $from_shipping_location['city_name']; ?>
            </td>
          	 <td><?php echo $from_shipping_location['area_name']; ?></td>
             <td><?php echo $from_shipping_location['cp_name']; ?>
            </td>
            <td><?php echo $from_shipping_location['cp_contact_no']; ?>
            </td>
             <td><?php echo $from_shipping_location['recess_timings_from']." - ".$from_shipping_location['recess_timings_to']; ?>
            </td>
             <td><?php echo $from_shipping_location['goods_type']; ?>
            </td>
            <td><?php echo $from_shipping_location['goods_weight_range']; ?>
            </td>
        
            
            
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
</td>
</tr>

<tr>
<td>
To Shiping Location<span class="requiredField">* </span> : 
</td>
<td></td>
</tr>
<tr>
<td colspan="2">
	
    <table id="" class="adminContentTable">
    <thead>
    	<tr>
        <th class="heading"></th>
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
           
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		$no=0;
		foreach($shipping_locations as $receipt)
		{
			
			
		 ?>
          <tr class="resultRow">
          <td><input type="radio" name="to_shipping_id" value="<?php echo $receipt['shipping_location_id']; ?>" onChange="getFareDetails(this.value)" /></td>
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
        
            
            
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
     
</td>
</tr>





<!--<tr>
<td>
Driver : 
</td>

<td>
<select id="driver_id" name="driver_id" class="driver_id" >
                        <option value="-1" >--Please Select--</option>
                       
                            </select> 
</td>
</tr> -->

<tr id="route_fare" style="display:none;">
<td>
Fare : 
</td>

<td>
<input type="text" id="" name="route_fare" class="route_fare" placeholder="only Digits!" />                         
</td>
</tr>


 
</table>

<table>
<tr>
<td width="250px;"></td>
<td>
<input id="disableSubmit" type="submit" value="Add Trip"  class="btn btn-warning">
<a href="<?php echo WEB_ROOT."admin/customer/index.php?view=details&id=".$customer_id; ?>"><input type="button" class="btn btn-success" value="Back"/></a>
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
	
	function getFareDetails(to_shipping_id) 
{
	var from_shipping_id = $('#from_shipping_id').val();
	var vehicle_type_id = $('#vehicle_type_id').val();
	if(vehicle_type_id==-1)
	{
	alert("Select Vehicle Type First!");
	return false;
	}
	var xmlhttp1;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp1 = new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
  }

  xmlhttp1.onreadystatechange=function()                        
  {
  if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
    {
	
    var route_fare=xmlhttp1.responseText;

	if(route_fare==0)
	{
		$('#route_fare').show();
	}
	else
	{
		$('#route_fare').hide();
	}
// Before adding new we must remove previously loaded elements

    }
  }
  
  xmlhttp1.open('GET', "getRouteFare.php?from_id="+from_shipping_id+"&to_id="+to_shipping_id+"&vehicle_type_id="+vehicle_type_id,true);    
  xmlhttp1.send(null);
	
}

 
</script>