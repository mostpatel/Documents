<?php
if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}

$delivery_challan_id=$_GET['id'];
$lr=getLRById($delivery_challan_id);
$admin_branches = getBranchesForAdminId($_SESSION['edmsAdminSession']['admin_id']);
$admin_branches_ids_array = array();
foreach($admin_branches as $branch)
{
	$admin_branches_ids_array[] = $branch['branch_id'];
}
if(!(in_array($lr['from_branch_ledger_id'],$admin_branches_ids_array) || in_array($lr['to_branch_ledger_id'],$admin_branches_ids_array)))
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}
if(is_array($lr) && $lr)
{
	$lr_Tax = getTaxForLr($delivery_challan_id);
	$tax_group_id = $lr_Tax[0]['tax_group_id'];
	$lr_products=getProductsByLRId($delivery_challan_id);
	$from_customer = getCustomerDetailsByCustomerId($lr['from_customer_id']);
	
	$to_customer = getCustomerDetailsByCustomerId($lr['to_customer_id']);
	$trip_memo = getTripMemoByLrId($delivery_challan_id);
}
else
{
	$_SESSION['ack']['msg']="Invalid File!";
	$_SESSION['ack']['type']=4; // 4 for error
	header("Location: ".WEB_ROOT."admin/search");
	
}

?>
<div class="addDetailsBtnStyling no_print"> <a href="index.php?view=lr&id=<?php echo $delivery_challan_id; ?>"><button class="btn btn-success">Print</button></a> </div>
<div class="insideCoreContent adminContentWrapper wrapper">
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
<div class="detailStyling">
<h4 class="headingAlignment"> Lorry Receipt Details </h4>


<table id="insertGuarantorTable" class="insertTableStyling detailStylingTable">
<tr>
<td>LR Date : </td>
				<td>
					<?php   echo date('d/m/Y',strtotime($lr['lr_date'])); ?>
                            </td>
</tr>

<tr>
<td width="180px;">LR No : </td>
<td><?php   echo $lr['lr_no']; ?> </td>
</tr>

<tr>
<td>From Branch : </td>
				<td>
					<?php   echo getLedgerNameFromLedgerId($lr['from_branch_ledger_id']); ?>
                            </td>
</tr>

<tr>
<td>To Branch : </td>
				<td>
					<?php   echo getLedgerNameFromLedgerId($lr['to_branch_ledger_id']); ?>
                            </td>
</tr>

<tr>
<td>From Customer : </td>
				<td>
					<?php  echo $from_customer['customer_name']; ?>
                            </td>
</tr>

<tr>
<td>To Customer : </td>
				<td>
					<?php echo  $to_customer['customer_name']; ?>
                            </td>
</tr>
<tr>
<td  width="180px;"> Total Weight : </td>
				<td>
					<?php echo $lr['weight']." Kg"; ?>
                            </td>
</tr>
<tr>
<td  width="180px;"> Builty Charge : </td>
				<td>
					<?php echo $lr['builty_charge']." Rs"; ?>
                            </td>
</tr>
<tr>
<td  width="180px;"> Tempo Fare : </td>
				<td>
					<?php echo $lr['tempo_fare']." Rs"; ?>
                            </td>
</tr>
<tr>
<td  width="180px;"> Rebooking charges : </td>
				<td>
					<?php echo $lr['rebooking_charges']." Rs"; ?>
                            </td>
</tr>
<tr>
<td  width="180px;"> Total Freight : </td>
				<td>
					<?php echo $lr['freight']." Rs"; ?>
                            </td>
</tr>

<tr>
<td class="firstColumnStyling">
Tax  : 
</td>

<td>
<?php if($tax_group_id==0) echo "Not Applicable"; else echo getTaxGroupNameByID($tax_group_id)." (".getTotalTaxPercentForTaxGroup($tax_group_id)."%)"; ?>
</td>
</tr>

<tr>
<td  width="180px;"> Total Tax : </td>
				<td>
					<?php echo $lr['total_tax']." Rs"; ?>
                            </td>
</tr>

<tr>
<td  width="180px;"> To Pay : </td>
				<td>
					<?php echo $lr['to_pay']." Rs"; ?>
                            </td>
</tr>

<tr>
<td  width="180px;"> Paid : </td>
				<td>
					<?php echo $lr['paid']." Rs"; ?>
                            </td>
</tr>

<tr>
<td  width="180px;"> To Be Billed : </td>
				<td>
					<?php echo $lr['to_be_billed']." Rs"; ?>
                            </td>
</tr>

<tr>
<td  width="180px;"> Tax Payer : </td>
				<td>
					<?php if($lr['tax_pay_type']==1) echo "Consignee";
					else if($lr['tax_pay_type']==2) echo "Consignor";
					else if($lr['tax_pay_type']==3) echo "Transporter";
					else echo "Default"; ?>
                            </td>
</tr>

<tr>
       <td>Remarks  :</td>
           
           
        <td>
            <?php echo $lr['remarks']; ?>
        </td>
 </tr>

<tr>
	<td></td>
  <td class="no_print">
            
          <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&id='.$delivery_challan_id; ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button></a>
          <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&id='.$delivery_challan_id; ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">X</span></button></a>
            </td>
</tr> 
</table>
</div>
<div class="detailStyling">
<h4 class="headingAlignment no_print">Product Details</h4>
<table id="pTable" class="insertTableStyling detailStylingTable">
<tr>
<th>Product Name</th>
<th>Qty</th>
<th>Unit</th>
</tr>
<?php
$i=-1;
 foreach($lr_products as $lr_product) {
$i++;	 
$product_id = $lr_product['product_id'];
$packing_unit_id = $lr_product['packing_unit_id'];

$qty_no = $lr_product['qty_no'];

$tax_group_id = $lr_product['tax_group_id'];

?>


<tr>

				<td align="left">
					<?php   echo getProductNameById($product_id); ?>
                            </td>


<td>
<?php echo getPackingUnitNameById($packing_unit_id); ?>
</td>

<td>
<?php echo $qty_no; ?>
</td>
</tr>



<?php } ?>
</table>

</div>

<div class="detailStyling">
<h4 class="headingAlignment no_print">Trip Memo Details</h4>
<table id="pTable" class="insertTableStyling detailStylingTable">
<tr>
<th>Memo No</th>
<th>Memo Date</th>
<th>Truck No</th>
</tr>



<tr>
<?php if(is_array($trip_memo)) { ?>
				<td align="left">
					<?php if(is_array($trip_memo))  echo $trip_memo['trip_memo_no'];  ?>
                            </td>


<td>
 <?php if(is_array($trip_memo)) echo date('d/m/Y',strtotime($trip_memo['trip_date'])); ?>
</td>

<td>
	<?php if(is_array($trip_memo)) echo getTruckNoById($trip_memo['truck_id']); ?>
</td>
<?php } ?>
</tr>




</table>

</div>

</div>
<div class="clearfix"></div>
