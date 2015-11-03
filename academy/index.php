<?php  // This is the main page for the site.

	// Set the page title and include the HTML header:
	$page_title = 'BCI Academy';
	$page_section = 'root';
	
	require_once ('includes/config.inc.php'); 
	include ('includes/header.html');
	

if (isset($_POST['submitted'])) {

	// Validate the first name:
	if (!empty($_POST['email'])) {
		$e = mysqli_real_escape_string ($dbc, $_POST['email']);
	} else {
		$e = FALSE;
		echo '<p class="error">You forgot to enter your email!</p>';
	}
	
	// Validate the password:
	if (!empty($_POST['pass'])) {
		$p = mysqli_real_escape_string ($dbc, $_POST['pass']);
	} else {
		$p = FALSE;
		echo '<p class="error">You forgot to enter your password!</p>';
	}

	if ($e && $p) { // If everything's OK.
	
		// Query the database:
		$q = "SELECT email, first_name, user_level FROM users WHERE email='$e' AND password=SHA1('$p')";		
		$r = @mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		
		if (@mysqli_num_rows($r) == 1) { // A match was made.

			// Register the values & redirect:
			$_SESSION = mysqli_fetch_array ($r, MYSQLI_ASSOC); 
			echo '<p> You are now logged in </p>';
			mysqli_free_result($r);
			mysqli_close($dbc);
			
			$base = 'index.php'; // Define the URL:
			ob_end_clean(); // Delete the buffer.
			header("Location: $base");
			
		} else { // No match was made.
			echo '<p class="error">The email address and password entered do not match those on file.</p>';
		}
		
	} else { // If everything wasn't OK.
		echo '<p class="error">Please try again.</p>';
	}
	
	
// Print any error messages, if they exist:
if (!empty($errors)) {
	echo '<h1>Error!</h1>
	<p class="error">The following error(s) occurred:<br />';
	foreach ($errors as $msg) {
		echo " - $msg<br />\n";
	}
	echo '</p><p>Please try again.</p>';
}

} // End of the main submit conditional.
?>

<div style="margin-left:25px">
<h1>Login</h1>
<form action="index.php" method="post">
	<p>Email: <input type="text" name="email" size="20" maxlength="80" /> </p>
	<p>Password: <input type="password" name="pass" size="20" maxlength="20" /></p>
	<p><input type="submit" name="submit" value="Login" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
</form>
</div>

<?php include ('includes/footer.html'); ?>