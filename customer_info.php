<?php
// Get the configuration file
require 'cart_config.php';

// Check for and set variables
if (!empty($_GET['msg'])) { $msg = $_GET['msg']; }
if (!empty($_POST['msg'])) { $msg = $_POST['msg']; }
if (!empty($_GET['error'])) { $error = $_GET['error']; }
if (!empty($_POST['error'])) { $error = $_POST['error']; }
if (!empty($_SESSION['UNAME'])) {
   $cart_email = $_SESSION['UNAME'];
}
if (!empty($_POST['cart_email'])) {
   $cart_email = $_POST['cart_email'];
}
$submit = $_POST['submit'];

if (!empty($submit)) {
   $sql_update = "UPDATE tbl_customers SET 
     email='".addslashes($_POST['email'])."',
     fname='".addslashes($_POST['fname'])."',
     lname='".addslashes($_POST['lname'])."',
     bill_address1='".addslashes($_POST['bill_address1'])."',
     bill_address2='".addslashes($_POST['bill_address2'])."',
     bill_city='".addslashes($_POST['bill_city'])."',
     bill_state='".addslashes($_POST['bill_state'])."',
     bill_zip='".addslashes($_POST['bill_zip'])."',
     bill_country='".addslashes($_POST['bill_country'])."',
     ship_name='".addslashes($_POST['ship_name'])."',
     ship_address1='".addslashes($_POST['ship_address1'])."',
     ship_address2='".addslashes($_POST['ship_address2'])."',
     ship_city='".addslashes($_POST['ship_city'])."',
     ship_state='".addslashes($_POST['ship_state'])."',
     ship_zip='".addslashes($_POST['ship_zip'])."',
     ship_country='".addslashes($_POST['ship_country'])."',
     day_phone='".addslashes($_POST['day_phone'])."',
     evening_phone='".addslashes($_POST['evening_phone'])."',
     fax='".addslashes($_POST['fax'])."',
     password='".addslashes($_POST['password'])."'";
   $sql_update .= " WHERE email='$cart_email'";
   if (!mysql_query($sql_update)) {
      echo "Error updating customer: ".mysql_error()."<br />Please try again in a few minutes.<br />\n";
      exit();
   }
   $msg = "Customer information has been updated and saved.";
}

$sql = "SELECT * FROM tbl_customers WHERE email = '$cart_email'";
$result = mysql_query($sql);
if (!$result) {
   echo "Error performing query: " . mysql_error();
   exit();
}
$num_rows = mysql_num_rows($result);
$row = mysql_fetch_array($result);

// Reset some session variables
$_SESSION['bill_state'] = $row['bill_state'];
$_SESSION['bill_country'] = $row['bill_country'];
$_SESSION['ship_state'] = $row['ship_state'];
$_SESSION['ship_country'] = $row['ship_country'];

$page_title = $store_name." - Customer Information";
$extra_head = "<SCRIPT LANGUAGE=\"JavaScript\">

<!-- This script and many more are available free online at -->
<!-- The JavaScript Source!! http://javascript.internet.com -->

<!-- Begin
var ship_name = \"\";
var ship_address1 = \"\";
var ship_address2 = \"\";
var ship_city = \"\";
var ship_state = \"\";
var ship_stateIndex = 0;
var ship_zip = \"\";
var ship_country = \"\";
var ship_countryIndex = 0;

function InitSaveVariables(form) {
ship_name = form.ship_name.value;
ship_address1 = form.ship_address1.value;
ship_address2 = form.ship_address2.value;
ship_city = form.ship_city.value;
ship_zip = form.ship_zip.value;
ship_stateIndex = form.ship_state.selectedIndex;
ship_state = form.ship_state[ship_stateIndex].value;
ship_countryIndex = form.ship_country.selectedIndex;
ship_country = form.ship_country[ship_countryIndex].value;
}

function ShipToBillPerson(form) {
if (form.copy.checked) {
InitSaveVariables(form);
form.ship_name.value = form.fname.value + \" \" + form.lname.value;
form.ship_address1.value = form.bill_address1.value;
form.ship_address2.value = form.bill_address2.value;
form.ship_city.value = form.bill_city.value;
form.ship_zip.value = form.bill_zip.value;
form.ship_state.selectedIndex = form.bill_state.selectedIndex;
form.ship_country.selectedIndex = form.bill_country.selectedIndex;
}
else {
form.ship_name.value = ship_name;
form.ship_address1.value = ship_address1;
form.ship_address2.value = ship_address2;
form.ship_city.value = ship_city;
form.ship_zip.value = ship_zip;
form.ship_state.selectedIndex = ship_stateIndex;
form.ship_country.selectedIndex = ship_countryIndex;
   }
}
//  End -->
</script>
";

// Include site header
include $inc_header_file;

if (!empty($msg)) {
   echo "<p class='msg'>$msg</p>\n";
}

/* Debugging Info */
if ($debugging == 'Y') {
   echo "<div class='debugging'>\n";
   echo "SESSION['taxrate']: ".$_SESSION['taxrate']."<br />\n";
   echo "SESSION['tax_samestateonly']: ".$_SESSION['tax_samestateonly']."<br />\n";
   echo "SESSION['tax_state']: ".$_SESSION['tax_state']."<br />\n";
   echo "SESSION['bill_state']: ".$_SESSION['bill_state']."<br />\n";
   echo "SESSION['tax']: ".$_SESSION['tax']."<br />\n";
   echo "</div>\n";
}

echo "<p><font face='Arial,Helvetica,Geneva,Swiss,SunSans-Regular' size='3'><b><a href='view_cart.php'>Back to Shopping Cart / Continue Placing Order >></a></b></font></p>\n";
echo "<form action='".$_SERVER['PHP_SELF']."' method='POST'>\n";
echo "<input type='hidden' name='cart_email' value='$cart_email' />\n";
echo "<table border='0' cellspacing='5' cellpadding='5'>\n";
echo " <tr>\n";
echo "  <td colspan='2' align='center'><font face='Arial,Helvetica,Geneva,Swiss,SunSans-Regular' size='2'>\n";
echo "   <b>Please review the information</b> we have on record for you and make any necessary changes.\n";
echo "  </font></td>\n";
echo " </tr>\n";
echo " <tr>\n";
echo "  <td align='right'>First Name:</td>\n";
echo "  <td align='left'><input type='text' size='10' name='fname' value='".$row['fname']."'></td>\n";
echo " </tr>\n";
echo " <tr>\n";
echo "  <td align='right'>Last Name:</td>\n";
echo "  <td align='left'><input type='text' size='10' name='lname' value='".$row['lname']."'></td>\n";
echo " </tr>\n";
echo " <tr>\n";
echo "  <td align='right'>Email:</td>\n";
echo "  <td align='left'><input type='text' size='20' name='email' value='".$row['email']."'></td>\n";
echo " </tr>\n";
echo " <tr>\n";
echo "  <td align='right'>Current Password:</td>\n";
echo "  <td align='left'><input type='text' size='20' name='password' value='".$row['password']."'></td>\n";
echo " </tr>\n";
echo " <tr>\n";
echo "  <td colspan='2' align='center'><hr width='90%' size='1' noshade /><b>Billing Information</b><br />\n";
echo "  <i>This information should be the same information listed on your credit card account.</i></td>\n";
echo " </tr>\n";
echo " <tr>\n";
echo "  <td align='right'>Address:</td>\n";
echo "  <td align='left'><input type='text' size='20' name='bill_address1' value='".$row['bill_address1']."'></td>\n";
echo " </tr>\n";
echo " <tr>\n";
echo "  <td align='right'>Address (con't):</td>\n";
echo "  <td align='left'><input type='text' size='20' name='bill_address2' value='".$row['bill_address2']."'></td>\n";
echo " </tr>\n";
echo " <tr>\n";
echo "  <td align='right'>City:</td>\n";
echo "  <td align='left'><input type='text' size='20' name='bill_city' value='".$row['bill_city']."'></td>\n";
echo " </tr>\n";
echo " <tr>\n";
echo "  <td align='right'>State Zip:</td>\n";
echo "  <td align='left'><select name='bill_state' size='1'>\n";
echo "<option value='".$row['bill_state']."'>".$row['bill_state']."</option>\n";
include 'statelist.html';
echo "   </select>&nbsp;<input type='text' size='8' name='bill_zip' value='".$row['bill_zip']."'></td>\n";
echo " </tr>\n";
echo " <tr>\n";
echo "  <td align='right'>Country:</td>\n";
echo "  <td align='left'><select name='bill_country' size='1'>\n";
echo "<option value='".$row['bill_country']."'>".$row['bill_country']."</option>\n";
echo "<option value='US'>US</option>\n";
echo "<option value='Canada'>Canada</option>\n";
echo "   </select></td>\n";
echo " </tr>\n";
echo " <tr>\n";
echo "  <td colspan='2' align='center'><hr width='90%' size='1' noshade /><b>Shipping Information</b><br />\n";
echo "  <i>Check to use same as Billing Information: <input type='checkbox' name='copy' OnClick='javascript:ShipToBillPerson(this.form);' value='checkbox'></i></td>\n";
echo " </tr>\n";
echo " <tr>\n";
echo "  <td align='right'>Name:</td>\n";
echo "  <td align='left'><input type='text' size='20' name='ship_name' value='".$row['ship_name']."'></td>\n";
echo " </tr>\n";
echo " <tr>\n";
echo "  <td align='right'>Address:</td>\n";
echo "  <td align='left'><input type='text' size='20' name='ship_address1' value='".$row['ship_address1']."'></td>\n";
echo " </tr>\n";
echo " <tr>\n";
echo "  <td align='right'>Address (con't):</td>\n";
echo "  <td align='left'><input type='text' size='20' name='ship_address2' value='".$row['ship_address2']."'></td>\n";
echo " </tr>\n";
echo " <tr>\n";
echo "  <td align='right'>City:</td>\n";
echo "  <td align='left'><input type='text' size='20' name='ship_city' value='".$row['ship_city']."'></td>\n";
echo " </tr>\n";
echo " <tr>\n";
echo "  <td align='right'>State Zip:</td>\n";
echo "  <td align='left'><select name='ship_state' size='1'>\n";
echo "<option value='".$row['ship_state']."'>".$row['ship_state']."</option>\n";
include 'statelist.html';
echo "   </select>&nbsp;<input type='text' size='8' name='ship_zip' value='".$row['ship_zip']."'></td>\n";
echo " </tr>\n";
echo " <tr>\n";
echo "  <td align='right'>Country:</td>\n";
echo "  <td align='left'><select name='ship_country' size='1'>\n";
echo "<option value='".$row['ship_country']."'>".$row['ship_country']."</option>\n";
echo "<option value='US'>US</option>\n";
echo "<option value='Canada'>Canada</option>\n";
echo "   </select></td>\n";
echo " </tr>\n";
echo " <tr>\n";
echo "  <td colspan='2' align='center'><hr width='90%' size='1' noshade /></td>\n";
echo " </tr>\n";
echo " <tr>\n";
echo "  <td align='right'>Daytime Phone:</td>\n";
echo "  <td align='left'><input type='text' size='20' name='day_phone' value='".$row['day_phone']."'><br /><font size='2'><i>Please use the following format: 123-456-7890</i</font></font></td>\n";
echo " </tr>\n";
echo " <tr>\n";
echo "  <td align='right'>Evening Phone:</td>\n";
echo "  <td align='left'><input type='text' size='20' name='evening_phone' value='".$row['evening_phone']."'></td>\n";
echo " </tr>\n";
echo " <tr>\n";
echo "  <td align='right'>Fax:</td>\n";
echo "  <td align='left'><input type='text' size='20' name='fax' value='".$row['fax']."'></td>\n";
echo " </tr>\n";
echo " <tr>\n";
echo "  <td colspan='2' align='center'><input type='submit' name='submit' value='Update Account'></td>\n";
echo " </tr>\n";
echo "</table>\n";
echo "</form>\n";

// Include site footer
include $inc_footer_file;

// Free any results & close Db connection
if (!empty($result)) {
   mysql_free_result($result);
}
mysql_close($dbcnx);
?>
