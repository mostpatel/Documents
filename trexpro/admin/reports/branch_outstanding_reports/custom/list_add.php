<div class="jvp"><?php if(isset($_SESSION['cToBCReport']['agency_id']) && $_SESSION['cToBCReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cToBCReport']['agency_id']);  } ?></div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Vehicle Outstanding Reports</h4>
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
<td>Up To Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="end_date" id="end_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cToBCReport']['to'])) echo $_SESSION['cToBCReport']['to']; ?>"/>	
                 </td>
</tr>



<tr>
<td>Branch Name : </td>
				<td>
					<select name="branch[]" class="broker selectpicker" multiple="multiple"  id="broker" >
                    	 <option value="-1" disabled="disabled">--Please Select--</option>
                          <?php
						 $brokers = listBranches();
						  
                          
                            foreach($brokers as $broker)
                              {
                             ?>
                             <option value="<?php echo $broker['ledger_id'] ?>" <?php if(isset($_SESSION['cToBCReport']['branch_id_array'])){ if(in_array($broker['ledger_id'],$_SESSION['cToBCReport']['branch_id_array'])) { ?> selected="selected" <?php }} ?>><?php echo $broker['ledger_name'] ?></option					>
                             <?php } 
						  
							 ?>
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
 <?php if(isset($_SESSION['cToBCReport']['emi_array']))
{
	
	$emi_array=$_SESSION['cToBCReport']['emi_array'];
		
		
	 ?>    
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>     
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Invoice No</label> 
       <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Memo no</label> 
  		<input class="showCB" type="checkbox" id="4" checked="checked"   /><label class="showLabel" for="4">From Branch</label> 
        
     <input class="showCB" type="checkbox" id="6" checked="checked"   /><label class="showLabel" for="6">Amount</label> 
        <input class="showCB" type="checkbox" id="7" checked="checked"   /><label class="showLabel" for="7">Received</label> 
        <input class="showCB" type="checkbox" id="8" checked="checked"  /><label class="showLabel" for="8">Balance</label> 
        
    </div>
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        	<th class="heading">No</th>
            <th class="heading file">Invoice No</th>
            <th class="heading">Memo No</th>
            <th class="heading numeric">Branch</th>
            
            <th class="heading numeric">Amount</th>
            <th class="heading numeric">Received</th>
             <th class="heading">Balance</th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
      
        <?php
	
		$total=0;
		$total_emi_amount=0;
		$customer_id_array = array();
		foreach($emi_array as $emi)
		{
			
			$total_vehcile_outstanding = 0;
			$total_customer_outstanding = 0;
			
			
		 ?>
         
         <tr class="resultRow">
         	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><div style="page-break-inside:avoid;"><?php echo ++$i; ?></div></td>
           
              <td><?php echo $emi['invoice_no']; ?>
            </td>
            <td><?php   echo $emi['trip_memo_nos']; ?></td>
            <td width="160px"><?php  echo $emi['branches']; ?>
          </td>
           
           <td width="160px"><?php  echo $emi['amount']; ?>
          </td>
             <td width="160px"><?php  if(is_numeric( $emi['paid_amount'])) echo  $emi['paid_amount']; else echo 0; ?>
          </td>
           <td><?php   if(is_numeric( $emi['paid_amount'])) echo $emi['amount'] - $emi['paid_amount']; else echo $emi['amount']; ?></td>
          
             <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/transportation/trip_invoice/index.php?view=details&id='.$emi['invoice_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
   
        </tr>
     
         <?php } }?>
            </tbody>
    </table>
    </div>
     <table class="reportFiltersTable">
    <tr>
    	<td> From : <?php if(isset($_SESSION['cToBCReport']['from']) && $_SESSION['cToBCReport']['from']!="") echo $_SESSION['cToBCReport']['from']; else echo "NA"; ?></td>
        <td> To : <?php if(isset($_SESSION['cToBCReport']['to']) && $_SESSION['cToBCReport']['to']!="") echo $_SESSION['cToBCReport']['to']; else echo "NA"; ?></td>
    	
        <td> City : <?php if(isset($_SESSION['cToBCReport']['city_id']) && $_SESSION['cToBCReport']['city_id']!="") {$city=getCityByID($_SESSION['cToBCReport']['city_id']); echo $city['city_name']; } else echo "NA"; ?></td>
       
        <td> Agency : <?php if(isset($_SESSION['cToBCReport']['agency_id']) && $_SESSION['cToBCReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cToBCReport']['agency_id']);  } else echo "NA"; ?></td>
        <td> File Status : <?php if(isset($_SESSION['cToBCReport']['file_status']) && $_SESSION['cToBCReport']['file_status']!="") { if($_SESSION['cToBCReport']['file_status']==1) echo "OPEN";else if($_SESSION['cToBCReport']['file_status']==2) echo "CLOSED";  } else echo "BOTH"; ?></td>
    </tr>
    </table> 
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
   <span class="Total">Total Customer Outstanding : <?php if(isset($total)) { if($total>=0) echo number_format($total)." DR"; else echo  number_format(-$total)." CR";  }?></span>
   <span class="Total" style="clear:both">Total Vehicle Outstanding : <?php if(isset($total_emi_amount)) { if($total_emi_amount>=0) echo number_format($total_emi_amount)." DR"; else echo  number_format(-$total_emi_amount)." CR";  }?></span>
<?php  ?>      
</div>
<div class="clearfix"></div>
