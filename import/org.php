<?php
require_once('config.php');
$query_result = CRM_Core_DAO::executeQuery( 'SELECT * FROM rd_data.institution' );

/* Get the contact subtypes
 * Set up the subtypes manually on civicrm
 * http://rd.local/civicrm/admin/options/subtype?action=add&reset=1
 */
/*
while($query_result->fetch()) {
  // This gives us the contact subtypes with a count for each.
  $contact_subtypes[$query_result->managed_as_2]++;
}
print_r($contact_subtypes);
 */
while ($query_result->fetch()) {
  $contact_sub_type = $query_result->managed_as_2;
  $contact_sub_type = str_replace('&', '_', $contact_sub_type);
  $contact_sub_type = str_replace('-', '_', $contact_sub_type);

  $institution_key = $query_result->institution_key_0;
  $trust_name      = $query_result->trust_name_3;
  
  $params = array(
    'version' => '3',
    'contact_type' => 'Organization',
    'contact_sub_type' => array($contact_sub_type),
    'organization_name' => $trust_name,
    'external_identifier' => $institution_key,
  );

  if ($trust_name == '') {
    continue;
  }

  $api_result=civicrm_api("Contact","create", $params);
  if ($api_result['is_error']) {
    print_r($api_result);
    print_r($params);
  }
}

