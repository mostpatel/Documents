// JavaScript Document
 $( ".datepicker1" ).datepicker({
      changeMonth: true,
      changeYear: true,
	   dateFormat: 'dd/mm/yy'
    });

$( ".datepicker2" ).datepicker({
      changeMonth: true,
      changeYear: true,
	   dateFormat: 'dd/mm/yy'
    });
	
$( ".datepicker3" ).datepicker({
      changeMonth: true,
      changeYear: true,
	  dateFormat: 'dd/mm/yy'
    });		
	
$( ".datepicker4" ).datepicker({
      changeMonth: true,
      changeYear: true,
	  dateFormat: 'dd/mm/yy'
    });		


$('#multiyear').multiDatesPicker({
	addDates: document.package_dates,
	numberOfMonths: [2,4],
	dateFormat: 'dd/mm/yy'
});	

$('#multiyear2').multiDatesPicker({
	addDates: document.package_dates,
	numberOfMonths: [3,3],
	dateFormat: 'dd/mm/yy',
	disabled: true
});		