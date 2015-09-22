<?php
$cfgProgDir = "phpSecurePages/";
include($cfgProgDir."secure.php");

/* Get the store variables and config */
include '../cart_config.php';
if (!empty($_GET['msg'])) { $msg = $_GET['msg']; }
if (!empty($_POST['msg'])) { $msg = $_POST['msg']; }
if (!empty($_GET['error'])) { $error = $_GET['error']; }
if (!empty($_POST['error'])) { $error = $_POST['error']; }

// Start the page
$page_title = "Manage Inventory";
include 'header.php';

if (!empty($_POST['submit'])) {

   if (($_POST['price']=='')){
      header("Location: ".$_SERVER['PHP_SELF']."?msg=Required field missing.");
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

   // Check for empty Category, Subcategory, or Spotlight
   if (empty($_POST['category'])) {
      $category = 'None';
   } else {
      $category = $_POST['category'];
   }
   if (empty($_POST['subcategory'])) {
      $subcategory = 'None';
   } else {
      $subcategory = $_POST['subcategory'];
   }
   if (empty($_POST['spotlight'])) {
      $spotlight = 'None';
   } else {
      $spotlight = $_POST['spotlight'];
   }

   // Check for new Category, Subcategory, or Spotlight
   if (!empty($_POST['newcat'])) {
      $category = $_POST['newcat'];
   }
   if (!empty($_POST['newsubcat'])) {
      $subcategory = $_POST['newsubcat'];
   }
   if (!empty($_POST['newspot'])) {
      $spotlight = $_POST['newspot'];
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
  // Grab uploaded brand logo
  if (!empty($_FILES['logofile']['name']) && (strtolower(substr($_FILES['logofile']['name'],-4,4)) == '.gif' || strtolower(substr($_FILES['logofile']['name'],-4,4)) == '.jpg' || strtolower(substr($_FILES['logofile']['name'],-4,4)) == '.png')) {
     $img_name = $_POST['brand'].substr($_FILES['logofile']['name'],-4,4);
     $uploadimg = $logo_fullpath.$img_name;
     
     if (move_uploaded_file($_FILES['logofile']['tmp_name'], $uploadimg)) {
        //print "File is valid, and was successfully uploaded. ";
        //print "Here's some more debugging info:\n";
        //print_r($_FILES);
        shell_exec('chmod a+r '.$uploadimg);
     } else {
        print "Possible file upload attack!  Here's some debugging info:\n";
        print_r($_FILES);
        exit;
     }
  }

   // Insert a record
   $sql = "INSERT INTO tbl_products SET 
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
   if (@mysql_query($sql)) {
      $msg = "Item has been added.";
   } else {
      $error = "Error adding item: " . mysql_error();
   }

   /* Debugging Info */
   if ($debugging == 'Y') {
      echo "<div class='debugging'>\n";
      echo "option1: $option1<br />\n";
      echo "option1_pricing: $option1_pricing<br />\n";
      for ($i=1; $i<=$num_photos; $i++) {
         echo "Imgfile$i: ".$_FILES['imgfile'.$i]['name']."<br />\n";
      }
      echo "</div>\n";
   }

}

//Select list of categories and subcategories
$result_cat = mysql_query("SELECT DISTINCT category FROM tbl_products ORDER BY category");
$result_subcat = mysql_query("SELECT DISTINCT subcategory FROM tbl_products ORDER BY subcategory");
$result_spot = mysql_query("SELECT DISTINCT spotlight FROM tbl_products ORDER BY spotlight");

if (!empty($msg)) {
   echo "<p style='color: blue;'>$msg</p>\n";
}
if (!empty($error)) {
   echo "<p style='color: red;'>$error</p>\n";
}
echo "<div align='center'>\n";
echo "<p class='instructions'>Required fields indicated by *.</p>\n";
echo "<form action='".$_SERVER['PHP_SELF']."' method='post' enctype='multipart/form-data' name='edititem' onsubmit='return submitForm();'>\n";
echo " <table border='0' width='780' cellpadding='2' cellspacing='0' style='font-family: Arial,Helvetica,sans-serif; font-size: 14px;'>\n";
echo "  <tr>\n";
echo "   <td width='250' align='right'><b>Title:</b></td>\n";
echo "   <td width='530'><input type='text' size='20' name='title' /></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "   <td align='right'><b>Item No.:</b></td>\n";
echo "   <td><input type='text' size='20' name='item_num' /></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "   <td align='right'><b>UPC:</b></td>\n";
echo "   <td><input type='text' size='20' name='upc' /></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "   <td align='right'><b>Weight:</b></td>\n";
echo "   <td><input type='text' size='5' name='weight' /><span class='instructions'>(Should be a decimal value in pounds.)</span></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "   <td align='right'><b>Brand:</b></td>\n";
echo "   <td><input type='text' size='20' name='brand' /></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "   <td align='right'><b>Descr:</b></td>\n";
echo "   <td><textarea name='descr' rows='3' cols='40'></textarea>\n";
echo "   </td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "   <td align='right'><b>Base Price:</b></td>\n";
echo "   <td><input type='text' size='20' name='price' /></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "   <td align='right' valign='top'><b>Option 1</b></td>\n";
echo "   <td valign='top'>&nbsp;</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "   <td colspan='2'><span class='instructions'>List selections here for one option. (Ex. If this is the color option, then you would list the colors available.)<br />";
echo "   Option pricing fields are used to add a specified amount to the base price when the corresponding option is selected. <b>Note:</b>If any options have pricing, then all options must have an amount; zero (0) is allowed.</span></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "   <td colspan='2' align='center' valign='top'>\n";
echo "   <table cellspacing='2' cellpadding='2' border='0'>\n";
echo "   <tr><td align='right' valign='top'>Option Name: <input type='text' size='20' name='option1[]' value='- Select Color -' onFocus=\"this.value=''\" /></td>\n";
echo "   <td><input type='hidden' name='option1_pricing[]' value='0' />&nbsp;</td></tr>\n";
// Show some blank fields here so that more options can be added
for ($j = 0; $j < 5; $j++) {
   echo "   <tr><td align='right' valign='top'><input type='text' size='20' name='option1[]' /></td>\n";
   echo "   <td align='left' valign='top'>+<input type='text' size='5' name='option1_pricing[]' /></td></tr>\n";
}
echo "   </table>\n";
echo "   </td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "   <td align='right' valign='top'><b>Option 2</b></td>\n";
echo "   <td valign='top'>&nbsp;</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "   <td colspan='2' align='center' valign='top'>\n";
echo "   <table cellspacing='2' cellpadding='2' border='0'>\n";
echo "   <tr><td align='right' valign='top'>Option Name: <input type='text' size='20' name='option2[]' value='- Select Color -' onFocus=\"this.value=''\" /></td>\n";
echo "   <td><input type='hidden' name='option2_pricing[]' value='0' />&nbsp;</td></tr>\n";
// Show some blank fields here so that more options can be added
for ($j = 0; $j < 5; $j++) {
   echo "   <tr><td align='right' valign='top'><input type='text' size='20' name='option2[]' /></td>\n";
   echo "   <td align='left' valign='top'>+<input type='text' size='5' name='option2_pricing[]' /></td></tr>\n";
}
echo "   </table>\n";
echo "   </td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "   <td align='right' valign='top'><b>Option 3</b></td>\n";
echo "   <td valign='top'>&nbsp;</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "   <td colspan='2' align='center' valign='top'>\n";
echo "   <table cellspacing='2' cellpadding='2' border='0'>\n";
echo "   <tr><td align='right' valign='top'>Option Name: <input type='text' size='20' name='option3[]' value='- Select Color -' onFocus=\"this.value=''\" /></td>\n";
echo "   <td><input type='hidden' name='option3_pricing[]' value='0' />&nbsp;</td></tr>\n";
// Show some blank fields here so that more options can be added
for ($j = 0; $j < 5; $j++) {
   echo "   <tr><td align='right' valign='top'><input type='text' size='20' name='option3[]' /></td>\n";
   echo "   <td align='left' valign='top'>+<input type='text' size='5' name='option3_pricing[]' /></td></tr>\n";
}
echo "   </table>\n";
echo "   </td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "   <td align='right' valign='top'><b>Option 4</b></td>\n";
echo "   <td valign='top'>&nbsp;</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "   <td colspan='2' align='center' valign='top'>\n";
echo "   <table cellspacing='2' cellpadding='2' border='0'>\n";
echo "   <tr><td align='right' valign='top'>Option Name: <input type='text' size='20' name='option4[]' value='- Select Color -' onFocus=\"this.value=''\" /></td>\n";
echo "   <td><input type='hidden' name='option4_pricing[]' value='0' />&nbsp;</td></tr>\n";
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
   echo "<option value='".$row_cat['category']."'>".$row_cat['category']."</option>\n";
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
   echo "<option value='".$row_subcat['subcategory']."'>".$row_subcat['subcategory']."</option>\n";
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
   echo "<option value='".$row_spot['spotlight']."'>".$row_spot['spotlight']."</option>\n";
}
echo "</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "   <td align='right'><b>New Spotlight:</b></td>\n";
echo "   <td><input type='text' size='20' name='newspot' /></td>\n";
echo "  </tr>\n";
for ($i=1; $i<=$num_photos; $i++) {
   echo " <tr>\n";
   echo "  <td colspan='2' align='right'>Photo $i: <input type='file' size='20' name='imgfile$i' />";
   if ($i == 1) {
      echo "<br /><span class='instructions'>Please use .gif .jpg or .png images only. Images should be approx. 480 pixels wide.</span>";
   }
   echo "</td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo "  <td colspan='2' align='right'>Photo $i thumbnail: <input type='file' size='20' name='tnfile$i' />";
   if ($i == 1) {
      echo "<br /><span class='instructions'>Please use .gif .jpg or .png images only. Images should be approx. 200x150.</span>";
   }
   echo "</td>\n";
   echo " </tr>\n";
}
echo "  <tr>\n";
echo "   <td align='right'><b>Status:</b></td>\n";
echo "   <td><select name='status' size='1' />\n";
echo "   <option value='In Stock'>In Stock</option>\n";
echo "   <option value='Out of Stock'>Out of Stock</option>\n";
echo "   <option value='Active'>Active</option>\n";
echo "   <option value='Inactive'>Inactive</option>\n";
echo "   </select></td>\n";
echo "  </tr>\n";
if (!empty($user_types)) {
   echo "  <tr>\n";
   echo "   <td align='right'><b>User Types:</b></td>\n";
   echo "   <td><select name='user_types' size='1' />\n";
   foreach ($user_types as $key => $type) {
      echo "   <option value='$key'>$type</option>\n";
   }
   echo "   </select></td>\n";
   echo "  </tr>\n";
}
echo "  <tr>\n";
echo "   <td align='right'><b>Sort No.:</b></td>\n";
echo "   <td><input type='text' size='5' name='sort_num' /></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "   <td align='right'><input type='submit' name='submit' value='Update' /></td>\n";
echo "   <td><input type='reset' name='reset' value='Reset' /></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "   <td colspan='2' align='center'><b><a href='edititem.php'><< Cancel, Exit without saving</a></b></td>";
echo "  </tr>\n";
echo " </table>\n";
echo "</form>\n";

include 'footer.php';

/* Close out the result set */
mysql_free_result($result_cat);
mysql_free_result($result_subcat);
mysql_free_result($result_spot);

/* Closing connection */
mysql_close($dbcnx);
?>
