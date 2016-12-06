
// JavaScript Document

				 jQuery("#txtEmail").validate({
                    expression: "if (VAL.match(/^[^\\W][a-zA-Z0-9\\_\\-\\.]+([a-zA-Z0-9\\_\\-\\.]+)*\\@[a-zA-Z0-9_]+(\\.[a-zA-Z0-9_]+)*\\.[a-zA-Z]{2,4}$/) || !VAL) return true; else return false;",
                    message: "Please enter a valid Email id"
                });
				 jQuery("#agency_id").validate({
                    expression: "if (VAL!=-1) return true; else return false;",
                    message: "Please Select the Agency!"
                });
				 jQuery("#broker_id").validate({
                    expression: "if (VAL!=-1) return true; else return false;",
                    message: "Please Select the Broker!"
                });
				 jQuery(".city").validate({
                    expression: "if (VAL>0) return true; else return false;",
                    message: "Please Select the City!"
                });
				 jQuery(".city_area").validate({
                    expression: "if (VAL>0) return true; else return false;",
                    message: "Please Select the Area!"
                });
				
				 jQuery(".person_name").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter name!"
                });
				 jQuery(".person_name").validate({
                     expression: "if (VAL.match(/^[a-zA-Z.()-,:& ]+$/)) return true; else return false;",
                    message: "Only letters and Dot(.) allowed!"
                });
				 jQuery(".address").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter address!"
                });
				jQuery("#guarantor_city_id").validate({
                    expression: "if (VAL>0 || ((jQuery('#guarantor_name').val()=='') && VAL<0)) return true; else return false;",
                    message: "Please Select the City!"
                });
				 jQuery("#city_area2").validate({
                    expression: "if (((jQuery('#guarantor_name').val()=='') && !VAL) || VAL) return true; else return false;",
                    message: "Please Enter the Area!"
                });
				 jQuery("#city_area2").validate({
                   expression: "if (((jQuery('#guarantor_name').val()=='') && !VAL) || VAL.match(/^[a-zA-Z. ]+$/)) return true; else return false;",
                    message: "Only letters allowed!"
                });
				 jQuery("#guarantor_name").validate({
                     expression: "if ((!VAL && (jQuery('#guarantor_address').val()=='' && jQuery('#city_area2').val()=='' && jQuery('#guarantor_city_id').val()<0 && jQuery('#guarantor_pincode').val()=='' && jQuery('#guarantorContact').val()=='')) || VAL) return true; else return false;",
                    message: "Please Enter Name!"
                });
				 jQuery("#guarantor_name").validate({
                     expression: "if (((!VAL || VAL.match(/^[a-zA-Z.()-,: ]+$/)) && (jQuery('#guarantor_address').val()=='' && jQuery('#city_area2').val()=='' && jQuery('#guarantor_city_id').val()<0 && jQuery('#guarantor_pincode').val()=='' && jQuery('#guarantorContact').val()=='')) || VAL.match(/^[a-zA-Z.()-,: ]+$/)) return true; else return false;",
                    message: "Only letters and Dot(.) allowed!"
                });
				 jQuery("#guarantor_address").validate({
                    expression: "if (((jQuery('#guarantor_name').val()=='') && !VAL) || VAL) return true; else return false;",
                    message: "Please enter address!"
                });
				jQuery("#guarantorContact").validate({
                    expression: "if (((jQuery('#guarantor_name').val()=='') && !VAL) || VAL.match(/^[0-9]+$/i)) return true; else return false;",
                    message: "Only digits allowed in contact no!"
                });
				 jQuery("#guarantorContact").validate({
                    expression: "if (((jQuery('#guarantor_name').val()=='') && !VAL) || VAL.length==10) return true; else return false;",
                    message: "contact no should be 10 digits long!"
                });
				jQuery(".pincode").validate({
                    expression: "if (!VAL || (!isNaN(VAL) && VAL>0)) return true; else return false;",
                    message: "Only digits allowed in pincode!"
                });
				 jQuery(".pincode").validate({
                    expression: "if (!VAL || VAL.length==6) return true; else return false;",
                    message: "Pincode should be 6 digits long!"
                });
				jQuery("#agreementNo").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter an Agreement number!"
                });
                jQuery("#agreementNo").validate({
                    expression: "if (VAL.match(/^[a-z0-9-/,.]+$/i)) return true; else return false;",
                    message: "Only letters and numbers allowed!"
                });
				jQuery("#agreementNo").validate({
                    expression: "if (VAL.length < 40) return true; else return false;",
                    message: "Agreement number should 4 characters or less!"
                });
				jQuery("#fileNumber").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter a File Number!"
                });
                jQuery("#fileNumber").validate({
                    expression: "if (VAL.match(/^[0-9/]+$/i)) return true; else return false;",
                    message: "Only  Digits and / allowed!"
                });
				jQuery("#fileNumber").validate({
                    expression: "if (VAL.length < 40) return true; else return false;",
                    message: "File number should 40 characters or less!"
                });
				 jQuery("#customerContact").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter cusomter's contact no!"
                });
				jQuery("#customerContact").validate({
                    expression: "if (!isNaN(VAL) && VAL>0 && VAL.match(/^[0-9]+$/i)) return true; else return false;",
                    message: "Only digits allowed in contact no!"
                });
				 jQuery("#customerContact").validate({
                    expression: "if (VAL.length==10) return true; else return false;",
                    message: "contact no should be 10 digits long!"
                });
				
				jQuery("#amount").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter Loan amount!"
                });
				jQuery("#amount").validate({
                    expression: "if (!isNaN(VAL) && VAL>0) return true; else return false;",
                    message: "Only digits allowed in Loan amount!"
                });
				
				jQuery("#duration").validate({
                    expression: "if ((($('input[name=loan_scheme]:checked').val()==1) && VAL) || ($('input[name=loan_scheme]:checked').val()==2)) return true; else return false;",
                    message: "Please enter Loan duration (months)!"
                });
				jQuery("#duration").validate({
                    expression: "if ((($('input[name=loan_scheme]:checked').val()==1) && !isNaN(VAL) && VAL>0 && VAL<=120) || ($('input[name=loan_scheme]:checked').val()==2)) return true; else return false;",
                    message: "Only digits allowed in loan duration (less than 120 months)!"
                });
				
				jQuery("#roi").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter Rate of Interest (annually in %)"
                });
				jQuery("#roi").validate({
                    expression: "if (!isNaN(VAL) && VAL>=0) return true; else return false;",
                    message: "Only digits allowed in Rate of Interest!"
                });
				
				jQuery("#emi").validate({
                    expression: "if ((($('input[name=loan_scheme]:checked').val()==1) && VAL) || ($('input[name=loan_scheme]:checked').val()==2)) return true; else return false;",
                    message: "Please enter EMI!"
                });
				jQuery("#emi").validate({
                    expression: "if ((($('input[name=loan_scheme]:checked').val()==1) && !isNaN(VAL) && VAL>0) || ($('input[name=loan_scheme]:checked').val()==2)) return true; else return false;",
                    message: "Only digits allowed in EMI!"
                });
				
				jQuery("#agency_amount").validate({
                    expression: "if ((document.agency_type==2 && VAL) || document.agency_type==1) return true; else return false;",
                    message: "Please enter Loan amount!"
                });
				jQuery("#agency_amount").validate({
                    expression: "if ((document.agency_type==2 && !isNaN(VAL) && VAL>0) || document.agency_type==1) return true; else return false;",
                    message: "Only digits allowed in Loan amount!"
                });
				
				jQuery("#agency_duration").validate({
                    expression: "if ((document.agency_type==2 && VAL) || document.agency_type==1) return true; else return false;",
                    message: "Please enter Loan duration (months)!"
                });
				jQuery("#agency_duration").validate({
                    expression: "if ((document.agency_type==2 && !isNaN(VAL) && VAL>0) || document.agency_type==1) return true; else return false;",
                    message: "Only digits allowed in loan duration!"
                });
				
				jQuery("#agency_emi").validate({
                    expression: "if ((document.agency_type==2 && VAL) || document.agency_type==1) return true; else return false;",
                    message: "Please enter EMI!"
                });
				jQuery("#agency_emi").validate({
                    expression: "if ((document.agency_type==2 && !isNaN(VAL) && VAL>0) || document.agency_type==1) return true; else return false;",
                    message: "Only digits allowed in EMI!"
                });
				
					jQuery("#bank_name").validate({
                    expression: "if ((($('input[name=loan_amount_type]:checked').val()==2) && VAL) || ($('input[name=loan_amount_type]:checked').val()==1)) return true; else return false;",
                    message: "Please enter the bank name"
                });
jQuery("#branch_name").validate({
                    expression: "if ((($('input[name=loan_amount_type]:checked').val()==2) && VAL) || ($('input[name=loan_amount_type]:checked').val()==1)) return true; else return false;",
                    message: "Please enter the branch name"
                });	
	jQuery("#bank_name").validate({
                    expression: "if ((($('input[name=loan_amount_type]:checked').val()==2) && VAL.match(/^[a-zA-Z ]+$/)) || ($('input[name=loan_amount_type]:checked').val()==1)) return true; else return false;",
                    message: "Only letters allowed!"
                });								
	jQuery("#branch_name").validate({
                    expression: "if ((($('input[name=loan_amount_type]:checked').val()==2) && VAL.match(/^[a-zA-Z ]+$/)) || ($('input[name=loan_amount_type]:checked').val()==1)) return true; else return false;",
                    message: "Only letters allowed!"
                });		
				
				 jQuery("#cheque_amount").validate({
                    expression: "if (( ($('input[name=loan_amount_type]:checked').val()==2) && VAL) || ($('input[name=loan_amount_type]:checked').val()==1)) return true; else return false;",
                    message: "Please enter Cheque Amount"
                });
				jQuery("#cheque_amount").validate({
                    expression: "if (( ($('input[name=loan_amount_type]:checked').val()==2) && !isNaN(VAL)) || ($('input[name=loan_amount_type]:checked').val()==1)) return true; else return false;",
                    message: "Only digits allowed in Amount!"
                });
				
				 jQuery("#cheque_no").validate({
                    expression: "if (( ($('input[name=loan_amount_type]:checked').val()==2) && VAL) || ($('input[name=loan_amount_type]:checked').val()==1)) return true; else return false;",
                    message: "Please enter Cheque Number"
                });
				jQuery("#cheque_no").validate({
                    expression: "if (( ($('input[name=loan_amount_type]:checked').val()==2) && (!isNaN(VAL) && (VAL.length==6 || VAL.length==8))) || ($('input[name=loan_amount_type]:checked').val()==1)) return true; else return false;",
                    message: "Only 6 or 8 digits allowed in Cheque Number!"
                });
				
				
				 jQuery("#axin_no").validate({
                    expression: "if (!VAL || VAL.match(/^[a-z0-9]+$/i)) return true; else return false;",
                    message: "Please enter Valid Cheque Axin Number"
                });
				
				
				jQuery("#remarks_remainder").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter Remarks!"
                });
				
				jQuery("#amount_interest").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter amount/day or interest!"
                });
				jQuery("#amount_interest").validate({
                    expression: "if (!isNaN(VAL) && VAL>0) return true; else return false;",
                    message: "Only digits allowed in amount/Interest!"
                });
				
				 jQuery("#by_ledger").validate({
                    expression: "if (( ($('input[name=loan_amount_type]:checked').val()==2) && VAL>0) || ($('input[name=loan_amount_type]:checked').val()==1)) return true; else return false;",
                    message: "Please Select Ledger!"
                });


function checkForDuplicateContactNo(contactNo)
{
	
var xmlhttp1;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp1 = new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
  }

  xmlhttp1.onreadystatechange=function()                        
  {
  if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
    {
	
    var myarray=eval(xmlhttp1.responseText);
// Before adding new we must remove previously loaded elements
if(myarray)
{
$('.dupContact').each(function(index, element) {
        element.innerHTML="";
    });
for (var i=0; i<myarray.length; i++)
{
if(myarray[i]>0)	
{
	var str = "<a style='color:#f00' target='_blank' href='index.php?view=details&id="+myarray[i]+"'>Duplicate Customer"+(i+1)+"</a>";
var newRow=document.getElementById("insertCustomerTable").insertRow(5);
newRow.setAttribute("class","dupContact");
var cell1 = newRow.insertCell(0);
var cell2 = newRow.insertCell(1);
cell2.innerHTML = str;
}

} 
}

    }
  }
  
  xmlhttp1.open('GET', "getCustomerIdFromContactNo.php?no="+contactNo, true );    
  xmlhttp1.send(null);	
	
}

function checkForDuplicateCustomerName(name)
{
	
var xmlhttp1;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp1 = new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
  }

  xmlhttp1.onreadystatechange=function()                        
  {
  if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
    {
	
    var myarray=eval(xmlhttp1.responseText);
// Before adding new we must remove previously loaded elements
if(myarray)
{
$('.dupName').each(function(index, element) {
        element.innerHTML="";
    });
for (var i=0; i<myarray.length; i++)
{
if(myarray[i]>0)	
{
	var str = "<a style='color:#f00' target='_blank' href='index.php?view=details&id="+myarray[i]+"'>Duplicate Customer"+(i+1)+"</a>";
var newRow=document.getElementById("insertCustomerTable").insertRow(0);
newRow.setAttribute("class","dupName");
var cell1 = newRow.insertCell(0);
var cell2 = newRow.insertCell(1);
cell2.innerHTML = str;
}

} 
}

    }
  }
  
  xmlhttp1.open('GET', "getCustomerIdFromName.php?name="+name, true );    
  xmlhttp1.send(null);	
	
}

function checkContactNo(contact,contactInput)
{
	
	if(contact=="" || contact==null)
		{
			$(contactInput).next().next().hide();
			$(contactInput).removeClass("ErrorField");
			res=res && true;
			
			}
		else if(contact.length>6 && contact.length<13 && $.isNumeric(contact))
		{
			
			$(contactInput).next().next().hide();
			$(contactInput).removeClass("ErrorField");
			res=res && true;
			
			}
		else
		{
			
			$(contactInput).next().next().show();
			$(contactInput).addClass("ErrorField");
			res=res && false;
			}	
	
	}

function checkProofNo(proofno,el)
{
	
	var letters = /^[0-9a-zA-Z]+$/;
					
	if(proofno=="" || proofno==null)
		{
			$(el).next().hide();
			$(el).removeClass("ErrorField");
			var proofIdEl=$(el).parent().parent().prev().find(".customerProofId");
			
					$(proofIdEl).next().hide();
					$(proofIdEl).removeClass("ErrorField");
					
			
			}
		else if(proofno.match(letters))
		{
			$(el).next().hide();
			$(el).removeClass("ErrorField");
			var proofIdEl=$(el).parent().parent().prev().find(".customerProofId");
			var proofId=$(el).parent().parent().prev().find(".customerProofId").val();
					 if(proofId!="-1")
					{
					$(proofIdEl).next().hide();
					$(proofIdEl).removeClass("ErrorField");
					}
					else
					{
					$(proofIdEl).next().show();
					$(proofIdEl).addClass("ErrorField");
					}	
			
			}
		else
		{
			
			$(el).next().show();
			$(el).addClass("ErrorField");
			
			}	
	
	}	
	
	

function checkProofId(proofId,el)
{
						
	if(proofId!="-1")
		{
			$(el).next().hide();
			$(el).removeClass("ErrorField");
		}
	else
	{
		$(el).next().show();
			$(el).addClass("ErrorField");
		}	
		
	}	

			

function submitOurCompany()
{
	
	var res=true;
	var i=0;
	$( ".contact" ).each(function( index ) {
		var contact=$(this).val();
		if(contact=="" || contact==null)
		{
			$(this).next().next().hide();
			$(this).removeClass("ErrorField");
			res=res && true;
			
			}
		else if(contact.length>6 && contact.length<13 && $.isNumeric(contact))
		{
			$(this).next().next().hide();
			$(this).removeClass("ErrorField");
			res=res && true;
			
			}
		else
		{
			
			$(this).next().next().show();
			$(this).addClass("ErrorField");
			res=res && false;
			}	
	  
	});
	
	$( ".datepick" ).each(function( index ) {
		
		
		var date=$(this).val();
		dateFormat=/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/g;
		var elName=$(this).attr('name');
		if(dateFormat.test(date))
		{
			$(this).next().hide();
			$(this).removeClass("ErrorField");
			res=res && true;
			
			}
		else if(elName=="cheque_date" && document.getElementById('loan_amount_type_cash').checked)
		{
			$(this).next().hide();
			$(this).removeClass("ErrorField");
			res=res && true;
			
			}	
		else
		{
			$(this).next().show();
			$(this).addClass("ErrorField");
			res=res && false;
			}	
		
			
		
	});
	
	$(".customerProofId").each(function(index, element) {
		if(index!=0)
		{
		var proofno=$(".customerProofNo").eq(index).val();
        var proofid=$(this).val();
		
		
		if(proofid!=-1)
		{
			
					var proofnoEl=$(".customerProofId").eq(index);
					proofnoEl.next().hide();
					proofnoEl.removeClass("ErrorField");
					res=res && true;
			if((proofno=="" || proofno==null))
			{
				if(proofno=="" || proofno==null)
				{
					var proofnoEl=$(".customerProofNo").eq(index);
					proofnoEl.next().show();
					proofnoEl.addClass("ErrorField");
					res=res && false;
					
				}
				
			}
			else if(!(proofno=="" || proofno==null))
			{
					var letters = /^[0-9a-zA-Z]+$/;
					if(proofno.match(letters))
					{
					var proofnoEl=$(".customerProofNo").eq(index);
					proofnoEl.next().hide();
					proofnoEl.removeClass("ErrorField");
					res=res && true;
					}
					else
					{
						var proofnoEl=$(".customerProofNo").eq(index);
						proofnoEl.next().show();
						proofnoEl.addClass("ErrorField");
						res=res && false;
						}
			}
			else
			{
					var proofnoEl=$(".customerProofNo").eq(index);
					proofnoEl.next().hide();
					proofnoEl.removeClass("ErrorField");
					res=res && true;
				
			}
		}
		else if(proofid==-1 && !((proofno=="" || proofno==null)))
		{
						var proofnoEl=$(".customerProofId").eq(index);
						proofnoEl.next().show();
						proofnoEl.addClass("ErrorField");
						res=res && false;
						
					if(!(proofno=="" || proofno==null))
					{
					var letters = /^[0-9a-zA-Z]+$/;
						if(proofno.match(letters))
						{
						var proofnoEl=$(".customerProofNo").eq(index);
						proofnoEl.next().hide();
						proofnoEl.removeClass("ErrorField");
						res=res && true;
						}
						else
						{
							var proofnoEl=$(".customerProofNo").eq(index);
							proofnoEl.next().show();
							proofnoEl.addClass("ErrorField");
							res=res && false;
							}
					}
					
			
			}
		}
    });
	
	return res;
}

	
function onChangeDate(date,el)
{
		var elName=$(el).attr('name');
		dateFormat=/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/g;
		if(dateFormat.test(date))
		{
			$(el).next().hide();
			$(el).removeClass("ErrorField");
			
			}
		else
		{
			
			$(el).next().show();
			$(el).addClass("ErrorField");
			
		}	
		
		if(elName=="cheque_date")
		{
			var loan_in_cash=document.getElementById('loan_amount_type_cash');
			if(loan_in_cash.checked)
			{
				$(el).next().hide();
			$(el).removeClass("ErrorField");
				}
		}
		
		if(elName=="approvalDate")
		{
			var starting_date=$('#startingDate').val();
			var cheque_date=$('#chequeDate').val();
			if(starting_date==null || starting_date=="")
			{
			document.getElementById('startingDate').value=$(el).val();
			}
			if(cheque_date==null || cheque_date=="")
			{
			document.getElementById('chequeDate').value=$(el).val();
			}
		}
	
	
	}		