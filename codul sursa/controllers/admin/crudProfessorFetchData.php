<?php
if(isset($_POST['editProfessorId']) && isset($_POST['editTitular']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}
	$_SESSION['professorIdForEdit'] = $_POST['editProfessorId'];
	$_SESSION['titularForEdit'] = $_POST['editTitular'];
	echo updateProfessorFetchData();
}
function requireDatabaseForProfessor()
{
	if(isset($_POST['editProfessorId']))
	{
		require '../../classes/database.php';			
	}
	else
	{
		require './classes/database.php';
	}
	return $con;
}
function getFacultyIdForEditProfessor()
{
	$con = requireDatabaseForProfessor();

	$result = $con->prepare("select * from faculty_professors where professor_id = ?");
	$result->bind_param('i', $_SESSION['idProfessor']);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row['faculty_id'];
}
function fetchDataProfessorForUpdate($professorId)
{
	$facultyId = getFacultyIdForEditProfessor();

	$con = requireDatabaseForProfessor();

	$result = $con->prepare("select * from professors where id=?");
	$result->bind_param('i', $professorId);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);
	return $row;
}
function getStmtCoursesForProfessor($professorId, $isTitular)
{
	$facultyId = getFacultyIdForEditProfessor();

	require './classes/database.php';

	$stmtCourses = '';

	$result = $con->prepare('SELECT * from professor_courses pc join courses c on c.id=pc.course_id join study_years sy on sy.id=c.study_year_id where pc.professor_id = ? and pc.is_titular = ? and sy.faculty_id = ?');
	$result->bind_param('iii', $professorId, $isTitular, $facultyId);
	$result->execute();

	$stmt = $result->get_result();
	while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		$stmtCourses .= ucfirst($row["course_title"]).'</br>';
	}
	return $stmtCourses;
}
function fetchIsAdminForProfessor($output, $isAdmin)
{
	if($isAdmin == -1)
	{
		$output .= '<label class="option">Da';
		$output .= '<input type="radio" name="isAdmin" value="1">';
		$output .= '
		<br>
		<span class="pseudoRadioButton"></span>
		</label>';
		$output .= '<label class="option">Nu';
		$output .= '<input type="radio" name="isAdmin" value="0">';
		$output .= '
		<br>
		<span class="pseudoRadioButton"></span>
		</label>';
	}
	else
	{
		if($isAdmin == 1)
		{
			$output .= '<label class="option">Da';
			$output .= '<input checked type="radio" name="isAdmin" value="1">';
			$output .= '
			<br>
			<span class="pseudoRadioButton"></span>
			</label>';
			$output .= '<label class="option">Nu';
			$output .= '<input type="radio" name="isAdmin" value="0">';
			$output .= '
			<br>
			<span class="pseudoRadioButton"></span>
			</label>';
		}
		elseif ($isAdmin == 0) 
		{
			$output .= '<label class="option">Da';
			$output .= '<input type="radio" name="isAdmin" value="1">';
			$output .= '
			<br>
			<span class="pseudoRadioButton"></span>
			</label>';
			$output .= '<label class="option">Nu';
			$output .= '<input checked type="radio" name="isAdmin" value="0">';
			$output .= '
			<br>
			<span class="pseudoRadioButton"></span>
			</label>';
		}
	}
	
	return $output;
}
function fetchIsTitularForProfessor($output, $isTitular)
{
	if($isTitular == -1)
	{
		$output .= '<label class="option">Da';
		$output .= '<input type="radio" name="isTitular" value="1">';
		$output .= '
		<br>
		<span class="pseudoRadioButton"></span>
		</label>';
		$output .= '<label class="option">Nu';
		$output .= '<input type="radio" name="isTitular" value="0">';
		$output .= '
		<br>
		<span class="pseudoRadioButton"></span>
		</label>';
	}
	else
	{
		if($isTitular == 1)
		{
			$output .= '<label class="option">Da';
			$output .= '<input checked type="radio" name="isTitular" value="1">';
			$output .= '
			<br>
			<span class="pseudoRadioButton"></span>
			</label>';
			$output .= '<label class="option">Nu';
			$output .= '<input type="radio" name="isTitular" value="0">';
			$output .= '
			<br>
			<span class="pseudoRadioButton"></span>
			</label>';
		}
		elseif ($isTitular == 0) 
		{
			$output .= '<label class="option">Da';
			$output .= '<input type="radio" name="isTitular" value="1">';
			$output .= '
			<br>
			<span class="pseudoRadioButton"></span>
			</label>';
			$output .= '<label class="option">Nu';
			$output .= '<input checked type="radio" name="isTitular" value="0">';
			$output .= '
			<br>
			<span class="pseudoRadioButton"></span>
			</label>';
		}
	}

	return $output;
}
function fetchCoursesForProfessor($output, $professorId, $titular)
{
	$facultyId = getFacultyIdForEditProfessor();
	
	$con = requireDatabaseForProfessor();

	$result = $con->prepare('SELECT c.id, c.course_title from courses c join study_years sy on sy.id=c.study_year_id where sy.faculty_id = ?');
	$result->bind_param('i', $facultyId);
	$result->execute();

	$stmt = $result->get_result();
	while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		$output .= '<label class="option">'.$row["course_title"];

		$resultProfessor = $con->prepare("select count(p.id) from professors p join professor_courses pc on pc.professor_id=p.id where pc.professor_id = ? and pc.course_id = ? and pc.is_titular = ?");
		$resultProfessor->bind_param('iii', $professorId, $row['id'], $titular);
		$resultProfessor->execute();
		$stmtProfessor = $resultProfessor->get_result();
		$rowProfessor = mysqli_fetch_array($stmtProfessor, MYSQLI_ASSOC);
		
		if($rowProfessor['count(p.id)'] != 0)
		{
			$output .= '<input checked type="checkbox" name="idsCourse[]" value="'.$row['id'].'">';
		}
		else
		{
			$output .= '<input type="checkbox" name="idsCourse[]" value="'.$row['id'].'">';
		}
		$output .= '
		<br>
		<span class="pseudoCheckboxButton"></span>
		</label>';
	}

	return $output;
}
function readProfessorFetchData()
{
	$output = '
	<span class="container" style="cursor:default">
	<div class="gridContainerProfessor">
	<div class="gridItem">Nr.</div>	
	<div class="gridItem">Grad</div>
	<div class="gridItem">Prenume</div>
	<div class="gridItem">Nume</div>
	<div class="gridItem">Email</div>
	<div class="gridItem">Titular</div>
	<div class="gridItem">Cursuri</div>
	<div class="gridItem">Admin</div>
	</div>
	</span>';
	$indexProfessor = 0;
	$facultyId = getFacultyIdForEditProfessor();

	require './classes/database.php';

	$result = $con->prepare("SELECT distinct p.id, p.first_name, p.last_name, p.title, p.email, p.is_admin, pc.is_titular from professors p join professor_courses pc on pc.professor_id=p.id join faculty_professors fp on fp.professor_id=p.id where fp.faculty_id = ? order by p.id, pc.is_titular desc");
	$result->bind_param('i', $facultyId);
	$result->execute();

	$stmt = $result->get_result();
	while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		$indexProfessor += 1;
		$output .= '<label onclick="hideElement(\'optionError\')" for="line'.$indexProfessor.'" class="container">';
		if( isset($_SESSION['professorIdForEdit']) && 
			$row['id'] == $_SESSION['professorIdForEdit'] &&
			isset($_SESSION['titularForEdit']) &&
			$row['is_titular'] == $_SESSION['titularForEdit'])
		{
			$output .= '<input checked id="line'.$indexProfessor.'" type="radio" name="professor" value="'.$row["id"].'.'.$row["is_titular"].'">';
		}
		else
		{	
			$output .= '<input id="line'.$indexProfessor.'" type="radio" name="professor" value="'.$row['id'].'.'.$row["is_titular"].'">';
		}
		$output .= '
		<div class="gridContainerProfessor">
		<div class="gridItem">'.$indexProfessor.'. </div>
		<div class="gridItem">'.$row["title"].'</div>
		<div class="gridItem">'.$row["first_name"].'</div>
		<div class="gridItem">'.$row["last_name"].'</div>
		<div class="gridItem">'.$row["email"].'</div>';
		if($row['is_titular'] == 1)
		{
			$output .= '<div class="gridItem">Da</div>';
		}
		else
		{
			$output .= '<div class="gridItem">Nu</div>';	
		}
		$professorCourses = getStmtCoursesForProfessor($row['id'], $row['is_titular']);
		$output .= '
		<div class="gridItem">'.$professorCourses.'</div>';
		if($row['is_admin'] == 1)
		{
			$output .= '<div class="gridItem">Da</div>';
		}
		else
		{
			$output .= '<div class="gridItem">Nu</div>';
		}
		$output .= '</div>
		</label>';
		
	}
	return $output;
}
function createProfessorFetchData()
{
	$output = '
	<h1>Adaugare profesor</h1>
	<form action="./controllers/admin/crudProfessor.php" method="post">
	<div class="crudPopUpContainer">
	<div class="crudInputContainer">
	<label for="titleProfessorAdd">Grad</label>
	<input type="text" name="titleProfessorAdd" id="titleProfessorAdd" placeholder="Introduceti gradul">
	<br>
	</div>
	<div class="crudInputContainer">
	<label for="firstNameProfessorAdd">Prenume</label>
	<input type="text" name="firstNameProfessorAdd" id="firstNameProfessorAdd" placeholder="Introduceti prenumele">
	<br>
	</div>
	<div class="crudInputContainer">
	<label for="lastNameProfessorAdd">Nume</label>
	<input type="text" name="lastNameProfessorAdd" id="lastNameProfessorAdd" placeholder="Introduceti numele">
	<br>
	</div>
	<div class="crudInputContainer">
	<label for="emailProfessorAdd">Email</label>
	<input type="text" name="emailProfessorAdd" id="emailProfessorAdd" placeholder="Introduceti numele">
	<br>
	</div>
	<div class="crudInputContainer">
	<label for="passwordProfessorAdd">Parola</label>
	<input type="text" name="passwordProfessorAdd" id="passwordProfessorAdd" placeholder="Introduceti parola">
	<br>
	</div>
	<div class="crudRadioButtonContainer" id="crudRadioButtonContainerAddAdmin">
	<label>Admin</label>
	<div class="optionsSemester">';
	$output = fetchIsAdminForProfessor($output, -1);
	$output .= '
	</div>
	</div>
	<div class="crudRadioButtonContainer" id="crudRadioButtonContainerAddTitular">
	<label>Titular</label>
	<div class="optionsSemester">';
	$output = fetchIsTitularForProfessor($output, -1);
	$output .= '
	</div>
	</div>
	<div class="crudRadioButtonContainerList">
	<label>Cursuri</label>
	<div class="options">';
	$output = fetchCoursesForProfessor($output, -1, -1);
	$output .= '
	</div>
	</div>
	</div>
	<div class="crudEntityButton">
	<button type="submit" name="createProfessor">Adauga</button>
	</div>
	</form>';
	return $output;
}
function updateProfessorFetchData()
{
	$row = fetchDataProfessorForUpdate($_SESSION['professorIdForEdit']);
	$output = '
	<h1>Modificare professor</h1>
	<form action="./controllers/admin/crudProfessor.php" method="post">
	<div class="crudPopUpContainer">
	<div class="crudInputContainer">
	<label for="titleProfessorEdit">Grad</label>
	<input type="text" name="titleProfessorEdit" id="titleProfessorEdit" placeholder="Introduceti gradul" value="'.$row["title"].'">
	<br>
	</div>
	<div class="crudInputContainer">
	<label for="firstNameProfessorEdit">Prenume</label>
	<input type="text" name="firstNameProfessorEdit" id="firstNameProfessorEdit" placeholder="Introduceti prenumele" value="'.$row["first_name"].'">
	<br>
	</div>
	<div class="crudInputContainer">
	<label for="lastNameProfessorEdit">Nume</label>
	<input type="text" name="lastNameProfessorEdit" id="lastNameProfessorEdit" placeholder="Introduceti numele" value="'.$row["last_name"].'">
	<br>
	</div>
	<div class="crudInputContainer">
	<label for="emailProfessorEdit">Email</label>
	<input type="text" name="emailProfessorEdit" id="emailProfessorEdit" placeholder="Introduceti emailul" value="'.$row["email"].'">
	<br>
	</div>
	<div class="crudRadioButtonContainer" id="crudRadioButtonContainerAddAdmin">
	<label>Admin</label>
	<div class="optionsSemester">';
	$output = fetchIsAdminForProfessor($output, $row['is_admin']);
	$output .= '
	</div>
	</div>
	<div class="crudRadioButtonContainer" id="crudRadioButtonContainerAddTitular">
	<label>Titular</label>
	<div class="optionsSemester">';
	$output = fetchIsTitularForProfessor($output, $_SESSION['titularForEdit']);
	$output .= '
	</div>
	</div>
	<div class="crudRadioButtonContainerList">
	<label>Cursuri</label>
	<div class="options">';
	$output = fetchCoursesForProfessor($output, $_SESSION['professorIdForEdit'], $_SESSION['titularForEdit']);
	$output .= '
	</div>
	</div>
	</div>
	<div class="crudEntityButton">
	<button type="submit" name="updateProfessor">Modificare</button>
	</div>
	</form>';
	return $output;
}

?>