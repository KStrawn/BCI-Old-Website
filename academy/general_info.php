<?php

	// Set the page title and include the HTML header:
	$page_title = 'General Info';
	$page_section = 'Students';

	require_once ('includes/config.inc.php'); 
	include ('includes/header.html');
?>

<link type="text/css" href="css/jquery-ui-1.8.19.custom.css" rel="stylesheet" />
<script type="text/javascript" src="includes/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="includes/jquery-ui-1.8.19.custom.min.js"></script>

<script type="text/javascript">
	function SelectAll(id) {
		document.getElementById(id).focus();
		document.getElementById(id).select();
	}
	
	$(function(){
		
		var studentList = [
			<?php // PHP to create the array of child names
	
				$query = "SELECT ChildID, ChildsName FROM student";
				$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
				
				// Makes sure there are child names selected
				if ($results->num_rows == 0) {
					echo '<strong> There are no staff in the table! </strong>';
				} else {
					// Fetch and print all the records:
					while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
						echo '{ label: "' . $row['ChildsName'] . '", value: "' . $row['ChildID'] . '" },';
					}
				}
			?>
		];
		
		$( "#students" ).autocomplete({
			source: studentList
		});
		
		
		
	});
</script>




<?php

if (!isset($_GET['id'])) { // If a student id has not been submitted

	// Form to display the EMPTY student name/id selection box
	//**************************************************************************************************************
	echo '<form action="general_info.php" method="GET" class="topleft" >
	<label for="students">Student: </label>
	<input id="students" name="id" />
	</form>';
	
} else { // if a student id has been selected
	
	// Query to get the name of the student selected
	//**************************************************************************************************************
	$query = "SELECT ChildsName FROM student WHERE ChildID = '{$_GET['id']}'";
	$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
	$student = mysqli_fetch_array($results, MYSQLI_ASSOC);
	
	// Set the page variables
	//**************************************************************************************************************
	$childid = $_GET['id'];
	$childName = $student['ChildsName'];
	
	// Form to display the FILLED IN student name/id selection box
	//**************************************************************************************************************
	echo '<form action="general_info.php" method="GET" class="topleft" >
	<label for="students">Student: </label>
	<input id="students" onClick="SelectAll(\'students\');" name="id" value="' . $childid . '" />
	</form>';
	
	// Query to get the information for the selected student
	//**************************************************************************************************************
    $q = "SELECT ChildsName, ChildID, DateEntered, IsActive, DOB, Age, Gender FROM student WHERE ChildID = '" . $childid . "'";
    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
	$student = mysqli_fetch_array($r, MYSQLI_ASSOC);
	
	// Validate that the selected student's id is in the database
	if ($r->num_rows == 0) {
		echo '<br /><strong style="color:red;margin-left:40px"> That Child ID is invalid! </strong>';
	} else {
		
		// Check if there is additional data for the child
		$q = "SELECT student_additional_data.ChildID from student, student_additional_data WHERE student_additional_data.ChildID = '{$student['ChildID']}'";
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		
		if ($r->num_rows == 0) {
			echo '<br /><p><strong> There is no picture for this student. Would you like to add it now? </strong></p>';
			
			// Form for adding child additional data:
			echo '
			<form enctype="multipart/form-data" action="student_profile.php" method="get" style="width:600px">
			  <fieldset>
				<legend>Student Picture:</legend>
				<label for="picture">File: </label> <input type="file" id="picture" class="input" name="upload"/><br />
				<label>&nbsp; </label> <input type="submit" value="Submit" name="upload" class="button"/>
				<input type="hidden" name="name" value="' . $childName . '" />
			 </fieldset>
			</form>';
			
			
			
		} 
			
			// The child's data:
			echo '<div style="float:left;margin-left:200px;margin-top:20px"><label> Student ID: </label><input type="text" name="id" value="' . 
			$student['ChildID'] . '" readonly="readonly" /></div><br />';
			
			echo '<div id="gi">';
			echo '<div id="gileft">';
			echo '<img src="pictures/' . $childName . '.jpg" alt="' . $childName . '.jpg" class="studentpicture">';
			echo '<br />';
			echo '<br />';
			echo '<label> Grade: </label><input type="text" id="grade" value="' . $childName . '" readonly="readonly" /><br />';
			echo '<label> GPA: </label><input type="text" id="gpa" value="' . $childName . '" readonly="readonly" /><br />';
			echo '</div>';
			
			echo '<div id="gicenter">';
			echo '<label> Name: </label><input type="text" id="name" value="' . $childName . '" readonly="readonly" /><br />';
			echo '<label> Nickname: </label><input type="text" id="name" value="' . $childName . '" readonly="readonly" /><br />';
			echo '<br />';
			echo '<label> Gender: </label><input type="text" id="gender" value="' . $student['Gender'] . '" readonly="readonly" /><br />';
			echo '<label> Birthdate: </label><input type="text" id="name" value="' . $student['DOB'] . '" readonly="readonly" /><br />';
			echo '<label> Age: </label><input type="text" id="name" value="' . $student['Age'] . '" readonly="readonly" /><br />';
			echo '<br />';
			echo '<label style="float:left"> Notes: </label><textarea rows="3"></textarea>';
			echo '</div>';
			
			echo '<div id="giright">';
			echo '<label> Father: </label><input type="text" id="name" value="' . $childName . '" readonly="readonly" /><br />';
			echo '<label> Father Phone: </label><input type="text" id="name" value="' . $childName . '" readonly="readonly" /><br />';
			echo '<label> Mothers Name: </label><input type="text" id="name" value="' . $childName . '" readonly="readonly" /><br />';
			echo '<label> Mother Phone: </label><input type="text" id="name" value="' . $childName . '" readonly="readonly" /><br />';
			echo '<label> Guardian: </label><input type="text" id="name" value="' . $childName . '" readonly="readonly" /><br />';
			echo '<label> Guardian Phone: </label><input type="text" id="name" value="' . $childName . '" readonly="readonly" /><br />';
			echo '<br />';
			echo '<label> BCI: </label><input type="text" id="name" value="' . $childName . '" readonly="readonly" /><br />';
			echo '<label> Student since: </label><input type="text" id="name" value="' . $childName . '" readonly="readonly" />';
			echo '</div></div>';
			
			
			
		
		
		
	}
} // End of SUBMIT conditional.

/*
if (isset($_POST['upload'])) {
	
	// Check for an uploaded file:
	if (isset($_FILES['upload'])) {
		
		$childName = $_POST['name'];
		
		// Validate the type. Should be JPEG or PNG.
		$allowed = array ('image/jpg', 'image/jpeg');
		if (in_array($_FILES['upload']['type'], $allowed)) {
		
			$imageName = $childName . '.' . pathinfo($_FILES['upload']['name'],PATHINFO_EXTENSION);
			// Move the file over.
			if (move_uploaded_file ($_FILES['upload']['tmp_name'], "pictures/" . $imageName)) {
				//echo '<p><em>The file has been uploaded!</em></p>';
			} // End of move... IF.
			
			$q = "INSERT INTO student_additional_data VALUES ((SELECT ChildID FROM student WHERE ChildsName = '{$childName}'), ' ')";
			$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
			
			
		} else { // Invalid type.
			echo '<p class="error">Please upload a jpg image.</p>';
		}

	} // End of isset($_FILES['upload']) IF.
}*/

include ('includes/footer.html');
?>