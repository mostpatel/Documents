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
<td>From Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="start_date" id="start_date" placeholder="Click to select Date!" class="datepicker2"/>	
                 </td>
</tr>


<tr>
<td>Up To Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="end_date" id="end_date" placeholder="Click to select Date!" class="datepicker2"/>	
                 </td>
</tr>

<tr>
<td>City : </td>
				<td>
					<select id="customer_city_id" name="city_id" class="city" onchange="createDropDownAreaCustomer(this.value)">
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
<td>Area : </td>
				<td>
					<input type="text" name="area" class="city_area" id="city_area1" placeholder="Only Letters" />
                            </td>
</tr>


<td></td>
				<td>
				 <input type="submit" value="Generate" class="btn btn-warning"/>	
                </td>
</tr>


</table>

</form>

  
<hr class="firstTableFinishing" />

	<div class="no_print">
 <?php if(isset($_SESSION['minsuranceReport']['insurance_array']))
{

	$emi_array=$_SESSION['minsuranceReport']['insurance_array'];
		
		
	 ?> 
    <div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>    
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading file">File No</th>
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
        	<td><?php echo ++$i; ?></td>
             <td><span style="display:none"><?php $infoArray=getAgencyOrCompanyIdFromFileId($emi['file_id']); 
			if($infoArray[0]=='agency') {
				$prefix=$infoArray[1];}
			else if($infoArray[0]=='oc')
			{$prefix=getTotalNoOfAgencies()+$infoArray[1]; }
			
			echo $prefix.".".preg_replace('/[a-zA-Z]+/', '', $emi['file_no']); ?></span> <?php  echo  $emi['file_no']; ?>
            </td>
              <td><?php if($emi['reg_no']!=null && $emi['reg_no']!="") echo $emi['reg_no']; else echo "NA"; ?>
            </td>
            <td><span style="display:none;"><?php echo $emi['insurance_expiry_date']; ?></span><?php echo date('d/m/Y',strtotime($emi['insurance_expiry_date'])); ?>
            </td>
            <td><?php echo $emi['insurance']['insurance_premium']; ?>
            </td>
            <td><?php  $comp = getInsuranceCompanyById($emi['insurance']['insurance_company_id']); echo $comp[1]; ?>
            </td>
            <td><?php   echo $emi['insurance']['idv']; ?>
            </td>
            <td><?php   echo $emi['customer']['customer_name']; ?></td>
             <td><?php   echo $emi['customer']['customer_address']; ?></td>
             <td><?php   $contactArray = $emi['customer']['contact_no']; 
			 			
			 			for($j=0;$j<count($contactArray);$j++)
						{
							$contact=$contactArray[$j];
							if($j==(count($contactArray)-1))
							{
								echo $contact[0];
								}
							else
							echo $contact[0]." | ";	
							}	
							
			 	?></td>
             <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/insurance/index.php?view=details&id='.$emi['file_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
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