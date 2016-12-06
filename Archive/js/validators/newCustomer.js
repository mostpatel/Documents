// JavaScript Document

 jQuery(".customer_name").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter  a name!"
                });
jQuery(".reminder_date").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter  a Follow Up date!"
                });
				
jQuery(".contact").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter the contact Number!"
                });
											
jQuery(".product").validate({
                   expression: "if (VAL.match(/^[a-zA-Z. ]+$/)) return true; else return false;",
                    message: "Only letters allowed!"
                });
			
jQuery(".contact").validate({
                    expression: "if (!isNaN(VAL)) return true; else return false;",
                    message: "Only digits allowed in contact number!"
                });

jQuery(".contact").validate({
                    expression: "if (VAL.length==10) return true; else return false;",
                    message: "10 digits!"
                });		

jQuery("#email").validate({
                    expression: "if ((!VAL) || (VAL.match(/^[^\\W][a-zA-Z0-9\\_\\-\\.]+([a-zA-Z0-9\\_\\-\\.]+)*\\@[a-zA-Z0-9_]+(\\.[a-zA-Z0-9_]+)*\\.[a-zA-Z]{2,4}$/))) return true; else return false;",
                    message: "Please enter a valid Email id!"
                });