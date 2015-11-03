<?php

	// Set the page title and include the HTML header:
	$page_title = 'Insert User';
	$page_section = 'Root';

	require_once ('includes/config.inc.php'); 
	include ('includes/header.html');


if (isset($_POST['submitted'])) { // Handle the form.

	
	// Trim all the incoming data:
	$trimmed = array_map('trim', $_POST);
	
	// Assume invalid values:
	$email = $fname = $lname = $pass = $pass2 = $user_level = FALSE;
	
	$email = mysqli_real_escape_string ($dbc, $trimmed['email']);
	$fname = mysqli_real_escape_string ($dbc, $trimmed['fname']);
	$lname = mysqli_real_escape_string ($dbc, $trimmed['lname']);
	$pass = mysqli_real_escape_string ($dbc, $trimmed['password']);
	$pass2 = mysqli_real_escape_string ($dbc, $trimmed['password2']);
	$user_level = intval(mysqli_real_escape_string ($dbc, $trimmed['user_level']));
	
	
	if ($email && $fname && $lname && $pass && $user_level) { // If everything's OK...
		// Check if the email is valid:
		if (!check_email_address($email)) {
			echo '<p class="error"> The email address is invalid </p>';
		}
		
		// Check if the passwords match
		if (!($pass == $pass2)) {
			echo '<p class="error"> The passwords do not match! </p>';
		}
		
		// Add the user to the database:
		$q = "INSERT INTO users (email, first_name, last_name, password, user_level) VALUES ('$email', '$fname', '$lname', SHA1('$pass'), $user_level)";
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
			echo '<div><h3> The user has been added. </h3></div>';
		} else { // If it did not run OK.
			echo '<p class="error"> The users has not been added. </p>';
		}
	} else { // If one of the data tests failed.
		echo '<p class="error">Something failed!</p>';
	}

	mysqli_close($dbc);  //Close the connection

} // End of the main Submit conditional.

function check_email_address($email) {
	// First, we check that there's one @ symbol, 
	// and that the lengths are right.
	if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
		// Email invalid because wrong number of characters 
		// in one section or wrong number of @ symbols.
		return false;
	}
	// Split it into sections to make life easier
	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);
	for ($i = 0; $i < sizeof($local_array); $i++) {
		if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&
		?'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",
		$local_array[$i])) {
			return false;
		}
	}
	// Check if domain is IP. If not, 
	// it should be valid domain name
	if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
		$domain_array = explode(".", $email_array[1]);
		if (sizeof($domain_array) < 2) {
			return false; // Not enough parts to domain
		}
		for ($i = 0; $i < sizeof($domain_array); $i++) {
			if
			(!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
			?([A-Za-z0-9]+))$",
			$domain_array[$i])) {
				return false;
			}
		}
	}
	return true;
}

?>

<div id="iu" >
	<h1> Add a User </h1>

	<form enctype="multipart/form-data" action="insert_user.php" method="post">
		<label for="email"> Email: </label> <input type="text" id="email" name="email" maxlength="64" class="input" value="<?php if (isset($trimmed['email'])) echo $trimmed['email']; ?>" /><br />
		<label for="fname"> First Name: </label> <input type="text" id="fname" name="fname" maxlength="30" class="input" value="<?php if (isset($trimmed['fname'])) echo $trimmed['fname']; ?>" /><br />
		<label for="lname"> Last Name: </label> <input type="text" id="lname" name="lname" maxlength="30" class="input" value="<?php if (isset($trimmed['lname'])) echo $trimmed['lname']; ?>" /><br />
		<label for="password"> Password: </label> <input type="password" id="password" name="password" maxlength="20" class="input" value="<?php if (isset($trimmed['password'])) echo $trimmed['password']; ?>" /><br />
		<label for="password2"> Confirm Password: </label> <input type="password" id="password2" name="password2" maxlength="20" class="input" value="<?php if (isset($trimmed['password2'])) echo $trimmed['password2']; ?>" /><br />
		<label for="user_level"> User Level: </label> <input type="text" id="user_level" name="user_level" maxlength="20" class="input" value="<?php if (isset($trimmed['user_level'])) echo $trimmed['user_level']; ?>" /><br />
		
		<label>&nbsp; </label> <input type="submit" name="submitted" value="Add!" class="button"/>
	</form>
</div>

<?php include ('includes/footer.html'); ?>