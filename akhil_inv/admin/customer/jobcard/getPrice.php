<?php

require_once "../../lib/cg.php";
require_once "../../lib/bd.php";
require_once "../../lib/common.php";
require_once "../../lib/sub-category-functions.php";

$q = intval($_GET['q']);

 $subCatDetails = getsubCategoryById($q);
 
 $price = $subCatDetails['subCategory_price'];
trim($price);
echo $price;
?>