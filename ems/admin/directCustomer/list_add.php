<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add a New Customer</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post" enctype="multipart/form-data" onsubmit="return submitOurCompany()">



<hr class="firstTableFinishing" />

<h4 class="headingAlignment no_print">Customer Details</h4>
<table id="insertCustomerTable" class="insertTableStyling no_print">


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

<tr>

<td width="220px" class="firstColumnStyling">
Customer's Name<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="customer_name" id="customer_name" class="customer_name" placeholder="Only Letters"/>

</td>
</tr>







 
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


<tr>

<td width="220px" class="firstColumnStyling">
Email Address : 
</td>

<td>
<input type="text" id="email" name="email_id"  placeholder="Only Letters!" onchange="searchForDuplicateEmail()"/>
</td>
</tr>



</table>



<table>
<tr>
<td width="250px"></td>
<td>
<input type="submit" value="Add Customer" class="btn btn-warning">
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
  
 