<?php 
include './components/style/rightSkewed/rightSkewed.php';
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
if (isset($_SESSION["loggedStudent"]) && $_SESSION["loggedStudent"]) {
	header("Location: moreInformation-student");
	die();
}
?>
<div class="rightSkewed">
	<div class="rightSkewedContainer">
		<?php include './components/user/login/login.php';?>
		<?php include './components/user/loginDelimiterLine/loginDelimiterLine.php';?>
		<?php include './components/student/login/login.php';?>
		<?php include './components/user/loginImProfessor/loginImProfessor.php';?>
	</div>
</div>