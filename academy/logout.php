<?php 

	// Set the page title and include the HTML header:
	$page_title = 'Logout';
	$page_section = 'Logout';

	require_once ('includes/config.inc.php'); 
	include ('includes/header.html');



// If no first_name session variable exists, redirect the user:
if (!isset($_SESSION['first_name'])) {

	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
	
} else { // Log out the user.

	$_SESSION = array(); // Destroy the variables.
	session_destroy(); // Destroy the session itself.
	setcookie (session_name(), '', time()-300); // Destroy the cookie.
}


// Print a customized message:
echo '<div><h3>You are now logged out.</h3></div>';

include ('includes/footer.html'); //Include the footer
?>
