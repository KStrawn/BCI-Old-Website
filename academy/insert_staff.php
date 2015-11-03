<?php

	// Set the page title and include the HTML header:
	$page_title = 'Insert Staff';
	$page_section = 'Staff';

	require_once ('includes/config.inc.php'); 
	include ('includes/header.html');


if (isset($_POST['submitted'])) { // Handle the form.

	
	// Trim all the incoming data:
	$trimmed = array_map('trim', $_POST);
	
	// Assume invalid values:
	$id = $fname = $lname = FALSE;
	
	$id = intval(mysqli_real_escape_string ($dbc, $trimmed['staff_id']));
	$fname = mysqli_real_escape_string ($dbc, $trimmed['first_name']);
	$lname = mysqli_real_escape_string ($dbc, $trimmed['last_name']);
	

	
	if ($id && $fname && $lname) { // If everything's OK...
			// Check if the staff data is valid:
			
			
			// Add the staff to the database:
			$q = "INSERT INTO staff (staff_id, first_name, last_name) VALUES ('$id', '$fname', '$lname')";
			$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
			
			if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
			
				// Finish the page:
				echo '<div><h3>Thank you for adding a staff!</h3></div>';
				echo '</body></html>';

				exit(); // Stop the page.
				
			} else { // If it did not run OK.
				echo '<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';
			}
					
	} else { // If one of the data tests failed.
		echo '<p class="error">Something failed!</p>';
	}

	mysqli_close($dbc);  //Close the connection

} // End of the main Submit conditional.
?>


<!--------------------------------- CONTENT ------------------------------------------>

<style type="text/css">

.input:hover {
    background: #F5F5F5;
}
label {
    display: block;
    width: 90px;
    float: left;
    margin: 2px 4px 6px 4px;
    text-align: right;
}
br { clear: left; }

</style>

<h1> Add a Staff Member </h1>


<form enctype="multipart/form-data" action="insert_staff.php" method="post">

	
	<label for="id"> Id: </label> <input type="text" id="id" name="staff_id" size="5" maxlength="8" class="input" value="<?php if (isset($trimmed['staff_id'])) echo $trimmed['staff_id']; ?>" /><br />
	<label for="fname"> First Name: </label> <input type="text" id="fname" name="first_name" size="16" maxlength="20" class="input" value="<?php if (isset($trimmed['first_name'])) echo $trimmed['first_name']; ?>" /><br />
	<label for="lname"> Last Name: </label> <input type="text" id="lname" name="last_name" size="16" maxlength="20" class="input" value="<?php if (isset($trimmed['last_name'])) echo $trimmed['last_name']; ?>" /><br />
	
	<label>&nbsp; </label> <input type="submit" name="submitted" value="Add!" class="button"/>
	


</form>
	

<?php include ('includes/footer.html'); ?>