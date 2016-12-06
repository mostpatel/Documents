<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/rel-attribute-functions.php";

if(isset($_GET['id']))
{

$type_id=$_GET['id'];

$super_cat_str=$_GET['str1'];
$cat_str=$_GET['str2'];
$sub_cat_str=$_GET['str3'];

$super_cat_id_array = explode(',', $super_cat_str);
$cat_id_array = explode(',', $cat_str);
$sub_cat_id_array = explode(',', $sub_cat_str);

$result=array();

$result=getAttributeNamesFromTypeIdAndCatSubCatSuperCatIdArrays($sub_cat_id_array, $super_cat_id_array, $cat_id_array, $type_id);



$str="";


foreach($result as $attribute_type_name_array)
{
 
  $str=$str . "\"$attribute_type_name_array[attribute_name_id]\"".",". "\"$attribute_type_name_array[attribute_name]\"".",";
}

$str=substr($str,0,(strLen($str)-1)); // Removing the last char, from the string
echo "new Array($str)";

}

?>