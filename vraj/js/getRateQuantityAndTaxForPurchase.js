// JavaScript Document
function getRateQuantityAndTaxForPurchaseFromItemId(item_id,item_option_el)
{

var godown_select = $(item_option_el).parent().next().next().next().next().next().next().next().children()[0];

	

if(godown_select && (godown_select.selectedIndex || godown_select.selectedIndex==0))
var godown_id = godown_select[godown_select.selectedIndex].value;
else
var godown_id=0;


var quantity_span = $(item_option_el).parent().next().children()[1];
var rate_input = $(item_option_el).parent().next().next().children()[0];
var tax_select = $(item_option_el).parent().next().next().next().next().next().children()[0];

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
	var item_type = myarray[3];
	
	if(item_type==0)
	{		
	rate_input = $(item_option_el).parent().next().children()[0];
 	tax_select = $(item_option_el).parent().next().next().next().children()[0];
	}
	
	var tax_options = tax_select.options;
	for(var i=0;i<tax_options.length;i++)
	{
		tax_option = tax_options[i];
		option_tax_group_id=tax_option.value;
		if(tax_group_id==option_tax_group_id)
		{
			tax_select.options[i].selected=true;
		}	
	}
	rate_input.value = mrp;
	
	
// Before adding new we must remove previously loaded elements
	if(item_type == 1)
	{
	
	quantity_span.innerHTML = quantity;
	onchangeRate(rate_input);	
	
	}
	else
	{
	  onchangeRateNS(rate_input);
	}
    
	
	
	}
  }

 xmlhttp1.open('GET', "getRateQuantityAndTaxForItem.php?id="+item_id+"&state="+godown_id, true );    
  xmlhttp1.send(null);
	
}

function getRateQuantityAndTaxForPurchaseFromGodwonId(godown_id,godown_select)
{	
var item_select = $(godown_select).parent().prev().prev().prev().prev().prev().prev().prev().children()[0];
var item_id = item_select[item_select.selectedIndex].value;
var quantity_span = $(godown_select).parent().prev().prev().prev().prev().prev().prev().children()[1];
var rate_input = $(godown_select).parent().prev().prev().prev().prev().prev().children()[0];

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