// JavaScript Document
function getLedgerOpeningBalance(ledger_id,select_el,credit_debit) // credit = 1, debit =0
{	
	
    datee=document.getElementById('payment_date').value;
	var amount = document.getElementById('amount').value;
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
		
	var span_id = select_el.id+"bal_span";
	
    var opening_balance=xmlhttp1.responseText;
	var td_con=$(select_el).parent()[0];

	
	if(!document.getElementById(span_id))
	{
	var bal_span = document.createElement("span");
	bal_span.id = span_id;
	}
	else
	{
	bal_span = document.getElementById(span_id);
	}
	bal_span.style.marginLeft="30px";
	bal_span.innerHTML = opening_balance;
	td_con.appendChild(bal_span);
// Before adding new we must remove previously loaded elements



 
    }
  }
  
  url = document.web_root+"json/currentBalanceForLedger.php?id="+ledger_id+"&date="+datee+'&amount='+amount+'&dc='+credit_debit;

  xmlhttp1.open('GET', url, true );    
  xmlhttp1.send(null);
}

function onChangeTransactionAmount(debit_ledger_id,credit_ledger_id)
{

	getLedgerOpeningBalance(document.getElementById(debit_ledger_id).value,document.getElementById(debit_ledger_id),0);
	getLedgerOpeningBalance(document.getElementById(credit_ledger_id).value,document.getElementById(credit_ledger_id),1);
	
}