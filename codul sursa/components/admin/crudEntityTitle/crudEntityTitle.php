<style>
<?php include 'crudEntityTitle.css';?>
</style>
<div class="crudEntityTitle">
    <?php 
    if ($_GET['url'] == 'crudStudent-admin')
    {
    	echo '<h1>Studenti</h1>';
    }
    if ($_GET['url'] == 'crudProfessor-admin')
    {
    	echo '<h1>Profesori</h1>';
    }
    if ($_GET['url'] == 'crudCourse-admin')
    {
        echo '<h1>Cursuri</h1>';
    }
    if ($_GET['url'] == 'crudGroup-admin')
    {
        echo '<h1>Grupe</h1>';
    }
    if ($_GET['url'] == 'crudSpecialization-admin')
    {
        echo '<h1>Specializari</h1>';
    }
    if ($_GET['url'] == 'crudQuestion-admin')
    {
        echo '<h1>Intrebari</h1>';
    }
    if ($_GET['url'] == 'crudStudyYear-admin')
    {
        echo '<h1>Ani de studiu</h1>';
    }
    ?>
</div>