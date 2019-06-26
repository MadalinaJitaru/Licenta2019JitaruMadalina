<?php
function emptyInput($input)
{
	if(empty($input))
	{
		return false;
	}
	return true;
}
function getFacultyIdForStudyYear()
{
	require '../../classes/database.php';

	$result = $con->prepare("select * from faculty_professors where professor_id = ?");
	$result->bind_param('i', $_SESSION['idProfessor']);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row['faculty_id'];
} 
function setStudyYearVariablesOnFalse($crudMethod)
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
function addStudyYear($studyYearAdd)
{
	require '../../classes/database.php';

	$facultyId = getFacultyIdForStudyYear();

	$result = $con->prepare("insert into study_years (id, faculty_id, study_year) VALUES (NULL, ?, ?);");
	$result->bind_param('is', intval($facultyId), $studyYearAdd);
	$result->execute();
}
function editStudyYear($studyYearEdit)
{
	require '../../classes/database.php';

	$result = $con->prepare("update study_years set study_year = ? where study_years.id = ?;");
	$result->bind_param('si', $studyYearEdit, $_SESSION['studyYearIdForEdit']);
	$result->execute();
}
function redirectionAdminIfCrudStudyYearDataIsOrNotCorrect($studyYear, $crudMethod)
{
	require '../../classes/database.php';

	$facultyId = getFacultyIdForStudyYear();
	
	$result = $con->prepare("select count(id) from study_years where study_year = ? and faculty_id = ?");
	$result->bind_param('si', $studyYear, $facultyId);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	if ($crudMethod == "edit") 
	{
		editStudyYear($studyYear);
		header('Location: ../../crudStudyYear-admin');
	}
	if ($crudMethod == "add") 
	{
		if($row['count(id)'] >= 1)
		{
			$_SESSION['sameDataEntity'] = true;
			header('Location: ../../crudStudyYear-admin');
		}
		else
		{
			addStudyYear($studyYear);
			header('Location: ../../crudStudyYear-admin');			
		}
	}
}
function redirectionAdminIfStudyYearDataInputIsOrNotEmpty($studyYear, $studyYearIsValid, $crudMethod)
{
	if($studyYearIsValid)
	{
		redirectionAdminIfCrudStudyYearDataIsOrNotCorrect($studyYear, $crudMethod);
	}
	else
	{
		setStudyYearVariablesOnFalse($crudMethod);
		header('Location: ../../crudStudyYear-admin');
	}
}
function createStudyYear()
{
	require '../../classes/database.php';
	$crudMethod = "add";
	$studyYearAdd = trim(ucfirst($_POST['studyYearAdd']));

	$studyYearAddIsValid = true;

	//my sql injection
	$studyYearAdd = stripcslashes($studyYearAdd);
	$studyYearAdd = mysqli_real_escape_string($con, $studyYearAdd);

	$studyYearAddIsValid = emptyInput($studyYearAdd);

	redirectionAdminIfStudyYearDataInputIsOrNotEmpty($studyYearAdd, $studyYearAddIsValid, $crudMethod);
}
function deleteStudyYear()
{
	require '../../classes/database.php';

	$result = $con->prepare("delete from study_years where id=?");
	$result->bind_param('i', intval($_POST['deleteStudyYear']));
	$result->execute();

	header('Location: ../../crudStudyYear-admin');	
}
function deleteAllStudyYears()
{
	require '../../classes/database.php';

	$facultyId = getFacultyIdForStudyYear();
	
	$result = $con->prepare("delete from study_years where faculty_id = ?");
	$result->bind_param('i', $facultyId);
	$result->execute();

	header('Location: ../../crudStudyYear-admin');	
}
function updateStudyYear()
{
	require '../../classes/database.php';

	$crudMethod = "edit";

	$studyYearEdit = trim(ucfirst($_POST['studyYearEdit']));

	$studyYearEditIsValid = true;

	//my sql injection
	$studyYearEdit = stripcslashes($studyYearEdit);
	$studyYearEdit = mysqli_real_escape_string($con, $studyYearEdit);

	$studyYearEditIsValid = emptyInput($studyYearEdit);

	redirectionAdminIfStudyYearDataInputIsOrNotEmpty($studyYearEdit, $studyYearEditIsValid, $crudMethod);
}
function importStudyYear()
{
	require '../../classes/database.php';

	if($_FILES['file']['name'])
	{
		$fileName = explode(".", $_Files['file']['name']);
		if($fileName[1] == 'csv')
		{
			$handle = fopen($_FILES['file']['tmp_name'], "r");
			$data = fgetcsv($handle);
			while($data = fgetcsv($handle))
			{
				$studyYearText = mysqli_real_escape_string($con, $data[0]);
				addStudyYear($studyYearText);
			}
			fclose($handle);
			header('Location: ../../crudStudyYear-admin');
		}
	}
}
function exportStudyYear()
{
	require '../../classes/database.php';
	header('Content-Type: txt/csv; charset=utf-8');
	header('Content-Disposition: attachment; fileName=aniDeStudiu.csv');
	$output = fopen("php://output", "w");
	fputcsv($output, array('ID', 'An de studiu'));

	$facultyId = getFacultyIdForStudyYear();

	$result = $con->prepare("select id, study_year from study_years where faculty_id = ? order by study_year");
	$result->bind_param('i', $facultyId);
	$result->execute();

	$stmt = $result->get_result();

	while($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		fputcsv($output, $row);
	}
	fclose($output);

}
if(isset($_POST['createStudyYear']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	createStudyYear();
	die();
}
if(isset($_POST['deleteStudyYear']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	deleteStudyYear();
	die();
}
if(isset($_POST['deleteAllStudyYears']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	deleteAllStudyYears();
	die();
}
if(isset($_POST['updateStudyYear']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	updateStudyYear();
	die();
}
if(isset($_POST['importStudyYear']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	importStudyYear();
	die();
}
if(isset($_POST['exportStudyYear']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	exportStudyYear();
	die();
}
class CrudStudyYear extends Controller {
}
?>