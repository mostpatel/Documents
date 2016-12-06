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
$guarantor=getGuarantorDetailsByFileId($file_id);
	$loan_id=getLoanIdFromFileId($file_id);
	$bucket_details = getBucketDetailsForLoan($loan_id);
	$bucket=getBucketForLoan($loan_id);
	$bucket_amount=0;
	
	$bucket_amount = getTotalBucketAmountForLoan($loan_id);
	
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

<h4 class="headingAlignment"> Generate Notice </h4>
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
<input name="bucket" value="<?php echo $bucket; ?>" type="hidden" />
<input name="bucket_amount" value="<?php echo $bucket_amount; ?>" type="hidden" />
<table id="insertInsuranceTable" class="insertTableStyling no_print">
<tr>
<td>Notice Date : </td>
				<td>
					<input placeholder="Click to select Date!" type="text" id="notice_date" name="notice_date" value="<?php echo date('d/m/Y',strtotime(getTodaysDate())); ?>" class="datepicker1 date"  onchange="onChangeDate(this.value,this)" /><span class="ValidationErrors contactNoError">Please select a date!</span>
                            </td>
</tr>
<tr>
<td class="firstColumnStyling">
Customer Name : 
</td>

<td>
<input type="text" name="customer_name" id="customer_name" placeholder="Only Letters!" value="<?php echo $customer['customer_name']; ?>"/>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Customer Address : 
</td>

<td>
 <textarea type="text"  name="customer_address" id="customer_address" ><?php echo $customer['customer_address']; if($customer['customer_pincode']!=0) echo " - ".$customer['customer_pincode']; 
                            $contactNumbers = $customer['contact_no'];
							
                            for($z=0;$z<count($contactNumbers);$z++)
                              {
								$c=$contactNumbers[$z];
								if($z==(count($contactNumbers)-1))
								echo "\nMOB : ".$c[0];  
								else
                      			echo "\nMOB : ".$c[0];				
                              }  ?></textarea>
</td>
</tr>
<tr>
<td class="firstColumnStyling">
Guarantor Name : 
</td>

<td>
<input type="text" name="guarantor_name" id="guarantor_name" placeholder="Only Letters!" value="<?php if(isset( $guarantor['guarantor_name'])) echo $guarantor['guarantor_name'];  ?>"/>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Guarantor Address : 
</td>

<td>
 <textarea type="text"  name="guarantor_address" id="guarantor_address" ><?php if(isset( $guarantor['guarantor_name'])) { echo $guarantor['guarantor_address']; if($guarantor['guarantor_pincode']!=0) echo " - ".$guarantor['guarantor_pincode']; 
                            $contactNumbers = $guarantor['contact_no'];
							
                            for($z=0;$z<count($contactNumbers);$z++)
                              {
								$c=$contactNumbers[$z];
								if($z==(count($contactNumbers)-1))
								echo "\nMOB : ".$c[0];  
								else
                      			echo "\nMOB : ".$c[0];				
                              } } ?></textarea>
</td>
</tr>
<tr>
<td class="firstColumnStyling">
Bucket : 
</td>

<td>
<input type="text" name="bucket" id="bucket" placeholder="Only Digits!" value="<?php echo $bucket; ?>"/>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Bucket Amount : 
</td>

<td>
<input type="text" name="bucket_amount" id="bucket_amount" placeholder="Only Digits!" value="<?php echo $bucket_amount; ?>"/>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Type : 
</td>

<td>
<select name="notice_type" id="notice_type"  >
<option value="0" selected="selected">Customer</option>
<?php if(is_array($guarantor)) { ?>
<option value="1" >Guarantor</option> 
<?php } ?>
</select>


</td>
</tr>




<tr>
<td width="250px;"></td>
<td>
<input type="submit" value="Issue Notice" class="btn btn-warning">
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
            <th class="heading">Notice Date</th>
            <th class="heading">Bucket</th>
            <th class="heading">Bucket Amount</th>
            <th class="heading no_print btnCol"></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$admins=listNoticesForFileID($file_id);
		$no=0;
		foreach($admins as $admin)
		{
		 ?>
         <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php echo date('d/m/Y',strtotime($admin['notice_date'])); ?>
            </td>
             <td><?php echo number_format($admin['bucket'],2); ?>
            </td>
            <td><?php echo number_format($admin['bucket_amount'],2); ?>
            </td>
            
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=notice&id='.$admin['notice_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$admin['notice_id'].'&id='.$admin['file_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
        </tr>
         <?php }?>
         </tbody>
    </table>
    </div>
     <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>