// JavaScript Document
/* if($('#mode').is(':checked'))
{
	$('#chequePaymentTable').show();
	}
$('.chequePaymentTable').hide(); */
					
function submitTransaction(check_rate) //0=no, 1=yes
{
	
			
	var res = true;
			
	var item_array	=	$('.inventory_item_autocomplete');
	var qty_array	=	$('.item_quantity');
	var rate_array	=	$('.item_rate');	
	if(item_array.length<rate_array.length)
	{
	var len=rate_array.length;	
	rate_array=rate_array.slice(1,len);
	qty_array=qty_array.slice(1,len);
	
	}
	var item_ns_array = $('.inventory_ns_item_autocomplete');
	var rate_ns_array = $('.item_ns_rate');
	var valid_product =0;	
	
	if(item_ns_array.length<rate_ns_array.length)
	{
		
	var len_ns=rate_ns_array.length;	
	rate_ns_array=rate_ns_array.slice(1,len);
	}
	for(var i=0;i<(item_array.length);i++)
	{	
	
		if(check_rate==1)
		{
		var rate = rate_array[i].value;	
	 
		if(!isNaN(parseFloat(rate)))
		rate = parseFloat(rate);
		}
		else 
		rate=0;
	
		var qty = qty_array[i].value;	
	   
		if(!isNaN(parseFloat(qty)))
		qty = parseFloat(qty);

		var item_name = item_array[i].value;
		
		if((item_name.trim()!="") || i==0)
		{
			
			if(check_rate==1)	
			{
				
				if(rate=="" || isNaN(rate) || (!isNaN(rate) && rate<1))
				{	
				alert('Please Enter Rate');
				$(rate_array[i]).add("ErrorField");
				$(rate_array[i]).focus();
				res= false;
				}
				else
				$(rate_array[i]).removeClass("ErrorField");
			}
			
			if(qty=="" || isNaN(qty) || (!isNaN(qty) && qty<1))
			{
			alert('Please Enter Qty');	
			$(qty_array[i]).addClass("ErrorField");
			$(qty_array[i]).focus();
			res= false;
			}
			else
			$(qty_array[i]).removeClass("ErrorField");
			
			
			if(item_name.trim()=="")
			{
			alert('Please Enter Item Name');		
			$(item_array[i]).addClass("ErrorField");
			$(item_array[i]).focus();
			res= false;
			}
			else
			$(item_array[i]).removeClass("ErrorField");
			
		}
	}

		return res;
}					 
