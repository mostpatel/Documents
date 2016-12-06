<?php 

$enquiry_form_id = $_GET['id'];


if (!checkForNumeric($enquiry_form_id))
{
	exit;
}

$enquiryDetails=getEnquiryById($enquiry_form_id);

$customer_id = $enquiryDetails['customer_id'];
$customerDetails=getCustomerById($customer_id);

$contactNumbers=getCustomerContactNo($customer_id);


$subCategory = getSubCatFromEnquiryId($enquiry_form_id);
$products=getSubCatFromEnquiryId($enquiry_form_id);

$sub_cat_id=$subCategory['sub_cat_id'];

$priceAndQuantityDetails= getRelSubCatEnquiryFromEnquiryId($enquiry_form_id);

$extraCustomerDetails = getExtraCustomerDetailsById($customer_id);


?>

<div class="insideCoreContent adminContentWrapper wrapper">
<h3 class="headingAlignment no_print">Quotation Details</h3>
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
<hr class="firstTableFinishing" />


<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post">

<input type="hidden" name="enquiry_id" value="<?php echo $enquiry_form_id; ?>" />
<input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>" />

<table class="insertTableStyling no_print">


<tr>

<td class="firstColumnStyling" width="130px">
Quotation Date : 
</td>

<td >
<input type="text" id="datepicker" size="12" autocomplete="off"  name="quotation_date" class="datepicker1 datepick" placeholder="Click to Select!" value=<?php $todayDate = getTodaysDate(); echo date('d/m/Y H:i:s',strtotime($todayDate)) ?>/><span class="customError DateError">Please select a date!</span>



</td>
</tr>

</table>

<h4 class="headingAlignment no_print">Customer's Basic Details</h4>

<table class="insertTableStyling no_print">


<tr>
<td class="firstColumnStyling">
Customer Name<span class="requiredField">* </span> :
</td>

<td>

<input type="text" name="name" id="txtName" value="<?php echo $customerDetails['customer_name']; ?>"/>
</td>
</tr>

<?php  
$lj=0;
foreach($contactNumbers as $contact)
{
 ?>
  <tr>
            <td>
            Contact No<?php if($lj==0) { ?><span class="requiredField">* </span> <?php } ?> : 
            </td>
            
            <td id="addcontactTd">
            <input type="text" class="contact" <?php if($lj==0) { ?> id="customerContact" <?php } ?> name="customerContact[]" <?php if($lj!=0) { ?> onblur="checkContactNo(this.value,this)" <?php } ?> placeholder="more than 6 Digits!" value="<?php echo $contact[0]; ?>" /><span></span><span class="ValidationErrors contactNoError">Please enter a valid Phone No (only numbers)</span>
                </td>
            </td>
            </tr>
<?php
$lj++;
 } ?> 


 <tr id="addcontactTrCustomer">
                <td>
                Contact No : 
                </td>
                
                <td id="addcontactTd">
                <input type="text" class="contact" <?php if($lj<1) { ?> id="customerContact" <?php } ?> name="customerContact[]" placeholder="more than 6 Digits!" <?php if($lj!=0) { ?> onblur="checkContactNo(this.value,this)" <?php } ?> /> <span class="addContactSpan"><input type="button" title="add more contact no" value="+" class="btn btn-success addContactbtnCustomer"/></span><span class="ValidationErrors contactNoError">Please enter a valid Phone No (only numbers)</span>
                </td>
            </tr>

<!-- for regenreation purpose Please donot delete -->
            
            <tr id="addcontactTrGeneratedCustomer">
            <td>
            Contact No : 
            </td>
            
            <td id="addcontactTd">
            <input type="text" class="contact" name="customerContact[]" onblur="checkContactNo(this.value,this)" placeholder="more than 6 Digits!" />  <span class="deleteContactSpan"><input type="button" value="-" title="delete this entry"  class="btn btn-danger deleteContactbtn" onclick="deleteContactTr(this)"/></span><span class="ValidationErrors contactNoError">Please enter a valid Phone No (only numbers)</span>
                </td>
            </td>
            </tr>
       
       

<td class="firstColumnStyling">
Email<span class="requiredField">* </span> :
</td>

<td>

<input type="text" name="email" id="email" value="<?php echo $customerDetails['customer_email']; ?>"/>
</td>
</tr>


</table>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment no_print">Customer's Address</h4>

<table class="insertTableStyling no_print">


<tr>
<td>
Address : 
</td>

<td>
<textarea id="address" class="address" name="address"  cols="5" rows="6">
<?php
if($extraCustomerDetails)
{

echo $extraCustomerDetails['customer_address'];
}
?>
</textarea>
</td>
</tr>

<tr>
<td width="130px" class="firstColumnStyling"> City : </td>
<td>
					<select id="city" name="city">
                        <option value="-1" >-- Select The City --</option>
                        <?php
                            $cities = listCities();
                            foreach($cities as $city)
                              {
                         ?>
                             
 <option value="<?php echo $city['city_id'] ?>" <?php if($extraCustomerDetails) { if($city['city_id'] == $extraCustomerDetails['city_id']){ ?> selected="selected" <?php }} ?>>
 <?php echo $city['city_name'] ?>
 </option>
                         <?php } ?>
                              
                         
                     </select> 
</td>
</tr>


</table>


</table>

<h4 class="headingAlignment no_print">Product Details</h4>

<table id="pTable" class="insertTableStyling no_print">
 
<tbody style="display:none;" id="productDetails">

<tr style="display:none;">

<td></td>

<td>
<span class="removeLink" onclick="removeThisProduct(this);"> Remove This Product </span>
</td>

</tr>

<tr>
<td>Product<span class="requiredField">* </span> : </td>
				<td>
                
					<select  name="product_array[]"  class="combobox editProduct" >
    					<option value=""></option>
                        <?php
                            $subCategories = listSubCategories();
                            foreach($subCategories as $subCategory)
                              {
								  $category = getCategoryBySubCategoryId($subCategory['sub_cat_id']);
                             ?>
                             
                             <option value="<?php echo $subCategory['sub_cat_id'] ?>"><?php echo $category['cat_name']. " ".$subCategory['sub_cat_name'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
                          
                            </td>
</tr>

<tr>

<td width="130px" class="firstColumnStyling">
MRP <span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="mrp_array[]"  class="editMRP" placeholder="Only Digits"/>

</td>
</tr>

<tr>
<td>Select Quantity : </td>
				<td>
					<select  name="quantity_id_array[]">
                        
                        <?php
                            $quantities = listQuantities();
                            foreach($quantities as $quantity)
                              {
                             ?>
                             
                           <option value="<?php echo $quantity['quantity_id'] ?>"><?php echo $quantity['quantity'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>
<td><hr class="firstTableFinishing" /></td>
<td><hr class="firstTableFinishing" /></td>
</tr>


</tbody>
<?php foreach($products as $product)
{
	$sub_cat_id=$product['sub_cat_id'];
	$price=$product['customer_price'];
	$quantity_id=$product['quantity_id']
	 ?>
<tbody >

<tr>

<td></td>

<td>
<span class="removeLink" onclick="removeThisProduct(this);"> Remove This Product </span>
</td>

</tr>

<tr>
<td>Product<span class="requiredField">* </span> : </td>
				<td>
                
					<select  name="product_array[]"  class="combobox EditProduct" >
    					<option value=""></option>
                        <?php
                            $subCategories = listSubCategories();
                            foreach($subCategories as $subCategory)
                              {
								  $category = getCategoryBySubCategoryId($subCategory['sub_cat_id']);
                             ?>
                             
                             <option value="<?php echo $subCategory['sub_cat_id'] ?>" <?php if($sub_cat_id==$subCategory['sub_cat_id']) { ?> selected="selected" <?php } ?>><?php echo $category['cat_name']. " ".$subCategory['sub_cat_name'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
                          
                            </td>
</tr>

<tr>

<td width="130px" class="firstColumnStyling">
MRP <span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="mrp_array[]"  class="editProduct" value="<?php echo $price; ?>" placeholder="Only Digits"/>

</td>
</tr>

<tr>
<td>Select Quantity : </td>
				<td>
					<select  name="quantity_id_array[]">
                        
                        <?php
                            $quantities = listQuantities();
                            foreach($quantities as $quantity)
                              {
                             ?>
                             
                           <option value="<?php echo $quantity['quantity_id'] ?>" <?php if($quantity_id== $quantity['quantity_id']) { ?> selected="selected" <?php } ?>><?php echo $quantity['quantity'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>
<td><hr class="firstTableFinishing" /></td>
<td><hr class="firstTableFinishing" /></td>
</tr>



</tbody>
<?php } ?>


</table>




<table style="margin-bottom:10px;">
<tr>
<td width="150px;">  </td>
<td><input type="button" class="btn btn-success" value="+ Add Another Product" id="addAnotherProductBtn" onclick="generateProductDetails()"/></td>
</tr>  
</table>

<table style="margin-top:25px;margin-bottom:10px;">
<tr>
<td width="150px" class="firstColumnStyling"> 
Terms & Conditions : 
</td>

<td>
<textarea id="rich_text_area" class="richtextarea" name="terms_condition"  cols="5" rows="8"></textarea>
</td>
</tr>
   
</table>






<table>

<tr>
<td style="width:165px;"></td>
<td><input type="submit" value="Generate Quotation" class="btn btn-warning">
<a href="../index.php?view=details&id=<?php echo $enquiry_form_id ?>"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>


</form>

       
</div>
<div class="clearfix"></div>


<script>

function generateProductDetails()
{

var sanket=document.getElementById('productDetails').innerHTML;
sanket=sanket.replace('style="display:none;"', '');
var mytbody=document.createElement('tbody');
mytbody.innerHTML=sanket;
$(mytbody).children().each(function(index, element) {
	if(index==1)
	{
		$(element).children().each(function(indexx, elementt) {
            
			if(indexx==1)
			{
				$(elementt).children().each(function(indexxx, elementtt) {
					
					if(indexxx==1)
					{
						elementtt.innerHTML="";
						$(elementtt).removeClass();
						}
					
				});
				
				}
			
        });
		
		}
   
});
pTable.appendChild(mytbody);


$(function() {
    $( ".combobox" ).combobox();
   
  });
  

}

function removeThisProduct(spanRemoveLink)
{
	var tbody=$(spanRemoveLink).parent().parent().parent();
	tbody=tbody[0];
	tbody.innerHTML="";
	}

</script>


<script>


function showUser(str,productEl) {


  if (str=="") {
        return;
  } 
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else { // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
	
      var mrp=parseInt(xmlhttp.responseText);
	  $(productEl).parent().parent().parent().next().find('input')[0].value=mrp;
    }
  }
  xmlhttp.open("GET","getPrice.php?q="+str,true);
  xmlhttp.send();
}
</script>


<script>
 
  
	  
 
  (function( $ ) {
    $.widget( "custom.combobox", {
      _create: function() {
        this.wrapper = $( "<span>" )
          .addClass( "custom-combobox" )
          .insertAfter( this.element );
 
        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();
      },
 
      _createAutocomplete: function() {
        var selected = this.element.children( ":selected" ),
          value = selected.val() ? selected.text() : "";
 
        this.input = $( "<input>" )
          .appendTo( this.wrapper )
          .val( value )
          .attr( "title", "" )
          .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
          .autocomplete({
            delay: 0,
            minLength: 0,
            source: $.proxy( this, "_source" )
          })
          .tooltip({
            tooltipClass: "ui-state-highlight"
          });
 
        this._on( this.input, {
          autocompleteselect: function( event, ui ) {
			  showUser(ui.item.option.value,ui.item.option);
            ui.item.option.selected = true;
            this._trigger( "select", event, {
              item: ui.item.option
            });
          },
 
          autocompletechange: "_removeIfInvalid"
        });
      },
 
      _createShowAllButton: function() {
        var input = this.input,
          wasOpen = false;
 
        $( "<a>" )
          .attr( "tabIndex", -1 )
          .attr( "title", "Show All Items" )
          .tooltip()
          .appendTo( this.wrapper )
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "custom-combobox-toggle ui-corner-right" )
          .mousedown(function() {
            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
          })
          .click(function() {
            input.focus();
 
            // Close if already visible
            if ( wasOpen ) {
              return;
            }
 
            // Pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );
          });
      },
 
      _source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
        response( this.element.children( "option" ).map(function() {
          var text = $( this ).text();
          if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text,
              option: this
            };
        }) );
      },
 
      _removeIfInvalid: function( event, ui ) {
 
        // Selected an item, nothing to do
        if ( ui.item ) {
			
          return;
        }
 
        // Search for a match (case-insensitive)
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
        this.element.children( "option" ).each(function() {
          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
            this.selected = valid = true;
            return false;
          }
        });
 
        // Found a match, nothing to do
        if ( valid ) {
			
          return;
        }
 
        // Remove invalid value
        this.input
          .val( "" )
          .attr( "title", value + " didn't match any item" )
          .tooltip( "open" );
        this.element.val( "" );
        this._delay(function() {
          this.input.tooltip( "close" ).attr( "title", "" );
        }, 2500 );
        this.input.data( "ui-autocomplete" ).term = "";
		
		
      },
 
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });
  })( jQuery );
 
  $(function() {
    $( ".combobox" ).combobox();
   
  });
  
 
   	  
  </script>
  
 <script>tinymce.init({ selector:'.richtextarea' });</script>

