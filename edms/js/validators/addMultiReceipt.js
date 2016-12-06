// JavaScript Document
                jQuery(".amount").validate({
                    expression: "if ( (VAL>0 && !isNaN(VAL)) || !VAL) return true; else return false;",
                    message: "Please Enter a valid amount!"
                });	
				jQuery("#amount").validate({
                    expression: "if ( (VAL>0 && !isNaN(VAL))) return true; else return false;",
                    message: "Please Enter a valid amount!"
                });		
				 jQuery("#remarks").validate({
                     expression: "if (VAL=='' || VAL) return true; else return false;",
                    message: "Omly Letters, Space and Dot(.) Allowed!"
                });
				jQuery("#by_ledger").validate({
                    expression: "if (VAL!=-1 && VAL!='') return true; else return false;",
                    message: "Please Select Ledger!"
                });
					jQuery("#to_ledger").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please Select Ledger!"
                });
	 $('#pay_ref_aganist').validate(
	 {
		  expression: "if ((VAL!=-1 && jQuery('#ref_type').val()==2) || jQuery('#ref_type').val()!=2) return true; else return false;",
           message: "Please Select Sales Aginst Receipt!"
		 
		}
	 
	 );
function submitMultiReceipt()
{
	
			
	var res=true;
	var amount_array=$(".amount");
	var to_ledger_id_array=$(".to_ledger");
	var ref_type_array=$(".ref_type");
	var pay_ref_aganist_array=$(".pay_ref_aganist");
	
	
	for(var i=1;i<amount_array.length;i++)
	{
	var amount = amount_array[i].value;	
	if(!isNaN(parseFloat(amount)))
	amount = parseFloat(amount);
	
	var to_ledger_id=to_ledger_id_array[i].value;
	var ref_type=ref_type_array[i].value;
	var pay_ref_aganist=pay_ref_aganist_array[i].value;
	if((amount!="") || (to_ledger_id.trim()!=""))
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
		
		
		if(ref_type==2 && pay_ref_aganist==-1)
		{
		$(pay_ref_aganist_array[i]).addClass("ErrorField");
		res= false;
		}
		else
		$(pay_ref_aganist_array[i]).removeClass("ErrorField");
		
		
	}
		
	}
		
		return res;
	}				 
