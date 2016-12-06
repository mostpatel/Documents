// JavaScript Document
/* if($('#mode').is(':checked'))
{
	$('#chequePaymentTable').show();
	}
$('.chequePaymentTable').hide(); */
				
				 jQuery("#remarks").validate({
                     expression: "if (VAL=='' || VAL) return true; else return false;",
                    message: "Omly Letters, Space and Dot(.) Allowed!"
                });
				jQuery("#to_ledger").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please Select Ledger!"
                });
				
				jQuery("#from_ledger").validate({
                    expression: "if (VAL!=-1 && VAL!='') return true; else return false;",
                    message: "Please Select Ledger!"
                });
				
				jQuery("#retail_tax").validate({
                    expression: "if (VAL!=-1 && VAL!='') return true; else return false;",
                    message: "Please Select Invoice Type!"
                });
				
				jQuery("#payment_date").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please Select Date!"
                });
			
				jQuery("#invoice_no").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter Invoice No"
                });
				jQuery("#invoice_no").validate({
                    expression: "if (!isNaN(VAL)) return true; else return false;",
                    message: "Only digits allowed!"
                });
	 $('#pay_ref_aganist').validate(
	 {
		  expression: "if ((VAL!=-1 && jQuery('#ref_type').val()==2) || jQuery('#ref_type').val()!=2) return true; else return false;",
           message: "Please Select Sales Aginst Receipt!"
		 
		}
	 
	 );
	 
	            jQuery("#combobox").validate({
                    expression: "if (VAL!=-1 && VAL!='') return true; else return false;",
                    message: "Please Select Ledger!"
                });
				
				 jQuery("#combobox2").validate({
                    expression: "if (VAL!=-1 && VAL!='') return true; else return false;",
                    message: "Please Select Ledger!"
                });
function submitPayment()
{
	
			
	var res=true;
	var date1=$(".datepicker1").val();
	var date2=$(".datepicker2").val();
	var date3=$(".datepicker3").val();
	
	var to_ledger=$("#to_ledger").val();

	var amount=$('#amount').val();
	
		dateFormat=/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/g;
		
		if(dateFormat.test(date1))
		{
			
			$(".datepicker1").next().hide();
			$(".datepicker1").removeClass("ErrorField");
			res=res && true;
			
			}
		else
		{
			$(".datepicker1").next().show();
			$(".datepicker1").addClass("ErrorField");
			res=res && false;
			}
		
		if((date2!=null && date2!=""))
		{
		dateFormat=/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/g;
			
		if(dateFormat.test(date2))
		{
			$(".datepicker2").next().hide();
			$(".datepicker2").removeClass("ErrorField");
			res=res && true;
			
			}
		else
		{
		

			$(".datepicker2").next().show();
			$(".datepicker2").addClass("ErrorField");
			res=res && false;
			}	
			
			
			}	
	
	
			
		if(to_ledger=="-1")
		{
			
			$("#to_ledger").next().next().show();
			res=res && false;	
			}
		else
		{
			$("#to_ledger").next().next().hide();
			res=res && true;
			}	
			
			
					
		
		
		return res;
	}	
	
