<?php
// Get the configuration file
require 'cart_config.php';

// Check for and set variables
$where_clause_num = 0;
/*session_register("cat");
session_register("subcat");
session_register("brand");
session_register("spot");
session_register("affiliate");*/
if (!empty($_SESSION['subcat']) && ($_GET['cat'] != $_SESSION['cat'] || $_POST['cat'] != $_SESSION['cat'])) { $_SESSION['subcat'] = ''; }
if (!empty($_GET['cat'])) { $_SESSION['cat'] = $_GET['cat']; }
if (!empty($_POST['cat'])) { $_SESSION['cat'] = $_POST['cat']; }
if (!empty($_GET['subcat'])) { $_SESSION['subcat'] = $_GET['subcat']; }
if (!empty($_POST['subcat'])) { $_SESSION['subcat'] = $_POST['subcat']; }
if (!empty($_GET['brand'])) { $_SESSION['brand'] = $_GET['brand']; }
if (!empty($_POST['brand'])) { $_SESSION['brand'] = $_POST['brand']; }
if (!empty($_GET['spot'])) { $_SESSION['spot'] = $_GET['spot']; } else { $_SESSION['spot'] = ''; }
if (!empty($_POST['spot'])) { $_SESSION['spot'] = $_POST['spot']; } else { $_SESSION['spot'] = ''; }
if (!empty($_GET['affiliate'])) { $_SESSION['affiliate'] = $_GET['affiliate']; }
if (!empty($_POST['affiliate'])) { $_SESSION['affiliate'] = $_POST['affiliate']; }
if (!empty($_GET['page'])) { $page = $_GET['page']; }
if (!empty($_POST['page'])) { $page = $_POST['page']; }
if (!empty($_GET['msg'])) { $msg = $_GET['msg']; }
if (!empty($_POST['msg'])) { $msg = $_POST['msg']; }
if (!empty($_GET['error'])) { $error = $_GET['error']; }
if (!empty($_POST['error'])) { $error = $_POST['error']; }

/* Check for variables; if we have no variables, then we need to direct user to the homepage
Otherwise every item in the Db would be displayed here. */
if (empty($_SESSION['cat']) && empty($_SESSION['subcat']) && empty($_SESSION['brand']) && empty($_SESSION['spot'])) {
   header("Location: http://".$site_root);
   exit();
}

// Include site header
include $inc_header_file;

// Display any messages
if (!empty($msg)) {
   echo "<p class='msg'>$msg</p>\n";
}
if (!empty($error)) {
   echo "<p class='error'>$error</p>\n";
}

// Check for variables
if (!empty($_SESSION['cat'])) {
   if ($where_clause_num > 0) { $where_clause .= " AND"; }
   if ($_SESSION['cat'] != 'All') {
      $where_clause .= " category = '".$_SESSION['cat']."'";
      $where_clause_num++;
   }
}
if (!empty($_SESSION['subcat'])) {
   if ($where_clause_num > 0) { $where_clause .= " AND"; }
   if ($_SESSION['subcat'] != 'All') {
      $where_clause .= " subcategory = '".$_SESSION['subcat']."'";
      $where_clause_num++;
   }
}
if (!empty($_SESSION['brand'])) {
   if ($where_clause_num > 0) { $where_clause .= " AND"; }
   if ($_SESSION['brand'] != 'All') {
      $where_clause .= " brand = '".$_SESSION['brand']."'";
      $where_clause_num++;
   }
}
if (!empty($_SESSION['spot'])) {
   if ($where_clause_num > 0) { $where_clause .= " AND"; }
   if ($_SESSION['spot'] != 'All') {
      $where_clause .= " spotlight = '".$_SESSION['spot']."'";
      $where_clause_num++;
   }
}
if (!empty($show_outofstock) && $show_outofstock == 'N') {
   if ($where_clause_num > 0) { $where_clause .= " AND"; }
   $where_clause .= " status != 'Out of Stock'";
   $where_clause_num++;
}
if (!empty($show_inactive) && $show_inactive == 'N') {
   if ($where_clause_num > 0) { $where_clause .= " AND"; }
   $where_clause .= " status != 'Inactive'";
   $where_clause_num++;
}
if (!empty($_SESSION['user_type'])) {
   if ($where_clause_num > 0) { $where_clause .= " AND"; }
   $where_clause .= " (user_types Like '%".$_SESSION['user_type']."%')";
   $where_clause_num++;
} else {
   if ($where_clause_num > 0) { $where_clause .= " AND"; }
   $where_clause .= " (user_types < 2)";
   $where_clause_num++;
}
if (!empty($where_clause)) {
   $where_clause = " WHERE".$where_clause;
}
if (!empty($_SESSION['cat'])) {
   $sql_subcat = "SELECT DISTINCT subcategory FROM tbl_products WHERE category = '".$_SESSION['cat']."'";
   $result_subcat = mysql_query($sql_subcat);
   $subcat_num = mysql_num_rows($result_subcat);
   if ($subcat_num >= 1) {
      echo "<div id='subcat_list'><span id='subcat_list_title'>Subcategories:</span> ";
   }
   while ($row_subcat = mysql_fetch_array($result_subcat)) {
      if ($row_num > 0 && $row_subcat['subcategory'] != 'None') { echo " | "; }
         if (!empty($row_subcat['subcategory']) && $row_subcat['subcategory'] != 'None') {
            echo "<a href='".$_SERVER['PHP_SELF']."?&subcat=".$row_subcat['subcategory']."'>".$row_subcat['subcategory']."</a>";
            $row_num++;
         }
   }
   echo "</div>\n";
}

// Choose records to display by page #
if (empty($page)) {
   $page = 1;
   $offset = 0;
} else {
   $offset = ($page - 1) * $max_products;
}
$sql_num = "SELECT id,item_num,price,$product_fields FROM tbl_products".$where_clause.$orderby_clause;
$result_num = mysql_query($sql_num);
if (!$result_num) {
   echo "Error performing query: " . mysql_error();
   exit();
}
$num_rows = mysql_num_rows($result_num);
$sql1 = "SELECT id,item_num,price,$product_fields FROM tbl_products".$where_clause.$orderby_clause." LIMIT $offset,$max_products";
$result1 = mysql_query($sql1);
if (!$result1) {
   echo "Error performing query: " . mysql_error();
   exit();
}
$num_rows1 = mysql_num_rows($result1);
/* Debugging Info */
if ($debugging == 'Y') {
   echo "<div class='debugging'>\n";
   echo "_SESSION['user_type']: ".$_SESSION['user_type']."<br />\n";
   echo "_SESSION['user_type_title']: ".$_SESSION['user_type_title']."<br />\n";
   echo "sql1: $sql1<br />\n";
   echo "where_clause_num: $where_clause_num<br />\n";
   echo "page: $page<br />\n";
   echo "offset: $offset<br />\n";
   echo "num_rows: $num_rows<br />\n";
   echo "num_rows1: $num_rows1<br />\n";
   echo "max_products: $max_products<br />\n";
   echo "image_fullpath: $image_fullpath<br />\n";
   echo "image_path: $image_path<br />\n";
   echo "</div>\n";
}

/* Display category description 
if (!empty($cat_descr)){
   echo "<div id='cat_descr'>$cat_descr</div>\n";
}
*/
echo "<div align='center'><table border='0' cellpadding='0' cellspacing='2' class='product_table'>\n";
if (!empty($_SESSION['brand'])) {
   if (file_exists($logo_fullpath.$_SESSION['brand'].".jpg") || file_exists($logo_fullpath.$_SESSION['brand'].".gif")) {
      if (file_exists($logo_fullpath.$_SESSION['brand'].".jpg")) {
         $logo_src = $logo_path.$_SESSION['brand'].".jpg";
      } else {
      	 $logo_src = $logo_path.$_SESSION['brand'].".gif";
      }
   echo " <tr>\n<td colspan='$num_columns'><img src='$logo_src' border='0' alt='".$_SESSION['brand']." logo'></td>\n</tr>\n";
   }
}
$cell_num = 0;
while ($row1 = @mysql_fetch_array($result1)) {
      $cell_num++;
      if ($cell_num == 1) {
      	 echo " <tr>\n";
      }
      echo "  <td><a href='product.php?id=".$row1['id']."'>";
      if (thumbnail_exists($image_fullpath,$row1['item_num'],1)) {
         $image_src = $image_path.thumbnail_exists($image_fullpath,$row1['item_num'],1);
         $image_info = getimagesize($image_src);
         echo "<img src='$image_src' ".$image_info[3]." border='0' alt='".$row1['id']."' /></a>\n";
      } else {
         if (photo_exists($image_fullpath,$row1['item_num'],1)) {
            $image_src = $image_path.photo_exists($image_fullpath,$row1['item_num'],1);
            $image_info = getimagesize($image_src);
            echo "<img src='$image_src' ".$image_info[3]." border='0' alt='".$row1['id']."' /></a>\n";
         } else {
            echo "<img src='images/no_image.jpg' width='200' height='150' border='0' alt='No image available' /></a>\n";
         }
      }
      echo "<br />\n";
      echo "<a href='product.php?id=".$row1['id']."' class='product_link'>\n";
      $arr_field_limits = explode(',',$field_limits);
      if (!empty($row1[3])) {
         echo "<b>";
         if (!empty($arr_field_limits[0])){
            echo stripslashes(substr($row1[3],0,$arr_field_limits[0]))."...";
	 } else {
 	    echo $row1[3];
	 }
         echo "</b><br />\n";
      }
     /* if (!empty($row1[4])) {
         if (!empty($arr_field_limits[0])){
            echo stripslashes(substr($row1[4],0,$arr_field_limits[0]))."...";
	 } else {
 	    echo $row1[4];
	 }
         echo "<br />\n";
      }
      /*echo "</a>\n";
      echo "<div class='product_descr'>\n";
      echo "<a href='product.php?id=".$row1['id']."'>\n";
      if (!empty($row1[5])) {
         if (!empty($arr_field_limits[1])){
            echo stripslashes(substr($row1[5],0,$arr_field_limits[1]))."...";
	 } else {
 	    echo $row1[5];
	 }
         echo "<br />\n";
      }
      if (!empty($row1[6])) {
         if (!empty($arr_field_limits[2])){
            echo stripslashes(substr($row1[6],0,$arr_field_limits[2]))."...";
	 } else {
 	    echo $row1[6];
	 }
         echo "<br />\n";
      }
      if (!empty($row1[7])) {
         if (!empty($arr_field_limits[3])){
            echo stripslashes(substr($row1[7],0,$arr_field_limits[3]))."...";
	 } else {
 	    echo $row1[7];
	 }
         echo "<br />\n";
      }*/
      if (!empty($price_display)) {
      	 echo $price_display.number_format($row1['price'],2)."\n";
      }
      echo "</a>\n";
      echo "</div>\n";
      echo "  </td>\n";
      if ($cell_num == $num_columns) {
      	 echo " </tr>\n";
      	 $cell_num = 0;
      }
}
if ($cell_num < $num_columns && $cell_num != 0) {
   echo "  <td colspan='".($num_columns - $cell_num)."'></td>\n</tr>\n";
}
/*switch ($cell_num) {
	case 1:
	     echo "  <td colspan='".($num_columns - 1)."'></td>\n</tr>\n";
	break;
	case 2:
	     echo "  <td colspan='".($num_columns - 2)."'></td>\n</tr>\n";
	break;
	case 3:
	     echo "  <td></td>\n</tr>\n";
	break;
	case 4:
	break;
}*/

// Pagination navigation
$prev_page = $page - 1;
$next_page = $page + 1;
if ($num_rows > $max_products) {
   echo " <tr><td colspan='$num_columns' class='product_pagination'><hr width='90%' size='1' noshade='true' />\n";
   if ($page > 1) {
      echo "<a href='$PHP_SELF?page=$prev_page'><< Previous page</a> | ";
   }
   if ($offset+$max_products < $num_rows) {
      echo "<a href='$PHP_SELF?page=$next_page'>Next page >></a>";
   }
   echo "</td></tr>\n";
}
// End the product table
echo "</table></div>\n";

/* Close out the result set */
mysql_free_result($result_num);
mysql_free_result($result1);
if (!empty($result_subcat)) {
   mysql_free_result($result_subcat);
}
/* Closing connection */
mysql_close($dbcnx);

// Include site footer
include $inc_footer_file;
?>
