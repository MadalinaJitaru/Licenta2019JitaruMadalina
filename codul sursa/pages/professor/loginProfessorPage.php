<?php 
include './components/style/rightSkewed/rightSkewed.php';
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"]) {
	header("Location: chooseOption-admin");
	die();
}
if (isset($_SESSION["loggedProfessor"]) && $_SESSION["loggedProfessor"]) {
	header("Location: chooseProfessor-professor");
	die();
}
?>
<div class="rightSkewed">
	<?php include './components/user/login/login.php';?>
	<?php include './components/user/loginDelimiterLine/loginDelimiterLine.php';?>
	<?php include './components/professor/login/login.php';?>
</div>