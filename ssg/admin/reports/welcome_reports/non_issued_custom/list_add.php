<div class="jvp"><?php if(isset($_SESSION['cNonWelcomeReport']['agency_id']) && $_SESSION['cNonWelcomeReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cNonWelcomeReport']['agency_id']);  } ?></div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">General Welcome Letter Reports</h4>
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
<td>From Date (Loan Apporval date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="start_date" id="start_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cNonWelcomeReport']['from'])) echo $_SESSION['cNonWelcomeReport']['from']; ?>" />	
                 </td>
</tr>


<tr>
<td>Up To Date (Loan Approval date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="end_date" id="end_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cNonWelcomeReport']['to'])) echo $_SESSION['cNonWelcomeReport']['to']; ?>"/>	
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
                             
                             <option value="ag<?php echo $super['agency_id'] ?>" <?php if(isset($_SESSION['cNonWelcomeReport']['agency_id'])){ if( "ag".$super['agency_id'] == $_SESSION['cNonWelcomeReport']['agency_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['agency_name'] ?></option>
                             
                             <?php } ?>
                              
                             <?php 
							 
							 $companies = listOurCompanies();
                              foreach($companies as $com)
							
                              {
                             ?>
                             
                             <option value="oc<?php echo $com['our_company_id'] ?>" <?php if(isset($_SESSION['cNonWelcomeReport']['agency_id'])){ if( "oc".$com['our_company_id'] == $_SESSION['cNonWelcomeReport']['agency_id'] ) { ?> selected="selected" <?php }} ?> ><?php echo $com['our_company_name'] ?></option>
                             
                             <?php } ?>
                              
                         
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
 <?php if(isset($_SESSION['cNonWelcomeReport']['emi_array']))
{
	
	$emi_array=$_SESSION['cNonWelcomeReport']['emi_array'];
		
		
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
            <th class="heading date">Loan Approval Date</th>
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
            <td><?php  $last_emi_date=date('d/m/Y',strtotime($emi['loan_approval_date'])); if($last_emi_date!='01/01/1970') echo $last_emi_date; else echo "NA"; ?>
            </td>
        
            <td><?php   echo $emi['customer_name']; ?></td>
             <td><?php   echo $emi['customer_address']; ?></td>
            
             <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/welcome/index.php?id='.$emi['file_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">E</span></button></a>
            </td>
   
        </tr>
     
         <?php } }?>
            </tbody>
    </table>
    </div>
     <table class="reportFiltersTable">
    <tr>
    	<td> From : <?php if(isset($_SESSION['cNonWelcomeReport']['from']) && $_SESSION['cNonWelcomeReport']['from']!="") echo $_SESSION['cNonWelcomeReport']['from']; else echo "NA"; ?></td>
        <td> To : <?php if(isset($_SESSION['cNonWelcomeReport']['to']) && $_SESSION['cNonWelcomeReport']['to']!="") echo $_SESSION['cNonWelcomeReport']['to']; else echo "NA"; ?></td>
    	
       
        <td> Agency : <?php if(isset($_SESSION['cNonWelcomeReport']['agency_id']) && $_SESSION['cNonWelcomeReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cNonWelcomeReport']['agency_id']);  } else echo "NA"; ?></td>
        
    </tr>
    </table> 
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
  
<?php  ?>      
</div>
<div class="clearfix"></div>
