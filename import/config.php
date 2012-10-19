<?php
require_once '../drupal/sites/default/civicrm.settings.php';
require_once 'CRM/Core/Config.php';
CRM_Core_Config::singleton( );

function trimString($string){
	trim($string);
	return $string;
}

function handle_errors($result,$params = NULL){
	if($result['is_error']){
		print_r($result);
		print_r($params);
	}
}

function get_gender_id($gender){
	if ($gender = "M"){
		return 2;
	}
	if ($gender = "F"){
		return 1;
	}

}

function get_employer_name($employer){
	$results = civicrm_api("Contact","get", array ('version' => '3','sequential' =>'1', 'external_identifier' => $employer));
	handle_errors($results);
	
	if($results['values']['0']['sort_name']){
		 $employer = $results['values']['0']['sort_name'];
	}
	return $employer;
}


?>