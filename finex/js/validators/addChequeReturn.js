 jQuery("#insurance_company").validate({
                    expression: "if (VAL!=-1) return true; else return false;",
                    message: "Please Select the Insurance Company!"
                });
jQuery("#bank").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please Enter the Bank Name!"
                });
jQuery("#branch").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please Enter the Branch Name!"
                });		
jQuery("#cheque_amount").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please Enter the Cheque Amount!"
                });						
jQuery("#cheque_amount").validate({
                    expression: "if (!isNaN(VAL)) return true; else return false;",
                    message: "Only digits allowed in Cheque Amount!"
                });	
jQuery("#cheque_no").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please Enter the Cheque No!"
                });						
jQuery("#cheque_no").validate({
                    expression: "if (!isNaN(VAL)) return true; else return false;",
                    message: "Only digits allowed in Cheque No!"
                });		
				
jQuery("#cheque_date").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please Enter the Cheque Date!"
                });	