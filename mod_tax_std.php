<?php
/* Configuration */
$taxrate = 0.06;
$tax_state = "FL";
$tax_samestateonly = "Y";

/* Debugging Info */
if ($debugging == 'Y') {
   echo "<div class='debugging'>\n";
   echo "SESSION['bill_state']: ".$_SESSION['bill_state']."<br />\n";
   echo "SESSION['tax']: ".$_SESSION['tax']."<br />\n";
   echo "</div>\n";
}

if (!empty($taxrate)) {
   if ($tax_samestateonly == "Y") {
      if ($tax_state == $_SESSION['bill_state']) {
         $_SESSION['tax'] = $subtotal*$taxrate;
      } else {
         $_SESSION['tax'] = 0;
      }
   } else {
      $_SESSION['tax'] = $subtotal*$taxrate;
   }
   echo " <tr>\n";
   echo "  <td colspan='4' align='right'><strong>Tax:</strong></td>\n";
   echo "  <td colspan='1' align='right'>$".number_format($_SESSION['tax'],2)."</td>\n";
   echo "  <td>&nbsp;</td>\n";
   echo " </tr>\n";
}
?>
