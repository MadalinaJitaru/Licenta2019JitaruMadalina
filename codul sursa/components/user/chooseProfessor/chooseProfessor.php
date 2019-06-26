<style>
    <?php include 'chooseProfessor.css';?>
</style>
<?php 
require './controllers/student/chooseProfessorFetchDataForStudent.php';
require './controllers/professor/chooseProfessorFetchDataForProfessor.php';
require './controllers/admin/chooseProfessorFetchDataForAdmin.php';
?>

<div class="chooseProfessorContainer">
    <?php 
    if (isset($_SESSION['invalidData']) && $_SESSION['invalidData']) {
        echo '<p class="invalidDataChooseProfessor">Optiune neselectata!</p>';   
    } 
    if($_GET['url'] == "chooseProfessor-student")
    {
        echo fetchDataStudent();
    }
    elseif ($_GET['url'] == "chooseProfessor-professor") {
        echo fetchDataProfessor();
    }
    elseif ($_GET['url'] == "chooseProfessor-admin") {
        echo fetchDataAdmin();
    }
    ?>
</div>
<?php
    unset($_SESSION['invalidData']);
?>