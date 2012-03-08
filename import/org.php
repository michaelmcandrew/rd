<?php
require_once('config.php');
$contacts = civicrm_api('Contact', 'get', $params);
print_r($contacts);
