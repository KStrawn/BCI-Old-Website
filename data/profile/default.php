<?php
	require_once('../PHP-Login/auth.php');
?> 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml">

<!-- #BeginTemplate "../master.dwt" -->

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<!-- #BeginEditable "doctitle" -->
<title>News</title>
<!-- #EndEditable -->
<link href="../styles/style2.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.style1 {
	margin-left: 0;
}
.style2 {
	text-align: left;
}
</style>
</head>

<body>

<!-- Begin Container -->
<div id="container">
	<!-- Begin Masthead -->
	<div id="masthead">
		<img alt="" height="121" src="../images/logo_low.jpg" width="125" />
		<p class="style1">toll-free (888) 269-2719<br />
		office (989) 667-8850<br />
		fax (989) 684-2005</p>
		<p class="style2">Welcome
		<?php
			//session_start();
			echo $_SESSION['SESS_FIRST_NAME'].'.  You are logged in as '.$_SESSION['SESS_FIRST_NAME'].' '.$_SESSION['SESS_LAST_NAME'].'.';
		?>
		</p>
		<p class="style1">&nbsp;<a href="mailto:info@BlessingTheChildren.org">info@BlessingTheChildren.org</a> </p>
		<p class="style1"><a href="http://www.BlessingTheChildren.org">
		www.BlessingTheChildren.org</a> </p>
	</div>
	<!-- End Masthead -->
	<!-- Begin Page Content -->
	<div id="page_content" style="left: 0px; top: 19px">
		<!-- Begin Sidebar -->
		<div id="sidebar">
			<ul>
				<li><a href="../index.php">Home</a></li>
				<li><a href="../children/default.php">Children</a></li>
				<li><a href="../sponsorships/default.php">Sponsorships</a></li>
				<li><a href="../donors/default.php">Donors</a></li>
				<li><a href="../teams/default.php">Teams</a></li>
				<li><a href="../calendar/default.php">Calendar</a></li>
				<li><a href="../contacts/default.php">Contacts</a></li>
				<li><a href="default.php">Profile</a></li>
			</ul>
		</div>
		<!-- End Sidebar -->
		<!-- Begin Content -->
		<div id="content">
			<!-- #BeginEditable "content" -->
			<h2>News</h2>
			<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Quisque 
			ornare ipsum at erat. Quisque elementum tempus urna. Donec ornare fringilla 
			erat. Phasellus gravida lectus vel dui. Fusce eget justo at odio posuere 
			dignissim.</p>
			<h3>Top Stories</h3>
			<ul>
				<li><span class="style_bold">Event Title</span><br />
				<span class="style_italic">Event Description</span><br />
				Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Quisque 
				ornare ipsum at erat. Quisque elementum tempus urna.</li>
				<li><span class="style_bold">Event Title</span><br />
				<span class="style_italic">Event Description</span><br />
				Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Quisque 
				ornare ipsum at erat. Quisque elementum tempus urna.</li>
			</ul>
			<!-- #EndEditable --></div>
		<!-- End Content --></div>
	<!-- End Page Content -->
	<!-- Begin Footer -->
	<div id="footer">
		<p><a href="../index.php">Home</a> | 
		<a href="../children/default.php">Children</a> | 
		<a href="../sponsorships/default.php">Sponsorships</a> |
		<a href="../donors/default.php">Donors</a> |
		<a href="../teams/default.php">Teams</a>  | 
		<a href="../calendar/default.php">Calendar</a> |
		<a href="../contacts/default.php">Contacts</a> |
		<a href="../press/default.php">Press</a> | 
		<a href="../site_map/default.php">Site Map</a><br />
		Copyright © 2012 Blessing the Children International. All Rights Reserved.  </p>
	</div>
	<!-- End Footer --></div>
<!-- End Container -->

</body>

<!-- #EndTemplate -->

</html>
