<?php
	// Main configuration
	$merchange_id 	= 817;			// Your Zapper MerchantID
	$site_id	= 463;			// Your Zapper SiteID
	$bill_amount	= 0.01;			// The amount being charged
	$default_tip	= '12.5%'; 		// Accepted values: '12.5%', '10%', '0%', false
	$split_bill	= true;			// Show or hide the 'split bill' option
	$bill_reference	= 'Order 1';	// Bill reference for for accounting reference
	$unique_ref	= 'aLz6CvDeOP';	// Unique string generated by the POS, helpful when polling Zapper API for payment response

	// Optional configuration
	$currency_iso	= 'ZAR';		// Currency ISO code
	$image_width	= '250';		// Image width, currently only supported value

	// End of configuration

	$url = "https://zapapi.zapzap.mobi/zappertech/api/generatecode?merchantid=$merchange_id&siteid=$site_id&taskid=6";

	$questions = array();
	$questions[] = array(
		'QuestionId' => 34,
		'GroupId' => 7,
		'Answer' => $bill_amount,
		'Required' => true,
		'Type' => 3
	);

	switch($default_tip) {
		case '12.5%':
			$questions[] = array(
				'QuestionId' => 40,
				'GroupId' => 7,
				'Required' => false,
				'Type' => 13
			);
			break;
		case '10%':
			$questions[] = array(
				'QuestionId' => 40,
				'GroupId' => 7,
				'Answer' => 278,
				'Required' => false,
				'Type' => 13
			);
			break;
		case '0%':
			$questions[] = array(
				'QuestionId' => 40,
				'GroupId' => 7,
				'Answer' => 298,
				'Required' => false,
				'Type' => 13
			);
			break;
		default:
			break;
	}
	
	if ($split_bill) {
		$questions[] = array(
			'QuestionId' => 63,
			'GroupId' => 7,
			'Required' => false,
			'Type' => 18
		);
	}
	
	$questions[] = array(
		'QuestionId' => 33,
		'GroupId' => 7,
		'Answer' => $bill_reference,
		'Required' => false,
		'Type' => 10
	);

	$questions[] = array(
		'QuestionId' => 66,
		'GroupId' => 7,
		'Answer' => $unique_ref,
		'Required' => true,
		'Type' => 10
	);

	$questions[] = array(
		'QuestionId' => 39,
		'GroupId' => 10,
		'Answer' => $currency_iso,
		'Required' => true
	);

	$properties = array('SizeInPixels' => $image_width);

	$data = array("Questions" => $questions, "QrProperties" => $properties);
	$content = json_encode($data);

	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
	$response = curl_exec($curl);

	header('Content-Type: image/png');
	echo ($response);
?>
