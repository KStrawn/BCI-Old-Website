<?php

// This file contains the database access information. 
// This file also establishes a connection to MySQL 
// and selects the database.

// Set the database access information as constants:
DEFINE ('DB_USER', 'bciacademy');
DEFINE ('DB_PASSWORD', 'Free22@@');
DEFINE ('DB_HOST', 'bciacademy.db.2824767.hostedresource.com');
DEFINE ('DB_NAME', 'bciacademy');

// Make the connection:
$dbc = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (!$dbc) { //If the connection did not work, display the error.
	trigger_error ('Could not connect to MySQL: ' . mysqli_connect_error() );
}

?>
