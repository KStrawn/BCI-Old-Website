<?php  

	// Set the page title and include the HTML header:
	$page_title = 'Report';
	$page_section = 'Attendance';
	
	require_once ('includes/config.inc.php'); 
	include ('includes/header.html');
?>


<form action="./" method="POST">
	<select name="state" onchange="this.form.submit();">
	<option>Choose One To Submit This Form</option>
	<option value="CA">CA</option>
	<option value="VA">VA</option>
	</select>
</form>


<?php include ('includes/footer.html'); ?>