<?php
require __DIR__ . '/vendor/autoload.php';

use Maximprof\Doku\Api;
use Maximprof\Doku\Doku;
use Maximprof\Doku\Library;

Doku::$sharedKey = "sharedKey";
Doku::$mallId = "mallId";

$payment_env = Doku::$isProduction ? 'production' : 'staging';

$token = $_POST['doku_token'];
$pairing_code = $_POST['doku_pairing_code'];
$invoice_no = $_POST['doku_invoice_no'];
$amount = $_POST['doku_amount'];
$currency = $_POST['doku_currency'];

$params = array(
	'amount' => $amount,
	'invoice' => $invoice_no,
	'currency' => $currency,
	'pairing_code' => $pairing_code,
	'token' => $token,
);

$words = Library::createWords($params);

$basket[] = array(
	'name' => $_POST['doku_invoice_no'],
	'amount' => $_POST['doku_amount'],
	'quantity' => '1',
	'subtotal' => $_POST['doku_amount'],
);
$customer = array(
	'name' => 'TEST NAME',
	'data_phone' => '08121111111',
	'data_email' => 'test@test.com',
	'data_address' => 'bojong gede',
);
$dataPayment = array(
	'req_mall_id' => $_POST['doku_mall_id'],
	'req_chain_merchant' => 'NA',
	'req_amount' => $_POST['doku_amount'],
	'req_words' => $words,
	'req_purchase_amount' => $_POST['doku_amount'],
	'req_trans_id_merchant' => $invoice_no,
	'req_request_date_time' => date('YmdHis'),
	'req_currency' => '360',
	'req_purchase_currency' => '360',
	'req_session_id' => sha1(date('YmdHis')),
	'req_name' => $customer['name'],
	'req_payment_channel' => 15,
	'req_basket' => $basket,
	'req_email' => $customer['data_email'],
	'req_mobile_phone' => $customer['data_phone'],
	'req_token_id' => $token,
	'req_address' => $customer['data_address'],

);

$responsePayment = Api::doPayment($dataPayment);

if ($responsePayment->res_response_code == '0000') {

	//redirect process to doku
	//$responsePayment->res_redirect_url = '../example-payment/merchant-redirect-example.php';
	$responsePayment->res_redirect_url = 'http://majoramigo.xyz/?' . $responsePayment->res_response_msg;
	$responsePayment->res_show_doku_page = true; //true if you want to show doku page first before redirecting to redirect url

	//example : Response doku to merchant
	//MIPPayment.processRequest ACKNOWLEDGE : {"res_approval_code":"245391","res_trans_id_merchant":"invoice_1461728094","res_amount":"50000.00","res_payment_date_time":"20160427003515","res_verify_score":"-1","res_verify_id":"","res_verify_status":"NA","res_words":"00a22b8d81a731d948605b682578d6a9074de5c47498312cd13abd0ef2f80e7a","res_response_msg":"SUCCESS","res_mcn":"5***********8754","res_mid":"094345145394964","res_bank":"Bank BNI","res_response_code":"0000","res_session_id":"b249a07ff9c5251dddc87997d482836ea3b8affd","res_payment_channel":"15"}

	echo json_encode($responsePayment);

} else {

	echo json_encode($responsePayment);

}
?>

