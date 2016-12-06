// JavaScript Document

  jQuery("#txtbank").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter the Tax name"
                });
	jQuery("#tax_percent").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please Enter Tax Percent!"
                });					
	jQuery("#tax_percent").validate({
                    expression: "if (!isNaN(VAL) && VAL>0) return true; else return false;",
                    message: "Only Digits allowed!"
                });			
				
		
      