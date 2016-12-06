// JavaScript Document
 $( ".datepicker1" ).datepicker({
      changeMonth: true,
      changeYear: true,
	   dateFormat: 'dd/mm/yy',
	   numberOfMonths : 2,
	    onSelect: function(dateText, inst) {
			$(this).focus();
		}
    });

$( ".datepicker2" ).datepicker({
      changeMonth: true,
      changeYear: true,
	   dateFormat: 'dd/mm/yy',
	    numberOfMonths : 2
    });
	
$( ".datepicker3" ).datepicker({
      changeMonth: true,
      changeYear: true,
	  dateFormat: 'dd/mm/yy',
	   numberOfMonths : 2
    });		