<?php 
function getFacultyIdForChooseOption()
{
	require '../../classes/database.php';

	$result = $con->prepare("select * from faculty_professors where professor_id = ?");
	$result->bind_param('i', $_SESSION['idProfessor']);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row['faculty_id'];
}
function deleteStudents()
{
	require '../../classes/database.php';

	$facultyId = getFacultyIdForChooseOption();

	$result = $con->prepare("delete from students where group_id in (SELECT g.id from groups g where g.faculty_id = ?)");
	$result->bind_param('i', $facultyId);
	$result->execute();
}
function deleteProfessors()
{
	require '../../classes/database.php';

	$facultyId = getFacultyIdForChooseOption();
	$isAdmin = 0;

	$result = $con->prepare("DELETE from professors  where id in (Select fp.professor_id from faculty_professors fp where fp.faculty_id = ?) and is_admin = ?");
	$result->bind_param('ii', $facultyId, intval($isAdmin));
	$result->execute();

	header('Location: ../../crudProfessor-admin');
}
function deleteQuestion()
{
	require '../../classes/database.php';

	$facultyId = getFacultyIdForChooseOption();

	$result = $con->prepare("delete from questions where id in (select fq.question_id from faculty_questions fq where fq.faculty_id = ?)");
	$result->bind_param('i', $facultyId);
	$result->execute();
}
function deleteStudyYears()
{
	require '../../classes/database.php';

	$facultyId = getFacultyIdForChooseOption();
	
	$result = $con->prepare("delete from study_years where faculty_id = ?");
	$result->bind_param('i', $facultyId);
	$result->execute();

	header('Location: ../../crudStudyYear-admin');	
}
function deleteSpecialization()
{
	require '../../classes/database.php';

	$facultyId = getFacultyIdForChooseOption();

	$result = $con->prepare("delete from specialization where faculty_id = ? ");
	$result->bind_param('i', $facultyId);
	$result->execute();

	header('Location: ../../crudSpecialization-admin');
}
function deleteAllInformationDataBase()
{
	deleteStudents();
	deleteProfessors();
	deleteQuestion();
	deleteStudyYears();
	deleteSpecialization();

	header('Location: ../../chooseOption-admin');
}
if(isset($_POST['deleteAllInformationDataBase']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	deleteAllInformationDataBase();
	die();
}
class ChooseOption extends Controller {

}
?>