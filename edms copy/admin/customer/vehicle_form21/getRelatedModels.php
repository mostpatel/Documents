<?php
require_once "../../../lib/cg.php";
require_once "../../../lib/bd.php";
require_once "../../../lib/vehicle-model-functions.php";
require_once "../../../lib/vehicle-dealer-functions.php";




if(isset($_GET['id'])){
$id=$_GET['id'];

$result=array();
$result=getModelsFromCompanyID($id);
$str="";
foreach($result as $branch){
$str=$str . "\"$branch[model_id]\"".",". "\"$branch[model_name]\"".",";
}
$str=substr($str,0,(strLen($str)-1)); // Removing the last char , from the string
echo "new Array($str)";

}

?>