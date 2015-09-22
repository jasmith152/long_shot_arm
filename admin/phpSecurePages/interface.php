<?PHP
//  ------ create table variable ------
// variables for Netscape Navigator 3 & 4 are +4 for compensation of render errors
$Browser_Type  =  strtok($_ENV['HTTP_USER_AGENT'],  "/");
if ( ereg( "MSIE", $_ENV['HTTP_USER_AGENT']) || ereg( "Mozilla/5.0", $_ENV['HTTP_USER_AGENT']) || ereg ("Opera/5.11", $_ENV['HTTP_USER_AGENT']) ) {
	$theTable = 'WIDTH="400" HEIGHT="245"';
} else {
	$theTable = 'WIDTH="404" HEIGHT="249"';
}

// ------ create document-location variable ------
if ( ereg("php\.exe", $_SERVER['PHP_SELF']) || ereg("php3\.cgi", $_SERVER['PHP_SELF']) || ereg("phpts\.exe", $_SERVER['PHP_SELF']) ) {
	$documentLocation = $_ENV['PATH_INFO'];
} else {
	$documentLocation = $_SERVER['PHP_SELF'];
}
if ( $_ENV['QUERY_STRING'] ) {
	$documentLocation .= "?" . $_ENV['QUERY_STRING'];
}

?>
<html><head>
	<meta name="description" content="<?PHP echo $strLoginInterface; ?>">
	<meta name="keywords" content="<?PHP echo $strLogin; ?>">
	<title><?PHP echo $strLoginInterface; ?></title>
	
<SCRIPT LANGUAGE="JavaScript">
<!--
//  ------ check form ------
function checkData() {
	var f1 = document.forms[0];
	var wm = "<?PHP echo $strJSHello; ?>\n\r\n";
	var noerror = 1;

	// --- entered_login ---
	var t1 = f1.entered_login;
	if (t1.value == "" || t1.value == " ") {
		wm += "<?PHP echo $strLogin; ?>\r\n";
		noerror = 0;
	}

	// --- entered_password ---
	var t1 = f1.entered_password;
	if (t1.value == "" || t1.value == " ") {
		wm += "<?PHP echo $strPassword; ?>\r\n";
		noerror = 0;
	}

	// --- check if errors occurred ---
	if (noerror == 0) {
		alert(wm);
		return false;
	}
	else return true;
}
//-->
</SCRIPT>

<style type="text/css">
<!-- 
A:hover.link {
	background-color: #E9E9E9;
}
//-->
</style>
</head>

<body bgcolor="White" TEXT="Black"><center>
<form action='<?PHP echo $documentLocation; ?>' METHOD="post" onSubmit="return checkData()">
<TABLE WIDTH="100%" HEIGHT="100%" CELLPADDING="0" CELLSPACING="0"><TR><TD ALIGN="center" VALIGN="middle">

	<!-- Place your logo here -->

	<TABLE <?PHP echo $theTable; ?> CELLPADDING="0" CELLSPACING="0" BACKGROUND="<?PHP echo $cfgHtmlDir; ?>images/<?PHP echo $bgImage; ?>"><TR><TD ALIGN="center" VALIGN="middle">
		<TABLE CELLPADDING="4" WIDTH="100%" HEIGHT="100%" BACKGROUND="">
		<TR><TD ALIGN="center" COLSPAN="2"><h1><?PHP echo $strLoginInterface; ?></h1></TD></TR>
		<TR><TD ALIGN="center" COLSPAN="2">
			<B><I><NOBR><?PHP
			// check for error messages
			if ($message) {
				echo $message;
			} ?></NOBR></I></B>
		</TD></TR>
		<tr><TD VALIGN="bottom"><A HREF="<?PHP echo $cfgUrl . getenv("HTTP_HOST") . $cfgIndexpage; ?>" TABINDEX="2">
			<IMG SRC="<?PHP echo $cfgHtmlDir; ?>images/cancel.gif" ALIGN="left" WIDTH="22" HEIGHT="23" ALT="<?PHP echo $strCancel; ?>" BORDER=0 hspace=10 vspace=4></A>
		</TD>
		<td ALIGN="right" VALIGN="bottom">
			<table cellpadding=4 cellspacing=1 BACKGROUND="">
			<tr><td><B><FONT FACE="Arial,Helvetica,sans-serif" SIZE="-1"><?PHP echo $strLogin; ?>: </FONT></B></td>
			<td> <INPUT TYPE="text" NAME="entered_login" STYLE="font-size: 9pt;" TABINDEX="1"></td></tr>
			<tr><td><B><FONT FACE="Arial,Helvetica,sans-serif" SIZE="-1"><?PHP echo $strPassword; ?>: </FONT></B></td>
			<td> <INPUT TYPE="password" NAME="entered_password" STYLE="font-size: 9pt;" TABINDEX="1"></td></tr>
			</table>
			<INPUT TYPE=image src="<?PHP echo $cfgHtmlDir; ?>images/enter.gif" WIDTH="26" HEIGHT="23" border=0 hspace=7 vspace=4 alt="<?PHP echo $strEnter; ?>   &gt;&gt;&gt;" TABINDEX="1">
		</td></tr></table>
	</TD></TR></TABLE>
	<!-- ------ Copyright line starts here ------
	(if this software is used for free (not allowed for commercial use)
	this line may NOT be removed or altered in such a way that it becomes
	less (or un-) readable). -->
	<FONT FACE="Verdana,Geneva,Arial,Helvetica,sans-serif" SIZE="-2"><?PHP echo $strPoweredBy; ?> <B><A HREF="http://www.phpSecurePages.com" TITLE="phpSecurePages <?PHP echo $strInfo; ?>" TARGET="_blank" CLASS="link">phpSecurePages</A></B></FONT>
	<!-- ------ Copyright line ends here ------ -->
</TD></TR></TABLE>
</form>
</center>

<SCRIPT LANGUAGE="JavaScript">
<!--
document.forms[0].entered_login.select();
document.forms[0].entered_login.focus();
//-->
</SCRIPT>
</body></html>
