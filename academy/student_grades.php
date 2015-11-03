<?php
// This is the main page for the site.

// Include the configuration file:
require_once ('includes/config.inc.php'); 

// Set the page title and include the HTML header:
$page_title = 'Conference Finder';
include ('includes/header.html');

require_once ('includes/mysqli_connect.php'); //Connect to the DB
?>

<!--------------------------------- CONTENT ------------------------------------------>



<p>  content </p>


<?php include ('includes/footer.html'); ?>