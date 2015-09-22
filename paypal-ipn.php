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

if ($order_status != "Cancelled") {

// Add order to the Orders table as a new order
$sql1 = "SELECT customer.*,cart.*,product.id,product.item_num,product.upc,product.title ";
$sql1 .= "FROM tbl_customers AS customer, tbl_orders_temp AS cart, tbl_products AS product WHERE cart.email='$email' AND customer.email=cart.email AND product.id=cart.product_id";
$result1 = mysql_query($sql1);
if (!$result1) {
   echo "Error performing query: " . mysql_error();
   exit();
}

while ($row1 = mysql_fetch_array($result1)) {
   $sql2 = "INSERT INTO tbl_orders SET 
         order_date='".date("Y-m-d")."', 
         order_num='$order_num', 
         customer_id='".$row1['id']."', 
         product_id='".$row1['product_id']."', 
         item_num='".$row1['item_num']."', 
         title='".$row1['title']."', 
         option1='".$row1['option1']."', 
         option2='".$row1['option2']."', 
         option3='".$row1['option3']."', 
         option4='".$row1['option4']."', 
         qty='".$row1['qty']."', 
         price='".$row1['price']."', 
         status='".$order_status."', 
         lname='".$row1['lname']."', 
         fname='".$row1['fname']."', 
         bill_address1='".$row1['bill_address1']."', 
         bill_address2='".$row1['bill_address2']."', 
         bill_city='".$row1['bill_city']."', 
         bill_state='".$row1['bill_state']."', 
         bill_zip='".$row1['bill_zip']."', 
         bill_country='".$row1['bill_country']."', 
         ship_address1='".$row1['ship_address1']."', 
         ship_address2='".$row1['ship_address2']."', 
         ship_city='".$row1['ship_city']."', 
         ship_state='".$row1['ship_state']."', 
         ship_zip='".$row1['ship_zip']."', 
         ship_country='".$row1['ship_country']."', 
         ship_name='".$row1['ship_name']."', 
         request_arrival_date='".$row1['request_arrival_date']."', 
         order_comment='".$row1['order_comment']."', 
         shipping_type='".$row1['shipping_type']."', 
         tax='".$_SESSION['tax']."', 
         shipping='".$_SESSION['shipping']."', 
         email='".$row1['email']."', 
         day_phone='".$row1['day_phone']."', 
         evening_phone='".$row1['evening_phone']."', 
         fax='".$row1['fax']."'";
   if (!mysql_query($sql2)) {
      $error = "There was a problem processing your order, please <a href='view_cart.php'>go back</a> to your shopping cart and try placing the order again.<br />Thank you!<br />\n";
   }
}

/* Delete items from shopping cart */
$sql_del = "DELETE FROM tbl_orders_temp WHERE email='$email'";
if (!mysql_query($sql_del)) {
   $error = "There was a problem removing these items from your shopping cart, please wait to speak to a representative before updating your shopping cart.<br />Thank you!<br />\n";
}

/* Send out the email receipts */
   $sql = "SELECT * FROM tbl_orders WHERE email = '$email' AND order_num = '$order_num'";
   $result = mysql_query($sql);
   if (!$result) {
      echo "Error performing query: " . mysql_error();
      exit();
   }
   $items = 0;
   $subtotal = 0;
   $total = 0;
   // Start creating text for email
   $text = "<font face='Arial,Helvetica,sans-serif' size='2'>Order #: $order_num  Date: ".date("Y-m-d")."<br />";
   $text_plain = "Order #: $order_num  Date: ".date("Y-m-d")."<br />";
   while ($row = mysql_fetch_array($result)) {
      $items++;
      //Continue text for email
      if ($items <= 1) {
         if (!empty($row['affiliate'])) {
            $affiliate = $row['affiliate'];
            $text .= "<b>Affiliate:</b> $affiliate<br />";
            $text_plain .= "Affiliate: $affiliate<br />";
         }
         $text .= "<font size='3'><b>Customer Info:</b></font><br />";
         $text_plain .= "Customer Info:<br />";
         $text .= $row['fname']." ".$row['lname']."<br />";
         $text_plain .= $row['fname']." ".$row['lname']."<br />";
         $customer_name = $row['fname']." ".$row['lname'];
         $ph_search_arr = array("(",")","-");
         $ph_replace_arr = array("","","");
         if (!empty($row['day_phone'])) {
            $day_phone = str_replace($ph_search_arr,$ph_replace_arr,$row['day_phone']);
            $text .= "Day Phone: (".substr($day_phone,0,3).") ".substr($day_phone,3,3)."-".substr($day_phone,6,4)."<br />";
            $text_plain .= "Day Phone: (".substr($day_phone,0,3).") ".substr($day_phone,3,3)."-".substr($day_phone,6,4)."<br />";
         }
         if (!empty($row['evening_phone'])) {
            $evening_phone = str_replace($ph_search_arr,$ph_replace_arr,$row['evening_phone']);
            $text .= "Evening Phone: (".substr($evening_phone,0,3).") ".substr($evening_phone,3,3)."-".substr($evening_phone,6,4)."<br />";
            $text_plain .= "Evening Phone: (".substr($evening_phone,0,3).") ".substr($evening_phone,3,3)."-".substr($evening_phone,6,4)."<br />";
         }
         if (!empty($row['fax'])) {
            $fax = str_replace($ph_search_arr,$ph_replace_arr,$row['fax']);
            $text .= "Fax: (".substr($fax,0,3).") ".substr($fax,3,3)."-".substr($fax,6,4)."<br />";
            $text_plain .= "Fax: (".substr($fax,0,3).") ".substr($fax,3,3)."-".substr($fax,6,4)."<br />";
         }
         if (!empty($row['email'])) {
            $text .= "Email: ".$row['email']."<br />";
            $text_plain .= "Email: ".$row['email']."<br />";
            $customer_email = $row['email'];
         }
         $text .= "<table border='0' cellpadding='4' cellspacing='0'><tr>";
         $text_plain .= "<br />";
         $text .= "<td><font size='2'><b>Billing Address:</b><br />";
         $text_plain .= "Billing Address:\r\n";
         $text .= $row['bill_address1']."<br />";
         $text_plain .= $row['bill_address1']."\r\n";
         if (!empty($row['bill_address2'])) {
            $text .= $row['bill_address2']."<br />";
            $text_plain .= $row['bill_address2']."\r\n";
         }
         $text .= $row['bill_city'].", ".$row['bill_state']." ".$row['bill_zip']."<br />".$row['bill_country']."</font></td>";
         $text_plain .= $row['bill_city'].", ".$row['bill_state']." ".$row['bill_zip']." ".$row['bill_country']."\r\n\r\n";
         $text .= "<td><font size='2'><b>Shipping Address:</b><br />";
         $text_plain .= "Shipping Address:\n";
         $text .= $row['ship_name']."<br />";
         $text_plain .= $row['ship_name']."\n";
         $text .= $row['ship_address1']."<br />";
         $text_plain .= $row['ship_address1']."\n";
         if (!empty($row['ship_address2'])) {
            $text .= $row['ship_address2']."<br />";
            $text_plain .= $row['ship_address2']."\n";
         }
         $text .= $row['ship_city'].", ".$row['ship_state']." ".$row['ship_zip']."<br />".$row['ship_country']."</font></td>";
         $text_plain .= $row['ship_city'].", ".$row['ship_state']." ".$row['ship_zip']." ".$row['ship_country']."\n\n";
         $ship_state = $row['ship_state'];
         $ship_country = $row['ship_country'];
         $text .= "</tr></table>";
         $text .= "<font size='3'><b>Order Details:</b></font><br />";
         $text_plain .= "Order Details:\n";
         $text .= "<table border='0' cellpadding='2' cellspacing='0'>";
         $text .= "<tr><td><font size='2'>Item No.</font></td><td><font size='2'>Product Name</font></td><td><font size='2'>Price Ea.</font></td><td><font size='2'>Qty</font></td><td><font size='2'>Price Ext.</font></td></tr>";
         $text_plain .= "Item No., Product Name, Price Ea., Qty, Price Ext.\n";
         $request_arrival_date = $row['request_arrival_date'];
         $greeting = $row['greeting'];
      }
      
      $text .= "<tr><td><font size='2'>".$row['item_num']."</font></td>";
      $text .= "<td><font size='2'>".$row['title']."</font></td>";
      $text .= "<td><font size='2'>$".number_format($row['price'],2)."</font></td>";
      $text .= "<td><font size='2'>".$row['qty']."</font></td>";
      $text .= "<td><font size='2'>$".number_format($row['price']*$row['qty'],2)."</font></td></tr>";
      $text_plain .= $row['item_num'].", ".$row['title'].", ".number_format($row['price'],2).", ".$row['qty'].", ".number_format($row['price']*$row['qty'],2)."\n";
      if (!empty($row['option1'])) {
         $text .= "<tr><td colspan='5'><font size='2'>Options selected: ".$row['option1'];
         $text_plain .= "Options selected: ".$row['option1'];
         if (!empty($row['option2'])) {
            $text .= ", ".$row['option2'];
            $text_plain .= ", ".$row['option2'];
         }
         if (!empty($row['option3'])) {
            $text .= ", ".$row['option3'];
            $text_plain .= ", ".$row['option3'];
         }
         if (!empty($row['option4'])) {
            $text .= ", ".$row['option4'];
            $text_plain .= ", ".$row['option4'];
         }
         $text .= "</font></td></tr>";
         $text_plain .= "\n";
      }
      $subtotal = $subtotal + ($row['price']*$row['qty']);
      $tax = $row['tax'];
      $shipping = $row['shipping'];
      $shipping_type = $row['shipping_type'];
      $order_comment = $row['order_comment'];
      $request_arrival_date = $row['request_arrival_date'];
   }
   $text .= "</table><br />";
   $text_plain .= "\n";
   $text .= "This order has $items item(s) with a <b>Subtotal</b> of $".number_format($subtotal,2).".<br />";
   $text_plain .= "This order has $items item(s) with a Subtotal of $".number_format($subtotal,2).".\n";

   if (isset($tax) && !empty($tax)) {
      $text .= "<b>Tax:</b> $".number_format($tax,2).".<br />";
      $text_plain .= "Tax: $".number_format($tax,2).".\n";
   }
   if (isset($shipping) && !empty($shipping)) {
      $text .= "<b>Shipping:</b> $".number_format($shipping,2).".<br />";
      $text_plain .= "Shipping: $".number_format($shipping,2).".\n";
   }
   $text .= "<b>Total:</b> $".number_format($subtotal+$tax+$shipping,2).".<br />";
   $text_plain .= "Total: $".number_format($subtotal+$tax+$shipping,2).".\n";
   $text .= "<b>Order Comment:</b> $order_comment<br />";
   $text_plain .= "Order Comment: $order_comment\n";
   $text .= "<b>Requested Arrival Date:</b> $request_arrival_date<br />";
   $text_plain .= "Requested Arrival Date: $request_arrival_date\n";
   $text .= "<br /></font>";
   $text_plain .= "\n";
   
   // Send email to notify employees of the new order
   $to_email = $order_email;
   $to_name = $order_email_name;
   $to = "$to_name <$to_email>";
   //$to = "cwebb@mychurchserver.com"; //For testing only
   if (!empty($_SESSION['affiliate'])) {
      $to .= ",$affiliate_email";
   }
   $subject = "$store_name Online Order #$order_num just received.";
   $from = "Checkout System <$to_email>";
   $headers = "From: $from\n";
   $headers .= "Reply-To: $from\n";
   $headers .= "Return-Path: $from\n";
   $headers .= "Message-ID: <".time()." Postmaster@".$_SERVER['SERVER_NAME'].">\n";
   $headers .= "MIME-Version: 1.0\n";
   $headers .= "Content-type: text/html; charset=\"iso-8859-1\"\n";
   $headers .= "X-Mailer: PHP v".phpversion()."\n";
   if (!mail("$to","$subject","$text","$headers")) {
      // There was an error, but we have already added the order to the table
      $error = "There was a problem notifying our representative of your order, please call us right away to complete your order.<br />Thank you!<br />\n";
   }
   /* Send order info to customer */
   $to_customer = $customer_email;
   $subject_customer = "$store_name Online Order #$order_num - Thank You";
   $text_customer = "<p><font face='Arial,Helvetica,sans-serif' size='2'>This is an automatic reply confirming the items in your order and amount due. If there is a problem with this order, you may reply to this email with any other information you want $store_name to have. Thank you for your business.</font></p>\n";
   $text_customer .= $text;
   if (!mail("$to_customer","$subject_customer","$text_customer","$headers")) {
      // There was an error, but we have already added the order to the table
      $error = "There was a problem sending you an email detailing your order, but your order is complete.<br />Thank you!<br />\n";
   }
}
/* Debugging Info */
if ($debugging == 'Y') {
   echo "<div class='debugging'>\n";
   echo "session: $PHPSESSID<br />\n";
   echo "email/SESSION['UNAME']: ".$_SESSION['UNAME']."<br />\n";
   echo "order_num: $order_num<br />\n";
   echo "sql1: $sql1<br />\n";
   echo "sql2: $sql2<br />\n";
   echo "customer.id: ".$row1['id']."<br />\n";
   echo "row1[0]: ".$row1[0]."<br />\n";
   echo "error: $error<br />\n";
   echo "order_confirmed: $order_confirmed<br />\n";
   echo "</div>\n";
}

// Free any results & close Db connection
if (!empty($result1)) {
   mysql_free_result($result1);
}
mysql_close($dbcnx);
?>
