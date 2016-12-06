<?php
if(!isset($_GET['id']))
{
	header("Location: index.php");
}
	
$customer_id=$_GET['id'];
if(is_numeric($customer_id))
{
$customerDetails = getCustomerById($customer_id);
$contact_nos = getCustomerContactNo($customer_id);
}
$customer_type_refernce_ids = listReferenceCustomerTypeIDString();
?>

<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add a New Enquiry</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post" enctype="multipart/form-data">

<?php if(is_numeric($customer_id) && isset($customerDetails['customer_name'])) { ?>
<input type="hidden" name="customer_id" value="<?php echo $customer_id ?>"  />
<?php } ?>


<hr class="firstTableFinishing" />

<h4 class="headingAlignment no_print">Customer Details</h4>
<table id="insertCustomerTable" class="insertTableStyling no_print">

<tr>

<td width="220px" class="firstColumnStyling">
Enquiry Date<span class="requiredField">* </span> : 
</td>

<td> 
<input type="text" name="enquiry_date" id="enquiry_date" placeholder="Enquiry Date" <?php if(!isset($_GET['status'])) { ?> readonly="readonly" <?php } ?>   value="<?php echo date('d/m/Y', strtotime(getTodaysDate())); ?>" />

</td>
</tr>

<?php
if(SHOW_PREFIX == 1)
{
?>
<tr>
<td> Customer Prefix : </td>
				<td>
					<select  name="prefix_id">
                        <!--<option value="0"> --Please Select-- </option>-->
                        <?php
                            $prefixes = listPrefix();
							
                            foreach($prefixes as $prefix)
                              {
                             ?>
                             
                           <option value="<?php echo $prefix['prefix_id'] ?>"><?php echo $prefix['prefix'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>
<?php
}
?>

<tr>

<td width="220px" class="firstColumnStyling">
Customer's Name<span class="requiredField">* </span> : 
</td>

<td> 
<input type="text" name="customer_name"  autofocus="autofocus" id="customer_name" class="customer_name" placeholder="Only Letters" <?php if(is_numeric($customer_id) && isset($customerDetails['customer_name'])) { ?> value="<?php echo $customerDetails['customer_name']; ?>" disabled="disabled" <?php } ?>/>

</td>
</tr>


<?php  if(is_numeric($customer_id) && isset($contact_nos[0][0]) && is_numeric($contact_nos[0][0])) { 

foreach($contact_nos as $contact_no)
{
?>


<tr id="addcontactTrCustomer">
                <td>
                Contact No<span class="requiredField">* </span> : 
                </td>
                
                <td id="addcontactTd">
                <input type="text" class="contact" id="customerContact" name="mobile_no[]" placeholder="more than 6 Digits!" disabled="disabled" value="<?php echo $contact_no[0]; ?>" /> 
                </td>
            	</tr>
                

<?php } }else { ?>



 
 <tr id="addcontactTrCustomer">
                <td>
                Contact No<span class="requiredField">* </span> : 
                </td>
                
                <td id="addcontactTd">
                <input type="text" class="contact" id="customerContact" name="mobile_no[]" placeholder="more than 6 Digits!" onchange="searchForDuplicateMobile()" /> <span class="addContactSpan"><input type="button" title="add more contact no" value="+" class="btn btn-success addContactbtnCustomer"/></span><span class="ValidationErrors contactNoError">Please enter a valid Phone No (only numbers)</span>
                </td>
            	</tr>
                

<!-- for regenreation purpose Please donot delete -->
            
            <tr id="addcontactTrGeneratedCustomer">
            <td>
            Contact No : 
            </td>
            
            <td id="addcontactTd">
            <input type="text" class="contact" name="mobile_no[]" onblur="checkContactNo(this.value,this)" placeholder="more than 6 Digits!" onchange="searchForDuplicateMobile()" />  <span class="deleteContactSpan"><input type="button" value="-" title="delete this entry"  class="btn btn-danger deleteContactbtn" onclick="deleteContactTr(this)"/></span><span class="ValidationErrors contactNoError">Please enter a valid Phone No (only numbers)</span>
                </td>
            </td>
            </tr>
       
       
<!-- end for regenreation purpose -->

<tr style="display:none" id="dup_contact_no">
<td></td>
<td><a id="dup_a_href" style="font-size:12px;text-decoration:underline;cursor:pointer;color:#00C;" href="">Duplicate Customer Found</a></td>
</tr>
<?php } ?>
<?php  if(is_numeric($customer_id) && $customerDetails['customer_email']) { ?>

<tr>

<td width="220px" class="firstColumnStyling">
Email Address : 
</td>

<td>
<input type="text" id="email" name="email_id"  placeholder="Only Letters!" value="<?php echo $customerDetails['customer_email']; ?>" disabled="disabled" />
</td>
</tr>


<?php }else { ?>

<tr>

<td width="220px" class="firstColumnStyling">
Email Address : 
</td>

<td>
<input type="text" id="email" name="email_id"  placeholder="Only Letters!" onchange="searchForDuplicateEmail()"/>
</td>
</tr>

<?php } ?>

<tr style="display:none;">
<td width="130px" class="firstColumnStyling"> City : </td>
<td>
					<select id="city" name="city">
                        <option value="-1" >-- Select The City --</option>
                        <?php
                            $cities = listCities();
                            foreach($cities as $city)
                              {
                             ?>
                             
                             <option value="<?php echo $city['city_id'] ?>" <?php if($city['city_id'] ==DEF_CITY_ID) { ?> selected="selected" <?php } ?>><?php echo $city['city_name'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
</td>
</tr>

<?php
if(SHOW_AREA == 1)
{
?>
<tr>
<td>Area<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="customer_area" class="city_area" id="city_area1" placeholder="Only Letters" />
                            </td>
</tr>

<?php
}
?>

<?php
if(SHOW_KM == 1)
{
?>
<tr>
<td>KM<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="km" class="km" id="km" placeholder="Only Digits" />
                            </td>
</tr>

<?php
}
?>

</table>

<h4 class="headingAlignment no_print">Interested <?php echo PRODUCT_GLOBAL_VAR; ?> Details </h4> 

<table id="pTable" class="insertTableStyling no_print">

<tbody id="productDetails">

<tr style="display:none;">

<td></td>

<td>
<span class="removeLink" onclick="removeThisProduct(this);"> Remove This <?php echo PRODUCT_GLOBAL_VAR; ?> </span>
</td>

</tr>

<tr>
<td><?php echo PRODUCT_GLOBAL_VAR; ?><span class="requiredField">* </span> : </td>
				<td>
                
	<select  name="product_id[]"  class="combobox product" multiple="multiple" onchange="createAttributeDropDown(this.value,this)">
    					<option value=""></option>
                        <?php
                            $subCategories = listSubCategories();
                            foreach($subCategories as $subCategory)
                              {
								  $category = getCategoryBySubCategoryId($subCategory['sub_cat_id']);
								  $superCategory = getSuperCategoryBySubCategoryId($subCategory['sub_cat_id']);
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
<input type="text" name="mrp[]"  class="mrp" placeholder="Only Digits"/>

</td>

				<td>
					<select  name="unit_id[]">
                        
                        <?php
                            $units = listUnits();
                            foreach($units as $unit)
                              {
                             ?>
                             
                           <option value="<?php echo $unit['unit_id'] ?>"><?php echo $unit['unit_name'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
               </td>
</tr>
<?php if(defined('SHOW_QUANTITY') && SHOW_QUANTITY==1) { 
if(defined('QUANTITY_BOX') && QUANTITY_BOX==0)
{
?>
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

<?php }
else
{ ?>
<tr>
<td> <?php echo QUANTITY_GLOBAL_VAR; ?> : </td>
				<td>
				<input type="text" name="quantity_id[]" id="quantity_id" class="quantity" value="1" />
                            </td>
</tr>


<?php	
}

}else { ?>
<tr>
<td colspan="2">
<input hidden="quantity_id" value="1" />
</td>
</tr>
<?php } ?>
<tr>
<td><hr class="firstTableFinishing" /></td>
<td><hr class="firstTableFinishing" /></td>
</tr>


</tbody>


</table>

<table style="margin-top:10px;margin-bottom:10px;">
<tr>
<td width="250px;">  </td>
<td><input type="button" class="btn btn-success" value="+ Add Another <?php echo PRODUCT_GLOBAL_VAR; ?>" id="addAnotherProductBtn" onclick="generateProductDetails()"/></td>
</tr>     
</table>

<h4 class="headingAlignment no_print">Lead Enquiry Details</h4>

<table id="insertCustomerTable" class="insertTableStyling no_print">

<tr>
<td> Add To Enquiry Group <span class="requiredField">* </span>: </td>
				<td>
					<select id="bs3Select" name="enquiry_group_id[]" class="selectpic show-tick form-control" multiple data-live-search="true">
                       
                        <?php
                            $enquiryGroups = listEnquiryGroups();
                            foreach($enquiryGroups as $enquiryGroup)
							
                              {
								 
                             ?>
                             
                             <option value="<?php echo $enquiryGroup['enquiry_group_id'] ?>" 
							 <?php if(in_array($enquiryGroup['enquiry_group_name'], $selected_enquiry_group_name_array)) { ?> selected="selected" <?php } ?>> <?php echo $enquiryGroup['enquiry_group_name'] ?>
                             
                             </option>
                             <?php 
							 } 
							 ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>

<td width="220px" class="firstColumnStyling">
Customer Budget : 
</td>

<td>
<input type="text" id="budget" name="budget"  placeholder="Only Digits!"/>
</td>
</tr>

<tr>
<td width="220px" class="firstColumnStyling"> Enquiry Type : </td>
<td>
					<select id="customer_type" name="customer_type_id" onchange="isEnquiryTypeRefrence(this.value)" class="selectpic show-tick form-control">
                        <option value="-1" >-- Select Enquiry Type --</option>
                        <?php
                            $customerTypes = listCustomerTypes();
                            foreach($customerTypes as $customerType)
                              {
                             ?>
                             
                             <option value="<?php echo $customerType['customer_type_id'] ?>"><?php echo $customerType['customer_type'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
</td>
</tr>
</table>

<table id="refrenceTable" class="insertTableStyling no_print" style="display:none;">

<tr>

<td width="220px" class="firstColumnStyling">
Refrence Name : 
</td>

<td>
<input type="text" id="refrence_name" name="refrence"  placeholder="Only Letters!"/>
</td>

</tr>

</table>

<table class="insertTableStyling no_print">

<tr>
<td width="220px" class="firstColumnStyling"> 
Discussion : 
</td>

<td>
<textarea id="discussion" class="discussion" name="discussion"  cols="5" rows="6"></textarea>
</td>
</tr>




<tr>

<td class="firstColumnStyling">
Follow Up Date <span class="requiredField">* </span>: 
</td>

<td>
<input type="text" id="reminder_date" size="12" autocomplete="off"  name="reminder_date" class="datepicker1 datepick reminder_date" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span> 
</td>
</tr>


<tr>

<tr>

<td class="firstColumnStyling">
Time <span class="requiredField">* </span>: 
</td>

<td>
<div class="demo">
                
                <p>
                    <input id="setTimeExample" type="text" class="time"  name="reminder_time"/>
                    
                </p>
            </div>

            <script>
                $(function() {
                    $('#setTimeExample').timepicker({
						'timeFormat': 'H:i:s',
        'minTime': '08:00:00',
		'maxTime': '00:00:00',
		'disableTextInput': true,
		'scrollDefault' : '16:00:00',
		
		
        
    });
                    $('#setTimeButton').on('click', function (){
                        $('#setTimeExample').timepicker('setTime', new Date());
                    });
                });
            </script>

            
</td>
</tr>



<tr>
<td> Send SMS? <span class="requiredField">* </span>: </td>
				<td>
					<select id="sms_status" name="sms_status">
                    
                    <option value="1"> Yes </option>
                    <option value="0"> No </option>        
                              
                    </select> 
                     
                </td>
</tr>

</table>



<table>
<tr>
<td width="250px"></td>
<td>
<input type="submit" value="Add Enquiry" class="btn btn-warning">
<!-- onclick="this.disabled=true;this.value='Processing, please wait...';this.form.submit();" -->
</td>
</tr>
</table>
</form>

</div>

<div class="clearfix"></div>

<script>
document.customer_refernce_types = "<?php echo $customer_type_refernce_ids ?>";
 $( "#city_area1" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/city_area.php',
                { term: request.term, city_id:$('#city').val() }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#city_area1" ).val(ui.item.label);
			return false;
		}
    });
</script>	

<script>
<?php if(isset($_GET['status'])) { ?>
$( "#enquiry_date" ).datepicker({
      changeMonth: true,
      changeYear: true,
	   dateFormat: 'dd/mm/yy'
    });
  <?php } ?>

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


function searchForDuplicateMobile()
{
	
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

<script>
$( "#refrence_name" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/refrence_name.php',
                { term: request.term }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#refrence_name" ).val(ui.item.label);
			return false;
		}
    });	
</script>
  
 