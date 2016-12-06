<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">General Insurance Reports</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=generateReport'; ?>" method="post" enctype="multipart/form-data" onsubmit="return submitOurCompany()">

<table class="insertTableStyling no_print">

<tr>
<td>From Date (Expiry Date): </td>
				<td>
				 <input autocomplete="off" type="text"  name="start_date" id="start_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cinsuranceReport']['from'])) echo $_SESSION['cinsuranceReport']['from']; ?>" />	
                 </td>
</tr>


<tr>
<td>Up To Date (Expiry Date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="end_date" id="end_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cinsuranceReport']['to'])) echo $_SESSION['cinsuranceReport']['to']; ?>"/>	
                 </td>
</tr>

<tr>
<td>City : </td>
				<td>
					<select id="customer_city_id" name="city_id" class="city"   onchange="createDropDownAreaReports(this.value)">
                        <option value="-1" >--Please Select--</option>
                        <?php
                            $cities = listCitiesAlpha();
                            foreach($cities as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['city_id'] ?>" <?php if(isset($_SESSION['cinsuranceReport']['city_id'])){ if( $super['city_id'] == $_SESSION['cinsuranceReport']['city_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['city_name'] ?></option					>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>
<td>Area : </td>
				<td>
					<select name="area[]" class="city_area selectpicker" multiple="multiple"  id="city_area1" >
                    	 <option value="-1" >--Please Select--</option>
                          <?php
						  if(isset($_SESSION['cinsuranceReport']['city_id'])){
                            $areas = listAreasFromCityIdWithGroups($_SESSION['cinsuranceReport']['city_id']);
                            foreach($areas as $area)
                              {
                             ?>
                             
                             <option value="<?php echo $area['area_id'] ?>" <?php if(isset($_SESSION['cinsuranceReport']['area_id_array'])){ if(in_array($area['area_id'],$_SESSION['cinsuranceReport']['area_id_array'])) { ?> selected="selected" <?php }} ?>><?php echo $area['area_name'] ?></option					>
                             <?php } 
						  }
							 ?>
                    </select>
                            </td>
</tr>

<tr>


<td></td>
				<td>
				 <input type="submit" value="Generate" class="btn btn-warning"/>	
                </td>
</tr>


</table>

</form>

  
<hr class="firstTableFinishing" />

	<div class="no_print">
 <?php if(isset($_SESSION['cinsuranceReport']['insurance_array']))
{

	$emi_array=$_SESSION['cinsuranceReport']['insurance_array'];
		
		
	 ?> 
    <div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">File No</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Reg No</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Expiry Date</label> 
        <input class="showCB" type="checkbox" id="5" checked="checked"   /><label class="showLabel" for="5">Premium</label> 
        <input class="showCB" type="checkbox" id="6" checked="checked"  /><label class="showLabel" for="6">Insurance Company</label> 
         <input class="showCB" type="checkbox" id="7" checked="checked"  /><label class="showLabel" for="7">IDV</label> 
        <input class="showCB" type="checkbox" id="8" checked="checked"  /><label class="showLabel" for="8">Name</label> 
         <input class="showCB" type="checkbox" id="9" checked="checked"  /><label class="showLabel" for="9">Address</label> 
          <input class="showCB" type="checkbox" id="10" checked="checked"  /><label class="showLabel" for="10">Contact No</label> 
    </div>    
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        	<th class="heading">No</th>
            <th class="heading">Reg No</th>
            <th class="heading date">Expiry Date</th>
            <th class="heading">Premium</th>
            <th class="heading">Insurance Company</th>
            <th class="heading">IDV</th>
            <th class="heading">Name</th>
            <th width="10%" class="heading">Address</th>
            <th class="heading">Contact No</th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
       
        <?php
		
		foreach($emi_array as $emi)
		{
		
		 ?>
         <tr class="resultRow">
         <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$i; ?></td>
            
              <td><?php if($emi['vehicle_reg_no']!=null && $emi['vehicle_reg_no']!="") echo $emi['vehicle_reg_no']; else echo "NA"; ?>
            </td>
            <td><span style="display:none;"><?php echo $emi['insurance_expiry_date']; ?></span><?php echo date('d/m/Y',strtotime($emi['insurance_expiry_date'])); ?>
            </td>
            <td><?php echo $emi['insurance_premium']; ?>
            </td>
            <td><?php   echo $emi['insurance_company_name']; ?>
            </td>
            <td><?php   echo $emi['idv']; ?>
            </td>
            <td><?php   echo $emi['customer_name']; ?></td>
             <td><?php   echo $emi['customer_address']; ?></td>
             <td><?php   $contactString = $emi['GROUP_CONCAT(customer_contact_no)']; 
			 			
			 			echo $contactString;
							
			 	?></td>
             <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/insurance/index.php?view=details&id='.$emi['customer_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
           
            
          
  
        </tr>
         <?php } }?>
         </tbody>
    </table>
    </div>
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
<?php  ?>      
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