// JavaScript Document

 jQuery("#name").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter the Ledger Name"
                });
jQuery("#head").validate({
                    expression: "if (VAL!=-1) return true; else return false;",
                    message: "Please Select the Ledger Head!"
                });		
jQuery("#opening_balance").validate({
                    expression: "if ((VAL) && !isNaN(VAL) || !(VAL)) return true; else return false;",
                    message: "Only Digits Allowed!"
                });									
				