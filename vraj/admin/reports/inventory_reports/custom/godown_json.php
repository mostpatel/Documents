<?php require_once '../../../../lib/cg.php';
require_once '../../../../lib/bd.php';
require_once '../../../../lib/common.php';
require_once '../../../../lib/godown-functions.php';

$sql="SELECT godown_id,godown_name FROM edms_godown";
$result = dbQuery($sql);
$resultarray = dbResultToArray($result);
echo json_encode($resultarray);  // send data as json format
?>