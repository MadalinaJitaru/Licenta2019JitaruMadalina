<?php
function emptyInput($input)
{
	if(empty($input))
	{
		return false;
	}
	return true;
}
function getFacultyIdForStudent()
{
	require '../../classes/database.php';

	$result = $con->prepare("select * from faculty_professors where professor_id = ?");
	$result->bind_param('i', $_SESSION['idProfessor']);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row['faculty_id'];
}
function setStudentVariablesOnFalse($crudMethod)
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
function addStudent($registerNumberAdd, $identificationNumberAdd, $idGroupAdd)
{
	require '../../classes/database.php';
	$resultVerify = $con->prepare("select count(id) from students where register_number = ? or identification_number = ?");
	$resultVerify->bind_param('ss', $registerNumberAdd, $identificationNumberAdd);
	$resultVerify->execute();

	$stmtVerify = $resultVerify->get_result();
	$rowVerify = mysqli_fetch_array($stmtVerify, MYSQLI_ASSOC);

	if($rowVerify['count(id)'] == 0)
	{
		$result = $con->prepare("insert into students (id, group_id, register_number, identification_number) VALUES (NULL, ?, ?, ?);");
		$result->bind_param('iss', intval($idGroupAdd), $registerNumberAdd, $identificationNumberAdd);
		$result->execute();
	}
	else
	{
		$_SESSION['sameDataEntity'] = true;
	}
	
}
function editStudent($registerNumberEdit, $identificationNumberEdit, $idGroupEdit)
{
	require '../../classes/database.php';

	$result = $con->prepare("UPDATE students SET register_number =?, identification_number = ?, group_id = ? WHERE students.id = ?;");
	$result->bind_param('ssii', $registerNumberEdit, $identificationNumberEdit, $idGroupEdit, $_SESSION['studentIdForEdit']);
	$result->execute();
}
function redirectionAdminIfCrudStudentDataIsOrNotCorrect($registerNumber, $identificationNumber, $idSpecialization, $idYear, $idGroup, $crudMethod)
{
	require '../../classes/database.php';

	$result = $con->prepare("select count(id) from students where group_id = ? and register_number = ? and identification_number = ?");
	$result->bind_param('iii', $idGroup, $registerNumber, $identificationNumber);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	if($row['count(id)'] == 1)
	{
		if ($crudMethod == "add") 
		{
			addStudent($registerNumber, $identificationNumber, $idGroup);
		}
		elseif ($crudMethod == "edit") 
		{
			editStudent($registerNumber, $identificationNumber, $idGroup);
		}
		header('Location: ../../crudStudent-admin');			
	}
	else
	{
		setStudentVariablesOnFalse($crudMethod);
		header('Location: ../../crudStudent-admin');
	}
}
function redirectionAdminIfDataInputIsOrNotEmpty($registerNumber, $identificationNumber, $idSpecialization, $idYear, $idGroup, $registerNumberIsValid, $identificationNumberIsValid, $idSpecializationIsValid, $idYearIsValid, $idGroupIsValid, $crudMethod)
{
	if(	$registerNumberIsValid && 
		$identificationNumberIsValid &&
		$idSpecializationIsValid &&
		$idYearIsValid &&
		$idGroupIsValid)
	{
		redirectionAdminIfCrudStudentDataIsOrNotCorrect($registerNumber, $identificationNumber, $idSpecialization, $idYear, $idGroup, $crudMethod);
	}
	else
	{
		setStudentVariablesOnFalse($crudMethod);
		header('Location: ../../crudStudent-admin');
	}
}
function createStudent()
{
	require '../../classes/database.php';
	$crudMethod = "add";
	$registerNumberAdd = trim(strtoupper($_POST["numarMatricolAdd"]));
	$identificationNumberAdd = trim(strtoupper($_POST["CNPAdd"]));
	$idSpecializationAdd = trim($_POST['idSpecializationAdd']);
	$idYearAdd = trim($_POST['idYearAdd']);
	$idGroupAdd = trim($_POST['idGroupAdd']);

	$registerNumberAddIsValid = true;
	$identificationNumberAddIsValid = true;
	$idSpecializationAddIsValid = true;
	$idYearAddIsValid = true;
	$idGroupAddIsValid = true;

	//my sql injection
	$registerNumberAdd = stripcslashes($registerNumberAdd);
	$registerNumberAdd = mysqli_real_escape_string($con, $registerNumberAdd);
	$identificationNumberAdd = stripcslashes($identificationNumberAdd);
	$identificationNumberAdd = mysqli_real_escape_string($con, $identificationNumberAdd);

	$registerNumberAddIsValid = emptyInput($registerNumberAdd);
	$identificationNumberAddIsValid = emptyInput($identificationNumberAdd);
	$idSpecializationAddIsValid = emptyInput($idSpecializationAdd);
	$idYearAddIsValid = emptyInput($idYearAdd);
	$idGroupAddIsValid = emptyInput($idGroupAdd);

	redirectionAdminIfDataInputIsOrNotEmpty($registerNumberAdd, $identificationNumberAdd, $idSpecializationAdd, $idYearAdd, $idGroupAdd, $registerNumberAddIsValid, $identificationNumberAddIsValid, $idSpecializationAddIsValid, $idYearAddIsValid, $idGroupAddIsValid, $crudMethod);
}
function deleteStudent()
{
	require '../../classes/database.php';

	$result = $con->prepare("delete from students where id=?");
	$result->bind_param('i', intval($_POST['deleteStudent']));
	$result->execute();

	header('Location: ../../crudStudent-admin');	
}
function deleteAllStudents()
{
	require '../../classes/database.php';

	$facultyId = getFacultyIdForStudent();

	$result = $con->prepare("delete from students where group_id in (SELECT g.id from groups g where g.faculty_id = ?)");
	$result->bind_param('i', $facultyId);
	$result->execute();
	
	header('Location: ../../crudStudent-admin');	
}
function updateStudent()
{
	require '../../classes/database.php';

	$crudMethod = "edit";
	$registerNumberEdit = strtoupper($_POST["numarMatricolEdit"]);
	$identificationNumberEdit = strtoupper($_POST["CNPEdit"]);
	$idSpecializationEdit = $_POST['idSpecializationAdd'];
	$idYearEdit = $_POST['idYearAdd'];
	$idGroupEdit = $_POST['idGroupAdd'];

	$registerNumberEditIsValid = true;
	$identificationNumberEditIsValid = true;
	$idSpecializationEditIsValid = true;
	$idYearEditIsValid = true;
	$idGroupEditIsValid = true;

	//my sql injection
	$registerNumberEdit = stripcslashes($registerNumberEdit);
	$registerNumberEdit = mysqli_real_escape_string($con, $registerNumberEdit);
	$identificationNumberEdit = stripcslashes($identificationNumberEdit);
	$identificationNumberEdit = mysqli_real_escape_string($con, $identificationNumberEdit);

	$registerNumberEditIsValid = emptyInput($registerNumberEdit);
	$identificationNumberEditIsValid = emptyInput($identificationNumberEdit);
	$idSpecializationEditIsValid = emptyInput($idSpecializationEdit);
	$idYearEditIsValid = emptyInput($idYearEdit);
	$idGroupEditIsValid = emptyInput($idGroupEdit);

	redirectionAdminIfDataInputIsOrNotEmpty($registerNumberEdit, $identificationNumberEdit, $idSpecializationEdit, $idYearEdit, $idGroupEdit, $registerNumberEditIsValid, $identificationNumberEditIsValid, $idSpecializationEditIsValid, $idYearEditIsValid, $idGroupEditIsValid, $crudMethod);
}
function importStudent()
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
				$idGroup = $context[0];
				$registerNumber = $context[1];
				$identificationNumber = $context[2];
				addStudent($registerNumber, $identificationNumber, $idGroup);
			}
			fclose($handle);
			header('Location: ../../crudStudent-admin');
		}
	}
}
function exportStudent()
{
	require '../../classes/database.php';
	header('Content-Type: txt/csv; charset=utf-8');
	header('Content-Disposition: attachment; fileName=studenti.csv');
	$output = fopen("php://output", "w");
	fputcsv($output, array('ID', 'Numar_matricol', 'CNP', 'Grupa_id', 'Grupa', 'Specializare_id', 'Specializare', 'An_id', 'An'));

	$facultyId = getFacultyIdForStudent();

	$result = $con->prepare("SELECT s.id, s.register_number, s.identification_number, s.group_id, g.group_name, g.specialization_id, sp.specialization_name, g.study_year_id, sy.study_year from students s join groups g on g.id=s.group_id join specialization sp on sp.id=g.specialization_id join study_years sy on sy.id=g.study_year_id where g.faculty_id = ? order by sy.study_year, sp.specialization_name, g.group_name, s.register_number");
	$result->bind_param('i', $facultyId);
	$result->execute();

	$stmt = $result->get_result();

	while($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		fputcsv($output, $row);
	}
	fclose($output);
}
if(isset($_POST['createStudent']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	createStudent();
	die();
}
if(isset($_POST['deleteStudent']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	deleteStudent();
	die();
}
if(isset($_POST['deleteAllStudents']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	deleteAllStudents();
	die();
}
if(isset($_POST['updateStudent']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	updateStudent();
	die();
}
if(isset($_POST['importStudent']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	importStudent();
	die();
}
if(isset($_POST['exportStudent']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	exportStudent();
	die();
}
class CrudStudent extends Controller {
}
?>