<?php

if(!isset($_GET['id']))
header("Location: ".WEB_ROOT."admin/search");

$quo_customer_id=$_GET['id'];

$ourCompanyDetails = getOurCompanyByID(5);


$customerQuotationDetails = getQuotationCustomerById($quo_customer_id);
$contactNumbers = getQuotationCustomerContactNo($quo_customer_id);
$productdetails = getQuotationRelSubCatEnquiryFromInCustomerId($quo_customer_id);

$quotationeDate = $customerQuotationDetails['quotation_date'];
$quotationDate = date('d/m/Y',strtotime($quotationDate));

$customerName = $customerQuotationDetails['quo_customer_name'];


$email = $customerQuotationDetails['quo_customer_email'];

$address = $customerQuotationDetails['quo_customer_address'];

$city_id = $customerQuotationDetails['city_id'];

$cityArray = getCityByID($city_id);
$city = $cityArray['city_name'];
$total_number_of_clos_to_display = 15;
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
<div class="alert no_print  <?php if(isset($type) && $type>0 && $type<4) echo "alert-success"; else echo "alert-error" ?>">
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
<link rel="stylesheet" href="<?php echo WEB_ROOT."css/invoice.css"; ?>"  />
<table class="mainInvoiceContainer" style="width:95%">
<tr style="border-bottom:1px solid #000;padding-bottom:10px;">
        
         <td width="50%;" align="left" valign="top">
            <div class="invoice font" style="font-size:34px;padding-bottom:70px;">QUOTATION</div>
            
            <div style="width:40%;" class="date font">
            <b>Date :</b> <?php echo date('d/m/Y', strtotime(getTodaysDate())); ?>
            </div>  <!-- End of date -->
            
            <div style="float:left;width:60%;" class="invoiceNo font">
            <b>Quotation No :</b> #410250501
            </div>  <!-- End of date -->
            
          </td><!-- End of LeftSectionOne -->
          <td width="50%;" align="right" style="padding-bottom:10px;">
          
          	<table border="0" align="right" id="address_table">
            <tr> <td class="companyName" style="text-align:left;">Balaji Enterprise</td></tr> 
            
           <tr>
           <td class="address" style="text-align:left;">
           410-A, Infinity, <br />
           Nr. Ramada Hotel <br />
           Prahladnagar, Corporate Road. <br />
           Ahmedabad.
             </td>
           </tr>  <!-- End of address -->
            
            <tr><td class="phoneNo">
             <b> Contact No : </b> 09978915666
            </td></tr>  <!-- End of phoneNo -->
            
            <tr><td class="email">
            <b> Email :</b> balajienterprise24@yahoo.in
            </td></tr>   <!-- End of email -->
            
            <tr>
            <td class="website">
            
            </td></tr>   <!-- End of email -->
           </table> 
          </td>  <!-- End of rightSectionOne -->
          
          <div class="clearFix"></div>
          
        </tr>
         <!-- End of SectionOne -->
        
      <tr>
        <td colspan="2" align="left">
        	<table id="customer_table">
           <tr><td style="padding-bottom:10px;padding-top:10px;">
              Quotation To, 
              </td></tr>
        
           <tr><td>
              
             <b style="margin-bottom::10px;"> <?php echo $customerName ?> </b>  <br />
             <?php echo $address ?><br />
             <?php echo $city ?><br />
             </td></tr>  <!-- End of address -->
            
            <tr><td style="padding-top:5px;">
             <b> Contact No : </b>  
			 <?php foreach($contactNumbers as $contact)
			 {
				echo $contact['quo_customer_contact_no']; 
			 }
			 ?>
             </td></tr>  <!-- End of phoneNo -->
            
             <tr><td style="padding-top:5px;"> 
            <b> Email :</b> <?php echo $email ?>
            </td></tr>   <!-- End of email -->
            
             <tr><td style="padding-top:25px;"> 
           Dear Sir / Madam,<br />
         As per your requirement we are providing our lowest rate as under.

            </td></tr>   <!-- End of email -->
       </table>
      </td></tr> 
        <!-- End of sectionTwo -->
        
        <tr>
        	<td colspan="2"> 
           
           
          <table border="1" id="invoice_product_table" class="font" width="100%" style="margin-top:50px;">
          
            <tr>
            <th align="left">No</th>
            <th align="left">Description</th>
            <th align="left">Quantity</th>
            <th align="left">Price(Rs.)</th>
            </tr>
            
            <?php
			$no=1;
			$total_amount = 0;
			foreach($productdetails as $p)
			{
				
			$total_amount=$total_amount+$p['quotation_price'];
            ?>
            <tr>
            <td height="22px" class="no-border"><?php echo $no++; ?></td>
            
            <td>
			<?php 
			$subCatId = $p['sub_cat_id']; 
			$subCatDetailArray = getsubCategoryById($subCatId);
			$category = getCategoryBySubCategoryId($subCatId);
			$catName = $category['cat_name'];
			echo $catName. " ".$subCatDetailArray['sub_cat_name'];
			
			  
			
			?>
            </td>
            
            <td>
            <?php 
			$quantityId = $p['quotation_quantity_id']; 
			$quantityArray = getQuantityById($quantityId);
			echo $quantityArray['quantity'];
			?>
            </td>
            <td>
            <?php 
			 echo number_format($p['quotation_price']); 
			?>
            </td>
            </tr>
            
            <?php
            }
            ?>
            
            <?php  if(count($productdetails)<$total_number_of_clos_to_display)
			{ ?>
            <tr>
            <td height="<?php echo ($total_number_of_clos_to_display-count($productdetails))*33 ?>px"></td>
            <td></td>
            <td></td>
            <td></td>
            </tr>
           <?php } ?> 
             <tr>
           
             <td></td>
             <td></td>
             <td> 
                Subtotal : 
             </td>  <!-- End of singleRowLeft -->
                
                <td>
                <?php echo number_format($total_amount); ?>
                </td>  <!-- End of singleRowRight -->
                
             </tr>  <!-- End of singleRow -->
             
             <tr>
           
             <td></td>
             <td></td>
             
                <td>
                VAT(12.5%) : 
                </td>  <!-- End of singleRowLeft -->
                
               <td>
                13,000
                </td>  <!-- End of singleRowRight -->
                
             </tr>  <!-- End of singleRow -->
             
              <tr>
           
             <td></td>
             <td></td>
             
                <td>   Other Tax(5%) : 
                </td  ><!-- End of singleRowLeft -->
                
                <td>5,000
                </td>  <!-- End of singleRowRight -->
                
             </tr>  <!-- End of singleRow -->
             
              <tr>
           
             <td></td>
             <td></td>
             
                <td>
                 Discount (5%): 
                </td>  <!-- End of singleRowLeft -->
                
                <td>
                5,000
                </td>  <!-- End of singleRowRight -->
                
             </tr>  <!-- End of singleRow -->
             
             <tr>
             	<td></td>
                <td></td>
               <td>
                <b> Grand Total: </b>
                </td>  <!-- End of singleRowLeft -->
                
               <td>
                <b>  1,18,000  </b>
                </td>  <!-- End of singleRowRight -->
                
             </tr>  <!-- End of singleRow -->
             
          </table>
         </td>
         </tr>
           <!-- End of sectionFourRight --> 
          
      
        
        <tr>
         <td colspan="2" style="padding-left:30px;padding-top:30px;padding-bottom:20px">
         	
          
           
               <div class="topSectionFourLeft font">
               Note : <br /> <br />
               1.) Sold goods will not be taken back. <br />
               2.) Kindly keep the bill with you for further communication. 
               </div>  <!-- End of topSectionFourLeft -->
               
               <div class="bottomSectionFourLeft font">
               Stamp/Signature : ___________________
               </div>  <!-- End of bottomSectionFourLeft -->
               
          </td>
          </tr></table>
             
           <div class="clearFix"></div>
           
        
        
        <div class="sectionFive font">
        Thanks for your business!
        </div> <!-- End of sectionFive --> 
      
    
    
 <!-- End of mainInvoiceContainer -->
<hr class="firstTableFinishing" />
</div>
<div class="clearfix"></div>
<style>
#invoice_product_table tr td,#invoice_product_table tr th{
	padding:7px;
	padding-left:7px;
	}
</style>