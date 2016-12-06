<?php 
require_once("cg.php");
require_once("bd.php");
require_once("common.php");
require_once("account-ledger-functions.php");
require_once("account-head-functions.php");

		
function listTaxs(){
	
	try
	{
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
		$sql="SELECT tax_id,CONCAT(IF(in_out=2,'IN PURCHASE',IF(in_out=1,'OUT',IF(in_out=3,'IN SALE','IN'))), ' ', tax_name) as tax_name,tax_percent,in_out, tax_ledger_id
		      FROM edms_tax,edms_ac_ledgers WHERE tax_ledger_id = ledger_id AND our_company_id = $our_company_id";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		return $resultArray; 
	}
	catch(Exception $e)
	{
	}
}

function listTaxClasses(){
	
	try
	{
		
		$sql="SELECT tax_class_id,tax_class, tax_form_id
		      FROM edms_tax_class";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		return $resultArray; 
	}
	catch(Exception $e)
	{
	}
}

function getTaxClassById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT tax_class_id,tax_class, tax_form_id
		      FROM edms_tax_class WHERE tax_class_id = $id";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		return $resultArray[0];
		}
	}
	catch(Exception $e)
	{
	}
}


function getTaxClassByLedgerId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT edms_ac_ledgers.tax_class_id, tax_class, tax_form_id
		      FROM edms_ac_ledgers, edms_tax_class WHERE edms_ac_ledgers.tax_class_id = edms_tax_class.tax_class_id AND ledger_id = $id AND edms_ac_ledgers.tax_class_id IS NOT NULL";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else 
		{
		$new_array = array("tax_class_id"=>0,"tax_class"=>"Not Applicable");
		$return_array = array();
		
		$return_array[] = $new_array;	
		return $return_array;
		}
		}
	}
	catch(Exception $e)
	{
	}
}


function listTaxForms(){
	
	try
	{
		
		$sql="SELECT tax_form_id,form_name
		      FROM edms_tax_forms";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		return $resultArray; 
	}
	catch(Exception $e)
	{
	}
}

function getTaxFormById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT tax_form_id,form_name
		      FROM edms_tax_forms WHERE tax_form_id = $id";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		return $resultArray[0];
		}
	}
	catch(Exception $e)
	{
	}
}


function getTaxFormByTaxClassId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT tax_form_id,form_name
		      FROM edms_tax_forms, edms_tax_class WHERE edms_tax_forms.tax_form_id = edms_tax_class.tax_form_id AND tax_class_id = $id";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
}

function getMainTaxPercentForTaxGroupId($tax_group_id)
{
	
	if(checkForNumeric($tax_group_id))
	{
	$sql="SELECT edms_tax.tax_id, tax_name , tax_percent, edms_tax.in_out
			  FROM 
			  edms_tax, edms_rel_tax_grp_tax 
			  WHERE tax_group_id=$tax_group_id
			  AND edms_tax.tax_id = edms_rel_tax_grp_tax.tax_id
			  ORDER BY tax_percent DESC";
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);

	if(dbNumRows($result)>0)
	{
		return $resultArray[0][2];
		}
	else
	{
		return false;
		}
	}
	return false;
	
}

function listTaxIds(){
	
	try
	{
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
		$sql="SELECT tax_id
		      FROM edms_tax ,edms_ac_ledgers WHERE tax_ledger_id = ledger_id AND our_company_id = $our_company_id";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		return $resultArray; 
	}
	catch(Exception $e)
	{
	}
}


function listTaxsAlpha(){
	
	try
	{
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
		$sql="SELECT tax_id,tax_percent,tax_name,in_out,tax_ledger_id
		      FROM edms_tax,edms_ac_ledgers WHERE tax_ledger_id = ledger_id AND our_company_id = $our_company_id
			  ORDER BY tax_name";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		return $resultArray; 
	}
	catch(Exception $e)
	{
	}
}


function insertTax($name,$display_name,$tax_percent,$in_out){
	
	try
	{
		
		$name=clean_data($name);
		$name = ucfirst(strtolower($name));
		$duplicate=checkForDuplicateTax($name,$tax_percent,$in_out);
		
		if(checkForNumeric($tax_percent,$in_out) && validateForNull($name) && !$duplicate)
		{
			$tax_head_id=getTaxHeadId();
			if($in_out==0)
			$tax_ledger_name = "In ".$name;
			else if($in_out==1)
		    $tax_ledger_name = "Out ".$name;
			else if($in_out==2)
		    $tax_ledger_name = "In Purchase ".$name;
			else if($in_out==3)
		    $tax_ledger_name = "In Sale ".$name;
			$tax_ledger_id=insertLedger($tax_ledger_name,'','',-1,'',NULL,$tax_head_id,NULL,'','','',0,0);
			
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO
		      edms_tax (tax_name,display_name, tax_percent, in_out, tax_ledger_id)
			  VALUES
			  ('$name', '$display_name', $tax_percent , $in_out, $tax_ledger_id)";
		$result=dbQuery($sql);	
			
		return dbInsertId();
		
		}
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}


function insertTaxClass($name,$tax_form_id="NULL"){
	
	try
	{
		
		if(!checkForNumeric($tax_form_id) && $tax_form_id<1)
		$tax_form_id="NULL";
		$name=clean_data($name);
		$name = ucfirst(strtolower($name));
		$duplicate=checkForDuplicateTaxClass($name);
		if(validateForNull($name) && !$duplicate)
		{
			
			
		
		$sql="INSERT INTO
		      edms_tax_class (tax_class, tax_form_id)
			  VALUES
			  ('$name',$tax_form_id)";
		$result=dbQuery($sql);	
			
		return dbInsertId();
		
		}
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}

function updateTaxClass($id,$name,$tax_form_id="NULL"){
	
	try
	{
		if(!checkForNumeric($tax_form_id) && $tax_form_id<1)
		$tax_form_id="NULL";
		$name=clean_data($name);
		$name = ucfirst(strtolower($name));
		$duplicate=checkForDuplicateTaxClass($name,$id);
		if(validateForNull($name) && !$duplicate)
		{
		$sql="UPDATE
		      edms_tax_class SET tax_class = '$name', tax_form_id = $tax_form_id
			  WHERE tax_class_id = $id";
		$result=dbQuery($sql);	
		return dbInsertId();
		}
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}


function checkForDuplicateTaxClass($tax_class,$id=false)
{
	if(validateForNull($tax_class))
	{
		$sql="SELECT tax_class_id FROM edms_tax_class WHERE tax_class = '$tax_class'";
		if(checkForNumeric($id))
		$sql=$sql." AND tax_class_id != $id";
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0][0]; //duplicate found
		}
		else 
		return false; 
	}
	return false;
}

function deleteTaxClass($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfTaxClassInUse($id))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="DELETE FROM
			  edms_tax_class
			  WHERE tax_class_id=$id";
		dbQuery($sql);	
		return  "success";
		}
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}

function checkIfTaxClassInUse($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT tax_class_id FROM edms_ac_ledgers WHERE tax_class_id = $id";
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0][0]; //duplicate found
		}
		
		$sql="SELECT tax_class_id FROM edms_tax_grp WHERE tax_class_id = $id";
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0][0]; //duplicate found
		}
		
		$sql="SELECT tax_class_id FROM edms_tax_grp WHERE tax_class_id = $id";
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0][0]; //duplicate found
		}
		
		$sql="SELECT tax_class_id FROM edms_ac_sales_item WHERE tax_class_id = $id";
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0][0]; //duplicate found
		}
		$sql="SELECT tax_class_id FROM edms_ac_sales_nonstock WHERE tax_class_id = $id";
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0][0]; //duplicate found
		}
		
		$sql="SELECT tax_class_id FROM edms_ac_purchase_item WHERE tax_class_id = $id";
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0][0]; //duplicate found
		}
		$sql="SELECT tax_class_id FROM edms_ac_purchase_nonstock WHERE tax_class_id = $id";
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0][0]; //duplicate found
		}
		
		$sql="SELECT tax_class_id FROM edms_ac_debit_note_item WHERE tax_class_id = $id";
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0][0]; //duplicate found
		}
		$sql="SELECT tax_class_id FROM edms_ac_debit_note_nonstock WHERE tax_class_id = $id";
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0][0]; //duplicate found
		}
		
		$sql="SELECT tax_class_id FROM edms_ac_credit_note_item WHERE tax_class_id = $id";
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0][0]; //duplicate found
		}
		$sql="SELECT tax_class_id FROM edms_ac_credit_note_nonstock WHERE tax_class_id = $id";
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0][0]; //duplicate found
		}
		
		return false;
		
		
	}
	
}


function insertTaxForm($name){
	
	try
	{
		
		$name=clean_data($name);
		$name = ucfirst(strtolower($name));
		$duplicate=checkForDuplicateTaxForm($name);
		
		if(validateForNull($name) && !$duplicate)
		{
			
			
		
		$sql="INSERT INTO
		      edms_tax_forms (form_name)
			  VALUES
			  ('$name')";
		$result=dbQuery($sql);	
			
		return dbInsertId();
		
		}
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}

function updateTaxForm($id,$name){
	
	try
	{
		$name=clean_data($name);
		$name = ucfirst(strtolower($name));
		$duplicate=checkForDuplicateTaxForm($name,$id);
		if(validateForNull($name) && !$duplicate)
		{
		$sql="UPDATE
		      edms_tax_forms SET form_name = '$name'
			  WHERE tax_form_id = $id";
		$result=dbQuery($sql);	
		return dbInsertId();
		}
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}


function checkForDuplicateTaxForm($tax_form,$id=false)
{
	if(validateForNull($tax_form))
	{
		$sql="SELECT tax_form_id FROM edms_tax_forms WHERE form_name = '$tax_form'";
		if(checkForNumeric($id))
		$sql=$sql." AND tax_form_id != $id";
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0][0]; //duplicate found
		}
		else 
		return false; 
	}
	return false;
}

function deleteTaxForm($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfTaxFormInUse($id))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="DELETE FROM
			  edms_tax_forms
			  WHERE tax_form_id=$id";
		dbQuery($sql);	
		return  "success";
		}
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}

function checkIfTaxFormInUse($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT tax_form_id FROM edms_tax_class WHERE tax_form_id = $id";
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0][0]; //duplicate found
		}
		
		
		return false;
		
		
	}
	
}


function deleteTax($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfTaxInUse($id))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="DELETE FROM
			  edms_tax
			  WHERE tax_id=$id";
		dbQuery($sql);	
		return  "success";
		}
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}



function updateTax($id,$name,$display_name,$tax_percent,$in_out){
	
	try
	{
		$name=clean_data($name);
		$name = ucfirst(strtolower($name));
		$display_name = clean_data($display_name);
		if(!validateForNull($display_name))
		$display_name=$name;
		$duplicate=checkForDuplicateTax($name,$tax_percent,$in_out,$id);
		if(checkForNumeric($tax_percent,$in_out) && validateForNull($name) && checkForNumeric($id) && !$duplicate)
		{
			
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="UPDATE edms_tax
			  SET tax_name='$name',display_name = '$display_name', tax_percent=$tax_percent , in_out = $in_out
			  WHERE tax_id=$id";	  
		dbQuery($sql);
		return "success";	
		}
		else
		{
			return "error";
		}
	}
	catch(Exception $e)
	{
	}
	
}

function checkForDuplicateTax($name,$tax_percent,$in_out,$id=false)
{
	try{
		$sql="SELECT tax_id 
			  FROM 
			  edms_tax 
			  WHERE tax_name='$name'
			  AND tax_percent=$tax_percent AND in_out = $in_out ";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND tax_id!=$id";	  
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0][0]; //duplicate found
			} 
		else
		{
			return false;
			}	 
		}
	catch(Exception $e)
	{
		
		}
	
	}

function getTaxByID($id)
{
	$sql="SELECT tax_id,tax_percent, tax_name, display_name, in_out, tax_ledger_id
			  FROM 
			  edms_tax 
			  WHERE tax_id=$id";
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		return $resultArray[0];
		}
	else
	{
		return false;
		}
	}

function getTaxNameByID($id)
{
	$sql="SELECT  tax_name
			  FROM 
			  edms_tax 
			  WHERE tax_id=$id";
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		return $resultArray[0][0];
		}
	else
	{
		return false;
		}
	}	
function checkIfTaxInUse($id)
{
	
	
		$sql="SELECT tax_id
	      FROM edms_ac_sales_tax
		  WHERE tax_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	{
		
		return true;
		}
	
	$sql="SELECT tax_id
	      FROM edms_ac_purchase_tax
		  WHERE tax_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	{
		
		return true;
		}	
	
		$sql="SELECT tax_id
	      FROM edms_ac_credit_note_tax
		  WHERE tax_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	{
		
		return true;
		}
	
	$sql="SELECT tax_id
	      FROM edms_ac_debit_note_tax
		  WHERE tax_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	{
		
		return true;
		}		
		
		return false;
			
		  
	}	

function listTaxsFromTaxGroupId($tax_group_id)
{
	if(checkForNumeric($tax_group_id))
	{
	$sql="SELECT edms_tax.tax_id, tax_name , tax_percent, edms_tax.in_out
			  FROM 
			  edms_tax, edms_rel_tax_grp_tax 
			  WHERE tax_group_id=$tax_group_id
			  AND edms_tax.tax_id = edms_rel_tax_grp_tax.tax_id
			  ORDER BY tax_name";
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		return $resultArray;
		}
	else
	{
		return false;
		}
	}
	return false;
}

function getTotalTaxPercentForTaxGroup($tax_group_id)
{
	$tax_percent = 0;
	if(checkForNumeric($tax_group_id))
	{
		$taxes = listTaxsFromTaxGroupId($tax_group_id);
		
		foreach($taxes as $tax)
		{
			$tax_percent = $tax_percent + $tax['tax_percent'];	
		}	
		
	}	
	return $tax_percent;
}
	


function getTaxIdFromName($name)
{
	$sql="SELECT tax_id
			  FROM 
			  edms_tax 
			  WHERE tax_name='$name'";
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		return $resultArray[0][0];
		}
	else
	{
		return false;
		}
	}	

function insertTaxGroup($name,$display_name,$tax_array,$tax_class)
{
	if(TAX_CLASS==0)
	$tax_class="NULL";
	else
	if(!checkForNumeric($tax_class) || $tax_class<1)
	return "error";
	if(is_array($tax_array) && count($tax_array)>0)
	{
		$main_tax_id=$tax_array[0];
		$main_tax = getTaxByID($main_tax_id);
		$main_tax_in_out = $main_tax['in_out'];
		foreach($tax_array as $tax_id)
		{
		$tax = getTaxByID($tax_id);	
		$tax_in_out = $tax ['in_out']; 	
		if($main_tax_in_out!=$tax_in_out)
		return "error";	
		}	
	}
	
	$group_id=insertTaxGroupName($name,$display_name,$main_tax_in_out,$tax_class);
	
	if(checkForNumeric($group_id))
	{
	insertTaxsToGroup($tax_array,$group_id);
	return "success";
	}
	else 
	return "error";
}	
		
function insertTaxGroupName($name,$display_name,$in_out,$tax_class_id)
{
	$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
	$display_name = clean_data($display_name);
	$name = clean_data($name);
	if(!validateForNull($display_name))
	$display_name=$name;
	if(validateForNull($name) && strlen($name)<255 && checkForNumeric($in_out,$our_company_id) && !checkForDuplicateTaxGroup($name,$in_out) && ((checkForNumeric($tax_class_id) && $tax_class_id>0) || TAX_CLASS==0) )
	{
		
		$name=trim($name);
		
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_tax_grp (tax_group_name,display_name, in_out,our_company_id,tax_class_id)
		       VALUES('$name' , '$display_name', $in_out, $our_company_id,$tax_class_id)";
		dbQuery($sql);
		return dbInsertId();	   
		
		}
	return "error";	
	}		

function checkForDuplicateTaxGroup($name,$in_out,$id=false)
{
	$sql="SELECT tax_group_id 
			  FROM 
			  edms_tax_grp 
			  WHERE tax_group_name='$name' AND in_out = $in_out";
			  
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND tax_group_id!=$id";		  
		
		$result=dbQuery($sql);	
		
		if(dbNumRows($result)>0)
		{
			return true; //duplicate found
			} 
		else
		{
			return false;
			}	 
	
	
	}	
	

function editTaxGroupName($id,$name,$display_name,$in_out,$tax_class_id)
{
	
	$display_name = clean_data($display_name);
	if(!validateForNull($display_name))
	$display_name=$name;
	
	if(validateForNull($name) && strlen($name)<255 && !checkForDuplicateTaxGroup($name,$in_out,$id) && checkForNumeric($id) && ((checkForNumeric($tax_class_id) && $tax_class_id>0) || TAX_CLASS==0))
	{
		$name=trim($name);
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="UPDATE  edms_tax_grp
		      SET tax_group_name='$name', display_name = '$display_name', tax_class_id = $tax_class_id
		      WHERE tax_group_id=$id";
			  
		dbQuery($sql);
		return "success";	   
		
		}
	return "error";	
	
	}	

function deleteTaxGroup($id)
{
	if(checkForNumeric($id))
	{
		$sql="DELETE FROM edms_tax_grp WHERE tax_group_id=$id";
		dbQuery($sql);
		return "success";
		}
	return "error";	
}	

function insertTaxsToGroup($area_array,$tax_group_id)
{
	if(count($area_array)>0 && is_numeric($tax_group_id))
	{
	foreach($area_array as $tax_id)
	{	
	$sql="INSERT INTO edms_rel_tax_grp_tax (tax_group_id,tax_id)
	      VALUES($tax_group_id,$tax_id)";
	dbQuery($sql);
	  
	}
	return "success";	
	
	}
	return "error";
	}

function deleteRelTaxGroupByTaxID($tax_id)
{
	if(checkForNumeric($tax_id))
	{
		$sql="DELETE FROM edms_rel_tax_grp_tax WHERE tax_id=$tax_id";
		dbQuery($sql);
		return "success";
		}
	return "error";	
	
	}

function deleteRelTaxGroupByGrpID($tax_group_id)
{
	if(checkForNumeric($tax_group_id))
	{
		$sql="DELETE FROM edms_rel_tax_grp_tax WHERE tax_group_id=$tax_group_id";
		dbQuery($sql);
		return "success";
		}
	return "error";	
	
	}
	
function editTaxGroup($id,$name,$display_name,$area_array,$tax_class_id)
{
	if(TAX_CLASS==0)
	$tax_class_id="NULL";
	else
	if(!checkForNumeric($tax_class_id) || $tax_class_id<1)
	return "error";
	$tax_array = $area_array;
	if(is_array($tax_array) && count($tax_array)>0)
	{
		$main_tax_id=$tax_array[0][0];
		$main_tax = getTaxByID($main_tax_id);
		$main_tax_in_out = $main_tax['in_out'];
		foreach($tax_array as $tax_id)
		{
		$tax = getTaxByID($tax_id);	
		$tax_in_out = $tax ['in_out']; 	
		if($main_tax_in_out!=$tax_in_out)
		return "error";	
		}	
	}
	
	$result=editTaxGroupName($id,$name,$display_name,$main_tax_in_out,$tax_class_id);
	if($result!="error")
	{
	$result1=deleteRelTaxGroupByGrpID($id);
	$result2=insertTaxsToGroup($area_array,$id);
	if($result1=="success" && $result2=="success")
	return "success";
	else
	return "error";
	}
	return "error";
	}		
	
function listTaxGroups()
{
	$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
	$sql="SELECT edms_tax_grp.tax_group_id,CONCAT(IF(in_out=2,'IN PURCHASE',IF(in_out=1,'OUT',IF(in_out=3,'IN SALE','IN'))), ' ', tax_group_name) as tax_group_name,GROUP_CONCAT(tax_id) as taxes_id, in_out
	      FROM edms_tax_grp
		  LEFT JOIN edms_rel_tax_grp_tax
		  ON edms_tax_grp.tax_group_id=edms_rel_tax_grp_tax.tax_group_id
		  WHERE our_company_id = $our_company_id
		  GROUP BY edms_tax_grp.tax_group_id";
	$result=dbQuery($sql);	 
	
	$resultArray =  dbResultToArray($result);	
	$returnArray[] = array('tax_group_id'=>0,'tax_group_name'=>'Not Applicable','taxes_id'=>'','in_out'=>-1);
	if(dbNumRows($result)>0)
	{
	foreach($resultArray as $re)
	{
		$returnArray[]=$re;	
	}
	}
	return $returnArray;
	
}		
	
function getTaxGroupsForTaxClassId($tax_class_id)
{
	$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
	
	if(!checkForNumeric($tax_class_id))
	$tax_class_id=0;
	
	
	$sql="SELECT edms_tax_grp.tax_group_id,CONCAT(IF(in_out=2,'IN PURCHASE',IF(in_out=1,'OUT',IF(in_out=3,'IN SALE','IN'))), ' ', tax_group_name) as tax_group_name,GROUP_CONCAT(tax_id) as taxes_id, in_out
	      FROM edms_tax_grp
		  LEFT JOIN edms_rel_tax_grp_tax
		  ON edms_tax_grp.tax_group_id=edms_rel_tax_grp_tax.tax_group_id
		  WHERE our_company_id = $our_company_id AND tax_class_id = $tax_class_id
		  GROUP BY edms_tax_grp.tax_group_id";
		  
	$result=dbQuery($sql);	 
	
	$resultArray =  dbResultToArray($result);	
	$returnArray[] = array('tax_group_id'=>0,'tax_group_name'=>'Not Applicable','taxes_id'=>'','in_out'=>-1);
	if(dbNumRows($result)>0)
	{
	foreach($resultArray as $re)
	{
		$returnArray[]=$re;	
	}
	}
	return $returnArray;
	
	
}		
		

function getTaxGroupByID($id)
{
	$sql="SELECT edms_tax_grp.tax_group_id,GROUP_CONCAT(tax_id) as taxes_id, in_out,CONCAT(IF(in_out=2,'IN PURCHASE',IF(in_out=1,'OUT',IF(in_out=3,'IN SALE','IN'))), ' ', tax_group_name) as tax_group_name, tax_group_name as unprefixed_name, tax_class_id
	      FROM edms_tax_grp
		  LEFT JOIN edms_rel_tax_grp_tax
		  ON edms_tax_grp.tax_group_id=edms_rel_tax_grp_tax.tax_group_id
		  WHERE edms_tax_grp.tax_group_id=$id
		  GROUP BY edms_tax_grp.tax_group_id";
	$result=dbQuery($sql);	
	$resultArray=dbResultToArray($result); 
	if(dbNumRows($result)>0)
	return $resultArray[0];
	else 
	return "error"; 
}

function getTaxGroupNameByID($id)
{
	
	$sql="SELECT CONCAT(IF(in_out=2,'IN PURCHASE',IF(in_out=1,'OUT',IF(in_out=3,'IN SALE','IN'))), ' ', tax_group_name) as tax_group_name
	      FROM edms_tax_grp
		  WHERE edms_tax_grp.tax_group_id=$id";
	$result=dbQuery($sql);	
	$resultArray=dbResultToArray($result); 
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
	else 
	return "error"; 
}

function getTaxForVehicleId($vehicle_id)
{
	if(checkForNumeric($vehicle_id))
	{
		$sql="SELECT purchase_id, vehicle_id,  edms_tax_grp.tax_group_id, tax_group_name, SUM(tax_amount) as tax_amount FROM edms_ac_purchase_tax, edms_tax_grp WHERE edms_tax_grp.tax_group_id = edms_ac_purchase_tax.tax_group_id AND edms_ac_purchase_tax.vehicle_id = $vehicle_id  GROUP BY vehicle_id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return false;
		
		}
	return false;
}

function getPurchaseTaxesForLedgerIdMonthWiseBetweenDates($from_ledger,$from=NULL,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{

	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($from_ledger);
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		}	
	
	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger)  && $head_type!=3 && $head_type!=4))
	{
	$sql="SELECT purchase_id,SUM(tax_amount),from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, edms_ac_purchase.created_by,edms_ac_purchase.last_updated_by,edms_ac_purchase.date_added,edms_ac_purchase.date_modified
			  FROM edms_ac_purchase , edms_ac_purchase_tax, edms_tax WHERE edms_ac_purchase.purchase_id = edms_ac_purchase_tax.purchase_id AND edms_tax.tax_id = edms_ac_purchase_tax.tax_id AND edms_ac_purchase.oc_id = $oc_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==2)  	  
	$sql=$sql." tax_ledger_id=$from_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
	$sql=$sql." from_ledger_id=$from_ledger";
	else if(!isset($head_type))
	$sql=$sql." from_customer_id=$from_customer";
	$sql=$sql." GROUP BY month_year";	  		  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
	}
		
function getTotalPurchasesTaxAmountForLedgerIdUptoDate($from_ledger,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{

	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($from_ledger);
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		}	
	
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger)  && $head_type!=3 && $head_type!=4))
	{
	$sql="SELECT edms_ac_purchase.purchase_id,SUM(tax_amount),from_ledger_id,to_ledger_id,from_customer_id
			  FROM edms_ac_purchase , edms_ac_purchase_tax, edms_tax WHERE edms_ac_purchase.purchase_id = edms_ac_purchase_tax.purchase_id AND edms_tax.tax_id = edms_ac_purchase_tax.tax_id AND edms_ac_purchase.oc_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==2)  	  
	$sql=$sql." tax_ledger_id=$from_ledger GROUP BY tax_ledger_id";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
	$sql=$sql." from_ledger_id=$from_ledger GROUP BY from_ledger_id";
	else if(!isset($head_type))
	$sql=$sql." from_customer_id=$from_customer GROUP BY from_customer_id";	  		  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];
	else
	return 0; 	
	}
	return 0;
	}	

function getPurchasesTaxesForLedgerIdForMonth($from_ledger,$month_id,$year,$from=NULL,$to=NULL)
{
	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($from_ledger);
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		}	
	
	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if((checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger)  && $head_type!=3 && $head_type!=4)) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT edms_ac_purchase_tax.purchase_tax_id,edms_ac_purchase.purchase_id,tax_amount as amount,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, edms_ac_purchase.created_by,edms_ac_purchase.last_updated_by,edms_ac_purchase.date_added,edms_ac_purchase.date_modified
			  FROM edms_ac_purchase,edms_ac_purchase_item, edms_ac_purchase_tax , edms_tax WHERE edms_ac_purchase.purchase_id = edms_ac_purchase_item.purchase_id AND edms_ac_purchase_item.purchase_item_id = edms_ac_purchase_tax.purchase_item_id AND edms_tax.tax_id = edms_ac_purchase_tax.tax_id AND edms_ac_purchase.oc_id = $oc_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==2)  	  
	$sql=$sql." tax_ledger_id=$from_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
	$sql=$sql." from_ledger_id=$from_ledger";
	else if(!isset($head_type))
	$sql=$sql." from_customer_id=$from_customer";
	
	$sql=$sql." AND DATE_FORMAT(trans_date,'%c')=$month_id AND DATE_FORMAT(trans_date,'%Y') = $year";		  
	$result=dbQuery($sql);
	
	$resultArray=dbResultToArray($result);
	
	
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
}	

function getTotalPurchaseTaxesForLedgerIdForMonth($from_ledger,$month_id,$year,$from=NULL,$to=NULL)
{
	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($from_ledger);
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		}	
	

	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if((checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger)  && $head_type!=3 && $head_type!=4)) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT edms_ac_purchase.purchase_id,SUM(tax_amount) as total_amount,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, edms_ac_purchase.created_by,edms_ac_purchase.last_updated_by,edms_ac_purchase.date_added,edms_ac_purchase.date_modified
			  FROM edms_ac_purchase , edms_ac_purchase_tax, edms_tax WHERE edms_ac_purchase.purchase_id = edms_ac_purchase_tax.purchase_id AND edms_tax.tax_id = edms_ac_purchase_tax.tax_id AND edms_ac_purchase.oc_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==2)  	  
	$sql=$sql." tax_ledger_id=$from_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
	$sql=$sql." from_ledger_id=$from_ledger";
	else if(!isset($head_type))
	$sql=$sql." from_customer_id=$from_customer";
	
	$sql=$sql." AND DATE_FORMAT(trans_date,'%c')=$month_id AND DATE_FORMAT(trans_date,'%Y') = $year GROUP BY month_year";	
			  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];
	else
	return 0; 	
	}
	return 0;
}			

function getPurchasesTaxesForLedgerIdBetweenDates($from_ledger,$from=NULL,$to=NULL)
{
	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($from_ledger);
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		}	
	
	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger)  && $head_type!=3 && $head_type!=4))
	{
	$sql="SELECT edms_ac_purchase_tax.purchase_tax_id,edms_ac_purchase.purchase_id,tax_amount as amount,edms_ac_purchase_tax.tax_id,tax_group_id,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,edms_ac_purchase.created_by,edms_ac_purchase.last_updated_by,edms_ac_purchase.date_added,edms_ac_purchase.date_modified
			  FROM edms_ac_purchase , edms_ac_purchase_tax, edms_tax WHERE edms_ac_purchase.purchase_id = edms_ac_purchase_tax.purchase_id AND edms_tax.tax_id = edms_ac_purchase_tax.tax_id AND edms_ac_purchase.oc_id = $oc_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==2)  	  
	$sql=$sql." tax_ledger_id=$from_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
	$sql=$sql." from_ledger_id=$from_ledger";
	else if(!isset($head_type))
	$sql=$sql." from_customer_id=$from_customer";  		  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
}	

function getSalesTaxesForLedgerIdMonthWise($to_ledger,$from=NULL,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{
	
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$head_type=getLedgerHeadType($to_ledger);
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		}
	
	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger))  && $head_type!=3 && $head_type!=4)
	{
	$sql="SELECT edms_ac_sales.sales_id,SUM(tax_amount),from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_sales, edms_ac_sales_tax,edms_tax WHERE edms_ac_sales.sales_id = edms_ac_sales_tax.sales_id AND  edms_ac_sales_tax.tax_id = edms_tax.tax_id AND edms_ac_sales.oc_id = $oc_id
			  AND ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==2)  	  
	$sql=$sql." tax_ledger_id=$to_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
	$sql=$sql." to_ledger_id=$to_ledger";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$to_customer";
	$sql=$sql." GROUP BY month_year";	  			  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
	}
	
function getTotalSalesTaxesAmountForLedgerIdUptoDate($to_ledger,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$head_type=getLedgerHeadType($to_ledger);
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		}
	
	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger))  && $head_type!=3 && $head_type!=4)
	{
	$sql="SELECT edms_ac_sales.sales_id,SUM(tax_amount),from_ledger_id,to_ledger_id,to_customer_id
			  FROM edms_ac_sales, edms_ac_sales_tax,edms_tax WHERE edms_ac_sales.sales_id = edms_ac_sales_tax.sales_id AND  edms_ac_sales_tax.tax_id = edms_tax.tax_id AND edms_ac_sales.oc_id = $oc_id AND ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==2)  	  
	$sql=$sql." tax_ledger_id=$to_ledger GROUP BY from_ledger_id";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
	$sql=$sql." to_ledger_id=$to_ledger GROUP BY to_ledger_id";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$to_customer GROUP BY to_customer_id";			  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];
	else
	return 0; 	
	}
	return 0;
	}	

function getSalesTaxesForLedgerIdForMonth($to_ledger,$month_id,$year,$from=NULL,$to=NULL)
{
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$head_type=getLedgerHeadType($to_ledger);
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		}
	
	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if((checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger)  && $head_type!=3 && $head_type!=4)) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT edms_ac_sales_tax.sales_tax_id,edms_ac_sales.sales_id,tax_amount,tax_amount as amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_sales, edms_ac_sales_tax,edms_tax WHERE edms_ac_sales.sales_id = edms_ac_sales_tax.sales_id AND  edms_ac_sales_tax.tax_id = edms_tax.tax_id AND edms_ac_sales.oc_id = $oc_id AND ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==2)  	  
	$sql=$sql." tax_ledger_id=$to_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0 || $head_type==0))  
	$sql=$sql." to_ledger_id=$to_ledger";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$to_customer";
	$sql=$sql."  AND DATE_FORMAT(trans_date,'%c')=$month_id AND DATE_FORMAT(trans_date,'%Y') = $year";	
				  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
	}	
	
function getTotalSalesTaxeForLedgerIdForMonth($to_ledger,$month_id,$year,$from=NULL,$to=NULL)
{
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$head_type=getLedgerHeadType($to_ledger);
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		}
	
	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if((checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger)  && $head_type!=3 && $head_type!=4)) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT edms_ac_sales.sales_id,SUM(tax_amount) as total_amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_sales, edms_ac_sales_tax,edms_tax WHERE edms_ac_sales.sales_id = edms_ac_sales_tax.sales_id AND  edms_ac_sales_tax.tax_id = edms_tax.tax_id AND edms_ac_sales.oc_id = $oc_id AND ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==2)  	  
	$sql=$sql." tax_ledger_id=$to_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
	$sql=$sql." to_ledger_id=$to_ledger";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$to_customer";
	$sql=$sql."  AND DATE_FORMAT(trans_date,'%c')=$month_id AND DATE_FORMAT(trans_date,'%Y') = $year GROUP BY month_year";	  			  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	return $resultArray[0][1];
	else
	return 0; 	
	}
	return 0;
	}		

function getSalesTaxesForLedgerIdBetweenDates($to_ledger,$from=NULL,$to=NULL)
{
		
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$head_type=getLedgerHeadType($to_ledger);
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		}
	
	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger)  && $head_type!=3 && $head_type!=4))
	{
	$sql="SELECT edms_ac_sales_tax.sales_tax_id,edms_ac_sales.sales_id,tax_amount as amount,edms_ac_sales_tax.tax_id,tax_group_id,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,edms_ac_sales.created_by,edms_ac_sales.last_updated_by,edms_ac_sales.date_added,edms_ac_sales.date_modified
			  FROM edms_ac_sales , edms_ac_sales_tax, edms_tax WHERE edms_ac_sales.sales_id = edms_ac_sales_tax.sales_id AND edms_tax.tax_id = edms_ac_sales_tax.tax_id AND edms_ac_sales.oc_id = $oc_id AND ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==2)  	  
	$sql=$sql." tax_ledger_id=$to_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
	$sql=$sql." to_ledger_id=$to_ledger";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$to_customer";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
}


function getDebitNoteTaxesForLedgerIdMonthWiseBetweenDates($from_ledger,$from=NULL,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{

	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($from_ledger);
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		}	
	
	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger)  && $head_type!=3 && $head_type!=4))
	{
	$sql="SELECT debit_note_id,SUM(tax_amount),from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, edms_ac_debit_note.created_by,edms_ac_debit_note.last_updated_by,edms_ac_debit_note.date_added,edms_ac_debit_note.date_modified
			  FROM edms_ac_debit_note , edms_ac_debit_note_tax, edms_tax WHERE edms_ac_debit_note.debit_note_id = edms_ac_debit_note_tax.debit_note_id AND edms_tax.tax_id = edms_ac_debit_note_tax.tax_id AND edms_ac_debit_note.oc_id = $oc_id AND  ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==2)  	  
	$sql=$sql." tax_ledger_id=$from_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
	$sql=$sql." from_ledger_id=$from_ledger";
	else if(!isset($head_type))
	$sql=$sql." from_customer_id=$from_customer";
	$sql=$sql." GROUP BY month_year";	  		  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
	}
		
function getTotalDebitNotesTaxAmountForLedgerIdUptoDate($from_ledger,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{

	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($from_ledger);
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		}	
	
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger)  && $head_type!=3 && $head_type!=4))
	{
	$sql="SELECT edms_ac_debit_note.debit_note_id,SUM(tax_amount),from_ledger_id,to_ledger_id,from_customer_id
			  FROM edms_ac_debit_note , edms_ac_debit_note_tax, edms_tax WHERE edms_ac_debit_note.debit_note_id = edms_ac_debit_note_tax.debit_note_id AND edms_tax.tax_id = edms_ac_debit_note_tax.tax_id AND edms_ac_debit_note.oc_id = $oc_id AND  ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==2)  	  
	$sql=$sql." tax_ledger_id=$from_ledger GROUP BY tax_ledger_id";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
	$sql=$sql." from_ledger_id=$from_ledger GROUP BY from_ledger_id";
	else if(!isset($head_type))
	$sql=$sql." from_customer_id=$from_customer GROUP BY from_customer_id";	  		  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];
	else
	return 0; 	
	}
	return 0;
	}	

function getDebitNotesTaxesForLedgerIdForMonth($from_ledger,$month_id,$year,$from=NULL,$to=NULL)
{
	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($from_ledger);
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		}	
	
	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if((checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger)  && $head_type!=3 && $head_type!=4)) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT edms_ac_debit_note_tax.debit_note_tax_id,edms_ac_debit_note.debit_note_id,tax_amount as amount,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, edms_ac_debit_note.created_by,edms_ac_debit_note.last_updated_by,edms_ac_debit_note.date_added,edms_ac_debit_note.date_modified
			  FROM edms_ac_debit_note,edms_ac_debit_note_item, edms_ac_debit_note_tax , edms_tax WHERE edms_ac_debit_note.debit_note_id = edms_ac_debit_note_item.debit_note_id AND edms_ac_debit_note_item.debit_note_item_id = edms_ac_debit_note_tax.debit_note_item_id AND edms_tax.tax_id = edms_ac_debit_note_tax.tax_id AND edms_ac_debit_note.oc_id = $oc_id AND  ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==2)  	  
	$sql=$sql." tax_ledger_id=$from_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
	$sql=$sql." from_ledger_id=$from_ledger";
	else if(!isset($head_type))
	$sql=$sql." from_customer_id=$from_customer";
	
	$sql=$sql." AND DATE_FORMAT(trans_date,'%c')=$month_id AND DATE_FORMAT(trans_date,'%Y') = $year";		  
	$result=dbQuery($sql);
	
	$resultArray=dbResultToArray($result);
	
	
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
}	

function getTotalDebitNoteTaxesForLedgerIdForMonth($from_ledger,$month_id,$year,$from=NULL,$to=NULL)
{
	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($from_ledger);
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		}	
	

	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if((checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger)  && $head_type!=3 && $head_type!=4)) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT edms_ac_debit_note.debit_note_id,SUM(tax_amount) as total_amount,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, edms_ac_debit_note.created_by,edms_ac_debit_note.last_updated_by,edms_ac_debit_note.date_added,edms_ac_debit_note.date_modified
			  FROM edms_ac_debit_note , edms_ac_debit_note_tax, edms_tax WHERE edms_ac_debit_note.debit_note_id = edms_ac_debit_note_tax.debit_note_id AND edms_tax.tax_id = edms_ac_debit_note_tax.tax_id AND edms_ac_debit_note.oc_id = $oc_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==2)  	  
	$sql=$sql." tax_ledger_id=$from_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
	$sql=$sql." from_ledger_id=$from_ledger";
	else if(!isset($head_type))
	$sql=$sql." from_customer_id=$from_customer";
	
	$sql=$sql." AND DATE_FORMAT(trans_date,'%c')=$month_id AND DATE_FORMAT(trans_date,'%Y') = $year GROUP BY month_year";	
			  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];
	else
	return 0; 	
	}
	return 0;
}			

function getDebitNotesTaxesForLedgerIdBetweenDates($from_ledger,$from=NULL,$to=NULL)
{
	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($from_ledger);
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
		}	
	
	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger)  && $head_type!=3 && $head_type!=4))
	{
	$sql="SELECT edms_ac_debit_note_tax.debit_note_tax_id,edms_ac_debit_note.debit_note_id,tax_amount as amount,edms_ac_debit_note_tax.tax_id,tax_group_id,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,edms_ac_debit_note.created_by,edms_ac_debit_note.last_updated_by,edms_ac_debit_note.date_added,edms_ac_debit_note.date_modified
			  FROM edms_ac_debit_note , edms_ac_debit_note_tax, edms_tax WHERE edms_ac_debit_note.debit_note_id = edms_ac_debit_note_tax.debit_note_id AND edms_tax.tax_id = edms_ac_debit_note_tax.tax_id AND edms_ac_debit_note.oc_id = $oc_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==2)  	  
	$sql=$sql." tax_ledger_id=$from_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
	$sql=$sql." from_ledger_id=$from_ledger";
	else if(!isset($head_type))
	$sql=$sql." from_customer_id=$from_customer";  		  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
}	

function getCreditNoteTaxesForLedgerIdMonthWise($to_ledger,$from=NULL,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{
	
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$head_type=getLedgerHeadType($to_ledger);
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		}
	
	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger))  && $head_type!=3 && $head_type!=4)
	{
	$sql="SELECT edms_ac_credit_note.credit_note_id,SUM(tax_amount),from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_credit_note, edms_ac_credit_note_tax,edms_tax WHERE edms_ac_credit_note.credit_note_id = edms_ac_credit_note_tax.credit_note_id AND  edms_ac_credit_note_tax.tax_id = edms_tax.tax_id AND edms_ac_credit_note.oc_id = $oc_id AND ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==2)  	  
	$sql=$sql." tax_ledger_id=$to_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
	$sql=$sql." to_ledger_id=$to_ledger";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$to_customer";
	$sql=$sql." GROUP BY month_year";	  			  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
	}
	
function getTotalCreditNoteTaxesAmountForLedgerIdUptoDate($to_ledger,$to=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$head_type=getLedgerHeadType($to_ledger);
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		}
	
	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger))  && $head_type!=3 && $head_type!=4)
	{
	$sql="SELECT edms_ac_credit_note.credit_note_id,SUM(tax_amount),from_ledger_id,to_ledger_id,to_customer_id
			  FROM edms_ac_credit_note, edms_ac_credit_note_tax,edms_tax WHERE edms_ac_credit_note.credit_note_id = edms_ac_credit_note_tax.credit_note_id AND  edms_ac_credit_note_tax.tax_id = edms_tax.tax_id AND edms_ac_credit_note.oc_id = $oc_id AND ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==2)  	  
	$sql=$sql." tax_ledger_id=$to_ledger GROUP BY from_ledger_id";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
	$sql=$sql." to_ledger_id=$to_ledger GROUP BY to_ledger_id";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$to_customer GROUP BY to_customer_id";			  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];
	else
	return 0; 	
	}
	return 0;
	}	

function getCreditNoteTaxesForLedgerIdForMonth($to_ledger,$month_id,$year,$from=NULL,$to=NULL)
{
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$head_type=getLedgerHeadType($to_ledger);
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		}
	
	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if((checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger)  && $head_type!=3 && $head_type!=4)) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT edms_ac_credit_note_tax.credit_note_tax_id,edms_ac_credit_note.credit_note_id,tax_amount,tax_amount as amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_credit_note, edms_ac_credit_note_tax,edms_tax WHERE edms_ac_credit_note.credit_note_id = edms_ac_credit_note_tax.credit_note_id AND  edms_ac_credit_note_tax.tax_id = edms_tax.tax_id AND edms_ac_credit_note.oc_id = $oc_id AND ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==2)  	  
	$sql=$sql." tax_ledger_id=$to_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
	$sql=$sql." to_ledger_id=$to_ledger";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$to_customer";
	$sql=$sql."  AND DATE_FORMAT(trans_date,'%c')=$month_id AND DATE_FORMAT(trans_date,'%Y') = $year";	  			  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
	}	
	
function getTotalCreditNoteTaxeForLedgerIdForMonth($to_ledger,$month_id,$year,$from=NULL,$to=NULL)
{
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$head_type=getLedgerHeadType($to_ledger);
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		}
	
	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if((checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger)  && $head_type!=3 && $head_type!=4)) && checkForNumeric($month_id,$year))
	{
	$sql="SELECT edms_ac_credit_note.credit_note_id,SUM(tax_amount) as total_amount,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_credit_note, edms_ac_credit_note_tax,edms_tax WHERE edms_ac_credit_note.credit_note_id = edms_ac_credit_note_tax.credit_note_id AND  edms_ac_credit_note_tax.tax_id = edms_tax.tax_id AND edms_ac_credit_note.oc_id = $oc_id AND ";	
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==2)  	  
	$sql=$sql." tax_ledger_id=$to_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
	$sql=$sql." to_ledger_id=$to_ledger";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$to_customer";
	$sql=$sql."  AND DATE_FORMAT(trans_date,'%c')=$month_id AND DATE_FORMAT(trans_date,'%Y') = $year GROUP BY month_year";	  			  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];
	else
	return 0; 	
	}
	return 0;
	}		

function getCreditNoteTaxesForLedgerIdBetweenDates($to_ledger,$from=NULL,$to=NULL)
{
	
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$head_type=getLedgerHeadType($to_ledger);
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		}
	
	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($to_customer) || (isset($head_type) && checkForNumeric($to_ledger)  && $head_type!=3 && $head_type!=4))
	{
	$sql="SELECT edms_ac_credit_note_tax.credit_note_tax_id,edms_ac_credit_note.credit_note_id,tax_amount as amount,edms_ac_credit_note_tax.tax_id,tax_group_id,from_ledger_id,to_ledger_id,to_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year,edms_ac_credit_note.created_by,edms_ac_credit_note.last_updated_by,edms_ac_credit_note.date_added,edms_ac_credit_note.date_modified
			  FROM edms_ac_credit_note , edms_ac_credit_note_tax, edms_tax WHERE edms_ac_credit_note.credit_note_id = edms_ac_credit_note_tax.credit_note_id AND edms_tax.tax_id = edms_ac_credit_note_tax.tax_id AND edms_ac_credit_note.oc_id = $oc_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==2)  	  
	$sql=$sql." tax_ledger_id=$to_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
	$sql=$sql." to_ledger_id=$to_ledger";
	else if(!isset($head_type))
	$sql=$sql." to_customer_id=$to_customer";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
}	


function getAmountsForTransactionTaxGroupWise($trans_id,$trans_type)
{
	$return_array = array();	
	if($trans_type==1)
	{
		$sql="SELECT SUM(net_amount) FROM edms_ac_purchase_item WHERE purchase_id = $trans_id GROUP BY purchase_id ";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		$total_amount = $resultArray[0][0];
		
		$sql="SELECT  tax_group_id, GROUP_CONCAT(DISTINCT purchase_item_id) as purchase_item_id_string FROM edms_ac_purchase_tax  WHERE edms_ac_purchase_tax.purchase_id = $trans_id AND edms_ac_purchase_tax.purchase_item_id IS NOT NULL GROUP BY tax_group_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		 foreach($resultArray as $re)
		 {
			 $tax_group_id = $re['tax_group_id'];
			 $purchase_item_string = $re['purchase_item_id_string'];
			 
			 $sql="SELECT SUM(net_amount) FROM edms_ac_purchase_item WHERE purchase_id = $trans_id AND purchase_item_id IN ($purchase_item_string) GROUP BY purchase_id ";
			 $result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
		
			if(dbNumRows($result)>0)
			$tax_group_amount = $resultArray[0][0];
			else
			$tax_group_amount = 0;
			
			if($total_amount>0)
			$tax_group_percent = ($tax_group_amount/$total_amount);
			else
			$tax_group_percent=0;
			 $return_array[$tax_group_id] = $tax_group_percent;
		  }
		}
		
		
	}
	if($trans_type==2)
	{
		$sql="SELECT SUM(net_amount) FROM edms_ac_sales_item WHERE sales_id = $trans_id GROUP BY sales_id ";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		$total_amount = $resultArray[0][0];
		
		$sql="SELECT  tax_group_id, GROUP_CONCAT(DISTINCT sales_item_id) as sales_item_id_string FROM edms_ac_sales_tax  WHERE edms_ac_sales_tax.sales_id = $trans_id AND edms_ac_sales_tax.sales_item_id IS NOT NULL GROUP BY tax_group_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		 foreach($resultArray as $re)
		 {
			 $tax_group_id = $re['tax_group_id'];
			 $sales_item_string = $re['sales_item_id_string'];
			 
			 $sql="SELECT SUM(net_amount) FROM edms_ac_sales_item WHERE sales_id = $trans_id AND sales_item_id IN ($sales_item_string) GROUP BY sales_id ";
			 $result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
		
			if(dbNumRows($result)>0)
			$tax_group_amount = $resultArray[0][0];
			else
			$tax_group_amount = 0;
			
			if($total_amount>0)
			$tax_group_percent = ($tax_group_amount/$total_amount);
			else
			$tax_group_percent=0;
			 $return_array[$tax_group_id] = $tax_group_percent;
		  }
		}
	}
	if($trans_type==3)
	{
		$sql="SELECT SUM(net_amount) FROM edms_ac_debit_note_item WHERE debit_note_id = $trans_id GROUP BY debit_note_id ";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		$total_amount = $resultArray[0][0];
		
		$sql="SELECT  tax_group_id, GROUP_CONCAT(DISTINCT debit_note_item_id) as debit_note_item_id_string FROM edms_ac_debit_note_tax  WHERE edms_ac_debit_note_tax.debit_note_id = $trans_id AND edms_ac_debit_note_tax.debit_note_item_id IS NOT NULL GROUP BY tax_group_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		 foreach($resultArray as $re)
		 {
			 $tax_group_id = $re['tax_group_id'];
			 $debit_note_item_string = $re['debit_note_item_id_string'];
			 
			 $sql="SELECT SUM(net_amount) FROM edms_ac_debit_note_item WHERE debit_note_id = $trans_id AND debit_note_item_id IN ($debit_note_item_string) GROUP BY debit_note_id ";
			 $result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
		
			if(dbNumRows($result)>0)
			$tax_group_amount = $resultArray[0][0];
			else
			$tax_group_amount = 0;
			
			if($total_amount>0)
			$tax_group_percent = ($tax_group_amount/$total_amount);
			else
			$tax_group_percent=0;
			 $return_array[$tax_group_id] = $tax_group_percent;
		  }
		}
		
	}
	if($trans_type==4)
	{
		$sql="SELECT SUM(net_amount) FROM edms_ac_credit_note_item WHERE credit_note_id = $trans_id GROUP BY credit_note_id ";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		$total_amount = $resultArray[0][0];
		
		$sql="SELECT  tax_group_id, GROUP_CONCAT(DISTINCT credit_note_item_id) as credit_note_item_id_string FROM edms_ac_credit_note_tax  WHERE edms_ac_credit_note_tax.credit_note_id = $trans_id AND edms_ac_credit_note_tax.credit_note_item_id IS NOT NULL GROUP BY tax_group_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		 foreach($resultArray as $re)
		 {
			 $tax_group_id = $re['tax_group_id'];
			 $credit_note_item_string = $re['credit_note_item_id_string'];
			 
			 $sql="SELECT SUM(net_amount) FROM edms_ac_credit_note_item WHERE credit_note_id = $trans_id AND credit_note_item_id IN ($credit_note_item_string) GROUP BY credit_note_id ";
			 $result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
		
			if(dbNumRows($result)>0)
			$tax_group_amount = $resultArray[0][0];
			else
			$tax_group_amount = 0;
			
			if($total_amount>0)
			$tax_group_percent = ($tax_group_amount/$total_amount);
			else
			$tax_group_percent=0;
			 $return_array[$tax_group_id] = $tax_group_percent;
		  }
		}
	}
	
	return $return_array;
	
}


// TAX FORM FUNCTIONS

function insertTransTaxForm($purchase_id,$sales_id,$credit_note_id,$debit_note_item_id,$tax_form_id,$form_no,$form_date)
{
 	if((checkForNumeric($purchase_id) || checkForNumeric($sales_id) || checkForNumeric($credit_note_id) || checkForNumeric($debit_note_id)) && checkForNumeric($tax_form_id))	
	{
		
		if(!checkForNumeric($purchase_id))
		$purchase_id="NULL";
		if(!checkForNumeric($sales_id))
		$sales_id="NULL";
		if(!checkForNumeric($credit_note_id))
		$credit_note_id="NULL";
		if(!checkForNumeric($debit_note_id))
		$debit_note_id="NULL";
		
		if(!validateForNull($form_no))
		$form_no="";
		if(!validateForNull($form_date))
		$form_date="1970-01-01";
		else
		{
		$form_date = str_replace('/', '-', $form_date);
	    $form_date = date('Y-m-d',strtotime($form_date));
		}
		$sql="INSERT INTO edms_ac_trans_tax_form (purchase_id, sales_id, credit_note_id, debit_note_id, tax_form_id,form_no,form_date) VALUES ($purchase_id,$sales_id,$credit_note_id,$debit_note_id,$tax_form_id,'$form_no','$form_date')";
		dbQuery($sql);
		return dbInsertId();
	}
}

function getTransTaxFormBySalesId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$sql="SELECT sales_id, edms_ac_trans_tax_form.tax_form_id,form_no,form_date,form_name FROM edms_ac_trans_tax_form INNER JOIN edms_tax_forms ON edms_tax_forms.tax_form_id = edms_ac_trans_tax_form.tax_form_id WHERE sales_id = $sales_id";
		$result=dbQuery($sql);
		$resultArray =dbResultToArray($result);
		return $resultArray[0];
		
	}
	return false;
}

function getTransTaxFormByDebitNoteId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$sql="SELECT debit_note_id, edms_ac_trans_tax_form.tax_form_id,form_no,form_date,form_name FROM edms_ac_trans_tax_form INNER JOIN edms_tax_forms ON edms_tax_forms.tax_form_id = edms_ac_trans_tax_form.tax_form_id WHERE debit_note_id = $sales_id";
		
		$result=dbQuery($sql);
		$resultArray =dbResultToArray($result);
		return $resultArray[0];
		
	}
	return false;
}

function getTransTaxFormByCreditNoteId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$sql="SELECT credit_note_id, edms_ac_trans_tax_form.tax_form_id,form_no,form_date,form_name FROM edms_ac_trans_tax_form INNER JOIN edms_tax_forms ON edms_tax_forms.tax_form_id = edms_ac_trans_tax_form.tax_form_id WHERE credit_note_id = $sales_id";
		$result=dbQuery($sql);
		$resultArray =dbResultToArray($result);
		return $resultArray[0];
		
	}
	return false;
}


function getTransTaxFormByPurchaseId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$sql="SELECT purchase_id, edms_ac_trans_tax_form.tax_form_id,form_no,form_date,form_name FROM edms_ac_trans_tax_form INNER JOIN edms_tax_forms ON edms_tax_forms.tax_form_id = edms_ac_trans_tax_form.tax_form_id WHERE purchase_id = $sales_id";
		$result=dbQuery($sql);
		$resultArray =dbResultToArray($result);
		return $resultArray[0];
		
	}
	return false;
}

function deleteTransTaxFormByTransId($trans_id,$trans_type)
{
	if(checkForNumeric($trans_id,$trans_type))
	{
	$sql="DELETE FROM edms_ac_trans_tax_form WHERE ";
	switch($trans_type)
	{
		case 1: $sql=$sql." purchase_id = ";
				break;
		case 2: $sql=$sql." sales_id = ";
				break;
		case 3: $sql=$sql." credit_note_id = ";
				break;
		case 4: $sql=$sql." debit_note_id = ";
				break;						
	}
	$sql=$sql."$trans_id";
	dbQuery($sql);
	return true;
	}
	return false;
}

function getTaxFormIdForTransId($trans_id,$trans_type)
{
	if(checkForNumeric($trans_id,$trans_type))
	{
		switch($trans_type)
		{	
	  case 1: $sql="SELECT edms_tax_class.tax_form_id FROM edms_ac_purchase_item, edms_tax_class WHERE purchase_id = $trans_id AND edms_tax_class.tax_form_id IS NOT NULL AND edms_ac_purchase_item.tax_class_id = edms_tax_class.tax_class_id UNION SELECT edms_tax_class.tax_form_id FROM edms_ac_purchase_nonstock, edms_tax_class WHERE purchase_id = $trans_id AND edms_tax_class.tax_form_id IS NOT NULL AND edms_ac_purchase_nonstock.tax_class_id = edms_tax_class.tax_class_id";
	  break;
	    case 2:$sql="SELECT edms_tax_class.tax_form_id FROM edms_ac_sales_item, edms_tax_class WHERE sales_id = $trans_id AND edms_tax_class.tax_form_id IS NOT NULL AND edms_ac_sales_item.tax_class_id = edms_tax_class.tax_class_id UNION  SELECT edms_tax_class.tax_form_id FROM edms_ac_sales_nonstock, edms_tax_class WHERE sales_id = $trans_id AND edms_tax_class.tax_form_id IS NOT NULL AND edms_ac_sales_nonstock.tax_class_id = edms_tax_class.tax_class_id";
	  break;
	   case 3:$sql="SELECT edms_tax_class.tax_form_id FROM edms_ac_credit_note_item, edms_tax_class WHERE credit_note_id = $trans_id AND edms_tax_class.tax_form_id IS NOT NULL AND edms_ac_credit_note_item.tax_class_id = edms_tax_class.tax_class_id UNION  SELECT edms_tax_class.tax_form_id FROM edms_ac_credit_note_nonstock, edms_tax_class WHERE credit_note_id = $trans_id AND edms_tax_class.tax_form_id IS NOT NULL AND edms_ac_credit_note_nonstock.tax_class_id = edms_tax_class.tax_class_id";
	  break;
	  case 4:$sql="SELECT edms_tax_class.tax_form_id FROM edms_ac_debit_note_item, edms_tax_class WHERE debit_note_id = $trans_id AND edms_tax_class.tax_form_id IS NOT NULL AND edms_ac_debit_note_item.tax_class_id = edms_tax_class.tax_class_id UNION SELECT edms_tax_class.tax_form_id FROM edms_ac_debit_note_nonstock, edms_tax_class WHERE debit_note_id = $trans_id AND edms_tax_class.tax_form_id IS NOT NULL AND edms_ac_debit_note_nonstock.tax_class_id = edms_tax_class.tax_class_id";
	  break;
		}
		$result=dbQuery($sql);
		
		if(dbNumRows($result)>0)
	  	{
			$resultArray = dbResultToArray($result);
			if(is_numeric($resultArray[0][0]))
			return $resultArray[0][0];
			if(is_numeric($resultArray[1][0]))
			return $resultArray[1][0];
		}
		else
		return false;
	  	
		
	}
}
?>