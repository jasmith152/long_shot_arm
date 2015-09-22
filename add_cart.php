<?php
// Get the configuration file
require 'cart_config.php';

// Check for and set variables
if (!empty($_GET['id'])) { $id = $_GET['id']; }
if (!empty($_POST['id'])) { $id = $_POST['id']; }
if (!empty($_GET['qty'])) { $qty = $_GET['qty']; }
if (!empty($_POST['qty'])) { $qty = $_POST['qty']; }
if (!empty($_GET['option1'])) { $option1 = $_GET['option1']; }
if (!empty($_POST['option1'])) { $option1 = $_POST['option1']; }
if (!empty($_GET['option2'])) { $option2 = $_GET['option2']; }
if (!empty($_POST['option2'])) { $option2 = $_POST['option2']; }
if (!empty($_GET['option3'])) { $option3 = $_GET['option3']; }
if (!empty($_POST['option3'])) { $option3 = $_POST['option3']; }
if (!empty($_GET['option4'])) { $option4 = $_GET['option4']; }
if (!empty($_POST['option4'])) { $option4 = $_POST['option4']; }
/*$id = $_POST['id'];
$qty = $_POST['qty'];
$option1 = $_POST['option1'];
$option2 = $_POST['option2'];
$option3 = $_POST['option3'];
$option4 = $_POST['option4'];*/
/*session_register("cat");
if (!empty($_GET['cat'])) { $_SESSION['cat'] = $_GET['cat']; }
if (!empty($_POST['cat'])) { $_SESSION['cat'] = $_POST['cat']; }*/

// Check for product details
if (empty($id) || empty($qty)) {
   $msg = "There was a problem adding this item to your shopping cart. Please reload the page and try again.";
   header("Location: http://".$site_root."product.php?id=$id&msg=$msg");
   exit();
}

// Get details from the product
$sql_details = "SELECT * FROM tbl_products WHERE id='$id'";
$result_details = mysql_query($sql_details);
if (!$result_details) {
   $msg = "There was a problem adding this item to your shopping cart.<br />".mysql_error()."<br />Please reload the page and try again.";
   header("Location: http://".$site_root."product.php?id=$id&msg=$msg");
   exit();
}
$row_details = mysql_fetch_array($result_details);
// Set variables for the product options that were chosen
if (!empty($option1) && !empty($row_details['option1_pricing'])) {
   $arr_option1 = explode('|',$row_details['option1']);
   $arr_option1_pricing = explode('|',$row_details['option1_pricing']);
   $count = count($arr_option1);
   for ($i = 0; $i < $count; $i++) {
       if ($arr_option1[$i] == $option1) {
       	  $option1_pricing = $arr_option1_pricing[$i];
       }
   }
}
if (!empty($option2) && !empty($row_details['option2_pricing'])) {
   $arr_option2 = explode('|',$row_details['option2']);
   $arr_option2_pricing = explode('|',$row_details['option2_pricing']);
   $count = count($arr_option2);
   for ($i = 0; $i < $count; $i++) {
       if ($arr_option2[$i] == $option2) {
       	  $option2_pricing = $arr_option2_pricing[$i];
       }
   }
}
if (!empty($option3) && !empty($row_details['option3_pricing'])) {
   $arr_option3 = explode('|',$row_details['option3']);
   $arr_option3_pricing = explode('|',$row_details['option3_pricing']);
   $count = count($arr_option3);
   for ($i = 0; $i < $count; $i++) {
       if ($arr_option3[$i] == $option3) {
          $option3_pricing = $arr_option3_pricing[$i];
       }
   }
}
if (!empty($option4) && !empty($row_details['option4_pricing'])) {
   $arr_option4 = explode('|',$row_details['option4']);
   $arr_option4_pricing = explode('|',$row_details['option4_pricing']);
   $count = count($arr_option4);
   for ($i = 0; $i < $count; $i++) {
       if ($arr_option4[$i] == $option4) {
          $option4_pricing = $arr_option4_pricing[$i];
       }
   }
}

// Look for an already existing item in the customer's shopping cart
$sql_cart = "SELECT session,email,product_id FROM tbl_orders_temp WHERE ";
if (!empty($_SESSION['UNAME'])) {
   $sql_cart .= "email='".$_SESSION['UNAME']."'";
} else {
   $sql_cart .= "session='$PHPSESSID'";
}
$sql_cart .= " AND product_id='".$row_details['id']."' AND option1='$option1' AND option2='$option2' AND option3='$option3' AND option4='$option4'";
$result_cart = mysql_query($sql_cart);
$num_rows = mysql_num_rows($result_cart);
if ($num_rows > 0) {
   // Update the qty since this item is already in the shopping cart
   $sql_update = "UPDATE tbl_orders_temp SET qty=qty+'$qty', session_date='".date('Y-m-d')."' WHERE product_id='".$row_details['id']."' AND option1='$option1' AND option2='$option2' AND session='$PHPSESSID'";
   if (!empty($_SESSION['UNAME'])) {
      $sql_update .= " OR email='".$_SESSION['UNAME']."'";
   }
   if (@mysql_query($sql_update)) {
      header("Location: http://".$site_root."view_cart.php?msg=Item added to your cart.");
      exit();
   } else {
      $msg = "There was a problem adding this item to your shopping cart.<br />".mysql_error()."<br />Please reload the page and try again.";
      header("Location: http://".$site_root."product.php?id=$id&msg=$msg");
      exit();
   }
} else {
   // Calculate price with options
   $price = $row_details['price'];
   if (!empty($option1_pricing)) {
      $price = $price + $option1_pricing;
   }
   if (!empty($option2_pricing)) {
      $price = $price + $option2_pricing;
   }
   if (!empty($option3_pricing)) {
      $price = $price + $option3_pricing;
   }
   if (!empty($option4_pricing)) {
      $price = $price + $option4_pricing;
   }
   // Insert the record
   $sql_insert = "INSERT INTO tbl_orders_temp SET session='$PHPSESSID', session_date='".date('Y-m-d')."', product_id='".$row_details['id']."', qty='$qty', email='".$_SESSION['UNAME']."', price='$price', option1='$option1', option2='$option2', option3='$option3', option4='$option4'";
   if (@mysql_query($sql_insert)) {
      header("Location: http://".$site_root."view_cart.php?msg=Item added to your cart.");
      exit();
   } else {
      $msg = "There was a problem adding this item to your shopping cart.<br />".mysql_error()."<br />Please reload the page and try again.";
      header("Location: http://".$site_root."product.php?id=$id&msg=$msg");
      exit();
   }
}

// Free any results & close Db connection
if (!empty($result_details)) {
   mysql_free_result($result_details);
}
if (!empty($result_cart)) {
   mysql_free_result($result_cart);
}
mysql_close($dbcnx);
?>
