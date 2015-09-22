<?php
// Get the configuration file
require 'cart_config.php';

// Check for and set variables
if (!empty($_GET['msg'])) { $msg = $_GET['msg']; }
if (!empty($_POST['msg'])) { $msg = $_POST['msg']; }
if (!empty($_GET['error'])) { $error = $_GET['error']; }
if (!empty($_POST['error'])) { $error = $_POST['error']; }

// Require the customer's information
require 'login.php';

// Get order details from shopping cart
$sql1 = "SELECT customer.*,cart.session,cart.session_date,cart.email,cart.product_id,cart.option1,cart.option2,cart.option3,cart.option4,cart.qty,cart.price,cart.request_arrival_date,cart.order_comment,product.id,product.item_num,product.upc,product.title ";
$sql1 .= "FROM tbl_customers AS customer, tbl_orders_temp AS cart, tbl_products AS product WHERE cart.email='".$_SESSION['UNAME']."' AND customer.email=cart.email AND product.id=cart.product_id";
$result1 = mysql_query($sql1);
if (!$result1) {
   echo "Error performing query: " . mysql_error();
   exit();
}
// Get the order number
$sql_order_num = "SELECT MAX(order_num) FROM tbl_orders";
$result_order_num = mysql_query($sql_order_num);
if (!$result_order_num) {
   echo "Error performing query: " . mysql_error();
   exit();
}
$row_order_num = mysql_fetch_array($result_order_num);
$order_num = $row_order_num['0']+1;

if (!empty($payment_gateway_module)) {
   include $payment_gateway_module;
}

// Begin page and form text output
$text = "<div id='checkout_cust_info'>Order #: $order_num  Date: ".date("m-d-Y")."<br />";
if (!empty($_SESSION['affiliate'])) {
   $text .= "<span class='heading'>Affiliate:</span> ".$_SESSION['affiliate']."<br />";
}
$subtotal = 0;
$tax = 0;
$total_wt = 0;
$items = 0;
$ship_items = 0;
$wine_qty = 0;
$other_qty = 0;
$shipping = 0;
$total = 0;
while ($row1 = mysql_fetch_array($result1)) {
   $items++;
   if ($items <= 1) {
      $text .= "<span class='heading'>Customer Info:</span><br />";
      $customer_name = $row1['fname']." ".$row1['lname'];
      $text .= $customer_name."<br />";
      $form_text .= " <input type='hidden' name='first_name' value='".$row1['fname']."' />\n";
      $form_text .= " <input type='hidden' name='last_name' value='".$row1['lname']."' />\n";
      $ph_search_arr = array("(",")","-");
      $ph_replace_arr = array("","","");
      if (!empty($row1['day_phone'])) {
         $day_phone = str_replace($ph_search_arr,$ph_replace_arr,$row1['day_phone']);
         $text .= "Day Phone: (".substr($day_phone,0,3).") ".substr($day_phone,3,3)."-".substr($day_phone,6,4)."<br />";
         $form_text .= " <input type='hidden' name='phone_a' value='".substr($day_phone,0,3)."' />\n";
         $form_text .= " <input type='hidden' name='phone_b' value='".substr($day_phone,3,3)."' />\n";
         $form_text .= " <input type='hidden' name='phone_c' value='".substr($day_phone,6,4)."' />\n";
      }
      if (!empty($row1['evening_phone'])) {
         $evening_phone = str_replace($ph_search_arr,$ph_replace_arr,$row1['evening_phone']);
         $text .= "Evening Phone: (".substr($evening_phone,0,3).") ".substr($evening_phone,3,3)."-".substr($evening_phone,6,4)."<br />";
         $form_text .= " <input type='hidden' name='night_phone_a' value='".substr($evening_phone,0,3)."' />\n";
         $form_text .= " <input type='hidden' name='night_phone_b' value='".substr($evening_phone,3,3)."' />\n";
         $form_text .= " <input type='hidden' name='night_phone_c' value='".substr($evening_phone,6,4)."' />\n";
      }
      if (!empty($row1['fax'])) {
         $fax = str_replace($ph_search_arr,$ph_replace_arr,$row1['fax']);
         $text .= "Fax: (".substr($fax,0,3).") ".substr($fax,3,3)."-".substr($fax,6,4)."<br />";
      }
      if (!empty($row1['email'])) {
         $text .= "Email: ".$row1['email']."<br />";
         $cart_email = $row1['email'];
      }
      $text .= "</div>\n";
      $text .= "<table id='checkout_cust_address' border='0' cellpadding='0' cellspacing='0'><tr>";
      $text .= "<td valign='top'><span class='heading'>Billing Address:</span><br />";
      $text .= $row1['bill_address1']."<br />";
      $form_text .= " <input type='hidden' name='address1' value='".$row1['bill_address1']."' />\n";
      if (!empty($row1['bill_address2'])) {
      	 $text .= $row1['bill_address2']."<br />";
         $form_text .= " <input type='hidden' name='address2' value='".$row1['bill_address2']."' />\n";
      }
      $text .= $row1['bill_city'].", ".$row1['bill_state']." ".$row1['bill_zip']."<br />".$row1['bill_country']."</td>";
      $form_text .= " <input type='hidden' name='city' value='".$row1['bill_city']."' />\n";
      $form_text .= " <input type='hidden' name='state' value='".$row1['bill_state']."' />\n";
      $form_text .= " <input type='hidden' name='zip' value='".$row1['bill_zip']."' />\n";
      $text .= "<td valign='top'><span class='heading'>Shipping Address:</span><br />";
      $text .= $row1['ship_name']."<br />";
      $text .= $row1['ship_address1']."<br />";
      if (!empty($row1['ship_address2'])) {
      	 $text .= $row1['ship_address2']."<br />";
      }
      $text .= $row1['ship_city'].", ".$row1['ship_state']." ".$row1['ship_zip']."<br />".$row1['ship_country']."</td>";
      $text .= "</tr></table>";
      $text .= "<p id='order_details_heading'>Order Details:</p>";
      $text .= "<table id='order_details' border='0' cellpadding='2' cellspacing='0'>";
      $text .= " <tr>";
      if (strstr($cart_fields,"item_num")) {
         $text .= "  <td><strong>Item No.</strong></td>";
      }
      if (strstr($cart_fields,"upc")) {
         $text .= "  <td><strong>UPC</strong></td>";
      }
      $text .= "  <td><strong>Description</strong></td>";
      if (strstr($cart_fields,"weight")) {
         $text .= "  <td><strong>Weight</strong></td>";
      }
      $text .= "  <td><strong>Price Ea.</strong></td>";
      $text .= "  <td><strong>Qty</strong></td>";
      $text .= "  <td><strong>Price Ext.</strong></td>";
      $text .= " </tr>";
      $request_arrival_date = $row1['request_arrival_date'];
      $order_comment = $row1['order_comment'];
   }
   $text .= "<tr>";
   if (strstr($cart_fields,"item_num")) {
      $text .= "<td>".$row1['item_num']."</td>";
      $form_text .= " <input type='hidden' name='item_number_".$items."' value='".$row1['item_num']."' />\n";
   }
   if (strstr($cart_fields,"upc")) {
      $text .= "<td>".$row1['upc']."</td>";
   }
   $text .= "<td>".$row1['title']."</td>";
   $form_text .= " <input type='hidden' name='item_name_".$items."' value='".$row1['title']."' />\n";
   if (strstr($cart_fields,"weight")) {
      $text .= "  <td>".$row1['weight']."</td>";
   }
   $text .= "<td>$".number_format($row1['price'],2)."</td>";
   $form_text .= " <input type='hidden' name='amount_".$items."' value='".number_format($row1['price'],2)."' />\n";
   $text .= "<td>".$row1['qty']."</td>";
   $form_text .= " <input type='hidden' name='quantity_".$items."' value='".$row1['qty']."' />\n";
   $text .= "<td>$".number_format($row1['price']*$row1['qty'],2)."</td></tr>";
   if (!empty($row1['option1'])) {
      $text .= "<tr><td colspan='5'>Options selected: ".$row1['option1'];
      $form_text .= " <input type='hidden' name='on0_".$items."' value='Option 1' />\n";
      $form_text .= " <input type='hidden' name='os0_".$items."' value='".$row1['option1']."' />\n";
      if (!empty($row1['option2'])) {
      	 $text .= ", ".$row1['option2'];
         $form_text .= " <input type='hidden' name='on1_".$items."' value='Option 2' />\n";
         $form_text .= " <input type='hidden' name='os1_".$items."' value='".$row1['option2']."' />\n";
      }
      if (!empty($row1['option3'])) {
      	 $text .= ", ".$row1['option3'];
         $form_text .= " <input type='hidden' name='on2_".$items."' value='Option 3' />\n";
         $form_text .= " <input type='hidden' name='os2_".$items."' value='".$row1['option3']."' />\n";
      }
      if (!empty($row1['option4'])) {
      	 $text .= ", ".$row1['option4'];
         $form_text .= " <input type='hidden' name='on3_".$items."' value='Option 4' />\n";
         $form_text .= " <input type='hidden' name='os3_".$items."' value='".$row1['option4']."' />\n";
      }
      $text .= "</td></tr>";
   }
   $subtotal = $subtotal + ($row1['price']*$row1['qty']);
   $total_wt = $total_wt + ($row1['weight']*$row1['qty']);
   $ship_items = $ship_items + $row1['qty'];
   if ($row1['item_num'] > 499 && $row1['item_num'] < 600) {
      $wine_qty = $wine_qty + $row1['qty'];
   } else {
      $other_qty++;
   }
}
$text .= "</table><br />";
$text .= "<p>This order has $items item(s) with a <strong>Subtotal</strong> of $".number_format($subtotal,2).".<br />";
if (strstr($cart_fields,"weight")) {
   echo " <strong>Total Weight:</strong> ".$total_wt."<br />\n";
}
/* Get the Tax module & recalculate in case customer info has been updated */
if (!empty($tax_module)) {
   include $tax_module;
}
if (isset($_SESSION['tax']) && !empty($_SESSION['tax'])) {
   $text .= "<strong>Tax:</strong> $".number_format($_SESSION['tax'],2).".<br />";
   $form_text .= " <input type='hidden' name='tax_cart' value='".number_format($_SESSION['tax'],2)."' />\n";
}

/* Get the Shipping module & recalculate in case customer info has been updated */
if (!empty($shipping_module)) {
   include $shipping_module;
}
if (isset($_SESSION['shipping']) && !empty($_SESSION['shipping'])) {
   $text .= "<strong>Shipping:</strong> $".number_format($_SESSION['shipping'],2).".<br />";
   $form_text .= " <input type='hidden' name='handling_cart' value='".number_format($_SESSION['shipping'],2)."' />\n";
}

$total = $subtotal+$_SESSION['shipping']+$_SESSION['tax'];
$text .= "<strong>Total:</strong> $".number_format($total,2).".<br />";
if ($show_req_arrival_date == 'Y') {
   $text .= "<strong>Requested Arrival Date:</strong> $request_arrival_date<br />";
}
if ($show_order_comment == 'Y') {
   $text .= "<strong>Comment:</strong> $order_comment<br />";
}
$text .= "<br /></p>";

/* Debugging Info */
if ($debugging == 'Y') {
   echo "<div class='debugging'>\n";
   echo "PHPSESSID: ".$PHPSESSID."<br />\n";
   echo "email/SESSION['UNAME']: ".$_SESSION['UNAME']."<br />\n";
   echo "order_num: $order_num<br />\n";
   echo "sql1: $sql1<br />\n";
   echo "customer.id: ".$row1['id']."<br />\n";
   echo "row1[0]: ".$row1[0]."<br />\n";
   echo "error: $error<br />\n";
   echo "order_confirmed: $order_confirmed<br />\n";
   echo "</div>\n";
}

// Check for errors
if (!empty($error)) {
   echo "There was a problem processing your order, please <a href='view_cart.php'>go back</a> to your shopping cart and try placing the order again.<br />Thank you!<br />\n";
   echo $error;
} else {
      // Display confirmation page
      $page_title = $store_name." - Order Confirmation";
      // Include site header
      include $inc_header_file;
      echo "<p class='error'><strong>Please review the information below for accuracy before placing your order.</strong></p>\n";
      
      if (!empty($_SESSION['affiliate'])) {
         $to .= ",".$affiliate_email;
      }
      $form_text .= "<p class='heading'>Your order will not be complete until you click the button below.<br />\n";
      $form_text .= "<p>Please proceed to $payment_gateway_name to complete payment information.</p>\n";
      $form_text .= "  <input type='submit' name='submit' value='Place Order' /></p>";
      $form_text .= "</form>\n";
      echo $text;
      echo $form_text;
      echo "<p>If the order or customer information above is not accurate, please return to the shopping cart to make the necessary changes. <br /><a href='view_cart.php'><< Back to the Shopping Cart</a>\n";
      // Include site footer
      include $inc_footer_file;
}

// Free any results & close Db connection
if (!empty($result1)) {
   mysql_free_result($result1);
}
if (!empty($result_order_num)) {
   mysql_free_result($result_order_num);
}
mysql_close($dbcnx);
?>
