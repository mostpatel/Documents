jQuery("#tax_pay_type").validate({
                    expression: "if (VAL>0 || ((jQuery('#total_freight').val()<=750) && VAL==-1)) return true; else return false;",
                    message: "Please Select Tax Type!"
                });
				
jQuery("#lr_type").validate({
                    expression: "if (VAL>0) return true; else return false;",
                    message: "Please Select LR Type!"
                });				