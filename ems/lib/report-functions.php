<?php 
require_once("cg.php");
require_once("lead-functions.php");
require_once("customer-functions.php");
require_once("enquiry-functions.php");
require_once("team-functions.php");
require_once("common.php");
require_once("bd.php");


function getLatestFollowUpDateByEnquiryId($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT enquiry_form_id,follow_up_date
			  FROM ems_enquiry_form
			  WHERE enquiry_form_id=$id";
		$result=dbQuery($sql);
		
		$max_follow_date=getMAXFollowUpDate($id);
		
		
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		
		if(!$max_follow_date || strtotime($resultArray[0][1])>=strtotime($max_follow_date))	
		return $resultArray[0][1];
		else
		return $max_follow_date;
		}
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}


function getMAXFollowUpDate($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		
			
		$sql="SELECT MAX(next_follow_up_date)
			  FROM ems_follow_up
			  WHERE enquiry_form_id=$id";
		$result=dbQuery($sql);
		
		
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}


function viewLeads($from=null,$to=null, $min_amount=null, $max_amount=null, $user_id=null, $customer_type_id=null, $leadStatus=null, $product=null, $super_cat=null, $cat=null,$attr_name_array=null,$group_id_array = NULL, $cust_group_id_array=NULL)
{
	        if(validateForNull($customer_type_id))
		    $customer_type_id_string=implode(',',$customer_type_id);
	        
	        if(validateForNull($user_id))
		    $user_id_string=implode(',',$user_id);
			
			if(validateForNull($leadStatus))
		    $lead_status_string=implode(',',$leadStatus);
			
			if(validateForNull($product))
		    $product_string=implode(',',$product);
			
			if(validateForNull($super_cat))
		    $super_cat_string=implode(',',$super_cat);
			
			if(validateForNull($cat))
		    $cat_string=implode(',',$cat);
			
			if(validateForNull($attr_name_array))
		    $attr_name_string=implode(',',$attr_name_array);
			
			
	if(isset($from) && validateForNull($from))
	{
	$from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
		$from=$from." 00:00:00";
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
		$to=$to." 23:59:59";
}	
	
	$admin_session_id = $_SESSION['EMSadminSession']['admin_id'];
	$his_member_id_array = getHisTeamMemberIdsForAnAdminId($admin_session_id);
	$his_member_id_string = implode(",", $his_member_id_array);

	$today=getTodaysDate();
	
	$sql="SELECT ems_enquiry_form.enquiry_form_id, ems_enquiry_form.date_added, enquiry_date, ems_enquiry_form.created_by, ems_enquiry_form.current_lead_holder, unique_enquiry_id, admin_name, total_mrp, customer_name, is_bought, (SELECT GROUP_CONCAT(DISTINCT customer_contact_no SEPARATOR '<br>') FROM ems_customer_contact_no WHERE ems_customer_contact_no.customer_id = ems_customer.customer_id GROUP BY ems_customer.customer_id ) as contact_no, GROUP_CONCAT(customer_price SEPARATOR '<br>') as customer_price, customer_type_id, is_bought, GROUP_CONCAT(sub_cat.sub_cat_id), GROUP_CONCAT(sub_cat.sub_cat_name SEPARATOR ' <br> ') as sub_cat_name, ems_enquiry_form.enquiry_date,  COALESCE(NULLIF(GROUP_CONCAT((SELECT GROUP_CONCAT(CONCAT_WS(' : ',ems_attribute_type.attribute_type,ems_attribute_name.attribute_name) SEPARATOR ' <br>') FROM ems_rel_subCat_enquiry_form_attributes, ems_attribute_type, ems_attribute_name  WHERE sub_cat.sub_cat_id = ems_rel_subCat_enquiry_form_attributes.sub_cat_id AND ems_rel_subCat_enquiry_form_attributes.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_rel_subCat_enquiry_form_attributes.attribute_type_id = ems_attribute_type.attribute_type_id AND ems_rel_subCat_enquiry_form_attributes.attribute_name_id = ems_attribute_name.attribute_name_id) SEPARATOR '<hr>'),''),'No Details')  as attribute_types_sub_cat_wise
, (SELECT IF(ems_follow_up.enquiry_form_id IS NULL,MAX(follow_up_date),GREATEST(MAX(follow_up_date),MAX(next_follow_up_date))) FROM ems_enquiry_form as enq_form_table LEFT JOIN ems_follow_up ON enq_form_table.enquiry_form_id = ems_follow_up.enquiry_form_id  WHERE (ems_follow_up.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_follow_up.enquiry_form_id IS NOT NULL) OR (enq_form_table.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_follow_up.enquiry_form_id IS NULL)) as next_follow_up_date,
IF((SELECT enquiry_form_id FROM ems_rel_enquiry_group WHERE ems_rel_enquiry_group.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_rel_enquiry_group.enquiry_group_id=1) IS NOT NULL,1,0) as is_imp
	 	  FROM 
	     ems_enquiry_form 
		 
		 LEFT JOIN  
		 ems_rel_subCategory_enquiry_form 
		 ON  
		 ems_enquiry_form.enquiry_form_id=ems_rel_subCategory_enquiry_form.enquiry_form_id  
		 
		 LEFT JOIN 
		 ems_subCategory as sub_cat 
		 ON 
		 ems_rel_subCategory_enquiry_form.sub_cat_id = sub_cat.sub_cat_id 
		 
		 LEFT JOIN  ems_customer ON  ems_customer.customer_id = ems_enquiry_form.customer_id 
		 
		 LEFT JOIN ems_admin ON  ems_enquiry_form.current_lead_holder = ems_admin.admin_id
		  ";
		  if(isset($attr_name_string) && validateForNull($attr_name_string))
		  $sql=$sql."
		  LEFT JOIN ems_rel_subCat_enquiry_form_attributes as enquiry_attribute_relation_table ON enquiry_attribute_relation_table.enquiry_form_id =  ems_enquiry_form.enquiry_form_id 
		  ";
		  
		  $sql=$sql."
		 
		  ";
		  
		  $sql=$sql." WHERE 
		 
		 
		1=1 
		 "; 
	 if(isset($attr_name_string) && validateForNull($attr_name_string))
	 $sql=$sql." AND enquiry_attribute_relation_table.attribute_name_id IN ($attr_name_string) ";	  
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND ems_enquiry_form.enquiry_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND ems_enquiry_form.enquiry_date<='$to'";
	
	if(isset($min_amount) && validateForNull($min_amount))
	$sql=$sql." AND total_mrp>='$min_amount' 
		   ";
	if(isset($max_amount) && validateForNull($max_amount))  
	$sql=$sql." AND total_mrp<='$max_amount'";
	
	if(isset($user_id) && validateForNull($user_id) && $user_id>0)  
	$sql=$sql." AND ems_enquiry_form.created_by IN ($user_id_string)";
	
	if(isset($customer_type_id) && validateForNull($customer_type_id) && $customer_type_id>0)  
	$sql=$sql." AND customer_type_id IN ($customer_type_id_string)";
	
	if(isset($leadStatus) && validateForNull($leadStatus))  
	$sql=$sql." AND is_bought IN ($lead_status_string)";
	
	
	if((isset($product_string) && validateForNull($product_string)) || (isset($super_cat_string) && validateForNull($super_cat_string)) || (isset($cat_string) && validateForNull($cat_string)))
	{
	$sql=$sql." AND (";	  
	if(isset($product_string) && validateForNull($product_string))  
	$sql=$sql." sub_cat.sub_cat_id IN ($product_string)";
	
	
	if(isset($product_string) && validateForNull($product_string) && ((isset($super_cat_string) && validateForNull($super_cat_string)) || (isset($cat_string) && validateForNull($cat_string))))
	$sql=$sql." OR ";
	
	if(isset($super_cat_string) && validateForNull($super_cat_string))  
	$sql=$sql." super_cat_id IN ($super_cat_string)";
	
	if(((isset($product_string) && validateForNull($product_string)) || (isset($super_cat_string) && validateForNull($super_cat_string))) && isset($cat_string) && validateForNull($cat_string))
	$sql=$sql." OR ";
	
	if(isset($cat_string) && validateForNull($cat_string))  
	$sql=$sql." cat_id IN ($cat_string)";
	
	$sql=$sql.")";

	
	}
	
	if(isset($group_id_array) && validateForNull($group_id_array))
	{
	$group_id_string = implode(",",$group_id_array);	
	$sql=$sql." AND ems_enquiry_form.enquiry_form_id IN (SELECT enquiry_form_id FROM ems_rel_enquiry_group WHERE enquiry_group_id IN  ($group_id_string) ) ";
	}
	
	if(isset($cust_group_id_array) && validateForNull($cust_group_id_array))
	{
	$cust_group_id_string = implode(",",$cust_group_id_array);	
	$sql=$sql." AND ems_customer.customer_id IN (SELECT customer_id FROM ems_rel_customer_group WHERE customer_group_id IN  ($cust_group_id_string) ) ";
	}
	
	$sql=$sql." AND current_lead_holder IN ($his_member_id_string) ";
	
	$sql=$sql." GROUP BY ems_enquiry_form.enquiry_form_id ORDER BY ems_enquiry_form.enquiry_date";
	
	
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);

	return $resultArray;		
}

function viewFollowUps($from=null,$to=null, $min_amount=null, $max_amount=null, $user_id=null, $customer_type_id=null, $leadStatus=null, $product=null, $super_cat=null, $cat=null,$attr_name_array=null, $group_id_array, $cust_group_id_array)
{
	
	        
	         
	        if(validateForNull($customer_type_id))
		    $customer_type_id_string=implode(',',$customer_type_id);
	        
	        if(validateForNull($user_id))
		    $user_id_string=implode(',',$user_id);
			
			if(validateForNull($leadStatus))
		    $lead_status_string=implode(',',$leadStatus);
			
			if(validateForNull($product))
		    $product_string=implode(',',$product);
			
			if(validateForNull($super_cat))
		    $super_cat_string=implode(',',$super_cat);
			
			if(validateForNull($cat))
		    $cat_string=implode(',',$cat);
			
			if(validateForNull($attr_name_array))
		    $attr_name_string=implode(',',$attr_name_array);
			
	
	if(isset($from) && validateForNull($from))
	{
	$from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
		$from=$from." 00:00:00";
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
		$to=$to." 23:59:59";
}	

     $admin_session_id = $_SESSION['EMSadminSession']['admin_id'];
	$his_member_id_array = getHisTeamMemberIdsForAnAdminId($admin_session_id);
	
	
	$his_member_id_string = implode(",", $his_member_id_array);
	
	$today=getTodaysDate();
	
	$sql="SELECT ems_enquiry_form.enquiry_form_id, ems_enquiry_form.date_added, enquiry_date, ems_enquiry_form.created_by, total_mrp, customer_name, is_bought, ems_enquiry_form.current_lead_holder, GROUP_CONCAT(DISTINCT customer_contact_no SEPARATOR '<br>') as contact_no, GROUP_CONCAT(customer_price SEPARATOR '<br>') as customer_price, customer_type_id, is_bought, GROUP_CONCAT(sub_cat.sub_cat_id), GROUP_CONCAT(sub_cat.sub_cat_name SEPARATOR ' <br> ') as sub_cat_name, ems_enquiry_form.enquiry_date,  COALESCE(NULLIF(GROUP_CONCAT((SELECT GROUP_CONCAT(CONCAT_WS(' : ',ems_attribute_type.attribute_type,ems_attribute_name.attribute_name) SEPARATOR ' <br>') FROM ems_rel_subCat_enquiry_form_attributes, ems_attribute_type, ems_attribute_name  WHERE sub_cat.sub_cat_id = ems_rel_subCat_enquiry_form_attributes.sub_cat_id AND ems_rel_subCat_enquiry_form_attributes.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_rel_subCat_enquiry_form_attributes.attribute_type_id = ems_attribute_type.attribute_type_id AND ems_rel_subCat_enquiry_form_attributes.attribute_name_id = ems_attribute_name.attribute_name_id) SEPARATOR '<hr>'),''),'No Details')  as attribute_types_sub_cat_wise
, (SELECT IF(ems_follow_up.enquiry_form_id IS NULL, CONCAT_WS(' # ',CONCAT_WS(' ^ ',follow_up_date,discussion),ems_admin.admin_name) , CONCAT_WS(' # ',CONCAT_WS(' ^ ',next_follow_up_date,discussion),follow_handler.admin_name)) FROM ems_enquiry_form as enq_form_table INNER JOIN ems_admin ON enq_form_table.current_lead_holder = ems_admin.admin_id  LEFT JOIN ems_follow_up ON enq_form_table.enquiry_form_id = ems_follow_up.enquiry_form_id LEFT JOIN ems_admin as follow_handler ON ems_follow_up.created_by = follow_handler.admin_id  WHERE (ems_follow_up.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_follow_up.enquiry_form_id IS NOT NULL) OR (enq_form_table.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_follow_up.enquiry_form_id IS NULL) ORDER BY next_follow_up_date DESC,ems_follow_up.date_added DESC LIMIT 0,1) as next_follow_up_date, (SELECT MAX(visit_date) from ems_visit WHERE ems_visit.enquiry_form_id = ems_enquiry_form.enquiry_form_id GROUP BY ems_visit.enquiry_form_id ) as visit_date,
IF((SELECT enquiry_form_id FROM ems_rel_enquiry_group WHERE ems_rel_enquiry_group.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_rel_enquiry_group.enquiry_group_id=1 LIMIT 0,1) IS NOT NULL,1,0) as is_imp 
	 	  FROM
	      ems_enquiry_form, ems_rel_subCategory_enquiry_form, ems_subCategory as sub_cat, ems_customer, ems_customer_contact_no
		  WHERE 
		  ems_enquiry_form.enquiry_form_id=ems_rel_subCategory_enquiry_form.enquiry_form_id 
		  AND 
		  ems_rel_subCategory_enquiry_form.sub_cat_id = sub_cat.sub_cat_id
		  AND
		  ems_customer.customer_id = ems_enquiry_form.customer_id
		  AND
		  ems_customer.customer_id = ems_customer_contact_no.customer_id
		  AND
		   (is_bought=3 OR is_bought=0)"; 
		  
	if(isset($min_amount) && validateForNull($min_amount))
	$sql=$sql." AND total_mrp>='$min_amount' 
		   ";
	if(isset($max_amount) && validateForNull($max_amount))  
	$sql=$sql." AND total_mrp<='$max_amount'";
	
	if(isset($user_id) && validateForNull($user_id) && $user_id>0)  
	$sql=$sql." AND ems_enquiry_form.created_by IN ($user_id_string)";
	
	if(isset($customer_type_id) && validateForNull($customer_type_id) && $customer_type_id>0)  
	$sql=$sql." AND customer_type_id IN ($customer_type_id_string)";
	
	if(isset($leadStatus) && validateForNull($leadStatus))  
	$sql=$sql." AND is_bought IN ($lead_status_string)";
	
	
	if((isset($product_string) && validateForNull($product_string)) || (isset($super_cat_string) && validateForNull($super_cat_string)) || (isset($cat_string) && validateForNull($cat_string)))
	{
	$sql=$sql."AND (";	  
	if(isset($product_string) && validateForNull($product_string))  
	$sql=$sql." sub_cat.sub_cat_id IN ($product_string)";
	
	
	if(isset($product_string) && validateForNull($product_string) && ((isset($super_cat_string) && validateForNull($super_cat_string)) || (isset($cat_string) && validateForNull($cat_string))))
	$sql=$sql." OR ";
	
	if(isset($super_cat_string) && validateForNull($super_cat_string))  
	$sql=$sql." super_cat_id IN ($super_cat_string)";
	
	if(((isset($product_string) && validateForNull($product_string)) || (isset($super_cat_string) && validateForNull($super_cat_string))) && isset($cat_string) && validateForNull($cat_string))
	$sql=$sql." OR ";
	
	if(isset($cat_string) && validateForNull($cat_string))  
	$sql=$sql." cat_id IN ($cat_string)";
	
	$sql=$sql.")";
	
	if(isset($attr_name_string) && validateForNull($attr_name_string))  
	$sql=$sql." AND attribute_name_id IN ($attr_name_string)";
	
	
	}
	
	if(isset($group_id_array) && validateForNull($group_id_array))
	{
	$group_id_string = implode(",",$group_id_array);	
	$sql=$sql." AND ems_enquiry_form.enquiry_form_id IN (SELECT enquiry_form_id FROM ems_rel_enquiry_group WHERE enquiry_group_id IN  ($group_id_string) ) ";
	}
	
	if(isset($cust_group_id_array) && validateForNull($cust_group_id_array))
	{
	$cust_group_id_string = implode(",",$cust_group_id_array);	
	$sql=$sql." AND ems_customer.customer_id IN (SELECT customer_id FROM ems_rel_customer_group WHERE customer_group_id IN  ($cust_group_id_string) ) ";
	}
	
	$sql=$sql." GROUP BY ems_enquiry_form.enquiry_form_id HAVING next_follow_up_date!='1970-01-01' "; 
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND next_follow_up_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND next_follow_up_date<='$to'";
	
	$sql=$sql." AND current_lead_holder IN ($his_member_id_string)";
	
	
	
	$sql=$sql."ORDER BY ems_enquiry_form.enquiry_date";
	
 
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	return $resultArray;		
}



function viewDoneFollowUps($from=null,$to=null)
{
	
	if(isset($from) && validateForNull($from))
	{
	$from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
		$from=$from." 00:00:00";
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
		$to=$to." 23:59:59";
}	

    $admin_session_id = $_SESSION['EMSadminSession']['admin_id'];
	$his_member_id_array = getHisTeamMemberIdsForAnAdminId($admin_session_id);
	
	
	$his_member_id_string = implode(",", $his_member_id_array);
	
	$today=getTodaysDate();
	
	$sql="SELECT ems_enquiry_form.enquiry_form_id, ems_enquiry_form.date_added, enquiry_date, ems_enquiry_form.created_by, total_mrp, customer_name, is_bought, ems_enquiry_form.current_lead_holder, GROUP_CONCAT(DISTINCT customer_contact_no SEPARATOR '<br>') as contact_no, GROUP_CONCAT(customer_price SEPARATOR '<br>') as customer_price, customer_type_id, is_bought, GROUP_CONCAT(sub_cat.sub_cat_id), GROUP_CONCAT(sub_cat.sub_cat_name SEPARATOR ' <br> ') as sub_cat_name, ems_enquiry_form.enquiry_date,  COALESCE(NULLIF(GROUP_CONCAT((SELECT GROUP_CONCAT(CONCAT_WS(' : ',ems_attribute_type.attribute_type,ems_attribute_name.attribute_name) SEPARATOR ' <br>') FROM ems_rel_subCat_enquiry_form_attributes, ems_attribute_type, ems_attribute_name  WHERE sub_cat.sub_cat_id = ems_rel_subCat_enquiry_form_attributes.sub_cat_id AND ems_rel_subCat_enquiry_form_attributes.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_rel_subCat_enquiry_form_attributes.attribute_type_id = ems_attribute_type.attribute_type_id AND ems_rel_subCat_enquiry_form_attributes.attribute_name_id = ems_attribute_name.attribute_name_id) SEPARATOR '<hr>'),''),'No Details')  as attribute_types_sub_cat_wise, 
ems_follow_up.date_added,
IF((SELECT enquiry_form_id FROM ems_rel_enquiry_group WHERE ems_rel_enquiry_group.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_rel_enquiry_group.enquiry_group_id=1) IS NOT NULL,1,0) as is_imp

	 	  FROM
	      ems_enquiry_form
		  INNER JOIN ems_follow_up
		  ON 
		  ems_follow_up.enquiry_form_id = ems_enquiry_form.enquiry_form_id
		  
		  INNER JOIN
		  ems_rel_subCategory_enquiry_form
		  ON
		  ems_enquiry_form.enquiry_form_id=ems_rel_subCategory_enquiry_form.enquiry_form_id 
		  
		  INNER JOIN
		  ems_subCategory as sub_cat
		  ON
		  ems_rel_subCategory_enquiry_form.sub_cat_id = sub_cat.sub_cat_id
		  
		  INNER JOIN
		  ems_customer
		  ON
		  ems_customer.customer_id = ems_enquiry_form.customer_id
		  
		  INNER JOIN
		  ems_customer_contact_no
		  ON
		  ems_customer.customer_id = ems_customer_contact_no.customer_id
		  
		  WHERE
		  (is_bought=3 OR is_bought=0) "; 
	
	if(isset($user_id) && validateForNull($user_id) && $user_id>0)  
	$sql=$sql." AND ems_enquiry_form.created_by IN ($user_id_string)";

	if(isset($group_id_array) && validateForNull($group_id_array))
	{
	$group_id_string = implode(",",$group_id_array);	
	$sql=$sql." AND ems_enquiry_form.enquiry_form_id IN (SELECT enquiry_form_id FROM ems_rel_enquiry_group WHERE enquiry_group_id IN  ($group_id_string) ) ";
	}
	
	if(isset($cust_group_id_array) && validateForNull($cust_group_id_array))
	{
	$cust_group_id_string = implode(",",$cust_group_id_array);	
	$sql=$sql." AND ems_customer.customer_id IN (SELECT customer_id FROM ems_rel_customer_group WHERE customer_group_id IN  ($cust_group_id_string) ) ";
	}
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND ems_follow_up.date_added>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND ems_follow_up.date_added<='$to'";
	
	$sql=$sql." GROUP BY ems_enquiry_form.enquiry_form_id HAVING 
	 current_lead_holder IN ($his_member_id_string)
	 ORDER BY ems_enquiry_form.enquiry_date";
	
    //echo $sql;
	
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	return $resultArray;		
}


function viewLeadsWidget($user_id=null)
{
	        
	        
	       if(validateForNull($customer_type_id))
		    $customer_type_id_string=implode(',',$customer_type_id);
	        
	        if(validateForNull($user_id))
		    $user_id_string=implode(',',$user_id);
			
			if(validateForNull($leadStatus))
		    $lead_status_string=implode(',',$leadStatus);
			
			if(validateForNull($product))
		    $product_string=implode(',',$product);
			
			if(validateForNull($super_cat))
		    $super_cat_string=implode(',',$super_cat);
			
			if(validateForNull($cat))
		    $cat_string=implode(',',$cat);
			
			if(validateForNull($attr_name_array))
		    $attr_name_string=implode(',',$attr_name_array);
			
	
	if(isset($from) && validateForNull($from))
	{
	$from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
		$from=$from." 00:00:00";
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
		$to=$to." 23:59:59";
}	
	
	$to = getTodaysDate();
	$from = getDateBeforeDaysFromTodaysDate(7);
	
	$admin_session_id = $_SESSION['EMSadminSession']['admin_id'];
	$his_member_id_array = getHisTeamMemberIdsForAnAdminId($admin_session_id);
	$his_member_id_string = implode(",", $his_member_id_array);
	
	$sql="SELECT ems_enquiry_form.enquiry_form_id, ems_enquiry_form.date_added, enquiry_date, ems_enquiry_form.created_by, ems_enquiry_form.current_lead_holder, admin_name, total_mrp, customer_name, is_bought, (SELECT GROUP_CONCAT(DISTINCT customer_contact_no SEPARATOR '<br>') FROM ems_customer_contact_no WHERE ems_customer_contact_no.customer_id = ems_customer.customer_id GROUP BY ems_customer.customer_id ) as contact_no, GROUP_CONCAT(customer_price SEPARATOR '<br>') as customer_price, customer_type_id, is_bought, GROUP_CONCAT(sub_cat.sub_cat_id), GROUP_CONCAT(sub_cat.sub_cat_name SEPARATOR ' <br> ') as sub_cat_name, ems_enquiry_form.enquiry_date,  COALESCE(NULLIF(GROUP_CONCAT((SELECT GROUP_CONCAT(CONCAT_WS(' : ',ems_attribute_type.attribute_type,ems_attribute_name.attribute_name) SEPARATOR ' <br>') FROM ems_rel_subCat_enquiry_form_attributes, ems_attribute_type, ems_attribute_name  WHERE sub_cat.sub_cat_id = ems_rel_subCat_enquiry_form_attributes.sub_cat_id AND ems_rel_subCat_enquiry_form_attributes.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_rel_subCat_enquiry_form_attributes.attribute_type_id = ems_attribute_type.attribute_type_id AND ems_rel_subCat_enquiry_form_attributes.attribute_name_id = ems_attribute_name.attribute_name_id) SEPARATOR '<hr>'),''),'No Details')  as attribute_types_sub_cat_wise
, (SELECT IF(ems_follow_up.enquiry_form_id IS NULL,MAX(follow_up_date),GREATEST(MAX(follow_up_date),MAX(next_follow_up_date))) FROM ems_enquiry_form as enq_form_table LEFT JOIN ems_follow_up ON enq_form_table.enquiry_form_id = ems_follow_up.enquiry_form_id  WHERE (ems_follow_up.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_follow_up.enquiry_form_id IS NOT NULL) OR (enq_form_table.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_follow_up.enquiry_form_id IS NULL)) as next_follow_up_date,
IF((SELECT enquiry_form_id FROM ems_rel_enquiry_group WHERE ems_rel_enquiry_group.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_rel_enquiry_group.enquiry_group_id=1) IS NOT NULL,1,0) as is_imp
	 	  FROM
	      ems_enquiry_form, ems_rel_subCategory_enquiry_form, ems_subCategory as sub_cat, ems_customer, ems_admin
		  WHERE 
		  ems_enquiry_form.enquiry_form_id=ems_rel_subCategory_enquiry_form.enquiry_form_id 
		  AND 
		  ems_rel_subCategory_enquiry_form.sub_cat_id = sub_cat.sub_cat_id
		  AND
		  ems_customer.customer_id = ems_enquiry_form.customer_id
		  AND
		  ems_enquiry_form.current_lead_holder = ems_admin.admin_id"; 
		  
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND ems_enquiry_form.date_added>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND ems_enquiry_form.date_added<='$to'";
	
	if(isset($min_amount) && validateForNull($min_amount))
	$sql=$sql." AND total_mrp>='$min_amount' 
		   ";
	if(isset($max_amount) && validateForNull($max_amount))  
	$sql=$sql." AND total_mrp<='$max_amount'";
	
	if(isset($user_id) && validateForNull($user_id) && $user_id>0)  
	$sql=$sql." AND ems_enquiry_form.created_by IN ($user_id_string)";
	
	if(isset($customer_type_id) && validateForNull($customer_type_id) && $customer_type_id>0)  
	$sql=$sql." AND customer_type_id IN ($customer_type_id_string)";
	
	if(isset($leadStatus) && validateForNull($leadStatus))  
	$sql=$sql." AND is_bought IN ($lead_status_string)";
	
	
	if((isset($product_string) && validateForNull($product_string)) || (isset($super_cat_string) && validateForNull($super_cat_string)) || (isset($cat_string) && validateForNull($cat_string)))
	{
	$sql=$sql."AND (";	  
	if(isset($product_string) && validateForNull($product_string))  
	$sql=$sql." sub_cat.sub_cat_id IN ($product_string)";
	
	
	if(isset($product_string) && validateForNull($product_string) && ((isset($super_cat_string) && validateForNull($super_cat_string)) || (isset($cat_string) && validateForNull($cat_string))))
	$sql=$sql." OR ";
	
	if(isset($super_cat_string) && validateForNull($super_cat_string))  
	$sql=$sql." super_cat_id IN ($super_cat_string)";
	
	if(((isset($product_string) && validateForNull($product_string)) || (isset($super_cat_string) && validateForNull($super_cat_string))) && isset($cat_string) && validateForNull($cat_string))
	$sql=$sql." OR ";
	
	if(isset($cat_string) && validateForNull($cat_string))  
	$sql=$sql." cat_id IN ($cat_string)";
	
	$sql=$sql.")";
	
	if(isset($attr_name_string) && validateForNull($attr_name_string))  
	$sql=$sql." AND attribute_name_id IN ($attr_name_string)";
	
	
	}
	
	$sql=$sql." AND current_lead_holder IN ($his_member_id_string) ";
	
	$sql=$sql." GROUP BY ems_enquiry_form.enquiry_form_id ORDER BY ems_enquiry_form.enquiry_date DESC LIMIT 0,15";
	

	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	return $resultArray;		
}


function viewFollowUpsWidgetOLD($from=null,$to=null, $min_amount=null, $max_amount=null, $user_id=null, $customer_type_id=null, $leadStatus=null, $product=null, $super_cat=null, $cat=null,$attr_name_array=null)
{
	
	        $from = getTodaysDate();
			
			//echo "From Date :".$from;
	      
	        if(validateForNull($customer_type_id))
		    $customer_type_id_string=implode(',',$customer_type_id);
	        
	        if(validateForNull($user_id))
		    $user_id_string=implode(',',$user_id);
			
			if(validateForNull($leadStatus))
		    $lead_status_string=implode(',',$leadStatus);
			
			if(validateForNull($product))
		    $product_string=implode(',',$product);
			
			if(validateForNull($super_cat))
		    $super_cat_string=implode(',',$super_cat);
			
			if(validateForNull($cat))
		    $cat_string=implode(',',$cat);
			
			if(validateForNull($attr_name_array))
		    $attr_name_string=implode(',',$attr_name_array);
			
	
	if(isset($from) && validateForNull($from))
	{
	$from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
		$from=$from." 00:00:00";
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
		$to=$to." 23:59:59";
}	
	
	$from=getTodaysDate();
	$to = getDateAfterDaysFromTodaysDate(7);
	$admin_session_id = $_SESSION['EMSadminSession']['admin_id'];
	$his_member_id_array = getHisTeamMemberIdsForAnAdminId($admin_session_id);
	$his_member_id_string = implode(",", $his_member_id_array);

	
	$sql="SELECT ems_enquiry_form.enquiry_form_id, ems_enquiry_form.date_added, enquiry_date, ems_enquiry_form.created_by, total_mrp, customer_name, is_bought, ems_enquiry_form.current_lead_holder, GROUP_CONCAT(DISTINCT customer_contact_no SEPARATOR '<br>') as contact_no, GROUP_CONCAT(customer_price SEPARATOR '<br>') as customer_price, customer_type_id, is_bought, GROUP_CONCAT(sub_cat.sub_cat_id), GROUP_CONCAT(sub_cat.sub_cat_name SEPARATOR ' <br> ') as sub_cat_name, ems_enquiry_form.enquiry_date,  COALESCE(NULLIF(GROUP_CONCAT((SELECT GROUP_CONCAT(CONCAT_WS(' : ',ems_attribute_type.attribute_type,ems_attribute_name.attribute_name) SEPARATOR ' <br>') FROM ems_rel_subCat_enquiry_form_attributes, ems_attribute_type, ems_attribute_name  WHERE sub_cat.sub_cat_id = ems_rel_subCat_enquiry_form_attributes.sub_cat_id AND ems_rel_subCat_enquiry_form_attributes.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_rel_subCat_enquiry_form_attributes.attribute_type_id = ems_attribute_type.attribute_type_id AND ems_rel_subCat_enquiry_form_attributes.attribute_name_id = ems_attribute_name.attribute_name_id) SEPARATOR '<hr>'),''),'No Details')  as attribute_types_sub_cat_wise
, (SELECT IF(ems_follow_up.enquiry_form_id IS NULL, CONCAT_WS(' # ',CONCAT_WS(' ^ ',follow_up_date,enquiry_discussion),ems_admin.admin_name) , CONCAT_WS(' # ',CONCAT_WS(' ^ ',next_follow_up_date,discussion),follow_handler.admin_name)) FROM ems_enquiry_form as enq_form_table INNER JOIN ems_admin ON enq_form_table.current_lead_holder = ems_admin.admin_id  LEFT JOIN ems_follow_up ON enq_form_table.enquiry_form_id = ems_follow_up.enquiry_form_id LEFT JOIN ems_admin as follow_handler ON ems_follow_up.created_by = follow_handler.admin_id  WHERE (ems_follow_up.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_follow_up.enquiry_form_id IS NOT NULL) OR (enq_form_table.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_follow_up.enquiry_form_id IS NULL AND enq_form_table.follow_up_date!='1970-01-01') ORDER BY next_follow_up_date DESC,ems_follow_up.date_added DESC LIMIT 0,1) as next_follow_up_date, (SELECT MAX(visit_date) from ems_visit WHERE ems_visit.enquiry_form_id = ems_enquiry_form.enquiry_form_id GROUP BY ems_visit.enquiry_form_id ) as visit_date,
IF((SELECT enquiry_form_id FROM ems_rel_enquiry_group WHERE ems_rel_enquiry_group.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_rel_enquiry_group.enquiry_group_id=1) IS NOT NULL,1,0) as is_imp
	 	  FROM
	      ems_enquiry_form, ems_rel_subCategory_enquiry_form, ems_subCategory as sub_cat, ems_customer, ems_customer_contact_no
		  WHERE 
		  ems_enquiry_form.enquiry_form_id=ems_rel_subCategory_enquiry_form.enquiry_form_id 
		  AND 
		  ems_rel_subCategory_enquiry_form.sub_cat_id = sub_cat.sub_cat_id
		  AND
		  ems_customer.customer_id = ems_enquiry_form.customer_id
		  AND
		  ems_customer.customer_id = ems_customer_contact_no.customer_id
		  AND
		   (is_bought=3 OR is_bought=0)"; 
		  
	
	
	if(isset($min_amount) && validateForNull($min_amount))
	$sql=$sql." AND total_mrp>='$min_amount' 
		   ";
	if(isset($max_amount) && validateForNull($max_amount))  
	$sql=$sql." AND total_mrp<='$max_amount'";
	
	if(isset($user_id) && validateForNull($user_id) && $user_id>0)  
	$sql=$sql." AND ems_enquiry_form.created_by IN ($user_id_string)";
	
	if(isset($customer_type_id) && validateForNull($customer_type_id) && $customer_type_id>0)  
	$sql=$sql." AND customer_type_id IN ($customer_type_id_string)";
	
	if(isset($leadStatus) && validateForNull($leadStatus))  
	$sql=$sql." AND is_bought IN ($lead_status_string)";
	
	
	if((isset($product_string) && validateForNull($product_string)) || (isset($super_cat_string) && validateForNull($super_cat_string)) || (isset($cat_string) && validateForNull($cat_string)))
	{
	$sql=$sql."AND (";	  
	if(isset($product_string) && validateForNull($product_string))  
	$sql=$sql." sub_cat.sub_cat_id IN ($product_string)";
	
	
	if(isset($product_string) && validateForNull($product_string) && ((isset($super_cat_string) && validateForNull($super_cat_string)) || (isset($cat_string) && validateForNull($cat_string))))
	$sql=$sql." OR ";
	
	if(isset($super_cat_string) && validateForNull($super_cat_string))  
	$sql=$sql." super_cat_id IN ($super_cat_string)";
	
	if(((isset($product_string) && validateForNull($product_string)) || (isset($super_cat_string) && validateForNull($super_cat_string))) && isset($cat_string) && validateForNull($cat_string))
	$sql=$sql." OR ";
	
	if(isset($cat_string) && validateForNull($cat_string))  
	$sql=$sql." cat_id IN ($cat_string)";
	
	$sql=$sql.")";
	
	if(isset($attr_name_string) && validateForNull($attr_name_string))  
	$sql=$sql." AND attribute_name_id IN ($attr_name_string)";
	
	
	}
	$sql=$sql." AND current_lead_holder IN ($his_member_id_string) ";
	
	$sql=$sql." GROUP BY ems_enquiry_form.enquiry_form_id HAVING next_follow_up_date!='1970-01-01' "; 
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND next_follow_up_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND next_follow_up_date<='$to'";
	
	
	
	$sql=$sql."ORDER BY next_follow_up_date LIMIT 0,15";

	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	return $resultArray;		
}



function viewFollowUpsWidget($from=null,$to=null, $min_amount=null, $max_amount=null, $user_id=null, $customer_type_id=null, $leadStatus=null, $product=null, $super_cat=null, $cat=null,$attr_name_array=null)
{
	
	        $from = getTodaysDate();
			
			//echo "From Date :".$from;
	      
	        if(validateForNull($customer_type_id))
		    $customer_type_id_string=implode(',',$customer_type_id);
	        
	        if(validateForNull($user_id))
		    $user_id_string=implode(',',$user_id);
			
			if(validateForNull($leadStatus))
		    $lead_status_string=implode(',',$leadStatus);
			
			if(validateForNull($product))
		    $product_string=implode(',',$product);
			
			if(validateForNull($super_cat))
		    $super_cat_string=implode(',',$super_cat);
			
			if(validateForNull($cat))
		    $cat_string=implode(',',$cat);
			
			if(validateForNull($attr_name_array))
		    $attr_name_string=implode(',',$attr_name_array);
			
	
	if(isset($from) && validateForNull($from))
	{
	$from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
		$from=$from." 00:00:00";
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
		$to=$to." 23:59:59";
}	
	
	$from=getTodaysDate();
	$to = getDateAfterDaysFromTodaysDate(7);
	$admin_session_id = $_SESSION['EMSadminSession']['admin_id'];
	$his_member_id_array = getHisTeamMemberIdsForAnAdminId($admin_session_id);
	$his_member_id_string = implode(",", $his_member_id_array);
   
	
	$sql="SELECT ems_enquiry_form.enquiry_form_id, ems_enquiry_form.date_added, enquiry_date, ems_enquiry_form.created_by, total_mrp, customer_name, is_bought, ems_enquiry_form.current_lead_holder, GROUP_CONCAT(DISTINCT customer_contact_no SEPARATOR '<br>') as contact_no, GROUP_CONCAT(customer_price SEPARATOR '<br>') as customer_price, customer_type_id, is_bought, GROUP_CONCAT(sub_cat.sub_cat_id), GROUP_CONCAT(sub_cat.sub_cat_name SEPARATOR ' <br> ') as sub_cat_name, COALESCE(NULLIF(GROUP_CONCAT((SELECT GROUP_CONCAT(CONCAT_WS(' : ',ems_attribute_type.attribute_type,ems_attribute_name.attribute_name) SEPARATOR ' <br>') FROM ems_rel_subCat_enquiry_form_attributes, ems_attribute_type, ems_attribute_name  WHERE sub_cat.sub_cat_id = ems_rel_subCat_enquiry_form_attributes.sub_cat_id AND ems_rel_subCat_enquiry_form_attributes.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_rel_subCat_enquiry_form_attributes.attribute_type_id = ems_attribute_type.attribute_type_id AND ems_rel_subCat_enquiry_form_attributes.attribute_name_id = ems_attribute_name.attribute_name_id) SEPARATOR '<hr>'),''),'No Details')  as attribute_types_sub_cat_wise,ems_enquiry_form.enquiry_date,   CONCAT_WS(' # ',CONCAT_WS(' ^ ',follow_up_date,(SELECT discussion FROM ems_follow_up WHERE ems_enquiry_form.enquiry_form_id = ems_follow_up.enquiry_form_id AND next_follow_up_date = follow_up_date ORDER BY ems_follow_up.date_added LIMIT 0,1)),ems_admin.admin_name)  as next_follow_up_date, 
(SELECT MAX(visit_date) from ems_visit WHERE ems_visit.enquiry_form_id = ems_enquiry_form.enquiry_form_id GROUP BY ems_visit.enquiry_form_id ) as visit_date,
IF((SELECT enquiry_form_id FROM ems_rel_enquiry_group WHERE ems_rel_enquiry_group.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_rel_enquiry_group.enquiry_group_id=1) IS NOT NULL,1,0) as is_imp
	 	  FROM
	      ems_enquiry_form, ems_rel_subCategory_enquiry_form, ems_subCategory as sub_cat, ems_customer, ems_customer_contact_no, ems_admin
		  WHERE 
		  ems_enquiry_form.enquiry_form_id=ems_rel_subCategory_enquiry_form.enquiry_form_id 
		  AND 
		  ems_enquiry_form.current_lead_holder = ems_admin.admin_id 
		  AND 
		  ems_rel_subCategory_enquiry_form.sub_cat_id = sub_cat.sub_cat_id
		  AND
		  ems_customer.customer_id = ems_enquiry_form.customer_id
		  AND
		  ems_customer.customer_id = ems_customer_contact_no.customer_id
		  AND
		   (is_bought=3 OR is_bought=0)"; 
		  
	
	
	if(isset($min_amount) && validateForNull($min_amount))
	$sql=$sql." AND total_mrp>='$min_amount' 
		   ";
	if(isset($max_amount) && validateForNull($max_amount))  
	$sql=$sql." AND total_mrp<='$max_amount'";
	
	if(isset($user_id) && validateForNull($user_id) && $user_id>0)  
	$sql=$sql." AND ems_enquiry_form.created_by IN ($user_id_string)";
	
	if(isset($customer_type_id) && validateForNull($customer_type_id) && $customer_type_id>0)  
	$sql=$sql." AND customer_type_id IN ($customer_type_id_string)";
	
	if(isset($leadStatus) && validateForNull($leadStatus))  
	$sql=$sql." AND is_bought IN ($lead_status_string)";
	
	
	if((isset($product_string) && validateForNull($product_string)) || (isset($super_cat_string) && validateForNull($super_cat_string)) || (isset($cat_string) && validateForNull($cat_string)))
	{
	$sql=$sql."AND (";	  
	if(isset($product_string) && validateForNull($product_string))  
	$sql=$sql." sub_cat.sub_cat_id IN ($product_string)";
	
	
	if(isset($product_string) && validateForNull($product_string) && ((isset($super_cat_string) && validateForNull($super_cat_string)) || (isset($cat_string) && validateForNull($cat_string))))
	$sql=$sql." OR ";
	
	if(isset($super_cat_string) && validateForNull($super_cat_string))  
	$sql=$sql." super_cat_id IN ($super_cat_string)";
	
	if(((isset($product_string) && validateForNull($product_string)) || (isset($super_cat_string) && validateForNull($super_cat_string))) && isset($cat_string) && validateForNull($cat_string))
	$sql=$sql." OR ";
	
	if(isset($cat_string) && validateForNull($cat_string))  
	$sql=$sql." cat_id IN ($cat_string)";
	
	$sql=$sql.")";
	
	if(isset($attr_name_string) && validateForNull($attr_name_string))  
	$sql=$sql." AND attribute_name_id IN ($attr_name_string)";
	
	
	}
	$sql=$sql." AND current_lead_holder IN ($his_member_id_string) ";

	if(isset($from) && validateForNull($from))
	$sql=$sql." AND follow_up_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND follow_up_date<='$to'";
	
	
	
	$sql=$sql." GROUP BY ems_enquiry_form.enquiry_form_id ORDER BY follow_up_date LIMIT 0,15";


	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	return $resultArray;		
}




function viewExpiredFollowUpsWidget($from=null,$to=null, $min_amount=null, $max_amount=null, $user_id=null, $customer_type_id=null, $leadStatus=null, $product=null, $super_cat=null, $cat=null,$attr_name_array=null)
{
	
	       $to = getTodaysDate();
		   
		  // echo "To Date : ".$to;
	
	        if(validateForNull($customer_type_id))
		    $customer_type_id_string=implode(',',$customer_type_id);
	        
	        if(validateForNull($user_id))
		    $user_id_string=implode(',',$user_id);
			
			if(validateForNull($leadStatus))
		    $lead_status_string=implode(',',$leadStatus);
			
			if(validateForNull($product))
		    $product_string=implode(',',$product);
			
			if(validateForNull($super_cat))
		    $super_cat_string=implode(',',$super_cat);
			
			if(validateForNull($cat))
		    $cat_string=implode(',',$cat);
			
			if(validateForNull($attr_name_array))
		    $attr_name_string=implode(',',$attr_name_array);
			
	
	if(isset($from) && validateForNull($from))
	{
	$from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
		$from=$from." 00:00:00";
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
		
}	
	
	$today=getTodaysDate();
	
	$admin_session_id = $_SESSION['EMSadminSession']['admin_id'];
	$his_member_id_array = getHisTeamMemberIdsForAnAdminId($admin_session_id);
	$his_member_id_string = implode(",", $his_member_id_array);

	$sql="SELECT ems_enquiry_form.enquiry_form_id, ems_enquiry_form.date_added, enquiry_date, ems_enquiry_form.created_by, total_mrp, customer_name, is_bought, ems_enquiry_form.current_lead_holder, GROUP_CONCAT(DISTINCT customer_contact_no SEPARATOR '<br>') as contact_no, GROUP_CONCAT(customer_price SEPARATOR '<br>') as customer_price, customer_type_id, is_bought, GROUP_CONCAT(sub_cat.sub_cat_id), GROUP_CONCAT(sub_cat.sub_cat_name SEPARATOR ' <br> ') as sub_cat_name, COALESCE(NULLIF(GROUP_CONCAT((SELECT GROUP_CONCAT(CONCAT_WS(' : ',ems_attribute_type.attribute_type,ems_attribute_name.attribute_name) SEPARATOR ' <br>') FROM ems_rel_subCat_enquiry_form_attributes, ems_attribute_type, ems_attribute_name  WHERE sub_cat.sub_cat_id = ems_rel_subCat_enquiry_form_attributes.sub_cat_id AND ems_rel_subCat_enquiry_form_attributes.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_rel_subCat_enquiry_form_attributes.attribute_type_id = ems_attribute_type.attribute_type_id AND ems_rel_subCat_enquiry_form_attributes.attribute_name_id = ems_attribute_name.attribute_name_id) SEPARATOR '<hr>'),''),'No Details')  as attribute_types_sub_cat_wise,ems_enquiry_form.enquiry_date,   CONCAT_WS(' # ',CONCAT_WS(' ^ ',follow_up_date,(SELECT discussion FROM ems_follow_up WHERE ems_enquiry_form.enquiry_form_id = ems_follow_up.enquiry_form_id AND next_follow_up_date = follow_up_date ORDER BY ems_follow_up.date_added LIMIT 0,1)),ems_admin.admin_name)  as next_follow_up_date, 
(SELECT MAX(visit_date) from ems_visit WHERE ems_visit.enquiry_form_id = ems_enquiry_form.enquiry_form_id GROUP BY ems_visit.enquiry_form_id ) as visit_date,
IF((SELECT enquiry_form_id FROM ems_rel_enquiry_group WHERE ems_rel_enquiry_group.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_rel_enquiry_group.enquiry_group_id=1) IS NOT NULL,1,0) as is_imp
	 	  FROM
	      ems_enquiry_form, ems_rel_subCategory_enquiry_form, ems_subCategory as sub_cat, ems_customer, ems_customer_contact_no, ems_admin
		  WHERE 
		  ems_enquiry_form.enquiry_form_id=ems_rel_subCategory_enquiry_form.enquiry_form_id 
		  AND 
		  ems_enquiry_form.current_lead_holder = ems_admin.admin_id 
		  AND 
		  ems_rel_subCategory_enquiry_form.sub_cat_id = sub_cat.sub_cat_id
		  AND
		  ems_customer.customer_id = ems_enquiry_form.customer_id
		  AND
		  ems_customer.customer_id = ems_customer_contact_no.customer_id
		  AND
		   (is_bought=3 OR is_bought=0)"; 
		  
	
	
	if(isset($min_amount) && validateForNull($min_amount))
	$sql=$sql." AND total_mrp>='$min_amount' 
		   ";
	if(isset($max_amount) && validateForNull($max_amount))  
	$sql=$sql." AND total_mrp<='$max_amount'";
	
	if(isset($user_id) && validateForNull($user_id) && $user_id>0)  
	$sql=$sql." AND ems_enquiry_form.created_by IN ($user_id_string)";
	
	if(isset($customer_type_id) && validateForNull($customer_type_id) && $customer_type_id>0)  
	$sql=$sql." AND customer_type_id IN ($customer_type_id_string)";
	
	if(isset($leadStatus) && validateForNull($leadStatus))  
	$sql=$sql." AND is_bought IN ($lead_status_string)";
	
	
	if((isset($product_string) && validateForNull($product_string)) || (isset($super_cat_string) && validateForNull($super_cat_string)) || (isset($cat_string) && validateForNull($cat_string)))
	{
	$sql=$sql."AND (";	  
	if(isset($product_string) && validateForNull($product_string))  
	$sql=$sql." sub_cat.sub_cat_id IN ($product_string)";
	
	
	if(isset($product_string) && validateForNull($product_string) && ((isset($super_cat_string) && validateForNull($super_cat_string)) || (isset($cat_string) && validateForNull($cat_string))))
	$sql=$sql." OR ";
	
	if(isset($super_cat_string) && validateForNull($super_cat_string))  
	$sql=$sql." super_cat_id IN ($super_cat_string)";
	
	if(((isset($product_string) && validateForNull($product_string)) || (isset($super_cat_string) && validateForNull($super_cat_string))) && isset($cat_string) && validateForNull($cat_string))
	$sql=$sql." OR ";
	
	if(isset($cat_string) && validateForNull($cat_string))  
	$sql=$sql." cat_id IN ($cat_string)";
	
	$sql=$sql.")";
	
	if(isset($attr_name_string) && validateForNull($attr_name_string))  
	$sql=$sql." AND attribute_name_id IN ($attr_name_string)";
	
	
	}
	$sql=$sql." AND current_lead_holder IN ($his_member_id_string) ";

	if(isset($from) && validateForNull($from))
	$sql=$sql." AND follow_up_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND follow_up_date<='$to'";
	
	
	
	$sql=$sql." GROUP BY ems_enquiry_form.enquiry_form_id ORDER BY follow_up_date LIMIT 0,15";

	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	return $resultArray;		
}



function viewReminders($from=null,$to=null, $user_id=null, $product=null, $super_cat=null, $cat=null, $status)
{
	
	        if(validateForNull($user_id))
		    $user_id_string=implode(',',$user_id);
			
			if(validateForNull($product))
		    $product_string=implode(',',$product);
			
			if(validateForNull($super_cat))
		    $super_cat_string=implode(',',$super_cat);
			
			if(validateForNull($cat))
		    $cat_string=implode(',',$cat);
			
	
	if(isset($from) && validateForNull($from))
	{
	$from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
		$from=$from." 00:00:00";
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
		$to=$to." 23:59:59";
}	

    $admin_session_id = $_SESSION['EMSadminSession']['admin_id'];
	$his_member_id_array = getHisTeamMemberIdsForAnAdminId($admin_session_id);
	
	
	$his_member_id_string = implode(",", $his_member_id_array);
	
	$today=getTodaysDate();
	
	$sql="SELECT ems_enquiry_form.enquiry_form_id, customer_name, is_bought, ems_enquiry_form.current_lead_holder,
	         
	(SELECT GROUP_CONCAT(DISTINCT customer_contact_no SEPARATOR '<br>') FROM ems_customer_contact_no WHERE ems_customer_contact_no.customer_id = ems_customer.customer_id GROUP BY ems_customer.customer_id ) as contact_no,
	 
	      sub_cat.sub_cat_id, sub_cat.sub_cat_name,
	      ems_lead_remainder.date, ems_lead_remainder.remarks, remainder_status
	
	 	  FROM
	      ems_enquiry_form
		  
		  INNER JOIN ems_rel_subCategory_enquiry_form 
		  ON ems_rel_subCategory_enquiry_form.enquiry_form_id = ems_enquiry_form.enquiry_form_id
		  
		  INNER JOIN ems_customer
		  ON ems_customer.customer_id = ems_enquiry_form.customer_id
		  
		  INNER JOIN ems_subCategory as sub_cat
		  ON ems_rel_subCategory_enquiry_form.sub_cat_id = sub_cat.sub_cat_id
		  
		  INNER JOIN ems_lead_remainder
		  ON
		  ems_enquiry_form.enquiry_form_id = ems_lead_remainder.enquiry_form_id
		  
		  WHERE is_bought=1";
	
	if(isset($user_id) && validateForNull($user_id) && $user_id>0)  
	$sql=$sql." AND ems_enquiry_form.created_by IN ($user_id_string)";
	
	
	if((isset($product_string) && validateForNull($product_string)) || (isset($super_cat_string) && validateForNull($super_cat_string)) || (isset($cat_string) && validateForNull($cat_string)))
	{
	$sql=$sql."AND (";	  
	if(isset($product_string) && validateForNull($product_string))  
	$sql=$sql." sub_cat.sub_cat_id IN ($product_string)";
	
	
	if(isset($product_string) && validateForNull($product_string) && ((isset($super_cat_string) && validateForNull($super_cat_string)) || (isset($cat_string) && validateForNull($cat_string))))
	$sql=$sql." OR ";
	
	if(isset($super_cat_string) && validateForNull($super_cat_string))  
	$sql=$sql." super_cat_id IN ($super_cat_string)";
	
	if(((isset($product_string) && validateForNull($product_string)) || (isset($super_cat_string) && validateForNull($super_cat_string))) && isset($cat_string) && validateForNull($cat_string))
	$sql=$sql." OR ";
	
	if(isset($cat_string) && validateForNull($cat_string))  
	$sql=$sql." cat_id IN ($cat_string)";
	
	$sql=$sql.")";
	
	}
	
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND ems_lead_remainder.date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND ems_lead_remainder.date<='$to'";
	
	$sql=$sql." AND current_lead_holder IN ($his_member_id_string)";
	
	$sql=$sql."ORDER BY ems_lead_remainder.date";
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	return $resultArray;	
}


function viewCustomers($from=null,$to=null, $min_amount=null, $max_amount=null, $city_id=null, $data_from_id=null, $from_added=null, $to_added=null, $cust_group_id_array=null)
{
	
	
	
	 if(validateForNull($city_id))
	  $city_id_string=implode(',',$city_id); 
	  
	  if(validateForNull($data_from_id))
	  $data_from_id_string=implode(',',$data_from_id);        
	
	if(isset($from) && validateForNull($from))
	{
	$from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
		$from=$from." 00:00:00";
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
		$to=$to." 23:59:59";
}	


if(isset($from_added) && validateForNull($from_added))
	{
	$from_added = str_replace('/', '-', $from_added);
		$from_added = date('Y-m-d',strtotime($from_added));
		$from_added = $from_added." 00:00:00";
	}
if(isset($to_added) && validateForNull($to_added))
{
	$to_added = str_replace('/', '-', $to_added);
		$to_added=date('Y-m-d',strtotime($to_added));
		$to_added=$to_added." 23:59:59";
}	


if(isset($from) && validateForNull($from) && isset($to) && validateForNull($to))
{
	$date_diff = getDateDifferneceBetweenDates($from,$to); 
	
	if($date_diff<365)
	{
	$month_array = getMonthArrayBetweenTwoDates($from,$to);	
	$month_array_string = implode(',',$month_array);
	$from_month = date('n',strtotime($from));
    $to_month = date('n',strtotime($to));
	$from_day = date('j',strtotime($from));
    $to_day = date('j',strtotime($to));
	}
}
	
	$today=getTodaysDate();
	
	$admin_session_id = $_SESSION['EMSadminSession']['admin_id'];
	$his_member_id_array = getHisTeamMemberIdsForAnAdminId($admin_session_id);
	$his_member_id_string = implode(",", $his_member_id_array);
	
	
	$sql="SELECT ems_customer.customer_id, customer_name, customer_email, customer_dob, customer_address, city_id,  data_from_id, ems_customer.created_by, ems_customer.date_added
	 	  FROM 
	      ems_customer 
		  
		  LEFT JOIN ems_customer_extra_details ON ems_customer.customer_id=ems_customer_extra_details.customer_id
		  
		   
		  
		  WHERE 1=1"; 
	
	if(isset($date_diff) && checkForNumeric($date_diff,$from_day,$from_month,$to_day,$to_month) && $date_diff<365 && validateForNull($month_array_string))
	{
	$sql=$sql." AND MONTH(customer_dob) IN ( ".$month_array_string.") 
		  AND ( ( MONTH(customer_dob) >= $from_month && DAY(customer_dob) >= $from_day  )";
		  
	if($to_month<$from_month)
	$sql=$sql." OR ";
	else
	$sql=$sql." AND ";
	
	$sql=$sql." ( MONTH(customer_dob) <= $to_month && DAY(customer_dob) <= $to_day  ) ) ";	  	  
	}
	
	if(isset($min_amount) && validateForNull($min_amount))
	$sql=$sql." AND total_mrp>='$min_amount' 
		   ";
	if(isset($max_amount) && validateForNull($max_amount))  
	$sql=$sql." AND total_mrp<='$max_amount'";
	
	if(isset($data_from_id) && validateForNull($data_from_id))  
	$sql=$sql." AND data_from_id IN ($data_from_id_string)";
	
	if(isset($city_id) && validateForNull($city_id))  
	$sql=$sql." AND city_id IN ($city_id_string)";
	
	if(isset($from_added) && validateForNull($from_added))
	$sql=$sql." AND date_added>='$from_added'";
	
	if(isset($to_added) && validateForNull($to_added))  
	$sql=$sql." AND date_added<='$to_added 23:59:59'";
	
	
	if(isset($cust_group_id_array) && validateForNull($cust_group_id_array))
	{
	$cust_group_id_string = implode(",",$cust_group_id_array);	
	$sql=$sql." AND ems_customer.customer_id IN (SELECT customer_id FROM ems_rel_customer_group WHERE customer_group_id IN  ($cust_group_id_string) ) ";
	}
	
	
	$sql=$sql." AND created_by IN ($his_member_id_string)";

	$sql=$sql." ORDER BY date_added";
	
	
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	return $resultArray;		
}

function viewCustomersForLabelPrinting($from=null,$to=null, $min_amount=null, $max_amount=null, $city_id=null, $data_from_id=null, $from_added=null, $to_added=null, $cust_group_id_array=null)
{
	
	
	
	 if(validateForNull($city_id))
	  $city_id_string=implode(',',$city_id); 
	  
	  if(validateForNull($data_from_id))
	  $data_from_id_string=implode(',',$data_from_id);        
	
	if(isset($from) && validateForNull($from))
	{
	$from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
		$from=$from." 00:00:00";
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
		$to=$to." 23:59:59";
}	


if(isset($from_added) && validateForNull($from_added))
	{
	$from_added = str_replace('/', '-', $from_added);
		$from_added = date('Y-m-d',strtotime($from_added));
		$from_added = $from_added." 00:00:00";
	}
if(isset($to_added) && validateForNull($to_added))
{
	$to_added = str_replace('/', '-', $to_added);
		$to_added=date('Y-m-d',strtotime($to_added));
		$to_added=$to_added." 23:59:59";
}	


if(isset($from) && validateForNull($from) && isset($to) && validateForNull($to))
{
	$date_diff = getDateDifferneceBetweenDates($from,$to); 
	
	if($date_diff<365)
	{
	$month_array = getMonthArrayBetweenTwoDates($from,$to);	
	$month_array_string = implode(',',$month_array);
	$from_month = date('n',strtotime($from));
    $to_month = date('n',strtotime($to));
	$from_day = date('j',strtotime($from));
    $to_day = date('j',strtotime($to));
	}
}
	
	$today=getTodaysDate();
	
	$admin_session_id = $_SESSION['EMSadminSession']['admin_id'];
	$his_member_id_array = getHisTeamMemberIdsForAnAdminId($admin_session_id);
	$his_member_id_string = implode(",", $his_member_id_array);
	
	
	$sql="SELECT ems_customer.customer_id, customer_name, customer_email, customer_dob, customer_address, city_id,  data_from_id, ems_customer.created_by, ems_customer.date_added
	 	  FROM 
	      ems_customer 
		  
		  LEFT JOIN ems_customer_extra_details ON ems_customer.customer_id=ems_customer_extra_details.customer_id
		  
		   
		  
		  WHERE customer_address!= ' ' AND 1=1"; 
	
	if(isset($date_diff) && checkForNumeric($date_diff,$from_day,$from_month,$to_day,$to_month) && $date_diff<365 && validateForNull($month_array_string))
	{
	$sql=$sql." AND MONTH(customer_dob) IN ( ".$month_array_string.") 
		  AND ( ( MONTH(customer_dob) >= $from_month && DAY(customer_dob) >= $from_day  )";
		  
	if($to_month<$from_month)
	$sql=$sql." OR ";
	else
	$sql=$sql." AND ";
	
	$sql=$sql." ( MONTH(customer_dob) <= $to_month && DAY(customer_dob) <= $to_day  ) ) ";	  	  
	}
	
	if(isset($min_amount) && validateForNull($min_amount))
	$sql=$sql." AND total_mrp>='$min_amount' 
		   ";
	if(isset($max_amount) && validateForNull($max_amount))  
	$sql=$sql." AND total_mrp<='$max_amount'";
	
	if(isset($data_from_id) && validateForNull($data_from_id))  
	$sql=$sql." AND data_from_id IN ($data_from_id_string)";
	
	if(isset($city_id) && validateForNull($city_id))  
	$sql=$sql." AND city_id IN ($city_id_string)";
	
	if(isset($from_added) && validateForNull($from_added))
	$sql=$sql." AND date_added>='$from_added'";
	
	if(isset($to_added) && validateForNull($to_added))  
	$sql=$sql." AND date_added<='$to_added 23:59:59'";
	
	
	if(isset($cust_group_id_array) && validateForNull($cust_group_id_array))
	{
	$cust_group_id_string = implode(",",$cust_group_id_array);	
	$sql=$sql." AND ems_customer.customer_id IN (SELECT customer_id FROM ems_rel_customer_group WHERE customer_group_id IN  ($cust_group_id_string) ) ";
	}
	
	
	$sql=$sql." AND created_by IN ($his_member_id_string)";

	$sql=$sql." ORDER BY date_added";
	
	
	
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	return $resultArray;		
}


/*SELECT b_date FROM foos 
  WHERE DAYOFYEAR(b_date) BETWEEN 
    DAYOFYEAR('2011-01-07' - INTERVAL 10 DAY) AND (DAYOFYEAR('2011-01-07' - INTERVAL 10 DAY) + 21)
  OR DAYOFYEAR(b_date) BETWEEN 
    (DAYOFYEAR('2011-01-07' + INTERVAL 10 DAY) - 21) AND DAYOFYEAR('2011-01-07' + INTERVAL 10 DAY)
  GROUP BY foos.id;*/

function viewLeadsOfLastSevenDays()
{
	
	
	$today=getTodaysDateTime();
    $sevenDaysAgoDate =  date('Y-m-d', strtotime('-7 day', strtotime($today)));
	$sevenDaysAgoDateAndTime = $sevenDaysAgoDate;
	

   $sql= "SELECT enquiry_form_id, date_added
	 	  FROM 
	      ems_enquiry_form
		  WHERE  (is_bought=0 OR is_bought=3) AND
	      date_added>='$sevenDaysAgoDateAndTime' AND date_added<='$today' 
	      ORDER BY date_added";
	
	
	$result=dbQuery($sql);
	
	$resultArray=dbResultToArray($result);
	return $resultArray;		
}



function getLeadsReminder($from=null,$to=null)
{
	
	if(isset($from) && validateForNull($from))
	{
	$from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
    {
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
		$to=$to." 23:59:59";
	}
	
	$today=getTodaysDateTime();
    
	

$sql="SELECT `enquiry_form_id`, date_added, IF((SELECT MAX( `next_follow_up_date` ) FROM  `ems_follow_up` WHERE  `ems_follow_up`.`enquiry_form_id` =  `ems_enquiry_form`.`enquiry_form_id`
) IS NULL, `follow_up_date`, (

SELECT MAX(  `next_follow_up_date` ) 
FROM  `ems_follow_up` 
WHERE  `ems_follow_up`.`enquiry_form_id` =  `ems_enquiry_form`.`enquiry_form_id`
)) as `ultimate_follow_up_date` FROM `ems_enquiry_form` WHERE (is_bought=0 OR is_bought=3) ";

if(isset($from) && validateForNull($from))
$sql=$sql." AND IF((SELECT MAX( `next_follow_up_date` ) 
FROM  `ems_follow_up` 
WHERE  `ems_follow_up`.`enquiry_form_id` =  `ems_enquiry_form`.`enquiry_form_id`
) IS NULL, `follow_up_date`, (SELECT MAX(  `next_follow_up_date` ) 
FROM  `ems_follow_up` 
WHERE  `ems_follow_up`.`enquiry_form_id` =  `ems_enquiry_form`.`enquiry_form_id`
)) >= '$from'";

if(isset($to) && validateForNull($to)) 
$sql=$sql." AND IF((SELECT MAX( `next_follow_up_date` ) 
FROM  `ems_follow_up` 
WHERE  `ems_follow_up`.`enquiry_form_id` =  `ems_enquiry_form`.`enquiry_form_id`
) IS NULL, `follow_up_date`, (SELECT MAX(  `next_follow_up_date` ) 
FROM  `ems_follow_up` 
WHERE  `ems_follow_up`.`enquiry_form_id` =  `ems_enquiry_form`.`enquiry_form_id`
)) <='$to'";

$sql=$sql." ORDER BY ultimate_follow_up_date";



$result=dbQuery($sql);
	
	$resultArray=dbResultToArray($result);
	return $resultArray;	
}

		


function getNextSevenDaysLeadsReminder()
{
	$today=getTodaysDateTime();
    $sevenDaysAfterDate =  date('Y-m-d', strtotime('+7 day', strtotime($today)));
	

$sql="SELECT `enquiry_form_id`, date_added, IF((SELECT MAX( `next_follow_up_date` ) 
FROM  `ems_follow_up` 
WHERE  `ems_follow_up`.`enquiry_form_id` =  `ems_enquiry_form`.`enquiry_form_id`
) IS NULL, `follow_up_date`, (

SELECT MAX(  `next_follow_up_date` ) 
FROM  `ems_follow_up` 
WHERE  `ems_follow_up`.`enquiry_form_id` =  `ems_enquiry_form`.`enquiry_form_id`
)) as `ultimate_follow_up_date` FROM `ems_enquiry_form` WHERE (is_bought=0 OR is_bought=3) AND IF((SELECT MAX( `next_follow_up_date` ) 
FROM  `ems_follow_up` 
WHERE  `ems_follow_up`.`enquiry_form_id` =  `ems_enquiry_form`.`enquiry_form_id`
) IS NULL, `follow_up_date`, (

SELECT MAX(  `next_follow_up_date` ) 
FROM  `ems_follow_up` 
WHERE  `ems_follow_up`.`enquiry_form_id` =  `ems_enquiry_form`.`enquiry_form_id`
)) >= '$today' AND IF((SELECT MAX( `next_follow_up_date` ) 
FROM  `ems_follow_up` 
WHERE  `ems_follow_up`.`enquiry_form_id` =  `ems_enquiry_form`.`enquiry_form_id`
) IS NULL, `follow_up_date`, (

SELECT MAX(  `next_follow_up_date` ) 
FROM  `ems_follow_up` 
WHERE  `ems_follow_up`.`enquiry_form_id` =  `ems_enquiry_form`.`enquiry_form_id`
)) <='$sevenDaysAfterDate'

ORDER BY ultimate_follow_up_date";

$result=dbQuery($sql);
	
	$resultArray=dbResultToArray($result);
	return $resultArray;	
}





function viewPurchaseDates($from=null,$to=null)
{
	
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
	
	$today=getTodaysDate();
	
	$admin_session_id = $_SESSION['EMSadminSession']['admin_id'];
	$his_member_id_array = getHisTeamMemberIdsForAnAdminId($admin_session_id);
	$his_member_id_string = implode(",", $his_member_id_array);
	
	$sql="SELECT ems_enquiry_form.enquiry_form_id, is_bought, ems_enquiry_form.current_lead_holder, purchase_date, admin_name, ems_customer.customer_name, ems_rel_subCategory_enquiry_form.sub_cat_id, (SELECT GROUP_CONCAT(DISTINCT customer_contact_no SEPARATOR '<br>') FROM ems_customer_contact_no WHERE ems_customer_contact_no.customer_id = ems_customer.customer_id GROUP BY ems_customer.customer_id ) as contact_no, (SELECT GROUP_CONCAT(DISTINCT sub_cat_name SEPARATOR '<br>') FROM ems_subCategory WHERE ems_rel_subCategory_enquiry_form.sub_cat_id = ems_subCategory.sub_cat_id AND ems_rel_subCategory_enquiry_form.enquiry_form_id = ems_enquiry_form.enquiry_form_id GROUP BY ems_rel_subCategory_enquiry_form.enquiry_form_id) as products
	
	 	  FROM ems_enquiry_form
		  
		  JOIN ems_rel_subCategory_enquiry_form ON ems_enquiry_form.enquiry_form_id = ems_rel_subCategory_enquiry_form.enquiry_form_id
		  
		  JOIN ems_customer ON ems_customer.customer_id = ems_enquiry_form.customer_id
		   
		  JOIN ems_admin ON ems_admin.admin_id = ems_enquiry_form.enquiry_closed_by
		  
		  WHERE is_bought=1";
		   
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND purchase_date>='$from'";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND purchase_date<='$to'";
	
	$sql=$sql." AND current_lead_holder IN ($his_member_id_string) ";
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return $resultArray;		
}


function viewTourEndingDates($from=null,$to=null)
{
	
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
	
	$today=getTodaysDate();
	
	$admin_session_id = $_SESSION['EMSadminSession']['admin_id'];
	$his_member_id_array = getHisTeamMemberIdsForAnAdminId($admin_session_id);
	$his_member_id_string = implode(",", $his_member_id_array);
	
	$sql="SELECT ems_enquiry_form.enquiry_form_id, is_bought, ems_enquiry_form.current_lead_holder, purchase_date, tour_ending_date, admin_name, ems_customer.customer_name, ems_customer.customer_email, ems_rel_subCategory_enquiry_form.sub_cat_id, (SELECT GROUP_CONCAT(DISTINCT customer_contact_no SEPARATOR '<br>') FROM ems_customer_contact_no WHERE ems_customer_contact_no.customer_id = ems_customer.customer_id GROUP BY ems_customer.customer_id ) as contact_no, (SELECT GROUP_CONCAT(DISTINCT sub_cat_name SEPARATOR '<br>') FROM ems_subCategory WHERE ems_rel_subCategory_enquiry_form.sub_cat_id = ems_subCategory.sub_cat_id AND ems_rel_subCategory_enquiry_form.enquiry_form_id = ems_enquiry_form.enquiry_form_id GROUP BY ems_rel_subCategory_enquiry_form.enquiry_form_id) as products
	
	 	  FROM ems_enquiry_form
		  
		  JOIN ems_rel_subCategory_enquiry_form ON ems_enquiry_form.enquiry_form_id = ems_rel_subCategory_enquiry_form.enquiry_form_id
		  
		  JOIN ems_customer ON ems_customer.customer_id = ems_enquiry_form.customer_id
		   
		  JOIN ems_admin ON ems_admin.admin_id = ems_enquiry_form.enquiry_closed_by
		  
		  WHERE is_bought=1";
		   
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND tour_ending_date>='$from'";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND tour_ending_date<='$to'";
	
	$sql=$sql." AND current_lead_holder IN ($his_member_id_string) ";
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return $resultArray;		
}






function viewOneMonthAfterPurchaseDates($from=null,$to=null)
{
	
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
	
	$today=getTodaysDate();
	
	$admin_session_id = $_SESSION['EMSadminSession']['admin_id'];
	$his_member_id_array = getHisTeamMemberIdsForAnAdminId($admin_session_id);
	$his_member_id_string = implode(",", $his_member_id_array);


	
	$sql="SELECT ems_enquiry_form.enquiry_form_id, is_bought, ems_enquiry_form.current_lead_holder, purchase_date, admin_name, ems_customer.customer_name, ems_rel_subCategory_enquiry_form.sub_cat_id, customer_email, ems_customer.customer_id,
	
	(SELECT GROUP_CONCAT(DISTINCT customer_contact_no SEPARATOR '<br>') FROM ems_customer_contact_no WHERE ems_customer_contact_no.customer_id = ems_customer.customer_id GROUP BY ems_customer.customer_id ) as contact_no, 
	
	(SELECT GROUP_CONCAT(DISTINCT sub_cat_name SEPARATOR '<br>') FROM ems_subCategory WHERE ems_rel_subCategory_enquiry_form.sub_cat_id = ems_subCategory.sub_cat_id AND ems_rel_subCategory_enquiry_form.enquiry_form_id = ems_enquiry_form.enquiry_form_id GROUP BY ems_rel_subCategory_enquiry_form.enquiry_form_id) as products
	
	 	  FROM ems_enquiry_form
		  
		  JOIN ems_rel_subCategory_enquiry_form ON ems_enquiry_form.enquiry_form_id = ems_rel_subCategory_enquiry_form.enquiry_form_id
		  
		  JOIN ems_customer ON ems_customer.customer_id = ems_enquiry_form.customer_id
		   
		  JOIN ems_admin ON ems_admin.admin_id = ems_enquiry_form.current_lead_holder
		  
		  WHERE purchase_date != '1970-01-01 00:00:00' AND is_bought=1";
		   
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND purchase_date>='$from'";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND purchase_date<='$to'";
	
	 $sql=$sql." AND current_lead_holder IN ($his_member_id_string) ";
	
	
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return $resultArray;		
}


function viewMeetingCustomers($from=null,$to=null)
{
	
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
	
	$today=getTodaysDate();
	
	$admin_session_id = $_SESSION['EMSadminSession']['admin_id'];
	$his_member_id_array = getHisTeamMemberIdsForAnAdminId($admin_session_id);
	$his_member_id_string = implode(",", $his_member_id_array);


	
	$sql="SELECT ems_enquiry_form.enquiry_form_id, is_bought, ems_enquiry_form.current_lead_holder, admin_name, ems_customer.customer_name, ems_rel_subCategory_enquiry_form.sub_cat_id, customer_email, ems_customer.customer_id,
	
	(SELECT GROUP_CONCAT(DISTINCT customer_contact_no SEPARATOR '<br>') FROM ems_customer_contact_no WHERE ems_customer_contact_no.customer_id = ems_customer.customer_id GROUP BY ems_customer.customer_id ) as contact_no, 
	
	(SELECT GROUP_CONCAT(DISTINCT sub_cat_name SEPARATOR '<br>') FROM ems_subCategory WHERE ems_rel_subCategory_enquiry_form.sub_cat_id = ems_subCategory.sub_cat_id AND ems_rel_subCategory_enquiry_form.enquiry_form_id = ems_enquiry_form.enquiry_form_id GROUP BY ems_rel_subCategory_enquiry_form.enquiry_form_id) as products, 
	
	(SELECT MAX(visit_date) from ems_visit WHERE ems_visit.enquiry_form_id = ems_enquiry_form.enquiry_form_id GROUP BY ems_visit.enquiry_form_id ) as visit_date
	
	 	  FROM ems_enquiry_form
		  
		  JOIN ems_rel_subCategory_enquiry_form ON ems_enquiry_form.enquiry_form_id = ems_rel_subCategory_enquiry_form.enquiry_form_id
		  
		  JOIN ems_customer ON ems_customer.customer_id = ems_enquiry_form.customer_id
		   
		  JOIN ems_admin ON ems_admin.admin_id = ems_enquiry_form.current_lead_holder
		  
		  WHERE  (is_bought=0 OR is_bought=3) HAVING visit_date IS NOT NULL";
		   
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND visit_date>='$from'";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND visit_date<='$to'";
	
	 $sql=$sql." AND current_lead_holder IN ($his_member_id_string) ";
	
	
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return $resultArray;		
}





function leadEfficiencyAnalysis($from=null,$to=null, $product=null, $super_cat=null, $cat=null,$attr_name_array=null, $user_id=null)
{
	
	
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

            if(validateForNull($user_id))
		    $user_id_string=implode(',', $user_id);
			
			if(validateForNull($product))
		    $product_string=implode(',', $product);
			
			if(validateForNull($super_cat))
		    $super_cat_string=implode(',', $super_cat);
			
			if(validateForNull($cat))
		    $cat_string=implode(',',$cat);
			
			if(validateForNull($attr_name_array))
		    $attr_name_string=implode(',', $attr_name_array);
	
	$today=getTodaysDate();
	
	$admin_session_id = $_SESSION['EMSadminSession']['admin_id'];
	$his_member_id_array = getHisTeamMemberIdsForAnAdminId($admin_session_id);
	$his_member_id_string = implode(",", $his_member_id_array);
	
	$sql="SELECT 
	
	(SELECT COUNT(ems_enquiry_form.enquiry_form_id) 
	FROM ems_enquiry_form 
	LEFT JOIN ems_rel_subCategory_enquiry_form as sub_cat_rel
	ON sub_cat_rel.enquiry_form_id = ems_enquiry_form.enquiry_form_id 
	LEFT JOIN ems_subCategory as sub_cat 
	ON sub_cat_rel.sub_cat_id = sub_cat.sub_cat_id 
	WHERE 1=1";
	
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND enquiry_date>='$from' ";
	if(isset($to) && validateForNull($to))
	$sql=$sql." AND enquiry_date<='$to' ";
	
	if(isset($user_id) && validateForNull($user_id) && $user_id>0)  
	$sql=$sql." AND ems_enquiry_form.created_by IN ($user_id_string)";
	
	if((isset($product_string) && validateForNull($product_string)))
	{
	$sql=$sql." AND (";	  
	if(isset($product_string) && validateForNull($product_string))  
	$sql=$sql." sub_cat.sub_cat_id IN ($product_string)";
	
	$sql=$sql.")";
     
	}
	
	$sql=$sql." AND current_lead_holder IN ($his_member_id_string) ";
	
	$sql=$sql.") as total_enquiry, 
	
	(SELECT COUNT(ems_enquiry_form.enquiry_form_id) FROM ems_enquiry_form LEFT JOIN ems_rel_subCategory_enquiry_form as sub_cat_rel
	ON sub_cat_rel.enquiry_form_id = ems_enquiry_form.enquiry_form_id 
	LEFT JOIN ems_subCategory as sub_cat 
	ON sub_cat_rel.sub_cat_id = sub_cat.sub_cat_id  WHERE is_bought=1";
	
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND enquiry_date>='$from' ";
	if(isset($to) && validateForNull($to))
	$sql=$sql." AND enquiry_date<='$to' ";
	
	if(isset($user_id) && validateForNull($user_id) && $user_id>0)  
	$sql=$sql." AND ems_enquiry_form.created_by IN ($user_id_string)";
	
	if((isset($product_string) && validateForNull($product_string)))
	{
	$sql=$sql." AND (";	  
	if(isset($product_string) && validateForNull($product_string))  
	$sql=$sql." sub_cat.sub_cat_id IN ($product_string)";
	
	$sql=$sql.")";
     
	}
	
	$sql=$sql." AND current_lead_holder IN ($his_member_id_string) ";
	
	
	$sql=$sql.") as successful_enquiries, 
	
	
	
	(SELECT COUNT(ems_enquiry_form.enquiry_form_id) FROM ems_enquiry_form LEFT JOIN ems_rel_subCategory_enquiry_form as sub_cat_rel
	ON sub_cat_rel.enquiry_form_id = ems_enquiry_form.enquiry_form_id 
	LEFT JOIN ems_subCategory as sub_cat 
	ON sub_cat_rel.sub_cat_id = sub_cat.sub_cat_id  WHERE is_bought=2";
	
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND enquiry_date>='$from' ";
	if(isset($to) && validateForNull($to))
	$sql=$sql." AND enquiry_date<='$to' ";
	
	if(isset($user_id) && validateForNull($user_id) && $user_id>0)  
	$sql=$sql." AND ems_enquiry_form.created_by IN ($user_id_string)";
	
	if((isset($product_string) && validateForNull($product_string)))
	{
	$sql=$sql." AND (";	  
	if(isset($product_string) && validateForNull($product_string))  
	$sql=$sql." sub_cat.sub_cat_id IN ($product_string)";
	
	$sql=$sql.")";
     
	}
	
	$sql=$sql." AND current_lead_holder IN ($his_member_id_string) ";
	
	$sql=$sql.") as unsuccessful_enquiries,  
	
	
	(SELECT COUNT(ems_enquiry_form.enquiry_form_id) FROM ems_enquiry_form LEFT JOIN ems_rel_subCategory_enquiry_form as sub_cat_rel
	ON sub_cat_rel.enquiry_form_id = ems_enquiry_form.enquiry_form_id 
	LEFT JOIN ems_subCategory as sub_cat 
	ON sub_cat_rel.sub_cat_id = sub_cat.sub_cat_id  WHERE is_bought=3";
	
	 if(isset($from) && validateForNull($from))
	$sql=$sql." AND enquiry_date>='$from' ";
	if(isset($to) && validateForNull($to))
	$sql=$sql." AND enquiry_date<='$to' ";
	
	if(isset($user_id) && validateForNull($user_id) && $user_id>0)  
	$sql=$sql." AND ems_enquiry_form.created_by IN ($user_id_string)";
	
	if((isset($product_string) && validateForNull($product_string)))
	{
	$sql=$sql." AND (";	  
	if(isset($product_string) && validateForNull($product_string))  
	$sql=$sql." sub_cat.sub_cat_id IN ($product_string)";
	
	$sql=$sql.")";
     
	}
	
	$sql=$sql." AND current_lead_holder IN ($his_member_id_string) ";
	
	$sql=$sql.") as ongoing_enquiries, 
	 
	
	(SELECT COUNT(ems_enquiry_form.enquiry_form_id) FROM ems_enquiry_form LEFT JOIN ems_rel_subCategory_enquiry_form as sub_cat_rel
	ON sub_cat_rel.enquiry_form_id = ems_enquiry_form.enquiry_form_id 
	LEFT JOIN ems_subCategory as sub_cat 
	ON sub_cat_rel.sub_cat_id = sub_cat.sub_cat_id  WHERE is_bought=0";
	
	  if(isset($from) && validateForNull($from))
	$sql=$sql." AND enquiry_date>='$from' ";
	if(isset($to) && validateForNull($to))
	$sql=$sql." AND enquiry_date<='$to' ";
	
	if(isset($user_id) && validateForNull($user_id) && $user_id>0)  
	$sql=$sql." AND ems_enquiry_form.created_by IN ($user_id_string)";
	
	if((isset($product_string) && validateForNull($product_string)))
	{
	$sql=$sql." AND (";	  
	if(isset($product_string) && validateForNull($product_string))  
	$sql=$sql." sub_cat.sub_cat_id IN ($product_string)";
	
	$sql=$sql.")";
     
	}
	
	$sql=$sql." AND current_lead_holder IN ($his_member_id_string) ";
	
	$sql=$sql.") as new_enquiries
	
	FROM ems_enquiry_form
		  
	WHERE 1=1";
		   
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND enquiry_date>='$from'";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND enquiry_date<='$to'";
	
	
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return $resultArray[0];
}


function userWiseReporting($from=null,$to=null)
{
	
	
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

    $today=getTodaysDate();
	
	$admin_session_id = $_SESSION['EMSadminSession']['admin_id'];
	$his_member_id_array = getHisTeamMemberIdsForAnAdminId($admin_session_id);
	$his_member_id_string = implode(",", $his_member_id_array);
	
	$sql="SELECT admin_name,
	
	(SELECT COUNT(ems_enquiry_form.enquiry_form_id) 
	FROM ems_enquiry_form 
	WHERE 1=1";
	
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND ems_enquiry_form.date_added>='$from' ";
	if(isset($to) && validateForNull($to))
	$sql=$sql." AND ems_enquiry_form.enquiry_date<='$to' ";
	
	$sql=$sql." AND current_lead_holder = ems_admin.admin_id ";
	
	$sql=$sql.") as total_enquiry_generated_by_user, 
	
	(SELECT COUNT(follow_up_id) 
	FROM ems_follow_up 
	LEFT JOIN ems_enquiry_form
	ON ems_enquiry_form.enquiry_form_id = ems_follow_up.enquiry_form_id 
	WHERE 1=1";
	
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND ems_follow_up.date_added>='$from' ";
	if(isset($to) && validateForNull($to))
	$sql=$sql." AND ems_follow_up.date_added<='$to' ";
	
	$sql=$sql." AND current_lead_holder  = ems_admin.admin_id";
	
	$sql=$sql.") as done_follow_ups_by_user
	
	FROM ems_enquiry_form
	
	JOIN ems_admin 
    ON ems_admin.admin_id = ems_enquiry_form.current_lead_holder
		  
    GROUP BY ems_enquiry_form.current_lead_holder";
	
	
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return $resultArray;
}



function sourceOfEnquiryWiseReporting($from=null,$to=null)
{
	
	
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

    $today=getTodaysDate();
	
	$admin_session_id = $_SESSION['EMSadminSession']['admin_id'];
	$his_member_id_array = getHisTeamMemberIdsForAnAdminId($admin_session_id);
	$his_member_id_string = implode(",", $his_member_id_array);
	
	$sql="SELECT IF(ems_customer_type.customer_type_id IS NOT NULL,customer_type,'Undefined Source') as customer_type,
	
	(SELECT COUNT(ems_enquiry_form.enquiry_form_id) 
	FROM ems_enquiry_form 
	WHERE 1=1";
	
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND ems_enquiry_form.date_added>='$from' ";
	if(isset($to) && validateForNull($to))
	$sql=$sql." AND ems_enquiry_form.enquiry_date<='$to' ";
	
	$sql=$sql." AND ((ems_customer_type.customer_type_id>0 AND ems_customer_type.customer_type_id = ems_enquiry_form.customer_type_id) OR (ems_enquiry_form.customer_type_id IS NULL AND ems_customer_type.customer_type_id IS NULL)) ";
	
	$sql=$sql.") as total_enquiry_generated, 
	
	(SELECT COUNT(ems_enquiry_form.enquiry_form_id) 
	FROM ems_enquiry_form 
	WHERE is_bought=1";
	
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND ems_enquiry_form.date_added>='$from' ";
	if(isset($to) && validateForNull($to))
	$sql=$sql." AND ems_enquiry_form.enquiry_date<='$to' ";
	
	$sql=$sql." AND ((ems_customer_type.customer_type_id>0 AND ems_customer_type.customer_type_id = ems_enquiry_form.customer_type_id) OR (ems_enquiry_form.customer_type_id IS NULL AND ems_customer_type.customer_type_id IS NULL)) ";
	
	$sql=$sql.") as successful_enquiry_generated,
	
	(SELECT COUNT(ems_enquiry_form.enquiry_form_id) 
	FROM ems_enquiry_form 
	WHERE is_bought=2";
	
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND ems_enquiry_form.date_added>='$from' ";
	if(isset($to) && validateForNull($to))
	$sql=$sql." AND ems_enquiry_form.enquiry_date<='$to' ";
	
	$sql=$sql." AND ((ems_customer_type.customer_type_id>0 AND ems_customer_type.customer_type_id = ems_enquiry_form.customer_type_id) OR (ems_enquiry_form.customer_type_id IS NULL AND ems_customer_type.customer_type_id IS NULL)) ";
	
	$sql=$sql.") as unsuccessful_enquiry_generated
	 
	FROM ems_enquiry_form
	LEFT JOIN ems_customer_type 
    ON ems_customer_type.customer_type_id = ems_enquiry_form.customer_type_id
    GROUP BY ems_enquiry_form.customer_type_id";
	
	
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return $resultArray;
}




/*function viewExpiredInsurances($from=null,$to=null, $min_amount=null, $max_amount=null, $product=null, $inCompany=null)
{
	        
			
			
			if(validateForNull($product))
		    $product_string=implode(',',$product);
			
			if(validateForNull($inCompany))
		    $inCompany_string=implode(',',$inCompany);
			
	
	if(isset($from) && validateForNull($from))
	{
	$from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
		$from=$from." 00:00:00";
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
		$to=$to." 23:59:59";
}	
	
	$today=getTodaysDate();
	
	$sql="SELECT insurance_start_date, insurance_end_date, insure_com_id, idv, final_amount,           ems_vehicle_insurance.customer_id, ems_vehicle_insurance.vehicle_id, ems_vehicle.vehicle_id, vehicle_model_id, vehicle_company_id 
	 	  FROM 
	      ems_vehicle_insurance, ems_vehicle
		  WHERE ems_vehicle_insurance.vehicle_id = ems_vehicle.vehicle_id";
	
	 
		  
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND insurance_end_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND insurance_end_date<='$to'";
	
	if(isset($min_amount) && validateForNull($min_amount))
	$sql=$sql." AND final_amount>='$min_amount' 
		   ";
	if(isset($max_amount) && validateForNull($max_amount))  
	$sql=$sql." AND final_amount<='$max_amount'";
	
	if(isset($product) && validateForNull($product) && $product>0)  
	$sql=$sql." AND vehicle_model_id IN ($product_string)";
	
	if(isset($inCompany) && validateForNull($inCompany) && $inCompany>0)  
	$sql=$sql." AND insure_com_id IN ($inCompany_string)";
	
	$sql=$sql." ORDER BY insurance_end_date";
	
	
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	return $resultArray;		
}

function viewNewInsurances($from=null,$to=null, $min_amount=null, $max_amount=null, $product=null)
{
	        
			
			if(validateForNull($product))
		    $product_string=implode(',',$product);
			
	
	if(isset($from) && validateForNull($from))
	{
	$from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
		$from=$from." 00:00:00";
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
		$to=$to." 23:59:59";
}	
	
	$today=getTodaysDate();
	
	$sql="SELECT insurance_start_date, insurance_end_date, insure_com_id, idv, final_amount, ems_vehicle_insurance.customer_id, ems_vehicle_insurance.vehicle_id, ems_vehicle.vehicle_id, vehicle_model_id, vehicle_company_id 
	 	  FROM 
	      ems_vehicle_insurance, ems_vehicle
		  WHERE ems_vehicle_insurance.vehicle_id = ems_vehicle.vehicle_id";
	
	 
		  
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND insurance_start_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND insurance_start_date<='$to'";
	
	if(isset($min_amount) && validateForNull($min_amount))
	$sql=$sql." AND final_amount>='$min_amount' 
		   ";
	if(isset($max_amount) && validateForNull($max_amount))  
	$sql=$sql." AND final_amount<='$max_amount'";
	
	if(isset($product) && validateForNull($product) && $product>0)  
	$sql=$sql." AND sub_cat_id IN ($product_string)";
	
	$sql=$sql." ORDER BY insurance_start_date";
	
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	return $resultArray;		
}

function dailyInvoices()


{
	
	$today=getTodaysDate();
	
	$sql="SELECT in_customer_id, invoice_date, in_customer_name
	 	  FROM ems_invoice_customer
		  WHERE invoice_date='$today'";
	
	
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return $resultArray;		
}

function viewInvoices($from=null,$to=null)


{
	
	
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
	
	$today=getTodaysDate();
	
	$sql="SELECT in_customer_id, invoice_date, in_customer_name
	 	  FROM 
	      ems_invoice_customer
		  WHERE 1=1"; 
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND invoice_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND invoice_date<='$to'";
	
	$sql=$sql."ORDER BY invoice_date";
	
	
	
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return $resultArray;		
}

*/



function countTodaysFollowUps($from=null,$to=null, $min_amount=null, $max_amount=null, $user_id=null, $customer_type_id=null, $leadStatus=null, $product=null, $super_cat=null, $cat=null,$attr_name_array=null)
{
	   $from = getTodaysDate();
	   $to = getTodaysDate();
	        
	         
	        if(validateForNull($customer_type_id))
		    $customer_type_id_string=implode(',',$customer_type_id);
	        
	        if(validateForNull($user_id))
		    $user_id_string=implode(',',$user_id);
			
			if(validateForNull($leadStatus))
		    $lead_status_string=implode(',',$leadStatus);
			
			if(validateForNull($product))
		    $product_string=implode(',',$product);
			
			if(validateForNull($super_cat))
		    $super_cat_string=implode(',',$super_cat);
			
			if(validateForNull($cat))
		    $cat_string=implode(',',$cat);
			
			if(validateForNull($attr_name_array))
		    $attr_name_string=implode(',',$attr_name_array);
			
	
	if(isset($from) && validateForNull($from))
	{
	$from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
		$from=$from." 00:00:00";
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
		$to=$to." 23:59:59";
}	

     $admin_session_id = $_SESSION['EMSadminSession']['admin_id'];
	$his_member_id_array = getHisTeamMemberIdsForAnAdminId($admin_session_id);
	
	
	$his_member_id_string = implode(",", $his_member_id_array);
	
	$today=getTodaysDate();
	
	$sql="SELECT ems_enquiry_form.enquiry_form_id, ems_enquiry_form.date_added, enquiry_date, ems_enquiry_form.created_by, total_mrp, customer_name, is_bought, ems_enquiry_form.current_lead_holder, GROUP_CONCAT(DISTINCT customer_contact_no SEPARATOR '<br>') as contact_no, GROUP_CONCAT(customer_price SEPARATOR '<br>') as customer_price, customer_type_id, is_bought, GROUP_CONCAT(sub_cat.sub_cat_id), GROUP_CONCAT(sub_cat.sub_cat_name SEPARATOR ' <br> ') as sub_cat_name, ems_enquiry_form.enquiry_date,  COALESCE(NULLIF(GROUP_CONCAT((SELECT GROUP_CONCAT(CONCAT_WS(' : ',ems_attribute_type.attribute_type,ems_attribute_name.attribute_name) SEPARATOR ' <br>') FROM ems_rel_subCat_enquiry_form_attributes, ems_attribute_type, ems_attribute_name  WHERE sub_cat.sub_cat_id = ems_rel_subCat_enquiry_form_attributes.sub_cat_id AND ems_rel_subCat_enquiry_form_attributes.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_rel_subCat_enquiry_form_attributes.attribute_type_id = ems_attribute_type.attribute_type_id AND ems_rel_subCat_enquiry_form_attributes.attribute_name_id = ems_attribute_name.attribute_name_id) SEPARATOR '<hr>'),''),'No Details')  as attribute_types_sub_cat_wise
, (SELECT IF(ems_follow_up.enquiry_form_id IS NULL, CONCAT_WS(' # ',CONCAT_WS(' ^ ',follow_up_date,discussion),ems_admin.admin_name) , CONCAT_WS(' # ',CONCAT_WS(' ^ ',next_follow_up_date,discussion),follow_handler.admin_name)) FROM ems_enquiry_form as enq_form_table INNER JOIN ems_admin ON enq_form_table.created_by = ems_admin.admin_id  LEFT JOIN ems_follow_up ON enq_form_table.enquiry_form_id = ems_follow_up.enquiry_form_id LEFT JOIN ems_admin as follow_handler ON ems_follow_up.created_by = follow_handler.admin_id  WHERE (ems_follow_up.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_follow_up.enquiry_form_id IS NOT NULL) OR (enq_form_table.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_follow_up.enquiry_form_id IS NULL) ORDER BY next_follow_up_date DESC,ems_follow_up.date_added DESC LIMIT 0,1) as next_follow_up_date
	 	  FROM
	      ems_enquiry_form, ems_rel_subCategory_enquiry_form, ems_subCategory as sub_cat, ems_customer, ems_customer_contact_no
		  WHERE 
		  ems_enquiry_form.enquiry_form_id=ems_rel_subCategory_enquiry_form.enquiry_form_id 
		  AND 
		  ems_rel_subCategory_enquiry_form.sub_cat_id = sub_cat.sub_cat_id
		  AND
		  ems_customer.customer_id = ems_enquiry_form.customer_id
		  AND
		  ems_customer.customer_id = ems_customer_contact_no.customer_id
		  AND
		   (is_bought=3 OR is_bought=0)"; 
		  
	if(isset($min_amount) && validateForNull($min_amount))
	$sql=$sql." AND total_mrp>='$min_amount' 
		   ";
	if(isset($max_amount) && validateForNull($max_amount))  
	$sql=$sql." AND total_mrp<='$max_amount'";
	
	if(isset($user_id) && validateForNull($user_id) && $user_id>0)  
	$sql=$sql." AND ems_enquiry_form.created_by IN ($user_id_string)";
	
	if(isset($customer_type_id) && validateForNull($customer_type_id) && $customer_type_id>0)  
	$sql=$sql." AND customer_type_id IN ($customer_type_id_string)";
	
	if(isset($leadStatus) && validateForNull($leadStatus))  
	$sql=$sql." AND is_bought IN ($lead_status_string)";
	
	
	if((isset($product_string) && validateForNull($product_string)) || (isset($super_cat_string) && validateForNull($super_cat_string)) || (isset($cat_string) && validateForNull($cat_string)))
	{
	$sql=$sql."AND (";	  
	if(isset($product_string) && validateForNull($product_string))  
	$sql=$sql." sub_cat.sub_cat_id IN ($product_string)";
	
	
	if(isset($product_string) && validateForNull($product_string) && ((isset($super_cat_string) && validateForNull($super_cat_string)) || (isset($cat_string) && validateForNull($cat_string))))
	$sql=$sql." OR ";
	
	if(isset($super_cat_string) && validateForNull($super_cat_string))  
	$sql=$sql." super_cat_id IN ($super_cat_string)";
	
	if(((isset($product_string) && validateForNull($product_string)) || (isset($super_cat_string) && validateForNull($super_cat_string))) && isset($cat_string) && validateForNull($cat_string))
	$sql=$sql." OR ";
	
	if(isset($cat_string) && validateForNull($cat_string))  
	$sql=$sql." cat_id IN ($cat_string)";
	
	$sql=$sql.")";
	
	if(isset($attr_name_string) && validateForNull($attr_name_string))  
	$sql=$sql." AND attribute_name_id IN ($attr_name_string)";
	
	
	}
	
	$sql=$sql." GROUP BY ems_enquiry_form.enquiry_form_id HAVING next_follow_up_date!='1970-01-01' "; 
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND next_follow_up_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND next_follow_up_date<='$to'";
	
	$sql=$sql." AND current_lead_holder = $admin_session_id";
	
 
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	return count($resultArray);		
}


function countExpiredFollowUps($from=null,$to=null, $min_amount=null, $max_amount=null, $user_id=null, $customer_type_id=null, $leadStatus=null, $product=null, $super_cat=null, $cat=null,$attr_name_array=null)
{
	       
	        $to = getTodaysDate();
		   
		  // echo "To Date : ".$to;
	
	        if(validateForNull($customer_type_id))
		    $customer_type_id_string=implode(',',$customer_type_id);
	        
	        if(validateForNull($user_id))
		    $user_id_string=implode(',',$user_id);
			
			if(validateForNull($leadStatus))
		    $lead_status_string=implode(',',$leadStatus);
			
			if(validateForNull($product))
		    $product_string=implode(',',$product);
			
			if(validateForNull($super_cat))
		    $super_cat_string=implode(',',$super_cat);
			
			if(validateForNull($cat))
		    $cat_string=implode(',',$cat);
			
			if(validateForNull($attr_name_array))
		    $attr_name_string=implode(',',$attr_name_array);
			
	
	if(isset($from) && validateForNull($from))
	{
	$from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
		$from=$from." 00:00:00";
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
		
}	
	
	$today=getTodaysDate();
	
	$admin_session_id = $_SESSION['EMSadminSession']['admin_id'];
	$his_member_id_array = getHisTeamMemberIdsForAnAdminId($admin_session_id);
	$his_member_id_string = implode(",", $his_member_id_array);

	
	$sql="SELECT ems_enquiry_form.enquiry_form_id, ems_enquiry_form.date_added, enquiry_date, ems_enquiry_form.created_by, total_mrp, customer_name, is_bought, ems_enquiry_form.current_lead_holder, GROUP_CONCAT(DISTINCT customer_contact_no SEPARATOR '<br>') as contact_no, GROUP_CONCAT(customer_price SEPARATOR '<br>') as customer_price, customer_type_id, is_bought, GROUP_CONCAT(sub_cat.sub_cat_id), GROUP_CONCAT(sub_cat.sub_cat_name SEPARATOR ' <br> ') as sub_cat_name, ems_enquiry_form.enquiry_date,  COALESCE(NULLIF(GROUP_CONCAT((SELECT GROUP_CONCAT(CONCAT_WS(' : ',ems_attribute_type.attribute_type,ems_attribute_name.attribute_name) SEPARATOR ' <br>') FROM ems_rel_subCat_enquiry_form_attributes, ems_attribute_type, ems_attribute_name  WHERE sub_cat.sub_cat_id = ems_rel_subCat_enquiry_form_attributes.sub_cat_id AND ems_rel_subCat_enquiry_form_attributes.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_rel_subCat_enquiry_form_attributes.attribute_type_id = ems_attribute_type.attribute_type_id AND ems_rel_subCat_enquiry_form_attributes.attribute_name_id = ems_attribute_name.attribute_name_id) SEPARATOR '<hr>'),''),'No Details')  as attribute_types_sub_cat_wise
, (SELECT IF(ems_follow_up.enquiry_form_id IS NULL, CONCAT_WS(' # ',CONCAT_WS(' ^ ',follow_up_date,enquiry_discussion),ems_admin.admin_name) , CONCAT_WS(' # ',CONCAT_WS(' ^ ',next_follow_up_date,discussion),follow_handler.admin_name)) FROM ems_enquiry_form as enq_form_table INNER JOIN ems_admin ON enq_form_table.created_by = ems_admin.admin_id  LEFT JOIN ems_follow_up ON enq_form_table.enquiry_form_id = ems_follow_up.enquiry_form_id LEFT JOIN ems_admin as follow_handler ON ems_follow_up.created_by = follow_handler.admin_id  WHERE (ems_follow_up.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_follow_up.enquiry_form_id IS NOT NULL) OR (enq_form_table.enquiry_form_id = ems_enquiry_form.enquiry_form_id AND ems_follow_up.enquiry_form_id IS NULL AND enq_form_table.follow_up_date!='1970-01-01') ORDER BY next_follow_up_date DESC,ems_follow_up.date_added DESC LIMIT 0,1) as next_follow_up_date
	 	  FROM
	      ems_enquiry_form, ems_rel_subCategory_enquiry_form, ems_subCategory as sub_cat, ems_customer, ems_customer_contact_no
		  WHERE 
		  ems_enquiry_form.enquiry_form_id=ems_rel_subCategory_enquiry_form.enquiry_form_id 
		  AND 
		  ems_rel_subCategory_enquiry_form.sub_cat_id = sub_cat.sub_cat_id
		  AND
		  ems_customer.customer_id = ems_enquiry_form.customer_id
		  AND
		  ems_customer.customer_id = ems_customer_contact_no.customer_id
		  AND
		  (is_bought=3 OR is_bought=0)"; 
		  
	
	
	if(isset($min_amount) && validateForNull($min_amount))
	$sql=$sql." AND total_mrp>='$min_amount' 
		   ";
	if(isset($max_amount) && validateForNull($max_amount))  
	$sql=$sql." AND total_mrp<='$max_amount'";
	
	if(isset($user_id) && validateForNull($user_id) && $user_id>0)  
	$sql=$sql." AND ems_enquiry_form.created_by IN ($user_id_string)";
	
	if(isset($customer_type_id) && validateForNull($customer_type_id) && $customer_type_id>0)  
	$sql=$sql." AND customer_type_id IN ($customer_type_id_string)";
	
	if(isset($leadStatus) && validateForNull($leadStatus))  
	$sql=$sql." AND is_bought IN ($lead_status_string)";
	
	
	if((isset($product_string) && validateForNull($product_string)) || (isset($super_cat_string) && validateForNull($super_cat_string)) || (isset($cat_string) && validateForNull($cat_string)))
	{
	$sql=$sql."AND (";	  
	if(isset($product_string) && validateForNull($product_string))  
	$sql=$sql." sub_cat.sub_cat_id IN ($product_string)";
	
	
	if(isset($product_string) && validateForNull($product_string) && ((isset($super_cat_string) && validateForNull($super_cat_string)) || (isset($cat_string) && validateForNull($cat_string))))
	$sql=$sql." OR ";
	
	if(isset($super_cat_string) && validateForNull($super_cat_string))  
	$sql=$sql." super_cat_id IN ($super_cat_string)";
	
	if(((isset($product_string) && validateForNull($product_string)) || (isset($super_cat_string) && validateForNull($super_cat_string))) && isset($cat_string) && validateForNull($cat_string))
	$sql=$sql." OR ";
	
	if(isset($cat_string) && validateForNull($cat_string))  
	$sql=$sql." cat_id IN ($cat_string)";
	
	$sql=$sql.")";
	
	if(isset($attr_name_string) && validateForNull($attr_name_string))  
	$sql=$sql." AND attribute_name_id IN ($attr_name_string)";
	
	
	}
	
	$sql=$sql." GROUP BY ems_enquiry_form.enquiry_form_id HAVING next_follow_up_date!='1970-01-01' "; 
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND next_follow_up_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND next_follow_up_date<='$to'";
	
	$sql=$sql." AND current_lead_holder = $admin_session_id";
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	return count($resultArray);		
}



function countTodaysFollowUpsNew($from=null,$to=null)
{
	   $from = getTodaysDate();
	   $to = getTodaysDate();
	        
	         
	       
if(isset($from) && validateForNull($from))
	{
	$from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
		$from=$from." 00:00:00";
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
		$to=$to." 23:59:59";
}	

    
	$admin_session_id = $_SESSION['EMSadminSession']['admin_id'];
	$his_member_id_array = getHisTeamMemberIdsForAnAdminId($admin_session_id);
	
	$his_member_id_string = implode(",", $his_member_id_array);
	
	$today=getTodaysDate();
	
	$sql="SELECT follow_up_date
	 	  FROM
	      ems_enquiry_form
		  WHERE 
		  (is_bought=3 OR is_bought=0)"; 
		   
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND follow_up_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND follow_up_date<='$to'";
	
	$sql=$sql." AND current_lead_holder = $admin_session_id";
	
     $result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	return count($resultArray);		
}



function countExpiredFollowUpsNew()
{
	   $from = getTodaysDate();
	   $to = getTodaysDate();
	        
	         
	       
if(isset($from) && validateForNull($from))
	{
	$from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
		$from=$from." 00:00:00";
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
		$to=$to." 23:59:59";
}	

    
	$admin_session_id = $_SESSION['EMSadminSession']['admin_id'];
	$his_member_id_array = getHisTeamMemberIdsForAnAdminId($admin_session_id);
	
	$his_member_id_string = implode(",", $his_member_id_array);
	
	$today=getTodaysDate();
	
	$sql="SELECT follow_up_date
	 	  FROM
	      ems_enquiry_form
		  WHERE 
		  (is_bought=3 OR is_bought=0)"; 
		   
	
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND follow_up_date<='$today'";
	
	$sql=$sql." AND current_lead_holder = $admin_session_id";
	
     $result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	return count($resultArray);		
}

?>