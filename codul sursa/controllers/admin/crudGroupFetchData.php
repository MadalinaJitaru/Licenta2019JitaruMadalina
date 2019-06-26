<?php
if(isset($_POST['editGroupId']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}
	$_SESSION['groupIdForEdit'] = $_POST['editGroupId'];
	
	echo updateGroupFetchData();
}
function requireDatabaseForGroup()
{
	if(isset($_POST['editGroupId']))
	{
		require '../../classes/database.php';			
	}
	else
	{
		require './classes/database.php';
	}
	return $con;
}
function getFacultyIdForEditGroup()
{
	$con = requireDatabaseForGroup();

	$result = $con->prepare("select * from faculty_professors where professor_id = ?");
	$result->bind_param('i', $_SESSION['idProfessor']);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row['faculty_id'];
}
function fetchDataGroupForUpdate($groupId)
{
	$facultyId = getFacultyIdForEditGroup();

	$con = requireDatabaseForGroup();

	$result = $con->prepare("select * from groups where id=?");
	$result->bind_param('i', $groupId);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);
	return $row;
}
function fetchYearOfStudyForGroup($output, $yearOfStudyId)
{
	$facultyId = getFacultyIdForEditGroup();
	
	$con = requireDatabaseForGroup();	
	$result = $con->prepare("select * from study_years where faculty_id = ?");
	$result->bind_param('i', $facultyId);
	$result->execute();

	$stmt = $result->get_result();
	while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		$output .= '<label class="option">'.$row['study_year'];
		if($yearOfStudyId == $row['id'])
		{
			$output .= '<input checked type="radio" name="idStudyYear" value="'.$row['id'].'">';
		}
		else
		{
			$output .= '<input type="radio" name="idStudyYear" value="'.$row['id'].'">';
		}
		$output .= '
		<br>
		<span class="pseudoRadioButton"></span>
		</label>';
	}

	return $output;
}
function fetchSpecializationForGroup($output, $specializationId)
{
	$facultyId = getFacultyIdForEditGroup();
	
	$con = requireDatabaseForGroup();	
	$result = $con->prepare("select * from specialization where faculty_id = ?");
	$result->bind_param('i', $facultyId);
	$result->execute();

	$stmt = $result->get_result();
	while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		$output .= '<label class="option">'.$row['specialization_name'];
		if($specializationId == $row['id'])
		{
			$output .= '<input checked type="radio" name="idSpecialization" value="'.$row['id'].'">';
		}
		else
		{
			$output .= '<input type="radio" name="idSpecialization" value="'.$row['id'].'">';
		}
		$output .= '
		<br>
		<span class="pseudoRadioButton"></span>
		</label>';
	}

	return $output;
}
function fetchProfessorsForGroup($output, $groupId)
{
	$facultyId = getFacultyIdForEditGroup();
	
	$con = requireDatabaseForGroup();	
	$result = $con->prepare("select * from professors p join faculty_professors fp on fp.professor_id=p.id where faculty_id=?");
	$result->bind_param('i', $facultyId);
	$result->execute();

	$stmt = $result->get_result();
	while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		$output .= '<label class="option">'.$row["title"].' '.strtoupper($row["first_name"]).' '.$row["last_name"].' - '.$row["email"];

		$resultProfessor = $con->prepare("select count(p.id) from professors p join professor_groups pg on pg.professor_id=p.id where pg.group_id = ? and p.id = ?");
		$resultProfessor->bind_param('ii', $groupId, $row['professor_id']);
		$resultProfessor->execute();
		$stmtProfessor = $resultProfessor->get_result();
		$rowProfessor = mysqli_fetch_array($stmtProfessor, MYSQLI_ASSOC);

		if($rowProfessor['count(p.id)'] != 0)
		{
			$output .= '<input checked type="checkbox" name="idsProfessor[]" value="'.$row['professor_id'].'">';
		}
		else
		{
			$output .= '<input type="checkbox" name="idsProfessor[]" value="'.$row['professor_id'].'">';
		}
		$output .= '
		<br>
		<span class="pseudoCheckboxButton"></span>
		</label>';
	}

	return $output;
}
function getStmtProfessorsForGroup($groupId)
{
	require './classes/database.php';
	$stmtProfessor = '';
	$result = $con->prepare('select p.title, p.first_name, p.last_name, p.email from professors p join professor_groups pg on pg.professor_id=p.id where pg.group_id=? order by p.title, p.first_name, p.last_name, p.email');
	$result->bind_param('i', $groupId);
	$result->execute();

	$stmt = $result->get_result();
	while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		$stmtProfessor .= $row["title"].' '.$row["first_name"].' '.strtoupper($row["last_name"]).' - '.$row["email"].'</br>';
	}
	return $stmtProfessor;
}
function readGroupFetchData()
{
	$output = '
	<span class="container" style="cursor:default">
	<div class="gridContainerGroup">
	<div class="gridItem">Nr.</div>
	<div class="gridItem">Specializare</div>
	<div class="gridItem">An de studiu</div>
	<div class="gridItem">Grupa</div>
	<div class="gridItem">Profesori</div>
	</div>
	</span>';
	$indexGroup = 0;
	$facultyId = getFacultyIdForEditGroup();

	require './classes/database.php';

	$result = $con->prepare('select s.specialization_name, sy.study_year, g.group_name, g.id from groups g join specialization s on s.id=g.specialization_id join study_years sy on sy.id=g.study_year_id where g.faculty_id=? order by s.specialization_name, sy.study_year, g.group_name');
	$result->bind_param('i', $facultyId);
	$result->execute();

	$stmt = $result->get_result();
	while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		$indexGroup += 1;
		$output .= '
		<label onclick="hideElement(\'optionError\')" for="line'.$indexGroup.'" class="container">';
		if(isset($_SESSION['groupIdForEdit']) && $row['id'] == $_SESSION['groupIdForEdit'])
		{
			$output .= '<input checked id="line'.$indexGroup.'" type="radio" name="group" value="'.$row['id'].'">';
		}
		else
		{	
			$output .= '<input id="line'.$indexGroup.'" type="radio" name="group" value="'.$row['id'].'">';
		}
		$output .= '
		<div class="gridContainerGroup">
		<div class="gridItem">'.$indexGroup.'. </div>
		<div class="gridItem">'.$row["specialization_name"].'</div>
		<div class="gridItem">'.$row["study_year"].'</div>
		<div class="gridItem">'.ucfirst($row["group_name"]).'</div>';
		$groupProfessors = getStmtProfessorsForGroup($row['id']);
		$output .= '		
		<div class="gridItem">'.$groupProfessors.'</div>
		</div>
		</label>';
	}
	return $output;
}
function createGroupFetchData()
{
	$output = '
	<h1>Adaugare grupa</h1>
	<form action="./controllers/admin/crudGroup.php" method="post">
	<div class="crudPopUpContainer">
	<div class="crudInputContainer">
	<label for="groupAdd">Grupa</label>
	<input type="text" name="groupAdd" id="groupAdd" placeholder="Introduceti grupa">
	<br>
	</div>
	<div class="crudRadioButtonContainer">
	<label>Specializare</label>
	<div class="options">';
	$output = fetchSpecializationForGroup($output, 0);
	$output .= '
	</div>
	</div>
	<div class="crudRadioButtonContainer">
	<label>Anul de studiu</label>
	<div class="options">';
	$output = fetchYearOfStudyForGroup($output, 0);
	$output .= '
	</div>
	</div>
	<div class="crudRadioButtonContainerList">
	<label>Profesori</label>
	<div class="options">';
	$output = fetchProfessorsForGroup($output, 0);
	$output .= '
	</div>
	</div>
	</div>
	<div class="crudEntityButton">
	<button type="submit" name="createGroup">Adauga</button>
	</div>
	</form>';
	return $output;
}
function updateGroupFetchData()
{
	$row = fetchDataGroupForUpdate($_SESSION['groupIdForEdit']);
	$output = '
	<h1>Modificare grupa</h1>
	<form action="./controllers/admin/crudGroup.php" method="post">
	<div class="crudPopUpContainer">
	<div class="crudInputContainer">
	<label for="groupEdit">Grupa</label>
	<input type="text" name="groupEdit" id="groupEdit" placeholder="Introduceti grupa" value="'.$row["group_name"].'">
	<br>
	</div>
	<div class="crudRadioButtonContainer">
	<label>Specializare</label>
	<div class="options">';
	$output = fetchSpecializationForGroup($output, $row["specialization_id"]);
	$output .= '
	</div>
	</div>
	<div class="crudRadioButtonContainer">
	<label>Anul de studiu</label>
	<div class="options">';
	$output = fetchYearOfStudyForGroup($output, $row["study_year_id"]);
	$output .= '
	</div>
	</div>
	<div class="crudRadioButtonContainerList">
	<label>Profesori</label>
	<div class="options">';
	$output = fetchProfessorsForGroup($output, $row["id"]);
	$output .= '
	</div>
	</div>
	</div>
	<div class="crudEntityButton">
	<button type="submit" name="updateGroup">Modifica</button>
	</div>
	</form>
	';
	return $output;
}
?>