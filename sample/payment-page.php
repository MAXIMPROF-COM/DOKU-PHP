<?php
require __DIR__ . '/vendor/autoload.php';

use Maximprof\Doku\Doku;
use Maximprof\Doku\Library;

Doku::$sharedKey = "sharedKey";
Doku::$mallId = "mallId";

$currency = '360';
$invoice = 'invoice' . rand(1, 100);
$amount = '10000.00';

$params = array(
	'amount' => $amount,
	'invoice' => $invoice,
	'currency' => $currency,
);
$words = Library::createWords($params);
$payment_env = Doku::$isProduction ? 'production' : 'staging';

?>

<!DOCTYPE html>
<html>
<head>
  <title>DOKU Payment Gateway</title>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.pack.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" rel="stylesheet">

  <?php if ($payment_env == 'production') {?>
    <script src="https://staging.doku.com/doku-js/assets/js/doku-1.2.js"></script>
    <link href="https://staging.doku.com/doku-js/assets/css/doku.css" rel="stylesheet">
  <?php } else {?>
    <script src="https://pay.doku.com/doku-js/assets/js/doku-1.2.js"></script>
    <link href="https://pay.doku.com/doku-js/assets/css/doku.css" rel="stylesheet">
  <?php }?>
</head>
<body>

<form action="" method="POST" id="payment-form">
  <div doku-div='form-payment'>
    <input id="doku-token" name="doku-token" type="hidden" />
    <input id=" doku-pairing-code" name="doku-pairing-code" type="hidden" />
  </div>
</form>

<script type="text/javascript">
$(function() {
 var data = new Object();
 data.req_merchant_code = '<?php echo $merchant_code; ?>';
 data.req_chain_merchant = 'NA';
 data.req_payment_channel = '15'; // ‘15’ = credit card
 data.req_transaction_id = '<?php echo $invoice; ?>';
 data.req_currency = '<?php echo $currency; ?>';
 data.req_amount = '<?php echo $amount; ?>';
 data.req_words = '<?php echo $words; ?>';
 data.req_form_type = 'full';
 data.req_server_url = 'charge.php'
getForm(data, '<?php echo $payment_env; ?>');
});
</script>

</body>
</html>
