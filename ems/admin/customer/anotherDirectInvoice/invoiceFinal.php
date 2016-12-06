<?php

if(!isset($_GET['id']))
header("Location: ".WEB_ROOT."admin/search");

$in_customer_id=$_GET['id'];

$customerInvoiceDetails = getInvoiceCustomerById($in_customer_id);
$contactNumbers = getInvoiceCustomerContactNo($in_customer_id);
$productdetails = getInvoiceRelSubCatEnquiryFromInCustomerId($in_customer_id);

$invoiceDate = $customerInvoiceDetails['invoice_date'];
$invoiceDate = date('d/m/Y',strtotime($invoiceDate));

$customerName = $customerInvoiceDetails['in_customer_name'];


$email = $customerInvoiceDetails['in_customer_email'];

$address = $customerInvoiceDetails['in_customer_address'];

$city_id = $customerInvoiceDetails['city_id'];

$cityArray = getCityByID($city_id);
$city = $cityArray['city_name'];
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
<table class="mainInvoiceContainer">
<tr style="border-bottom:1px solid #000;padding-bottom:10px;">
        
         <td width="40%;" align="left" valign="top">
            <div class="invoice font">INVOICE</div>
            
            <div class="date font">
            <b>Date :</b> <?php echo $invoiceDate; ?>
            </div>  <!-- End of date -->
            
            <div class="invoiceNo font">
            <b>Invoice No :</b> #410250501
            </div>  <!-- End of date -->
            
          </td><!-- End of LeftSectionOne -->
          <td width="60%;" align="right" style="padding-bottom:10px;">
          
          	<table border="0" align="right" id="address_table">
            <tr> <td class="companyName">Tap and Type</td></tr> 
            
           <tr><td class="address">
             30, Vanijya Bhavan,<br />
             Opp. Diwan Ballubhai School, <br />
             Kankaria, Maninagar<br />
             Ahmedabad</td>
           </tr>  <!-- End of address -->
            
            <tr><td class="phoneNo">
             <b> Contact No : </b> 079-26871370, 09824143009
            </td></tr>  <!-- End of phoneNo -->
            
            <tr><td class="email">
            <b> Email :</b> info@tapandtype.com
            </td></tr>   <!-- End of email -->
            
            <tr>
            <td class="website">
            <b> website :</b> www.tapandtype.com
            </td></tr>   <!-- End of email -->
           </table> 
          </td>  <!-- End of rightSectionOne -->
          
          <div class="clearFix"></div>
          
        </tr>
         <!-- End of SectionOne -->
        
      <tr>
        <td colspan="2" align="left">
        	<table id="customer_table">
           <tr><td>
              Bill To, 
              </td></tr>
        
           <tr><td>
              
             <b> <?php echo $customerName ?> </b>  <br />
             <?php echo $address ?><br />
             <?php echo $city ?><br />
             </td></tr>  <!-- End of address -->
            
            <tr><td>
             <b> Contact No : </b>  
			 <?php foreach($contactNumbers as $contact)
			 {
				echo $contact['in_customer_contact_no']; 
			 }
			 ?>
             </td></tr>  <!-- End of phoneNo -->
            
             <tr><td>
            <b> Email :</b> <?php echo $email ?>
            </td></tr>   <!-- End of email -->
       </table>
      </td></tr> 
        <!-- End of sectionTwo -->
        
        <tr>
        	<td colspan="2"> 
           
           
          <table id="invoice_product_table" class="font" width="100%">
          
            <tr>
            <th>No</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Price(Rs.)</th>
            </tr>
            
            <?php
			$no=1;
			$total_amount = 0;
			foreach($productdetails as $p)
			{
				
			$total_amount=$total_amount+$p['invoice_price'];
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
			$quantityId = $p['invoice_quantity_id']; 
			$quantityArray = getQuantityById($quantityId);
			echo $quantityArray['quantity'];
			?>
            </td>
            <td>
            <?php 
			 echo number_format($p['invoice_price']); 
			?>
            </td>
            </tr>
            
            <?php
            }
            ?>
            
            
            <tr>
            <td height="<?php echo 20*33 ?>px"></td>
            <td></td>
            <td></td>
            <td></td>
            </tr>
            
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
                </td  <!-- End of singleRowLeft -->
                
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