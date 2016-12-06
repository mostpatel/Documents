<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">General File Reports</h4>
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
<td>From Date (NOC Date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="start_date" id="start_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['clNonNocFileReport']['from'])) echo $_SESSION['clNonNocFileReport']['from']; ?>" />	
                 </td>
</tr>


<tr>
<td>Up To Date (NOC Date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="end_date" id="end_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['clNonNocFileReport']['to'])) echo $_SESSION['clNonNocFileReport']['to']; ?>"/>	
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
                             
                             <option value="<?php echo $super['city_id'] ?>" <?php if(isset($_SESSION['clNonNocFileReport']['city_id'])){ if( $super['city_id'] == $_SESSION['clNonNocFileReport']['city_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['city_name'] ?></option					>
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
						  if(isset($_SESSION['clNonNocFileReport']['city_id'])){
                            $areas = listAreasFromCityIdWithGroups($_SESSION['clNonNocFileReport']['city_id']);
                            foreach($areas as $area)
                              {
                             ?>
                             
                             <option value="<?php echo $area['area_id'] ?>" <?php if(isset($_SESSION['clNonNocFileReport']['area_id_array'])){ if(in_array($area['area_id'],$_SESSION['clNonNocFileReport']['area_id_array'])) { ?> selected="selected" <?php }} ?>><?php echo $area['area_name'] ?></option					>
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
                             
                             <option value="ag<?php echo $super['agency_id'] ?>" <?php if(isset($_SESSION['clNonNocFileReport']['agency_id'])){ if( "ag".$super['agency_id'] == $_SESSION['clNonNocFileReport']['agency_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['agency_name'] ?></option>
                             
                             <?php } ?>
                              
                             <?php 
							 
							 $companies = listOurCompanies();
                              foreach($companies as $com)
							
                              {
                             ?>
                             
                             <option value="oc<?php echo $com['our_company_id'] ?>" <?php if(isset($_SESSION['clNonNocFileReport']['agency_id'])){ if( "oc".$com['our_company_id'] == $_SESSION['clNonNocFileReport']['agency_id'] ) { ?> selected="selected" <?php }} ?> ><?php echo $com['our_company_name'] ?></option>
                             
                             <?php } ?>
                              
                         
                            </select> 
                    </td>
                    
                    
                  
</tr>
<tr>
	<td>NOC Issued:</td>
    <td>
    	<input  type="radio" name="noc_status" id="open" value="1" <?php if(isset($_SESSION['clNonNocFileReport']['noc_status'])){ if(  $_SESSION['clNonNocFileReport']['noc_status']==1 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="open">Yes</label>
		<input  type="radio" name="noc_status" id="closed" value="0" <?php if(isset($_SESSION['clNonNocFileReport']['noc_status'])){ if( $_SESSION['clNonNocFileReport']['noc_status']==0 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="closed">No</label>
    	
    	<input  type="radio" name="noc_status" value="3" id="both"  <?php if(!isset($_SESSION['clNonNocFileReport']['noc_status']) || ($_SESSION['clNonNocFileReport']['noc_status']!=1 && $_SESSION['clNonNocFileReport']['noc_status']!=0 && $_SESSION['clNonNocFileReport']['noc_status']!=5 && $_SESSION['clNonNocFileReport']['noc_status']!=6)){  ?> checked="checked" <?php } ?> /> <label style="display:inline-block;top:3px;position:relative;" for="both">All</label>
    </td>
</tr>

</tr>


    	
		<input  type="hidden" name="file_status" id="closed" value="2"  />
    	
<td></td>
				<td>
				 <input type="submit" value="Generate" class="btn btn-warning"/>	
                </td>
</tr>



</table>

</form>

  
<hr class="firstTableFinishing" />
 

	<div class="no_print">
 <?php if(isset($_SESSION['clNonNocFileReport']['file_array']))
{
	
	$emi_array=$_SESSION['clNonNocFileReport']['file_array'];
		
		
	 ?>    
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>     
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">File No</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">
        <?php if(isset($_SESSION['clNonNocFileReport']['noc_status'])){ if(  $_SESSION['clNonNocFileReport']['noc_status']==1 ) { ?>NOC Date<?php }}else { ?>
        Last Payment Date<?php } ?></label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Reg No</label> 
       
           <input class="showCB" type="checkbox" id="5" checked="checked"   /><label class="showLabel" for="5">Loan Amount</label> 
          
        <input class="showCB" type="checkbox" id="6" checked="checked"  /><label class="showLabel" for="6">Name</label> 
       <!--  <input class="showCB" type="checkbox" id="10" checked="checked"  /><label class="showLabel" for="10">Address</label> -->
        <input class="showCB" type="checkbox" id="7" checked="checked"  /><label class="showLabel" for="7">Address</label> 
        <input class="showCB" type="checkbox" id="8" checked="checked"  /><label class="showLabel" for="8">Contact</label> 
    </div>
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        <th class="heading">No</th>
        	<th class="heading file">File No</th>
            <th class="heading date"><?php if(isset($_SESSION['clNonNocFileReport']['noc_status'])){ if(  $_SESSION['clNonNocFileReport']['noc_status']==1 ) { ?>NOC Date<?php }}else { ?>
        Last Payment Date<?php } ?></th>
            <th class="heading">Reg No</th>
            <th class="heading">Loan Amount</th>
            <th class="heading">Name</th>
          <!--  <th width="10%" class="heading">Address</th> -->
            <th class="heading">Address</th>
            <th class="heading">Contact No</th>
            <th class="heading no_print btnCol"></th>
           
        </tr>
    </thead>
    <tbody>
      
        <?php
		$total = 0;
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
             <td>
			 <?php if(isset($_SESSION['clNonNocFileReport']['noc_status'])){ if(  $_SESSION['clNonNocFileReport']['noc_status']==1 ) { echo date('d/m/Y',strtotime($emi['noc_date'])); }}else { ?>
        
			 <?php if(validateForNull($emi['closure_date']) && validateForNull($emi['last_payment_date'])){ if(strtotime($emi['closure_date']<=$emi['last_payment_date'])) echo date('d/m/Y',strtotime($emi['last_payment_date'])); else  echo date('d/m/Y',strtotime($emi['closure_date']));} else if(validateForNull($emi['closure_date'])) echo date('d/m/Y',strtotime($emi['closure_date'])); else if(validateForNull($emi['last_payment_date'])) echo date('d/m/Y',strtotime($emi['last_payment_date'])); else echo "NA"; ?><?php } ?>
            </td>
              <td><?php if($emi['reg_no']!=null && $emi['reg_no']!="") echo $emi['reg_no']; else echo "NA"; ?>
            </td>
           
          
            <td><?php   echo $emi['loan_amount']; ?></td>
            
            <td><?php   echo $emi['customer']['customer_name']; ?></td>
             <td><?php   echo $emi['customer']['customer_address']; ?></td> 
           
            </td>
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
            <td class="no_print"><?php if(isset($_SESSION['clNonNocFileReport']['noc_status'])){ if(  $_SESSION['clNonNocFileReport']['noc_status']==1 ) { ?> <a href="<?php echo WEB_ROOT.'admin/customer/noc/index.php?view=noc&id='.$emi['file_id'].'&from=customerhome'; ?>"> <?php }}else { ?>
         <a href="<?php echo WEB_ROOT.'admin/customer/index.php?view=details&id='.$emi['file_id']; ?>"> <?php } ?><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
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