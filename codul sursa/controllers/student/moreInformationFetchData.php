<?php
function fetchFaculties()
{
	
	require './classes/database.php';
	$output = '';
	$result = $con->prepare("select * from faculties");
	$result->execute();

	$stmt = $result->get_result();
	while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		$output .= '<option value="'.$row["id"].'">Facultatea de '.$row["faculty_name"].'</option>';
	}

	return $output;
}
function getOutputForSpecialization($facultyId)
{
	require '../../classes/database.php';
	$output = '<option value="0">Selecteaza specializarea</option>';
	$result = $con->prepare("select * from specialization where faculty_id = ?");
	$result->bind_param('s', $facultyId);
	$result->execute();

	$stmt = $result->get_result();
	while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		$output .= '<option value="'.$row["id"].'">'.$row["specialization_name"].'</option>';
	}

	return $output;
}
function getOutputForYearsOfStudy($facultyId)
{
	require '../../classes/database.php';
	$output = '<option value="0">Selecteaza anul de studiu</option>';
	$result = $con->prepare("select * from study_years where faculty_id = ?");
	$result->bind_param('s', $facultyId);
	$result->execute();

	$stmt = $result->get_result();
	while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		$output .= '<option value="'.$row["id"].'">'.$row["study_year"].'</option>';
	}

	return $output;
}
function getOutputForGroups()
{
	require '../../classes/database.php';
	$output = '<option value="0">Selecteaza grupa</option>';
	$result = $con->prepare("select * from groups where faculty_id = ? and specialization_id = ? and study_year_id = ?");
	$result->bind_param('sss', $_POST["facultyId"], $_POST["specializationId"], $_POST["yearOfStudyId"]);
	$result->execute();

	$stmt = $result->get_result();
	while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		$output .= '<option value="'.$row["id"].'">'.$row["group_name"].'</option>';
	}

	return $output;
}
if(isset($_POST["facultyIdSpecialization"]))
{
	$output = getOutputForSpecialization($_POST["facultyIdSpecialization"]);
	echo $output;
}

if(isset($_POST["facultyIdYear"]))
{
	$output = getOutputForYearsOfStudy($_POST["facultyIdYear"]);
	echo $output;
}

if(isset($_POST["facultyId"]))
{
	$output = getOutputForGroups();
	echo $output;
}
?>