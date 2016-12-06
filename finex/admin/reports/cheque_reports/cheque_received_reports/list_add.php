<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Customer Cheque Reports</h4>
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
<td>From Date (Entry Date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="start_date" id="start_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cChequeAddedReport']['from'])) echo $_SESSION['cChequeAddedReport']['from']; ?>" />	
                 </td>
</tr>


<tr>
<td>Up To Date (Entry Date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="end_date" id="end_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cChequeAddedReport']['to'])) echo $_SESSION['cChequeAddedReport']['to']; ?>"/>	
                 </td>
</tr>


<tr>
<td>Pending Cheques (>=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="pending_cheques" id="pending_cheques" placeholder="min Qty of pending cheques" class="datepicker2" value="<?php if(isset($_SESSION['cChequeAddedReport']['pending_cheques'])) echo $_SESSION['cChequeAddedReport']['pending_cheques']; ?>"/>	
                 </td>
</tr>

<tr>
<td>Cheques Added : </td>
				<td>
				 <select name="cheques_added" >
                 <option value="" <?php if(isset($_SESSION['cChequeAddedReport']['cheques_added']) && !is_numeric($_SESSION['cChequeAddedReport']['cheques_added'])) {  ?> selected <?php } ?>>All</option>
                 <option value="0" <?php if(isset($_SESSION['cChequeAddedReport']['cheques_added']) && $_SESSION['cChequeAddedReport']['cheques_added']==0) {  ?> selected <?php } ?>>No</option>
                 <option value="1" <?php if(isset($_SESSION['cChequeAddedReport']['cheques_added']) && $_SESSION['cChequeAddedReport']['cheques_added']==1) {  ?> selected <?php } ?>>Yes</option>
                 </select>
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
                             
                             <option value="<?php echo $super['city_id'] ?>" <?php if(isset($_SESSION['cChequeAddedReport']['city_id'])){ if( $super['city_id'] == $_SESSION['cChequeAddedReport']['city_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['city_name'] ?></option					>
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
						  if(isset($_SESSION['cChequeAddedReport']['city_id'])){
                            $areas = listAreasFromCityIdWithGroups($_SESSION['cChequeAddedReport']['city_id']);
                            foreach($areas as $area)
                              {
                             ?>
                             
                             <option value="<?php echo $area['area_id'] ?>" <?php if(isset($_SESSION['cChequeAddedReport']['area_id_array'])){ if(in_array($area['area_id'],$_SESSION['cChequeAddedReport']['area_id_array'])) { ?> selected="selected" <?php }} ?>><?php echo $area['area_name'] ?></option					>
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
                             
                             <option value="ag<?php echo $super['agency_id'] ?>" <?php if(isset($_SESSION['cChequeAddedReport']['agency_id'])){ if( "ag".$super['agency_id'] == $_SESSION['cChequeAddedReport']['agency_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['agency_name'] ?></option>
                             
                             <?php } ?>
                              
                             <?php 
							 
							 $companies = listOurCompanies();
                              foreach($companies as $com)
							
                              {
                             ?>
                             
                             <option value="oc<?php echo $com['our_company_id'] ?>" <?php if(isset($_SESSION['cChequeAddedReport']['agency_id'])){ if( "oc".$com['our_company_id'] == $_SESSION['cChequeAddedReport']['agency_id'] ) { ?> selected="selected" <?php }} ?> ><?php echo $com['our_company_name'] ?></option>
                             
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
                             <option value="<?php echo $broker['broker_id'] ?>" <?php if(isset($_SESSION['cChequeAddedReport']['broker_id_array'])){ if(in_array($broker['broker_id'],$_SESSION['cChequeAddedReport']['broker_id_array'])) { ?> selected="selected" <?php }} ?>><?php echo $broker['broker_name'] ?></option					>
                             <?php } 
						  
							 ?>
                    </select>
                            </td>
</tr>

<tr>
	<td>File Status:</td>
    <td>
    	<input  type="radio" name="file_status" id="open" value="1" <?php if(isset($_SESSION['cChequeAddedReport']['file_status'])){ if(  $_SESSION['cChequeAddedReport']['file_status']==1 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="open">Open</label>
		<input  type="radio" name="file_status" id="closed" value="2" <?php if(isset($_SESSION['cChequeAddedReport']['file_status'])){ if( $_SESSION['cChequeAddedReport']['file_status']==2 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="closed">Closed</label>
    	<input  type="radio" name="file_status" id="closed_unpaid" value="5" <?php if(isset($_SESSION['cChequeAddedReport']['file_status'])){ if( $_SESSION['cChequeAddedReport']['file_status']==5 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="closed_unpaid">Closed & unpaid</label>
        <input  type="radio" name="file_status" id="running" value="6" <?php if(isset($_SESSION['cChequeAddedReport']['file_status'])){ if( $_SESSION['cChequeAddedReport']['file_status']==6 ) { ?> checked="checked" <?php }} else { ?> checked="checked"  <?php } ?> /> <label style="display:inline-block;top:3px;position:relative;" for="running">Running</label>
    	<input  type="radio" name="file_status" id="both"  <?php if(isset($_SESSION['cChequeAddedReport']['file_status']) && ($_SESSION['cChequeAddedReport']['file_status']!=1 && $_SESSION['cChequeAddedReport']['file_status']!=2 && $_SESSION['cChequeAddedReport']['file_status']!=5 && $_SESSION['cChequeAddedReport']['file_status']!=6)){  ?> checked="checked" <?php } ?> /> <label style="display:inline-block;top:3px;position:relative;" for="both">All</label>
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
 <?php if(isset($_SESSION['cChequeAddedReport']['remainder_array']))
{
	
	$emi_array=$_SESSION['cChequeAddedReport']['remainder_array'];
		
		
	 ?>    
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>     
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">File No</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Reg No</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Chasis No</label> 
         <input class="showCB" type="checkbox" id="5" checked="checked"  /><label class="showLabel" for="5">Engine No</label>
          
                  <input class="showCB" type="checkbox" id="6" checked="checked"  /><label class="showLabel" for="6">Remarks</label> 
        <input class="showCB" type="checkbox" id="7" checked="checked"  /><label class="showLabel" for="7">Name</label> 
        
        <input class="showCB" type="checkbox" id="10" checked="checked"  /><label class="showLabel" for="10">Contact</label> 
          
    </div>
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        <th class="heading">No</th>
        	<th class="heading file">File No</th>
            <th class="heading">Reg No</th>
            <th class="heading">Cheques Required</th>
            <th class="heading">Cheques Received</th>
            <th class="heading">Pending Cheques</th>
            <th class="heading">Remarks</th>
             <th class="heading">Bank Name</th>
              <th class="heading">Branch</th>
             <th class="heading">Ac No</th>
            <th class="heading">Name</th>
          <th class="heading">Address</th>
           <th class="heading">Contact No</th>
           <th class="heading">Broker</th>
            <th class="heading">Emi Date</th>
            
          
            <th class="heading no_print btnCol"></th>
           
        </tr>
    </thead>
    <tbody>
      
        <?php
		if(isset($_SESSION['cChequeAddedReport']['pending_cheques']))
		$pending_cheques = $_SESSION['cChequeAddedReport']['pending_cheques'];
		else
		$pending_cheques = NULL;
		
		if(isset($_SESSION['cChequeAddedReport']['cheques_added']))
		$cheques_added = $_SESSION['cChequeAddedReport']['cheques_added'];
		else
		$cheques_added = NULL;
		
		
		foreach($emi_array as $emi)
		{
			if((!is_numeric($cheques_added) || (($emi['required_cheques']!=NULL && $emi['required_cheques']!="" && $cheques_added==1) || ($emi['required_cheques']=="" && $cheques_added==0))) && (!is_numeric($pending_cheques) || ($emi['required_cheques']=="") || (($emi['required_cheques']-$emi['cheques_received'])>=$pending_cheques) ))
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
            
              <td><?php if($emi['vehicle_reg_no']!=NULL && $emi['vehicle_reg_no']!="") echo $emi['vehicle_reg_no']; else echo "NOT Added"; ?>
            </td>
                <td><?php if($emi['required_cheques']!=NULL && $emi['required_cheques']!="") echo $emi['required_cheques']; else echo "NOT Added"; ?>
            </td>
               <td><?php if($emi['cheques_received']!=NULL && $emi['cheques_received']!="") echo $emi['cheques_received']; else echo "NOT Added"; ?>
            </td>
                <td><?php if($emi['cheques_received']!=NULL && $emi['cheques_received']!="") echo ($emi['required_cheques']-$emi['cheques_received']); else echo "NOT Added"; ?>
            </td>
           
            <td><?php echo $emi['remarks']; ?>	
            </td>
             <td><?php echo $emi['bank_name']; ?>	
            </td>
             <td><?php echo $emi['branch_name']; ?>	
            </td>
              <td><?php echo $emi['ac_no']; ?>	
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
              <td><?php echo $emi['broker_name']; ?></td>
               <td><?php echo date('d',strtotime($emi['loan_starting_date'])); ?></td>
            <td class="no_print"> <?php if($emi['cheque_details_id']!=NULL && $emi['cheque_details_id']!="") {  ?> <a target="_blank" href="<?php echo WEB_ROOT.'admin/customer/loan_cheques/index.php?view=edit&id='.$emi['file_id']; ?>"><button title="View this entry" class="btn btn-warning viewBtn">Update Cheque Details</button></a> <?php } else { ?> <a target="_blank" href="<?php echo WEB_ROOT.'admin/customer/loan_cheques/index.php?&id='.$emi['file_id'].'&state='.$emi['customer']['customer_id']; ?>"><button title="View this entry" class="btn btn-success viewBtn">Add Cheque Details</button></a> <?php } ?> 
            </td>
           
            
          
  
        </tr>
         <?php }} }?>
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