
// JavaScript Document

				 
				 jQuery(".city").validate({
                    expression: "if (VAL>0) return true; else return false;",
                    message: "Please Select the City!"
                });
				 jQuery(".city_area").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please Enter the Area!"
                });
				 jQuery(".city_area").validate({
                   expression: "if (VAL.match(/^[a-zA-Z. ]+$/)) return true; else return false;",
                    message: "Only letters allowed!"
                });
				 
				 jQuery(".address").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter the address!"
                });
				
				 
				 