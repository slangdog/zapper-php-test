<?php

	$response = array("payments" => 0, "total" => $total);

	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		$url = 'https://zapapi.zapzap.mobi/zapperpointofsale/api/payments/GetMultiplePaymentStatusByPosReferences?merchantsiteId=$site_id&posreference='.$id;

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
		$response = curl_exec($curl);
		$data = json_decode($response);

		$total = 0;

		foreach($data->data[0]->Payments as $payment) {
			foreach($payment->PaymentItems as $entry) {
				if ($entry->PaymentStatus != 'Failed') {
					$total += floatval($entry->AmountPaid);
				}
			}
		}

		$response['payments'] = count($data->data[0]->Payments);
		$response['total'] = $total;
	}

	header('Content-Type: application/json');
	echo json_encode($response);

?>
