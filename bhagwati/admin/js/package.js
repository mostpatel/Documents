// JavaScript Document
function alterItenary(days)
{
	var prev_days = document.package_days;
	
	var itenary_inner_html = document.getElementById('day').innerHTML;
	var itenary_table = document.getElementById('insertItenaryTable');
	if(prev_days==(days+1))
	{
		
		
	}
	else if(prev_days<=days)
	{
		
		for(i=prev_days;i<=days;i++)
		{
			var new_tbody=document.createElement('tbody');
			new_tbody.setAttribute('id','day'+i);
			new_tbody.innerHTML=itenary_inner_html;
			itenary_table.appendChild(new_tbody);
			$('#day'+i+' span:first-child')[0].innerHTML='Day '+i;
			}
	}
	else if(prev_days>days)
	{
		
		for(i=(prev_days-1);i>days;i--)
		{
			$('#day'+i).remove();
			}
	}	
	
	document.package_days=parseInt(days)+1;
	
}