<?php
function emptyInput($input)
{
	if(empty($input))
	{
		return false;
	}
	return true;
}
function setVariablesOnFalse()
{
	$_SESSION['registerNumberIsValid'] = false;
	$_SESSION['identificationNumberIsValid'] = false;
	$_SESSION['dataIsValid'] = false;
}
function setVariablesOnSession($registerNumber, $identificationNumber, $registerNumberIsValid, $identificationNumberIsValid)
{
	$_SESSION['registerNumberIsValid'] = $registerNumberIsValid;
	$_SESSION['identificationNumberIsValid'] = $identificationNumberIsValid;
	$_SESSION['registerNumber'] = $registerNumber;
	$_SESSION['identificationNumber'] = $identificationNumber;
}
function getLoginDataFromDatabase($registerNumber, $identificationNumber)
{
	require '../../classes/database.php';

	$result = $con->prepare("select * from students where register_number = ? and identification_number = ?");
	$result->bind_param('ss', $registerNumber, $identificationNumber);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row;	
}
function putExtraInformationOnSession($row)
{
	$_SESSION['groupIdStudent'] = $row['group_id'];
	$_SESSION['idStudent'] = $row['id'];
}
function redirectionStudentIfLoginDataIsOrNotCorrect($registerNumber, $identificationNumber)
{
	$row = getLoginDataFromDatabase($registerNumber, $identificationNumber);
	if($row['register_number']==$registerNumber && $row['identification_number']==$identificationNumber)
	{
		$_SESSION['loggedStudent'] = true;
		putExtraInformationOnSession($row);
		header('Location: ../../moreInformation-student');			
	}
	else
	{
		setVariablesOnFalse();
		header('Location: ../../login-student');
	}
}
function redirectionStudentIfDataInputIsOrNotEmpty($registerNumber, $identificationNumber, $registerNumberIsValid, $identificationNumberIsValid)
{
	if($registerNumberIsValid && $identificationNumberIsValid)
	{
		redirectionStudentIfLoginDataIsOrNotCorrect($registerNumber, $identificationNumber);
	}
	else
	{
		//setVariablesOnFalse();
		header('Location: ../../login-student');
	}
}
function loginStudent()
{
	require '../../classes/database.php';

	$registerNumber = trim(strtoupper($_POST["numarMatricol"]));
	$identificationNumber = trim(strtoupper($_POST["CNP"]));
	$registerNumberIsValid = true;
	$identificationNumberIsValid = true;

	//my sql injection
	$registerNumber = stripcslashes($registerNumber);
	$registerNumber = mysqli_real_escape_string($con, $registerNumber);
	$identificationNumber = stripcslashes($identificationNumber);
	$identificationNumber = mysqli_real_escape_string($con, $identificationNumber);

	$registerNumberIsValid = emptyInput($registerNumber);
	$identificationNumberIsValid = emptyInput($identificationNumber);

	setVariablesOnSession($registerNumber, $identificationNumber, $registerNumberIsValid, $identificationNumberIsValid);

	redirectionStudentIfDataInputIsOrNotEmpty($registerNumber, $identificationNumber, $registerNumberIsValid, $identificationNumberIsValid);
}
if(isset($_POST['loginStudent']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	loginStudent();
	die();
}

class LoginStudent extends Controller {
	
}

?>