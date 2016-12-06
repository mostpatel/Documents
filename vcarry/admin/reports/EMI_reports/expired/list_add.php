<div class="jvp"><?php if(isset($_SESSION['eEMIReport']['agency_id']) && $_SESSION['eEMIReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['eEMIReport']['agency_id']);  } ?></div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Expired EMI Reports</h4>
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




<input  type="hidden"  name="end_date"  value="<?php echo date('d/m/Y'); ?>"/>	

				 <input  type="hidden"  name="win"  value="1" />	
                 

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
                             
                             <option value="<?php echo $super['city_id'] ?>" <?php if(isset($_SESSION['eEMIReport']['city_id'])){ if( $super['city_id'] == $_SESSION['eEMIReport']['city_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['city_name'] ?></option					>
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
						  if(isset($_SESSION['eEMIReport']['city_id'])){
                            $areas = listAreasFromCityIdWithGroups($_SESSION['eEMIReport']['city_id']);
                            foreach($areas as $area)
                              {
                             ?>
                             
                             <option value="<?php echo $area['area_id'] ?>" <?php if(isset($_SESSION['eEMIReport']['area_id_array'])){ if(in_array($area['area_id'],$_SESSION['eEMIReport']['area_id_array'])) { ?> selected="selected" <?php }} ?>><?php echo $area['area_name'] ?></option					>
                             <?php } 
						  }
							 ?>
                    </select>
                            </td>
</tr>

<tr>
<td width="220px">Agency Name : </td>
				<td>
					<select id="agency_id" name="agency_id">
                        <option value="-1" >--Please Select--</option>
                        <?php
                            $agencies = listAgencies();
							$companies = listOurCompanies();
                            foreach($agencies as $super)
							
                              {
                             ?>
                             
                             <option value="ag<?php echo $super['agency_id'] ?>" <?php if(isset($_SESSION['eEMIReport']['agency_id'])){ if( "ag".$super['agency_id'] == $_SESSION['eEMIReport']['agency_id'] ) { ?> selected="selected" <?php }} ?> ><?php echo $super['agency_name'] ?></option>
                             
                             <?php } ?>
                              
                             <?php 
							 
							 $companies = listOurCompanies();
                              foreach($companies as $com)
							
                              {
                             ?>
                             
                             <option value="oc<?php echo $com['our_company_id'] ?>" <?php if(isset($_SESSION['eEMIReport']['agency_id'])){ if( "oc".$com['our_company_id'] == $_SESSION['eEMIReport']['agency_id'] ) { ?> selected="selected" <?php }} ?> ><?php echo $com['our_company_name'] ?></option>
                             
                             <?php } ?>
                              
                         
                            </select> 
                    </td>
                    
                    
                  
</tr>

<tr>
<td>Broker Name : </td>
				<td>
					<select name="broker[]" class="broker selectpicker" multiple="multiple"  id="broker" >
                    	 <option value="-1" disabled="disabled">--Please Select--</option>
                          <?php
						  $brokers=listBrokers();
						  
                          
                            foreach($brokers as $broker)
                              {
                             ?>
                             <option value="<?php echo $broker['broker_id'] ?>" <?php if(isset($_SESSION['eEMIReport']['broker_id_array'])){ if(in_array($broker['broker_id'],$_SESSION['eEMIReport']['broker_id_array'])) { ?> selected="selected" <?php }} ?>><?php echo $broker['broker_name'] ?></option					>
                             <?php } 
						  
							 ?>
                    </select>
                            </td>
</tr>


<tr>
	<td>File Status:</td>
    <td>
    	<input  type="radio" name="file_status" id="flat_loan" value="1" <?php if(isset($_SESSION['eEMIReport']['file_status'])){ if(  $_SESSION['eEMIReport']['file_status']==1 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="flat_loan">Open</label>
		<input  type="radio" name="file_status" id="reducing_loan" value="2" <?php if(isset($_SESSION['eEMIReport']['file_status'])){ if( $_SESSION['eEMIReport']['file_status']==2 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="reducing_loan">Closed</label>
    	<input  type="radio" name="file_status" id="closed_unpaid" value="5" <?php if(isset($_SESSION['eEMIReport']['file_status'])){ if( $_SESSION['eEMIReport']['file_status']==5 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="closed_unpaid">Closed & unpaid</label>
        <input  type="radio" name="file_status" id="running" value="6" <?php if(isset($_SESSION['eEMIReport']['file_status'])){ if( $_SESSION['eEMIReport']['file_status']==6 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="running">Running</label>
    	<input  type="radio" name="file_status" id="both"  <?php if(!isset($_SESSION['eEMIReport']['file_status']) || ($_SESSION['eEMIReport']['file_status']!=1 && $_SESSION['eEMIReport']['file_status']!=2 && $_SESSION['eEMIReport']['file_status']!=5 && $_SESSION['eEMIReport']['file_status']!=6)){  ?> checked="checked" <?php } ?> /> <label style="display:inline-block;top:3px;position:relative;" for="both">All</label>
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
 <?php if(isset($_SESSION['eEMIReport']['emi_array']))
{

	$emi_array=$_SESSION['eEMIReport']['emi_array'];
		
		
	 ?>    
   <div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>  
<div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">File No</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Reg No</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Last EMI Date</label> 
        <input class="showCB" type="checkbox" id="5" checked="checked"   /><label class="showLabel" for="5">EMI</label> 
        <input class="showCB" type="checkbox" id="6" checked="checked"  /><label class="showLabel" for="6">Bucket</label> 
         <input class="showCB" type="checkbox" id="7" checked="checked"  /><label class="showLabel" for="7">Balance</label> 
        <input class="showCB" type="checkbox" id="8" checked="checked"  /><label class="showLabel" for="8">Name</label> 
         <input class="showCB" type="checkbox" id="9" checked="checked"  /><label class="showLabel" for="9">Address</label> 
          <input class="showCB" type="checkbox" id="10" checked="checked"  /><label class="showLabel" for="10">Contact No</label> 
    </div>
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        	<th class="heading">No</th>
            <th class="heading file">File No</th>
            <th class="heading">Reg No</th>
            <th class="heading date">Last EMI Date</th>
            <th class="heading numeric">EMI</th>
            <th class="heading">Bucket</th>
            <th class="heading">Balance</th>
            <th class="heading">Name</th>
            <th width="10%" class="heading">Address</th>
            <th class="heading">Contact No</th>
            <th class="heading no_print btnCol"></th>
           
        </tr>
    </thead>
      <tbody>
      
        <?php
		$total_no_agencies=getTotalNoOfAgencies();
		$total=0;
		foreach($emi_array as $emi)
		{
			
		 ?>
         <tr class="resultRow">
         	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$i; ?></td>
            <td><span style="display:none"><?php 
			if(is_numeric($emi['agency_id'])) {
				$prefix=$emi['agnecy_id'];}
			else if(is_numeric($emi['oc_id']))
			{$prefix=$total_no_agencies+$emi['oc_id']; }
			
			echo $prefix.".".preg_replace('/[^0-9]+/', '', $emi['file_no']); ?></span> <?php  echo  $emi['file_no']; ?>
            </td>
              <td><?php if($emi['reg_no']!=null && $emi['reg_no']!="") echo $emi['reg_no']; else echo "NA"; ?>
            </td>
           <td><?php  $last_emi_date=date('d/m/Y',strtotime($emi['emi_date'])); if($last_emi_date!='01/01/1970') echo $last_emi_date; else echo "NA"; ?>
            </td>
            <td width="160px"><?php  $total_bucket=0; if(isset($emi['bucket_details']) && $emi['bucket_details']!=0 && is_array($emi['bucket_details']) && count($emi['bucket_details'])>1 && $emi['file_status']!=4) { foreach($emi['bucket_details'] as $e=>$corr_bucket) { $total_bucket=$total_bucket+$corr_bucket; echo $e." X ".$corr_bucket." <br>";} }else if($emi['file_status']!=4){ $total_bucket=$emi['bucket_details'][$emi['emi']]; echo $emi['emi']; } else if($emi['file_status']==4 && $emi['loan_scheme']!="error"){ if(is_array($emi['loan_scheme'])) {  foreach($emi['loan_scheme'] as $scheme){ echo $scheme['emi']." X ".$scheme['duration']."<br>"; } } }; ?>
            </td>
            <td><?php
			
			  if($emi['file_status']!=4) echo $total_bucket; else echo 0.0; ?>
            </td>
           <td>
           		<?php   echo $emi['balance']; 
				$total=$total+$emi['balance'];
				?>
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
							echo $contact[0]." <br> ";	
							}	
							
			 	?></td>
             <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/index.php?view=details&id='.$emi['file_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
           
            
          
  
        </tr>
         <?php } }?>
         </tbody>
    </table>
    </div>
    <table class="reportFiltersTable">
    <tr>
    	<td> Bucket : <?php if(isset($_SESSION['eEMIReport']['win']) && $_SESSION['eEMIReport']['win']!="") echo $_SESSION['eEMIReport']['win']; else echo "NA"; ?></td>
        <td> City : <?php if(isset($_SESSION['eEMIReport']['city_id']) && $_SESSION['eEMIReport']['city_id']!="") {$city=getCityByID($_SESSION['eEMIReport']['city_id']); echo $city['city_name']; } else echo "NA"; ?></td>
       <td> Agency : <?php if(isset($_SESSION['eEMIReport']['agency_id']) && $_SESSION['eEMIReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['eEMIReport']['agency_id']);  } else echo "NA"; ?></td>
        <td> File Status : <?php if(isset($_SESSION['eEMIReport']['file_status']) && $_SESSION['eEMIReport']['file_status']!="") { if($_SESSION['eEMIReport']['file_status']==1) echo "OPEN";else if($_SESSION['eEMIReport']['file_status']==2) echo "CLOSED";  } else echo "BOTH"; ?></td>
    </tr>
    </table> 
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