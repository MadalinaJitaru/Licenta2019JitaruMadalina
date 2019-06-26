<style>
	<?php include 'logoutLeft.css';?>
</style>
<?php 
if ($_GET['url'] == 'moreInformation-student' ||
	$_GET['url'] == 'chooseProfessor-student' ||
	$_GET['url'] == 'questionsForFeedback-student') 
	{ ?>
		<form id="form-id" action="./controllers/student/logoutStudent.php" method="post">
		<?php } else { ?>
			<form id="form-id" action="./controllers/professor/logoutProfessor.php" method="post">
			<?php } ?>
			<div class="logoutLeft">
				<img onclick="document.getElementById('form-id').submit();" src="./components/user/logoutLeft/logoutLeft.png"/>
				<span class="tooltiptext">Logout</span>
			</div>
		</form>