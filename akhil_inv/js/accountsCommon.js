// JavaScript Document

$('#periodModal').on('show.bs.modal', function () {
  document.getElementById('periodModal').style.zIndex=1050;
});
$('#periodModal').on('hidden.bs.modal', function () {
  document.getElementById('periodModal').style.zIndex=-1050;
});

$('#currentDateModal').on('show.bs.modal', function () {
  document.getElementById('currentDateModal').style.zIndex=1050;
});
$('#currentDateModal').on('hidden.bs.modal', function () {
  document.getElementById('currentDateModal').style.zIndex=-1050;
});
$('#change_ac_settings').click(function(e) {
    $('#periodModal').modal('show');
});

jQuery(document).bind("keyup keydown", function(e){
   });
	
jQuery(document).bind("keyup keydown", function(e){
    if(e.altKey && e.keyCode == 113){
		$('#currentDateModal').modal('show');
	}});
	
jQuery(document).bind("keyup keydown", function(e){
	 if(e.ctrlKey && e.keyCode == 113){
		if(document.disablePeriodModal==null)
		$('#periodModal').modal('show');
		else
		alert('Cannot Change Period, Date & Company On Edit Page!');
	}
	else
    if(e.keyCode == 113){
	 
		window.location.href = document.web_root+"admin/accounts/transactions/receipt/";
	}});	

jQuery(document).bind("keyup keydown", function(e){
 if( e.keyCode == 114){
	 e.preventDefault();
		window.location.href = document.web_root+"admin/accounts/transactions/payment/";
	}});
	
	jQuery(document).bind("keyup keydown", function(e){
 if( e.keyCode == 115){
	 e.preventDefault();
		window.location.href = document.web_root+"admin/accounts/transactions/jv/";
	}});
	
	jQuery(document).bind("keyup keydown", function(e){
 if( e.keyCode == 116){
	 e.preventDefault();
		window.location.href = document.web_root+"admin/accounts/transactions/contra/";
	}});		
	
	jQuery(document).bind("keyup keydown", function(e){
 if( e.keyCode == 117){
	 e.preventDefault();
		window.location.href = document.web_root+"admin/accounts/transactions/purchase_inventory/";
	}});
	
	jQuery(document).bind("keyup keydown", function(e){
 if( e.keyCode == 118){
	 e.preventDefault();
		window.location.href = document.web_root+"admin/accounts/transactions/delivery_challan/";
	}});	
	
	jQuery(document).bind("keyup keydown", function(e){
 if( e.keyCode == 119){
	 e.preventDefault();
		window.location.href = document.web_root+"admin/accounts/transactions/sales_inventory/";
	}});
	
	jQuery(document).bind("keyup keydown", function(e){
 if(e.altKey && e.keyCode == 76){
	 e.preventDefault();
		window.location.href = document.web_root+"admin/accounts/ledgers/index.php/";
	}});		
	
	jQuery(document).bind("keyup keydown", function(e){
 if(e.altKey && e.keyCode == 67){
	 e.preventDefault();
		window.location.href = document.web_root+"admin/customer/index.php/";
	}});
	
		jQuery(document).bind("keyup keydown", function(e){
 if(e.altKey && e.keyCode == 70){
	 	e.preventDefault();
		window.location.href = document.web_root+"admin/search/index.php/";
	}});		
	
	
function changeRefFeild(ref_type) // 0=new, 1 = advance, 2 = against purchase, 3 = on account
{

	if(ref_type==2)
	{
		var payment_date=$('#payment_date').val();
		var to_ledger = document.getElementById('to_ledger').value;
		
		createDropDownPaymentRef(document.web_root+'json/outstanding_sales.php?cid='+to_ledger+'&trans_date='+payment_date,'pay_ref_aganist')
	//	$('#pay_ref_new').hide();
		$('#pay_ref_against_tr').show();
		
	
		
		}
	else
	{
		
		removeData('pay_ref_aganist');
		$('#pay_ref_against_tr').hide();
    //	$('#pay_ref_new').show();
		}	
}	

function changeRefFeildMulti(ref_type_el) // 0=new, 1 = advance, 2 = against purchase, 3 = on account
{
	var ref_type = ref_type_el.value;
   
	var payment_date=$('#payment_date').val();
		var to_ledger_td=$(ref_type_el).parent().parent().prev().children()[1];
		
		var to_ledger = $(to_ledger_td).children()[1].value;
		
		var pay_ref_against_tr = $(ref_type_el).parent().parent().next().next()[0];
		var pay_ref_against_td = $(pay_ref_against_tr).children()[1];
		var pay_ref_against = $(pay_ref_against_td).children()[0];
		if(document.getElementById('lid'))
		var lid = document.getElementById('lid').value;
		
	if(ref_type==2)
	{
		 to_ledger = encodeURIComponent(to_ledger);
		var url = document.web_root+'json/outstanding_sales.php?cid='+to_ledger+'&trans_date='+payment_date+'&type=1';
	   
		
		if(document.getElementById('lid'))
		url = url + '&lid='+lid;
		
		//var to_ledger = document.getElementById('to_ledger').value;
		createDropDownPaymentRefMulti(url,pay_ref_against) // type = 1 means the cid is full ledger name instead of just the id
	//	$('#pay_ref_new').hide();
		
		$(pay_ref_against_tr).show();	
		}
	else
	{
		
		removeDataMulti(pay_ref_against);
		$(pay_ref_against_tr).hide();
    //	$('#pay_ref_new').show();
		}	
}	


function changeSalesPurchaseLedger(sales_ledger_el)
{
	
	sales_ledger_id = sales_ledger_el.value;
	tax_class_el = $(sales_ledger_el).next()[0];
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
	
// Before adding new we must remove previously loaded elements

removeDataFromEl(tax_class_el);

var plsOptn = document.createElement("OPTION");

plsOptn.value = -1;
plsOptn.text = "-- Vat/Tax Class --";

tax_class_el.options.add(plsOptn);

for (var i=0; i<myarray.length; i++)
{
	
var optn = document.createElement("OPTION");

optn.value = myarray[i];
optn.text = myarray[++i];

tax_class_el.options.add(optn);

} 
    }
  }
  
  xmlhttp1.open('GET',  document.web_root+"json/getTaxClassFromLedger.php?id="+sales_ledger_id, true );    
  xmlhttp1.send(null);
	
}

function changeTaxClass(tax_class_el)
{
	
	tax_class_id = tax_class_el.value;
	tax_el = $(tax_class_el).next()[0];
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
	
// Before adding new we must remove previously loaded elements

removeDataFromEl(tax_el);

var plsOptn = document.createElement("OPTION");

plsOptn.value = -1;
plsOptn.text = "-- Vat/Tax Class --";

tax_el.options.add(plsOptn);

for (var i=0; i<myarray.length; i++)
{
	
var optn = document.createElement("OPTION");

optn.value = myarray[i];
optn.text = myarray[++i];
optn.id = myarray[++i];
tax_el.options.add(optn);

} 
    }
  }
  
  xmlhttp1.open('GET',  document.web_root+"json/getTaxFromTaxClass.php?id="+tax_class_id, true );    
  xmlhttp1.send(null);
	
}


function removeDataFromEl(el){
	

	for(j=el.options.length-1;j>=0;j--)
	{	
	el.remove(j);
	}
	
}
