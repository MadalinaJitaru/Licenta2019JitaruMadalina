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
	$_SESSION['emailIsValid'] = false;
	$_SESSION['passwordIsValid'] = false;
	$_SESSION['dataIsValid'] = false;
}
function setVariablesOnSession($emailIsValid, $passwordIsValid, $email, $password)
{
	$_SESSION['emailIsValid'] = $emailIsValid;
	$_SESSION['passwordIsValid'] = $passwordIsValid;
	$_SESSION['email'] = $email;
	$_SESSION['password'] = $password;
}
function getLoginDataFromDatabase($email, $password)
{
	require '../../classes/database.php';

	$result = $con->prepare("select * from professors where email = ? and password = ?");
	$result->bind_param('ss', $email, $password);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row;	
}
function getIdCourseForProfessor()
{
	require '../../classes/database.php';

	$result = $con->prepare("select course_id, is_titular from professor_courses where professor_id = ?");
	$result->bind_param('i', $_SESSION['idProfessor']);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row['course_id'];
}
function hasMoreCoursesThisProfessor()
{
	require '../../classes/database.php';

	$result = $con->prepare("select count(id) from professor_courses where professor_id = ?");
	$result->bind_param('i', $_SESSION['idProfessor']);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	if ($row['count(id)'] > 1)
	{
		return true;
	}
	return false;
	
}
function isAdmin()
{
	require '../../classes/database.php';

	$result = $con->prepare("select is_admin from professors where id = ?");
	$result->bind_param('i', $_SESSION['idProfessor']);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	if ($row['is_admin'] == 1)
	{
		return true;
	}
	return false;
}
function isTitular()
{
	require '../../classes/database.php';
	$idCourse = getIdCourseForProfessor();
	$result = $con->prepare("select is_titular from professor_courses where professor_id=? and course_id=?");
	$result->bind_param('ii', $_SESSION['idProfessor'], $idCourse);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	if ($row['is_titular'] == 1)
	{
		return true;
	}
	return false;
}
function redirectionProfessor()
{
	if(isAdmin())
	{
		$_SESSION['isAdmin'] = true;
		header('Location: ../../chooseOption-admin');
	}
	else
	{
		if(hasMoreCoursesThisProfessor() or isTitular())
		{
			$_SESSION['moreCourses'] = true;
			header('Location: ../../chooseProfessor-professor');
		}
		else
		{
			$_SESSION['courseIdForProfessor'] = getIdCourseForProfessor();
			$_SESSION['professorIdForProfessor'] = $_SESSION['idProfessor'];
			$_SESSION['oneCourse'] = true;
			header('Location: ../../feedbackResults-professor');
		}
	}
}
function redirectionProfessorIfLoginDataIsOrNotCorrect($email, $password)
{
	$row = getLoginDataFromDatabase($email, $password);
	if($row['email']==$email && $row['password']==$password)
	{
		$_SESSION['loggedProfessor'] = true;
		$_SESSION['idProfessor'] = $row['id'];
		redirectionProfessor();
	}
	else
	{
		setVariablesOnFalse();
		header('Location: ../../login-professor');
	}
}
function redirectionProfessorIfDataInputIsOrNotEmpty($email, $password, $emailIsValid, $passwordIsValid)
{
	if($emailIsValid && $passwordIsValid)
	{
		redirectionProfessorIfLoginDataIsOrNotCorrect($email, $password);
	}
	else
	{
		header('Location: ../../login-professor');
	}
}
function loginProfessor()
{
	require '../../classes/database.php';

	$email = trim($_POST["email"]);
	$password = trim($_POST["parola"]);
	$emailIsValid = true;
	$passwordIsValid = true;

	//my sql injection
	$email = stripcslashes($email);
	$email = mysqli_real_escape_string($con, $email);
	$password = stripcslashes($password);
	$password = mysqli_real_escape_string($con, $password);

	$emailIsValid = emptyInput($email);
	$passwordIsValid = emptyInput($password);

	setVariablesOnSession($emailIsValid, $passwordIsValid, $email, $password);

	redirectionProfessorIfDataInputIsOrNotEmpty($email, $password, $emailIsValid, $passwordIsValid);
}
function getPasswordForProfessor()
{
	require '../../classes/database.php';

	$result = $con->prepare("select password from professors where email = ?");
	$result->bind_param('s', $_POST['emailForReceive']);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row['password'];
}
function sendMail()
{
	require_once "Mail.php";

	$password = getPasswordForProfessor();
	if(empty($_POST["emailForReceive"]) or empty($password))
	{
		$_SESSION['wrongMail'] = true;
		header('Location: ../../login-professor');

	}

	$from = '<feedback.profesori@gmail.com>';
	$to = '<'.$_POST["emailForReceive"].'>';
	
	$subject = 'Parola feedback';
	$body = 'Parola pentru aplicatia de feedback pentru profesori este: '.$password;

	$headers = array(
		'From' => $from,
		'To' => $to,
		'Subject' => $subject
	);

	$smtp = Mail::factory('smtp', array(
		'host' => 'ssl://smtp.gmail.com',
		'port' => '465',
		'auth' => true,
		'username' => 'feedback.profesori@gmail.com',
		'password' => 'feedback123456789'
	));

	$mail = $smtp->send($to, $headers, $body);

	if (PEAR::isError($mail)) {
		echo('<p>' . $mail->getMessage() . '</p>');
	} else {
		echo('<p>Message successfully sent!</p>');
	}
	header('Location: ../../login-professor');
	
}
if(isset($_POST['loginProfessor']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	loginProfessor();

	die();
}
if(isset($_POST['sendMail']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	sendMail();

	die();
}
class LoginProfessor extends Controller {

}

?>