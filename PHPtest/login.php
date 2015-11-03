<?php
session_start(); // This starts the session which is like a cookie, but it isn't saved on your hdd and is much more secure.

if(isset($_SESSION['loggedin']))
{
    die("You are already logged in!");
} // That bit of code checks if you are logged in or not, and if you are, you can't log in again!
if(isset($_POST['submit']))
{
   $name = $_POST['username']; // The function mysql_real_escape_string() stops hackers!
   $pass = $_POST['password']; 
   // We won't use MD5 encryption here because it is the simple tutorial, if you don't know what MD5 is, dont worry!

   $_SESSION['loggedin'] = "YES"; // Set it so the user is logged in!
   $_SESSION['name'] = $name; // Make it so the username can be called by $_SESSION['name']
   die("You are now logged in!"); // Kill the script here so it doesn't show the login form after you are logged in!
} // That bit of code logs you in! The "$_POST['submit']" bit is the submission of the form down below VV

echo "<form type='login.php' method='POST'>
Username: <br>
<input type='text' name='username'><br>
Password: <br>
<input type='password' name='password'><br>
<input type='submit' name='submit' value='Login'>
</form>"; // That set up the form to enter your password and username to login.
?>