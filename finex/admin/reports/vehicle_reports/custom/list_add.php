<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Vehicle Document Reports</h4>

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
<td>From Date  : </td>
				<td>
				 <input autocomplete="off" type="text"  name="start_date" id="start_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cVehicleReport']['from'])) echo $_SESSION['cVehicleReport']['from']; ?>" />	
                 </td>
</tr>


<tr>
<td>Up To Date  : </td>
				<td>
				 <input autocomplete="off" type="text"  name="end_date" id="end_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cVehicleReport']['to'])) echo $_SESSION['cVehicleReport']['to']; ?>"/>	
                 </td>
</tr>


<tr>

<td>Date Type : </td>
				<td>
					<select name="date_type" class="broker selectpicker"  id="date_type" >
                    	 
                          <option value="0" <?php if(isset($_SESSION['cVehicleReport']['date_type'])){ if(in_array(0,$_SESSION['cVehicleReport']['date_type'])) { ?> selected="selected" <?php }} ?> >Entry Date</option>
                          <option value="1" <?php if(isset($_SESSION['cVehicleReport']['date_type'])){ if(in_array(1,$_SESSION['cVehicleReport']['date_type'])) { ?> selected="selected" <?php }} ?> >Agent Given Date</option>
                          <option value="2" <?php if(isset($_SESSION['cVehicleReport']['date_type'])){ if(in_array(2,$_SESSION['cVehicleReport']['date_type'])) { ?> selected="selected" <?php }} ?> >Customer Given Date</option> 
                          
						 
                    </select>
                            </td>
</tr>



<tr>
<tr>
<td>RTO Agent : </td>
				<td>
					<select id="rto_agent" name="rto_agent_id">
                        <option value="-1" >--Please Select Agent--</option>
                        <?php
                            $companies = listRtoAgents();
                            foreach($companies as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['rto_agent_id'] ?>" <?php if($super['rto_agent_id']==$_SESSION['cVehicleReport']['rto_agent_id']) { ?> selected="selected" <?php } ?>><?php echo $super['rto_agent_name'] ?></option					>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>

<td>Docs With : </td>
				<td>
					<select name="docs_with" class="broker selectpicker"  id="docs_with" >
                    	  <option value="-1" >--Please Select--</option>
                          <option value="1" <?php if(isset($_SESSION['cVehicleReport']['docs_with'])){ if(1==$_SESSION['cVehicleReport']['docs_with']) { ?> selected="selected" <?php }} ?> >With us</option>
                          <option value="2" <?php if(isset($_SESSION['cVehicleReport']['docs_with'])){ if(2==$_SESSION['cVehicleReport']['docs_with']) { ?> selected="selected" <?php }} ?> >Agent</option>
                          <option value="3" <?php if(isset($_SESSION['cVehicleReport']['docs_with'])){ if(3==$_SESSION['cVehicleReport']['docs_with']) { ?> selected="selected" <?php }} ?> >Customer</option> 
                          
						 
                    </select>
                            </td>
</tr>


<tr>

<td>Docs Type : </td>
				<td>
					<select name="docs_type[]" class="broker selectpicker" multiple="multiple"  id="docs_type" >
                    	 
                          <option value="1" <?php if(isset($_SESSION['cVehicleReport']['docs_type'])){ if(in_array(1,$_SESSION['cVehicleReport']['docs_type'])) { ?> selected="selected" <?php }} ?> >R.C Book</option>
                          <option value="2" <?php if(isset($_SESSION['cVehicleReport']['docs_type'])){ if(in_array(2,$_SESSION['cVehicleReport']['docs_type'])) { ?> selected="selected" <?php }} ?> >Passing</option>
                          <option value="3" <?php if(isset($_SESSION['cVehicleReport']['docs_type'])){ if(in_array(3,$_SESSION['cVehicleReport']['docs_type'])) { ?> selected="selected" <?php }} ?> >Permit</option> 
                           <option value="4" <?php if(isset($_SESSION['cVehicleReport']['docs_type'])){ if(in_array(4,$_SESSION['cVehicleReport']['docs_type'])) { ?> selected="selected" <?php }} ?> >Insurance</option> 
                            <option value="5" <?php if(isset($_SESSION['cVehicleReport']['docs_type'])){ if(in_array(5,$_SESSION['cVehicleReport']['docs_type'])) { ?> selected="selected" <?php }} ?> >HP</option> 
                             <option value="6" <?php if(isset($_SESSION['cVehicleReport']['docs_type'])){ if(in_array(6,$_SESSION['cVehicleReport']['docs_type'])) { ?> selected="selected" <?php }} ?> >Bill</option> 
                              <option value="7" <?php if(isset($_SESSION['cVehicleReport']['docs_type'])){ if(in_array(7,$_SESSION['cVehicleReport']['docs_type'])) { ?> selected="selected" <?php }} ?> >Key</option> 
                          
						 
                    </select>
                            </td>
</tr>


<td>City : </td>
				<td>
					<select id="customer_city_id" name="city_id" class="city"   onchange="createDropDownAreaReports(this.value)">
                        <option value="-1" >--Please Select--</option>
                        <?php
                            $cities = listCitiesAlpha();
                            foreach($cities as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['city_id'] ?>" <?php if(isset($_SESSION['cVehicleReport']['city_id'])){ if( $super['city_id'] == $_SESSION['cVehicleReport']['city_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['city_name'] ?></option					>
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
						  if(isset($_SESSION['cVehicleReport']['city_id'])){
                            $areas = listAreasFromCityIdWithGroups($_SESSION['cVehicleReport']['city_id']);
                            foreach($areas as $area)
                              {
                             ?>
                             
                             <option value="<?php echo $area['area_id'] ?>" <?php if(isset($_SESSION['cVehicleReport']['area_id_array'])){ if(in_array($area['area_id'],$_SESSION['cVehicleReport']['area_id_array'])) { ?> selected="selected" <?php }} ?>><?php echo $area['area_name'] ?></option					>
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
                             
                             <option value="ag<?php echo $super['agency_id'] ?>" <?php if(isset($_SESSION['cVehicleReport']['agency_id'])){ if( "ag".$super['agency_id'] == $_SESSION['cVehicleReport']['agency_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['agency_name'] ?></option>
                             
                             <?php } ?>
                              
                             <?php 
							 
							 $companies = listOurCompanies();
                              foreach($companies as $com)
							
                              {
                             ?>
                             
                             <option value="oc<?php echo $com['our_company_id'] ?>" <?php if(isset($_SESSION['cVehicleReport']['agency_id'])){ if( "oc".$com['our_company_id'] == $_SESSION['cVehicleReport']['agency_id'] ) { ?> selected="selected" <?php }} ?> ><?php echo $com['our_company_name'] ?></option>
                             
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
                             <option value="<?php echo $broker['broker_id'] ?>" <?php if(isset($_SESSION['cVehicleReport']['broker_id_array'])){ if(in_array($broker['broker_id'],$_SESSION['cVehicleReport']['broker_id_array'])) { ?> selected="selected" <?php }} ?>><?php echo $broker['broker_name'] ?></option					>
                             <?php } 
						  
							 ?>
                    </select>
                            </td>
</tr>

<tr>
	<td>File Status:</td>
    <td>
    	<input  type="radio" name="file_status" id="open" value="1" <?php if(isset($_SESSION['cVehicleReport']['file_status'])){ if(  $_SESSION['cVehicleReport']['file_status']==1 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="open">Open</label>
		<input  type="radio" name="file_status" id="closed" value="2" <?php if(isset($_SESSION['cVehicleReport']['file_status'])){ if( $_SESSION['cVehicleReport']['file_status']==2 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="closed">Closed</label>
    	<input  type="radio" name="file_status" id="closed_unpaid" value="5" <?php if(isset($_SESSION['cVehicleReport']['file_status'])){ if( $_SESSION['cVehicleReport']['file_status']==5 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="closed_unpaid">Closed & unpaid</label>
        <input  type="radio" name="file_status" id="running" value="6" <?php  if(!isset($_SESSION['cVehicleReport']['file_status']) || $_SESSION['cVehicleReport']['file_status']==6 ) { ?> checked="checked" <?php } ?> /> <label style="display:inline-block;top:3px;position:relative;" for="running">Running</label>
    	<input  type="radio" name="file_status" id="both"  <?php if((isset($_SESSION['cVehicleReport']['file_status']) && $_SESSION['cVehicleReport']['file_status']!=1 && $_SESSION['cVehicleReport']['file_status']!=2 && $_SESSION['cVehicleReport']['file_status']!=5 && $_SESSION['cVehicleReport']['file_status']!=6)){  ?> checked="checked" <?php } ?> /> <label style="display:inline-block;top:3px;position:relative;" for="both">All</label>
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
 <?php if(isset($_SESSION['cVehicleReport']['remainder_array']))
{
	
	$emi_array=$_SESSION['cVehicleReport']['remainder_array'];
		
		
	 ?>    
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>     
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">File No</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Reg No</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">RTO</label> 
         <input class="showCB" type="checkbox" id="5" checked="checked"  /><label class="showLabel" for="5">Passing</label>
          <input class="showCB" type="checkbox" id="6" checked="checked"  /><label class="showLabel" for="6">Permit</label>
           <input class="showCB" type="checkbox" id="7" checked="checked"  /><label class="showLabel" for="7">Insurance</label> 
            <input class="showCB" type="checkbox" id="8" checked="checked"  /><label class="showLabel" for="8">HP</label> 
               <input class="showCB" type="checkbox" id="9" checked="checked"  /><label class="showLabel" for="9">Bill</label> 
                  <input class="showCB" type="checkbox" id="10" checked="checked"  /><label class="showLabel" for="10">Remarks</label> 
        <input class="showCB" type="checkbox" id="11" checked="checked"  /><label class="showLabel" for="11">Name</label> 
         <input class="showCB" type="checkbox" id="12" checked="checked"  /><label class="showLabel" for="12">Address</label> 
        <input class="showCB" type="checkbox" id="13" checked="checked"  /><label class="showLabel" for="13">City</label> 
        <input class="showCB" type="checkbox" id="14" checked="checked"  /><label class="showLabel" for="14">Contact</label> 
          <input class="showCB" type="checkbox" id="15" checked="checked"  /><label class="showLabel" for="15">Broker</label> 
    </div>
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        <th class="heading">No</th>
        	<th class="heading file">File No</th>
              
           
            <th class="heading">Reg No</th>
            <th class="heading">RC</th>
            <th class="heading">Passing</th>
            <th class="heading">Permit</th>
            <th class="heading ">Ins</th>
            <th class="heading ">HP</th>
            <th class="heading ">Bill</th>
            <th class="heading">Key</th>
            <th class="heading ">Remarks</th>
             <th class="heading">RTO Agent</th>
            <th class="heading">Name</th>
            <th class="heading">Broker</th>
             <th class="heading">Updated By</th>
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
            
            <td><span style="display:none"><?php $infoArray=getAgencyOrCompanyIdFromFileId($emi['file_id']); 
			if($infoArray[0]=='agency') {
				$prefix=$infoArray[1];}
			else if($infoArray[0]=='oc')
			{$prefix=getTotalNoOfAgencies()+$infoArray[1]; }
			
			echo $prefix.".".preg_replace('/[^0-9]+/', '', $emi['file_no']); ?></span> <?php  echo  $emi['file_no']; ?>
            </td>
            
              <td><?php if($emi['vehicle_reg_no']!=NULL && $emi['vehicle_reg_no']!="") echo $emi['vehicle_reg_no']; else echo "NA"; ?>
            </td>
            <td class="<?php switch($emi['rto_papers']) { 		
														case 1: echo "shantiRow";
														break;	
														case 2: echo "warningRow";
														break;	
														case 3: echo "dangerRow";
														break;
															}  ?>" ><?php switch($emi['rto_papers']) { 		case 0: echo "-";
														break;
														case 1: echo "Y";
														break;	
														case 2: echo "A";
														break;	
														case 3: echo "C";
														break;
														case 4: echo "NA";
														break;	}  ?> 	
            </td>
            <td class="<?php switch($emi['passing']) { 		
														case 1: echo "shantiRow";
														break;	
														case 2: echo "warningRow";
														break;	
														case 3: echo "dangerRow";
														break;
															}  ?>" ><?php switch($emi['passing']) { 		case 0: echo "-";
														break;
														case 1: echo "Y";
														break;	
														case 2: echo "A";
														break;	
														case 3: echo "C";
														break;
														case 4: echo "NA";
														break;	}  ?> 	 
            </td>
            <td class="<?php switch($emi['permit']) { 		
														case 1: echo "shantiRow";
														break;	
														case 2: echo "warningRow";
														break;	
														case 3: echo "dangerRow";
														break;
															}  ?>" ><?php switch($emi['permit']) { 		case 0: echo "-";
														break;
														case 1: echo "Y";
														break;	
														case 2: echo "A";
														break;	
														case 3: echo "C";
														break;
														case 4: echo "NA";
														break;	}  ?> 	
            </td>
            <td class="<?php switch($emi['insurance']) { 		
														case 1: echo "shantiRow";
														break;	
														case 2: echo "warningRow";
														break;	
														case 3: echo "dangerRow";
														break;
															}  ?>" ><?php switch($emi['insurance']) { 		case 0: echo "-";
														break;
														case 1: echo "Y";
														break;	
														case 2: echo "A";
														break;	
														case 3: echo "C";
														break;
														case 4: echo "NA";
														break;	}  ?> 	
            </td>
            <td class="<?php switch($emi['hp']) { 		
														case 1: echo "shantiRow";
														break;	
														case 2: echo "warningRow";
														break;	
														case 3: echo "dangerRow";
														break;
															}  ?>" ><?php switch($emi['hp']) { 		case 0: echo "-";
														break;
														case 1: echo "Y";
														break;	
														case 2: echo "A";
														break;	
														case 3: echo "C";
														break;
														case 4: echo "NA";
														break;	}  ?> 	
            </td>
            <td class="<?php switch($emi['bill']) { 		
														case 1: echo "shantiRow";
														break;	
														case 2: echo "warningRow";
														break;	
														case 3: echo "dangerRow";
														break;
															}  ?>" ><?php switch($emi['bill']) { 		case 0: echo "-";
														break;
														case 1: echo "Y";
														break;	
														case 2: echo "A";
														break;	
														case 3: echo "C";
														break;
														case 4: echo "NA";
														break;	}  ?> 	
            </td>
             <td class="<?php switch($emi['vehicle_key']) { 		
														case 1: echo "shantiRow";
														break;	
														case 2: echo "warningRow";
														break;	
														case 3: echo "dangerRow";
														break;
															}  ?>" ><?php switch($emi['vehicle_key']) { 		case 0: echo "-";
														break;
														case 1: echo "Y";
														break;	
														case 2: echo "A";
														break;	
														case 3: echo "C";
														break;
														case 4: echo "NA";
														break;	}  ?> 	
            </td>
            <td><?php echo $emi['remarks']; ?>	
            </td>
            <td><?php   echo $emi['rto_agent_name']; ?></td>
            <td><?php   echo $emi['customer']['customer_name']; ?>
           <hr style="margin:0;">
             <?php echo $emi['city']; ?>
             <hr style="margin:0">
            <?php   $contactArray = $emi['customer']['contact_no']; 
			 			
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
                <td><?php echo $emi['broker_name']; ?>	
            </td>
             <td><?php echo $emi['username']; ?>	
            </td>
            <td class="no_print"> <?php if(is_numeric($emi['vehicle_docs_id']) && $emi['vehicle_docs_id']>0) { ?> <a target="_blank" href="<?php echo WEB_ROOT.'admin/customer/vehicle/docs/index.php?view=edit&access=approved&id='.$emi['file_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="edit">E</span></button></a> <?php } else if(is_numeric($emi['vehicle_id']) && $emi['vehicle_id']>0) { ?>  <a target="_blank" href="<?php echo WEB_ROOT.'admin/customer/vehicle/docs/index.php?id='.$emi['file_id']."&state=".$emi['vehicle_id']; ?>"><button title="View this entry" class="btn btn-success">Add Docs</button></a> <?php } else { ?><a target="_blank" href="<?php echo WEB_ROOT.'admin/customer/vehicle/index.php?id='.$emi['file_id'].'&state='.$emi['customer']['customer_id']; ?>"><button title="View this entry" class="btn btn-warning viewBtn">Add Vehicle</button></a><?php } ?>
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