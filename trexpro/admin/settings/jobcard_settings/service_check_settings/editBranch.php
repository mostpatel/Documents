<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/service-check-functions.php";
require_once "../../../../lib/service-check-value-functions.php";
if($_POST["branch"]=="" || $_POST["branch"]==null)
{
	$branch=getServiceCheckValueNameById($_POST["lid"]);
	echo $branch;
	}
else{
updateServiceCheckValue($_POST["lid"],$_POST["branch"]);
echo $_POST["branch"];
}

?>