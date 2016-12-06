document.totalCustomerProof=1;

$('#addCustomerProofBtn').click(function(e) {
	
	var proofno=document.totalCustomerProof;
	
	var insertTable=document.getElementById('insertCustomerTable');
	var newIndex=$(this).parent().parent().index();
	
	var mytbody=document.createElement('tbody');
	insertTable.appendChild(mytbody);
	var proofImgRow=document.createElement('tr');
	var proofNoRow=document.createElement('tr');
	var proofTypeRow=document.createElement('tr');
	var removeProof=document.createElement('tr');
	var member_proof=document.createElement('tr');
	var hr2=document.createElement('tr');
	
	
	
	var cell1=proofTypeRow.insertCell(0);
    var cell2=proofTypeRow.insertCell(1);
	
	var cell3=proofNoRow.insertCell(0);
    var cell4=proofNoRow.insertCell(1);
	
	var cell5=proofImgRow.insertCell(0);
    var cell6=proofImgRow.insertCell(1);
	
	
	var cell9=hr2.insertCell(0);
    var cell0=hr2.insertCell(1);
	
	var cella=removeProof.insertCell(0);
    var cellb=removeProof.insertCell(1);
	
	var cellc=member_proof.insertCell(0);
    var celld=member_proof.insertCell(1);
	
	cell1.innerHTML="Proof Type : ";
	
	cell3.innerHTML="Proof Number : ";
	
	cell5.innerHTML="Proof Image :  <br />(.jpg | .jpeg | .png | .gif | .pdf)";
	

	
	cell9.innerHTML="<hr>";
	cell0.innerHTML="<hr>";
	
	cellc.innerHTML="Proof For : ";
	
	cellb.innerHTML="<a class='removeProof' onclick='removeCustomerProof(this)'>Remove this Proof</a>";
	
	
	var customerProofTypeHtml=document.getElementById('customerProofTypeTd').innerHTML;
	var customerNoTypeHtml=document.getElementById('customerProofNoTd').innerHTML;
	var member_select_html = document.getElementById('MemberSelectTd').innerHTML;
	
	cell2.innerHTML=customerProofTypeHtml;
	cell4.innerHTML=customerNoTypeHtml;
	celld.innerHTML= member_select_html;
	
	cell6.innerHTML='<input class="customerFile fleInput" onchange="onChangeFile(this.value,this)" accept="image/jpeg,image/png,image/gif,application/pdf" type="file" name="customerProofImg[' + proofno + '][]"  /><br /> <br /> <input type="button" value="+" title="Add More image to this proof" class="btn btn-primary addscanbtnCustomer" onclick="addProofImgCustomer(this,'+proofno+')"/><span class="ValidationErrors fileError">Not Supported File Type!</span>';
	document.totalCustomerProof=++proofno;
	
	mytbody.appendChild(hr2);
	mytbody.appendChild(removeProof);
	mytbody.appendChild(proofTypeRow);
	mytbody.appendChild(proofNoRow);
	mytbody.appendChild(proofImgRow);
	mytbody.appendChild(member_proof);
	
	
});

function removeCustomerProof(elem)
{
	
	elem=$(elem);
	removeTbody=elem.parent().parent().parent();
	removeTbody[0].innerHTML="";
	}
if(document.generateCustomerProof && document.generateCustomerProof ==1)	
document.getElementById('addCustomerProofBtn').click();	