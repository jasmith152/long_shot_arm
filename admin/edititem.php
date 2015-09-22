<?php
$cfgProgDir = "phpSecurePages/";
include($cfgProgDir."secure.php");

/* Get the store variables and config */
include '../cart_config.php';
if (!empty($_GET['msg'])) { $msg = $_GET['msg']; }
if (!empty($_POST['msg'])) { $msg = $_POST['msg']; }
if (!empty($_GET['error'])) { $error = $_GET['error']; }
if (!empty($_POST['error'])) { $error = $_POST['error']; }
if (!empty($_GET['action'])) { $action = $_GET['action']; }
if (!empty($_POST['action'])) { $action = $_POST['action']; }
if (!empty($_GET['id'])) { $id = $_GET['id']; }
if (!empty($_POST['id'])) { $id = $_POST['id']; }

// Execute requested action
switch ($action) {

   // *** Edit ***
   case 'edit':
      $page_title = "Manage Inventory";
      include 'header.php';
      
      $sql_edit = "SELECT * FROM tbl_products WHERE id = '$id'";
      $result_edit = mysql_query($sql_edit);
      $row_edit = mysql_fetch_array($result_edit);
      //Select list of categories and subcategories
      $result_cat = mysql_query("SELECT DISTINCT category FROM tbl_products ORDER BY category");
      $result_subcat = mysql_query("SELECT DISTINCT subcategory FROM tbl_products ORDER BY subcategory");
      $result_spot = mysql_query("SELECT DISTINCT spotlight FROM tbl_products ORDER BY spotlight");
      
      if (!empty($msg)) {
         echo "<p style='color: blue;'>$msg</p>\n";
      }
      if (!empty($error)) {
         echo "<p style='color: red;'>$msg</p>\n";
      }
      echo "<div align='center'>\n";
      echo "<p class='instructions'>Required fields indicated by *.</p>\n";
      echo "<form action='".$PHP_SELF."' method='post' enctype='multipart/form-data' name='edititem' onsubmit='return submitForm();'>\n";
      echo " <table border='0' cellpadding='2' cellspacing='0' style='font-family: Arial,Helvetica,sans-serif; font-size: 14px;'>\n";
      echo "  <tr>\n";
      echo "   <td align='right' width='250'><input type='submit' name='submit' value='Update' /></td>\n";
      echo "   <td><input type='reset' name='reset' value='Reset' /></td>\n";
      echo "  </tr>\n";
      echo "  <tr>\n";
      echo "   <td colspan='2' align='center'><b><a href='".$PHP_SELF."'><< Cancel, Exit without saving</a></b></td>";
      echo "  </tr>\n";
      echo "  <tr>\n";
      echo "   <td align='right'><b>Title:</b></td>\n";
      echo "   <td><input type='text' size='20' name='title' value=\"".htmlspecialchars($row_edit['title'])."\" /></td>\n";
      echo "  </tr>\n";
      echo "  <tr>\n";
      echo "   <td align='right'><b>Item No.:</b></td>\n";
      echo "   <td><input type='text' size='20' name='item_num' value=\"".htmlspecialchars($row_edit['item_num'])."\" /></td>\n";
      echo "  </tr>\n";
      echo "  <tr>\n";
      echo "   <td align='right'><b>UPC:</b></td>\n";
      echo "   <td><input type='text' size='20' name='upc' value=\"".htmlspecialchars($row_edit['upc'])."\" /></td>\n";
      echo "  </tr>\n";
      echo "  <tr>\n";
      echo "   <td align='right'><b>Weight:</b></td>\n";
      echo "   <td><input type='text' size='5' name='weight' value=\"".htmlspecialchars($row_edit['weight'])."\" /><span class='instructions'>(Should be a decimal value in pounds.)</span></td>\n";
      echo "  </tr>\n";
      echo "  <tr>\n";
      echo "   <td align='right'><b>Brand:</b></td>\n";
      echo "   <td><input type='text' size='20' name='brand' value=\"".htmlspecialchars($row_edit['brand'])."\" /></td>\n";
      echo "  </tr>\n";
      echo "  <tr>\n";
      echo "   <td align='right'><b>Descr:</b></td>\n";
      echo "    <td><textarea name='descr' rows='3' cols='40'>".$row_edit['descr']."</textarea></td>\n";
      echo "  </tr>\n";
      echo "  <tr>\n";
      echo "   <td align='right'><b>Base Price:</b></td>\n";
      echo "   <td><input type='text' size='20' name='price' value='".$row_edit['price']."' /></td>\n";
      echo "  </tr>\n";
      echo "  <tr>\n";
      echo "   <td align='right' valign='top'><b>Option 1</b></td>\n";
      echo "   <td valign='top'>&nbsp;</td>\n";
      echo "  </tr>\n";
      echo "  <tr>\n";
      echo "   <td colspan='2'><span class='instructions'>List selections here for one option. (Ex. If this is the color option, then you would list the colors available.)<br />";
      echo "   Option pricing fields are used to add a specified amount to the base price when the corresponding option is selected. <br /><b>Note:</b>If any options have pricing, then all options must have an amount; zero (0) is allowed.</span></td>\n";
      echo "  </tr>\n";
      echo "  <tr>\n";
      echo "   <td colspan='2' align='center' valign='top'>\n";
      echo "   <table cellspacing='2' cellpadding='2' border='0'>\n";
      //Parse the comma separated string from Option1 and display as separate fields
      $arr_option1 = explode('|',$row_edit['option1']);
      $arr_option1_pricing = explode('|',$row_edit['option1_pricing']);
      $count = count($arr_option1);
      echo "   <tr><td align='right' valign='top'>Option Name: <input type='text' size='20' name='option1[]'";
      if ($count > 0) {
         echo " value='".$arr_option1[0]."'";
      }
      echo " /></td>\n";
      echo "   <td align='left' valign='top'><input type='hidden' name='option1_pricing[]' value='0' />&nbsp;</td></tr>\n";
      for ($i = 1; $i < $count; $i++) {
         echo "   <tr><td align='right' valign='top'><input type='text' size='20' name='option1[]' value='".$arr_option1[$i]."' /></td>\n";
         echo "   <td align='left' valign='top'>+<input type='text' size='5' name='option1_pricing[]' value='".$arr_option1_pricing[$i]."' /></td></tr>\n";
      }
      // Show some blank fields here so that more options can be added
      for ($j = 0; $j < 5; $j++) {
         echo "   <tr><td align='right' valign='top'><input type='text' size='20' name='option1[]' /></td>\n";
         echo "   <td align='left' valign='top'>+<input type='text' size='5' name='option1_pricing[]' /></td></tr>\n";
      }
      echo "   </table>\n";
      echo "   </td>\n";
      echo "  </tr>\n";
      echo "  <tr>\n";
      echo "   <td colspan='2' align='center' valign='top'><strong>Option 2</strong><br />\n";
      echo "   <table cellspacing='2' cellpadding='2' border='0'>\n";
      //Parse the comma separated string from Option2 and display as separate fields
      $arr_option2 = explode('|',$row_edit['option2']);
      $arr_option2_pricing = explode('|',$row_edit['option2_pricing']);
      $count = count($arr_option2);
      echo "   <tr><td align='right' valign='top'>Option Name: <input type='text' size='20' name='option2[]'";
      if ($count > 0) {
         echo " value='".$arr_option2[0]."'";
      }
      echo " /></td>\n";
      echo "   <td align='left' valign='top'><input type='hidden' name='option2_pricing[]' value='0' />&nbsp;</td></tr>\n";
      for ($i = 1; $i < $count; $i++) {
         echo "   <tr><td align='right' valign='top'><input type='text' size='20' name='option2[]' value='".$arr_option2[$i]."' /></td>\n";
         echo "   <td align='left' valign='top'>+<input type='text' size='5' name='option2_pricing[]' value='".$arr_option2_pricing[$i]."' /></td></tr>\n";
      }
      // Show some blank fields here so that more options can be added
      for ($j = 0; $j < 5; $j++) {
         echo "   <tr><td align='right' valign='top'><input type='text' size='20' name='option2[]' /></td>\n";
         echo "   <td align='left' valign='top'>+<input type='text' size='5' name='option2_pricing[]' /></td></tr>\n";
      }
      echo "   </table>\n";
      echo "   </td>\n";
      echo "  </tr>\n";
      echo "  <tr>\n";
      echo "   <td colspan='2' align='center' valign='top'><strong>Option 3</strong><br />\n";
      echo "   <table cellspacing='2' cellpadding='2' border='0'>\n";
      //Parse the comma separated string from Option3 and display as separate fields
      $arr_option3 = explode('|',$row_edit['option3']);
      $arr_option3_pricing = explode('|',$row_edit['option3_pricing']);
      $count = count($arr_option3);
      echo "   <tr><td align='right' valign='top'>Option Name: <input type='text' size='20' name='option3[]'";
      if ($count > 0) {
         echo " value='".$arr_option3[0]."'";
      }
      echo " /></td>\n";
      echo "   <td align='left' valign='top'><input type='hidden' name='option3_pricing[]' value='0' />&nbsp;</td></tr>\n";
      for ($i = 1; $i < $count; $i++) {
         echo "   <tr><td align='right' valign='top'><input type='text' size='20' name='option3[]' value='".$arr_option3[$i]."' /></td>\n";
         echo "   <td align='left' valign='top'>+<input type='text' size='5' name='option3_pricing[]' value='".$arr_option3_pricing[$i]."' /></td></tr>\n";
      }
      // Show some blank fields here so that more options can be added
      for ($j = 0; $j < 5; $j++) {
         echo "   <tr><td align='right' valign='top'><input type='text' size='20' name='option3[]' /></td>\n";
         echo "   <td align='left' valign='top'>+<input type='text' size='5' name='option3_pricing[]' /></td></tr>\n";
      }
      echo "   </table>\n";
      echo "   </td>\n";
      echo "  </tr>\n";
      echo "  <tr>\n";
      echo "   <td colspan='2' align='center' valign='top'><strong>Option 4</strong><br />\n";
      echo "   <table cellspacing='2' cellpadding='2' border='0'>\n";
      //Parse the comma separated string from Option4 and display as separate fields
      $arr_option4 = explode('|',$row_edit['option4']);
      $arr_option4_pricing = explode('|',$row_edit['option4_pricing']);
      $count = count($arr_option4);
      echo "   <tr><td align='right' valign='top'>Option Name: <input type='text' size='20' name='option4[]'";
      if ($count > 0) {
         echo " value='".$arr_option4[0]."'";
      }
      echo " /></td>\n";
      echo "   <td align='left' valign='top'><input type='hidden' name='option4_pricing[]' value='0' />&nbsp;</td></tr>\n";
      for ($i = 1; $i < $count; $i++) {
         echo "   <tr><td align='right' valign='top'><input type='text' size='20' name='option4[]' value='".$arr_option2[$i]."' /></td>\n";
         echo "   <td align='left' valign='top'>+<input type='text' size='5' name='option4_pricing[]' value='".$arr_option2_pricing[$i]."' /></td></tr>\n";
      }
      // Show some blank fields here so that more options can be added
      for ($j = 0; $j < 5; $j++) {
         echo "   <tr><td align='right' valign='top'><input type='text' size='20' name='option4[]' /></td>\n";
         echo "   <td align='left' valign='top'>+<input type='text' size='5' name='option4_pricing[]' /></td></tr>\n";
      }
      echo "   </table>\n";
      echo "   </td>\n";
      echo "  </tr>\n";
      echo "  <tr>\n";
      echo "   <td align='right'><b>Category:</b></td>\n";
      echo "   <td><select name='category' size='1'>";
      while ($row_cat = mysql_fetch_array($result_cat)) {
            echo "<option value='".$row_cat['category']."'";
            if ($row_cat['category'] == $row_edit['category']) { echo " selected"; }
            echo ">".$row_cat['category']."</option>\n";
      }
      echo "</td>\n";
      echo "  </tr>\n";
      echo "  <tr>\n";
      echo "   <td align='right'><b>New Category:</b></td>\n";
      echo "   <td><input type='text' size='20' name='newcat' /></td>\n";
      echo "  </tr>\n";
      echo "  <tr>\n";
      echo "   <td align='right'><b>Subcategory:</b></td>\n";
      echo "   <td><select name='subcategory' size='1'>";
      while ($row_subcat = mysql_fetch_array($result_subcat)) {
            echo "<option value='".$row_subcat['subcategory']."'";
            if ($row_subcat['subcategory'] == $row_edit['subcategory']) { echo " selected"; }
            echo ">".$row_subcat['subcategory']."</option>\n";
      }
      echo "</td>\n";
      echo "  </tr>\n";
      echo "  <tr>\n";
      echo "   <td align='right'><b>New Subcategory:</b></td>\n";
      echo "   <td><input type='text' size='20' name='newsubcat' /></td>\n";
      echo "  </tr>\n";
      echo "  <tr>\n";
      echo "   <td align='right'><b>Spotlight:</b></td>\n";
      echo "   <td><select name='spotlight' size='1'>";
      while ($row_spot = mysql_fetch_array($result_spot)) {
            echo "<option value='".$row_spot['spotlight']."'";
            if ($row_spot['spotlight'] == $row_edit['spotlight']) { echo " selected"; }
            echo ">".$row_spot['spotlight']."</option>\n";
      }
      echo "</td>\n";
      echo "  </tr>\n";
      echo "  <tr>\n";
      echo "   <td align='right'><b>New Spotlight:</b></td>\n";
      echo "   <td><input type='text' size='20' name='newspot' /></td>\n";
      echo "  </tr>\n";
      for ($i=1; $i<=$num_photos; $i++) {
         if (photo_exists($image_fullpath,$row_edit['item_num'],$i)) {
            echo " <tr>\n";
            echo "  <td align='right'>Photo $i: </td>\n";
            echo "  <td><a class='preview_link' href=\"javascript:ImgWindow('../".$image_path.photo_exists($image_fullpath,$row_edit['item_num'],$i)."','Image','400','300','20','front');\">".photo_exists($image_fullpath,$row_edit['item_num'],$i)."</a>\n";
            echo "  &nbsp;<a href='$PHP_SELF?action=delete-photo&id=".$row_edit['item_num']."&photo_num=$i' onclick=\"confirmPhotoMsg('".$row_edit['item_num']."','$i');return false;\"><img src='icon_delete.gif' alt='Delete Item' width='12' height='12' border='0' /></a>\n";
            echo "  </td>\n";
            echo " </tr>\n";
            echo " <tr>\n";
            echo "  <td align='right'>Replace Photo $i: </td><td><input type='file' size='20' name='imgfile$i' />";
         } else {
            echo " <tr>\n";
            echo "  <td align='right'>Photo $i: </td><td><input type='file' size='20' name='imgfile$i' />";
         }
         if ($i == 1) {
            echo "<br /><span class='instructions'>Please use .gif .jpg or .png images only. Images should be approx. 480 pixels wide.</span>";
         }
         echo "</td>\n";
         echo " </tr>\n";
         if (photo_exists($image_fullpath,$row_edit['item_num'],$i.'_sm')) {
            echo " <tr>\n";
            echo "  <td align='right'>Photo $i thumbnail: </td>\n";
            echo "  <td><a class='preview_link' href=\"javascript:ImgWindow('../".$image_path.photo_exists($image_fullpath,$row_edit['item_num'],$i.'_sm')."','Image','400','300','20','front');\">".photo_exists($image_fullpath,$row_edit['item_num'],$i.'_sm')."</a>\n";
            echo "  &nbsp;<a href='$PHP_SELF?action=delete-photo&id=".$row_edit['item_num']."&photo_num=".$i.'_sm'."' onclick=\"confirmPhotoMsg('".$row_edit['item_num']."','$i.'_sm'');return false;\"><img src='icon_delete.gif' alt='Delete Item' width='12' height='12' border='0' /></a>\n";
            echo "  </td>\n";
            echo " </tr>\n";
            echo " <tr>\n";
            echo "  <td align='right'>Replace Photo $i thumbnail: </td><td><input type='file' size='20' name='tnfile$i' />";
         } else {
            echo " <tr>\n";
            echo "  <td align='right'>Photo $i thumbnail: </td><td><input type='file' size='20' name='tnfile$i' />";
         }
         if ($i == 1) {
            echo "<br /><span class='instructions'>Please use .gif .jpg or .png images only. Images should be approx. 200x150.</span>";
         }
         echo "</td>\n";
         echo " </tr>\n";
      }
      echo "  <tr>\n";
      echo "   <td align='right'><b>Status:</b></td>\n";
      echo "   <td><select name='status' size='1' />\n";
      echo "   <option value='In Stock'";
      if ($row_edit['status'] == 'In Stock') { echo " selected"; }
      echo ">In Stock</option>\n";
      echo "   <option value='Out of Stock'";
      if ($row_edit['status'] == 'Out of Stock') { echo " selected"; }
      echo ">Out of Stock</option>\n";
      echo "   <option value='Active'";
      if ($row_edit['status'] == 'Active') { echo " selected"; }
      echo ">Active</option>\n";
      echo "   <option value='Inactive'";
      if ($row_edit['status'] == 'Inactive') { echo " selected"; }
      echo ">Inactive</option>\n";
      echo "   </select></td>\n";
      echo "  </tr>\n";
      if (!empty($user_types)) {
         echo "  <tr>\n";
         echo "   <td align='right'><b>User Types:</b></td>\n";
         echo "   <td><select name='user_types' size='1' />\n";
         foreach ($user_types as $key => $type) {
            echo "   <option value='$key'";
            if ($row_edit['user_types'] == $key) { echo " selected"; }
            echo ">$type</option>\n";
         }
         echo "   </select></td>\n";
         echo "  </tr>\n";
      }
      echo "  <tr>\n";
      echo "   <td align='right'><b>Sort No.:</b></td>\n";
      echo "   <td><input type='text' size='5' name='sort_num' value='".$row_edit['sort_num']."' /></td>\n";
      echo "  </tr>\n";
      if (logo_exists($logo_fullpath,$row_edit['brand'])) {
         echo " <tr>\n";
         echo "  <td align='right'>Brand Logo: </td>\n";
         echo "  <td><a class='preview_link' href=\"javascript:ImgWindow('../".$logo_path.logo_exists($logo_fullpath,$row_edit['brand'])."','Image','400','300','20','front');\">".logo_exists($logo_fullpath,$row_edit['brand'])."</a>\n";
         echo "  </td>\n";
         echo " </tr>\n";
         echo " <tr>\n";
         echo "  <td align='right'>Replace Brand Logo: </td><td><input type='file' size='20' name='imgfile-logo' />";
      } else {
         echo " <tr>\n";
         echo "  <td align='right'>Brand Logo: </td><td><input type='file' size='20' name='imgfile-logo' />";
         echo "<br /><span class='instructions'>Please use .gif .jpg or .png images only.</span>";
      }
      echo "  </td>\n";
      echo " </tr>\n";
      echo "  <tr>\n";
      echo "   <td align='right'><input type='submit' name='submit' value='Update' /></td>\n";
      echo "   <td><input type='reset' name='reset' value='Reset' /></td>\n";
      echo "  </tr>\n";
      echo "  <tr>\n";
      echo "   <td colspan='2' align='center'><b><a href='".$PHP_SELF."'><< Cancel, Exit without saving</a></b></td>";
      echo "  </tr>\n";
      echo " </table>\n";
      echo "<input type='hidden' name='action' value='update' />\n";
      echo "<input type='hidden' name='id' value='".$row_edit['id']."' />\n";
      echo "</form>\n";
      echo "</div>\n";
      
      include 'footer.php';
      
      /* Close out the result set */
      mysql_free_result($result_edit);
      mysql_free_result($result_cat);
      mysql_free_result($result_subcat);
      mysql_free_result($result_spot);
   break;

   // *** Update Sort ***
   case 'update_sort':
      //Create Where clause and set session variables
      if (!empty($_POST['cat'])) {
         if ($where_clause_num > 0) { $where_clause .= " AND"; }
         $where_clause .= " category = '".$_POST['cat']."'";
         $where_clause_num++;
      }
      if (!empty($_POST['subcat'])) {
         if ($where_clause_num > 0) { $where_clause .= " AND"; }
         $where_clause .= " subcategory = '".$_POST['subcat']."'";
         $where_clause_num++;
      }
      if (!empty($_POST['brand'])) {
         if ($where_clause_num > 0) { $where_clause .= " AND"; }
         $where_clause .= " brand = '".$_POST['brand']."'";
         $where_clause_num++;
      }
      if (!empty($_POST['spot'])) {
         if ($where_clause_num > 0) { $where_clause .= " AND"; }
         $where_clause .= " spotlight = '".$_POST['spot']."'";
         $where_clause_num++;
      }
      if ($where_clause_num > 0) { $where_clause = " WHERE".$where_clause; }
      $sql = "SELECT id,sort_num,category,subcategory,spotlight,brand FROM tbl_products".$where_clause;
      $result = mysql_query($sql);
      if (!$result) {
         echo "Error performing query: " . mysql_error();
         echo "sql: $sql<br />\n";
         exit();
      }
      $num_rows = mysql_num_rows($result);
      while ($row = mysql_fetch_array($result)) {
         $sort_id = "sort_".$row['id'];
         // Update the record in the database
         $sql_update = "UPDATE tbl_products SET sort_num = '".$$sort_id."'";
         $sql_update .= " WHERE id='".$row['id']."'";
         if (!mysql_query($sql_update)) {
            echo("<p>Error updating item: " . mysql_error() . "</p>");
         }
         /* Debugging Info */
         if ($debugging == 'Y') {
            echo "<div class='debugging'>\n";
            echo "sql: $sql<br />\n";
            echo "where_clause: $where_clause<br />\n";
            echo "num_rows: $num_rows<br />\n";
            echo "sort_id: $sort_id<br />\n";
            echo "$sort_id: ".$$sort_id."<br />\n";
            echo "sql_update: $sql_update<br />\n";
            echo "</div>\n";
         }

         // Destroy the recycled variables
         unset($sort_id,$sql_update);
      }

      // Free any results & close Db connection
      if (!empty($result)) {
         mysql_free_result($result);
      }
      mysql_close($dbcnx);

      // Send user back to cart to view changes
      header("Location: ".$PHP_SELF."?msg=Sort order has been updated.");
      exit();
   break;

   // *** Update ***
   case 'update':
      if ($_POST['price']==''){
         header("Location: ".$PHP_SELF."?action=edit&id=$id&msg=Required field missing.");
         exit;
      }

      // Prepare the option arrays to be inserted into the db
      $option1 = implode('|', array_filter($_POST['option1']));
      $option1_pricing = implode('|', array_filter($_POST['option1_pricing'],"strlen"));
      $option2 = implode('|', array_filter($_POST['option2']));
      $option2_pricing = implode('|', array_filter($_POST['option2_pricing'],"strlen"));
      $option3 = implode('|', array_filter($_POST['option3']));
      $option3_pricing = implode('|', array_filter($_POST['option3_pricing'],"strlen"));
      $option4 = implode('|', array_filter($_POST['option4']));
      $option4_pricing = implode('|', array_filter($_POST['option4_pricing'],"strlen"));

      // Check for empty or new category, subcategory or spotlight
      if (empty($_POST['category']) && empty($_POST['newcat'])) {
         $category = 'None';
      } else {
         if (!empty($_POST['newcat'])) {
            $category = $_POST['newcat'];
         } else {
            $category = $_POST['category'];
         }
      }
      if (empty($_POST['subcategory']) && empty($_POST['newsubcat'])) {
         $subcategory = 'None';
      } else {
         if (!empty($_POST['newsubcat'])) {
            $subcategory = $_POST['newsubcat'];
         } else {
            $subcategory = $_POST['subcategory'];
         }
      }
      if (empty($_POST['spotlight']) && empty($_POST['newspot'])) {
         $spotlight = 'None';
      } else {
         if (!empty($_POST['newspot'])) {
            $spotlight = $_POST['newspot'];
         } else {
            $spotlight = $_POST['spotlight'];
         }
      }

      // Grab uploaded images
      for ($i=1; $i<=$num_photos; $i++) {
         if (!empty($_FILES['imgfile'.$i]['name']) && (strtolower(substr($_FILES['imgfile'.$i]['name'],-4,4)) == '.gif' || strtolower(substr($_FILES['imgfile'.$i]['name'],-4,4)) == '.jpg' || strtolower(substr($_FILES['imgfile'.$i]['name'],-4,4)) == '.png')) {
            $img_name{$i} = $_POST['item_num'].'-'.$i.substr($_FILES['imgfile'.$i]['name'],-4,4);
            $uploadimg{$i} = $image_fullpath.$img_name{$i};
   
            if (move_uploaded_file($_FILES['imgfile'.$i]['tmp_name'], $uploadimg{$i})) {
               //print "File is valid, and was successfully uploaded. ";
               //print "Here's some more debugging info:\n";
               //print_r($_FILES);
               shell_exec('chmod a+r '.$uploadimg{$i});
            } else {
               print "Possible file upload attack!  Here's some debugging info:\n";
               print_r($_FILES);
               exit;
            }
         }
         if (!empty($_FILES['tnfile'.$i]['name']) && (strtolower(substr($_FILES['tnfile'.$i]['name'],-4,4)) == '.gif' || strtolower(substr($_FILES['tnfile'.$i]['name'],-4,4)) == '.jpg' || strtolower(substr($_FILES['tnfile'.$i]['name'],-4,4)) == '.png')) {
            $tn_name{$i} = $_POST['item_num'].'-'.$i.'_sm'.substr($_FILES['tnfile'.$i]['name'],-4,4);
            $uploadtn{$i} = $image_fullpath.$tn_name{$i};
   
            if (move_uploaded_file($_FILES['tnfile'.$i]['tmp_name'], $uploadtn{$i})) {
               //print "File is valid, and was successfully uploaded. ";
               //print "Here's some more debugging info:\n";
               //print_r($_FILES);
               shell_exec('chmod a+r '.$uploadtn{$i});
            } else {
               print "Possible file upload attack!  Here's some debugging info:\n";
               print_r($_FILES);
               exit;
            }
         }
      }
      if (!empty($_FILES['imgfile-logo']['name'])) {
         $img_name = $brand.substr($_FILES['imgfile-logo']['name'],-4,4);
         $uploadimg = $logo_fullpath.$img_name;

         if (move_uploaded_file($_FILES['imgfile-logo']['tmp_name'], $uploadimg)) {
            //print "File is valid, and was successfully uploaded. ";
            //print "Here's some more debugging info:\n";
            //print_r($_FILES);
            shell_exec('chmod a+r ../'.$logo_path.$img_name);
         } else {
            print "Possible file upload attack!  Here's some debugging info:\n";
            print_r($_FILES);
            exit;
         }
      }
      // Update the record in the database
      $sql_update = "UPDATE tbl_products SET 
                   title='".addslashes($_POST['title'])."', 
                   item_num='".addslashes($_POST['item_num'])."', 
                   sort_num='".addslashes($_POST['sort_num'])."', 
                   upc='".addslashes($_POST['upc'])."', 
                   weight='".addslashes($_POST['weight'])."', 
                   descr='".addslashes($_POST['descr'])."', 
                   price='".addslashes($_POST['price'])."', 
                   brand='".addslashes($_POST['brand'])."', 
                   option1='".addslashes($option1)."', 
                   option1_pricing='".addslashes($option1_pricing)."', 
                   option2='".addslashes($option2)."', 
                   option2_pricing='".addslashes($option2_pricing)."', 
                   option3='".addslashes($option3)."', 
                   option3_pricing='".addslashes($option3_pricing)."', 
                   option4='".addslashes($option4)."', 
                   option4_pricing='".addslashes($option4_pricing)."', 
                   category='".addslashes($category)."', 
                   subcategory='".addslashes($subcategory)."', 
                   spotlight='".addslashes($spotlight)."', 
                   user_types='".addslashes($_POST['user_types'])."', 
                   status='".addslashes($_POST['status'])."'";
      $sql_update .= " WHERE id='$id'";

      if (@mysql_query($sql_update)) {
         header("Location: ".$PHP_SELF."?msg=Item updated.");
         exit;
      } else {
         echo("<p>Error updating item: " . mysql_error() . "</p>");
      }
      /* Debugging Info */
      if ($debugging == 'Y') {
         echo "<div class='debugging'>\n";
         echo "option1: $option1<br />\n";
         echo "option1_pricing: $option1_pricing<br />\n";
         echo "</div>\n";
      }
   break;

   // *** Delete ***
   case 'delete':
      for ($i=1; $i<=$num_photos; $i++) {
         // Delete old image file
         if (photo_exists($image_fullpath,$item_num,$i)) {
            if (unlink($image_fullpath.photo_exists($image_fullpath,$item_num,$i))) {
               
            } else {
               echo "Error deleting image. ".photo_exists($image_fullpath,$item_num,$i)."<br />\n";
               exit;
            }
         }
         // Delete old thumbnail file
         if (photo_exists($image_fullpath,$item_num,$i.'_sm')) {
            if (unlink($image_fullpath.photo_exists($image_fullpath,$item_num,$i.'_sm'))) {
               
            } else {
               echo "Error deleting image. ".photo_exists($image_fullpath,$item_num,$i.'_sm')."<br />\n";
               exit;
            }
         }
      }
      // Delete record from db
      $sql_delete = "DELETE FROM tbl_products WHERE id='$id'";
      if (@mysql_query($sql_delete)) {
         header("Location: ".$PHP_SELF."?msg=Item deleted.");
         exit;
      } else {
         echo("<p>Error deleting item: " . mysql_error() . "</p>");
      }
   break;

   // *** Delete Photo ***
   case 'delete-photo':
      if (photo_exists($image_fullpath,$item_num,$photo_num)) {
         if (unlink($image_fullpath.photo_exists($image_fullpath,$item_num,$photo_num))) {
            header("Location: $PHP_SELF?action=edit&id=".$id."&msg=Photo deleted.");
            exit;
         } else {
            echo "Error deleting image. ".photo_exists($uploaddir_abs,$item_num,$photo_num)."<br />\n";
            exit;
         }
      }
   break;

   // *** Default ***
   default:

      $page_title = "Update Inventory Item";
      include 'header.php';
      
      /* Capture the search variables */
      $cat = $_POST['cat'];
      $subcat = $_POST['subcat'];
      $brand = $_POST['brand'];
      $spot = $_POST['spot'];
      
      /* Variables for testing
      $where_clause_num = 0;
      $cat = "";
      $subcat = "";
      $brand = ""; */
      
      if (!empty($_POST['submit_reset'])) {
         $cat = '';
         $subcat = '';
         $spot = '';
         $brand = '';
      }
      
      /* Display search box */
      //Select list of categories and subcategories
      $result_cat = mysql_query("SELECT DISTINCT category FROM tbl_products ORDER BY category");
      if (!empty($_POST['cat'])) {
         $result_subcat = mysql_query("SELECT DISTINCT category,subcategory FROM tbl_products WHERE category = '$cat' ORDER BY subcategory");
      }
      $result_spot = mysql_query("SELECT DISTINCT spotlight FROM tbl_products ORDER BY spotlight");
      $result_brand = mysql_query("SELECT DISTINCT brand FROM tbl_products ORDER BY brand");
      
      echo "<form action='".$PHP_SELF."' method='post' name='product_search'>\n";
      echo "<table width='500' border='0' cellspacing='0' cellpadding='2' style='font-family: Arial,Helvetica,sans-serif;font-size: 14px;color: #000;border: 1px solid #000;'>\n";
      echo "<tr style='background-color: #CCC;font-size: 16px;border-bottom: 1px solid #000;'>\n";
      echo "<td><b>Search for Products</b></td>\n";
      echo "</tr>\n";
      echo "<tr style='background-color: #999;'>\n";
      echo "<td><b>By Category</b></td>\n";
      echo "</tr>\n";
      echo "<tr style='background-color: #CCC;border-bottom: 1px solid #000;'>\n";
      echo "<td>Category: <select name='cat' size='1'>\n";
      echo "<option value=''>-Select-</option>\n";
      while ($row_cat = mysql_fetch_array($result_cat)) {
            echo "<option value='".$row_cat['category']."'";
            if ($row_cat['category'] == $cat) {
               echo " selected";
            }
            echo ">".$row_cat['category']."</option>\n";
      }
      echo "</select>&nbsp;&nbsp;";
      echo "Subcategory: <select name='subcat' size='1'";
      if (empty($cat)) {
         echo "disabled>\n";
         echo "<option value=''>-Select Category first-</option>\n";
      } else {
         echo ">\n";
         echo "<option value=''>-Select-</option>\n";
         while ($row_subcat = mysql_fetch_array($result_subcat)) {
               echo "<option value='".$row_subcat['subcategory']."'";
               if ($row_subcat['subcategory'] == $subcat) {
                  echo " selected";
               }
               echo ">".$row_subcat['subcategory']."</option>\n";
         }
      }
      echo "</select><br />\n";
      echo "Spotlight: <select name='spot' size='1'>\n";
      echo "<option value=''>-Select-</option>\n";
      while ($row_spot = mysql_fetch_array($result_spot)) {
            echo "<option value='".$row_spot['spotlight']."'";
            if ($row_spot['spotlight'] == $spot) {
               echo " selected";
            }
            echo ">".$row_spot['spotlight']."</option>\n";
      }
      echo "</select>";
      echo "</td>\n";
      echo "<tr style='background-color: #999;'>\n";
      echo "<td><b>Or By Brand</b> <select name='brand' size='1'>\n";
      echo "<option value=''>-Select-</option>\n";
      while ($row_brand = mysql_fetch_array($result_brand)) {
            echo "<option value='".$row_brand['brand']."'";
            if ($row_brand['brand'] == $brand) {
               echo " selected";
            }
            echo ">".$row_brand['brand']."</option>\n";
      }
      echo "</select></td>\n";
      echo "</tr>\n";
      echo "<tr style='background-color: #CCC;'>\n";
      echo "<td><input type='submit' name='submit' value='Search' />&nbsp;<input type='submit' name='submit_reset' value='Reset All' />&nbsp;&nbsp;<span style='font-size: 12px;font-style: italic;'>Tip: Try resetting fields in your search to get more results.</span></td>\n";
      echo "</tr>\n";
      echo "</table>\n";
      echo "</form>\n";
      /* End Search box */
      
      /* Display any messages needed */
      if (!empty($msg)) {
         echo "<p align='center' style='color: blue;'>$msg</p>\n";
      }
      if (!empty($error)) {
         echo "<p align='center' style='color: red;'>$error</p>\n";
      }
      
      /* Stop here if there are any errors */
      if ($exit > 0) {
         exit;
      }
      
      If (!empty($_POST['submit']) || !empty($_POST['cat']) || !empty($_POST['subcat']) || !empty($_POST['brand']) || !empty($_POST['spot'])) {
      
         if (!empty($_POST['cat'])) {
            if ($where_clause_num > 0) { $where_clause .= " AND"; }
            $where_clause .= " category = '".$_POST['cat']."'";
            $where_clause_num++;
         }
         if (!empty($_POST['subcat'])) {
            if ($where_clause_num > 0) { $where_clause .= " AND"; }
            $where_clause .= " subcategory = '".$_POST['subcat']."'";
            $where_clause_num++;
         }
         if (!empty($_POST['brand'])) {
            if ($where_clause_num > 0) { $where_clause .= " AND"; }
            $where_clause .= " brand = '".$_POST['brand']."'";
            $where_clause_num++;
         }
         if (!empty($_POST['spot'])) {
            if ($where_clause_num > 0) { $where_clause .= " AND"; }
            $where_clause .= " spotlight = '".$_POST['spot']."'";
            $where_clause_num++;
         }
         if (!empty($where_clause)) {
            $where_clause = " WHERE".$where_clause;
         }
         
         $sql = "SELECT id,item_num,sort_num,title,price,category,subcategory,spotlight,user_types FROM tbl_products".$where_clause." ORDER BY category,title,sort_num";
         $result = mysql_query($sql);
         if (!$result) {
            echo "Error performing query: " . mysql_error();
            exit();
         }
         $num_rows = mysql_num_rows($result);
         
         /* Debugging Info */
         if ($debugging == 'Y') {
            echo "<div class='debugging'>\n";
            echo "sql: $sql<br />\n";
            echo "where_clause_num: $where_clause_num<br />\n";
            echo "num_rows: $num_rows<br />\n";
            echo "</div>\n";
         }
         
         echo "<form action='".$PHP_SELF."' name='update_sort' method='post'>\n";
         echo "<input type='hidden' name='action' value='update_sort' />\n";
         echo "<table border='0' cellpadding='2' cellspacing='0' style='font-family: Arial,Helvetica,sans-serif;font-size: 14px;'>\n";
         echo " <tr>\n";
         echo "  <td><b>Item No.</b></td>\n";
         echo "  <td><b>Sort No.</b></td>\n";
         echo "  <td><b>Title</b></td>\n";
         echo "  <td><b>Category</b></td>\n";
         echo "  <td><b>Subcategory</b></td>\n";
         if (!empty($user_types)) {
            echo "  <td><b>User Type</b></td>\n";
         }
         echo "  <td><b>Price</b></td>\n";
         echo "  <td><b>Edit/Delete</b></td>\n";
         echo " </tr>\n";
         while ($row = @mysql_fetch_array($result)) {
            echo " <tr>\n";
            echo "  <td>".$row['item_num']."</td>\n";
            echo "  <td><input type='text' size='4' name='sort_".$row['id']."' value='".$row['sort_num']."' /></td>\n";
            echo "  <td>".stripslashes($row['title'])."</td>\n";
            echo "  <td>".$row['category']."</td>\n";
            echo "  <td>".$row['subcategory']."</td>\n";
            if (!empty($user_types)) {
               echo "  <td>".$row['user_types']."</td>\n";
            }
            echo "  <td>".$row['price']."</td>\n";
            echo "  <td><a href='".$PHP_SELF."?action=edit&id=".$row['id']."'>Edit</a> / <a href='".$PHP_SELF."?action=delete&id=".$row['id']."' onclick=\"confirmMsg('".$row['id']."');return false;\">Delete</a></td>\n";
            echo " </tr>\n";
         }
         echo "</table>\n";
         echo "<input type='submit' name='submit' value='Update Sort' /> <input type='reset' name='reset' value='Reset' />\n";
         echo "</form>\n";
         
         /* Close out the result set */
         mysql_free_result($result);
      }
      include 'footer.php';
      
      if (!empty($result_cat)) {
         mysql_free_result($result_cat);
      }
      if (!empty($result_subcat)) {
         mysql_free_result($result_subcat);
      }
      if (!empty($result_brand)) {
         mysql_free_result($result_brand);
      }
   // End the switch
   break;
}

/* Closing connection */
mysql_close($dbcnx);
?>
