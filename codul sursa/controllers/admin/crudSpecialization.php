<?php
function emptyInput($input)
{
	if(empty($input))
	{
		return false;
	}
	return true;
}
function getFacultyIdForSpecialization()
{
	require '../../classes/database.php';

	$result = $con->prepare("select * from faculty_professors where professor_id = ?");
	$result->bind_param('i', $_SESSION['idProfessor']);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row['faculty_id'];
} 
function setSpecializationVariablesOnFalse($crudMethod)
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
function addSpecialization($specializationNameAdd)
{
	require '../../classes/database.php';

	$facultyId = getFacultyIdForSpecialization();

	$result = $con->prepare("insert into specialization (id, faculty_id, specialization_name) VALUES (NULL, ?, ?);");
	$result->bind_param('is', intval($facultyId), $specializationNameAdd);
	$result->execute();
}
function editSpecialization($specializationNameAdd)
{
	require '../../classes/database.php';

	$result = $con->prepare("update specialization set specialization_name = ? where specialization.id = ?;");
	$result->bind_param('si', $specializationNameAdd, $_SESSION['specializationIdForEdit']);
	$result->execute();
}
function redirectionAdminIfCrudSpecializationDataIsOrNotCorrect($specializationName, $crudMethod)
{
	require '../../classes/database.php';

	$facultyId = getFacultyIdForSpecialization();
	
	$result = $con->prepare("select count(id) from specialization where specialization_name = ? and faculty_id = ?");
	$result->bind_param('si', $specializationName, $facultyId);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	if ($crudMethod == "edit") 
	{
		editSpecialization($specializationName);
		header('Location: ../../crudSpecialization-admin');
	}
	if ($crudMethod == "add") 
	{
		if($row['count(id)'] >= 1)
		{
			$_SESSION['sameDataEntity'] = true;
			header('Location: ../../crudSpecialization-admin');
		}
		else
		{
			addSpecialization($specializationName);
			header('Location: ../../crudSpecialization-admin');	
		}
	}
}
function redirectionAdminIfSpecializationDataInputIsOrNotEmpty($specializationName, $specializationNameIsValid, $crudMethod)
{
	if($specializationNameIsValid)
	{
		redirectionAdminIfCrudSpecializationDataIsOrNotCorrect($specializationName, $crudMethod);
	}
	else
	{
		setSpecializationVariablesOnFalse($crudMethod);
		header('Location: ../../crudSpecialization-admin');
	}
}
function createSpecialization()
{
	require '../../classes/database.php';
	$crudMethod = "add";

	$specializationNameAdd = trim(ucfirst($_POST['specializationAdd']));

	$specializationNameAddIsValid = true;

	//my sql injection
	$specializationNameAdd = stripcslashes($specializationNameAdd);
	$specializationNameAdd = mysqli_real_escape_string($con, $specializationNameAdd);

	$specializationNameAddIsValid = emptyInput($specializationNameAdd);

	redirectionAdminIfSpecializationDataInputIsOrNotEmpty($specializationNameAdd, $specializationNameAddIsValid, $crudMethod);
}
function deleteSpecialization()
{
	require '../../classes/database.php';

	$result = $con->prepare("delete from specialization where id=?");
	$result->bind_param('i', intval($_POST['deleteSpecialization']));
	$result->execute();

	header('Location: ../../crudSpecialization-admin');	
}
function deleteAllSpecialization()
{
	require '../../classes/database.php';

	$facultyId = getFacultyIdForSpecialization();

	$result = $con->prepare("delete from specialization where faculty_id = ? ");
	$result->bind_param('i', $facultyId);
	$result->execute();

	header('Location: ../../crudSpecialization-admin');	
}
function updateSpecialization()
{
	require '../../classes/database.php';

	$crudMethod = "edit";

	$specializationNameAdd = trim(ucfirst($_POST['specializationEdit']));

	$specializationNameAddIsValid = true;

	//my sql injection
	$specializationNameAdd = stripcslashes($specializationNameAdd);
	$specializationNameAdd = mysqli_real_escape_string($con, $specializationNameAdd);

	$specializationNameAddIsValid = emptyInput($specializationNameAdd);

	redirectionAdminIfSpecializationDataInputIsOrNotEmpty($specializationNameAdd, $specializationNameAddIsValid, $crudMethod);
}
function importSpecialization()
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
				$specializationText = mysqli_real_escape_string($con, $data[0]);
				addSpecialization($specializationText);
			}
			fclose($handle);
			header('Location: ../../crudSpecialization-admin');
		}
	}
}
function exportSpecialization()
{
	require '../../classes/database.php';
	header('Content-Type: txt/csv; charset=utf-8');
	header('Content-Disposition: attachment; fileName=specializari.csv');
	$output = fopen("php://output", "w");
	fputcsv($output, array('ID', 'Specializare'));

	$facultyId = getFacultyIdForSpecialization();

	$result = $con->prepare("select id, specialization_name from specialization where faculty_id = ? order by specialization_name");
	$result->bind_param('i', $facultyId);
	$result->execute();

	$stmt = $result->get_result();

	while($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		fputcsv($output, $row);
	}
	fclose($output);
}
if(isset($_POST['createSpecialization']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	createSpecialization();
	die();
}
if(isset($_POST['deleteSpecialization']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	deleteSpecialization();
	die();
}
if(isset($_POST['deleteAllSpecialization']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	deleteAllSpecialization();
	die();
}
if(isset($_POST['updateSpecialization']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	updateSpecialization();
	die();
}
if(isset($_POST['importSpecialization']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	importSpecialization();
	die();
}
if(isset($_POST['exportSpecialization']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	exportSpecialization();
	die();
}
class CrudSpecialization extends Controller {
}
?>