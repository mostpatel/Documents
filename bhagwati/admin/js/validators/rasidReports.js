// JavaScript Document

 jQuery("#start_date").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please Select Date!"
                });