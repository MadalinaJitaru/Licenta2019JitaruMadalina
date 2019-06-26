<?php
function getMoreInformationDataFromDatabase()
{
	require '../../classes/database.php';

	$result = $con->prepare("select * from groups where id = ?");
	$result->bind_param('i', $_SESSION['groupIdStudent']);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row;	
}
function redirectionStudentIfMoreInformationDataIsOrNotCorrect()
{
	$row = getMoreInformationDataFromDatabase();
	if ($row['faculty_id'] == $_POST['facultySelect'] && 
		$row['specialization_id'] == $_POST['specializationSelect'	] &&
		$row['study_year_id'] == $_POST['yearOfStudySelect'] &&
		$_SESSION['groupIdStudent'] == $_POST['groupSelect'])
	{
		$_SESSION['validStudent'] = true;
		header('Location: ../../chooseProfessor-student');
	}
	else
	{
		$_SESSION['invalidData'] = true;
		//echo $_SESSION['invalidData'];
		header('Location: ../../moreInformation-student');
	}
}
if(isset($_POST['next']))
{
	if (session_status() == PHP_SESSION_NONE)
	{
		session_start();
	}

	redirectionStudentIfMoreInformationDataIsOrNotCorrect();

	die();
}	
class MoreInformation extends Controller 
{
}
?>