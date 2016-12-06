// JavaScript Document

                jQuery("#customer_name").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter name!"
                });
				 jQuery("#customer_name").validate({
                     expression: "if (VAL.match(/^[a-zA-Z.()-,: ]+$/)) return true; else return false;",
                    message: "Only letters and Dot(.) allowed!"
                });
				
				 jQuery("#customer_address").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter address!"
                });
				
				 jQuery("#notice_date").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter address!"
                });
				
				function onChangeDate(date,el)
{
		var elName=$(el).attr('name');
		dateFormat=/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/g;
		if(dateFormat.test(date))
		{
			$(el).next().hide();
			$(el).removeClass("ErrorField");
			
			}
		else
		{
			
			$(el).next().show();
			$(el).addClass("ErrorField");
			
		}	
		
		if(elName=="cheque_date")
		{
			var loan_in_cash=document.getElementById('loan_amount_type_cash');
			if(loan_in_cash.checked)
			{
				$(el).next().hide();
			$(el).removeClass("ErrorField");
				}
		}
		
		if(elName=="approvalDate")
		{
			var starting_date=$('#startingDate').val();
			var cheque_date=$('#chequeDate').val();
			if(starting_date==null || starting_date=="")
			{
			document.getElementById('startingDate').value=$(el).val();
			}
			if(cheque_date==null || cheque_date=="")
			{
			document.getElementById('chequeDate').value=$(el).val();
			}
		}
	
	
	}	