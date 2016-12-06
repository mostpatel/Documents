<?php require_once '../lib/cg.php';
require_once '../lib/lr-functions.php';
?>
<?php
$from_branch=$_GET['from_branch'];
$to_branch=$_GET['to_branch'];	
$lr_ids=getUnTrippedLrIDsFromBranchToBranch($from_branch,$to_branch);
echo count($lr_ids);
?>