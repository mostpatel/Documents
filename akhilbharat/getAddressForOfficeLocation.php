<?php
require_once("admin/lib/cg.php");
require_once("admin/lib/bd.php");
require_once("admin/lib/common.php");
require_once("admin/lib/office-addresses-functions.php");

$id = $_GET['id'];

$address_info = listAddressForLocationId($id);
$address_info = $address_info[0];
echo json_encode($address_info);
?>