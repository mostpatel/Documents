jQuery("#item_type_id").validate({
                    expression: "if (VAL!=-1) return true; else return false;",
                    message: "Please Select the Item Type!"
                });
jQuery("#txtName").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please Enter the Item Name!"
                });							
