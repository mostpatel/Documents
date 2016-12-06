function toggleProductStatus(value)
{
	
	if(value==2)
	{
	document.getElementById('declined').style.display="table";
	document.getElementById('tourDate').style.display="none";
	}
	else if(value==1)
	{
		
	document.getElementById('declined').style.display="none";
	document.getElementById('tourDate').style.display="table";
	}
}