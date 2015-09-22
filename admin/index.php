<?php
$cfgProgDir = "phpSecurePages/";
include($cfgProgDir."secure.php");

$page_title = "Admin Panel";
include 'header.php';

echo "<table border='0' cellspacing='2' cellpadding='2' style='font-family: Arial,Helvetica,sans-serif;font-size: 13px;'>\n";
echo " <tr>\n";
echo "  <td><b><a href='edititem.php'>Manage Inventory</a></b></td>\n";
echo "  <td>Update your inventory. For example adding items to a category, subcategory or special section. Special sections are designated by the Spotlight field.</td>\n";
echo " </tr>\n";
echo " <tr>\n";
echo "  <td><b><a href='additem.php'>Add New Item</a></b></td>\n";
echo "  <td>Add a new item to your inventory.</td>\n";
echo " </tr>\n";
/*echo " <tr>\n";
echo "  <td><b><a href='create-customer.php'>Add Customer Account</a></b></td>\n";
echo "  <td>Add a new customer account.</td>\n";
echo " </tr>\n";
echo " <tr>\n";
echo "  <td><b><a href='edit-customer.php'>Edit Customer Account</a></b></td>\n";
echo "  <td>Search and Update a customer account.</td>\n";
echo " </tr>\n";*/
/*echo " <tr>\n";
echo "  <td><b><a href='editor.php'>Edit Site Content</a></b></td>\n";
echo "  <td>Edit content on other pages.</td>\n";
echo " </tr>\n";*/
/*echo " <tr>\n";
echo "  <td><b><a href='../blog/'>Blog Admin</a></b></td>\n";
echo "  <td>Post stories through the blog.</td>\n";
echo " </tr>\n";*/
/*echo " <tr>\n";
echo "  <td><b><a href='import_manual.php'>Import Inventory</a></b></td>\n";
echo "  <td>Import new inventory from a vendor's data file.</td>\n";
echo " </tr>\n";*/
echo "</table>\n";

include 'footer.php';
?>
