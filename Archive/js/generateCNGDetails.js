function toggleCNG(value)
{
	
	if(value==1)
	{
	document.getElementById('yesCNG').style.display="block";
	document.getElementById('cngNCB').style.display="table";
	}
	else if(value==0)
	{
	document.getElementById('yesCNG').style.display="none";
	document.getElementById('cngNCB').style.display="none";
    }
	
	basicPremiumPlusCNGPlusElectric();

}