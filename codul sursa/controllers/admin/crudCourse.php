<?php
function emptyInput($input)
{
	if(empty($input))
	{
		return false;
	}
	return true;
}
function getFacultyIdForCourse()
{
	require '../../classes/database.php';

	$result = $con->prepare("select * from faculty_professors where professor_id = ?");
	$result->bind_param('i', $_SESSION['idProfessor']);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row['faculty_id'];
} 
function setCourseVariablesOnFalse($crudMethod)
{
	if($crudMethod == "edit")
	{
		$_SESSION['dataUpdadeEntityIsValid'] = false;		
	}
	elseif ($crudMethod == "add") 
	{
		$_SESSION['dataCreateEntityIsValid'] = false;		
	}
}
function addCourse($courseTitleAdd, $idStudyYearAdd, $semesterAdd)
{
	require '../../classes/database.php';

	$result = $con->prepare("insert into courses (id, study_year_id, semester, course_title) VALUES (NULL, ?, ?, ?);");
	$result->bind_param('iis', $idStudyYearAdd, $semesterAdd, $courseTitleAdd);
	$result->execute();
}
function editCourse($courseTitleEdit, $idStudyYearEdit, $semesterEdit)
{
	require '../../classes/database.php';

	$result = $con->prepare("update courses set study_year_id = ?, semester = ?, course_title = ? where courses.id = ?;");
	$result->bind_param('iisi', $idStudyYearEdit, $semesterEdit, $courseTitleEdit, $_SESSION['courseIdForEdit']);
	$result->execute();
}
function redirectionAdminIfCrudCourseDataIsOrNotCorrect($courseTitle, $idStudyYear, $semester, $crudMethod)
{
	require '../../classes/database.php';

	$result = $con->prepare("select count(id) from courses where study_year_id = ? and semester = ? and course_title = ?");
	$result->bind_param('iis', $idStudyYear, $semester, $courseTitle);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	if ($crudMethod == "edit") 
	{
		editCourse($courseTitle, $idStudyYear, $semester);
		header('Location: ../../crudCourse-admin');
	}
	if ($crudMethod == "add") 
	{
		if($row['count(id)'] >= 1)
		{
			$_SESSION['sameDataEntity'] = true;
			header('Location: ../../crudCourse-admin');
		}
		else
		{
			addCourse($courseTitle, $idStudyYear, $semester);
			header('Location: ../../crudCourse-admin');	
		}
	}
}
function redirectionAdminIfCourseDataInputIsOrNotEmpty($courseTitle, $courseTitleIsValid, $idStudyYear, $idStudyYearIsValid, $semester, $semesterIsValid, $crudMethod)
{
	if( $courseTitleIsValid &&
		$idStudyYearIsValid &&
		$semesterIsValid )
	{
		redirectionAdminIfCrudCourseDataIsOrNotCorrect($courseTitle, $idStudyYear, $semester, $crudMethod);
	}
	else
	{
		setCourseVariablesOnFalse($crudMethod);
		header('Location: ../../crudCourse-admin');
	}
}
function createCourse()
{
	require '../../classes/database.php';
	$crudMethod = "add";
	$courseTitleAdd = trim(ucfirst($_POST['courseTitleAdd']));
	$idStudyYearAdd = $_POST['idStudyYear'];
	$semesterAdd = $_POST['semester'];

	$courseTitleAddIsValid = true;
	$idStudyYearAddIsValid = true;
	$semesterAddIsValid = true;

	//my sql injection
	$courseTitleAdd = stripcslashes($courseTitleAdd);
	$courseTitleAdd = mysqli_real_escape_string($con, $courseTitleAdd);

	$courseTitleAddIsValid = emptyInput($courseTitleAdd);
	$idStudyYearAddIsValid = emptyInput($idStudyYearAdd);
	$semesterAddIsValid = emptyInput($semesterAdd);

	redirectionAdminIfCourseDataInputIsOrNotEmpty($courseTitleAdd, $courseTitleAddIsValid, $idStudyYearAdd, $idStudyYearAddIsValid, $semesterAdd, $semesterAddIsValid, $crudMethod);
}
function deleteCourse()
{
	require '../../classes/database.php';

	$result = $con->prepare("delete from courses where id=?");
	$result->bind_param('i', intval($_POST['deleteCourse']));
	$result->execute();

	header('Location: ../../crudCourse-admin');	
}
function deleteAllCourses()
{
	require '../../classes/database.php';

	$facultyId = getFacultyIdForCourse();

	$result = $con->prepare("delete from courses where study_year_id in (select sy.id from study_years sy where sy.faculty_id = ?)");
	$result->bind_param('i', $facultyId);
	$result->execute();
	
	header('Location: ../../crudCourse-admin');	
}
function updateCourse()
{
	require '../../classes/database.php';

	$crudMethod = "edit";

	$courseTitleEdit = trim(ucfirst($_POST['courseTitleEdit']));
	$idStudyYearEdit = $_POST['idStudyYear'];
	$semesterEdit = $_POST['semester'];

	$courseTitleEditIsValid = true;
	$idStudyYearEditIsValid = true;
	$semesterEditIsValid = true;
	//my sql injection
	$courseTitleEdit = stripcslashes($courseTitleEdit);
	$courseTitleEdit = mysqli_real_escape_string($con, $courseTitleEdit);

	$courseTitleEditIsValid = emptyInput($courseTitleEdit);
	$idStudyYearEditIsValid = emptyInput($idStudyYearEdit);
	$semesterEditIsValid = emptyInput($semesterEdit);

	redirectionAdminIfCourseDataInputIsOrNotEmpty($courseTitleEdit, $courseTitleEditIsValid, $idStudyYearEdit, $idStudyYearEditIsValid, $semesterEdit, $semesterEditIsValid, $crudMethod);
}
function exportCourse()
{
	require '../../classes/database.php';
	header('Content-Type: txt/csv; charset=utf-8');
	header('Content-Disposition: attachment; fileName=cursuri.csv');
	$output = fopen("php://output", "w");
	fputcsv($output, array('ID', 'Curs_titlu', 'An_id', 'An_nume', 'Semestru'));

	$facultyId = getFacultyIdForCourse();

	$result = $con->prepare("	SELECT c.id, c.course_title, c.study_year_id, sy.study_year, c.semester from courses c join study_years sy on sy.id=c.study_year_id where sy.faculty_id = ? order by c.study_year_id, c.semester, c.course_title");
	$result->bind_param('i', $facultyId);
	$result->execute();

	$stmt = $result->get_result();

	while($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		fputcsv($output, $row);
	}
	fclose($output);
}
function importCourse()
{
	require '../../classes/database.php';

	if($_FILES['file']['name'])
	{
		$fileName = explode(".", $_FILES['file']['name']);
		if($fileName[1] == 'csv')
		{
			$handle = fopen($_FILES['file']['tmp_name'], "r");
			$data = fgetcsv($handle);
			while($data = fgetcsv($handle))
			{
				$context = explode(';', $data[0]);
				$idStudyYear = $context[0];
				$semester = $context[1];
				$courseTitle = $context[2];
				addCourse($courseTitle, $idStudyYear, $semester);
			}
			fclose($handle);
			header('Location: ../../crudCourse-admin');
		}
	}
}
if(isset($_POST['createCourse']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	createCourse();
	die();
}
if(isset($_POST['deleteCourse']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	deleteCourse();
	die();
}
if(isset($_POST['deleteAllCourses']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	deleteAllCourses();
	die();
}
if(isset($_POST['updateCourse']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	updateCourse();
	die();
}
if(isset($_POST['importCourse']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	importCourse();
	die();
}
if(isset($_POST['exportCourse']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	exportCourse();
	die();
}
class CrudCourse extends Controller {
}
?>