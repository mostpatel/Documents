<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Custom Loan Reports</h4>
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
<td width="260px;">From Date (Loan Approval Date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="start_date" id="start_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cLoanReport']['from'])) echo $_SESSION['cLoanReport']['from']; ?>" />	
                 </td>
</tr>


<tr>
<td>Up To Date (Loan Approval Date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="end_date" id="end_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cLoanReport']['to'])) echo $_SESSION['cLoanReport']['to']; ?>"/>	
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
                             
                             <option value="<?php echo $super['city_id'] ?>" <?php if(isset($_SESSION['cLoanReport']['city_id'])){ if( $super['city_id'] == $_SESSION['cLoanReport']['city_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['city_name'] ?></option					>
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
						  if(isset($_SESSION['cLoanReport']['city_id'])){
                            $areas = listAreasFromCityIdWithGroups($_SESSION['cLoanReport']['city_id']);
                            foreach($areas as $area)
                              {
                             ?>
                             
                             <option value="<?php echo $area['area_id'] ?>" <?php if(isset($_SESSION['cLoanReport']['area_id_array'])){ if(in_array($area['area_id'],$_SESSION['cLoanReport']['area_id_array'])) { ?> selected="selected" <?php }} ?>><?php echo $area['area_name'] ?></option					>
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
                             
                             <option value="ag<?php echo $super['agency_id'] ?>" <?php if(isset($_SESSION['cLoanReport']['agency_id'])){ if( "ag".$super['agency_id'] == $_SESSION['cLoanReport']['agency_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['agency_name'] ?></option>
                             
                             <?php } ?>
                              
                             <?php 
							 
							 $companies = listOurCompanies();
                              foreach($companies as $com)
							
                              {
                             ?>
                             
                             <option value="oc<?php echo $com['our_company_id'] ?>" <?php if(isset($_SESSION['cLoanReport']['agency_id'])){ if( "oc".$com['our_company_id'] == $_SESSION['cLoanReport']['agency_id'] ) { ?> selected="selected" <?php }} ?> ><?php echo $com['our_company_name'] ?></option>
                             
                             <?php } ?>
                              
                         
                            </select> 
                    </td>
                    
                    
                  
</tr>

<tr>
	<td>File Status:</td>
    <td>
    	<input  type="radio" name="file_status" id="open" value="1" <?php if(isset($_SESSION['cLoanReport']['file_status'])){ if(  $_SESSION['cLoanReport']['file_status']==1 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="open">Open</label>
		<input  type="radio" name="file_status" id="closed" value="2" <?php if(isset($_SESSION['cLoanReport']['file_status'])){ if( $_SESSION['cLoanReport']['file_status']==2 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="closed">Closed</label>
    	<input  type="radio" name="file_status" id="closed_unpaid" value="5" <?php if(isset($_SESSION['cLoanReport']['file_status'])){ if( $_SESSION['cLoanReport']['file_status']==5 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="closed_unpaid">Closed & unpaid</label>
        <input  type="radio" name="file_status" id="running" value="6" <?php if(isset($_SESSION['cLoanReport']['file_status'])){ if( $_SESSION['cLoanReport']['file_status']==6 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="running">Running</label>
    	<input  type="radio" name="file_status" id="both"  <?php if(!isset($_SESSION['cLoanReport']['file_status']) || ($_SESSION['cLoanReport']['file_status']!=1 && $_SESSION['cLoanReport']['file_status']!=2 && $_SESSION['cLoanReport']['file_status']!=5 && $_SESSION['cLoanReport']['file_status']!=6)){  ?> checked="checked" <?php } ?> /> <label style="display:inline-block;top:3px;position:relative;" for="both">All</label>
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
 <?php if(isset($_SESSION['cLoanReport']['remainder_array']))
{
	
	$emi_array=$_SESSION['cLoanReport']['remainder_array'];
		
		
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
        	<th class="heading file">Broker Name</th>
             <th class="heading">No Of Loans</th>
              <th class="heading">Total Loan Amount</th>
               <th class="heading">File Charges</th>
                <th class="heading">Penalty</th>
           <?php  $fin_years = $emi_array[0]['income_array'];
		   foreach($fin_years as $key => $value)
		   {
			  ?>
              <th class="heading"><?php echo $key; ?></th>
              <?php 
			   
			  }
		    ?>
           
        </tr>
    </thead>
    <tbody>
      
        <?php
		$total = 0;
		$total_file_charges = 0;
		$total_penalty = 0;
		$total_vars = array();
		
	
		
		foreach($emi_array as $emi)
		{
		 ?>
         <tr class="resultRow">
         	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$i; ?></td>
            
             
            
              <td><?php echo $emi['broker_name']; ?>
            </td>
             <td><?php echo $emi['total_loans'];   ?>
            </td>
              <td><?php echo $emi['total_loan_amount'];  $total = $total + $emi['total_loan_amount'];  ?>
            </td>
             <td><?php echo $emi['total_file_charges'];  $total_file_charges = $total_file_charges + $emi['total_file_charges'];  ?>
            </td>
            <td><?php echo $emi['total_penalty'];  $total_penalty = $total_penalty + $emi['total_penalty'];  ?>
            </td>
             <?php  $fin_years = $emi['income_array'];
		   foreach($fin_years as $key => $value)
		   {
			  if(!isset($total_vars[$key]))
			  $total_vars[$key] = $value; 
			  else
			  $total_vars[$key] = $total_vars[$key] + $value; 
			  ?>
              <td><?php echo $value; ?></td>
              <?php 
			   
			  }
		    ?>
           
            
          
  
        </tr>
         <?php } }?>
         </tbody>
    </table>
    </div>
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
   
   <span class="Total" style="margin:10px;">Total Amount : <?php if(isset($total)) echo number_format($total); ?></span>
   <span class="Total" style="margin:10px;">Total File Charges : <?php if(isset($total_file_charges)) echo number_format($total_file_charges); ?></span>
   <span class="Total" style="margin:10px;">Total Penalty : <?php if(isset($total_penalty)) echo number_format($total_penalty); ?></span>
   <?php foreach($total_vars as $key => $value) {  ?>
    <span class="Total" style="margin:10px;">Total <?php echo $key; ?> : <?php if(isset($value)) echo number_format($value); ?></span>
<?php } ?>      
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