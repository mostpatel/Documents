// JavaScript Document

jQuery("#name").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter the Godown name"
                });
				
				 jQuery("#city").validate({
                     expression: "if (!isNaN(VAL) && VAL!=-1) return true; else return false;",
                    message: "Please Select City!"
                });
				
				
function checkCheckBox()
{

	return true;
	}				