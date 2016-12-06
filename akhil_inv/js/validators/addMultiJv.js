// JavaScript Document
             
				 jQuery("#remarks").validate({
                     expression: "if (VAL=='' || VAL) return true; else return false;",
                    message: "Only Letters, Space and Dot(.) Allowed!"
                });
				jQuery("#payment_date").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please Select Date!"
                });
function submitMultiJV()
{
	var res=true;
	var amount_array=$(".to_ledger_amount");
	var to_ledger_id_array=$(".to_ledger");
	
	var from_amount_array=$(".from_ledger_amount");
	var from_ledger_id_array=$(".from_ledger");
	
	for(var i=0;i<(amount_array.length-1);i++)
	{	
	var amount = amount_array[i].value;	
   
	if(!isNaN(parseFloat(amount)))
	amount = parseFloat(amount);

	var to_ledger_id=to_ledger_id_array[i].value;
	if((amount=="") || (to_ledger_id.trim()==""))
	{
		
		if(amount=="" || isNaN(amount))
		{
		$(amount_array[i]).addClass("ErrorField");
		res= false;
		}
		else
		$(amount_array[i]).removeClass("ErrorField");
		
		
		if(to_ledger_id.trim()=="")
		{
		$(to_ledger_id_array[i]).addClass("ErrorField");
		res= false;
		}
		else
		$(to_ledger_id_array[i]).removeClass("ErrorField");
	}
		
	}
	
	for(var j=0;j<(from_amount_array.length-1);j++)
	{

	var amount = from_amount_array[j].value;	
	if(!isNaN(parseFloat(amount)))
	amount = parseFloat(amount);
	
	
	var to_ledger_id=from_ledger_id_array[j].value;
	
	if((amount=="") || (to_ledger_id.trim()==""))
	{
		
		if(amount=="" || isNaN(amount))
		{
		$(from_amount_array[j]).addClass("ErrorField");
		res= false;
		}
		else
		$(from_amount_array[j]).removeClass("ErrorField");
		
		
		if(to_ledger_id.trim()=="")
		{
		$(from_ledger_id_array[j]).addClass("ErrorField");
		res= false;
		}
		else
		$(from_ledger_id_array[j]).removeClass("ErrorField");
	}
		
	}
	
		
		return res;
	}				 
