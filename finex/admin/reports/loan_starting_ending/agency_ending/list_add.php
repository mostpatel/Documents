<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Agency Loan Ending Reports</h4>
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

<tr >
<td width="260px;">From Date (Loan Ending Date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="start_date" id="start_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cAgencyEnding']['from'])) echo $_SESSION['cAgencyEnding']['from']; ?>" />	
                 </td>
</tr>


<tr>
<td>Up To Date (Loan Ending Date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="end_date" id="end_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cAgencyEnding']['to'])) echo $_SESSION['cAgencyEnding']['to']; ?>"/>	
                 </td>
</tr>


<!--
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
                             
                             <option value="<?php echo $super['city_id'] ?>" <?php if(isset($_SESSION['cAgencyEnding']['city_id'])){ if( $super['city_id'] == $_SESSION['cAgencyEnding']['city_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['city_name'] ?></option					>
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
						  if(isset($_SESSION['cAgencyEnding']['city_id'])){
                            $areas = listAreasFromCityIdWithGroups($_SESSION['cAgencyEnding']['city_id']);
                            foreach($areas as $area)
                              {
                             ?>
                             
                             <option value="<?php echo $area['area_id'] ?>" <?php if(isset($_SESSION['cAgencyEnding']['area_id_array'])){ if(in_array($area['area_id'],$_SESSION['cAgencyEnding']['area_id_array'])) { ?> selected="selected" <?php }} ?>><?php echo $area['area_name'] ?></option					>
                             <?php } 
						  }
							 ?>
                    </select>
                            </td>
</tr>
-->
<tr>
<td width="220px">Agency Name : </td>
				<td>
					<select id="agency_id" name="agency_id">
                        <option value="-1" >--Please Select--</option>
                        <?php
                            $agencies = listAgencies();
						//	$companies = listOurCompanies();
                            foreach($agencies as $super)
							
                              {
                             ?>
                             
                             <option value="ag<?php echo $super['agency_id'] ?>" <?php if(isset($_SESSION['cAgencyEnding']['agency_id'])){ if( "ag".$super['agency_id'] == $_SESSION['cAgencyEnding']['agency_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['agency_name'] ?></option>
                             
                             <?php } ?>
                              
                             <?php 
							 
						/*	 $companies = listOurCompanies();
                              foreach($companies as $com)
							
                              {
                             ?>
                             
                             <option value="oc<?php echo $com['our_company_id'] ?>" <?php if(isset($_SESSION['cAgencyEnding']['agency_id'])){ if( "oc".$com['our_company_id'] == $_SESSION['cAgencyEnding']['agency_id'] ) { ?> selected="selected" <?php }} ?> ><?php echo $com['our_company_name'] ?></option>
                             
                             <?php } */ ?>
                              
                         
                            </select> 
                    </td>
                    
                    
                  
</tr>

<tr>
	<td>File Status:</td>
    <td>
    	<input  type="radio" name="file_status" id="open" value="1" <?php if(isset($_SESSION['cAgencyEnding']['file_status'])){ if(  $_SESSION['cAgencyEnding']['file_status']==1 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="open">Open</label>
		<input  type="radio" name="file_status" id="closed" value="2" <?php if(isset($_SESSION['cAgencyEnding']['file_status'])){ if( $_SESSION['cAgencyEnding']['file_status']==2 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="closed">Closed</label>
    	<input  type="radio" name="file_status" id="closed_unpaid" value="5" <?php if(isset($_SESSION['cAgencyEnding']['file_status'])){ if( $_SESSION['cAgencyEnding']['file_status']==5 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="closed_unpaid">Closed & unpaid</label>
        <input  type="radio" name="file_status" id="running" value="6" <?php if(isset($_SESSION['cAgencyEnding']['file_status'])){ if( $_SESSION['cAgencyEnding']['file_status']==6 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="running">Running</label>
    	<input  type="radio" name="file_status" id="both"  <?php if(!isset($_SESSION['cAgencyEnding']['file_status']) || ($_SESSION['cAgencyEnding']['file_status']!=1 && $_SESSION['cAgencyEnding']['file_status']!=2 && $_SESSION['cAgencyEnding']['file_status']!=5 && $_SESSION['cAgencyEnding']['file_status']!=6)){  ?> checked="checked" <?php } ?> /> <label style="display:inline-block;top:3px;position:relative;" for="both">All</label>
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
 <?php if(isset($_SESSION['cAgencyEnding']['remainder_array']))
{
	
	$emi_array=$_SESSION['cAgencyEnding']['remainder_array'];
		
		
	 ?>    
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>     
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">File No</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Reg No</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Amount</label> 
        <input class="showCB" type="checkbox" id="5" checked="checked"   /><label class="showLabel" for="5">EMI</label> 
        <input class="showCB" type="checkbox" id="6" checked="checked"  /><label class="showLabel" for="6">Starting Date</label> 
         <input class="showCB" type="checkbox" id="7" checked="checked"  /><label class="showLabel" for="7">Name</label> 
        <input class="showCB" type="checkbox" id="8" checked="checked"  /><label class="showLabel" for="8">Address</label> 
        <input class="showCB" type="checkbox" id="9" checked="checked"  /><label class="showLabel" for="9">Contact</label> 
         <input class="showCB" type="checkbox" id="10" checked="checked"  /><label class="showLabel" for="10">Broker</label> 
    </div>
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        <th class="heading">No</th>
        <th class="heading file">File No</th>
        <th class="heading">Reg No</th>
        <th class="heading">Amount</th>
        <th class="heading">EMI</th>
        <th class="heading date">Approval Date</th>
        <th class="heading date">Agency Ending Date</th>
        <th class="heading">Name</th>
        
       
        <th class="heading">Broker</th>
        <th class="heading no_print btnCol"></th>   
        </tr>
    </thead>
    <tbody>
      
        <?php
		$total = 0;
		foreach($emi_array as $emi)
		{
			$from=$_SESSION['cAgencyEnding']['from'];
		    $to=$_SESSION['cAgencyEnding']['to'];
			
			if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
			$loan_id = getLoanIdFromFileId($emi['file_id']);
			$loan_ending_date = getAgencyEndingDateForLoan($loan_id);
			$loan_ending_date = date('Y-m-30',strtotime($loan_ending_date));
			$agency_participation_details=getLoanSchemeAgency($loan_id);
		
			if((isset($from) && validateForNull($from) && strtotime($loan_ending_date)>=strtotime($from) || !validateForNull($from)) && (isset($to) && validateForNull($to) && strtotime($loan_ending_date)<=strtotime($to) || !validateForNull($to)))
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
			
			echo $prefix.".".preg_replace('/[a-zA-Z]+/', '', $emi['file_no']); ?></span> <?php  echo  $emi['file_no']; ?>
            </td>
            
              <td><?php if($emi['reg_no']!=null && $emi['reg_no']!="") echo $emi['reg_no']; else echo "NA"; ?>
            </td>
             <td><?php echo $emi['loan_amount']; $total = $total + $emi['loan_amount'];  ?>
            </td>
             <td width="160px"><?php 	if($agency_participation_details!="error")
									{ foreach($agency_participation_details as $agency_participation_detail)
										echo $agency_participation_detail['agency_emi']." X ".$agency_participation_detail['agency_duration']."<br>";
										} ?>
            </td>
             <td><?php if($emi['loan_approval_date']!="1970-01-01" && $emi['loan_approval_date']!='0000-00-00') echo date('d/m/Y',strtotime($emi['loan_approval_date'])); else echo "NA"; ?>
            </td>
            <td><?php if($loan_ending_date!="1970-01-01" && $loan_ending_date!='0000-00-00') echo date('30/m/Y',strtotime($loan_ending_date)); else echo "NA"; ?>
            </td>
            <td><?php   echo $emi['customer']['customer_name']; ?></td>
            
                 <td><?php   echo $emi['broker_name']; ?></td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/index.php?view=details&id='.$emi['file_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
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