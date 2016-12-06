<?php
$enquiry_id=$_GET['lid'];
$products=getSubCatFromEnquiryId($enquiry_id);
?>

<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit <?php echo PRODUCT_GLOBAL_VAR; ?> Details</h4>
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
<form action="<?php echo $_SERVER['PHP_SELF'].'?action=editProducts'; ?>" method="post" >


<table id="pTable" class="insertTableStyling no_print">
<input type="hidden" name="lid" value="<?php echo $enquiry_id; ?>" />
<tbody style="display:none;" id="productDetails">

<tr style="display:none;">

<td></td>

<td>
<span class="removeLink" onclick="removeThisProduct(this);"> Remove This <?php echo PRODUCT_GLOBAL_VAR; ?> </span>
</td>

</tr>

<tr>
<td><?php echo PRODUCT_GLOBAL_VAR; ?><span class="requiredField">* </span> : </td>
				<td>
                
					<select  name="product[]"  class="combobox editProduct" onchange="createAttributeDropDown(this.value,this)">
    					<option value=""></option>
                        <?php
                            $subCategories = listSubCategories();
                            foreach($subCategories as $subCategory)
                              {
								  $category = getCategoryBySubCategoryId($subCategory['sub_cat_id']);
                             ?>
                             
                             <option value="<?php echo $subCategory['sub_cat_id'] ?>"><?php echo $subCategory['sub_cat_name'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
                          
                            </td>
</tr>

<tr>

<td width="220px" class="firstColumnStyling">
MRP <span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="mrp[]"  class="editMRP" placeholder="Only Digits"/>

</td>

<td><select  name="unit_id[]">
                        
                        <?php
                            $units = listUnits();
                            foreach($units as $unit)
                              {
                             ?>
                             
                           <option value="<?php echo $unit['unit_id'] ?>" <?php if($unit_id == $unit['unit_id']) { ?> selected="selected" <?php } ?>><?php echo $unit['unit_name'] ?></option>
                             <?php } ?>
                              
                         
                            </select> </td>

</tr>


<?php if(defined('SHOW_QUANTITY') && SHOW_QUANTITY==1) { ?>
<tr>
<td> <?php echo QUANTITY_GLOBAL_VAR; ?> : </td>
				<td>
					<select  name="quantity_id[]">
                        
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
<?php }?>

<tr>
<td><hr class="firstTableFinishing" /></td>
<td><hr class="firstTableFinishing" /></td>
</tr>


</tbody>
<?php foreach($products as $product)
{
	$sub_cat_id=$product['sub_cat_id'];
	$price=$product['customer_price'];
	$quantity_id=$product['quantity_id'];
	$unit_id=$product['product_unit_id'];
	 ?>
<tbody>

<tr>

<td></td>

<td>
<span class="removeLink" onclick="removeThisProduct(this);"> Remove This <?php echo PRODUCT_GLOBAL_VAR; ?> </span>
</td>

</tr>

<tr>
<td><?php echo PRODUCT_GLOBAL_VAR; ?><span class="requiredField">* </span> : </td>
				<td>
                
					<select  name="product[]"  class="combobox EditProduct" >
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

<td width="220px" class="firstColumnStyling">
MRP <span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="mrp[]"  class="editProduct" value="<?php echo $price; ?>" placeholder="Only Digits"/>

</td>

<td>
					<select  name="unit_id[]">
                        
                        <?php
                            $units = listUnits();
                            foreach($units as $unit)
                              {
                             ?>
                             
                           <option value="<?php echo $unit['unit_id'] ?>" <?php if($unit_id == $unit['unit_id']) { ?> selected="selected" <?php } ?>><?php echo $unit['unit_name'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
               </td>


</tr>

<?php if(defined('SHOW_QUANTITY') && SHOW_QUANTITY==1) { ?>
<tr>
<td><?php echo QUANTITY_GLOBAL_VAR; ?>: </td>
				<td>
					<select  name="quantity_id[]">
                        
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
<?php
}
?>

<tr>
<td><hr class="firstTableFinishing" /></td>
<td><hr class="firstTableFinishing" /></td>
</tr>


</tbody>
<tbody class="attributeTbody">
<?php 
$all_attribute_type_array =getAttributesFromSubCatId($sub_cat_id);
if($all_attribute_type_array)
{
	foreach($all_attribute_type_array as $all_attribute_type)
{
	$attribute_type = $all_attribute_type['attribute_type'];
	$names_ids_array =  explode(',',$attribute_type_names['attribute_name_ids_string']);
	$selected_attribute_names_array=getAttributeNamesForASubCatOfAnEnquiryForAnAttributeType($sub_cat_id,$enquiry_id,				$attribute_type['attribute_type_id']);
	if($selected_attribute_names_array)
	$selected_name_ids_array = explode(',',$selected_attribute_names_array['attribute_name_ids_string']);
	else
	$selected_name_ids_array = false;

	$all_names_array=$all_attribute_type['attribute_name'];
?>
<tr>
<td class="firstColumnStyling">
<?php  echo  $attribute_type['attribute_type']. " : ";	?> 
</td>
  
<td>
 <select name="attribute_name_array[<?php echo $sub_cat_id;  ?>][<?php echo $attribute_type['attribute_type_id']; ?>][]"  class="selectpic show-tick form-control" multiple="multiple" data-live-search="true">
 		<?php foreach($all_names_array as $attribute_name) { ?>	
        <option value="<?php echo $attribute_name['attribute_name_id'] ?>" <?php if(in_array($attribute_name['attribute_name_id'],$selected_name_ids_array)) { ?> selected="selected" <?php } ?>><?php echo $attribute_name['attribute_name']; ?></option>
        <?php } ?>
 </select>                       			
                          
</td>
</tr>
<?php	
	}}
 ?>
</tbody>
<?php } ?>


</table>


<table style="margin-top:10px;margin-bottom:10px;">
<tr>
<td width="250px;">  </td>
<td><input type="button" class="btn btn-success" value="+ Add Another Product" id="addAnotherProductBtn" onclick="generateProductDetails()"/></td>
</tr>     
</table>


<table>
<tr>
<td width="250px"></td>
<td>
<input type="submit" value="Save" class="btn btn-warning">

<a href="<?php echo WEB_ROOT."admin/customer/index.php?view=details&id=".$enquiry_id ?>">
<input type="button" value="back" class="btn btn-success" />
</a>
</td>
</tr>
</table>
</form>

</div>

<div class="clearfix"></div>

<script>
  $('.selectpic').selectpicker({
                
            });
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
	var product_tbody_next=$(tbody).next('.attributeTbody')[0];
	if(product_tbody_next)
	{
		product_tbody_next.innerHTML="";
	}
}

</script>


<script>

function searchForDuplicateEmail()
{
	
	var emailAddress = document.getElementById('email').value;
	
	if(emailAddress!="")
	checkForDuplicateEmail(emailAddress);
	
}

function checkForDuplicateEmail(emailAddress)
{
	if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else { // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
	
      var customer_id=parseInt(xmlhttp.responseText);
	  
	  if(!isNaN(customer_id) && customer_id>0)
	  {
		  $('#dup_contact_no').show();
		  var hreff="<?php echo WEB_ROOT ?>admin/customer/index.php?view=customerDetails&id="+customer_id;
		 
		  document.getElementById('dup_a_href').setAttribute('href',hreff);
		  }
		else
		 {
			 $('#dup_contact_no').hide();
			 
		 } 

    }
  }
   
   
  var url="<?php echo WEB_ROOT ?>json/exact_email.php?q="+emailAddress;
 
  xmlhttp.open("GET",url,true);
  xmlhttp.send();
	
}


function searchForDuplicateMobile(){
	
	 var contact_str="";
	$('.contact').each(function(index, element) {
     
	
	 if(!isNaN(parseInt(element.value)))
	 {
		 var con_no=parseInt(element.value);
		 contact_str=contact_str+con_no+",";
		 }
	
	
    });
	if(contact_str!="")
	checkForDuplicateCustomer(contact_str);
	
}

function checkForDuplicateCustomer(multi_contact_str)
{
	if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else { // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
	
      var customer_id=parseInt(xmlhttp.responseText);
	  
	  if(!isNaN(customer_id) && customer_id>0)
	  {
		  $('#dup_contact_no').show();
		  var hreff="<?php echo WEB_ROOT ?>admin/customer/index.php?view=customerDetails&id="+customer_id;
		
		  document.getElementById('dup_a_href').setAttribute('href',hreff);
		  }
		else
		 {
			 $('#dup_contact_no').hide();
			 
			 } 

    }
  }
   
   
	var url="<?php echo WEB_ROOT ?>json/exact_mobile_no.php?q="+multi_contact_str;
  xmlhttp.open("GET",url,true);
  xmlhttp.send();
	
	}

function showUser(str,productEl) {


  if (str=="") {
    document.getElementById("mrp").innerHTML="";
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
			  createAttributeDropDown(ui.item.option.value,ui.item.option);
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
  
<!--<script>

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
  
 -->