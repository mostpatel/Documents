// JavaScript Document


function deleteProofImgGuarantorTr(elem){
	var parent1=elem.parentNode.parentNode;
	parent1.innerHTML="";
	}

function addProofImgGuarantor(elem,proofno) {
   
   var jelem=$(elem);
	var tableRow=elem.parentNode.parentNode;
	var tableBody=elem.parentNode.parentNode.parentNode;
	var insertTable=document.getElementById('insertGuarantorTable');
		 
	var newIndex=jelem.parent().parent().index();

	newIndex=newIndex+1;
	
	var newRow=tableBody.insertRow(newIndex);
	
	

	
	var cell1=newRow.insertCell(0);
    var cell2=newRow.insertCell(1);
	
	cell1.innerHTML="Proof Image : <br />(.jpg,.jpeg,.png,.gif,.pdf)";
	
	var imgBtn=document.createElement("input");
	imgBtn.setAttribute("type","file");
	imgBtn.setAttribute("class","guarantorFile2 fleInput");
	imgBtn.setAttribute("accept","image/jpeg,image/png,image/gif,application/pdf");
	imgBtn.setAttribute("name","guarantorProofImg["+proofno+"][]");
	imgBtn.setAttribute("title","insert Proof Image");
	imgBtn.setAttribute("onchange","onChangeFile(this.value,this)");
	cell2.appendChild(imgBtn);
	
	var spanOr=document.createElement("span");
	spanOr.innerHTML="<br>- OR -<br>";
	cell2.appendChild(spanOr);
	
	var scanBtn=document.createElement("input");
	scanBtn.setAttribute("type","button");
	scanBtn.setAttribute("class","btn scanBtn guarantorScanBtn");
	scanBtn.setAttribute("onclick","scanGuarantorProof(this,'"+proofno+"')");
	scanBtn.setAttribute("name","scanProof");
	scanBtn.setAttribute("value","scan");
	cell2.appendChild(scanBtn);
	
	var removeScanBtn=document.createElement("input");
	removeScanBtn.setAttribute("type","button");
	removeScanBtn.setAttribute("class","btn btn-danger removescanbtn");
	removeScanBtn.setAttribute("value","-");
	removeScanBtn.setAttribute("onclick","deleteProofImgGuarantorTr(this)");
	cell2.appendChild(removeScanBtn);
	
	var spanError=document.createElement("span");
	spanError.setAttribute("class","customError fileError");
	spanError.innerHTML="Not Supported File Type!";
	cell2.appendChild(spanError);
	
}



