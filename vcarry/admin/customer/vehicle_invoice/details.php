<?php
if(!isset($_GET['id']))
header("Location: ".WEB_ROOT."admin/search");

$delivery_challan_id=$_GET['id'];
$delivery_challan=getDeliveryChallanById($delivery_challan_id);
if(is_array($delivery_challan) && $delivery_challan!="error")
{
	$customer=getCustomerDetailsByCustomerId($delivery_challan['customer_id']);
	$vehicle=getVehicleById($delivery_challan['vehicle_id']);
	$vehicle_model = getVehicleModelById($vehicle['model_id']);
	$insurance = getInsuranceForDeliveryChallanID($delivery_challan_id);
	$invoice = getVehicleInvoiceByVehicleId($vehicle['vehicle_id']);
	$sales = getSaleById($invoice['sales_id']);
	$tax_group = getTaxForVehicleSaleId($invoice['sales_id']);
	$our_company = getOurCompanyByID($_SESSION['edmsAdminSession']['oc_id']);
	$sales_jvs = getSalesJvForVehicleId($vehicle['vehicle_id']);
	$all_sales_jvs_ledger_id = listSalesJvLedgerIds();
	
	$loan_jv = getLoanJVForVehicleId($vehicle['vehicle_id']);
	
	$exchange_vehicle_id = getExchangeVehicleIdForVehicleInvoiceId($invoice['vehicle_invoice_id']);
	if(is_numeric($exchange_vehicle_id))
	{
	$exchange_vehicle =getVehicleById($exchange_vehicle_id);
	$exchange_vehicle_model = getVehicleModelById($exchange_vehicle['model_id']);
	
	}
}
else
{
	$_SESSION['ack']['msg']="Invalid File!";
	$_SESSION['ack']['type']=4; // 4 for error
	header("Location: ".WEB_ROOT."admin/search");
	
}

?>
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
<div class="detailStyling no_print">
<h4 class="headingAlignment"> Vehicle Invoice Details </h4>


<table id="insertGuarantorTable" class="insertTableStyling detailStylingTable">

<tr>
<td width="190px;">Invoice No : </td>
<td><?php   echo $invoice['invoice_no']; ?> </td>
</tr>

<tr>
<td>Invoice Date : </td>
				<td>
					<?php   echo date('d/m/Y',strtotime($invoice['invoice_date'])); ?>
                            </td>
</tr>

<tr>
<td width="180px;">Invoice Type : </td>
<td><?php if($sales['retail_tax']==0)  echo "Retail"; else if($sales['retail_tax']==1) echo "Tax"; ?> </td>
</tr>


<tr>
<td width="180px;">Basic Price : </td>
<td><?php   echo number_format($sales['amount'])." /-"; ?> </td>
</tr>

<?php $nett_amount = $sales['amount']; foreach($tax_group as $tg) { $nett_amount = $nett_amount + $tg['tax_amount']; ?>
<tr>
<td width="180px;"><?php echo $tg['tax_name']; ?> : </td>
<td><?php   echo number_format($tg['tax_amount'])." /-"; ?> </td>
</tr>
<?php } ?>

<tr>
<td width="180px;">Total Amount : </td>
<td><?php   echo number_format($nett_amount)." /-"; ?> </td>
</tr>

<tr>
<td width="180px;">Delivery Challan No : </td>
<td><?php   echo $delivery_challan['challan_no']; ?> </td>
</tr>

<tr>
<td>Delivery Date : </td>
				<td>
					<?php   echo date('d/m/Y',strtotime($delivery_challan['delivery_date'])); ?>
                            </td>
</tr>

<tr>
<td class="firstColumnStyling">
Sales Agent / Salesman  : 
</td>

<td>
<?php echo getLedgerNameFromLedgerId($delivery_challan["salesman_ledger_id"]); ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
H.P.A / H.V.P  : 
</td>

<td>
<?php  if(validateForNull($delivery_challan["financer_ledger_id"]) && is_numeric($delivery_challan["financer_ledger_id"])) echo getLedgerNameFromLedgerId($delivery_challan["financer_ledger_id"]);  else echo "NA"; ?>
</td>
</tr>
<?php if(is_array($sales_jvs) && count($sales_jvs)>0) { 

foreach($sales_jvs as $sales_jv)
{
	$from_ledger_id = $sales_jv['from_ledger_id'];
	$from_customer_id = $sales_jv['from_customer_id'];
	$to_ledger_id = $sales_jv['to_ledger_id'];
	$from_customer_id = $sales_jv['to_customer_id'];
	if(is_numeric($from_ledger_id))
	$purchase_jv_ledger_id = $from_ledger_id;
	else
	$purchase_jv_ledger_id = $to_ledger_id;
	
?>	
<tr>
	<td><?php echo getLedgerNameFromLedgerId($purchase_jv_ledger_id); ?> :</td>
    <td>Rs. <?php echo number_format($sales_jv['amount'],2); ?></td>
</tr>
<?php	
}}
?>
<?php   if($loan_jv){ ?>
<tr>
<td>Loan Jv Amount :</td>
<td><?php if($loan_jv) echo "Rs. ".number_format($loan_jv['amount'],2); ?></td>
</tr>

<tr>
<td class="firstColumnStyling">
Loan Jv (Debit) :
</td>

<td>
<?php if($loan_jv)  echo getLedgerNameFromLedgerId($loan_jv['to_ledger_id']); ?>
</td>
</tr>
<?php } ?>
</table>



<h4 class="headingAlignment" > Customer Details </h4>
<table class="insertTableStyling detailStylingTable">

<tr>
<td  width="180px;">Customer Name : </td>
				<td>
					<?php echo $customer['customer_name']; ?>
                            </td>
</tr>

<tr>
       <td>Customer Address :</td>
           
           
        <td>
            <?php echo $customer['customer_address']; ?>
        </td>
 </tr>

</table>

<?php if($insurance!="error") { ?><h4 class="headingAlignment" > Insurance Details </h4><?php } ?>
<table class="insertTableStyling detailStylingTable">
<?php if($insurance!="error") { ?>
<tr>
<td class="firstColumnStyling" width="180px;">
Insurance Company : 
</td>

<td  >
<?php echo $insurance["insurance_company_name"]; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Insurance Date : 
</td>

<td>
<?php echo date('d/m/Y',strtotime($insurance["insurance_issue_date"])); ?>
</td>
</tr>
<?php } ?>
<tr>
	<td></td>
  <td class="no_print">
            
          <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&id='.$delivery_challan_id.'&state='.$vehicle['vehicle_id'] ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button></a>
             <a href="<?php echo WEB_ROOT.'admin/customer/index.php?view=details&id='.$customer['customer_id'] ?>"><button title="Back" class="btn btn-success">Back</button></a>
            </td>
</tr> 

</table>


</div>

<div class="detailStyling no_print">

<h4 class="headingAlignment"> Vehicle Details </h4>
<table class="insertTableStyling detailStylingTable">
 
<tr>
<td  width="180px;">Vehicle Model : </td>
				<td>
					<?php echo getModelNameById($vehicle['model_id']); ?>	
                </td>
</tr> 

<tr>
<td  width="180px;">Vehicle Color : </td>
				<td>
					<?php if(is_numeric($vehicle['vehicle_color_id'])) echo getVehicleColorNameById($vehicle['vehicle_color_id']); else echo "NA"; ?>	
                </td>
</tr> 
 
 <tr>
<td>Vehicle Mfg Year : </td>
				<td>
					<?php echo $vehicle['vehicle_model']; ?>
                            </td>
</tr>



<tr>
<td>Vehicle Type : </td>
				<td>
					<?php echo getVehicleTypeNameById($vehicle_model['vehicle_type_id']); ?>	
                </td>
</tr>
 


<tr>
<td class="firstColumnStyling">
Engine Number : 
</td>

<td>
<?php echo $vehicle["vehicle_engine_no"]; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Chasis Number : 
</td>

<td>
<?php echo $vehicle["vehicle_chasis_no"]; ?>
</td>
</tr>


<tr>
<td class="firstColumnStyling">
CNG Cylinder Number : 
</td>

<td>
<?php echo $vehicle["cng_cylinder_no"]; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
CNG Kit Number : 
</td>

<td>
<?php echo $vehicle["cng_kit_no"]; ?>
</td>
</tr>




<tr>
<td class="firstColumnStyling">
Battery Make  : 
</td>

<td>
<?php if(validateForNull($vehicle['battery_make_id']) && is_numeric($vehicle['battery_make_id'])) echo getBatteryMakeNameById($vehicle['battery_make_id']);  else echo "NA";  ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Battery Number : 
</td>

<td>
<?php echo $vehicle["battery_no"]; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Key Number : 
</td>

<td>
<?php echo $vehicle["key_no"]; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Service Number : 
</td>

<td>
<?php echo $vehicle["service_book"]; ?>
</td>
</tr>


</table>
</div>

<?php if(is_numeric($exchange_vehicle_id)) { ?>
<div class="detailStyling no_print">

<h4 class="headingAlignment">Exchange Vehicle Details </h4>
<table class="insertTableStyling detailStylingTable">
 
<tr>
<td  width="180px;">Vehicle Model : </td>
				<td>
					<?php echo getModelNameById($exchange_vehicle['model_id']); ?>	
                </td>
</tr> 

<tr>
<td  width="180px;">Vehicle Color : </td>
				<td>
					<?php if(is_numeric($exchange_vehicle['exchange_vehicle_color_id'])) echo getVehicleColorNameById($exchange_vehicle['vehicle_color_id']); else echo "NA"; ?>	
                </td>
</tr> 
 
 <tr>
<td>Vehicle Mfg Year : </td>
				<td>
					<?php echo $exchange_vehicle['vehicle_model']; ?>
                            </td>
</tr>



<tr>
<td>Vehicle Type : </td>
				<td>
					<?php echo getVehicleTypeNameById($exchange_vehicle_model['vehicle_type_id']); ?>	
                </td>
</tr>
 
<tr>
<td class="firstColumnStyling">
Reg Number : 
</td>

<td>
<?php echo $exchange_vehicle["vehicle_reg_no"]; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Engine Number : 
</td>

<td>
<?php echo $exchange_vehicle["vehicle_engine_no"]; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Chasis Number : 
</td>

<td>
<?php echo $exchange_vehicle["vehicle_chasis_no"]; ?>
</td>
</tr>


<tr>
<td class="firstColumnStyling">
CNG Cylinder Number : 
</td>

<td>
<?php echo $exchange_vehicle["cng_cylinder_no"]; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
CNG Kit Number : 
</td>

<td>
<?php echo $exchange_vehicle["cng_kit_no"]; ?>
</td>
</tr>




<tr>
<td class="firstColumnStyling">
Battery Make  : 
</td>

<td>
<?php if(validateForNull($exchange_vehicle['battery_make_id']) && is_numeric($exchange_vehicle['battery_make_id'])) echo getBatteryMakeNameById($exchange_vehicle['battery_make_id']);  else echo "NA";  ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Battery Number : 
</td>

<td>
<?php echo $exchange_vehicle["battery_no"]; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Key Number : 
</td>

<td>
<?php echo $exchange_vehicle["key_no"]; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Service Number : 
</td>

<td>
<?php echo $exchange_vehicle["service_book"]; ?>
</td>
</tr>


</table>
</div>
<?php } ?>
<table class="delivery_challan_table" width="100%;">



<tr id="company_name_tr">
	<td class="border_column" align="center" >
    	<?php echo $our_company['our_company_name']; ?>
    </td>
</tr>

<tr>
	<td id="logo_tr" align="center" >
    <img src="../../../images/tvs-motors-021211.png" width="250px;" style="position:absolute; top:80px;left:70px;" />	Authorised Dealer Of TVS 3 Wheelers <br /><pre><?php echo $our_company['our_company_address']; ?></pre>
    </td>
</tr>

<tr>
	<td id="company_address_tr" align="center" >
    <table width="100%">
            <tr>
                <td class="" align="left" style="padding-left:2%;">
                  EMAIL:  <span style="text-decoration:underline"><?php echo $our_company['email']; ?></span>
                </td>
                <td class="" align="center">
                    Phone No: <?php echo $ourCompany['our_company_id']; $contact_nos=getContactNoForOurCompany($_SESSION['edmsAdminSession']['oc_id']); 
                                if($contact_nos!=false)
                                { 	
                                    for($i=0;$i<count($contact_nos);$i++)
                                    { 
                                        $no=$contact_nos[$i]; 
                                        if($i==(count($contact_nos)-1)) 
                                        echo $no[1]; 
                                        else
                                        echo $no[1]." <br> "; 
                                    }
                                } ?>
                </td>
    
			</tr>
	</table>
    </td>
</tr>    


<tr id="retail_tr">
	<td class="" align="center" valign="bottom" >
    	<?php if($sales['retail_tax']==0)  echo "RETAIL INVOICE"; else if($sales['retail_tax']==1) echo "TAX INVOICE"; ?>
    </td>
</tr>

<tr>
	<td class="border_column">
    	<table width="100%">
        	<tr>
            	<td width="12%" style="padding-left:2%;">TIN No</td>
                <td width="51%">: <?php echo $our_company['tin_no']; ?> &nbsp; &nbsp; &nbsp; Dt: <?php echo date('d/m/Y',strtotime($our_company['tin_date']));?></td>
                <td width="35%" >ORIGINAL / DUPLICATE / TRPLICATE</td>
                
            </tr>
            
            <tr>
            	<td width="12%" style="padding-left:2%;">C.S.T No</td>
                <td width="36%">: <?php echo $our_company['cst_no']; ?> &nbsp; &nbsp; &nbsp; Dt: <?php echo date('d/m/Y',strtotime($our_company['cst_date']));?></td>
                <td width="50%" ></td>
                
            </tr>
         </table>
    </td>
</tr>            

<tr>
	<td class="border_column">
    	<table width="100%">
        	
            <tr>
            	<td width="12%" style="padding-left:2%;">Name</td>
                <td width="56%">: <?php echo $customer['customer_name'];  ?></td>
                <td width="15%">INVOICE NO: <br /> CHALLAN NO:</td>
                <td width="25%"><?php echo $invoice['invoice_no'];?> <br /> <?php echo $delivery_challan['challan_no'];?></td>
            </tr>
             <tr>
            	<td width="12%" style="padding-left:2%;" valign="top">Address</td>
                <td width="56%" ><pre>: <?php echo $customer['customer_address']?></pre></td>
                 <td width="15%" valign="top">Date: <Br /> STATE:</td>
                <td width="25%" valign="top"><?php echo date('d/m/Y',strtotime($invoice['invoice_date']));?> <br /> GUJARAT</td>
                
            </tr>
             <tr>
            	<td width="12%" style="padding-left:2%;" valign="top">Phone</td>
                <td width="56%">:
            <?php
			
                            $contactNumbers = $customer['contact_no'];
							
                           for($z=0;$z<count($contactNumbers);$z++)
                              {
								$c=$contactNumbers[$z];
								if($z==(count($contactNumbers)-1))
								echo $c[0];  
								else
                      			echo $c[0]." | ";				
                              } ?>
                </td>
                <td width="15%"><?php if($sales['retail_tax']==1) { ?>TIN No:<?php } ?></td>
                <td width="25%"><?php if($sales['retail_tax']==1) { echo $customer['tin_no']; } ?></td>
                </tr>
                 
                <tr>
            	<td  colspan="4" style="padding-left:2%;"><span style="text-decoration:underline">UNDER H.P.A / H.Y.P WITH :</span>
                <?php if(validateForNull($delivery_challan["financer_ledger_id"]) && is_numeric($delivery_challan["financer_ledger_id"])) echo getLedgerNameFromLedgerId($delivery_challan["financer_ledger_id"]);  else echo "NO FINANCE";?></td>
               
            </tr>         
        </table>
    </td>
</tr>

      <td class="border_column">
        <table width="100%" >
        	<tr style="border-bottom:1px solid #000;">
            	<td width="35%" style="padding-left:2%">SR. Description</td>
                <td width="21%">HSN CODE</td>
                <td width="21%">QTY</td>
                <td width="21%">AMOUNT - RS.</td>
              
            </tr>
            <tr>
            	<td width="35%" style="padding-left:2%">1. <?php echo getVehicleTypeNameById($vehicle_model['vehicle_type_id']); ?> <br /> <?php echo $vehicle_model['model_name']; ?> <br /> COLOR: <?php if(is_numeric($vehicle['vehicle_color_id'])) echo getVehicleColorNameById($vehicle['vehicle_color_id']); else echo "NA"; ?>	 <br /> WITH <?php echo $vehicle_model['cubic_capacity'] ?> CC ENGINE</td>
                <td width="21%"></td>
                <td width="21%">1</td>
                <td width="21%" style="border-bottom:1px solid #000;"><?php echo $sales['amount']." /-"; ?></td>
            </tr>
            <?php foreach($tax_group as $tg) {  ?>
            <tr>
            	<td width="35%" style="padding-left:2%"></td>
                <td width="21%"></td>
                <td width="21%"><?php echo $tg['tax_name']." ".$tg['tax_percent']."%"; ?></td>
                <td width="21%"><?php echo number_format($tg['tax_amount'],2,".","")." /-"; ?></td>
            </tr>
            
            <?php } ?>
            <tr>
            	<td width="35%" style="padding-left:2%"></td>
                <td width="21%"></td>
                <td width="21%">Total: </td>
                <td width="21%" style="border-top:1px solid #000;"><?php echo number_format($nett_amount,2,".","")." /-"; ?></td>
            </tr>
        </table>
    </td>
</tr>

<tr>
      <td class="border_column">
        <table width="100%" class="">
        	<tr>
            	<td width="98%" style="padding-left:2%" colspan="2">Rs: <?php echo numberToWord(number_format($nett_amount,0,".","")); ?> Only</td>
            </tr>
           
        </table>
    </td>
</tr>

<tr>
      <td>
        <table width="100%" class="full_border_table" >
        	<tr>
            	<td width="12%">MFG YR</td>
                <td width="22%">CNG TANK NO</td>
                <td width="22%">VEPORISE NO</td>
                <td width="22%">CHASIS NO</td>
                <td width="22%">ENGINE NO</td>
            </tr>
            <tr>
            	<td width="12%"><?php echo $vehicle['vehicle_model']; ?></td>
                <td width="22%"><?php echo $vehicle['cng_cylinder_no']; ?></td>
                <td width="22%"><?php echo $vehicle['cng_kit_no']; ?></td>
                <td width="22%"><?php echo $vehicle['vehicle_chasis_no']; ?></td>
                <td width="22%"><?php echo $vehicle['vehicle_engine_no']; ?></td>
            </tr>
        </table>
    </td>
</tr>

<tr>
	<td class="border_column" width="98%" style="padding:1%;">
    	We hereby certify that our registration No. GJ-07-TC-99 Senctioned above are in force on the data on which the sale of goods specified in this bill/cash memorandum is made by us.
    </td>
</tr>

<tr>
	<td class="border_column">
    	<table width="100%">
        	<tr>
            	<td width="58%"  style="padding-left:2%; text-decoration:underline">TERMS & CONDITIONS :</td>
                <td width="40%" > </td>
               
            </tr>
            
            <tr>
            	<td width="58%"  style="padding-left:2%;">1. Goods once sold will not be taken back.</td>
                
                 <td width="40%"  align="center" >For, KESHAV MOTOTRS.</td>
               
            </tr>
            
            <tr>
            	<td width="58%"  style="padding-left:2%;">2. No claim for damage will be entertained after the vehicle leave our showroom  except those included in mfg Company Warranty.</td>
                <td width="40%" > </td>
               
            </tr>
            
            <tr>
            	<td width="58%" style="padding-left:2%;">3. * SUBJECT TO KHEDA JURISDICTION *</td>
               
                <td width="40%"  align="center" >Authorised Signatory</td>
            </tr>
            
           
             <tr>
             	
            	<td width="60%"  align="center">E. & O. E.</td>
                
                <td width="40%" align="center" ></td>
                
            </tr>
            
          
                         
        </table>
     </td>
</tr>

</table>

</div>
<div class="clearfix"></div>