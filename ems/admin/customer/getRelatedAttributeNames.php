<?php
require_once "../../lib/cg.php";
require_once "../../lib/bd.php";
require_once "../../lib/rel-attribute-functions.php";

if(isset($_GET['id']) && isset($_GET['state'])){

$id=$_GET['id'];
$type_id=$_GET['state'];
$result=array();

$result=getAttributeNamesFromTypeIdAndSubCatId($id,$type_id);



$str="";


foreach($result as $attribute_type_name_array)
{
 
  $str=$str . "\"$attribute_type_name_array[attribute_name_id]\"".",". "\"$attribute_type_name_array[attribute_name]\"".",";
}

$str=substr($str,0,(strLen($str)-1)); // Removing the last char, from the string
echo "new Array($str)";

}

?>