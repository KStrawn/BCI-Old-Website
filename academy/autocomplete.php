<?php

//require_once ('includes/mysqli_connect.php');

/* Connection vars here for example only. Consider a more secure method. 
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'bciacademy';
 
try {
  $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
}
catch(PDOException $e) {
    echo $e->getMessage();
}*/
 
$return_arr = array();
 /*
if ($conn)
{
    $ac_term = $_GET['term']."%";
    $query = "SELECT ChildsName FROM students WHERE ChildsName like :term";
    $result = $conn->prepare($query);
    $result->bindValue(":term",$ac_term);
    $result->execute();*/
 
    /* Retrieve and store in array the results of the query.
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $row_array['id'] = $row['id'];
        $row_array['value'] = $row['state'];
        $row_array['abbrev'] = $row['abbrev'];
 
        array_push($return_arr,$row_array);
    }
 
}*/
/* Free connection resources. 
$conn = null; */
/* Toss back results as json encoded array. */




/* Connection vars here for example only. Consider a more secure method. */
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'bciacademy';
 
$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
mysql_select_db($dbname);
 
 
/* If connection to database, run sql statement. */
if ($conn)
{
    $fetch = mysql_query("SELECT ChildsName FROM students where ChildsName like '%" . mysql_real_escape_string($_GET['term']) . "%'");
 
    /* Retrieve and store in array the results of the query.*/
    while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) {
 
        array_push($return_arr,$row['ChildsName']);
    }
}
 
/* Free connection resources. */
mysql_close($conn);
 
/* Toss back results as json encoded array. */
echo json_encode($return_arr);



/*
if ($dbc) {

	$query = "SELECT ChildsName FROM students WHERE ChildsName like " . $_GET['term'] . "%";
	
	$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
	
	// Fetch and print all the records:
	while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
		array_push($return_arr,$row['ChildsName']);
	}
	
}*/


echo json_encode($return_arr);

?>