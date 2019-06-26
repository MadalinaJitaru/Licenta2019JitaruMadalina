<?php
if(isset($_POST['editSpecializationId']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}
	$_SESSION['specializationIdForEdit'] = $_POST['editSpecializationId'];
	echo updateSpecializationFetchData();
}
function requireDatabaseForSpecialization()
{
	if(isset($_POST['editSpecializationId']))
	{
		require '../../classes/database.php';			
	}
	else
	{
		require './classes/database.php';
	}
	return $con;
}
function getFacultyIdForEditSpecialization()
{
	$con = requireDatabaseForSpecialization();

	$result = $con->prepare("select * from faculty_professors where professor_id = ?");
	$result->bind_param('i', $_SESSION['idProfessor']);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row['faculty_id'];
}
function fetchDataSpecializationForUpdate($specializationId)
{
	$facultyId = getFacultyIdForEditSpecialization();

	$con = requireDatabaseForSpecialization();

	$result = $con->prepare("select * from specialization where id=?");
	$result->bind_param('i', $specializationId);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);
	return $row;
}
function readSpecializationFetchData()
{
	$output = '
	<span class="container" style="cursor:default">
	<div class="gridContainerSpecialization">
	<div class="gridItem">Nr.</div>
	<div class="gridItem">Nume specializare</div>
	</div>
	</span>';
	$indexSpecialization = 0;
	$facultyId = getFacultyIdForEditSpecialization();

	require './classes/database.php';

	$result = $con->prepare('select * from specialization where faculty_id=?');
	$result->bind_param('i', $facultyId);
	$result->execute();

	$stmt = $result->get_result();
	while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		$indexSpecialization += 1;
		$output .= '
		<label onclick="hideElement(\'optionError\')" for="line'.$indexSpecialization.'" class="container">';
		if(isset($_SESSION['specializationIdForEdit']) && $row['id'] == $_SESSION['specializationIdForEdit'])
		{
			$output .= '<input checked id="line'.$indexSpecialization.'" type="radio" name="specialization" value="'.$row['id'].'">';
		}
		else
		{	
			$output .= '<input id="line'.$indexSpecialization.'" type="radio" name="specialization" value="'.$row['id'].'">';
		}
		$output .= '
		<div class="gridContainerSpecialization">
		<div class="gridItem">'.$indexSpecialization.'. </div>
		<div class="gridItem">'.ucfirst($row["specialization_name"]).'</div>
		</div>
		</label>';
	}
	return $output;
}
function createSpecializationFetchData()
{
	$output = '
	<h1>Adaugare specializare</h1>
	<form action="./controllers/admin/crudSpecialization.php" method="post">
	<div class="crudPopUpContainer">
	<div class="crudInputContainer">
	<label for="specializationAdd">Specializare</label>
	<textarea type="text" name="specializationAdd" id="specializationAdd" placeholder="Introduceti numele specializarii"></textarea>
	<br>
	</div>
	</div>
	<div class="crudEntityButton">
	<button type="submit" name="createSpecialization">Adauga</button>
	</div>
	</form>';
	return $output;
}
function updateSpecializationFetchData()
{
	$row = fetchDataSpecializationForUpdate($_SESSION['specializationIdForEdit']);
	$output = '
	<h1>Modificare specializare</h1>
	<form action="./controllers/admin/crudSpecialization.php" method="post">
	<div class="crudPopUpContainer">
	<div class="crudInputContainer">
	<label for="specializationEdit">Specializare</label>
	<textarea type="text" name="specializationEdit" id="specializationEdit" placeholder="Introduceti numele specializarii">'.$row["specialization_name"].'</textarea>
	<br>
	</div>
	</div>
	<div class="crudEntityButton">
	<button type="submit" name="updateSpecialization">Modifica</button>
	</div>
	</form>
	';
	return $output;
}
?>