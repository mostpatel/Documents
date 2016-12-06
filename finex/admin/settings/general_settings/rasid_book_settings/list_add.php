<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment">Add Rasid Book</h4>
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
<form id="addAgencyForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post" onsubmit="return checkCheckBox()">
<table class="insertTableStyling no_print">
<tr>
<td width="220px">Company Name : </td>
				<td>
					<select id="agency_id" name="agency_id">
                        <option value="-1" >--Please Select--</option>
                        <?php
                            $agencies = listAgencies();
							$companies = listOurCompanies();
                            foreach($agencies as $super)
							
                              {
                             ?>
                             
                             <option value="ag<?php echo $super['agency_id'] ?>" <?php if(isset($_SESSION['cFileReport']['agency_id'])){ if( "ag".$super['agency_id'] == $_SESSION['cFileReport']['agency_id'] ) { ?> selected="selected" <?php }} ?>><?php echo $super['agency_name'] ?></option>
                             
                             <?php } ?>
                              
                             <?php 
							 
							 $companies = listOurCompanies();
                              foreach($companies as $com)
							
                              {
                             ?>
                             
                             <option value="oc<?php echo $com['our_company_id'] ?>" <?php if(isset($_SESSION['cFileReport']['agency_id'])){ if( "oc".$com['our_company_id'] == $_SESSION['cFileReport']['agency_id'] ) { ?> selected="selected" <?php }} ?> ><?php echo $com['our_company_name'] ?></option>
                             
                             <?php } ?>
                              
                         
                            </select> 
                    </td>
                    
                    
                  
</tr>

<tr>

<td class="firstColumnStyling">
Book No<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="book_no" id="book_no"/>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Rasid No From<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="rasid_no_from" id="rasid_no_from"/>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Rasid No To<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="rasid_no_to" id="rasid_no_to"/>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Given To<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="given_to" id="given_to"/>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Given Date<span class="requiredField">* </span> : 
</td>

<td>
<input class="datepicker2"  type="text" name="given_date" id="given_date"   />
</td>
</tr>


<tr>
<td></td>
<td>
<input type="submit" value="Add Rasid book" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>
</table>
</form>
	
    <hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Rasid Book</h4>
    <div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
   	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Company</th>
            <th class="heading">Book No</th>
            <th class="heading">Rasid No</th>
            <th class="heading">Given To</th>
            <th class="heading">Given Date</th>
                       <th class="heading">Received Date</th>
            <th class="heading no_print btnCol"></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$dealers=listRasidBooks();
		$no=0;
		if(count($dealers)>0)
		{
		foreach($dealers as $agencyDetails)
		{
		 ?>
         <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php if(validateForNull($agencyDetails['agency_name'])) echo $agencyDetails['agency_name']; else echo $agencyDetails['our_company_name'] ?></span>
            </td>
            <td><?php  echo $agencyDetails['book_no']; ?>
            </td>
           <td><?php  echo $agencyDetails['rasid_no_from']." - ".$agencyDetails['radid_no_to'];  ?>
            </td>
              <td><?php  echo $agencyDetails['given_to']; ?>
            </td>
              <td><?php  echo date('d/m/Y',strtotime($agencyDetails['given_date'])); ?>
            </td>
             <td><?php if($agencyDetails['received_date']!="1970-01-01")  echo date('d/m/Y',strtotime($agencyDetails['received_date'])); ?>
            </td>
           <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$agencyDetails['rasid_book_id'] ?>"><button title="Delete this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
            
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$agencyDetails['rasid_book_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php }}?>
         </tbody>
    </table>
    </div>
     <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>