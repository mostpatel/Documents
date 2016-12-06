<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/rto-agent-functions.php";
require_once "../../../../lib/rto-work-functions.php";

if(isset($_GET['id']) && isset($_GET['state'])){
$model_id=$_GET['id'];
$rto_agent_id = $_GET['state'];

$result=array();
$result=getRtoAgentWork($rto_agent_id,$model_id);
$str="";
foreach($result as $branch){
$str=$str . "\"$branch[rto_work_id]\"".",". "\"$branch[rto_work_name] - $branch[rate] Rs\"".",";
}
$str=substr($str,0,(strLen($str)-1)); // Removing the last char , from the string
echo "new Array($str)";

}

?>