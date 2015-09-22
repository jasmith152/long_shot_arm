<?php
// Get the configuration file
require 'cart_config.php';

// Check for and set variables
$where_clause_num = 0;
if (!empty($_GET['id'])) { $id = $_GET['id']; }
if (!empty($_POST['id'])) { $id = $_POST['id']; }
$option1 = $_POST['option1'];
$option2 = $_POST['option2'];
$option3 = $_POST['option3'];
$option4 = $_POST['option4'];
/* Variables for testing
$vendor = "";
$style = "";
$price = ""; */

/* Check for variables; if we have no variables, then we need to direct user to the homepage
Otherwise every item in the Db would be displayed here. */
if (empty($id)) {
   header("Location: http://".$site_root."category.php");
   exit();
}

// Include site header
$page_title = "";
$page_descr = "";
$extra_head = "<SCRIPT LANGUAGE='JAVASCRIPT'>\n<!--\nfunction roll(img_name1, img_src1)\n   {\n   document[img_name1].src = img_src1;\n   }\n//-->\n</SCRIPT>\n";
include $inc_header_file;

// Display any messages
if (!empty($msg)) {
   echo "<p class='msg'>$msg</p>\n";
}
if (!empty($error)) {
   echo "<p class='error'>$error</p>\n";
}

$sql = "SELECT * FROM tbl_products WHERE id = '$id'";
$result = mysql_query($sql);
if (!$result) {
   echo "Error performing query: " . mysql_error();
   exit();
}
$num_rows = mysql_num_rows($result);
$row = mysql_fetch_array($result);

//Recalculate price if options have been set
if (isset($option1) && !empty($row['option1_pricing'])) {
   
}

/* Debugging Info */
if ($debugging == 'Y') {
   echo "<div class='debugging'>\n";
   echo "PHPSESSID: ".$PHPSESSID."<br />\n";
   echo "sql: $sql<br />\n";
   echo "num_rows: $num_rows<br />\n";
   echo "</div>\n";
}

echo "<p><a class='back_to_cat_link' href='category.php'><< Back to products</a></p>\n";

//echo " <form action='".$_SERVER['PHP_SELF']."' method='post' name='get_price'>\n";
echo " <form action='add_cart.php' method='post' name='add_cart'>\n";
echo " <a name='photo-box'></a>\n";
echo " <table border='0' cellpadding='0' cellspacing='0' class='product_detail'>\n";
echo "  <tr>\n";

echo "   <td align='left' valign='top'><span class='product_title'>".$row['title']."</span><br />\n";
echo " <table id='product_photos' border='0' cellpadding='0' cellspacing='1'>\n";
echo "  <tr>\n";
echo "   <td colspan='5' align='center'>\n";
if (photo_exists($image_fullpath,$row['item_num'],1)) {
   $image_src = $image_path.photo_exists($image_fullpath,$row['item_num'],1);
   $image_info = getimagesize($image_src);
   echo "<img src='$image_src' ".$image_info[3]." name='Photo' border='0' alt='".$row1['id']."' />\n";
} else {
   echo "<img src='images/no_image.jpg' width='126' height='118' border='0' alt='No image available' /><br />\n";
}
echo "   </td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "   <td align='center'>\n";
if (photo_exists($image_fullpath,$row['item_num'],1)) {
   $image_src = $image_path.photo_exists($image_fullpath,$row['item_num'],1);
   $image_info = getimagesize($image_src);
   $new_height = ($image_info[1]/$image_info[0])*96;
   echo "<a href='#photo-box' onclick=\"roll('Photo', '$image_src')\"><img src='$image_src' width='96' height='$new_height' border='0' alt='".$row1['id']."' /></a><br />\n";
}
echo "   </td>\n";
echo "   <td align='center'>\n";
if (photo_exists($image_fullpath,$row['item_num'],2)) {
   $image_src = $image_path.photo_exists($image_fullpath,$row['item_num'],2);
   $image_info = getimagesize($image_src);
   $new_height = ($image_info[1]/$image_info[0])*96;
   echo "<a href='#photo-box' onclick=\"roll('Photo', '$image_src')\"><img src='$image_src' width='96' height='$new_height' border='0' alt='".$row1['id']."' /></a><br />\n";
}
echo "   </td>\n";
echo "   <td align='center'>\n";
if (photo_exists($image_fullpath,$row['item_num'],3)) {
   $image_src = $image_path.photo_exists($image_fullpath,$row['item_num'],3);
   $image_info = getimagesize($image_src);
   $new_height = ($image_info[1]/$image_info[0])*96;
   echo "<a href='#photo-box' onclick=\"roll('Photo', '$image_src')\"><img src='$image_src' width='96' height='$new_height' border='0' alt='".$row1['id']."' /></a><br />\n";
}
echo "   </td>\n";
echo "   <td align='center'>\n";
if (photo_exists($image_fullpath,$row['item_num'],4)) {
   $image_src = $image_path.photo_exists($image_fullpath,$row['item_num'],4);
   $image_info = getimagesize($image_src);
   $new_height = ($image_info[1]/$image_info[0])*96;
   echo "<a href='#photo-box' onclick=\"roll('Photo', '$image_src')\"><img src='$image_src' width='96' height='$new_height' border='0' alt='".$row1['id']."' /></a><br />\n";
}
echo "   </td>\n";
echo "   <td align='center'>\n";
if (photo_exists($image_fullpath,$row['item_num'],5)) {
   $image_src = $image_path.photo_exists($image_fullpath,$row['item_num'],5);
   $image_info = getimagesize($image_src);
   $new_height = ($image_info[1]/$image_info[0])*96;
   echo "<a href='#photo-box' onclick=\"roll('Photo', '$image_src')\"><img src='$image_src' width='96' height='$new_height' border='0' alt='".$row1['id']."' /></a><br />\n";
}
echo "   </td>\n";
echo "  </tr>\n";
echo " </table>\n";
if (!empty($row['brand'])) {
   echo "   <span class='field_name'>".$row['brand']."</span><br />\n";
}
if (!empty($row['item_num'])) {
   echo "   <span class='field_name'>Item No.:</span> ".$row['item_num']."<br />\n";
}
if (!empty($row['upc'])) {
   echo "   <span class='field_name'>UPC:</span> ".$row['upc']."<br />\n";
}
echo "   ".stripslashes($row['descr'])."<br />\n";
if (!empty($row['option1'])) {
   //Parse the comma separated string from Option1 and display as drop down
   $arr_option1 = explode('|',$row['option1']);
   if (!empty($row['option1_pricing'])) {
      $arr_option1_pricing = explode('|',$row['option1_pricing']);
   }
   $count = count($arr_option1);
   echo "   ".$arr_option1[0].": <select name='option1' size='1'>\n";
   for ($i = 0; $i < $count; $i++) {
       if ($i != 0) {
       	  echo "    <option value='".$arr_option1[$i]."'";
          if ($arr_option1[$i] == $option1) { echo " selected"; }
          echo ">".$arr_option1[$i];
          if (!empty($arr_option1_pricing[$i]) && ($i != 0)) {
       	     echo " $".number_format($arr_option1_pricing[$i],2);
          }
          echo "</option>\n";
       }
   }
   echo "   </select> \n";
}
if (!empty($row['option2'])) {
   //Parse the comma separated string from Option1 and display as drop down
   $arr_option2 = explode('|',$row['option2']);
   if (!empty($row['option2_pricing'])) {
      $arr_option2_pricing = explode('|',$row['option2_pricing']);
   }
   $count = count($arr_option2);
   echo "   <select name='option2' size='1'";
   //if (isset($arr_option2_pricing)) { echo " onChange='this.form.submit()'"; }
   echo ">\n";
   for ($i = 0; $i < $count; $i++) {
       if ($i != 0) {
       	  echo "    <option value='".$arr_option2[$i]."'";
       } else {
          echo "    <option value=''";
       }
       if ($arr_option2[$i] == $option2) { echo " selected"; }
       echo ">".$arr_option2[$i];
       if (!empty($arr_option2_pricing[$i]) && ($i != 0)) {
       	  echo " $".number_format($arr_option2_pricing[$i],2);
       }
       echo "</option>\n";
   }
   echo "   </select><br />\n";
}
if (!empty($row['option3'])) {
   //Parse the comma separated string from Option1 and display as drop down
   $arr_option3 = explode('|',$row['option3']);
   if (!empty($row['option3_pricing'])) {
      $arr_option3_pricing = explode('|',$row['option3_pricing']);
   }
   $count = count($arr_option3);
   echo "   <select name='option3' size='1'";
   //if (isset($arr_option3_pricing)) { echo " onChange='this.form.submit()'"; }
   echo ">\n";
   for ($i = 0; $i < $count; $i++) {
       if ($i != 0) {
       	  echo "    <option value='".$arr_option3[$i]."'";
       } else {
          echo "    <option value=''";
       }
       if ($arr_option3[$i] == $option3) { echo " selected"; }
       echo ">".$arr_option3[$i];
       if (!empty($arr_option3_pricing[$i]) && ($i != 0)) {
       	  echo " $".number_format($arr_option3_pricing[$i],2);
       }
       echo "</option>\n";
   }
   echo "   </select> \n";
}
if (!empty($row['option4'])) {
   //Parse the comma separated string from Option1 and display as drop down
   $arr_option4 = explode('|',$row['option4']);
   if (!empty($row['option4_pricing'])) {
      $arr_option4_pricing = explode('|',$row['option4_pricing']);
   }
   $count = count($arr_option4);
   echo "   <select name='option4' size='1'";
   //if (isset($arr_option4_pricing)) { echo " onChange='this.form.submit()'"; }
   echo ">\n";
   for ($i = 0; $i < $count; $i++) {
       if ($i != 0) {
       	  echo "    <option value='".$arr_option4[$i]."'";
       } else {
          echo "    <option value=''";
       }
       if ($arr_option4[$i] == $option4) { echo " selected"; }
       echo ">".$arr_option4[$i];
       if (!empty($arr_option4_pricing[$i]) && ($i != 0)) {
       	  echo " $".number_format($arr_option4_pricing[$i],2);
       }
       echo "</option>\n";
   }
   echo "   </select><br />\n";
}
if ($show_status == 'Y'){
   echo "<br />Status: <span class='status'>".$row['status']."</span>\n";
}
echo "<br />\n";
if ($row['price'] > 0){
   echo " $".number_format($row['price'],2)." Each&nbsp;\n";
   echo "Qty:<input type='text' size='3' name='qty' value='1' />\n";
} else {
   echo "<input type='hidden' name='qty' value='1' />\n";
}
if ($row['status'] == 'Active' || $row['status'] == 'In Stock') {
   echo "<input type='submit' name='submit_cart' value='Add to Cart' />\n";
}
if (file_exists($logo_fullpath.$row['brand'].".jpg") || file_exists($logo_fullpath.$row['brand'].".gif")) {
   if (file_exists($logo_fullpath.$row['brand'].".jpg")) {
      $logo_src = $logo_path.$row['brand'].".jpg";
   } else {
      $logo_src = $logo_path.$row['brand'].".gif";
   }
   echo "<br /><img src='$logo_src' border='0' alt='".$row['brand']." logo'>\n";
}
echo "<input type='hidden' name='id' value='$id' />\n";
echo "</form>\n";
//echo "<div align='left'><iframe src='http://www.facebook.com/plugins/like.php?href=http://www.southernciderco.com/product.php?id=$id' scrolling='no' frameborder='0' style='border:none; width:265px; height:80px;'></iframe></div>\n";
echo "   </td>\n";
echo "  </tr>\n";
echo " </table>\n";

// Include site footer
include $inc_footer_file;

/* Close out the result set */
mysql_free_result($result);
/* Closing connection */
mysql_close($dbcnx);
?>
