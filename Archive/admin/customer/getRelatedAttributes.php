<?php
require_once "../../lib/cg.php";
require_once "../../lib/bd.php";
require_once "../../lib/rel-attribute-functions.php";


if(isset($_GET['id'])){
$id=$_GET['id'];

$result=array();

$result=getAttributesFromSubCatId($id);




$str="";


foreach($result as $attribute_type_name_array)
{
  $attribute_type = $attribute_type_name_array['attribute_type'];
  $str=$str . "\"$attribute_type[attribute_type_id]\"".",". "\"$attribute_type[attribute_type]\"".","."\"$attribute_type[single_multiple]\"".",";
}

$str=substr($str,0,(strLen($str)-1)); // Removing the last char, from the string
echo "new Array($str)";

}

?>