<?php
/* PayPal payment gateway module */
/* Configuration */
$paypal_email = 'longshotarmsllc@gmail.com';
$paypal_image_url = 'logo-for-paypal.jpg';

/* Start form to send info to PayPal */
//$form_text = "<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post'>\n";
$form_text = "<form action='https://www.paypal.com/cgi-bin/webscr' method='post'>\n";
$form_text .= " <input type='hidden' name='cmd' value='_cart' />\n";
$form_text .= " <input type='hidden' name='upload' value='1' />\n";
//$form_text .= " <input type='hidden' name='business' value='chris_1221683232_biz@naturecoastdesign.net' />\n";
$form_text .= " <input type='hidden' name='business' value='$paypal_email' />\n";
if (isset($_SESSION['affiliate']) && !empty($_SESSION['affiliate'])) {
   $form_text .= " <input type='hidden' name='custom' value='Affiliate: ".$_SESSION['affiliate']."' />\n";
}
$form_text .= "<input type='hidden' name='notify_url' value='http://".$site_root."paypal-ipn.php?email=".$_SESSION['UNAME']."&order=$order_num&order_status=Payment%20Received' />\n";
$form_text .= "<input type='hidden' name='return' value='http://".$site_root."thankyou-order.php?email=".$_SESSION['UNAME']."&order=$order_num&order_status=Payment%20Received' />\n";
$form_text .= "<input type='hidden' name='cancel_return' value='http://".$site_root."thankyou-order.php?email=".$_SESSION['UNAME']."&order=$order_num&order_status=Cancelled' />\n";
$form_text .= "<input type='hidden' name='image_url' value='http://".$site_root."images/".$paypal_image_url."' />\n";
?>
