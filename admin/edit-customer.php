<?php
$cfgProgDir = "phpSecurePages/";
include($cfgProgDir."secure.php");

/* Get the store variables and config */
include '../cart_config.php';

// Check for new account info
if (!empty($_POST['customer_fname']) && !empty($_POST['customer_lname']) && !empty($_POST['customer_email'])) {

   // Update the customer record
   $sql_update = "UPDATE tbl_customers SET
         email='".addslashes($_POST['customer_email'])."',
         fname='".addslashes($_POST['customer_fname'])."',
         lname='".addslashes($_POST['customer_lname'])."',
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
         user_type='".addslashes($_POST['user_type'])."'";
         if (!empty($_POST['password'])) {
            $sql_update .= ",password='".addslashes($_POST['password'])."'";
         }
   if (!mysql_query($sql_update)) {
      echo "Error updating customer: ".mysql_error()."<br />Please try again in a few minutes.<br />\n";
      exit();
   } else {
      $msg = "Customer account updated.";
   }
}

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
var ship_countryIndex = 0;
var ship_country = \"\";

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
form.ship_name.value = form.customer_fname.value + \" \" + form.customer_lname.value;
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
$page_title = "Update Customer Account";
// Include site header
include 'header.php';

// Display any messages
if (!empty($msg)) {
   echo "<p class='msg'>$msg</p>\n";
}
if (!empty($error)) {
   echo "<p class='error'>$error</p>\n";
}

// Display customer search box
echo "<div align='center' style='padding: 15px; border: 1px solid #000;'>\n";
echo "<form action='".$_SERVER['PHP_SELF']."' method='POST'>\n";
echo " <input type='text' name='customer_email' size='20' /> <input type='submit' name='submit-search' value='Search' />\n";
echo "</form>\n";
echo "</div>\n";

if (!empty($_POST['customer_email'])) {
   // Check for existing customer record matching email
   $sql = "SELECT * FROM tbl_customers WHERE email = '".addslashes($_POST['customer_email'])."'";
   $result = mysql_query($sql);
   $row = mysql_fetch_array($result);
   
   echo "<form action='".$_SERVER['PHP_SELF']."' method='POST'>\n";
   echo "<table border='0' cellspacing='5' cellpadding='5'>\n";
   echo " <tr>\n";
   echo " <td align='right'>First Name:</td>\n";
   echo " <td align='left'><input type='text' size='10' name='customer_fname' value='".$row['fname']."' /></td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo " <td align='right'>Last Name:</td>\n";
   echo " <td align='left'><input type='text' size='10' name='customer_lname' value='".$row['lname']."' /></td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo " <td align='right'>Email:</td>\n";
   echo " <td align='left'><input type='text' size='20' name='customer_email' value='".$row['email']."' /></td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo " <td align='right'>Reset Password:</td>\n";
   echo " <td align='left'><input type='password' size='20' name='password'></td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo " <td align='right'>Customer Type:</td>\n";
   echo " <td align='left'><select name='user_type' size='1'>\n";
   $i = 0;
   foreach ($user_types as $type) {
      if (!empty($type)) {
         echo "  <option value='$i'";
         if ($i == $row['user_type']) {
            echo " selected";
         }
         echo ">$type</option>\n";
      }
      $i++;
   }
   echo "  </select></td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo " <td colspan='2' align='center'><hr width='90%' size='1' noshade /><b>Billing Information</b><br />\n";
   echo "  <i>This information should be the same information listed on your credit card account.</i></td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo " <td align='right'>Address:</td>\n";
   echo " <td align='left'><input type='text' size='20' name='bill_address1' value='".$row['bill_address1']."' /></td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo " <td align='right'>Address (con't):</td>\n";
   echo " <td align='left'><input type='text' size='20' name='bill_address2' value='".$row['bill_address2']."' /></td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo " <td align='right'>City:</td>\n";
   echo " <td align='left'><input type='text' size='20' name='bill_city' value='".$row['bill_city']."' /></td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo " <td align='right'>State, Zip:</td>\n";
   echo " <td align='left'><select name='bill_state' size='1'>\n";
   if (!empty($row['bill_state'])) {
      echo "  <option value='".$row['bill_state']."'>".$row['bill_state']."</option>\n";
   } else {
      echo "  <option value=''>--</option>\n";
   }
   include '../statelist.html';
   echo "   </select>&nbsp;<input type='text' size='8' name='bill_zip' value='".$row['bill_zip']."' /></td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo " <td align='right'>Country:</td>\n";
   echo " <td align='left'><select name='bill_country' size='1'>\n";
   echo "  <option value='US'";
   if ($row['bill_country'] == 'US') {
      echo " selected";
   }
   echo ">US</option>\n";
   echo "  <option value='Canada'";
   if ($row['bill_country'] == 'Canada') {
      echo " selected";
   }
   echo ">Canada</option>\n";
   echo "  </select></td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo " <td colspan='2' align='center'><hr width='90%' size='1' noshade /><b>Shipping Information</b><br />\n";
   echo "  <i>Check to use same as Billing Information: <input type='checkbox' name='copy' OnClick='javascript:ShipToBillPerson(this.form);' value='checkbox'></i></td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo " <td align='right'>Name:</td>\n";
   echo " <td align='left'><input type='text' size='20' name='ship_name' value='".$row['ship_name']."' /></td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo " <td align='right'>Address:</td>\n";
   echo " <td align='left'><input type='text' size='20' name='ship_address1' value='".$row['ship_address1']."' /></td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo " <td align='right'>Address (con't):</td>\n";
   echo " <td align='left'><input type='text' size='20' name='ship_address2' value='".$row['ship_address2']."' /></td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo " <td align='right'>City:</td>\n";
   echo " <td align='left'><input type='text' size='20' name='ship_city' value='".$row['ship_city']."' /></td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo " <td align='right'>State, Zip:</td>\n";
   echo " <td align='left'><select name='ship_state' size='1'>\n";
   if (!empty($row['ship_state'])) {
      echo "  <option value='".$row['ship_state']."'>".$row['ship_state']."</option>\n";
   } else {
      echo "  <option value=''>--</option>\n";
   }
   include '../statelist.html';
   echo "   </select>&nbsp;<input type='text' size='8' name='ship_zip' value='".$row['ship_zip']."' /></td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo " <td align='right'>Country:</td>\n";
   echo " <td align='left'><select name='ship_country' size='1'>\n";
   echo "  <option value='US'";
   if ($row['ship_country'] == 'US') {
      echo " selected";
   }
   echo ">US</option>\n";
   echo "  <option value='Canada'";
   if ($row['ship_country'] == 'US') {
      echo " selected";
   }
   echo ">Canada</option>\n";
   echo "  </select></td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo " <td colspan='2' align='center'><hr width='90%' size='1' noshade /></td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo " <td align='right'>Daytime Phone:</td>\n";
   echo " <td align='left'><input type='text' size='20' name='day_phone' value='".$row['day_phone']."' /><br /><font size='2'><i>Please use the following format: 123-456-7890</i</font></font></td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo " <td align='right'>Evening Phone:</td>\n";
   echo " <td align='left'><input type='text' size='20' name='evening_phone' value='".$row['evening_phone']."' /></td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo " <td align='right'>Fax:</td>\n";
   echo " <td align='left'><input type='text' size='20' name='fax' value='".$row['fax']."' /></td>\n";
   echo " </tr>\n";
   echo " <tr>\n";
   echo " <td colspan='2' align='center'><input type='submit' name='submit' value='Save Changes' /></td>\n";
   echo " </tr>\n";
   echo "</table>\n";
   echo "</form>\n";
}

/* Debugging Info */
if ($debugging == 'Y') {
   echo "<div class='debugging'>\n";
   echo "sql: $sql<br />\n";
   echo "</div>\n";
}

if (!empty($result)) {
   mysql_free_result($result);
}
// Close Db connection
mysql_close($dbcnx);

// Include site footer
include 'footer.php';
?>
