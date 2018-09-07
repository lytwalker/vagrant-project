<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
// No direct access to this file
require_once 'code/ZOLPaymentsUtils.php';
require_once 'cmdbuild/listcard.php';
// Build Content
$zpu = new ZOLPaymentsUtils();

// Build Content
$zpu = new ZOLPaymentsUtils();
// Get posted fields from step1
// Validations

if (!isset($_POST['area'])) {
    $res = "[{'status':'error'}]";
}

$ref = isset($_POST['ref']) ? $_POST['ref'] : (isset($_GET['ref']) ? $zpu->HashDecode($_GET['ref']) : $zpu->GetRef("ES"));
$formatted_address = $_POST['formatted_address'];
$area = $_POST['area'];
$lat = $_POST['lat'];
$lng = $_POST['lng'];
$code = $_POST['code'];
$zone = $_POST['zone'];
$tech = $_POST['tech'];

?>
