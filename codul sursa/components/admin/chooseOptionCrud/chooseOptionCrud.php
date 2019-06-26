<style>
	<?php include 'chooseOptionCrud.css';?>
</style>
<div class="chooseOptionCrudContainer">
	<a href="./crudStudent-admin">
		<button>Operatii Student</button>
	</a>
	<a href="./crudProfessor-admin">
		<button>Operatii Profesor</button>
	</a>
	<a href="./crudGroup-admin">
		<button>Operatii Grupa</button>
	</a>
	<a href="./crudCourse-admin">
		<button>Operatii Cursuri</button>
	</a>
	<a href="./crudSpecialization-admin">
		<button>Operatii Specializare</button>
	</a>
	<a href="./crudQuestion-admin">
		<button>Operatii Intrebari</button>
	</a>
	<a href="./crudStudyYear-admin">
		<button >Operatii Ani de studiu</button>
	</a>
	<button onclick="showElement('deleteAllInformation')">Golire baza de date</button>
</div>
<div class="deleteAllInformationPopUp" id="deleteAllInformation">
		<p>Sunteti sigur ca doriti sa stergeti TOATE informatiile din baza de date?</p>
		<form action="./controllers/admin/chooseOption.php" method="post">
			<button class="confirm" type="submit" name="deleteAllInformationDataBase">Da</button>
		</form>
		<button class="cancel" onclick="hideElement('deleteAllInformation')">Nu</button>
	</div>
<script type="text/javascript" src="./components/admin/crudEntity/crudEntity.js"></script>