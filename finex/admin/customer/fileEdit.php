<?php
if(!isset($_GET['id']))
header("Location: ".WEB_ROOT."admin/search");

$file_id=$_GET['id'];
if(!checkIfFileInAdminId($file_id,$_SESSION['adminSession']['admin_id']))
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}
$file=getFileDetailsByFileId($file_id);
if(is_array($file) && $file!="error")
{
	
}
else
{
	$_SESSION['ack']['msg']="Invalid File!";
	$_SESSION['ack']['type']=4; // 4 for error
	header("Location: ".WEB_ROOT."admin/search");
	
}

 $id =  $file['agency_id']; 
							 if($id!=null)
							 {
							        $agency_id=$id;
									$prefix=getAgencyPrefixFromAgencyId($agency_id);
									$ca_id=getCombinedAgencyIdForAgencyId($agency_id);
							 }
							 else
							 {
								 $oc_id=$file['oc_id'];
								 $prefix=getPrefixFromOCId($oc_id);
								 $ca_id=getCombinedAgencyIdForOCId($oc_id);
								 }

$file_number=$file['file_number'];	
$file_number=str_replace($prefix,"",$file_number);							 

?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit File Details</h4>

<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=editFile'; ?>" method="post" enctype="multipart/form-data" onsubmit="return submitOurCompany()">
<input name="lid" value="<?php echo $file_id ?>" type="hidden">
<table class="insertTableStyling no_print">

<tr>
<td width="220px">Agency Name<span class="requiredField">* </span> : </td>
				<td>
					<select id="agency_id" name="agency_id" onchange="getPrefixFromAgency(this.value)"  >
                        <option value="-1" >--Please Select--</option>
                        <?php
						
							if($ca_id && is_numeric($ca_id))
							{
							$agency_oc_array=getAgencyForCOmbinedAgency($ca_id);
                            
							if($agency_oc_array)
							{
                            foreach($agency_oc_array as $super)
                              {
							
                             ?>
                             
                             <option value="ag<?php echo $super['agency_id']; ?>" <?php if(isset($agency_id)){ if($super['agency_id']==$agency_id) { ?> selected <?php }} ?>><?php echo $super['agency_name'] ?></option>
                             
                             <?php }} ?>
                              
                             <?php 
							 
							 $companies = getOCForCOmbinedAgency($ca_id);
							 if($companies)
							 {
                              foreach($companies as $com)
                              {
							?>
                             
                             <option value="oc<?php echo $com['our_company_id'] ?>" <?php if(isset($oc_id)){ if($com['our_company_id']==$oc_id) { ?> selected <?php }} ?>><?php echo $com['our_company_name'] ?></option>
                             
                             <?php }}
							
							}else if($ca_id==false)
							{
							 $agencies = listAgencies();	
                            foreach($agencies as $super)
                              {
							 if(isset($agency_id)){ if($super['agency_id']==$agency_id) { 		  
                             ?>
                             
                             <option value="ag<?php echo $super['agency_id']; ?>" <?php if(isset($agency_id)){ if($super['agency_id']==$agency_id) { ?> selected <?php }} ?>><?php echo $super['agency_name'] ?></option>
                             
                             <?php }}} ?>
                              
                             <?php 
							 
							 $companies = listOurCompanies();
                              foreach($companies as $com)
                              {
							if(isset($oc_id)){ if($com['our_company_id']==$oc_id) {	  
                             ?>
                             
                             <option value="oc<?php echo $com['our_company_id'] ?>" <?php if(isset($oc_id)){ if($com['our_company_id']==$oc_id) { ?> selected <?php }} ?>><?php echo $com['our_company_name'] ?></option>
                             
                             <?php }}}} ?>
                              
                         
                            </select> 
                    </td>
                    
                    
                  
</tr>
<?php ?>
<tr>
<td>
File Agreement No<span class="requiredField">* </span> : 
</td>
<td>
<input type="text" value="<?php echo $file['file_agreement_no'] ?>" name="agreementNo" id="agreementNo" placeholder="Only Letters and Digits" autocomplete="off" onblur="checkAvailibilty(this,'agerror','ajax/agreementNo.php?fid=<?php echo $file_id; ?>&id=','agency_id')"/><span id="agerror" class="availError">Agreement Number already taken!</span>
</td>
</tr>

<tr>
<td>File Number<span class="requiredField">* </span> : </td>
				<td>
				<span id="agencyPrefix"><?php echo $prefix; ?></span> <input value="<?php echo $file_number; ?>" type="text" autocomplete="off"  name="fileNumber" id="fileNumber" placeholder="Only Letters and Digits" onblur="checkAvailibilty(this,'agerror','ajax/fileNumber.php?fid=<?php echo $file_id; ?>&id=','agency_id')"/><span id="agerror" class="availError">File Number already taken!</span>	
                 </td>
</tr>
<?php if(MEM_NO==1) {  ?>
<tr>
<td>Membership Number<span class="requiredField">* </span> : </td>
				<td>
				 <input value="<?php echo $file['mem_no']; ?>" type="text" autocomplete="off"  name="mem_number" id="mem_number" placeholder="Only Letters and Digits" />
                 </td>
</tr>
<?php } ?>
<?php if(HO_OPENING_DATE==1) {  ?>
<tr>
<td>HO Opening Date<span class="requiredField">* </span> : </td>
				<td>
				 <input value="<?php if($file['ho_opening_date']!="1970-01-01") echo date('d/m/Y',strtotime($file['ho_opening_date'])); ?>" type="text" autocomplete="off"  name="ho_opening_date" class="datepicker4 datepick" id="ho_opening_date" placeholder="dd/mm/yyyy" />
                 </td>
</tr>
<?php } ?>
<tr>
<td>Broker<span class="requiredField">* </span> : </td>
				<td>
					<select id="broker_id" name="broker_id" class="broker" >
                        <option value="-1" >--Please Select--</option>
                        <?php
                            $brokers = listBrokers();
                            foreach($brokers as $broker)
                              {
                             ?>
                             
                             <option value="<?php echo $broker['broker_id'] ?>" <?php if($file['broker_id']==$broker['broker_id']) { ?> selected="selected"<?php } ?> ><?php echo $broker['broker_name'] ?></option					>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>
<td></td>
<td>
<input id="disableSubmit" type="submit" value="Edit" class="btn btn-warning">
<a href="<?php echo $_SERVER['PHP_SELF'].'?view=details&id='.$file_id ?>"><input type="button" value="Back" class="btn btn-success" /></a>
</td>
</tr>


</table>

</form>

</div>
<div class="clearfix"></div>