<?php
/* Set store variables */
$store_name = 'Long Shot Arms LLC';
$order_email = 'longshotarmsllc@gmail.com';
$order_email_name = 'Long Shot Arms';
//$site_root = $_SERVER['SERVER_NAME']."/"; /* do not include http:// */
$site_root = 'longshotarmsllc.com/';
$inc_header_file = 'header.php';
$inc_footer_file = 'footer.php';
$inc_cart_css = 'cart_styles.css';
$image_fullpath = "/home/longshot/public_html/product_images/";
$image_path = "product_images/";
$num_photos = 5;
$logo_fullpath = "/home/longshot/public_html/logos/";
$logo_path = "logos/";

/* Display category descriptions */
switch ($cat){
  case 'Rifles':
    $page_title = "";
    $page_descr = "";
    $cat_descr = "";
  break;
  case 'Shotguns':
    $page_title = "";
    $page_descr = "";
    $cat_descr = "";
  break;
  default:
    $page_title = "";
    $page_descr = "";
    $cat_descr = "";
  break;
}
/* Display spotlight descriptions 
switch ($spot){
  case '':
    $page_title = "Title";
    $page_descr = "Description";
  break;
  default:
    $page_title = "Title";
    $page_descr = "Description";
  break;
}*/

/* Product display variables */
$num_columns = "3"; //number of columns across the page
$max_products = "12"; //should be a multiple of the number of columns
$product_fields = "title,descr"; //up to 5 comma separated fields
$field_limits = ",20"; //corresponding to fields above, list limits to be placed on the fields; leave blanks for no limit
$price_display = "$"; //use for preceding text (like $); leave blank to not show price
$orderby_clause = " ORDER BY item_num,sort_num"; //order clause; be sure to sort by a field that is being pulled
$show_inactive = "N";
$show_outofstock = "Y";

/* Cart display and calculations */
$require_login_for_cart = 'N';
$cart_fields = "item_num,weight"; // up to 5 comma separated fields to display on cart page
$tax_module = 'mod_tax_std.php';
$shipping_module = 'mod_ship_flat.php';
$payment_gateway_name = 'PayPal';
$payment_gateway_module = 'mod_payment_paypal.php';
$show_req_arrival_date = 'N';
$show_order_comment = 'Y';
$cart_shipping_policy = "";

/* Variables for testing
$cat = "";
$subcat = "";
$brand = "";
$spot = "";
$affiliate = "" */

// Testing mode
$test_store = 'N'; // If turned on, this will allow regular functionality but will skip payment gateway

// Debugging mode
$debugging = 'N'; // If turned on, each page will display debugging info

/* Start a session
if (empty($PHPSESSID)) { */
   // initiate a session
   session_start();
   // register some session variables
   $PHPSESSID = session_id();
//}

/* User types */
$user_types = explode("|","All Users|Retail Customer|Wholesale Customer"); // For no user types, leave blank. Otherwise, begin array with All Users|
$_SESSION['user_type_title'] = $user_types[$_SESSION['user_type']];
/*switch ($_SESSION['user_type']) {
   case 1:
      $user_type_title = "Retail Customer";
   break;
   case 2:
      $user_type_title = "Wholesale Customer";
   break;
   default:
   break;
}*/

/* Affiliates info */
switch ($_SESSION['affiliate']) {
   case "blah":
      $affiliate_email = "blah@blah.com";
   break;
   default:
   break;
}

/* Connect to the Db */
$dbcnx = mysql_connect("localhost", "longshot_armsllc", "r1fl3sGuns") or die("<p>Unable to connect to the database at this time.</p>");
mysql_select_db("longshot_armsllc") or die("<p>Unable to locate the database at this time.</p>");

/* Function for finding product photo thumbnails */
function thumbnail_exists($uploaddir_abs,$item_num,$photo_num) {
  if (file_exists($uploaddir_abs.$item_num.'-'.$photo_num.'_sm.jpg')) {
    return $item_num.'-'.$photo_num.'_sm.jpg';
  } elseif (file_exists($uploaddir_abs.$item_num.'-'.$photo_num.'_sm.JPG')) {
    return $item_num.'-'.$photo_num.'_sm.JPG';
  } elseif (file_exists($uploaddir_abs.$item_num.'-'.$photo_num.'_sm.gif')) {
    return $item_num.'-'.$photo_num.'_sm.gif';
  } elseif (file_exists($uploaddir_abs.$item_num.'-'.$photo_num.'_sm.GIF')) {
    return $item_num.'-'.$photo_num.'_sm.GIF';
  } elseif (file_exists($uploaddir_abs.$item_num.'-'.$photo_num.'_sm.png')) {
    return $item_num.'-'.$photo_num.'_sm.png';
  } elseif (file_exists($uploaddir_abs.$item_num.'-'.$photo_num.'_sm.PNG')) {
    return $item_num.'-'.$photo_num.'_sm.PNG';
  } else {
    return FALSE;
  }
}

/* Function for finding product photos */
function photo_exists($uploaddir_abs,$item_num,$photo_num) {
  if (file_exists($uploaddir_abs.$item_num.'-'.$photo_num.'.jpg')) {
    return $item_num.'-'.$photo_num.'.jpg';
  } elseif (file_exists($uploaddir_abs.$item_num.'-'.$photo_num.'.JPG')) {
    return $item_num.'-'.$photo_num.'.JPG';
  } elseif (file_exists($uploaddir_abs.$item_num.'-'.$photo_num.'.gif')) {
    return $item_num.'-'.$photo_num.'.gif';
  } elseif (file_exists($uploaddir_abs.$item_num.'-'.$photo_num.'.GIF')) {
    return $item_num.'-'.$photo_num.'.GIF';
  } elseif (file_exists($uploaddir_abs.$item_num.'-'.$photo_num.'.png')) {
    return $item_num.'-'.$photo_num.'.png';
  } elseif (file_exists($uploaddir_abs.$item_num.'-'.$photo_num.'.PNG')) {
    return $item_num.'-'.$photo_num.'.PNG';
  } else {
    return FALSE;
  }
}

/* Function for finding logos for product brands */
function logo_exists($uploaddir_abs,$item_num) {
  if (file_exists($uploaddir_abs.$item_num.'.jpg')) {
    return $item_num.'.jpg';
  } elseif (file_exists($uploaddir_abs.$item_num.'.JPG')) {
    return $item_num.'.JPG';
  } elseif (file_exists($uploaddir_abs.$item_num.'.gif')) {
    return $item_num.'.gif';
  } elseif (file_exists($uploaddir_abs.$item_num.'.GIF')) {
    return $item_num.'.GIF';
  } elseif (file_exists($uploaddir_abs.$item_num.'.png')) {
    return $item_num.'.png';
  } elseif (file_exists($uploaddir_abs.$item_num.'.PNG')) {
    return $item_num.'.PNG';
  } else {
    return FALSE;
  }
}

/* Function for listing categories */
function category_list($prefix,$postfix) {
  $sql_cats = "SELECT DISTINCT category FROM tbl_products ORDER BY category";
  $result_cats = @mysql_query($sql_cats);
  while ($row_cats = mysql_fetch_array($result_cats)) {
     // Create category nav links
     $category_list .= $prefix."<a href='category.php?cat=".$row_cats['category']."'>".$row_cats['category']."</a>".$postfix;
  }
  
  /* Free the mysql result */
  if (!empty($result_cats)) { mysql_free_result($result_cats); }
  
  return $category_list;
}

$PHP_SELF = $_SERVER['PHP_SELF'];
?>