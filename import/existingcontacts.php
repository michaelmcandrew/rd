<?php
require_once('config.php');
$query_result = CRM_Core_DAO::executeQuery( 'SELECT * FROM rd_data.peoplesplit_cleaned' );

/* DB field names

external_id_0
managed_as__2
title__4
first_name_5
middle_name_6
surname_7
suffix_8
address__line__10
address__line__11
address__line__12
address__line__13
towncity__14
countystate__15
post_codezip__16
country__17
tel_28
mobile_29
email__30
email__31
position_1of2_32
related_trustfund_33
company_34
position_2of2_36
gender_42
alumni_43
employer_46
country_programme_50
years_volunteered_51
primary_contact_55
gv_post_61
gv_email_62
network_news_63
gift_aid_83
organisation_code_85
/*   
while($query_result->fetch()) {     
// This gives us the contact subtypes with a count for each.     $contact_subtypes[$query_result->managed_as_2]++;   }   print_r($contact_subtypes);    
*/      

$prefix_translation = array(
	'Baron' => 'Baron' ,
	'Baroness' => 'Baroness' ,
	'Barroness' => 'Baroness' ,
	'Charlie' => '' ,
	'Cllr' => 'Cllr' ,
	'Commodore' => 'Commodore' ,
	'Dame' => 'Dame' ,
	'Dr' => 'Dr' ,
	'Lady' => 'Lady' ,
	'Lord' => 'Lord' ,
	'Miss' => 'Miss' ,
	'Mr' => 'Mr' ,
	'Mr`' => 'Mr' ,
	'Mrs' => 'Mrs' ,
	'Ms' => 'Ms' ,
	'Professor' => 'Professor' ,
	'Rev' => 'Rev' ,
	'Right Hon' => '' ,
	'RT Hon' => 'Rt Hon' ,
	'Rt Hon' => 'Rt Hon' ,
	'Sheik' => 'Sheik' ,
	'Sir' => 'Sir' ,
	'Sister' => 'Sister'
	);
	
$prefix2id=array(
	'Baron' => '10' ,
	'Baroness' => '9' ,
	'Charlie' => '' ,
	'Cllr' => '11' ,
	'Commodore' => '12' ,
	'Dame' => '13' ,
	'Dr' => '4' ,
	'Lady' => '6' ,
	'Lord' => '8' ,
	'Miss' => '7' ,
	'Mr' => '3' ,
	'Mrs' => '1' ,
	'Ms' => '2' ,
	'Professor' => '14' ,
	'Rev' => '15' ,
	'Rt Hon' => '16' ,
	'Sheik' => '17' ,
	'Sir' => '5' ,
	'Sister' => '18'
	);

$i=0;
while ($query_result->fetch()) {
	$i++;
	
	$external_id = trimString($query_result->external_id_0);
//	print_r(".".strlen($external_id).".");
	
	$title = trimString($query_result->title__4);
	$first_name = trimString($query_result->first_name_5);
	$middle_name = trimString($query_result->middle_name_6);
	$surname = trimString($query_result->surname_7);
	$suffix_8 = trimString($query_result->suffix_8);
	$job_title_1 = trimString($query_result->position_1of2_32);
	$job_title_2 = trimString($query_result->position_2of2_36);
	$employer_1 = trimString($query_result->organisation_code_85);
	$employer_2 = trimString($query_result->employer_46);
	$employer_3 = trimString($query_result->company_34);
	$gender = trimString($query_result->gender_42);
	$alumni = trimString($query_result->alumni_43);
	
	
	$historical_gv_post = str_replace('Y','1' , trimString($query_result->gv_post_61));
	$historical_gv_email = str_replace('Y','1' , trimString($query_result->gv_email_62));
	$historical_network_news = str_replace('Y','1' , trimString($query_result->network_news_63));
	$historical_gift_aid = str_replace('Y','1' , trimString($query_result->gift_aid_83));
	$historical_managed_as = trimString($query_result->managed_as__2);
	$historical_primary_contact = trimString($query_result->primary_contact_55);
	$historical_alumni_date = trimString($query_result->years_volunteered_51);
	$historical_alumni_country_programme = trimString($query_result->country_programme_50);

	$params = array(
		'version' => '3',
		'dupe_check' => true,
		//'debug' => '1',
		'contact_type' => 'Individual',
		'contact_sub_type' => '',
		//'external_identifier' => $external_id,
		'first_name' => $first_name,
		'middle_name' => $middle_name,
		'source' => 'Magic spreadsheet',
		'last_name' => $surname,
		'current_employer' => '',
		'job_title' => '',
		'suffix_id' => '',
		'prefix' => '',
		'prefix_id' => '',
		'gender_id' => '',
		'custom_53' => $historical_gift_aid,
		'custom_54' => $historical_gv_post,
		'custom_55' => $historical_gv_email,
		'custom_56' => $historical_network_news,
		'custom_57' => $historical_managed_as,
		'custom_58' => $historical_primary_contact,
		'custom_59' => $historical_alumni_date,
		'custom_60' => $historical_alumni_country_programme,
		'email' => ''
		);
	
	if ($alumni=="Y"){
		$params['contact_sub_type'] = "Alumni";
	}	
	
	if (!($employer_1=="")){
		$params['current_employer'] = get_employer_name($employer_1);
	}elseif	(!($employer_2=='')){
		$params['current_employer'] = get_employer_name($employer_2);
	}elseif(!($employer_3=='')){
		$params['current_employer'] = get_employer_name($employer_3);
	}
	
	if (!($job_title_1=='')){
		$params['job_title'] = $job_title_1;
	} elseif (!($job_title_2=='')){
		$params['job_title'] = $job_title_2;
	}
	
	if (!($suffix_8=="MP")){
		$params['suffix_id'] = "10";
	}	elseif (!($suffix_8=="OBE")){
		$params['suffix_id'] = "9";
	}
	
	if($title){
		$params['prefix']=str_replace('.','' , $title );
		$params['prefix']=$prefix_translation[$params['prefix']];
		$params['prefix_id']=$prefix2id[$params['prefix']];
	}

	if (!($gender=='')){
		$params['gender_id'] = get_gender_id($gender);
	}
	$phoneAndEmail = createPhoneAndEmailArray($query_result);
	$emails = $phoneAndEmail['0'];
	$phoneNumbers = $phoneAndEmail['1'];
	if($emails){
		foreach($emails as $email){
			$params = array( 
				'email' => $email,
				'version' => 3,
				//'first_name' => $first_name,
				//'surname' => $last_name,
				
			);
			$result = civicrm_api( 'contact','get',$params );
			print_r($result['count']."  ");
			$count = $count + $result['count'];
			print_r($count." ");
		}
	}
}

print_r("\n");
print_r($i);
print_r("\n");




function createPhoneAndEmailArray($query_result){
	if(!$query_result){
		return;
	}
	$fields[] = trimString($query_result->tel_28);
	$fields[] = trimString($query_result->mobile_29);
	$fields[] = trimString($query_result->email__30);
	$fields[] = trimString($query_result->email__31);
	foreach($fields as $field){
		$field = str_replace(' ', '', $field);
		$email = strpos($field, "@");
		if (!($email === false)){
			$emailArray[] = $field;
		}else{
			$field = str_replace('+', '', $field);
			$phone = is_numeric($field);
			if ($phone === true){
				$phoneArray[] = $field;
			}
		}
	}
	return array($emailArray, $phoneArray);
}
