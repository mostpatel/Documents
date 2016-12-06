<?php 

$enquiry_id = $_GET['id'];
if (!checkForNumeric($enquiry_id))
{
	exit;
}
$products=getSubCatFromEnquiryId($enquiry_id);

?>

<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Supplier Email Sending Details</h4>
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

 foreach($products as $product)
{
	$sub_cat_id=$product['sub_cat_id'];
	$price=$product['customer_price'];
	$quantity_id=$product['quantity_id']
?>

<input type="hidden" name="enquiry_id" value="<?php echo $enquiry_id; ?>" />
<table class="insertTableStyling no_print">




<tr>
<td> Supplier Details <span class="requiredField" class="firstColumnStyling" width="225px"> </span>: </td>
				<td>
					<?php
					$supplierArray = getSuppliersBySubCatId($sub_cat_id);
					foreach($supplierArray as $supplier)
					{
						
						$supplierData = getSupplierById($supplier['supplier_id']);
			echo $supplierData['supplier_email'].  " (".$supplierData['supplier_name']. ") (".$supplierData['supplier_phone'].") ";
						?>
                        <br />
                        <?php
					}
					?>
                     
                </td>
</tr>

</table>


<table class="insertTableStyling">

<?php
$subCategory = getSubCatFromEnquiryId($enquiry_id);
if(count($subCategory)==1 && is_numeric($subCategory[0][0]))
{
foreach($subCategory as $subC)
{
$sub_cat_id=$subC['sub_cat_id'];
$subCatNameArray = getsubCategoryById($sub_cat_id);
$subCatName = $subCatNameArray['sub_cat_name'];

$quantity_id = $subC['quantity_id'];
$quantityDetails = getQuantityById($quantity_id);
$quantity = $quantityDetails['quantity'];


$price = $subC['customer_price'];


$attribute_type_names_array=getAttributeTypesForASubCatOfAnEnquiry($sub_cat_id,$enquiry_id);

?>

<tr>
<td class="firstColumnStyling">
<b style="font-family:myFontBold"><?php echo PRODUCT_GLOBAL_VAR; ?> :</b> 
</td>

<td>
                             <?php echo $subCatName; ?>					
                          
</td>
</tr>
<?php
foreach($attribute_type_names_array as $attribute_type_names)
{

?>

<tr>
<td class="firstColumnStyling">
<b style="font-family:myFontBold"> 
<?php  echo  $attribute_type_names['attribute_type']. " : ";	?>
</b>
 
</td>
  
<td>
  <?php echo  $attribute_type_names['attribute_names_string'];	?>                      			
                          
</td>
</tr>

<?php
}
?>

<?php if(defined('SHOW_QUANTITY') && SHOW_QUANTITY==1) 
{ ?>
<tr>
<td class="firstColumnStyling">
<b style="font-family:myFontBold"> <?php echo QUANTITY_GLOBAL_VAR; ?>:</b> 
</td>

<td>
                             <?php 
							 
							  
							  
							 
							 echo $quantity; 
							 
							 ?>					
                          
</td>
</tr>
<?php  } ?>


<?php
}
}
else if(count($subCategory)>1 && is_numeric($subCategory[0][0]))
{
?>
<tr>
<td >
	<table>
    	<tr>
        	<th align="left" style="font-family:myFontBold"><b><?php echo PRODUCT_GLOBAL_VAR; ?></b></th>
            <th align="left" style="font-family:myFontBold"><b> Extra Details </b></th>
            <th align="left" style="font-family:myFontBold"><?php echo QUANTITY_GLOBAL_VAR; ?></th>
            <th align="left" style="font-family:myFontBold">Price/unit</th> 
        </tr>
        
  
<?php	
foreach($subCategory as $subC)
{
$sub_cat_id=$subC['sub_cat_id'];
$subCatNameArray = getsubCategoryById($sub_cat_id);
$subCatName = $subCatNameArray['sub_cat_name'];

$quantity_id = $subC['quantity_id'];
$quantityDetails = getQuantityById($quantity_id);
$quantity = $quantityDetails['quantity'];

$price = $subC['customer_price'];

$attribute_type_names_array=getAttributeTypesForASubCatOfAnEnquiry($sub_cat_id, $enquiry_form_id);

?>

<tr>

<td width="160px;" style="padding-left:0;">
                             <?php echo $subCatName; ?>
                          
</td>

<td width="200px;" style="padding-left:0;">
  <?php
  
  if(is_array($attribute_type_names_array))
  {
  foreach($attribute_type_names_array as $attribute_type_names)
  {
  ?>
<u> <?php echo  $attribute_type_names['attribute_type']; ?> </u> <?php echo " : ". $attribute_type_names['attribute_names_string'];?> <br />
  <?php
  }
  }
  else
  {
	echo "NA";  
  }
  ?>                           					
                          
</td>


<td width="120px;" style="padding-left:0;">
                             <?php 
							 echo $quantity; 
							 ?>					
                          
</td>


<td width="120px;" style="padding-left:0;">
                              <?php 
							  
							  echo $price;
							   ?>						
                          
</td>

</tr>

<?php
}	
?>
</table>
</td>
</tr>
<?php }} ?>

</table>
<a href="https://mail.google.com/" target="_blank">
<input type="button" value="Go To MailBox" class="btn btn-warning" />
</a>
       
</div>
<div class="clearfix"></div>