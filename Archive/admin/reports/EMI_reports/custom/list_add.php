<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">General EMI Reports</h4>
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
<td>From Date (EMI date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="start_date" id="start_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cEMIReport']['from'])) echo $_SESSION['cEMIReport']['from']; ?>" />	
                 </td>
</tr>


<tr>
<td>Up To Date (EMI date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="end_date" id="end_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cEMIReport']['to'])) echo $_SESSION['cEMIReport']['to']; ?>"/>	
                 </td>
</tr>

<tr>
<td>Bucket(>=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="win_gt" id="win_gt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cEMIReport']['win_gt'])) echo $_SESSION['cEMIReport']['win_gt']; ?>" />	
                 </td>
</tr>

<tr>
<td>Bucket(<=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="win_lt" id="win_lt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cEMIReport']['win_lt'])) echo $_SESSION['cEMIReport']['win_lt']; ?>" />	
                 </td>
</tr>

<tr>
<td>EMI(>=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="emi_gt" id="emi_gt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cEMIReport']['emi_gt'])) echo $_SESSION['cEMIReport']['emi_gt']; ?>" />	
                 </td>
</tr>

<tr>
<td>EMI(<=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="emi_lt" id="emi_lt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cEMIReport']['emi_lt'])) echo $_SESSION['cEMIReport']['emi_lt']; ?>" />	
                 </td>
</tr>

<tr>
<td>Balance(>=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="balance_gt" id="balance_gt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cEMIReport']['balance_gt'])) echo $_SESSION['cEMIReport']['balance_gt']; ?>" />	
                 </td>
</tr>

<tr>
<td>Balance(<=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="balance_lt" id="balance_lt" placeholder="Only Digits!" value="<?php if(isset($_SESSION['cEMIReport']['balance_lt'])) echo $_SESSION['cEMIReport']['balance_lt']; ?>" />	
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
                             
                             <option value="<?php echo $super['city_id'] ?>" <?php if(isset($_SESSION['cEMIReport']['city_id'])){ if( $super['city_id'] == $_SESSION['cEMIReport']['city_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['city_name'] ?></option					>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>
<td>Area : </td>
				<td>
					<input type="text" name="area" class="city_area" id="city_area1" placeholder="Only Letters" value="<?php if(isset($_SESSION['cEMIReport']['area_id'])) echo $_SESSION['cEMIReport']['area_id']; ?>" />
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
                             
                             <option value="ag<?php echo $super['agency_id'] ?>" <?php if(isset($_SESSION['cEMIReport']['agency_id'])){ if( "ag".$super['agency_id'] == $_SESSION['cEMIReport']['agency_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['agency_name'] ?></option>
                             
                             <?php } ?>
                              
                             <?php 
							 
							 $companies = listOurCompanies();
                              foreach($companies as $com)
							
                              {
                             ?>
                             
                             <option value="oc<?php echo $com['our_company_id'] ?>" <?php if(isset($_SESSION['cEMIReport']['agency_id'])){ if( "oc".$com['our_company_id'] == $_SESSION['cEMIReport']['agency_id'] ) { ?> selected="selected" <?php }} ?> ><?php echo $com['our_company_name'] ?></option>
                             
                             <?php } ?>
                              
                         
                            </select> 
                    </td>
                    
                    
                  
</tr>

<tr>
	<td>File Status :</td>
    <td>
    	<input  type="radio" name="file_status" id="open" value="1" <?php if(isset($_SESSION['cEMIReport']['file_status'])){ if(  $_SESSION['cEMIReport']['file_status']==1 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="open">Open</label>
		<input  type="radio" name="file_status" id="closed" value="2" <?php if(isset($_SESSION['cEMIReport']['file_status'])){ if( $_SESSION['cEMIReport']['file_status']==2 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="closed">Closed</label>
    	<input  type="radio" name="file_status" id="both"  <?php if(!isset($_SESSION['cEMIReport']['file_status']) || ($_SESSION['cEMIReport']['file_status']!=1 && $_SESSION['cEMIReport']['file_status']!=2)){  ?> checked="checked" <?php } ?> /> <label style="display:inline-block;top:3px;position:relative;" for="both">Both</label>
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
 <?php if(isset($_SESSION['cEMIReport']['emi_array']))
{
	
	$emi_array=$_SESSION['cEMIReport']['emi_array'];
		
		
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
            <th class="heading">File No</th>
            <th class="heading">Reg No</th>
            <th class="heading">Last EMI Date</th>
            <th class="heading">EMI</th>
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
		
		foreach($emi_array as $emi)
		{
		 ?>
         <tr class="resultRow">
         	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR" onchange="selectTR(this)" /></td>
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
            <td><span style="display:none;"><?php echo $emi['emi_date']; ?></span><?php echo date('d/m/Y',strtotime($emi['emi_date'])); ?>
            </td>
            <td><?php echo $emi['emi']; ?>
            </td>
            <td><?php   echo $emi['window'] ?>
            </td>
           <td>
           		<?php   echo $emi['balance']; ?>
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