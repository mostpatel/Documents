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


}
else
{
	$_SESSION['ack']['msg']="Invalid File!";
	$_SESSION['ack']['type']=4; // 4 for error
	header("Location: ".WEB_ROOT."admin/search");
	
}

?>
<div class="addDetailsBtnStyling no_print"> <a href="index.php?view=challan&id=<?php echo $delivery_challan_id; ?>"><button class="btn btn-success">Print</button></a> </div>
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
<h4 class="headingAlignment"> Delivery Challan Details </h4>


<table id="insertGuarantorTable" class="insertTableStyling detailStylingTable">

<tr>
<td width="180px;">Challan No : </td>
<td><?php   echo $delivery_challan['challan_no']; ?> </td>
</tr>

<tr>
<td>Date : </td>
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
<?php if($insurance!="error") echo $insurance["insurance_company_name"]; else echo "NA"; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Insurance Date : 
</td>

<td>
<?php if($insurance!="error") echo date('d/m/Y',strtotime($insurance["insurance_issue_date"])); else echo "NA";?>
</td>
</tr>

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
					<?php if(is_numeric($vehicle['vehicle_color_id'])) echo getVehicleColorNameById($vehicle['vehicle_color_id']); else echo "-"; ?>	
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

<tr>
	<td class="empty_column">
    </td>
</tr>

<tr>
	<td class="border_column" align="center">
    	* SUBJECT TO KHEDA JURISDICTION *
    </td>
</tr>

<tr>
	<td class="empty_column">
    </td>
</tr>

<tr>
	<td class="border_column" align="center">
    	DELIVERY CHALLAN
    </td>
</tr>

<tr>
	<td class="border_column">
    	<table width="100%">
        	<tr>
            	<td width="12%" style="padding-left:2%;">Challan No</td>
                <td width="63%">: <?php echo $delivery_challan['challan_no']?></td>
                <td width="7%">Date:</td>
                <td width="18%"><?php echo date('d/m/Y',strtotime($delivery_challan['delivery_date']));?></td>
            </tr>
            <tr>
            	<td width="12%" style="padding-left:2%;">Name</td>
                <td width="63%">: <?php echo $customer['customer_name']?></td>
                <td width="7%">Time:</td>
                <td width="18%"><?php echo date('h:i A',strtotime($delivery_challan['date_added']));?></td>
            </tr>
             <tr>
            	<td width="12%" style="padding-left:2%;" valign="top">Address :</td>
                <td width="88%" colspan="3"> <pre> <?php echo $customer['customer_address']?></pre></td>
                
            </tr>
             <tr>
            	<td width="12%" style="padding-left:2%;" valign="top">Phone</td>
                <td width="88%" colspan="3">:
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
                </tr>
                         
        </table>
    </td>
</tr>

<tr>
	<td class="border_column">
    	<table width="100%">
        	<tr>
            	<td width="30%" colspan="2" style="padding-left:2%; text-decoration:underline">UNDER H.P.A / H.Y.P WITH :</td>
                <td width="63%" colspan="2"> <?php if(validateForNull($delivery_challan["financer_ledger_id"]) && is_numeric($delivery_challan["financer_ledger_id"])) echo getLedgerNameFromLedgerId($delivery_challan["financer_ledger_id"]);  else echo "NO FINANCE";?></td>
               
            </tr>
            <tr>
            	<td width="15%" style="padding-left:2%;">Model</td>
                <td width="78%" colspan="3">: <?php echo getModelNameById($vehicle['model_id']); ?>	</td>
            </tr>
             <tr>
            	<td width="15%" style="padding-left:2%;">Capacity (CC)</td>
                <td width="33%" >: <?php echo $vehicle_model['cubic_capacity']; ?>	</td>
                <td width="15%" >Key No</td>
                <td width="35%" >: <?php echo $vehicle['key_no']; ?>	</td>
            </tr>
             <tr>
            	<td width="15%" style="padding-left:2%;">Battery Make</td>
                <td width="33%" >: <?php if(validateForNull($vehicle['battery_make_id']) && is_numeric($vehicle['battery_make_id'])) echo getBatteryMakeNameById($vehicle['battery_make_id']);  else echo "NA";  ?>	</td>
                <td width="15%" >Battery No</td>
                <td width="35%" >: <?php echo $vehicle['battery_no']; ?>	</td>
            </tr>
            <tr>
            	<td width="15%" style="padding-left:2%;">Service Book</td>
                <td width="33%" >: <?php echo $vehicle['service_book']; ?>	</td>
                <td width="15%" >Color</td>
                <td width="35%" >: <?php echo getVehicleColorNameById($vehicle['vehicle_color_id']); ?>		</td>
            </tr>
            <tr>
            	<td width="15%" style="padding-left:2%;">Salesman Reference / Agent</td>
                <td width="78%" colspan="3">: <?php echo getLedgerNameFromLedgerId($delivery_challan["salesman_ledger_id"]); ?>	</td>
            </tr>
            <tr>
            	<td width="15%" style="padding-left:2%;">Insurance</td>
                <td width="78%" colspan="3">: <?php echo $insurance["insurance_company_name"]; ?>	</td>
            </tr>
             <tr>
            	<td width="15%" style="padding-left:2%;">FROM</td>
                <td width="78%" colspan="3">: <?php echo date('d/m/Y',strtotime($insurance['insurance_issue_date'])); ?> &nbsp; &nbsp; &nbsp;  TO : <?php echo date('d/m/Y',strtotime($insurance['insurance_expiry_date'])); ?>	</td>
            </tr>
            
                         
        </table>
     </td>
</tr>
<tr>
      <td>
        <table width="100%" class="full_border_table">
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
      <td>
        <table width="100%" class="full_border_table">
        	<tr>
            	<td align="center" style="padding-top:20px;padding-bottom:20px;">COMPANY KIT</td>
              
            </tr>
           
        </table>
    </td>
</tr>

<tr>
      <td>
        <table width="100%" class="full_border_table">
        	<tr>
            	<td width="12%">TOOL KIT</td>
                <td width="22%">SERVICE BOOK</td>
                <td width="22%">BATTERY</td>
                <td width="22%">SPARE WHEEL</td>
                <td width="22%">WATER BOTTLE</td>
            </tr>
            <tr>
            	<td width="12%"><?php if($delivery_challan['toolkit_inc']==1) echo "Y"; else echo "N"; ?></td>
                <td width="22%"><?php if($delivery_challan['service_book_inc']==1) echo "Y"; else echo "N"; ?></td>
                <td width="22%"><?php if($delivery_challan['battery_inc']==1) echo "Y"; else echo "N"; ?></td>
                <td width="22%"><?php if($delivery_challan['spare_wheel_inc']==1) echo "Y"; else echo "N"; ?></td>
                <td width="22%"><?php if($delivery_challan['water_bottle_inc']==1) echo "Y"; else echo "N"; ?></td>
            </tr>
        </table>
    </td>
</tr>
<tr>
	<td class="border_column">
    	<table width="100%">
        	<tr>
            	<td width="30%" colspan="2" style="padding-left:2%; text-decoration:underline">NOTE :</td>
                <td width="63%" colspan="2"> </td>
               
            </tr>
            
             <tr>
            	<td width="15%" style="padding-left:2%;"></td>
                <td width="33%" ></td>
                <td width="50%"  colspan="2" align="center" >For, KESHAV MOTOTRS.</td>
                
            </tr>
             <tr>
            	<td width="15%" height="70px;" style="padding-left:2%;"></td>
                <td width="33%" ></td>
               <td width="50%"  colspan="2" ></td>
            </tr>
             <tr>
            	<td width="15%" style="padding-left:2%;"></td>
                <td width="33%" ></td>
                <td width="50%" colspan="2" align="center" >Authorised Signatory</td>
                
            </tr>
             <tr>
             	<td width="15%" ></td>
            	<td width="30%" style="border-top:1px solid #000" align="center">Customer's Signature</td>
                
                <td width="48%" colspan="2" align="center" ></td>
                
            </tr>
            
            <tr>
            	<td width="15%" colspan="1" style="padding-left:2%;">NOTE :</td>
                <td width="63%" colspan="3"> ALWAYS BRING THIS SLIP WHEN YOU COME TO COLLECT BILL / SALE LETTER. </td>
               
            </tr>
            
                         
        </table>
     </td>
</tr>

</table>

</div>
<div class="clearfix"></div>