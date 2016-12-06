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
	$sale_cert = getSaleCertByVehicleId($vehicle['vehicle_id']);
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
<h4 class="headingAlignment"> Sale Certificate Details </h4>


<table id="insertGuarantorTable" class="insertTableStyling detailStylingTable">

<tr>
<td width="180px;">Sale Certificate Date : </td>
<td><?php   echo date('d/m/Y',strtotime($sale_cert['cert_date'])); ?> </td>
</tr>


<tr>
<td width="180px;">Invoice No : </td>
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
<?php if(validateForNull($delivery_challan["financer_ledger_id"]) && is_numeric($delivery_challan["financer_ledger_id"])) echo getLedgerNameFromLedgerId($delivery_challan["financer_ledger_id"]);  else echo "NA"; ?>
</td>
</tr>

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

<h4 class="headingAlignment" > Insurance Details </h4>
<table class="insertTableStyling detailStylingTable">

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

<tr>
	<td></td>
  <td class="no_print">
            
          <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&id='.$delivery_challan_id.'&state='.$vehicle['vehicle_id'] ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button></a>
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$delivery_challan_id.'&state='.$customer['customer_id']; ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">X</span></button></a>
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
					<?php echo getVehicleColorNameById($vehicle['vehicle_color_id']); ?>	
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
  




<tr>
	<td class="" id="retail_tr" valign="bottom">
    	<table width="100%">
        	<tr>
            	<td  align="center">* SUBJECT TO KHEDA JURISDICTION *</td>
               
                
            </tr>
            
            
         </table>
    </td>
</tr>          

<tr>
	<td class="border_column">
    	<table width="100%">
        	
        	<tr>
            	<td  align="right" width="67%" >FORM NO 21 (See Rule 47 (A) and (B))</td>
                <td align="right" height="0px" width="32%" style="padding-right:1%;">Date : <?php echo date('d/m/Y',strtotime($sale_cert['cert_date'])) ?></td>
            </tr>
           
            <tr>
            	<td  align="center" colspan="2" >SALE CERTIFICATE</td>
               
            </tr>
            
         </table>
    </td>
</tr>            

<tr>
	<td class="border_column" style="border-bottom:none;">
    	<table width="100%" id="padding_bottom_less">
        	  <tr>
            	<td width="100%" style="padding-left:2%;" colspan="4"><p style="font-weight:bold;">(To be issued by Manufacturer / Dealer of officer of derpartment (incase of military auctioned vehicle) for presentation along with the application for registration of motor vehicle)</p> Certified that vehicle has been delivered by us to :</td>
                
            </tr>
            <tr>
            	<td width="12%" style="padding-left:2%;">Name</td>
                <td width="56%">: <?php echo $customer['customer_name']?></td>
                <td width="15%"></td>
                <td width="25%"></td>
            </tr>
             <tr>
            	<td width="12%" style="padding-left:2%;" valign="top">Address</td>
                <td width="56%" ><pre>: <?php echo $customer['customer_address']?></pre></td>
                 <td width="15%" valign="top"></td>
                <td width="25%" valign="top"></td>
                
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
                <td width="15%"></td>
                <td width="25%"></td>
                </tr>
                 
                <tr>
            	<td  colspan="4" style="padding-left:2%;"><span style="text-decoration:underline">UNDER H.P.A / H.V.P WITH :</span>
                <?php if(validateForNull($delivery_challan["financer_ledger_id"]) && is_numeric($delivery_challan["financer_ledger_id"])) echo getLedgerNameFromLedgerId($delivery_challan["financer_ledger_id"]);  else echo "NO FINANCE";?></td>
            </tr>    
            
            <tr>
            	<td width="98%" colspan="4" style="padding-left:2%;" valign="top">The Details of the vehicle are given below :</td>
               
                
            </tr>  
            <tr>
            	<td width="23%" style="padding-left:2%;" valign="top">1. Class of Vehicle</td>
                <td width="25%" >: <?php echo $vehicle_model['model_name']; ?></td>
                 <td width="25%" valign="top"></td>
                <td width="25%" valign="top"></td>
                
            </tr>   
            <tr>
            	<td width="23%" style="padding-left:2%;" valign="top">2. Maker's Name</td>
                <td width="25%" >: <?php echo getVehicleCompanyNameById($vehicle_model['vehicle_company_id']); ?></td>
                 <td width="25%" valign="top"></td>
                <td width="25%" valign="top"></td>
                
            </tr>   
             <tr>
            	<td width="23%" style="padding-left:2%;" valign="top">3. Chasis Number</td>
                <td width="25%" >: <?php echo $vehicle['vehicle_chasis_no']; ?></td>
                 <td width="25%" valign="top"></td>
                <td width="25%" valign="top"></td>
                
            </tr>   
             <tr>
            	<td width="23%" style="padding-left:2%;" valign="top">4. Engine Number</td>
                <td width="25%" >: <?php echo $vehicle['vehicle_engine_no']; ?></td>
                 <td width="25%" valign="top"></td>
                <td width="25%" valign="top"></td>
                
            </tr>  
             <tr>
            	<td width="23%" style="padding-left:2%;" valign="top">5. CNG Cylinder No</td>
                <td width="25%" >: <?php echo $vehicle['cng_cylinder_no']; ?></td>
                 <td width="25%" valign="top"></td>
                <td width="25%" valign="top"></td>
                
            </tr>  
             <tr>
            	<td width="23%" style="padding-left:2%;" valign="top">6. CNG Kit No</td>
                <td width="25%" >: <?php echo $vehicle['cng_kit_no']; ?></td>
                 <td width="25%" valign="top"></td>
                <td width="25%" valign="top"></td>
                
            </tr>  
             <tr>
            	<td width="23%" style="padding-left:2%;" valign="top">7. Cubic Capacity (CC)</td>
                <td width="25%" >: <?php echo $vehicle_model['cubic_capacity']; ?> CC</td>
                 <td width="25%" valign="top"></td>
                <td width="25%" valign="top"></td>
                
            </tr>   
             <tr>
            	<td width="23%" style="padding-left:2%;" valign="top">8. Fuel Used</td>
                <td width="25%" >: <?php echo getFuelTypeNameById($vehicle_model['fuel_type_id']); ?></td>
                 <td width="25%" valign="top"></td>
                <td width="25%" valign="top"></td>
                
            </tr> 
             <tr>
            	<td width="23%" style="padding-left:2%;" valign="top">9. No of Cylinders</td>
                <td width="25%" >: <?php echo $vehicle_model['no_of_cylinders']; ?></td>
                 <td width="25%" valign="top"></td>
                <td width="25%" valign="top"></td>
                
            </tr> 
             <tr>
            	<td width="23%" style="padding-left:2%;" valign="top">10. Year of manufacture</td>
                <td width="25%" >: <?php echo $vehicle['vehicle_model']; ?></td>
                 <td width="25%" valign="top"></td>
                <td width="25%" valign="top"></td>
                
            </tr> 
             <tr>
            	<td width="23%" style="padding-left:2%;" valign="top">11. Seating Capacity</td>
                <td width="25%" >: <?php echo $vehicle_model['seating_capacity']; ?></td>
                 <td width="25%" valign="top"></td>
                <td width="25%" valign="top"></td>
                
            </tr> 
             <tr>
            	<td width="23%" style="padding-left:2%;" valign="top">12. Unladen Weight</td>
                <td width="25%" >: <?php echo $vehicle_model['unladen_weight']; ?> Kgs</td>
                 <td width="25%" valign="top"></td>
                <td width="25%" valign="top"></td>
                
            </tr>   
            <tr>
            	<td width="25%" style="padding-left:2%;" >13. (A) Maximum Axle Wt.</td>
                <td width="36%"  >(a) Front : <?php if($vehicle_model['axle_wt_fr']>0) echo $vehicle_model['axle_wt_fr']; ?> Kgs &nbsp; &nbsp; (b) Rear : <?php if($vehicle_model['axle_wt_rr']>0) echo $vehicle_model['axle_wt_rr']; ?> Kgs</td>
                 <td width="37%" colspan="2" ></td>
                
                
            </tr>  
            <tr>
            	<td width="23%" style="padding-left:2%;" valign="top">&nbsp; &nbsp; &nbsp; (B) No. And Tyres.</td>
                <td width="36%"  >(a) Front : <?php echo $vehicle_model['no_tyre_fr']." (".$vehicle_model['tyre_type_fr'].")"; ?> (b) Rear : <?php echo $vehicle_model['no_tyre_rr']." (".$vehicle_model['tyre_type_rr'].")"; ?></td>
                 <td width="37%" colspan="2" ></td>
                
            </tr>   
            <tr>
            	<td width="98%" colspan="4" style="padding-left:2%;" valign="top">&nbsp; &nbsp; Description of Vehicle (Incase of transport Vehicle)</td>
                
                
            </tr>   
            <tr>
            	<td width="23%" style="padding-left:2%;" valign="top">14. Color</td>
                <td width="25%" >: <?php echo getVehicleColorNameById($vehicle['vehicle_color_id']); ?>	</td>
                 <td width="25%" valign="top"></td>
                <td width="25%" valign="top"></td>
                
            </tr>   
            <tr>
            	<td width="35%" style="padding-left:2%;" valign="top">15. Gross Vehicle Weight (GCW)</td>
                <td width="23%" >: <?php echo $vehicle_model['gross_weight']; ?> Kgs</td>
                 <td width="20%" valign="top"></td>
                <td width="20%" valign="top"></td>
                
            </tr>   
            <tr>
            	<td width="23%" style="padding-left:2%;" valign="top">16. Type Of Body</td>
                <td width="25%" >: Auto Rickshaw</td>
                 <td width="25%" valign="top"></td>
                <td width="25%" valign="top"></td>
                
            </tr>   
            <tr>
            	<td width="23%" style="padding-left:2%;" valign="top">17. WheelBase</td>
                <td width="25%" >: <?php echo $vehicle_model['wheelbase']; ?> mm</td>
                 <td width="25%" valign="top"></td>
                <td width="25%" valign="top"></td>
                
            </tr>   
           
        </table>
    </td>
</tr>

    

<tr>
	<td class="border_column border_top_none" >
    	<table width="100%">
        
            
            <tr>
            	<td width="58%"  style="padding-left:2%;"></td>
                
                 <td width="40%"  align="center" >For, KESHAV MOTOTRS.</td>
               
            </tr>
            
            
            
            <tr>
            	<td width="58%" style="padding-left:2%;height:80px;"></td>
               
                <td width="40%"  align="center" valign="bottom">Authorised Signatory</td>
            </tr>
            
           
           
            
          
                         
        </table>
     </td>
</tr>

</table>

</div>
<div class="clearfix"></div>