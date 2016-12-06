function toggleElectrical(value)
{
	
	if(value==1)
	{
	document.getElementById('yesElectric').style.display="table";
	document.getElementById('electronicNCB').style.display="table";
	
	}
	else if(value==0)
	{
	document.getElementById('yesElectric').style.display="none";
	document.getElementById('electronicNCB').style.display="none";
	}
	
	basicPremiumPlusCNGPlusElectric();
}