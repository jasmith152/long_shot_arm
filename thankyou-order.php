<?php
// Get the configuration file
require 'cart_config.php';

// Check for and set variables
if (!empty($_GET['msg'])) { $msg = $_GET['msg']; }
if (!empty($_POST['msg'])) { $msg = $_POST['msg']; }
if (!empty($_GET['error'])) { $error = $_GET['error']; }
if (!empty($_POST['error'])) { $error = $_POST['error']; }
if (!empty($_GET['order'])) { $order_num = $_GET['order']; }
if (!empty($_GET['order_status'])) { $order_status = $_GET['order_status']; }
if (!empty($_GET['email'])) { $email = $_GET['email']; }

// Display confirmation page
$page_title = $store_name." - Thank You";
// Include site header
include $inc_header_file;

/* Debugging Info */
if ($debugging == 'Y') {
   echo "<div class='debugging'>\n";
   echo "session: $PHPSESSID<br />\n";
   echo "SESSION['UNAME']: ".$_SESSION['UNAME']."<br />\n";
   echo "email: $email<br />\n";
   echo "order_num: $order_num<br />\n";
   echo "order_status: $order_status<br />\n";
   echo "</div>\n";
}

if ($order_status == "Cancelled") {
   echo "<p><strong>Uh Oh!</strong> It looks like there was a problem with the payment information.</p>\n";
   echo "<p>Please contact us right away for assistance to complete your order (Order# $order_num). Or return to the <a href='view_cart.php'>shopping cart</a> to try again.</p>\n";
} elseif ($order_status == 'Payment Received') {
   echo "<p><strong>Thank You</strong> for your order.</p>\n";
   echo "<p><strong>Your Order # is: $order_num.</strong> You should receive an email detailing this order at the email address that you provided. Please be sure to keep that email until you have received all of the items from your order.</p>\n";
   echo "<p>Please feel free to call us to get updated on the status or shipping progress for your order.</p>\n";
}

// Display messages for user
if (!empty($msg)) {
   echo "<p class='msg'>$msg</p>\n";
}
if (!empty($error)) {
   echo "<p class='error'>$error</p>\n";
}

// Include site footer
include $inc_footer_file;
?>
