<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/common.php";
require_once "../../../../lib/attribute-name-functions.php";
require_once "../../../../lib/attribute-type-functions.php";
require_once "../../../../lib/rel-attribute-functions.php";



if(isset($_GET['id'])){
$id=$_GET['id'];

$id_array = explode(',',$id);

$result=array();
$cat_id_array = array();
$subcat_id_array = array();
$supcat_id_array = array();

foreach($id_array as $ida)
{
	if(validateForNull($ida))
	{
		$pref = substr($ida,0,3);
		$ids = substr($ida,3);
		
		$ids = intval($ids);
		
		switch($pref)
		{
		case 'sub': $subcat_id_array[]=$ids;
					break;	
		case 'cat': $cat_id_array[]=$ids;
					break;
		case 'sup': $supcat_id_array[]=$ids;
					break;
		default : break;							
		}	
	}
}

$result=getAttributeTypeIntersection($subcat_id_array,$supcat_id_array,$cat_id_array);

$str="";
foreach($result as $key=>$value)
{
$attr_type = getAttributeTypeById($value);
$attr_type_name = $attr_type['attribute_type'];	
$str=$str . "\"$value\"".",". "\"$attr_type_name\"".",";
}
$str=substr($str,0,(strLen($str)-1)); // Removing the last char , from the string
echo "new Array($str)";

}

?>