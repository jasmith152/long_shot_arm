<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="content-type" content="text/html;charset=ISO-8859-1" />
  <title><?php echo $page_title; ?></title>
  <link rel="stylesheet" type="text/css" media="screen" href="styles.css" />
  <SCRIPT LANGUAGE='JAVASCRIPT' TYPE='TEXT/JAVASCRIPT'>
  <!--
   var win=null;
   function ImgWindow(mypage,myname,w,h,pos,infocus){
    if(pos=="random"){myleft=(screen.width)?Math.floor(Math.random()*(screen.width-w)):100;mytop=(screen.height)?Math.floor(Math.random()*((screen.height-h)-75)):100;}
    if(pos=="center"){myleft=(screen.width)?(screen.width-w)/2:100;mytop=(screen.height)?(screen.height-h)/2:100;}
    else if((pos!='center' && pos!="random") || pos==null){myleft=0;mytop=20}
    settings="width=" + w + ",height=" + h + ",top=" + mytop + ",left=" + myleft + ",scrollbars=yes,location=no,directories=no,status=no,menubar=no,toolbar=yes,resizable=yes";win=window.open(mypage,myname,settings);
    win.focus();}
  // -->
  </script>
  <script type="text/javascript">
  <!--
   function confirmMsg(i,j){
    var answer=confirm("WARNING!\nYou are about to delete an item!\nAre you sure you want to do this?")
    if(answer) {
     var where="edititem.php?action=delete<?php echo $str_url_vars; ?>&id=" + i + "&item_num=" + j;
     window.location=where;
    }
   }
  //-->
  </script>
  <script type="text/javascript">
  <!--
   function confirmPhotoMsg(i,j){
    var answer=confirm("WARNING!\nYou are about to delete a photo!\nAre you sure you want to do this?")
    if(answer) {
     var where="edititem.php?action=delete-photo&item_num=" + i + "&photo_num=" + j;
     window.location=where;
    }
   }
  //-->
  </script>
  <?php
  if (!empty($extra_head)) {
     echo $extra_head;
  }
  ?>
</head>

<body topmargin="10" marginheight="10">
 <div align="center">
 <table id="content" width="956" cellpadding="0" cellspacing="0" border="0">
  <tr>
   <td id="right-col" valign="top">
    <p align="center"><a href="edititem.php">Manage Inventory</a> | <a href="additem.php">Add New Item</a><!-- | <a href="create-customer.php">Create Customer</a> | <a href="edit-customer.php">Edit Customer</a> | <a href="editor.php">Content Editor</a>--></p>
                                                   <!-- Content Begins -->
