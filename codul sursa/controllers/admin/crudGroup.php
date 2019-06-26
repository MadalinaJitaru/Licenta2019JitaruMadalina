<?php
function emptyInput($input)
{
	if(empty($input))
	{
		return false;
	}
	return true;
}
function getFacultyIdForGroup()
{
	require '../../classes/database.php';

	$result = $con->prepare("select * from faculty_professors where professor_id = ?");
	$result->bind_param('i', $_SESSION['idProfessor']);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row['faculty_id'];
} 
function setGroupVariablesOnFalse($crudMethod)
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
function getLastIdGroup()
{
	require '../../classes/database.php';

	$result = $con->prepare("select max(id) from groups");
	$result->execute();
	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row['max(id)'];
}
function addProfessorsForGroup($idGroup)
{
	require '../../classes/database.php';

	foreach ($_POST['idsProfessor'] as $idProfessor) 
	{
		$result = $con->prepare("insert into professor_groups (id, professor_id, group_id) VALUES (NULL, ?, ?);");
		$result->bind_param('ii', $idProfessor, $idGroup);
		$result->execute();
	}
}
function addGroup($groupNameAdd, $idStudyYearAdd, $idSpecializationAdd)
{
	$facultyId = getFacultyIdForGroup();
	require '../../classes/database.php';

	$result = $con->prepare("insert into groups (id, faculty_id, specialization_id, study_year_id, group_name) VALUES (NULL, ?, ?, ?, ?);");
	$result->bind_param('iiis', $facultyId, $idSpecializationAdd, $idStudyYearAdd, $groupNameAdd);
	$result->execute();

	$lastIdGroup = getLastIdGroup();
	addProfessorsForGroup($lastIdGroup);
}
function getDataProfessorGroups($idProfessor, $idGroup)
{
	require '../../classes/database.php';
	$resultVerify = $con->prepare("select count(id), id from professor_groups where professor_id = ? and group_id = ?");
	$resultVerify->bind_param('ii', $idProfessor, $idGroup);
	$resultVerify->execute();
	$stmtVerify = $resultVerify->get_result();
	$rowVerify = mysqli_fetch_array($stmtVerify, MYSQLI_ASSOC);

	return $rowVerify;
}
function editProfessorsForGroup()
{
	$facultyId = getFacultyIdForGroup();

	require '../../classes/database.php';

	$resultProfessor = $con->prepare("select p.id from professors p join faculty_professors fp on fp.professor_id=p.id where faculty_id=?");
	$resultProfessor->bind_param('i', $facultyId);
	$resultProfessor->execute();

	$stmtProfessor = $resultProfessor->get_result();

	while ($rowProfessor = mysqli_fetch_array($stmtProfessor, MYSQLI_ASSOC))
	{
		if(in_array($rowProfessor['id'], $_POST['idsProfessor']))
		{			
			$rowVerify = getDataProfessorGroups($rowProfessor['id'], $_SESSION['groupIdForEdit']);
			if($rowVerify['count(id)'] == 0)
			{
				$result = $con->prepare("insert into professor_groups (id, professor_id, group_id) VALUES (NULL, ?, ?);");
				$result->bind_param('ii', $rowProfessor['id'], $_SESSION['groupIdForEdit']);
				$result->execute();	
			}
		}
		else
		{
			$rowVerify = getDataProfessorGroups($rowProfessor['id'], $_SESSION['groupIdForEdit']);
			if($rowVerify['count(id)'] == 1)
			{
				$result = $con->prepare("delete from professor_groups where id=?");
				$result->bind_param('i', $rowVerify['id']);
				$result->execute();
			}	
		}
	}
}
function editGroup($groupNameEdit, $idStudyYearEdit, $idSpecializationEdit)
{
	require '../../classes/database.php';

	$result = $con->prepare("update groups set specialization_id = ?, study_year_id = ?, group_name = ? where groups.id = ?;");
	$result->bind_param('iisi', $idSpecializationEdit, $idStudyYearEdit, $groupNameEdit, $_SESSION['groupIdForEdit']);
	$result->execute();

	editProfessorsForGroup();
}
function redirectionAdminIfCrudGroupDataIsOrNotCorrect($groupName, $idStudyYear, $idSpecialization, $crudMethod)
{
	require '../../classes/database.php';

	$facultyId = getFacultyIdForGroup();

	$result = $con->prepare("select count(id) from groups where group_name = ? and specialization_id = ? and study_year_id = ? and faculty_id = ?");
	$result->bind_param('siii', $groupName, $idSpecialization, $idStudyYear, $facultyId);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	if ($crudMethod == "edit") 
	{
		editGroup($groupName, $idStudyYear, $idSpecialization);
		header('Location: ../../crudGroup-admin');
	}
	if ($crudMethod == "add") 
	{
		if($row['count(id)'] >= 1)
		{
			$_SESSION['sameDataEntity'] = true;
			header('Location: ../../crudGroup-admin');
		}
		else
		{
			addGroup($groupName, $idStudyYear, $idSpecialization);
			header('Location: ../../crudGroup-admin');	
		}
	}
}
function redirectionAdminIfGroupDataInputIsOrNotEmpty($groupName, $groupNameIsValid, $idStudyYear, $idStudyYearIsValid, $idSpecialization, $idSpecializationIsValid, $idsProfessorIsValid, $crudMethod)
{
	if( $groupNameIsValid &&
		$idStudyYearIsValid &&
		$idSpecializationIsValid &&
		$idsProfessorIsValid )
	{
		redirectionAdminIfCrudGroupDataIsOrNotCorrect($groupName, $idStudyYear, $idSpecialization, $crudMethod);
	}
	else
	{
		setGroupVariablesOnFalse($crudMethod);
		header('Location: ../../crudGroup-admin');
	}
}
function createGroup()
{
	require '../../classes/database.php';

	$crudMethod = "add";

	$groupNameAdd = trim(ucfirst($_POST['groupAdd']));
	$idStudyYearAdd = $_POST['idStudyYear'];
	$idSpecializationAdd = $_POST['idSpecialization'];

	$groupNameAddIsValid = true;
	$idStudyYearAddIsValid = true;
	$idSpecializationAddIsValid = true;
	$idsProfessorAddIsValid = true;

	//my sql injection
	$groupNameAdd = stripcslashes($groupNameAdd);
	$groupNameAdd = mysqli_real_escape_string($con, $groupNameAdd);

	$groupNameAddIsValid = emptyInput($groupNameAdd);
	$idStudyYearAddIsValid = emptyInput($idStudyYearAdd);
	$idSpecializationAddIsValid = emptyInput($idSpecializationAdd);
	$idsProfessorAddIsValid = emptyInput($_POST['idsProfessor']);

	redirectionAdminIfGroupDataInputIsOrNotEmpty($groupNameAdd, $groupNameAddIsValid, $idStudyYearAdd, $idStudyYearAddIsValid, $idSpecializationAdd, $idSpecializationAddIsValid, $idsProfessorAddIsValid, $crudMethod);
}
function deleteGroup()
{
	require '../../classes/database.php';

	$result = $con->prepare("delete from groups where id=?");
	$result->bind_param('i', intval($_POST['deleteGroup']));
	$result->execute();

	header('Location: ../../crudGroup-admin');	
}
function deleteAllGroups()
{
	require '../../classes/database.php';

	$facultyId = getFacultyIdForGroup();

	$result = $con->prepare("delete from groups where faculty_id = ?");
	$result->bind_param('i', $facultyId);
	$result->execute();
	
	header('Location: ../../crudGroup-admin');	
}
function updateGroup()
{
	require '../../classes/database.php';

	$crudMethod = "edit";

	$groupNameEdit = trim(ucfirst($_POST['groupEdit']));
	$idStudyYearEdit = $_POST['idStudyYear'];
	$idSpecializationEdit = $_POST['idSpecialization'];

	$groupNameEditIsValid = true;
	$idStudyYearEditIsValid = true;
	$idSpecializationEditIsValid = true;
	$idsProfessorEditIsValid = true;

	//my sql injection
	$groupNameEdit = stripcslashes($groupNameEdit);
	$groupNameEdit = mysqli_real_escape_string($con, $groupNameEdit);

	$groupNameEditIsValid = emptyInput($groupNameEdit);
	$idStudyYearEditIsValid = emptyInput($idStudyYearEdit);
	$idSpecializationEditIsValid = emptyInput($idSpecializationEdit);
	$idsProfessorEditIsValid = emptyInput($_POST['idsProfessor']);

	redirectionAdminIfGroupDataInputIsOrNotEmpty($groupNameEdit, $groupNameEditIsValid, $idStudyYearEdit, $idStudyYearEditIsValid, $idSpecializationEdit, $idSpecializationEditIsValid, $idsProfessorEditIsValid, $crudMethod);
}
function importGroup()
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
				$idSpecialization = $context[0];
				$idStudyYear = $context[1];
				$groupName = $context[2];
				addGroup($groupName, $idStudyYear, $idSpecialization);
			}
			fclose($handle);
			header('Location: ../../crudGroup-admin');
		}
	}
}
function importProfessorGroups()
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
				$idGroup = $context[1];
				
				$result = $con->prepare("insert into professor_groups (id, professor_id, group_id) VALUES (NULL, ?, ?);");
				$result->bind_param('ii', $idProfessor, $idGroup);
				$result->execute();
				
			}
			fclose($handle);
			header('Location: ../../crudGroup-admin');
		}
	}
}
function exportGroup()
{
	require '../../classes/database.php';
	header('Content-Type: txt/csv; charset=utf-8');
	header('Content-Disposition: attachment; fileName=grupe.csv');
	$output = fopen("php://output", "w");
	fputcsv($output, array('ID', 'Grupa_nume', 'Specializare_id', 'Specializare_nume', 'An_id', 'An_nume'));

	$facultyId = getFacultyIdForGroup();

	$result = $con->prepare("select g.id , g.group_name, g.specialization_id, s.specialization_name, g.study_year_id, sy.study_year from groups g join specialization s on s.id=g.specialization_id join study_years sy on sy.id=g.specialization_id where g.faculty_id = ? order by sy.study_year, s.specialization_name, g.group_name");
	$result->bind_param('i', $facultyId);
	$result->execute();

	$stmt = $result->get_result();

	while($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		fputcsv($output, $row);
	}
	fclose($output);
}
if(isset($_POST['createGroup']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	createGroup();
	die();
}
if(isset($_POST['deleteGroup']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	deleteGroup();
	die();
}
if(isset($_POST['deleteAllGroups']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	deleteAllGroups();
	die();
}
if(isset($_POST['updateGroup']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	updateGroup();
	die();
}
if(isset($_POST['importGroup']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	importGroup();
	die();
}
if(isset($_POST['importProfessorGroups']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	importProfessorGroups();
	die();
}
if(isset($_POST['exportGroup']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	exportGroup();
	die();
}
class CrudGroup extends Controller {
}
?>