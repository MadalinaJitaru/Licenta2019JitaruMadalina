<?php 

if(isset($_POST['editStudentId']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}
	$_SESSION['studentIdForEdit'] = $_POST['editStudentId'];
	echo updateStudentFetchData();
}

function requireDatabase()
{
	if(isset($_POST['editStudentId']))
	{
		require '../../classes/database.php';			
	}
	else
	{
		require './classes/database.php';
	}
	return $con;
}
function getFacultyId()
{
	$con = requireDatabase();

	$result = $con->prepare("select * from faculty_professors where professor_id = ?");
	$result->bind_param('i', $_SESSION['idProfessor']);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row['faculty_id'];
}
function fetchDataStudentForUpdate($studentId)
{
	$facultyId = getFacultyId();

	$con = requireDatabase();
	$result = $con->prepare("SELECT s.id as 'student_id', s.register_number, s.identification_number,sp.id as 'specialization_id', sy.id as 'study_year_id', g.id as 'group_id' FROM students s join groups g on g.id =s.group_id join study_years sy on sy.id=g.study_year_id join specialization sp on sp.id=g.specialization_id where g.faculty_id=? and s.id=?");
	$result->bind_param('ii', $facultyId, $studentId);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);
	return $row;
}
function fetchSpecialization($output, $specializationId)
{
	$facultyId = getFacultyId();
	
	$con = requireDatabase();
	$result = $con->prepare("select * from specialization where faculty_id = ?");
	$result->bind_param('i', $facultyId);
	$result->execute();

	$stmt = $result->get_result();
	while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		$output .= '<label class="option">'.$row['specialization_name'];
		if($specializationId == $row['id'])
		{
			$output .= '<input checked type="radio" name="idSpecializationAdd" value="'.$row['id'].'">';
		}
		else
		{
			$output .= '<input type="radio" name="idSpecializationAdd" value="'.$row['id'].'">';
		}
		$output .= '<br>
		<span class="pseudoRadioButton"></span>
		</label>';
		
	}

	return $output;
}
function fetchYearOfStudy($output, $yearOfStudyId)
{
	$facultyId = getFacultyId();
	
	$con = requireDatabase();	
	$result = $con->prepare("select * from study_years where faculty_id = ?");
	$result->bind_param('i', $facultyId);
	$result->execute();

	$stmt = $result->get_result();
	while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		$output .= '<label class="option">'.$row['study_year'];
		if($yearOfStudyId == $row['id'])
		{
			$output .= '<input checked type="radio" name="idYearAdd" value="'.$row['id'].'">';
		}
		else
		{
			$output .= '<input type="radio" name="idYearAdd" value="'.$row['id'].'">';
		}
		$output .= '
		<br>
		<span class="pseudoRadioButton"></span>
		</label>';
	}

	return $output;
}
function fetchGroup($output, $groupId)
{
	$facultyId = getFacultyId();
	
	$con = requireDatabase();	
	$result = $con->prepare("select g.id, sy.study_year, g.group_name from study_years sy join groups g on g.study_year_id=sy.id where g.faculty_id = ? order by sy.study_year");
	$result->bind_param('i', $facultyId);
	$result->execute();

	$stmt = $result->get_result();
	while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		$output .= '<label class="option">'.$row['group_name'].' - '.$row['study_year'];
		if($groupId == $row['id'])
		{
			$output .= '<input checked type="radio" name="idGroupAdd" value="'.$row['id'].'">';
		}
		else
		{
			$output .= '<input type="radio" name="idGroupAdd" value="'.$row['id'].'">';	
		}
		$output .= '
		<br>
		<span class="pseudoRadioButton"></span>
		</label>';
	}
	return $output;
}
function readStudentFetchData()
{
	$output = '
	<span class="container" style="cursor:default">
	<div class="gridContainerStudent">
	<div class="gridItem">Nr.</div>
	<div class="gridItem">Numar matricol</div>
	<div class="gridItem">CNP</div>
	<div class="gridItem">Specializare</div>
	<div class="gridItem">Anul de studiu</div>
	<div class="gridItem">Grupa</div>
	</div>
	</span>';
	$indexStudent = 0;
	$facultyId = getFacultyId();

	require './classes/database.php';

	$result = $con->prepare('SELECT s.id, s.register_number, s.identification_number,sp.specialization_name, sy.study_year, g.group_name FROM students s join groups g on g.id =s.group_id join study_years sy on sy.id=g.study_year_id join specialization sp on sp.id=g.specialization_id where g.faculty_id=?');
	$result->bind_param('i', $facultyId);
	$result->execute();

	$stmt = $result->get_result();
	while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		$indexStudent += 1;
		$output .= '
		<label onclick="hideElement(\'optionError\')" for="line'.$indexStudent.'" class="container">';
		
		if(isset($_SESSION['studentIdForEdit']) && $row['id'] == $_SESSION['studentIdForEdit'])
		{
			$output .= '<input checked id="line'.$indexStudent.'" type="radio" name="student" value="'.$row['id'].'">';		
		}
		else
		{
			$output .= '<input id="line'.$indexStudent.'" type="radio" name="student" value="'.$row['id'].'">';	
		}
		$output .= '
		<div class="gridContainerStudent">
		<div class="gridItem">'.$indexStudent.'. </div>
		<div class="gridItem">'.$row["register_number"].'</div>
		<div class="gridItem">'.$row["identification_number"].'</div>
		<div class="gridItem">'.$row["specialization_name"].'</div>
		<div class="gridItem">'.$row["study_year"].'</div>
		<div class="gridItem">'.$row["group_name"].'</div>
		</div>
		</label>';
	}
	return $output;
}
function createStudentFetchData()
{
	$output = '
	<h1>Adaugare student</h1>
	<form action="./controllers/admin/crudStudent.php" method="post">
	<div class="crudPopUpContainer">
	<div class="crudInputContainer">
	<label for="numarMatricolAdd">Numar Matricol</label>
	<input type="text" name="numarMatricolAdd" id="numarMatricolAdd" placeholder="Introduceti numarul matricol">
	<br>
	</div>
	<div class="crudInputContainer">
	<label for="CNPAdd">CNP</label>
	<input type="text" name="CNPAdd" id="CNPAdd" placeholder="Introduceti CNP-ul">
	<br>
	</div>
	<div class="crudRadioButtonContainer">
	<label>Specializarea</label>
	<div class="options">';
	$output = fetchSpecialization($output, 0);
	$output .= '
	</div>
	</div>
	<div class="crudRadioButtonContainer">
	<label>Anul de studiu</label>
	<div class="options">';
	$output = fetchYearOfStudy($output, 0);
	$output .= '
	</div>
	</div>
	<div class="crudRadioButtonContainer">
	<label>Grupa</label>
	<div class="options">';
	$output = fetchGroup($output, 0);
	$output .= '
	</div>
	</div>
	</div>
	<div class="crudEntityButton">
	<button type="submit" name="createStudent">Adauga</button>
	</div>
	</form>
	
	';
	return $output;
}
function updateStudentFetchData()
{
	$row = fetchDataStudentForUpdate($_SESSION['studentIdForEdit']);
	$output = '
	<h1>Modificare student</h1>
	<form action="./controllers/admin/crudStudent.php" method="post">
	<div class="crudPopUpContainer">
	<div class="crudInputContainer">
	<label for="numarMatricolEdit">Numar Matricol</label>
	<input type="text" name="numarMatricolEdit" id="numarMatricolEdit" placeholder="Introduceti numarul matricol" value="'.$row["register_number"].'">
	<br>
	</div>
	<div class="crudInputContainer">
	<label for="CNPEdit">CNP</label>
	<input type="text" name="CNPEdit" id="CNPEdit" placeholder="Introduceti CNP-ul" value="'.$row["identification_number"].'">
	<br>
	</div>
	<div class="crudRadioButtonContainer">
	<label>Specializarea</label>
	<div class="options">';
	$output = fetchSpecialization($output, $row['specialization_id']);
	$output .= '
	</div>
	</div>
	<div class="crudRadioButtonContainer">
	<label>Anul de studiu</label>
	<div class="options">';
	$output = fetchYearOfStudy($output, $row['study_year_id']);
	$output .= '
	</div>
	</div>
	<div class="crudRadioButtonContainer">
	<label>Grupa</label>
	<div class="options">';
	$output = fetchGroup($output, $row['group_id']);
	$output .= '
	</div>
	</div>
	</div>
	<div class="crudEntityButton">
	<button type="submit" name="updateStudent">Modifica</button>
	</div>
	</form>
	';
	return $output;
}

?>