<div class="jvp"><?php if(isset($_SESSION['cNoticeReport']['agency_id']) && $_SESSION['cNoticeReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cNoticeReport']['agency_id']);  } ?></div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">General Notice Reports</h4>
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
<td>From Date (Notice date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="start_date" id="start_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cNoticeReport']['from'])) echo $_SESSION['cNoticeReport']['from']; ?>" />	
                 </td>
</tr>


<tr>
<td>Up To Date (Notice date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="end_date" id="end_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cNoticeReport']['to'])) echo $_SESSION['cNoticeReport']['to']; ?>"/>	
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
                             
                             <option value="ag<?php echo $super['agency_id'] ?>" <?php if(isset($_SESSION['cNoticeReport']['agency_id'])){ if( "ag".$super['agency_id'] == $_SESSION['cNoticeReport']['agency_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['agency_name'] ?></option>
                             
                             <?php } ?>
                              
                             <?php 
							 
							 $companies = listOurCompanies();
                              foreach($companies as $com)
							
                              {
                             ?>
                             
                             <option value="oc<?php echo $com['our_company_id'] ?>" <?php if(isset($_SESSION['cNoticeReport']['agency_id'])){ if( "oc".$com['our_company_id'] == $_SESSION['cNoticeReport']['agency_id'] ) { ?> selected="selected" <?php }} ?> ><?php echo $com['our_company_name'] ?></option>
                             
                             <?php } ?>
                              
                         
                            </select> 
                    </td>
                    
                    
                  
</tr>

<tr>
	<td>Show Seized Vehicles :</td>
    <td>
    	<input  type="radio" name="seized" id="yes" value="1"  <?php if(isset($_SESSION['cNoticeReport']['seized'])){ if(  $_SESSION['cNoticeReport']['seized']==1 ) { ?> checked="checked" <?php }} ?> checked="checked" /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="yes">Yes</label>
		<input  type="radio" name="seized" id="no" value="0" <?php if(isset($_SESSION['cNoticeReport']['seized'])){ if( $_SESSION['cNoticeReport']['seized']==2 ) { ?> checked="checked" <?php }} ?> /> <label style="display:inline-block;top:3px;position:relative;" for="no">No</label>
       
    </td>
</tr>
<?php if(defined('NOTICE_STAGE') && NOTICE_STAGE==1) { ?>
<tr>
<td class="firstColumnStyling">
Notice Stage : 
</td>

<td>
<select name="notice_stage" class="selectpicker" multiple="multiple">
	<option  value="0" > General</option>
    <option value="1"  >Last Office Notice</option>
    <option value="2"   >Advocate</option>
</select>
</td>
</tr>

<?php } ?>

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
 <?php if(isset($_SESSION['cNoticeReport']['emi_array']))
{
	
	$emi_array=$_SESSION['cNoticeReport']['emi_array'];
		
		
	 ?>    
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>     
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">File No</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">File No</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Reg No</label> 
        <input class="showCB" type="checkbox" id="5" checked="checked"  /><label class="showLabel" for="5">Notice Date</label> 
     
      
        <input class="showCB" type="checkbox" id="6" checked="checked"  /><label class="showLabel" for="6">Name</label> 
         <input class="showCB" type="checkbox" id="7" checked="checked"  /><label class="showLabel" for="7">Address</label> 
        
    </div>
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        	<th class="heading">No</th>
            <th class="heading file">File No</th>
             <th class="heading file">Agreement No</th>
            <th class="heading">Reg No</th>
            <th class="heading date">Notice Date</th>
            <th class="heading">Name</th>
            <th width="10%" class="heading">Address</th>
           
            <th class="heading no_print btnCol"></th>
           
        </tr>
    </thead>
    <tbody>
      
        <?php
		$total_no_agencies=getTotalNoOfAgencies();
		$total=0;
		foreach($emi_array as $emi)
		{
			$reg_no = $returnArray[$j]['reg_no']=getRegNoFromFileID($emi['file_id']);
			$seieze_details=getVehicleSeizeDetailsByFileId($emi['file_id']);
			
			if(is_numeric($seieze_details['seize_id']))
			$seieze=true;
			else 
			$seieze=false;
			
			if(($_SESSION['cNoticeReport']['seized']==1 && $seieze) || !$seieze)
			{
		 ?>
         
         <tr class="resultRow">
         	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><div style="page-break-inside:avoid;"><?php echo ++$i; ?></div></td>
            <td><span style="display:none"><?php 
			if(is_numeric($emi['agency_id'])) {
				$prefix=$emi['agnecy_id'];}
			else if(is_numeric($emi['oc_id']))
			{$prefix=$total_no_agencies+$emi['oc_id']; }
			
			echo $prefix.".".preg_replace('/[^0-9]+/', '', $emi['file_no']); ?></span> <?php  echo  $emi['file_no']; if($seieze) echo "(S)"; ?>
            </td>
            <td><?php echo $emi['file_agreement_no']; ?></td>
              <td><?php if($reg_no!=null && $reg_no!="") echo $reg_no; else echo "NA";  ?> 
            </td>
            <td><?php  $last_emi_date=date('d/m/Y',strtotime($emi['notice_date'])); if($last_emi_date!='01/01/1970') echo $last_emi_date; else echo "NA"; ?>
            </td>
             
            <td><?php   echo $emi['customer_name']; ?></td>
             <td><?php   echo $emi['customer_address']; ?></td>
            
             <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/index.php?view=details&id='.$emi['file_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
   
        </tr>
     
         <?php }} }?>
            </tbody>
    </table>
    </div>
     <table class="reportFiltersTable">
    <tr>
    	<td> From : <?php if(isset($_SESSION['cNoticeReport']['from']) && $_SESSION['cNoticeReport']['from']!="") echo $_SESSION['cNoticeReport']['from']; else echo "NA"; ?></td>
        <td> To : <?php if(isset($_SESSION['cNoticeReport']['to']) && $_SESSION['cNoticeReport']['to']!="") echo $_SESSION['cNoticeReport']['to']; else echo "NA"; ?></td>
    	
       
        <td> Agency : <?php if(isset($_SESSION['cNoticeReport']['agency_id']) && $_SESSION['cNoticeReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cNoticeReport']['agency_id']);  } else echo "NA"; ?></td>
        
    </tr>
    </table> 
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
  
<?php  ?>      
</div>
<div class="clearfix"></div>
