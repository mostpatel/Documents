<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment">Add a New Advocate</h4>
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

<td class="firstColumnStyling">
Name<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="name" id="name"/>
</td>
</tr>

<?php if(defined('SECONDARY_NAME') && SECONDARY_NAME==1) { ?>
<tr>

<td width="230px" class="firstColumnStyling">
Secondary Customer's Name<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="secondary_name" id="transliterateTextarea" class="person_name" placeholder="Only Letters"/>
</td>
</tr>
<?php } ?>



<tr>
<td>
Address : 
</td>

<td>
<textarea name="address" cols="5" rows="6" id="address"></textarea>
</td>
</tr>

<tr>
<td> Contact Number : </td>
<td> <input type="text" name="contactNo"/> </tr>
</tr>

<tr>
<td> Contact Number (2) : </td>
<td> <input type="text" name="contactNo2"/> </tr>
</tr>


<tr>
<td></td>
<td>
<input type="submit" value="Add Dealer" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>
</table>
</form>
	
    <hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Advocates</h4>
    <div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
   	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Name</th>
            <th class="heading">Address</th>
            <th class="heading">Contact Number</th>
              <th class="heading">Contact Number 2</th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$dealers=listAdvocates();
		$no=0;
		foreach($dealers as $agencyDetails)
		{
		 ?>
         <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php echo $agencyDetails['advocate_name']; ?>
            </td>
            <td><?php if($agencyDetails['advocate_address']=="" || $agencyDetails['advocate_address']==null) echo "NA"; else echo $agencyDetails['advocate_address']; ?>
            </td>
             <td><?php echo $agencyDetails['contact_no']; ?>
            </td>
             <td><?php echo $agencyDetails['contact_no2']; ?>
            </td>
            
            
        
            
           
          
            
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=details&lid='.$agencyDetails['advocate_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$agencyDetails['advocate_id'] ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$agencyDetails['advocate_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
    </div>
     <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>