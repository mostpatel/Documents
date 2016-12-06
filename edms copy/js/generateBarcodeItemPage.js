// JavaScript Document

function toggleQuantityBarcode(value)
{
	if(value==0)
	{
		$('#quantity_row').show();
		$('#addcontactTrCustomer').hide();
		
	}
	else if(value==1)
	{
		$('#addcontactTrCustomer').show();
		$('#quantity_row').hide();	
		
	}
	
}


if($('.addContactbtnCustomer'))
{
	
$('.addContactbtnCustomer').click(function(e) {
	
    var newRowData=document.getElementById('addcontactTrGeneratedCustomer').innerHTML;
	var insertTable=document.getElementById('insertItemTable');
	var newIndex=$('#addcontactTrCustomer').index();
	newIndex=newIndex+2;
	var newRow=insertTable.insertRow(newIndex);
	newRow.innerHTML=newRowData;
});
}

function deleteContactTr(elem){
	
	var parent1=elem.parentNode.parentNode.parentNode;
	parent1.innerHTML="";
	}
