// JavaScript Document

 jQuery("#dealer").validate({
                     expression: "if (!isNaN(VAL) && VAL!=-1) return true; else return false;",
                    message: "Please Select Vehicle Company!"
                });
				
 jQuery("#txtName").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter the Vehicle Model name"
                });
				 jQuery("#txtName").validate({
                     expression: "if (VAL.match(/^[a-zA-Z. ]+$/)) return true; else return false;",
                    message: "Only letters and Dot(.) allowed!"
                });
								