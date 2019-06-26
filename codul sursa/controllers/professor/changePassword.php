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
	$_SESSION['emailChangePasswordIsValid'] = false;
	$_SESSION['oldPasswordIsValid'] = false;
	$_SESSION['newPasswordIsValid'] = false;
	$_SESSION['confirmNewPasswordIsValid'] = false;
	$_SESSION['dataForChangePasswordIsValid'] = false;
}
function setVariablesOnSession($emailChangePassword, $oldPassword, $newPassword, $confirmNewPassword, $emailChangePasswordIsValid, $oldPasswordIsValid, $newPasswordIsValid, $confirmNewPasswordIsValid)
{
	$_SESSION['emailChangePasswordIsValid'] = $emailChangePasswordIsValid;
	$_SESSION['oldPasswordIsValid'] = $oldPasswordIsValid;
	$_SESSION['newPasswordIsValid'] = $newPasswordIsValid;
	$_SESSION['confirmNewPasswordIsValid'] = $confirmNewPasswordIsValid;
	
	$_SESSION['emailChangePassword'] = $emailChangePassword;
	$_SESSION['oldPassword'] = $oldPassword;
	$_SESSION['newPassword'] = $newPassword;
	$_SESSION['confirmNewPassword'] = $confirmNewPassword;
}
function getChangePasswordDataFromDatabase($emailChangePassword, $oldPassword)
{
	require '../../classes/database.php';
	$result = $con->prepare("select * from professors where email = ? and password = ?");
	$result->bind_param('ss', $emailChangePassword, $oldPassword);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row;
}
function redirectionProfessorIfChangePasswordDataIsOrNotCorrect($con, $emailChangePassword, $oldPassword, $newPassword)
{
	$row = getChangePasswordDataFromDatabase($emailChangePassword, $oldPassword);
	if($row['email']==$emailChangePassword && $row['password']==$oldPassword)
	{
		$_SESSION['changedPasswordProfessor'] = true;
		$updatePassword = mysqli_query($con, "update professors set password='$newPassword' where email='$emailChangePassword'");

		header('Location: ../../changePassword-professor');			
	}
	else
	{
		setVariablesOnFalse();
		header('Location: ../../changePassword-professor');
	}
}
function redirectionProfessorIfDataInputIsOrNotEmpty($con, $emailChangePassword, $oldPassword, $newPassword, $confirmNewPassword, $emailChangePasswordIsValid, $oldPasswordIsValid, $newPasswordIsValid, $confirmNewPasswordIsValid)
{
	if($emailChangePasswordIsValid && $oldPasswordIsValid && $newPasswordIsValid && $confirmNewPasswordIsValid)
	{
		if($newPassword == $confirmNewPassword && $newPassword!=$oldPassword)
		{
			redirectionProfessorIfChangePasswordDataIsOrNotCorrect($con, $emailChangePassword, $oldPassword, $newPassword);	
		}
		else
		{
			setVariablesOnFalse();
			header('Location: ../../changePassword-professor');	
		}
	}
	else
	{
		header('Location: ../../changePassword-professor');
	}
}
function changePasswordProfessor()
{
	require '../../classes/database.php';

	$emailChangePassword = trim($_POST["emailSchimbareParola"]);
	$oldPassword = $_POST["parolaVeche"];
	$newPassword = $_POST["parolaNoua"];
	$confirmNewPassword = $_POST["confirmareParolaNoua"];
	$emailChangePasswordIsValid = true;
	$oldPasswordIsValid = true;
	$newPasswordIsValid = true;
	$confirmNewPasswordIsValid = true;

	//my sql injection
	$emailChangePassword = stripcslashes($emailChangePassword);
	$emailChangePassword = mysqli_real_escape_string($con, $emailChangePassword);
	$oldPassword = stripcslashes($oldPassword);
	$oldPassword = mysqli_real_escape_string($con, $oldPassword);
	$newPassword = stripcslashes($newPassword);
	$newPassword = mysqli_real_escape_string($con, $newPassword);
	$confirmNewPassword = stripcslashes($confirmNewPassword);
	$confirmNewPassword = mysqli_real_escape_string($con, $confirmNewPassword);

	$emailChangePasswordIsValid = emptyInput($emailChangePassword);
	$oldPasswordIsValid = emptyInput($oldPassword);
	$newPasswordIsValid = emptyInput($newPassword);
	$confirmNewPasswordIsValid = emptyInput($confirmNewPassword);

	setVariablesOnSession($emailChangePassword, $oldPassword, $newPassword, $confirmNewPassword, $emailChangePasswordIsValid, $oldPasswordIsValid, $newPasswordIsValid, $confirmNewPasswordIsValid);

	redirectionProfessorIfDataInputIsOrNotEmpty($con, $emailChangePassword, $oldPassword, $newPassword, $confirmNewPassword, $emailChangePasswordIsValid, $oldPasswordIsValid, $newPasswordIsValid, $confirmNewPasswordIsValid);
}
if(isset($_POST['changePasswordProfessor']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	changePasswordProfessor();

	die();
}
class ChangePassword extends Controller {

}

?>