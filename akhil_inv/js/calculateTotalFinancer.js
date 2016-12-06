

$('#deleteSelected').click(function(e) {
   
            
		
	$('.selectTR').each(function(index, element) {
       if($(element).prop("checked")==true)
	{
		var itd=element.parentNode.parentNode;
		oTable.fnDeleteRow( itd );
	}
    });	
	
	oTable.fnDraw();
	
	var total=0;
	$('.selectTR').each(function(index, element) {
       if($(element).prop("checked")==true)
	{
		var itd=element.parentNode.parentNode.cells;
		var amount=itd[5].innerHTML;
		total=total+parseInt(amount);
	}
    });
	
	document.getElementById('total_amount').innerHTML=total;
});


		  

		
$('#selectAllTR').change(function(e) {
  
	if($("#selectAllTR").prop("checked")==true)
	{
		$('#adminContentReport .selectTR').prop('checked','checked');
		}
	else
	{
		$('#adminContentReport .selectTR').prop('checked',false);
		
		}
		
	calculateTotal(0);
	
});

$('#adminContentReport .selectTR').change(function(e) {
 
	calculateTotal(0);
	
});

function calculateTotal(j)
{
	
	var total=j;
	$('#adminContentReport .selectTR').each(function(index, element) {
       if($(element).prop("checked")==true)
	{
		
		var itd=element.parentNode.parentNode.cells;
		var amount=itd[3].innerHTML;
		total=total+parseInt(amount);
	}
    });
	
	document.getElementById('total_amount').value=total;
}

		