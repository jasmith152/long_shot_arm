<?php
  // Connect to the database server and Select the database
  $dbcnx = mysql_connect("localhost", "longshot_armsllc", "r1fl3sGuns") or die("<p>Unable to connect to the database at this time.</p>");
  mysql_select_db("longshot_armsllc") or die("<p>Unable to locate the database at this time.</p>");
?>
