<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
}
	
$customerDetails=getCustomerById($_GET['lid']);
$customer_id=$_GET['lid'];

$extraCustomerDetails = getExtraCustomerDetailsById($customer_id);


?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Extra Customer Details</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=editExtraCustomerDetails'; ?>" method="post">

<table class="insertTableStyling no_print">
<input type="hidden" name="lid" value="<?php echo $customerDetails['customer_id'] ?>" />



<tr>
<td class="firstColumnStyling">
Date of Birth : 
</td>

<td>
<input type="text" id="datepicker" size="12" autocomplete="off" 
value="<?php 
 $customerDob = $extraCustomerDetails['customer_dob'];
  $customerDob = date('d/m/Y',strtotime($customerDob));
  echo $customerDob;

 ?>"  
name="dob" class="datepicker2 datepick" placeholder="Click to Select!" />
<span class="customError DateError">Please select a date!</span>
</td>
</tr>


<tr>
<td>
Address <span class="requiredField">* </span> : 
</td>

<td>
<textarea id="address" class="address" name="address"  cols="5" rows="6">
<?php
echo $extraCustomerDetails['customer_address'];
?>
</textarea>
</td>
</tr>

<tr>
<td width="130px" class="firstColumnStyling"> City <span class="requiredField">* </span> : </td>
<td>
					<select id="city" name="city_id">
                        <option value="-1" >-- Select The City --</option>
                        <?php
                            $cities = listCities();
                            foreach($cities as $city)
                              {
                             ?>
                             
                             <option value="<?php echo $city['city_id']; ?> " <?php if($city['city_id']==$extraCustomerDetails['city_id']) { ?> selected="selected" <?php } ?>><?php echo $city['city_name'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
</td>
</tr>


<tr>
<td>Area<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="customer_area" class="city_area" id="city_area1" placeholder="Only Letters" 
                    <?php
if($extraCustomerDetails['area_id'] != -1)
{
	$areaDetails = getAreaByID($extraCustomerDetails['area_id']);
	$area_name = $areaDetails['area_name'];
?> 
value="<?php echo $area_name;?>"
<?php
}
?>
/>
                            </td>
</tr>

<tr>
<td>
 Secondary Address : 
</td>

<td>
<textarea id="secondary_address" class="secondary_address" name="secondary_address"  cols="5" rows="6">
<?php
echo $extraCustomerDetails['secondary_address'];
?>
</textarea>
</td>
</tr>

<tr>
<td class="firstColumnStyling"> Nationality : </td>
<td>
                         <select  name="customer_nationality">
                             
                             <option value="1" 
							 <?php if($extraCustomerDetails['customer_nationality']==1){ ?> 
                             selected="selected" <?php }?>>Indian</option>
                             
                              <option value="0" <?php if($extraCustomerDetails['customer_nationality']==0){ ?> 
                             selected="selected"<?php }?>>Other</option>
                             
                          </select> 
                            
</td>
</tr>

<tr>
<td width="130px" class="firstColumnStyling"> Profession : </td>
<td>
					<select id="profession_id" name="profession_id">
                        <option value="-1" >-- Select The Profession --</option>
                        <?php
                            $professions = listProfessions();
                            foreach($professions as $profession)
                              {
                             ?>
                             
                             <option value="<?php echo $profession['profession_id']; ?> " <?php if($profession['profession_id']==$extraCustomerDetails['profession_id']) { ?> selected="selected" <?php } ?>><?php echo $profession['profession'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
</td>
</tr>


<tr>
<td width="220px" class="firstColumnStyling"> Data From : </td>
<td>
					<select id="data_from" name="data_from_id" class="selectpic show-tick form-control"  data-live-search="true">
                        <option value="-1" >-- Please Select --</option>
                         <?php
                            $dataFrom = listDataFrom();
                            foreach($dataFrom as $df)
                              {
                             ?>
                             
                           <option value="<?php echo $df['data_from_id'] ?>" <?php if($df['data_from_id']==$extraCustomerDetails['data_from_id']) { ?> selected="selected" <?php } ?> > <?php echo $df['data_from'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
</td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" value="Save" class="btn btn-warning">

<a href="<?php echo WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$customer_id ?>">
<input type="button" value="back" class="btn btn-success" />
</a>

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
                { term: request.term, city_id:$('#city').val() }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#city_area1" ).val(ui.item.label);
			return false;
		}
    });
</script>