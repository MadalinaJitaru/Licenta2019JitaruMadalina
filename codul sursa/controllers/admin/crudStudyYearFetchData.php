<?php
if(isset($_POST['editStudyYearId']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}
	$_SESSION['studyYearIdForEdit'] = $_POST['editStudyYearId'];
	echo updateStudyYearFetchData();
}
function requireDatabaseForStudyYear()
{
	if(isset($_POST['editStudyYearId']))
	{
		require '../../classes/database.php';			
	}
	else
	{
		require './classes/database.php';
	}
	return $con;
}
function getFacultyIdForEditStudyYear()
{
	$con = requireDatabaseForStudyYear();

	$result = $con->prepare("select * from faculty_professors where professor_id = ?");
	$result->bind_param('i', $_SESSION['idProfessor']);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row['faculty_id'];
}
function fetchDataStudyYearForUpdate($studyYearId)
{
	$facultyId = getFacultyIdForEditStudyYear();

	$con = requireDatabaseForStudyYear();

	$result = $con->prepare("select * from study_years where id=?");
	$result->bind_param('i', $studyYearId);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);
	return $row;
}
function readStudyYearFetchData()
{
	$output = '
	<span class="container" style="cursor:default">
	<div class="gridContainerStudyYear">
	<div class="gridItem">Nr.</div>
	<div class="gridItem">Anul de studiu</div>
	</div>
	</span>';
	$indexStudyYear = 0;
	$facultyId = getFacultyIdForEditStudyYear();

	require './classes/database.php';

	$result = $con->prepare('select * from study_years where faculty_id=?');
	$result->bind_param('i', $facultyId);
	$result->execute();

	$stmt = $result->get_result();
	while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		$indexStudyYear += 1;
		$output .= '
		<label onclick="hideElement(\'optionError\')" for="line'.$indexStudyYear.'" class="container">';
		if(isset($_SESSION['studyYearIdForEdit']) && $row['id'] == $_SESSION['studyYearIdForEdit'])
		{
			$output .= '<input checked id="line'.$indexStudyYear.'" type="radio" name="studyYear" value="'.$row['id'].'">';	
		}
		else
		{
			$output .= '<input id="line'.$indexStudyYear.'" type="radio" name="studyYear" value="'.$row['id'].'">';
		}
		$output .= '
		<div class="gridContainerStudyYear">
		<div class="gridItem">'.$indexStudyYear.'. </div>
		<div class="gridItem">'.ucfirst($row["study_year"]).'</div>
		</div>
		</label>';
	}
	return $output;
}
function createStudyYearFetchData()
{
	$output = '
	<h1>Adaugare an de studiu</h1>
	<form action="./controllers/admin/crudStudyYear.php" method="post">
	<div class="crudPopUpContainer">
	<div class="crudInputContainer">
	<label for="studyYearAdd">Anul de studiu</label>
	<input type="text" name="studyYearAdd" id="studyYearAdd" placeholder="Introduceti anul de studiu">
	<br>
	</div>
	</div>
	<div class="crudEntityButton">
	<button type="submit" name="createStudyYear">Adauga</button>
	</div>
	</form>';
	return $output;
}
function updateStudyYearFetchData()
{
	$row = fetchDataStudyYearForUpdate($_SESSION['studyYearIdForEdit']);
	$output = '
	<h1>Modificare an de studiu</h1>
	<form action="./controllers/admin/crudStudyYear.php" method="post">
	<div class="crudPopUpContainer">
	<div class="crudInputContainer">
	<label for="studyYearEdit">Anul de studiu</label>
	<input type="text" name="studyYearEdit" id="studyYearEdit" placeholder="Introduceti anul de studiu" value="'.$row["study_year"].'">
	<br>
	</div>
	</div>
	<div class="crudEntityButton">
	<button type="submit" name="updateStudyYear">Modifica</button>
	</div>
	</form>
	';
	return $output;
}
?>