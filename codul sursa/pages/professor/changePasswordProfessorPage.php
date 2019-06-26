<?php 
include './components/style/leftSkewed/leftSkewed.php';
if (session_status() == PHP_SESSION_NONE) 
{
	session_start();
}?>

<div class="leftSkewed">	
	<?php include './components/professor/changePasswordTitle/changePasswordTitle.php';?>  
	<?php include './components/professor/changePasswordInput/changePasswordInput.php';?>
	<?php include './components/professor/changePasswordBackButton/changePasswordBackButton.php';?> 
</div>

<?php 
if(isset($_SESSION['changedPasswordProfessor']) && $_SESSION['changedPasswordProfessor'])
{
	include './components/professor/changePasswordAlertPositive/changePasswordAlertPositive.php';
}
unset($_SESSION['changedPasswordProfessor']);
?>
