<?php
/* Configuration */
$shipping_rate = 55; // Dollar amt to multiply by total order weight

/* Debugging Info */
if ($debugging == 'Y') {
   echo "<div class='debugging'>\n";
   //echo "total weight: $total_wt<br />\n";
   echo "session['shipping']: ".$_SESSION['shipping']."<br />\n";
   echo "</div>\n";
}

$_SESSION['shipping'] = $shipping_rate;
echo " <tr>\n";
echo "  <td colspan='4' align='right'><strong>Shipping &amp; Handling:</strong></td>\n";
echo "  <td colspan='1' align='right'>$".number_format($_SESSION['shipping'],2)."</td>\n";
echo "  <td>&nbsp;</td>\n";
echo " </tr>\n";
?>
