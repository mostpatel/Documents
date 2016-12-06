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

<h4 class="headingAlignment"> Generate Legal / Court Case </h4>
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
<input name="cheque_return_id" value="<?php if(isset($_GET['cheque_return_id']) && is_numeric($_GET['cheque_return_id']) && $_GET['cheque_return_id']>0) echo $_GET['cheque_return_id']; ?>" type="hidden" />

<table id="insertInsuranceTable" class="insertTableStyling no_print">
<tr>
<td>Case Date : </td>
				<td>
					<input placeholder="Click to select Date!" type="text" id="notice_date" name="notice_date" value="<?php echo date('d/m/Y',strtotime(getTodaysDate())); ?>" class="datepicker1 date"  onchange="onChangeDate(this.value,this)" /><span class="ValidationErrors contactNoError">Please select a date!</span>
                            </td>
</tr>

<tr>
<td class="firstColumnStyling">
Court : 
</td>

<td>
<select  name="court_id" >
	<option value="">--Please Select--</option>
	<?php $courts = listCourts();
	foreach($courts as $court)
	{
		?>
        <option value="<?php echo $court['court_id'] ?>"><?php echo $court['court']; ?></option>
        <?php
	}
	 ?>
</select>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Case Petetionar : 
</td>

<td>
<select  name="case_petetionar_id" >
	<option value="">--Please Select--</option>
	<?php $courts = listCasePetetionars();
	foreach($courts as $court)
	{
		?>
        <option value="<?php echo $court['case_petetionar_id'] ?>"><?php echo $court['case_petetionar']; ?></option>
        <?php
	}
	 ?>
</select>
</td>
</tr>


<tr>
<td class="firstColumnStyling">
Case Type : 
</td>

<td>
<select  name="case_type_id" >
	<option value="">--Please Select--</option>
	<?php $courts = listCaseTypes();
	foreach($courts as $court)
	{
		?>
        <option value="<?php echo $court['case_type_id'] ?>"><?php echo $court['case_type']; ?></option>
        <?php
	}
	 ?>
</select>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Advocate : 
</td>

<td>
<select  name="advocate_id" >
	<option value="">--Please Select--</option>
	<?php $courts = listAdvocates();
	foreach($courts as $court)
	{
		?>
        <option value="<?php echo $court['advocate_id'] ?>"><?php echo $court['advocate_name']; ?></option>
        <?php
	}
	 ?>
</select>
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
Case No : 
</td>

<td>
<input type="text" name="case_no" id="case_no" placeholder="Only Letters And Numbers!" value=""/>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Stage : 
</td>

<td>
<input type="text" name="stage" id="stage" placeholder="Current Stage of Case!" value=""/>
</td>
</tr>


<tr>
<td>Next Date : </td>
				<td>
					<input placeholder="Click to select Date!" type="text" id="next_date" name="next_date"  class="datepicker2 date"   /><span class="ValidationErrors contactNoError">Please select a date!</span>
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

<h4 class="headingAlignment">List of Cases</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Case Date</th>
            <th class="heading">Court</th>
            <th class="heading">Case Type</th>
            <th class="heading">Petetionar</th>
            <th class="heading">Advocate</th>
            <th class="heading">Type</th>
            <th class="heading">Case No</th>
              <th class="heading">Warrant</th>
            <th class="heading">Stage</th>
            <th class="heading">Next Date</th>
            <th class="heading">Remarks</th>
            <th class="heading no_print btnCol"></th>
            <th class="heading no_print btnCol"></th>
            <th class="heading no_print btnCol"></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$admins=listLegalNoticesForFileID($file_id);
		$no=0;
		foreach($admins as $admin)
		{
			
			if(checkForNumeric($admin['cheque_return_id']))
			{
			$cheque_return = getChequeReturnDetailsForId($admin['cheque_return_id']);	
				$cheque_return_customer=NULL;
				$cheque_return_guarantor=NULL;
			}
			else
			{
			$cheque_return=NULL;
			$cheque_return_customer=getLatestChequeReturnDateForFileId($file_id,0);
			$cheque_return_guarantor=getLatestChequeReturnDateForFileId($file_id,1);
			}
			
			
		 ?>
         <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php echo date('d/m/Y',strtotime($admin['notice_date'])); ?>
            </td>
             <td><?php echo $admin['court']; ?>
            </td>
             <td><?php echo $admin['case_type']; if(isset($cheque_return['cheque_amount'])) echo "<br>(".$cheque_return['cheque_amount']."Rs)"; else { 
			
			 if($admin['type']==0) 
			 { 
			 	if(isset($cheque_return_customer['cheque_amount'])) 
					{ 
						echo "(".$cheque_return_customer['cheque_amount']." Rs)"; 
						$total = $total + $cheque_return_customer['cheque_amount']; 
					} 
			} 
			else 
			{ 
				if(isset($cheque_return_guarantor['cheque_amount'])) 
				{ 
					echo "(".$cheque_return_guarantor['cheque_amount']." Rs)"; 
					$total = $total + $cheque_return_customer['cheque_amount'];
				}
			}} ?>
            </td>
              <td><?php echo $admin['case_petetionar']; ?>
            </td>
             <td><?php echo $admin['advocate_name']; ?>
            </td>
            <td><?php if($admin['type']==0) echo "Customer"; else echo "Guarantor"; ?></td>
             <td><?php echo $admin['case_no']; ?>
            </td>
             <td><?php  if($admin['warrant']==0) echo "NA"; else if($admin['warrant']==1) echo "NOT Received"; else if($admin['warrant']==2) echo "Received"; ?>
            </td>
            <td><?php echo $admin['stage']; ?>
            </td>
             <td><?php echo date('d/m/Y',strtotime($admin['next_date'])); ?>
            </td>
            <td><?php echo $admin['remarks']; ?>
            </td>
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=finish_notice&id='.$admin['legal_notice_id'] ?>"><button title="View this entry" class="btn btn-danger viewBtn">Finnish Case</button></a>
            </td> 
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=notice&id='.$admin['legal_notice_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&id='.$admin['legal_notice_id'] ?>"><button title="Edit this entry" class="btn viewBtn"><span class="view">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$admin['legal_notice_id'].'&id='.$admin['file_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
        </tr>
         <?php }?>
         </tbody>
    </table>
    </div>
     <table id="to_print" class="to_print adminContentTable"></table> 
     <hr class="firstTableFinishing" style="margin-top:50px;" />

<h4 class="headingAlignment">List of Finnished Cases</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Case Date</th>
            <th class="heading">Court</th>
            <th class="heading">Case Type</th>
            <th class="heading">Petetionar</th>
            <th class="heading">Advocate</th>
            <th class="heading">Type</th>
            <th class="heading">Case No</th>
            <th class="heading">Stage</th>
            <th class="heading">Finish Date</th>
            <th class="heading">Remarks</th>
           
          
        </tr>
    </thead>
    <tbody>
        
        <?php
		$admins=listFinishNoticesForFileID($file_id);
		$no=0;
		foreach($admins as $admin)
		{
		 ?>
         <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php echo date('d/m/Y',strtotime($admin['notice_date'])); ?>
            </td>
             <td><?php echo $admin['court']; ?>
            </td>
             <td><?php echo $admin['case_type']; ?>
            </td>
              <td><?php echo $admin['case_petetionar']; ?>
            </td>
             <td><?php echo $admin['advocate_name']; ?>
            </td>
            <td><?php if($admin['type']==0) echo "Customer"; else echo "Guarantor"; ?></td>
             <td><?php echo $admin['case_no']; ?>
            </td>
            <td><?php echo $admin['stage']; ?>
            </td>
             <td><?php echo date('d/m/Y',strtotime($admin['finish_date'])); ?>
            </td>
            <td><?php echo $admin['finish_remarks']; ?>
            </td>
            
        
        </tr>
         <?php }?>
         </tbody>
    </table>
    </div>
     <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>