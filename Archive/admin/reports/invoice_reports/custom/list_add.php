<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Select Dates to generate Invoices between a particular time</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

<table class="insertTableStyling no_print">

<tr>

<td class="firstColumnStyling">
From Date : 
</td>

<td>
<input type="text" id="datepicker" size="12" autocomplete="off"  name="from_date" class="datepicker1 datepick" value="<?php if(isset($_POST) && validateForNull($_POST['from_date'])) { echo $_POST['from_date'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>

<tr>

<td>
To Date : 
</td>

<td>
<input type="text" id="datepicker1" size="12" autocomplete="off"  name="to_date" class="datepicker2 datepick" value="<?php if(isset($_POST) && validateForNull($_POST['to_date'])) { echo $_POST['to_date'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" value="Generate" class="btn btn-warning">
</td>
</tr>

</table>
</form>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">Invoice List</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentReport" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Invoice Date</th>
            <th class="heading">Customer Name</th>
           
            <th class="heading">Product</th>
            
             <th class="heading">Invoice Amount</th>
             <th class="heading no_print btnCol" ></th>
           
        </tr>
    </thead>
    <tbody>
        
        <?php
		if(!isset($_POST['from_date']) && !isset($_POST['to_date']))
		{
		
		}
		else if(isset($_POST) && !validateForNull($_POST['from_date']) && !validateForNull($_POST['to_date']))
		{
			
		}
		else if(isset($_POST) && (isset($_POST['from_date']) || isset($_POST['to_date'])))
		{
			$invoices=viewInvoices($_POST['from_date'],$_POST['to_date']);
		}
		$i=0;
		
		foreach($invoices as $invoice)
		{
			$in_customer_id = $invoice['in_customer_id'];
			$in_customer_name = $invoice['in_customer_name'];
			$invoice_date = $invoice['invoice_date'];
			 
			
			
			
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$i; ?>
            </td>
            
            
            <td><span  class="editLocationName"><?php echo date('d/m/Y',strtotime($invoice['invoice_date']))?></span>
            </td>
            
            
            
             <td><span  class="editLocationName"><?php echo $in_customer_name ?></span>
            </td>
            
            <td><span  class="editLocationName">
			<?php
			$productDetails = getInvoiceRelSubCatEnquiryFromInCustomerId($in_customer_id);
			
			foreach($productDetails as $pd)
			{
            $sub_cat_id=$pd['sub_cat_id'];
            $subCatNameArray = getsubCategoryById($sub_cat_id);
            $subCatName = $subCatNameArray['sub_cat_name'];
			echo $subCatName." <br/>";
			}
			?>
            </span>
            </td>
            
            <td><span  class="editLocationName">
			<?php
			$productDetails = getInvoiceRelSubCatEnquiryFromInCustomerId($in_customer_id);
		    
			$total=0;
			foreach($productDetails as $pd)
			{
			
            $invoice_price = $pd['invoice_price'];
            
			$total=$total+$invoice_price;
			
			}
			echo $total;
			?>
            </span>
            </td>
            
            
            
      <td class="no_print"> <a href="<?php echo WEB_ROOT."admin/customer/invoice/index.php?view=invoiceFinal&id=$in_customer_id "?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
    </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>