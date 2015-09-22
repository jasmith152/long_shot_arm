<?php
$page_title = "Please Log In";
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
// Include site header
include $inc_header_file;
      
      /* Debugging info */
      if ($debugging == 'Y') {
         echo "<div class='debugging'>\n";
         echo "f_user: $f_user<br />\n";
         echo "f_pass: $f_pass<br />\n";
         echo "status: $status<br />\n";
         echo "line: ".$line."<br />\n";
         echo "arr[0]: ".$arr[0]."<br />\n";
         echo "arr[1]: ".$arr[1]."<br />\n";
         echo "</div>\n";
      }
?>
<!-- Begin Login form -->
<table width="608" border="0" cellpadding="0" cellspacing="0">
 <tr>
  <td valign="top">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<table border="0" cellspacing="5" cellpadding="5">
 <tr>
  <td align="center"><font face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular" size="2">
   <b>If you already have an account,<br />Please Log In</b>
  </font></td>
 </tr>
 <tr>
  <td align="left">Email<br /><input type="text" size="10" name="f_user"></td>
 </tr>
 <tr>
  <td align="left">Password<br /><input type="password" size="10" name="f_pass"></td>
 </tr>
 <tr>
  <td align="center"><input type="submit" name="submit" value="Log In"></td>
 </tr>
</table>
<input type="hidden" name="request_uri" value="<?php echo $_SERVER["REQUEST_URI"]; ?>">
</form>
  </td>
  <td valign="top">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<table border="0" cellspacing="5" cellpadding="5">
 <tr>
  <td colspan="2" align="center"><font face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular" size="2">
   <b>Or Create an account now.<br />It only takes a minute!</b>
  </font></td>
 </tr>
 <tr>
  <td align="right">First Name:</td>
  <td align="left"><input type="text" size="10" name="customer_fname"></td>
 </tr>
 <tr>
  <td align="right">Last Name:</td>
  <td align="left"><input type="text" size="10" name="customer_lname"></td>
 </tr>
 <tr>
  <td align="right">Email:</td>
  <td align="left"><input type="text" size="20" name="customer_email"></td>
 </tr>
 <tr>
  <td align="right">Create a Password:</td>
  <td align="left"><input type="password" size="20" name="password"></td>
 </tr>
 <tr>
  <td colspan="2" align="center"><hr width="90%" size="1" noshade /><b>Billing Information</b><br />
  <i>This information should be the same information listed on your credit card account.</i></td>
 </tr>
 <tr>
  <td align="right">Address:</td>
  <td align="left"><input type="text" size="20" name="bill_address1"></td>
 </tr>
 <tr>
  <td align="right">Address (con't):</td>
  <td align="left"><input type="text" size="20" name="bill_address2"></td>
 </tr>
 <tr>
  <td align="right">City:</td>
  <td align="left"><input type="text" size="20" name="bill_city"></td>
 </tr>
 <tr>
  <td align="right">State, Zip:</td>
  <td align="left"><select name="bill_state" size="1">
  <option value="">--</option>
<?php
include 'statelist.html';
?>
   </select>&nbsp;<input type="text" size="8" name="bill_zip"></td>
 </tr>
 <tr>
  <td align="right">Country:</td>
  <td align="left"><select name="bill_country" size="1">
  <option value="US">US</option>
  <option value="Canada">Canada</option>
  </select></td>
 </tr>
 <tr>
  <td colspan="2" align="center"><hr width="90%" size="1" noshade /><b>Shipping Information</b><br />
  <i>Check to use same as Billing Information: <input type="checkbox" name="copy" OnClick="javascript:ShipToBillPerson(this.form);" value="checkbox"></i></td>
 </tr>
 <tr>
  <td align="right">Name:</td>
  <td align="left"><input type="text" size="20" name="ship_name"></td>
 </tr>
 <tr>
  <td align="right">Address:</td>
  <td align="left"><input type="text" size="20" name="ship_address1"></td>
 </tr>
 <tr>
  <td align="right">Address (con't):</td>
  <td align="left"><input type="text" size="20" name="ship_address2"></td>
 </tr>
 <tr>
  <td align="right">City:</td>
  <td align="left"><input type="text" size="20" name="ship_city"></td>
 </tr>
 <tr>
  <td align="right">State, Zip:</td>
  <td align="left"><select name="ship_state" size="1">
  <option value="">--</option>
<?php
include 'statelist.html';
?>
   </select>&nbsp;<input type="text" size="8" name="ship_zip"></td>
 </tr>
 <tr>
  <td align="right">Country:</td>
  <td align="left"><select name="ship_country" size="1">
  <option value="US">US</option>
  <option value="Canada">Canada</option>
  </select></td>
 </tr>
 <tr>
  <td colspan="2" align="center"><hr width="90%" size="1" noshade /></td>
 </tr>
 <tr>
  <td align="right">Daytime Phone:</td>
  <td align="left"><input type="text" size="20" name="day_phone"><br /><font size='2'><i>Please use the following format: 123-456-7890</i</font></font></td>
 </tr>
 <tr>
  <td align="right">Evening Phone:</td>
  <td align="left"><input type="text" size="20" name="evening_phone"></td>
 </tr>
 <tr>
  <td align="right">Fax:</td>
  <td align="left"><input type="text" size="20" name="fax"></td>
 </tr>
 <tr>
  <td colspan="2" align="center"><input type="submit" name="submit" value="Create Account"></td>
 </tr>
</table>
<input type="hidden" name="request_uri" value="<?php echo $_SERVER["REQUEST_URI"]; ?>">
</form>
  </td>
 </tr></table>
<!-- End of Login form -->
<?php
// Include site footer
include $inc_footer_file;
?>
