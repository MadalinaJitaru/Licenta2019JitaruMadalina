<?php
function emptyInput($input)
{
	if(empty($input) && $input != 0)
	{
		return false;
	}
	return true;
}
function getFacultyIdForProfessor()
{
	require '../../classes/database.php';

	$result = $con->prepare("select * from faculty_professors where professor_id = ?");
	$result->bind_param('i', $_SESSION['idProfessor']);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row['faculty_id'];
} 
function setProfessorVariablesOnFalse($crudMethod)
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
function getLastIdProfessor()
{
	require '../../classes/database.php';

	$result = $con->prepare("select max(id) from professors");
	$result->execute();
	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row['max(id)'];
}
function addProfessorOnFacultyProfessors($idProfessor)
{
	$facultyId = getFacultyIdForProfessor();
	require '../../classes/database.php';
	$result = $con->prepare("insert into faculty_professors (id, faculty_id, professor_id) VALUES (NULL, ?, ?);");
	$result->bind_param('ii', $facultyId, $idProfessor);
	$result->execute();
}
function addCoursesForProfessor($idProfessor, $isTitular)
{
	require '../../classes/database.php';

	foreach ($_POST['idsCourse'] as $idCourse) 
	{
		$result = $con->prepare("insert into professor_courses (id, professor_id, course_id, is_titular) VALUES (NULL, ?, ?, ?);");
		$result->bind_param('iii', $idProfessor, $idCourse, $isTitular);
		$result->execute();
	}
}
function addProfessor($titleProfessorAdd, $firstNameProfessorAdd, $lastNameProfessorAdd, $emailProfessorAdd, $passwordProfessorAdd, $isAdminAdd, $isTitularAdd)
{
	require '../../classes/database.php';

	$result = $con->prepare("INSERT INTO professors (id, first_name, last_name, title, email, password, is_admin) VALUES (NULL, ?, ?, ?, ?, ?, ?)");
	$result->bind_param('sssssi', $firstNameProfessorAdd, $lastNameProfessorAdd, $titleProfessorAdd, $emailProfessorAdd, $passwordProfessorAdd, $isAdminAdd);
	$result->execute();

	$lastIdProfessor = getLastIdProfessor();
	addProfessorOnFacultyProfessors($lastIdProfessor);
	addCoursesForProfessor($lastIdProfessor, $isTitularAdd);
}
function addProfessorForExpor($titleProfessorAdd, $firstNameProfessorAdd, $lastNameProfessorAdd, $emailProfessorAdd, $passwordProfessorAdd, $isAdminAdd)
{
	require '../../classes/database.php';

	$result = $con->prepare("INSERT INTO professors (id, first_name, last_name, title, email, password, is_admin) VALUES (NULL, ?, ?, ?, ?, ?, ?)");
	$result->bind_param('sssssi', $firstNameProfessorAdd, $lastNameProfessorAdd, $titleProfessorAdd, $emailProfessorAdd, $passwordProfessorAdd, $isAdminAdd);
	$result->execute();

	$lastIdProfessor = getLastIdProfessor();
	addProfessorOnFacultyProfessors($lastIdProfessor);	
}
function getDataProfessorCourses($idCourse, $idProfessor, $isTitular)
{
	require '../../classes/database.php';
	$resultVerify = $con->prepare("select count(id), id from professor_courses where professor_id = ? and course_id = ? and is_titular = ?");
	$resultVerify->bind_param('iii', $idProfessor, $idCourse, $isTitular);
	$resultVerify->execute();
	$stmtVerify = $resultVerify->get_result();
	$rowVerify = mysqli_fetch_array($stmtVerify, MYSQLI_ASSOC);

	return $rowVerify;
}
function editCoursesForProfessor($isTitular)
{
	$facultyId = getFacultyIdForProfessor();

	require '../../classes/database.php';

	$resultCourse = $con->prepare("select c.id from courses c join study_years sy on sy.id=c.study_year_id where sy.faculty_id = ?");
	$resultCourse->bind_param('i', $facultyId);
	$resultCourse->execute();

	$stmtCourse = $resultCourse->get_result();

	while ($rowCourse = mysqli_fetch_array($stmtCourse, MYSQLI_ASSOC))
	{
		if(in_array($rowCourse['id'], $_POST['idsCourse']))
		{			
			$rowVerify = getDataProfessorCourses($rowCourse['id'], $_SESSION['professorIdForEdit'], $isTitular);
			
			if($rowVerify['count(id)'] == 0)
			{
				$result = $con->prepare("insert into professor_courses (id, professor_id, course_id, is_titular) VALUES (NULL, ?, ?, ?);");
				$result->bind_param('iii', $_SESSION['professorIdForEdit'], $rowCourse['id'], $isTitular);
				$result->execute();	
			}
			
			else
			{
				$result = $con->prepare("update professor_courses set professor_id = ?, course_id = ?, is_titular = ? where professor_courses.id = ?;");
				$result->bind_param('iiii', $_SESSION['professorIdForEdit'], $rowCourse['id'], $isTitular, $rowVerify['id']);
				$result->execute();
			}
		}
		else
		{
			$rowVerify = getDataProfessorCourses($rowCourse['id'], $_SESSION['professorIdForEdit'], $isTitular);
			if($rowVerify['count(id)'] == 1)
			{
				$result = $con->prepare("delete from professor_courses where id=?");
				$result->bind_param('i', $rowVerify['id']);
				$result->execute();
			}	
		}
	}
}
function editProfessor($titleProfessor, $firstNameProfessor, $lastNameProfessor, $emailProfessor, $isAdmin, $isTitular)
{
	require '../../classes/database.php';

	$result = $con->prepare("update professors set first_name = ?, last_name = ?, title = ?, email = ?, is_admin = ? where professors.id = ?;");
	$result->bind_param('ssssii', $firstNameProfessor, $lastNameProfessor, $titleProfessor, $emailProfessor, $isAdmin, $_SESSION['professorIdForEdit']);
	$result->execute();

	editCoursesForProfessor($isTitular);
}
function redirectionAdminIfCrudProfessorDataIsOrNotCorrect($titleProfessor, $firstNameProfessor, $lastNameProfessor, $emailProfessor, $passwordProfessor, $isAdmin, $isTitular, $crudMethod)
{
	require '../../classes/database.php';

	$result = $con->prepare("select count(p.id) from professors p join professor_courses pc on pc.professor_id=p.id where p.email = ? and p.is_admin = ? and pc.is_titular = ?");
	$result->bind_param('sii', $emailProfessor, $isAdmin, $isTitular);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	if ($crudMethod == "edit") 
	{
		editProfessor($titleProfessor, $firstNameProfessor, $lastNameProfessor, $emailProfessor, $isAdmin, $isTitular);

		header('Location: ../../crudProfessor-admin');
	}
	if ($crudMethod == "add") 
	{
		if($row['count(p.id)'] >= 1)
		{
			$_SESSION['sameDataEntity'] = true;
			header('Location: ../../crudProfessor-admin');
		}
		else
		{
			addProfessor($titleProfessor, $firstNameProfessor, $lastNameProfessor, $emailProfessor, $passwordProfessor, $isAdmin, $isTitular);
			header('Location: ../../crudProfessor-admin');	
		}
	}
}
function redirectionAdminIfProfessorDataInputIsOrNotEmpty($titleProfessor, $titleProfessorIsValid, $firstNameProfessor, $firstNameProfessorIsValid, $lastNameProfessor, $lastNameProfessorIsValid, $emailProfessor, $emailProfessorIsValid, $passwordProfessor, $passwordProfessorIsValid, $isAdmin, $isAdminIsValid, $isTitular, $isTitularIsValid, $idsCourseIsValid, $crudMethod)
{
	if( $titleProfessorIsValid &&
		$firstNameProfessorIsValid &&
		$lastNameProfessorIsValid &&
		$emailProfessorIsValid &&
		$isAdminIsValid &&
		$isTitularIsValid &&
		$passwordProfessorIsValid &&
		$idsCourseIsValid )
	{
		redirectionAdminIfCrudProfessorDataIsOrNotCorrect($titleProfessor, $firstNameProfessor, $lastNameProfessor, $emailProfessor, $passwordProfessor, $isAdmin, $isTitular, $crudMethod);
	}
	else
	{
		setProfessorVariablesOnFalse($crudMethod);
		header('Location: ../../crudProfessor-admin');
	}
}
function createProfessor()
{
	require '../../classes/database.php';

	$crudMethod = "add";

	$titleProfessor = trim(ucfirst($_POST['titleProfessorAdd']));
	$firstNameProfessor = trim(ucfirst($_POST['firstNameProfessorAdd']));
	$lastNameProfessor = trim(ucfirst($_POST['lastNameProfessorAdd']));
	$emailProfessor = trim($_POST['emailProfessorAdd']);
	$passwordProfessor = trim($_POST['passwordProfessorAdd']);
	$isAdmin = $_POST['isAdmin'];
	$isTitular = $_POST['isTitular'];

	$titleProfessorIsValid = true;
	$firstNameProfessorIsValid = true;
	$lastNameProfessorIsValid = true;
	$emailProfessorIsValid = true;
	$passwordProfessorIsValid = true;
	$isAdminIsValid = true;
	$isTitularIsValid = true;

	//my sql injection
	$titleProfessor = stripcslashes($titleProfessor);
	$titleProfessor = mysqli_real_escape_string($con, $titleProfessor);
	$firstNameProfessor = stripcslashes($firstNameProfessor);
	$firstNameProfessor = mysqli_real_escape_string($con, $firstNameProfessor);
	$lastNameProfessor = stripcslashes($lastNameProfessor);
	$lastNameProfessor = mysqli_real_escape_string($con, $lastNameProfessor);
	$emailProfessor = stripcslashes($emailProfessor);
	$emailProfessor = mysqli_real_escape_string($con, $emailProfessor);
	$passwordProfessor = stripcslashes($passwordProfessor);
	$passwordProfessor = mysqli_real_escape_string($con, $passwordProfessor);

	$titleProfessorIsValid = emptyInput($titleProfessor);
	$firstNameProfessorIsValid = emptyInput($firstNameProfessor);
	$lastNameProfessorIsValid = emptyInput($lastNameProfessor);
	$emailProfessorIsValid = emptyInput($emailProfessor);
	$passwordProfessorIsValid = emptyInput($passwordProfessor);
	$isAdminIsValid = emptyInput($isAdmin);
	$isTitularIsValid = emptyInput($isTitular);
	$idsCourseIsValid = emptyInput($_POST['idsCourse']);

	redirectionAdminIfProfessorDataInputIsOrNotEmpty($titleProfessor, $titleProfessorIsValid, $firstNameProfessor, $firstNameProfessorIsValid, $lastNameProfessor, $lastNameProfessorIsValid, $emailProfessor, $emailProfessorIsValid, $passwordProfessor, $passwordProfessorIsValid, $isAdmin, $isAdminIsValid, $isTitular, $isTitularIsValid, $idsCourseIsValid, $crudMethod);
}
function deleteProfessor()
{
	require '../../classes/database.php';

	$result = $con->prepare("delete from professors where id=?");
	$result->bind_param('i', intval($_POST['deleteProfessor']));
	$result->execute();

	header('Location: ../../crudProfessor-admin');	
}
function deleteAllProfessors()
{
	require '../../classes/database.php';

	$facultyId = getFacultyIdForProfessor();
	$isAdmin = 0;

	$result = $con->prepare("DELETE from professors  where id in (Select fp.professor_id from faculty_professors fp where fp.faculty_id = ?) and is_admin = ?");
	$result->bind_param('ii', $facultyId, intval($isAdmin));
	$result->execute();

	header('Location: ../../crudProfessor-admin');	
}

function updateProfessor()
{
	require '../../classes/database.php';

	$crudMethod = "edit";

	$titleProfessor = trim(ucfirst($_POST['titleProfessorEdit']));
	$firstNameProfessor = trim(ucfirst($_POST['firstNameProfessorEdit']));
	$lastNameProfessor = trim(ucfirst($_POST['lastNameProfessorEdit']));
	$emailProfessor = trim($_POST['emailProfessorEdit']);
	$passwordProfessor = "pass";
	$isAdmin = $_POST['isAdmin'];
	$isTitular = $_POST['isTitular'];

	$titleProfessorIsValid = true;
	$firstNameProfessorIsValid = true;
	$lastNameProfessorIsValid = true;
	$emailProfessorIsValid = true;
	$passwordProfessorIsValid = true;
	$isAdminIsValid = true;
	$isTitularIsValid = true;

	//my sql injection
	$titleProfessor = stripcslashes($titleProfessor);
	$titleProfessor = mysqli_real_escape_string($con, $titleProfessor);
	$firstNameProfessor = stripcslashes($firstNameProfessor);
	$firstNameProfessor = mysqli_real_escape_string($con, $firstNameProfessor);
	$lastNameProfessor = stripcslashes($lastNameProfessor);
	$lastNameProfessor = mysqli_real_escape_string($con, $lastNameProfessor);
	$emailProfessor = stripcslashes($emailProfessor);
	$emailProfessor = mysqli_real_escape_string($con, $emailProfessor);

	$titleProfessorIsValid = emptyInput($titleProfessor);
	$firstNameProfessorIsValid = emptyInput($firstNameProfessor);
	$lastNameProfessorIsValid = emptyInput($lastNameProfessor);
	$emailProfessorIsValid = emptyInput($emailProfessor);
	$isAdminIsValid = emptyInput($isAdmin);
	$isTitularIsValid = emptyInput($isTitular);
	$idsCourseIsValid = emptyInput($_POST['idsCourse']);

	redirectionAdminIfProfessorDataInputIsOrNotEmpty($titleProfessor, $titleProfessorIsValid, $firstNameProfessor, $firstNameProfessorIsValid, $lastNameProfessor, $lastNameProfessorIsValid, $emailProfessor, $emailProfessorIsValid, $passwordProfessor, $passwordProfessorIsValid, $isAdmin, $isAdminIsValid, $isTitular, $isTitularIsValid, $idsCourseIsValid, $crudMethod);
}
function importProfessor()
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
				$firstNameProfessor = $context[0];
				$lastNameProfessor = $context[1];
				$titleProfessor = $context[2];
				$emailProfessor = $context[3];
				$passwordProfessor = $context[4];
				$isAdmin = $context[5];
				
				addProfessorForExpor($titleProfessor, $firstNameProfessor, $lastNameProfessor, $emailProfessor, $passwordProfessor, $isAdmin);
			}
			fclose($handle);
			header('Location: ../../crudProfessor-admin');
		}
	}
}
function importProfessorCourses()
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
				$idProfessor = $context[0];
				$idCourse = $context[1];
				$isTitular = $context[2];
				
				$result = $con->prepare("insert into professor_courses (id, professor_id, course_id, is_titular) VALUES (NULL, ?, ?, ?);");
				$result->bind_param('iii', $idProfessor, $idCourse, $isTitular);
				$result->execute();
				
			}
			fclose($handle);
			header('Location: ../../crudProfessor-admin');
		}
	}
}

function exportProfessor()
{
	require '../../classes/database.php';
	header('Content-Type: txt/csv; charset=utf-8');
	header('Content-Disposition: attachment; fileName=profesori.csv');
	$output = fopen("php://output", "w");
	fputcsv($output, array('ID', 'Grad', 'Prenume', 'Nume', 'Email', 'Este admin (1-Da, 0-Nu)'));

	$facultyId = getFacultyIdForProfessor();

	$result = $con->prepare("select p.id, p.title, p.first_name, p.last_name, p.email, p.is_admin FROM professors p join faculty_professors fp on fp.professor_id=p.id where fp.faculty_id = ? order by p.title, p.first_name, p.last_name");
	$result->bind_param('i', $facultyId);
	$result->execute();

	$stmt = $result->get_result();

	while($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		fputcsv($output, $row);
	}
	fclose($output);
}
if(isset($_POST['createProfessor']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	createProfessor();
	die();
}
if(isset($_POST['deleteProfessor']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	deleteProfessor();
	die();
}
if(isset($_POST['deleteAllProfessors']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	deleteAllProfessors();
	die();
}
if(isset($_POST['updateProfessor']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	updateProfessor();
	die();
}
if(isset($_POST['importProfessor']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	importProfessor();
	die();
}
if(isset($_POST['importProfessorCourses']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	importProfessorCourses();
	die();
}
if(isset($_POST['exportProfessor']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	exportProfessor();
	die();
}
class CrudProfessor extends Controller {
}
?>