// JavaScript Document

 jQuery("#txtName").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter the Vehicle Type"
                });
				 jQuery("#txtName").validate({
                     expression: "if (VAL.match(/^[a-zA-Z ]+$/)) return true; else return false;",
                    message: "Only letters allowed!"
                });