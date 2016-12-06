<div class="jvp"><?php if(isset($_SESSION['cKamalReport']['agency_id']) && $_SESSION['cKamalReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cKamalReport']['agency_id']);  } ?></div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Kamal Bhai Reports</h4>
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
<input type="hidden" id="agency_id" name="agency_id" value="1"/> 
<table class="insertTableStyling no_print">

<tr>
<td>From Date (Emi Date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="start_date" id="start_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cKamalReport']['from'])) echo $_SESSION['cKamalReport']['from']; ?>" />	
                 </td>
</tr>


<tr>
<td>Up To Date (Emi Date) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="end_date" id="end_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cKamalReport']['to'])) echo $_SESSION['cKamalReport']['to']; ?>"/>	
                 </td>
</tr>

<tr>
<td>Payment Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="payment_date" id="payment_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cKamalReport']['payment_date'])) echo $_SESSION['cKamalReport']['payment_date']; ?>"/>	
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

	
     <?php if(isset($_SESSION['cKamalReport']['emi_array']))
{
	
	$emi_array=$_SESSION['cKamalReport']['emi_array'];
		
		
	 ?>
     <div class="no_print">
     <div class="printBtnDiv no_print"> <a href="index.php?action=add&from=<?php echo $_SESSION['cKamalReport']['from'];
	  ?>&to=<?php echo $_SESSION['cKamalReport']['to'] ?>&agency_id=<?php echo $_SESSION['cKamalReport']['agency_id']; ?>&payment_date=<?php echo $_SESSION['cKamalReport']['payment_date']; ?>"><button title="Add Payments For Valid Files" class="btn btn-success">Add Payments</button></a><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
     <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">File No</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Name</label> 
         <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Contact No</label> 
        <input class="showCB" type="checkbox" id="5" checked="checked"  /><label class="showLabel" for="5">Reg No</label> 
        <input class="showCB" type="checkbox" id="6" checked="checked"   /><label class="showLabel" for="6">EMI Date</label> 
       <input class="showCB" type="checkbox" id="7" checked="checked"   /><label class="showLabel" for="7">Amount</label> 
    </div>    
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        	<th class="heading">No</th>
            <th class="heading file">File No</th>
             <th class="heading file">Name</th>
            <th class="heading">Contact No</th>
             <th class="heading">Reg No</th>
            <th class="heading date">EMI Date</th>
            <th class="heading">Amount</th>
            <th class="heading no_print btnCol"></th>
           
        </tr>
    </thead>
    <tbody>
       
        <?php
		$total=0;
		$total_agencies=getTotalNoOfAgencies();
		foreach($emi_array as $emi)
		{
			$file_id=getFileIdFromLoanId($emi['loan_id']);
			$customer=getCustomerDetailsByFileId($file_id);
			$total=$total+$emi['emi_amount'];
			
			$balance=getBalanceForEmi($emi['loan_emi_id']);
			
		 ?>
         <tr class="resultRow">
         <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$i; ?></td>
          
            <td><span style="display:none"><?php 
			if(is_numeric($emi['agency_id'])) {
				$prefix=$emi['agency_id'];}
			else if(is_numeric($emi['oc_id']))
			{$prefix=$total_agencies+$emi['oc_id'];}
			echo $prefix.".".preg_replace('/[^0-9]+/', '', $emi['file_number']); ?></span> <?php  echo  $emi['file_number']; ?>
            </td>
             <td><?php   echo $customer['customer_name']; ?></td>
             <td><?php   $contactArray = $customer['contact_no']; 
			 			
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
             <td><?php echo getRegNoFromFileID($file_id); ?></td>   
            <td><?php echo date('d/m/Y',strtotime($emi['actual_emi_date'])); ?>
            </td>
            <td><?php   echo $emi['emi_amount'] ?>
            </td>
             <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/index.php?view=details&id='.$emi['file_id']; ?>"><button title="View this entry" class="btn <?php if($balance==0) echo "btn-success"; else if($emi['emi_amount']!=-$balance) echo "btn-danger"; else echo "btn-warning"; ?>"><span class=""><?php if($balance==0) echo "Print"; else if($emi['emi_amount']!=-$balance) echo "error"; else echo "Add Payment"; ?></span></button></a>
            </td>
           
            
          
  
        </tr>
         <?php } ?>
         </tbody>
    </table>
    </div>
     <table class="reportFiltersTable">
    <tr>
    	<td> From : <?php if(isset($_SESSION['cKamalReport']['from']) && $_SESSION['cKamalReport']['from']!="") echo $_SESSION['cKamalReport']['from']; else echo "NA"; ?></td>
        <td> To : <?php if(isset($_SESSION['cKamalReport']['to']) && $_SESSION['cKamalReport']['to']!="") echo $_SESSION['cKamalReport']['to']; else echo "NA"; ?></td>
    </tr>
    </table> 
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
   <span class="Total">Total Amount : <?php if(isset($total)) echo number_format($total); ?></span>
  
<?php } ?>      
</div>

<div class="clearfix"></div>
