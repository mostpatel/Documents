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

jQuery(document).bind("keyup keydown", function(e){
    if(e.ctrlKey && e.keyCode == 113){
		$('#periodModal').modal('show');
	}});
	
jQuery(document).bind("keyup keydown", function(e){
    if(e.altKey && e.keyCode == 113){
		$('#currentDateModal').modal('show');
	}});	
	
function changeRefFeild(ref_type) // 0=new, 1 = advance, 2 = against purchase, 3 = on account
{

	if(ref_type==2)
	{
		var payment_date=$('#payment_date').val();
		var to_ledger = $('#to_ledger').val();
		createDropDownPaymentRef("");
		$('#pay_ref_new').hide();
		$('#pay_ref_against').show();
		}
	else
	{
		$('#pay_ref_against').hide();
		$('#pay_ref_new').show();
		}	
}	

function createDropDownPaymentRef(url,id,paymennt_date,to_ledger)  
{

var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp = new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }

  xmlhttp.onreadystatechange=function()                        
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
	
    var myarray=eval(xmlhttp.responseText);
	
// Before adding new we must remove previously loaded elements

removeData(id);
if(removeArray!=null)
{
for(var i=0;i<removeArray.length;i++)
{
	removeData(removeArray[i]);
	}
}

var plsOptn = document.createElement("OPTION");

plsOptn.value = -1;
plsOptn.text = "--Please Select--";

document.getElementById(id).options.add(plsOptn);

for (var i=0; i<myarray.length; i++)
{
	
var optn = document.createElement("OPTION");

optn.value = myarray[i];
optn.text = myarray[++i];

document.getElementById(id).options.add(optn);

}

    }
  }
  
  xmlhttp.open('GET', url, true );    
  xmlhttp.send(null);

}
