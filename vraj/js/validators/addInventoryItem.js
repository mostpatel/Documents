jQuery("#item_type_id").validate({
                    expression: "if (VAL!=-1) return true; else return false;",
                    message: "Please Select the Item Type!"
                });
jQuery("#item_unit_id").validate({
                    expression: "if (VAL!=-1) return true; else return false;",
                    message: "Please Select the Item Unit!"
                });	
jQuery("#supplier_id").validate({
                    expression: "if (VAL!=-1) return true; else return false;",
                    message: "Please Select the Supplier!"
                });								
jQuery("#txtName").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please Enter the Item Name!"
                });	
jQuery("#txtItemCode").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please Enter the SKU Code!"
                });	
jQuery("#txtMfgItemCode").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please Enter the UPC Code!"
                });									
jQuery("#txtMinQuantity").validate({
                    expression: "if ((VAL) && !isNaN(VAL) || !(VAL)) return true; else return false;",
                    message: "Only Digits Allowed!"
                });											
jQuery("#txtDealerPrice").validate({
                    expression: "if ((VAL) && !isNaN(VAL) || !(VAL)) return true; else return false;",
                    message: "Only Digits Allowed!"
                });											

jQuery("#txtMrp").validate({
                    expression: "if ((VAL) && !isNaN(VAL) || !(VAL)) return true; else return false;",
                    message: "Only Digits Allowed!"
                });	
jQuery("#txtOpeningQuantity").validate({
                    expression: "if ((VAL) && !isNaN(VAL) || !(VAL)) return true; else return false;",
                    message: "Only Digits Allowed!"
                });		
jQuery("#txtOpeningRate").validate({
                    expression: "if ((VAL) && !isNaN(VAL) || !(VAL)) return true; else return false;",
                    message: "Only Digits Allowed!"
                });	
				
												
				
				
														

