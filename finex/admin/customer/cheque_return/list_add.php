<?php if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}
$file_id=$_GET['id'];
$file=getFileDetailsByFileId($file_id);
if(is_array($file) && $file!="error")
{
	$customer=getCustomerDetailsByFileId($file_id);
	$customer_id=$customer['customer_id'];
	$loan_id=getLoanIdFromFileId($file_id);
	
	
}
else
{
	$_SESSION['ack']['msg']="Invalid File!";
	$_SESSION['ack']['type']=4; // 4 for error
	header("Location: ".WEB_ROOT."admin/search");
	exit;
}

 ?>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment"> Add Cheque Return Details </h4>
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
<form  id="addNoticeForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post">
<input name="file_id" value="<?php echo $file_id; ?>" type="hidden" />
<table id="insertInsuranceTable" class="insertTableStyling no_print">
<tr>
<td width="220px">Bank Name<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="bank_name" id="bank" placeholder="Only Letters!" autocomplete="off"  />
                            </td>
</tr>
<tr>
<td width="220px">Branch Name<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="branch_name" id="branch" placeholder="Only Letters!" autocomplete="off"  />
                            </td>
</tr>
<tr>
<td width="220px">Cheque Amount<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="cheque_amount" id="cheque_amount" placeholder="Only Digits!" />
                            </td>
</tr>
<tr>
<td width="220px">Cheque No<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="cheque_no" id="cheque_no" placeholder="Only Digits!" />
                            </td>
</tr>
<tr>
<td width="220px">Cheque Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="cheque_date" id="cheque_date" class="datepicker3" placeholder="click to select date!"  /><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>
<tr>
<td >
Slip No : 
</td>

<td>
<input type="text" name="slip_no" id="" placeholder="Only Numbers!" value=""/>
</td>
</tr>

<tr>
<td >
Type : 
</td>

<td>
<select  name="type" id="type" >
 <?php if(!isset($_GET['type']) || $_GET['type']==0) { ?>	<option value="0"  selected="selected"  >Customer</option> <?php } ?>
 <?php if(!isset($_GET['type']) || $_GET['type']==1) { ?>  <option value="1"  >Guarantor</option> <?php } ?>
</select>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Remarks : 
</td>

<td>
 <textarea type="text"  name="remarks" id="remarks" ></textarea>
</td>
</tr>

<tr>
<td width="250px;"></td>
<td>
<input type="submit" value="Issue Cheque Return" class="btn btn-warning">
<?php if(isset($_GET['from']) && $_GET['from']=='customerhome') { ?>
<a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=details&id=<?php echo $file_id; ?>"><input type="button" class="btn btn-success" value="back"></a>
<?php } else { ?>
<a href="<?php echo WEB_ROOT; ?>admin/customer/EMI/index.php?view=details&id=<?php echo $file_id; ?>"><input type="button" class="btn btn-success" value="back"></a><?php } ?>
</td>
</tr>

</table>

</form>
<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Notices</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Amount</th>
            <th class="heading">Date</th>
            <th class="heading">Cheque No</th>
            <th class="heading">Bank Name</th>
            <th class="heading">Branch Name</th>
            <th class="heading">Slip No</th> 
             <th class="heading">Received</th>
            <th class="heading">Remarks</th>
            <th class="heading">Type</th>
            <th class="heading">Added By</th>
            <th class="heading">Date Added</th>
            <th class="heading no_print btnCol"></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$admins=ListChequeReturnsForFileId($file_id);
		$no=0;
		
		foreach($admins as $admin)
		{
		 ?>
         <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php echo $admin['cheque_amount']; ?>
            </td>
            <td><?php echo date('d/m/Y',strtotime($admin['cheque_date'])); ?>
            </td>
            <td><?php echo $admin['cheque_no']; ?>
            </td>
             <td><?php echo $admin['bank_name']; ?>
            </td>
             <td><?php echo $admin['branch_name']; ?>
            </td>
             <td><?php echo $admin['slip_no']; ?>
            </td>
            <td><?php    if($admin['received']==0) echo "Status Unknown"; else if($admin['received']==1) echo "Received"; else if($admin['received']==2) echo "Not Received"; else if($admin['received']==3) echo "Resent"; ?></td>
            <td><?php echo $admin['remarks']; ?>
            </td>
            <td><?php if($admin['type']==0) echo "Customer"; else echo "Guarantor"; ?></td>
             <td><?php echo getAdminUserNameByID($admin['last_updated_by']); ?>
            </td>
             <td><?php echo date('d/m/Y',strtotime($admin['date_added'])); ?>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$admin['cheque_return_id'].'&id='.$admin['file_id'] ?>"><button title="Delete this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$admin['cheque_return_id'].'&id='.$admin['file_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
        </tr>
         <?php }?>
         </tbody>
    </table>
    </div>
     <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>
<script>
 $( "#bank" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/bank_name.php',
                { term: request.term }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#bank" ).val(ui.item.label);
			return false;
		}
    });
	 $( "#branch" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/branch_name.php',
                { term: request.term, bank_name:$('#bank').val() }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#branch" ).val(ui.item.label);
			return false;
		}
    });	
	
</script>