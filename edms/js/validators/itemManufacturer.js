// JavaScript Document

 jQuery("#txtName").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter the Item Type"
                });
				
jQuery("#txtContact").validate({
                    expression: "if (!VAL || (!isNaN(VAL) && VAL>0)) return true; else return false;",
                    message: "Only digits allowed in Mobile no!"
                });
				 jQuery("#txtContact").validate({
                    expression: "if (!VAL || VAL.length==10) return true; else return false;",
                    message: "Mobile No should be 10 digits long!"
                });				