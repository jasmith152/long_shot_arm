<?
// login.php - performs validation

// Set some variables
$request_uri = $_SERVER['REQUEST_URI'];

// authenticate username/password against .php_passwd file
// returns: -1 if user does not exist
//           0 if user exists but password is incorrect
//           1 if username and password are correct
function authenticate($user, $pass) {
   $result = -1;

   // Get emails from Db that match
   include 'dbconn.php';
   $sql_email = "SELECT id, email, password, user_type FROM tbl_customers WHERE email = '$user'";
   $result_email = mysql_query($sql_email);
   $num_email = mysql_num_rows($result_email);
   $row_email = mysql_fetch_array($result_email);
   if ($num_email > 0) {
      $result++;
   }
   if ($pass == $row_email['password']) {
      // update items in shopping cart to include email of customer
      $sql_update = "UPDATE tbl_orders_temp SET email='$user' WHERE (session='$PHPSESSID')";
      if (!mysql_query($sql_update)) {
         // There was an error, but let's not stop the login for this error
         echo "Error: ".mysql_error();
      }
      $result = $result+$row_email['user_type'];
   }
   if (!empty($result_email)) {
      mysql_free_result($result_email);
   }
   mysql_close($dbcnx);

   // return value & end function
   return $result;
}

// check for current session
if (!isset($_SESSION['UNAME'])) {

// check for supplied user/pass
if (isset($_POST['f_user']) && isset($_POST['f_pass'])) {
   
   // authenticate using form variables
   $status = authenticate($_POST['f_user'], $_POST['f_pass']);
   
   // if  user/pass combination is correct
   if ($status > 0) {
      // register the username
      /*session_register("UNAME");
      session_register("user_type");*/
      $_SESSION['UNAME'] = $_POST['f_user'];
      $_SESSION['user_type'] = $status;
      
      // update items in shopping cart to include email of customer
      include 'dbconn.php';
      $sql_update = "UPDATE tbl_orders_temp SET email='".$_SESSION['UNAME']."' WHERE session='$PHPSESSID'";
      //echo "sql_update: $sql_update<br />\n";
      if (!mysql_query($sql_update)) {
         // There was an error
         echo "Error updating cart: ".mysql_error()."<br />\n";
         exit();
      }
      $sql_user = "SELECT id,email,bill_state,bill_country,ship_name,ship_address1,ship_city,ship_state,ship_zip,ship_country FROM tbl_customers WHERE email = '".$_SESSION['UNAME']."'";
      $result_user = mysql_query($sql_user);
      if (!mysql_query($sql_user)) {
         // There was an error
         echo "Error updating cart: ".mysql_error()."<br />\n";
         exit();
      }
      $row_user = mysql_fetch_array($result_user);
      /*session_register("bill_state");
      session_register("bill_country");
      session_register("ship_name");
      session_register("ship_address");
      session_register("ship_city");
      session_register("ship_state");
      session_register("ship_zip");
      session_register("ship_country");*/
      $_SESSION['bill_state'] = $row_user['bill_state'];
      $_SESSION['bill_country'] = $row_user['bill_country'];
      $_SESSION['ship_name'] = $row_user['ship_name'];
      $_SESSION['ship_address'] = $row_user['ship_address1'];
      $_SESSION['ship_city'] = $row_user['ship_city'];
      $_SESSION['ship_state'] = $row_user['ship_state'];
      $_SESSION['ship_zip'] = $row_user['ship_zip'];
      $_SESSION['ship_country'] = $row_user['ship_country'];
   
      // Free any results
      if (!empty($result_user)) {
         mysql_free_result($result_user);
      }
      // Close Db connection
      mysql_close($dbcnx);
   
      /* Debugging info
      if ($debugging == 'Y') {
         echo "<div class='debugging'>\n";
         echo "SESSION['ship_name']: ".$_SESSION['ship_name']."<br />\n";
         echo "SESSION['ship_address']: ".$_SESSION['ship_address']."<br />\n";
         echo "</div>\n";
         exit();
      } */
      header("Location: ".$request_uri);
      exit();
   } else {
      // user/pass check failed
      // check the error code and generate an appropriate error message switch
      switch ($status) {
      case -1:
            $message = "Invalid username and/or password.";
            break;
      case 0:
            $message = "Invalid username and/or password.";
            break;
      case 2:
            $message = "Unauthorized access.";
            break;
      default:
            $message = "An unspecified error occurred.";
            break;
      }
      echo $message."<br />\n";
      echo "Please <a href='$request_uri'>log in</a> again.<br />\n";

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
         exit();
      }
   }
} else {
   // Check for new account info
   if (!empty($_POST['customer_fname']) && !empty($_POST['customer_lname']) && !empty($_POST['customer_email'])) {
      //include 'dbconn.php';
   
      // Check for existing customer record matching email
      $sql_check = "SELECT id,email FROM tbl_customers WHERE email = '".addslashes($_POST['customer_email'])."'";
      $result_check = mysql_query($sql_check);
      $numrows_check = mysql_num_rows($result_check);
      if ($numrows_check > 0) {
         // Delete the old record first
         $sql_del = "DELETE FROM tbl_customers WHERE email = '".addslashes($_POST['customer_email'])."'";
         if (!mysql_query($sql_del)) {
            echo "Error deleting old record: ".mysql_error()."<br />Please contact us to complete your order.<br />\n";
            exit();
         }
      }
      if (!empty($result_check)) {
         mysql_free_result($result_check);
      }

      // Create the customer record and start a session
      $sql_create = "INSERT INTO tbl_customers SET
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
          password='".addslashes($_POST['password'])."'";
      if (!mysql_query($sql_create)) {
         echo "Error creating customer: ".mysql_error()."<br />Please try again in a few minutes.<br />\n";
         exit();
      }

      // initiate a session
      session_start();
      // register some session variables
      //session_register("UNAME");
      $_SESSION['UNAME'] = $_POST['customer_email'];
      $_SESSION['bill_state'] = $_POST['bill_state'];
      $_SESSION['bill_country'] = $_POST['bill_country'];
      $_SESSION['ship_state'] = $_POST['ship_state'];
      $_SESSION['ship_country'] = $_POST['ship_country'];

      // update items in shopping cart to include email of customer
      $sql_update = "UPDATE tbl_orders_temp SET email='".$_SESSION['UNAME']."' WHERE session='$PHPSESSID'";
      //echo "sql_update: $sql_update<br />\n";
      if (!mysql_query($sql_update)) {
         // There was an error
         echo "Error updating cart: ".mysql_error()."<br />\n";
         exit();
      }

      // Close Db connection
      mysql_close($dbcnx);

      // redirect to protected page
      header("Location: ".$request_uri);
      exit();
   }
   // if no user/pass supplied, then show form
   include 'login_interface.php';
   exit();
}
}
?>
