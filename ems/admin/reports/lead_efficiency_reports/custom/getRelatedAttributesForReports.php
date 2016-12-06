<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/rel-attribute-functions.php";




if(isset($_GET['str1']))
{
$super_cat_str=$_GET['str1'];
$cat_str=$_GET['str2'];
$sub_cat_str=$_GET['str3'];
$super_cat_id_array = explode(',', $super_cat_str);
$cat_id_array = explode(',', $cat_str);
$sub_cat_id_array = explode(',', $sub_cat_str);

$result=array();

$result=getAttributesFromCatSubCatAndSuperCatIdArray($sub_cat_id_array, $super_cat_id_array, $cat_id_array);



$str="";


foreach($result as $attribute_type_name_array)
{
  $attribute_type = $attribute_type_name_array['attribute_type'];
  $str=$str . "\"$attribute_type[attribute_type_id]\"".",". "\"$attribute_type[attribute_type]\"".",";
}

$str=substr($str,0,(strLen($str)-1)); // Removing the last char, from the string
echo "new Array($str)";

}

?>