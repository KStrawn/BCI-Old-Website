<?php  

	// Set the page title and include the HTML header:
	$page_title = 'Daily Attendance';
	$page_section = 'Attendance';
	
	require_once ('includes/config.inc.php'); 
	include ('includes/header.html');
?>

<link type="text/css" href="css/jquery-ui-1.8.19.custom.css" rel="stylesheet" />
<script type="text/javascript" src="includes/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="includes/jquery-ui-1.8.19.custom.min.js"></script>

<script type="text/javascript"> 
	function YNconfirm(form) {
		if (confirm("<?php //Check if attendance is generated: custom message for if/ifnot
		
			if (isset($_GET['date'])) {
				$sqlsubmitdate = $_GET['date'];
				$submitdate = date("Y-m-d", strtotime($_GET['date']));
			} else {
				$sqlsubmitdate = Date("m/d/Y");
				$submitdate = Date("Y-m-d");
			}
			
			if ($sqlsubmitdate == Date("m/d/Y")) {
				$jsdate = 'today';
			} else {
				$jsdate = 'the selected date';
			}
			
			// Check if there are attendance records for the selected date
			//**************************************************************************************************************
			$query = "SELECT DISTINCT section_id FROM student_attendance WHERE class_date = '$submitdate'";
			$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
			
			if ($results->num_rows == 0) {
				echo 'There are no attendance records generated for ' . $jsdate . '. Would you like to generate them now?';
				$mode = 'generate';
			} else {
				echo 'There are already attendance records generated for ' . $jsdate . '. Are you sure you want to overwrite them?';
				$mode = 'overwrite';
			}
		
		
		?>")) {
			alert("<?php //message for if generated or overwritten 
				if ($mode == 'generate') {
					echo 'Attendance has been generated for ' . $jsdate;
				} else if ($mode == 'overwrite') {
					echo 'Attendance has been overwritten for ' . $jsdate;
				}
			?>");
			return true;
		} else {
			return false;
		}
	}
	
	$(function() {
		
		$( "#datepicker" ).datepicker();

	});

</script> 

<?php
if (isset($_POST['submit'])) {
	$q = "SELECT sr.childid, sr.section_id FROM student_registration sr JOIN student_class_sections scs ON sr.section_id = scs.section_id 
		JOIN student_classes sc ON scs.class_id = sc.class_id WHERE sc.name LIKE '%homeroom%'";
	$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
	$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
	$query5 = "INSERT INTO student_attendance(childid, section_id, class_date) VALUES('$row[childid]', $row[section_id], '$submitdate')";

	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		$query5 .= ",('$row[childid]', $row[section_id], '$submitdate')";
	}
	$query5 .= " ON DUPLICATE KEY UPDATE status = NULL";
	$results = mysqli_query ($dbc, $query5) or trigger_error("Query: $query5\n<br />MySQL Error: " . mysqli_error($dbc));

} else {
	$q = "SELECT sr.childid, sr.section_id FROM student_registration sr JOIN student_class_sections scs ON sr.section_id = scs.section_id 
		JOIN student_classes sc ON scs.class_id = sc.class_id WHERE sc.name LIKE '%homeroom%'";
	$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
	$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
	$query5 = "INSERT INTO student_attendance(childid, section_id, class_date) VALUES('$row[childid]', $row[section_id], '$submitdate')";

	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		$query5 .= ",('$row[childid]', $row[section_id], '$submitdate')";
	}
	$query5 .= " ON DUPLICATE KEY UPDATE status = NULL";

}
	
// Start of left column
//**************************************************************************************************************
echo '<div id="da">
	<div id="daleft">
	<p> For Today - by home-room: </p>';

	
// Set the page variables
//**************************************************************************************************************
$date = Date("m/d/Y");
$sqldate = Date("Y-m-d");

	
// Query to get the list of homeroom classes
//**************************************************************************************************************
$query = "SELECT scs.section_id, sc.name FROM student_class_sections scs JOIN student_classes sc ON scs.class_id = sc.class_id 
			WHERE sc.name LIKE '%homeroom%'";
$results1 = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));


// Tables - one for each homeroom instance
//**************************************************************************************************************
while ($table = mysqli_fetch_array($results1, MYSQLI_ASSOC)) {
	echo '<table>';
	echo '<thead><tr><th scope="col">' . $table['name'] . '</th><th scope="col" width="100px">' . $date . '</th></tr></thead><tbody>';
	
	// Query to get the list of students in a homeroom		-*- Also need to make sure it's a homeroom
	//**************************************************************************************************************
	$query = "SELECT s.ChildsName, sa.status FROM student s JOIN student_attendance sa ON s.childid = sa.childid 
	WHERE sa.section_id = {$table['section_id']} 
	AND sa.class_date = '{$sqldate}' ";
	$results2 = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
	
	while ($student = mysqli_fetch_array($results2, MYSQLI_ASSOC)) {
		echo '<tr><td>' . $student['ChildsName'] . '</td><td style="text-align:center">' . $student['status'] . '</td></tr>';
	}

	echo '</table>';
}
?>

	</div>
	<div id="daright">
		<table style="width:250px">
			<tr><th> Legend </th><th></th></tr>
			<tr><td> Present </td><td> P </td></tr>
			<tr><td> Absent </td><td> A </td></tr>
			<tr><td> Excused Absence </td><td> E </td></tr>
			<tr><td> Tardee </td><td> T </td></tr>
			<tr><td> Excused Tardee </td><td> X </td></tr>
		</table>
		<form enctype="multipart/form-data" action="daily_attendance.php" method="get">
			<input type="text" name="date" id="datepicker" value="<?php echo $sqlsubmitdate; ?>" onchange="this.form.submit()"/>
		</form>
		<form enctype="multipart/form-data" id="generate" action="daily_attendance.php?date=<?php echo $sqlsubmitdate; ?>" method="post" onsubmit="return YNconfirm(this);">
			<button name="submit"> Generate Attendance </button>
		</form>
	</div>
</div>

	
<?php include ('includes/footer.html'); ?>