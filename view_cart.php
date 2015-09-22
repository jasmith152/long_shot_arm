<?php
// Get the configuration file
require 'cart_config.php';

// Check for and set variables
/*session_register("cat");*/
if (!empty($_GET['msg'])) { $msg = $_GET['msg']; }
if (!empty($_POST['msg'])) { $msg = $_POST['msg']; }
if (!empty($_GET['error'])) { $error = $_GET['error']; }
if (!empty($_POST['error'])) { $error = $_POST['error']; }
if (!empty($_POST['request_arrival_date'])) { $_SESSION['request_arrival_date'] = $_POST['request_arrival_date']; }
if (!empty($_POST['order_comment'])) { $_SESSION['order_comment'] = $_POST['order_comment']; }
if (empty($_POST['action'])) {
   $action = $_GET['action'];
} else {
   $action = $_POST['action'];
}
$invalid_char_search = array(" ");

// Check for account requirement
if ($require_login_for_cart == 'Y') {
   require 'login.php';
}

switch ($action) {
  case 'login':
       // Capture some variables that may be set
       //$_SESSION['shipping_type'] = $_POST['shipping_type'];
       //$_SESSION['request_arrival_date'] = $_POST['request_arrival_date'];
       //$_SESSION['order_comment'] = $_POST['order_comment'];
       include 'login.php';
  break;
  case 'update':
       // Get some variables from the posted form
       import_request_variables('P');

       /* Custom code for shipping options */
       if (!empty($_POST['shipping_svc'])) {
          $_SESSION['shipping_svc'] = $_POST['shipping_svc'];
       } else {
          $_SESSION['shipping_svc'] = 'GROUND_HOME_DELIVERY';
       }
       
       // Select the items from the Db for this customer
       $sql = "SELECT * FROM tbl_orders_temp WHERE (session='$PHPSESSID'";
       if (isset($_SESSION['UNAME'])) {
       	  $sql .= " OR email='".$_SESSION['UNAME']."'";
       }
       $sql .= ")";
       $result = mysql_query($sql);
       if (!$result) {
          echo "Error performing query: " . mysql_error();
          exit();
       }
       $num_rows = mysql_num_rows($result);
       
       while ($row = mysql_fetch_array($result)) {
          // Update quantities
          $qty_name = "qty_".$row['product_id']."_".str_replace($invalid_char_search,"",$row['option1'])."_".str_replace($invalid_char_search,"",$row['option2'])."_".str_replace($invalid_char_search,"",$row['option3'])."_".str_replace($invalid_char_search,"",$row['option4']);
          $qty_name_arr = explode("_",$qty_name);
          $sql_update = "UPDATE tbl_orders_temp SET qty='".$$qty_name."', session_date='".date('Y-m-d')."' WHERE (session='$PHPSESSID'";
          if (isset($_SESSION['UNAME'])) {
             $sql_update .= " OR email='".$_SESSION['UNAME']."'";
          }
          $sql_update .= ") AND product_id='".$row['product_id']."'";
          if (!empty($qty_name_arr[2])) {
             $sql_update .= " AND option1='".$row['option1']."'";
          }
          if (!empty($qty_name_arr[3])) {
             $sql_update .= " AND option2='".$row['option2']."'";
          }
          if (!empty($qty_name_arr[4])) {
             $sql_update .= " AND option3='".$row['option3']."'";
          }
          if (!empty($qty_name_arr[5])) {
             $sql_update .= " AND option4='".$row['option4']."'";
          }
          if (!mysql_query($sql_update)) {
             $msg = "There was a problem updating this item in your shopping cart.<br />".mysql_error()."<br />";
             header("Location: ".$_SERVER['PHP_SELF']."?msg=$msg");
             exit();
          }
          // Save/Update the order_comment, arrival date and shipping type
          if (!empty($_POST['request_arrival_date']) || !empty($_POST['order_comment']) || !empty($_POST['shipping_type'])) {
             $sql_update2 = "UPDATE tbl_orders_temp SET request_arrival_date='".$_POST['request_arrival_date']."', order_comment='".$_POST['order_comment']."', shipping_type='".$_POST['shipping_type']."' WHERE session='$PHPSESSID'";
             if (!mysql_query($sql_update2)) {
                $msg = "There was a problem updating this item in your shopping cart.<br />".mysql_error()."<br />";
                header("Location: ".$_SERVER['PHP_SELF']."?msg=$msg");
                exit();
             }
          }
          // Remove any unwanted items
          $rmv_name = "rmv_".$row['product_id']."_".str_replace($invalid_char_search,"",$row['option1'])."_".str_replace($invalid_char_search,"",$row['option2'])."_".str_replace($invalid_char_search,"",$row['option3'])."_".str_replace($invalid_char_search,"",$row['option4']);
          $rmv_name_arr = explode("_",$rmv_name);
          if ($$rmv_name == 'Y') {
             $sql_rmv = "DELETE FROM tbl_orders_temp WHERE (session='$PHPSESSID'";
             if (isset($_SESSION['UNAME'])) {
                $sql_rmv .= " OR email='".$_SESSION['UNAME']."'";
             }
             $sql_rmv .= ") AND product_id='".$row['product_id']."'";
             if (!empty($rmv_name_arr[2])) {
                $sql_rmv .= " AND option1='".$row['option1']."'";
             }
             if (!empty($rmv_name_arr[3])) {
                $sql_rmv .= " AND option2='".$row['option2']."'";
             }
             if (!empty($rmv_name_arr[4])) {
                $sql_rmv .= " AND option3='".$row['option3']."'";
             }
             if (!empty($rmv_name_arr[5])) {
                $sql_rmv .= " AND option4='".$row['option4']."'";
             }
             if (!mysql_query($sql_rmv)) {
                $msg = "There was a problem updating this item in your shopping cart.<br />".mysql_error()."<br />";
                header("Location: ".$_SERVER['PHP_SELF']."?msg=$msg");
                exit();
             }
          }

         // Destroy the recycled variables
         unset($qty_name,$sql_update,$rmv_name,$sql_rmv);
       }
       // Free any results & close Db connection
       if (!empty($result)) {
          mysql_free_result($result);
       }
       mysql_close($dbcnx);
       
       if ($debugging == 'Y') {
          echo "<div class='debugging'>\n";
          echo "upc: ".$row['upc']."<br />\n";
          echo "qty_name: $qty_name<br />\n";
          echo "$qty_name: ".$$qty_name."<br />\n";
          echo "sql_update: $sql_update<br />\n";
          echo "rmv_name: $rmv_name<br />\n";
          echo "$rmv_name: ".$$rmv_name."<br />\n";
          if (!empty($sql_rmv)) {
             echo "sql_rmv: $sql_rmv<br />\n";
          }
          echo "<br />\n";
          echo "</div>\n";
       }

       // Send user back to cart to view changes
       header("Location: ".$_SERVER['PHP_SELF']."?msg=Shopping cart has been updated.");
       exit();
  break;
  default:
       $page_title = $store_name." - Your shopping cart";
       // Include site header
       include $inc_header_file;
       
       // Check for a session, which we should have at this point
       if (empty($PHPSESSID)) {
          // Customer doesn't have a session, so they don't have a shopping cart
          // Ask customer to login to resume a previous session
          echo "Please <a href='".$_SERVER['PHP_SELF']."?action=login'>login</a> if you already have an account.<br /><br />\n";
          // Or return to the homepage to begin shopping
          echo "Or visit our <a href='http://$siteroot'>homepage</a> to begin shopping.<br />\n";
          exit();
       } else {
       	  // Select the items from the Db for this customer
       	  $sql = "SELECT cart.product_id,cart.session,cart.email,cart.qty,cart.price,cart.option1,cart.option2,products.id,products.title,products.descr,products.upc,products.item_num,products.weight,cart.request_arrival_date,cart.order_comment,cart.shipping_type FROM tbl_orders_temp AS cart, tbl_products AS products";
          $sql .= " WHERE (cart.session='$PHPSESSID'";
          if (isset($_SESSION['UNAME'])) {
             $sql .= " OR cart.email='".$_SESSION['UNAME']."'";
          }
          $sql .= ") AND products.id = cart.product_id";
       	  $result = mysql_query($sql);
       	  if (!$result) {
             echo "Error performing query: " . mysql_error();
             exit();
       	  }
       	  $num_rows = mysql_num_rows($result);
          
          /* Get customer info into session
          $sql_customer = "SELECT customer.*,cart.session,cart.session_date,cart.email ";
          $sql_customer .= "FROM tbl_customers AS customer, tbl_orders_temp AS cart WHERE cart.email='".$_SESSION['UNAME']."' AND customer.email=cart.email";
          $result_customer = mysql_query($sql_customer);
          if (!$result_customer) {
             echo "Error performing query: " . mysql_error();
             exit();
          }
          $row_customer = mysql_fetch_array($result_customer);
          $_SESSION['ship_name'] = $row_customer['ship_name'];
          $_SESSION['ship_address'] = $row_customer['ship_address'];
          $_SESSION['ship_city'] = $row_customer['ship_city'];
          $_SESSION['ship_state'] = $row_customer['ship_state'];
          $_SESSION['ship_zip'] = $row_customer['ship_zip'];
          $_SESSION['ship_country'] = $row_customer['ship_country']; */
       	  
          /* Debugging Info */
          if ($debugging == 'Y') {
             echo "<div class='debugging'>\n";
             echo "PHPSESSID: ".$PHPSESSID."<br />\n";
             echo "SESSION['UNAME']: ".$_SESSION['UNAME']."<br />\n";
             //echo "sql: $sql<br />\n";
             //echo "num_rows: $num_rows<br />\n";
             echo "cart_fields: $cart_fields<br />\n";
             echo "tax_module: $tax_module<br />\n";
             echo "shipping_module: $shipping_module<br />\n";
             echo "show_req_arrival_date: $show_req_arrival_date<br />\n";
             echo "show_order_comment: $show_order_comment<br />\n";
             //echo "sql_customer: ".$sql_customer."<br />\n";
             //echo "row_customer['ship_name']: ".$row_customer['ship_name']."<br />\n";
             echo "SESSION['ship_name']: ".$_SESSION['ship_name']."<br />\n";
             echo "SESSION['ship_address']: ".$_SESSION['ship_address']."<br />\n";
             echo "SESSION['ship_city']: ".$_SESSION['ship_city']."<br />\n";
             echo "SESSION['ship_state']: ".$_SESSION['ship_state']."<br />\n";
             echo "SESSION['ship_zip']: ".$_SESSION['ship_zip']."<br />\n";
             echo "SESSION['ship_country']: ".$_SESSION['ship_country']."<br />\n";
             echo "</div>\n";
          }

       	  // Display messages for user
          if (!empty($msg)) {
             echo "<p class='msg'>$msg</p>\n";
       	  }
          if (!empty($error)) {
             echo "<p class='error'>$error</p>\n";
          }
          
          // Display the items in the shopping cart
          echo "<p>You currently have $num_rows item(s) in your shopping cart.<br />\n";
       	  echo "<a href='category.php'><strong><< Continue Shopping</strong></a><br /></p>\n";
       	  echo "<form action='".$_SERVER['PHP_SELF']."' name='update_cart' method='post'>\n";
          echo "<input type='hidden' name='action' value='update' />\n";
          echo "<table id='cart' border='0' cellpadding='2' cellspacing='0'>\n";
       	  echo " <tr>\n";
       	  if (strstr($cart_fields,"item_num")) {
             echo "  <td><strong>Item No.</strong></td>\n";
       	  }
       	  if (strstr($cart_fields,"upc")) {
             echo "  <td><strong>UPC</strong></td>\n";
       	  }
       	  echo "  <td><strong>Description</strong></td>\n";
          if (strstr($cart_fields,"weight")) {
             echo "  <td><strong>Weight</strong></td>\n";
          }
       	  echo "  <td><strong>Price Ea.</strong></td>\n";
       	  echo "  <td><strong>Qty</strong></td>\n";
       	  echo "  <td><strong>Price Ext.</strong></td>\n";
       	  echo "  <td><strong>Remove</strong></td>\n";
       	  echo " </tr>\n";
       	  $subtotal = 0;
       	  $tax = 0;
          $total_wt = 0;
          $items = 0;
       	  $shipping = 0;
          $total = 0;
       	  while ($row = mysql_fetch_array($result)) {
             echo " <tr>\n";
       	     if (strstr($cart_fields,"item_num")) {
                echo "  <td valign='top'>".$row['item_num']."</td>\n";
             }
       	     if (strstr($cart_fields,"upc")) {
       	        echo "  <td valign='top'>".$row['upc']."</td>\n";
       	     }
             echo "  <td valign='top'>".$row['title']."<br />".stripslashes(substr($row['descr'],0,100))."...<br />".$row['option1'];
             //echo "  <td valign='top'>".$row['title']."<br />".$row['option1'];
             if (!empty($row['option2'])) {
                echo ", ".$row['option2'];
             }
             if (!empty($row['option3'])) {
                echo ", ".$row['option3'];
             }
             if (!empty($row['option4'])) {
                echo ", ".$row['option4'];
             }
             echo "</td>\n";
             if (strstr($cart_fields,"weight")) {
                echo "  <td valign='top'>".$row['weight']."</td>\n";
             }
             echo "  <td valign='top'>$".number_format($row['price'],2)."</td>\n";
             echo "  <td valign='top'><input type='text' size='2' name='qty_".$row['product_id']."_".str_replace($invalid_char_search,"",$row['option1'])."_".str_replace($invalid_char_search,"",$row['option2'])."_".str_replace($invalid_char_search,"",$row['option3'])."_".str_replace($invalid_char_search,"",$row['option4'])."' value='".$row['qty']."' /></td>\n";
             echo "  <td valign='top' align='right'>$".number_format($row['price']*$row['qty'],2)."</td>\n";
             echo "  <td valign='top'><input type='checkbox' name='rmv_".$row['product_id']."_".str_replace($invalid_char_search,"",$row['option1'])."_".str_replace($invalid_char_search,"",$row['option2'])."_".str_replace($invalid_char_search,"",$row['option3'])."_".str_replace($invalid_char_search,"",$row['option4'])."' title='Click to select this item to be removed' value='Y' /></td>\n";
             echo " </tr>\n";
             $subtotal = $subtotal + ($row['price']*$row['qty']);
             $total_wt = $total_wt + ($row['weight']*$row['qty']);
             $_SESSION['totat_wt'] = $total_wt;
             $items = $items + $row['qty'];
             $cart_email = $row['email'];
             if (empty($_SESSION['request_arrival_date'])) {
                $_SESSION['request_arrival_date'] = $row['request_arrival_date'];
             }
             if (empty($_SESSION['order_comment'])) {
                $_SESSION['order_comment'] = $row['order_comment'];
             }
       	  }
          if (strstr($cart_fields,"weight")) {
             echo " <tr>\n";
             echo "  <td colspan='4' align='right'><strong>Total Weight:</strong></td>\n";
             echo "  <td colspan='1' align='right'>".$total_wt."</td>\n";
             echo "  <td>&nbsp;</td>\n";
             echo " </tr>\n";
          }
       	  echo " <tr>\n";
       	  echo "  <td colspan='4' align='right'><strong>SubTotal:</strong></td>\n";
       	  echo "  <td colspan='1' align='right'>$".number_format($subtotal,2)."</td>\n";
       	  echo "  <td>&nbsp;</td>\n";
       	  echo " </tr>\n";

          /* Get the Tax module */
          if (!empty($tax_module)) {
             include $tax_module;
          }

          /* Get the Shipping module */
          if (!empty($shipping_module)) {
             include $shipping_module;
          }

          if ($show_req_arrival_date == 'Y') {
             echo " <tr>\n";
             echo "  <td colspan='3' align='right'><strong>Requested Arrival Date:</strong> </td>\n";
             echo "  <td colspan='3' align='left'><input type='text' name='request_arrival_date' size='10' value='".$_SESSION['request_arrival_date']."' /></td>\n";
             echo " </tr>\n";
          }
          if ($show_order_comment == 'Y') {
             echo " <tr>\n";
             echo "  <td colspan='3' align='right'><strong>Comment:</strong> </td>\n";
             echo "  <td colspan='3' align='left'><input type='text' name='order_comment' size='20' value='".$_SESSION['order_comment']."' /></td>\n";
             echo " </tr>\n";
          }
       	  echo " <tr>\n";
       	  echo "  <td colspan='3' align='right' valign='top'>Click here to make any changes to your cart -></td>\n";
       	  echo "  <td colspan='3'><input type='submit' name='submit' value='Update Cart' /></form>\n";
       	  echo "  <form action='checkout.php' name='checkout' method='post'>\n";
          echo "  <input type='submit' name='submit' value='Checkout'";
          if (empty($num_rows) || $num_rows < 1) {
             echo " disabled";
          }
          echo " />\n";
       	  echo "  </form>\n";
          echo "  </td>\n";
       	  echo " </tr>\n";
       	  echo "</table>\n";

          // Display messages for user
          if (!empty($msg)) {
             echo "<p class='msg'>$msg</p>\n";
          }
          if (!empty($error)) {
             echo "<p class='error'>$error</p>\n";
          }
       	  
          echo "<p><a href='category.php'><strong><< Continue Shopping</strong></a></p>\n";
       	  if (!empty($cart_email)){
       	     echo "<p><a href='customer_info.php'>Update Current Customer Info</a></p>\n";
          } else {
             echo "<p><a href='view_cart.php?action=login'>Returning Customer Login</a></p>\n";
          }
          
          /* Debugging Info */
          if ($debugging == 'Y') {
             echo "<div class='debugging'>\n";
             //echo "PHPSESSID: ".$PHPSESSID."<br />\n";
             echo "sql_check: $sql_check<br />\n";
             echo "missing_requirement: $missing_requirement<br />\n";
             echo "missing_requirement1: $missing_requirement1<br />\n";
             echo "missing_requirement2: $missing_requirement2<br />\n";
             echo "missing_requirement3: $missing_requirement3<br />\n";
             echo "</div>\n";
          }
       	  
       	  // Include site footer
          include $inc_footer_file;

          // Free any results & close Db connection
       	  if (!empty($result)) {
             mysql_free_result($result);
          }
          if (!empty($result_check)) {
             mysql_free_result($result_check);
          }
          if (!empty($result_check2)) {
             mysql_free_result($result_check2);
          }
          if (!empty($result_customer)) {
             mysql_free_result($result_customer);
          }
          mysql_close($dbcnx);
       }
  break;
}
?>
