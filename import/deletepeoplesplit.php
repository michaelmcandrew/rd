<?php
require_once('config.php');

$results = CRM_Core_DAO::executeQuery( 'SELECT * FROM rd_civicrm.civicrm_contact WHERE id > 3856' );


while($results->fetch()){
	$ids[]=$results->id;
}

foreach($ids as $id){
	$params=array('id' => $id, 'skip_undelete' => true, 'version'=>3, 'contact_type' => "Individual");
	$contact_delete=civicrm_api('Contact', 'delete', $params);
	print_r("D ".$id."D ");
}