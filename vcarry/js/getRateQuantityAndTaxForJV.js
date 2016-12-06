// JavaScript Document
function getRateQuantityAndTaxForSalesFromItemId(item_id,item_option_el)
{		
var godown_select = $(item_option_el).parent().next().children()[0];

if(godown_select.selectedIndex || godown_select.selectedIndex==0)
var godown_id = godown_select[godown_select.selectedIndex].value;
else
var godown_id=0;

var quantity_span = $(item_option_el).parent().next().next().children()[1];

var rate_input = $(item_option_el).parent().next().next().next().children()[0];


	var xmlhttp1;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp1 = new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
  }

  xmlhttp1.onreadystatechange=function()                        
  {
  if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
    {
    var myarray=eval(xmlhttp1.responseText);
	
	var quantity = myarray[0];
	var mrp = myarray[1];
	
	
	rate_input.value = mrp;
	
	
// Before adding new we must remove previously loaded elements
	
	quantity_span.innerHTML = quantity;
	onchangeRate(rate_input);	
	}
  }

 xmlhttp1.open('GET', "getRateQuantityAndTaxForItem.php?id="+item_id+"&state="+godown_id, true );    
  xmlhttp1.send(null);
	
}

function getRateQuantityAndTaxForSalesFromGodwonId(godown_id,godown_select)
{	
var item_select = $(godown_select).parent().prev().children()[0];
var item_id = item_select[item_select.selectedIndex].value;
var quantity_span = $(godown_select).parent().next().children()[1];
var rate_input = $(godown_select).parent().next().next().children()[0];

	var xmlhttp1;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp1 = new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
  }

  xmlhttp1.onreadystatechange=function()                        
  {
  if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
    {
    var myarray=eval(xmlhttp1.responseText);
	var quantity = myarray[0];
	var mrp = myarray[1];
	var tax_group_id = myarray[2];
// Before adding new we must remove previously loaded elements
	quantity_span.innerHTML = quantity;
    }
  }
  
 xmlhttp1.open('GET', "getRateQuantityAndTaxForItem.php?id="+item_id+"&state="+godown_id, true );    
  xmlhttp1.send(null);
	
}