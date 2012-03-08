<?php
require_once('config.php');
$result = CRM_Core_DAO::executeQuery( 'SELECT * FROM rd_data.institution' );
while($result->fetch()){
	print_r($result);
}
