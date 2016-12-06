// JavaScript Document

 jQuery("#txtName").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter the Item Type"
                });
				