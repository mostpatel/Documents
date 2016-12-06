function isEnquiryTypeRefrence(value)
{
	
var customer_type_string = document.customer_refernce_types;
	if(document.customer_refernce_types.indexOf(value)>=0)
	{
	document.getElementById('refrenceTable').style.display="table";
	}
	else
	{
		
	document.getElementById('refrenceTable').style.display="none";
	
	}
	
}