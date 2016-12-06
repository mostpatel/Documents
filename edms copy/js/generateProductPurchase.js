// JavaScript Document
function addProductRow(addBtn,url,type)
{

	if(!type)
	type=0;
    var newTbodyData=document.getElementById('p0').innerHTML;
	newTbodyData=newTbodyData.replace("inventory_item_autocomplete1","inventory_item_autocomplete");
	var insertTable=document.getElementById('productPurchaseTable');
	
	var newTbody = document.createElement('tbody');
	newTbody.innerHTML=newTbodyData;
	var product_count = document.product_count;
	newTbody.id = 'p'+product_count;
	var newRow=insertTable.appendChild(newTbody);
	
	$(addBtn).hide();
	$(addBtn).next().show();
	$(addBtn).next().focus();
	document.product_count = document.product_count+1;

	if(type==0)
	{
	 $( ".inventory_item_autocomplete" ).autocomplete({
      minLength: 1,
      source:  function(request, response) 
	  {
		 var trans_date = request.term + " | "+ $('#payment_date').val()+" | "+document.barcode_type;
                $.getJSON (url,
                { term: trans_date }, 
                response );
     },
	autoFocus: true,
    selectFirst: true,
    open: function(event, ui) {  select=false; target_el=event.target },
    select: function(event, ui) { select=true; $(event.target).val(ui.item.label);
	getRateQuantityAndTaxForSalesFromItemId(ui.item.id,target_el);  
		getUnitsFromItemId(ui.item.id,target_el);  
			 }
	 
 
}).blur(function(){
	
    if(!select)
    {
		
		$(target_el).val("");
    }
 });		
	}
		else if(type==1)
	{
		
		 $( ".inventory_item_autocomplete" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
		var trans_date = request.term + " | "+ $('#payment_date').val()+" | "+document.barcode_type;
                $.getJSON (url,
                { term: trans_date }, 
                response );
            },
			autoFocus: true,
    selectFirst: true,
    open: function(event, ui) {  select=false; target_el=event.target },
    select: function(event, ui) { select=true; $(event.target).val(ui.item.label);
	getRateQuantityAndTaxForPurchaseFromItemId(ui.item.id,target_el); 
	getUnitsFromItemIdPurchase(ui.item.id,target_el); 
			 }
	 
 
}).blur(function(){
	
    if(!select)
    {
		
		$(target_el).val("");
    }
 });		
		
		}		
}

function addOJProductRow(addBtn,url){
	
    var newTbodyData=document.getElementById('oj0').innerHTML;
	newTbodyData=newTbodyData.replace("inventory_ns_item_autocomplete1","inventory_ns_item_autocomplete");
	var insertTable=document.getElementById('outSideJobTable');
	var newTbody = document.createElement('tbody');
	newTbody.innerHTML=newTbodyData;
	var product_count = document.product_count;
	newTbody.id = 'oj'+product_count;
	var newRow=insertTable.appendChild(newTbody);
	
	 $( ".inventory_ns_item_autocomplete" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON (url,
                { term: request.term }, 
                response );
            },
			autoFocus: true,
    selectFirst: true,
    open: function(event, ui) {  select=false; target_el=event.target },
    select: function(event, ui) { select=true; $(event.target).val(ui.item.label);
	getRateQuantityAndTaxForSalesFromItemId(ui.item.id,target_el);  
			 }
}).blur(function(){
	
    if(!select)
    {
		
		$(target_el).val("");
    }
 });		
	$(addBtn).hide();
	$(addBtn).next().show();
	$(addBtn).next().focus();
	document.product_count = document.product_count+1;
}

function addNSProductRow(addBtn,url){
	
    var newTbodyData=document.getElementById('ns0').innerHTML;
	newTbodyData=newTbodyData.replace("inventory_ns_item_autocomplete1","inventory_ns_item_autocomplete");
	var insertTable=document.getElementById('nonStockSaleTable');
	var newTbody = document.createElement('tbody');
	newTbody.innerHTML=newTbodyData;
	var product_count = document.product_count;
	newTbody.id = 'ns'+product_count;
	var newRow=insertTable.appendChild(newTbody);
	

	$(addBtn).hide();
	$(addBtn).next().show();
	$(addBtn).next().focus();
	document.product_count = document.product_count+1;
	
	 $( ".inventory_ns_item_autocomplete" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON (url,
                { term: request.term }, 
                response );
            },
			autoFocus: true,
    selectFirst: true,
    open: function(event, ui) {  select=false; target_el=event.target },
    select: function(event, ui) { select=true; $(event.target).val(ui.item.label);
	getRateQuantityAndTaxForSalesFromItemId(ui.item.id,target_el);  
			 }
}).blur(function(){
	
    if(!select)
    {
		
		$(target_el).val("");
    }
 });		
}

function addWarProductRow(addBtn,url){
	
    var newTbodyData=document.getElementById('pwar0').innerHTML;
	newTbodyData=newTbodyData.replace("inventory_item_autocomplete1","inventory_item_autocomplete");
	var insertTable=document.getElementById('warProductPurchaseTable');
	var newTbody = document.createElement('tbody');
	newTbody.innerHTML=newTbodyData;
	var product_count = document.product_count;
	newTbody.id = 'pwar'+product_count;
	var newRow=insertTable.appendChild(newTbody);
	
	
	$(addBtn).hide();
	$(addBtn).next().show();
	$(addBtn).next().focus();
	document.product_count = document.product_count+1;
	 $( ".inventory_item_autocomplete" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON (url,
                { term: request.term }, 
                response );
            },
			autoFocus: true,
    selectFirst: true,
    open: function(event, ui) {  select=false; target_el=event.target },
    select: function(event, ui) { select=true; $(event.target).val(ui.item.label);
	getRateQuantityAndTaxForSalesFromItemId(ui.item.id,target_el);  
			 }
}).blur(function(){
	
    if(!select)
    {
		
		$(target_el).val("");
    }
 });		
}

function deleteProductTr(elem){
	
	var parent1=elem.parentNode.parentNode.parentNode;
	parent1.innerHTML="";
	}

if($('.addContactbtnCustomer'))
{
$('.addContactbtnCustomer').click(function(e) {
	
    var newRowData=document.getElementById('addcontactTrGeneratedCustomer').innerHTML;
	var insertTable=document.getElementById('insertInsuranceTable');
	var newIndex=$('#addcontactTrCustomer').index();
	newIndex=newIndex+2;
	var newRow=insertTable.insertRow(newIndex);
	newRow.innerHTML=newRowData;
});
}

if($('.addContactbtnCustomer1'))
{
$('.addContactbtnCustomer1').click(function(e) {
	
    var newRowData=document.getElementById('addcontactTrGeneratedCustomer1').innerHTML;
	var insertTable=document.getElementById('insertInsuranceTable');
	var newIndex=$('#addcontactTrCustomer1').index();
	newIndex=newIndex+2;
	var newRow=insertTable.insertRow(newIndex);
	newRow.innerHTML=newRowData;
});
}

if($('.addContactbtnCustomer2'))
{
$('.addContactbtnCustomer2').click(function(e) {
	
    var newRowData=document.getElementById('addcontactTrGeneratedCustomer2').innerHTML;
	var insertTable=document.getElementById('insertInsuranceTable');
	var newIndex=$('#addcontactTrCustomer2').index();
	newIndex=newIndex+2;
	var newRow=insertTable.insertRow(newIndex);
	newRow.innerHTML=newRowData;
});
}

function deleteContactTr(elem){
	
	var parent1=elem.parentNode.parentNode.parentNode;
	parent1.innerHTML="";
	}
