<style>
    <?php include 'crudEntity.css';?>
</style>
<?php
require './controllers/admin/crudStudentFetchData.php';
require './controllers/admin/crudProfessorFetchData.php';
require './controllers/admin/crudSpecializationFetchData.php';
require './controllers/admin/crudQuestionFetchData.php';
require './controllers/admin/crudStudyYearFetchData.php';
require './controllers/admin/crudCourseFetchData.php';
require './controllers/admin/crudGroupFetchData.php';
?>
<p id="optionError">Linie neselectata!</p>
<div class="crudEntityButtonsRight">
    <?php 
    if ($_GET['url'] == 'crudStudent-admin')
    {
        echo '<div class="tooltipContainer">
        <img id="studentEditEntity" onclick="selectedEntity(\'editPopUp\', \'student\')" src="./components/admin/crudEntity/editEntity.svg"/>
        <span class="tooltipText">Modificare student</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="showElement(\'addPopUp\')" src="./components/admin/crudEntity/addEntity.svg"/>
        <span class="tooltipText">Adaugare student</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="selectedEntity(\'deletePopUp\', \'student\')" src="./components/admin/crudEntity/deleteEntity.svg"/>
        <span class="tooltipText">Stergere student</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="showElement(\'deleteAllForTablePopUp\')" src="./components/admin/crudEntity/deleteEntity.svg"/>
        <span class="tooltipText">Stergere toti studentii</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="showElement(\'importPopUp\')" src="./components/admin/crudEntity/importEntity.svg"/>
        <span class="tooltipText">Import studenti</span>
        </div>
        <div class="tooltipContainer">
        <form action="./controllers/admin/crudStudent.php" method="post">
        <button type="submit" name="exportStudent"><img src="./components/admin/crudEntity/exportEntity.svg"/></button>
        <span class="tooltipText">Export studenti</span>
        </form>
        </div>';
    }
    if ($_GET['url'] == 'crudGroup-admin')
    {
        echo '<div class="tooltipContainer">
        <img id="groupEditEntity" onclick="selectedEntity(\'editPopUp\', \'group\')" src="./components/admin/crudEntity/editEntity.svg"/>
        <span class="tooltipText">Modificare grupa</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="showElement(\'addPopUp\')" src="./components/admin/crudEntity/addEntity.svg"/>
        <span class="tooltipText">Adaugare grupa</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="selectedEntity(\'deletePopUp\', \'group\')" src="./components/admin/crudEntity/deleteEntity.svg"/>
        <span class="tooltipText">Stergere grupa</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="showElement(\'deleteAllForTablePopUp\')" src="./components/admin/crudEntity/deleteEntity.svg"/>
        <span class="tooltipText">Stergere toate grupele</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="showElement(\'importPopUp\')" src="./components/admin/crudEntity/importEntity.svg"/>
        <span class="tooltipText">Import grupe</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="showElement(\'importReferencesPopUp\')" src="./components/admin/crudEntity/importEntity.svg"/>
        <span class="tooltipText">Import profesori grupe</span>
        </div>
        <div class="tooltipContainer">
        <form action="./controllers/admin/crudGroup.php" method="post">
        <button type="submit" name="exportGroup"><img src="./components/admin/crudEntity/exportEntity.svg"/></button>
        <span class="tooltipText">Export grupe</span>
        </form>
        </div>';
    }
    if ($_GET['url'] == 'crudSpecialization-admin')
    {
        echo '<div class="tooltipContainer">
        <img id="specializationEditEntity" onclick="selectedEntity(\'editPopUp\', \'specialization\')" src="./components/admin/crudEntity/editEntity.svg"/>
        <span class="tooltipText">Modificare specializare</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="showElement(\'addPopUp\')" src="./components/admin/crudEntity/addEntity.svg"/>
        <span class="tooltipText">Adaugare specializare</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="selectedEntity(\'deletePopUp\', \'specialization\')" src="./components/admin/crudEntity/deleteEntity.svg"/>
        <span class="tooltipText">Stergere specializare</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="showElement(\'deleteAllForTablePopUp\')" src="./components/admin/crudEntity/deleteEntity.svg"/>
        <span class="tooltipText">Stergere toate specializarile</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="showElement(\'importPopUp\')" src="./components/admin/crudEntity/importEntity.svg"/>
        <span class="tooltipText">Import specializari</span>
        </div>
        <div class="tooltipContainer">
        <form action="./controllers/admin/crudSpecialization.php" method="post">
        <button type="submit" name="exportSpecialization"><img src="./components/admin/crudEntity/exportEntity.svg"/></button>
        <span class="tooltipText">Export specializari</span>
        </form>
        </div>';
    }
    if ($_GET['url'] == 'crudStudyYear-admin')
    {
        echo '<div class="tooltipContainer">
        <img id="studyYearEditEntity" onclick="selectedEntity(\'editPopUp\', \'studyYear\')" src="./components/admin/crudEntity/editEntity.svg"/>
        <span class="tooltipText">Modificare an de studiu</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="showElement(\'addPopUp\')" src="./components/admin/crudEntity/addEntity.svg"/>
        <span class="tooltipText">Adaugare an de studiu</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="selectedEntity(\'deletePopUp\', \'studyYear\')" src="./components/admin/crudEntity/deleteEntity.svg"/>
        <span class="tooltipText">Stergere an de studiu</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="showElement(\'deleteAllForTablePopUp\')" src="./components/admin/crudEntity/deleteEntity.svg"/>
        <span class="tooltipText">Stergere toti anii de studiu</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="showElement(\'importPopUp\')" src="./components/admin/crudEntity/importEntity.svg"/>
        <span class="tooltipText">Import ani de studiu</span>
        </div>
        <div class="tooltipContainer">
        <form action="./controllers/admin/crudStudyYear.php" method="post">
        <button type="submit" name="exportStudyYear"><img src="./components/admin/crudEntity/exportEntity.svg"/></button>
        <span class="tooltipText">Export ani de studiu</span>
        </form>
        </div>';
    }
    ?>
</div>
<div class="crudEntityButtonsLeft">
    <?php
    if ($_GET['url'] == 'crudProfessor-admin')
    {
        echo '<div class="tooltipContainer">
        <form action="./controllers/admin/crudProfessor.php" method="post">
        <button type="submit" name="exportProfessor"><img src="./components/admin/crudEntity/exportEntity.svg"/></button>
        <span class="tooltipText">Export profesori</span>
        </form>
        </div>
        <div class="tooltipContainer">
        <img onclick="showElement(\'importPopUp\')" src="./components/admin/crudEntity/importEntity.svg"/>
        <span class="tooltipText">Import profesori</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="showElement(\'importReferencesPopUp\')" src="./components/admin/crudEntity/importEntity.svg"/>
        <span class="tooltipText">Import profesori cursuri</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="selectedEntity(\'deletePopUp\', \'professor\')" src="./components/admin/crudEntity/deleteEntity.svg"/>
        <span class="tooltipText">Stergere profesor</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="showElement(\'deleteAllForTablePopUp\')" src="./components/admin/crudEntity/deleteEntity.svg"/>
        <span class="tooltipText">Stergere toti profesorii</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="showElement(\'addPopUp\')" src="./components/admin/crudEntity/addEntity.svg"/>
        <span class="tooltipText">Adaugare profesor</span>
        </div>
        <div class="tooltipContainer">
        <img id="professorEditEntity" onclick="selectedEntity(\'editPopUp\', \'professor\')" src="./components/admin/crudEntity/editEntity.svg"/>
        <span class="tooltipText">Modificare profesor</span>
        </div>';
    }
    if ($_GET['url'] == 'crudCourse-admin')
    {
        echo '<div class="tooltipContainer">
        <form action="./controllers/admin/crudCourse.php" method="post">
        <button type="submit" name="exportCourse"><img src="./components/admin/crudEntity/exportEntity.svg"/></button>
        <span class="tooltipText">Export cursuri</span>
        </form>
        </div>
        <div class="tooltipContainer">
        <img onclick="showElement(\'importPopUp\')" src="./components/admin/crudEntity/importEntity.svg"/>
        <span class="tooltipText">Import cursuri</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="selectedEntity(\'deletePopUp\', \'course\')" src="./components/admin/crudEntity/deleteEntity.svg"/>
        <span class="tooltipText">Stergere curs</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="showElement(\'deleteAllForTablePopUp\')" src="./components/admin/crudEntity/deleteEntity.svg"/>
        <span class="tooltipText">Stergere toate cursurile</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="showElement(\'addPopUp\')" src="./components/admin/crudEntity/addEntity.svg"/>
        <span class="tooltipText">Adaugare curs</span>
        </div>
        <div class="tooltipContainer">
        <img id="courseEditEntity" onclick="selectedEntity(\'editPopUp\', \'course\')" src="./components/admin/crudEntity/editEntity.svg"/>
        <span class="tooltipText">Modificare curs</span>
        </div>';
    }
    if ($_GET['url'] == 'crudQuestion-admin')
    {
        echo '<div class="tooltipContainer">
        <form action="./controllers/admin/crudQuestion.php" method="post">
        <button type="submit" name="exportQuestion"><img src="./components/admin/crudEntity/exportEntity.svg"/></button>
        <span class="tooltipText">Export intrebari</span>
        </form>
        </div>
        <div class="tooltipContainer">
        <img onclick="showElement(\'importPopUp\')" src="./components/admin/crudEntity/importEntity.svg"/>
        <span class="tooltipText">Import intrebari</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="selectedEntity(\'deletePopUp\', \'question\')" src="./components/admin/crudEntity/deleteEntity.svg"/>
        <span class="tooltipText">Stergere intrebare</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="showElement(\'deleteAllForTablePopUp\')" src="./components/admin/crudEntity/deleteEntity.svg"/>
        <span class="tooltipText">Stergere toate intrebarile</span>
        </div>
        <div class="tooltipContainer">
        <img onclick="showElement(\'addPopUp\')" src="./components/admin/crudEntity/addEntity.svg"/>
        <span class="tooltipText">Adaugare intrebare</span>
        </div>
        <div class="tooltipContainer">
        <img id="questionEditEntity" onclick="selectedEntity(\'editPopUp\', \'question\')" src="./components/admin/crudEntity/editEntity.svg"/>
        <span class="tooltipText">Modificare intrebare</span>
        </div>';
    }
    ?>
</div>
<div class="crudEntityContainer">
    <?php 
    if ($_GET['url'] == 'crudStudent-admin')
    {
        echo readStudentFetchData();
    }
    if ($_GET['url'] == 'crudProfessor-admin')
    {
        echo readProfessorFetchData();
    }
    if ($_GET['url'] == 'crudSpecialization-admin')
    {
        echo readSpecializationFetchData();
    }
    if ($_GET['url'] == 'crudQuestion-admin')
    {
        echo readQuestionFetchData();
    }
    if ($_GET['url'] == 'crudStudyYear-admin')
    {
        echo readStudyYearFetchData();
    }
    if ($_GET['url'] == 'crudCourse-admin')
    {
        echo readCourseFetchData();
    }
    if ($_GET['url'] == 'crudGroup-admin')
    {
        echo readGroupFetchData();
    }
    ?>
</div>
<div class="crudPopUp" id="addPopUp">
    <img onclick="hideElement('addPopUp')" src="./components/admin/crudEntity/closePopUp.svg"/>
    <?php 
    if (isset($_SESSION['dataCreateEntityIsValid']) && !$_SESSION['dataCreateEntityIsValid']) 
    {
        echo '<script>document.getElementById("addPopUp").style.display = "block";</script>';
        echo '<p class="invalidDataCreateEntity">Campuri completate gresit sau necompletate!</p>';
    }
    if (isset($_SESSION['sameDataEntity']) && $_SESSION['sameDataEntity']) 
    {
        echo '<script>document.getElementById("addPopUp").style.display = "block";</script>';
        echo '<p class="sameDataCreateEntity">Informatiile campurilor deja existente!</p>';
    }
    
    if ($_GET['url'] == 'crudStudent-admin')
    {
        echo createStudentFetchData();
    }
    if ($_GET['url'] == 'crudSpecialization-admin')
    {
        echo createSpecializationFetchData();
    }
    if ($_GET['url'] == 'crudQuestion-admin')
    {
        echo createQuestionFetchData();
    }
    if ($_GET['url'] == 'crudStudyYear-admin')
    {
        echo createStudyYearFetchData();
    }
    if ($_GET['url'] == 'crudCourse-admin')
    {
        echo createCourseFetchData();
    }
    if ($_GET['url'] == 'crudGroup-admin')
    {
        echo createGroupFetchData();
    }
    if ($_GET['url'] == 'crudProfessor-admin')
    {
        echo createProfessorFetchData();
    }
    ?>
</div>
<div class="crudPopUp" id="editPopUp">
    <img onclick="hideElement('editPopUp')" src="./components/admin/crudEntity/closePopUp.svg"/>
    <?php
    if (isset($_SESSION['dataUpdadeEntityIsValid']) && !$_SESSION['dataUpdadeEntityIsValid']) 
    {
        if ($_GET['url'] == 'crudStudent-admin')
        {
            echo '<script>
            window.addEventListener("DOMContentLoaded", function(){
                document.getElementById("studentEditEntity").click();  
            }, false);
            </script>';
            echo '<p class="invalidDataUpdadeEntity">Campuri completate gresit sau necompletate!</p>';
        }
        if ($_GET['url'] == 'crudSpecialization-admin')
        {
            echo '<script>
            window.addEventListener("DOMContentLoaded", function(){
                document.getElementById("specializationEditEntity").click();  
            }, false);
            </script>';
            echo '<p class="invalidDataUpdadeEntity">Campuri completate gresit sau necompletate!</p>';
        }
        if ($_GET['url'] == 'crudQuestion-admin')
        {
            echo '<script>
            window.addEventListener("DOMContentLoaded", function(){
                document.getElementById("questionEditEntity").click();  
            }, false);
            </script>';
            echo '<p class="invalidDataUpdadeEntity">Campuri completate gresit sau necompletate!</p>';
        }
        if ($_GET['url'] == 'crudStudyYear-admin')
        {
            echo '<script>
            window.addEventListener("DOMContentLoaded", function(){
                document.getElementById("studyYearEditEntity").click();  
            }, false);
            </script>';
            echo '<p class="invalidDataUpdadeEntity">Campuri completate gresit sau necompletate!</p>';
        }
        if ($_GET['url'] == 'crudCourse-admin')
        {
            echo '<script>
            window.addEventListener("DOMContentLoaded", function(){
                document.getElementById("courseEditEntity").click();  
            }, false);
            </script>';
            echo '<p class="invalidDataUpdadeEntity">Campuri completate gresit sau necompletate!</p>';
        }
        if ($_GET['url'] == 'crudGroup-admin')
        {
            echo '<script>
            window.addEventListener("DOMContentLoaded", function(){
                document.getElementById("groupEditEntity").click();  
            }, false);
            </script>';
            echo '<p class="invalidDataUpdadeEntity">Campuri completate gresit sau necompletate!</p>';
        }
        if ($_GET['url'] == 'crudProfessor-admin')
        {
            echo '<script>
            window.addEventListener("DOMContentLoaded", function(){
                document.getElementById("professorEditEntity").click();  
            }, false);
            </script>';
            echo '<p class="invalidDataUpdadeEntity">Campuri completate gresit sau necompletate!</p>';
        }
    }
    echo '<div id="editPopUpContainer">
         </div>';
    ?>
</div>
<div class="deleteEntityPopUp" id="deletePopUp">
    <?php 
    if ($_GET['url'] == 'crudStudent-admin')
    {
        echo '<p>Sunteti sigur ca doriti sa stergeti acest student?</p>
        <form action="./controllers/admin/crudStudent.php" method="post">
        <button class="confirm" type="submit" id="deleteStudent" name="deleteStudent" value="">Da</button>
        </form>';
    }
    if ($_GET['url'] == 'crudSpecialization-admin')
    {
        echo '<p>Sunteti sigur ca doriti sa stergeti aceasta specializare?</p>
        <form action="./controllers/admin/crudSpecialization.php" method="post">
        <button class="confirm" type="submit" id="deleteSpecialization" name="deleteSpecialization" value="">Da</button>
        </form>';
    }
    if ($_GET['url'] == 'crudQuestion-admin')
    {
        echo '<p>Sunteti sigur ca doriti sa stergeti aceasta intrebare?</p>
        <form action="./controllers/admin/crudQuestion.php" method="post">
        <button class="confirm" type="submit" id="deleteQuestion" name="deleteQuestion" value="">Da</button>
        </form>';
    }
    if ($_GET['url'] == 'crudStudyYear-admin')
    {
        echo '<p>Sunteti sigur ca doriti sa stergeti acest an de studiu?</p>
        <form action="./controllers/admin/crudStudyYear.php" method="post">
        <button class="confirm" type="submit" id="deleteStudyYear" name="deleteStudyYear" value="">Da</button>
        </form>';
    }
    if ($_GET['url'] == 'crudCourse-admin')
    {
        echo '<p>Sunteti sigur ca doriti sa stergeti acest curs?</p>
        <form action="./controllers/admin/crudCourse.php" method="post">
        <button class="confirm" type="submit" id="deleteCourse" name="deleteCourse" value="">Da</button>
        </form>';
    }
    if ($_GET['url'] == 'crudGroup-admin')
    {
        echo '<p>Sunteti sigur ca doriti sa stergeti aceasta grupa?</p>
        <form action="./controllers/admin/crudGroup.php" method="post">
        <button class="confirm" type="submit" id="deleteGroup" name="deleteGroup" value="">Da</button>
        </form>';
    }
    if ($_GET['url'] == 'crudProfessor-admin')
    {
        echo '<p>Sunteti sigur ca doriti sa stergeti acest profesor?</p>
        <form action="./controllers/admin/crudProfessor.php" method="post">
        <button class="confirm" type="submit" id="deleteProfessor" name="deleteProfessor" value="">Da</button>
        </form>';
    }
    ?>
    <button class="cancel" onclick="hideElement('deletePopUp')">Nu</button>

</div>
<div class="deleteEntityPopUp" id="deleteAllForTablePopUp">
    <?php 
    if ($_GET['url'] == 'crudStudent-admin')
    {
        echo '<p>Sunteti sigur ca doriti sa stergeti TOATE informatiile despre studenti?</p>
        <form action="./controllers/admin/crudStudent.php" method="post">
        <button class="confirm" type="submit" name="deleteAllStudents">Da</button>
        </form>';
    }
    if ($_GET['url'] == 'crudSpecialization-admin')
    {
        echo '<p>Sunteti sigur ca doriti sa stergeti TOATE specializarile?</p>
        <form action="./controllers/admin/crudSpecialization.php" method="post">
        <button class="confirm" type="submit" name="deleteAllSpecialization">Da</button>
        </form>';
    }
    if ($_GET['url'] == 'crudQuestion-admin')
    {
        echo '<p>Sunteti sigur ca doriti sa stergeti TOATE intrebarile?</p>
        <form action="./controllers/admin/crudQuestion.php" method="post">
        <button class="confirm" type="submit" name="deleteAllQuestion" >Da</button>
        </form>';
    }
    if ($_GET['url'] == 'crudStudyYear-admin')
    {
        echo '<p>Sunteti sigur ca doriti sa stergeti TOTI ani de studiu?</p>
        <form action="./controllers/admin/crudStudyYear.php" method="post">
        <button class="confirm" type="submit" name="deleteAllStudyYears">Da</button>
        </form>';
    }
    if ($_GET['url'] == 'crudCourse-admin')
    {
        echo '<p>Sunteti sigur ca doriti sa stergeti TOATE cursurile?</p>
        <form action="./controllers/admin/crudCourse.php" method="post">
        <button class="confirm" type="submit" name="deleteAllCourses" >Da</button>
        </form>';
    }
    if ($_GET['url'] == 'crudGroup-admin')
    {
        echo '<p>Sunteti sigur ca doriti sa stergeti TOATE grupele?</p>
        <form action="./controllers/admin/crudGroup.php" method="post">
        <button class="confirm" type="submit" name="deleteAllGroups">Da</button>
        </form>';
    }
    if ($_GET['url'] == 'crudProfessor-admin')
    {
        echo '<p>Sunteti sigur ca doriti sa stergeti TOTI profesorii?</p>
        <form action="./controllers/admin/crudProfessor.php" method="post">
        <button class="confirm" type="submit" name="deleteAllProfessors" >Da</button>
        </form>';
    }
    ?>
    <button class="cancel" onclick="hideElement('deleteAllForTablePopUp')">Nu</button>

</div>
<div class="importEntityPopUp" id="importPopUp">
    <img onclick="hideElement('importPopUp')" src="./components/admin/crudEntity/closePopUp.svg"/>
    <?php
    if ($_GET['url'] == 'crudQuestion-admin')
    {
        echo '<form action="./controllers/admin/crudQuestion.php" method="post" enctype="multipart/form-data">
        <div class="tooltipContainerColumns">
        <p>Alege fisierul intrebari.csv: <input type="file" name="file"/></p>
        <span class="tooltipText">col(intrebare)</span>
        </div>
        <button type="submit" name="importQuestion">Import</button>
        </form>';
    }
    if ($_GET['url'] == 'crudSpecialization-admin')
    {
        echo '<form action="./controllers/admin/crudSpecialization.php" method="post" enctype="multipart/form-data">
        <div class="tooltipContainerColumns">
        <p>Alege fisierul specializari.csv: <input type="file" name="file"/></p>
        <span class="tooltipText">col(specializare)</span>
        </div>

        <button type="submit" name="importSpecialization">Import</button>
        </form>';
    }
    if ($_GET['url'] == 'crudStudyYear-admin')
    {
        echo '
        <form action="./controllers/admin/crudStudyYear.php" method="post" enctype="multipart/form-data">
        <div class="tooltipContainerColumns">
        <p>Alege fisierul ani.csv: <input type="file" name="file"/></p>
        <span class="tooltipText">col(an_de_studiu)</span>
        </div>
        <button type="submit" name="importStudyYear">Import</button>
        </form>';
    }
    if ($_GET['url'] == 'crudCourse-admin')
    {
        echo '
        <form action="./controllers/admin/crudCourse.php" method="post" enctype="multipart/form-data">
        <div class="tooltipContainerColumns">
        <p>Alege fisierul cursuri.csv: <input type="file" name="file"/></p>
        <span class="tooltipText">col(id_an, semestru, curs_titlu)</span>
        </div>
        <button type="submit" name="importCourse">Import</button>
        </form>';
    }
    if ($_GET['url'] == 'crudStudent-admin')
    {
        echo '
        <form action="./controllers/admin/crudStudent.php" method="post" enctype="multipart/form-data">
        <div class="tooltipContainerColumns">
        <p>Alege fisierul studenti.csv: <input type="file" name="file"/>
        </p>
        <span class="tooltipText">col(id_grupa, numar_matricol, cnp)</span>
        </div>
        <button type="submit" name="importStudent">Import</button>
        </form>';
    }
    if ($_GET['url'] == 'crudGroup-admin')
    {
        echo '
        <form action="./controllers/admin/crudGroup.php" method="post" enctype="multipart/form-data">
        <div class="tooltipContainerColumns">
        <p>Alege fisierul grupe.csv: <input  type="file" name="file"/>
        </p>
        <span class="tooltipText">col(id_specializare, id_an, grupa)</span>
        </div>
        <button type="submit" name="importGroup">Import</button>
        </form>';
    }
    if ($_GET['url'] == 'crudProfessor-admin')
    {
        echo '
        <form action="./controllers/admin/crudProfessor.php" method="post" enctype="multipart/form-data">
        <div class="tooltipContainerColumns">
        <p>Alege fisierul profesori.csv: <input type="file" name="file"/>
        </p>
        <span class="tooltipText">col(prenume, nume, grad, email, parola, este_admin{1-da, 0-nu})</span>
        </div>
        <button type="submit" name="importProfessor">Import</button>
        </form>';
    }
    ?>
</div>
<div class="importEntityPopUp" id="importReferencesPopUp">
    <img onclick="hideElement('importReferencesPopUp')" src="./components/admin/crudEntity/closePopUp.svg"/>
    <?php
    if ($_GET['url'] == 'crudGroup-admin')
    {
        echo '
        <form action="./controllers/admin/crudGroup.php" method="post" enctype="multipart/form-data">
        <div class="tooltipContainerColumns">
        <p>Alege fisierul profesoriGrupe.csv: <input  type="file" name="file"/>
        <span class="tooltipText">col(id_profesor, id_grupa)</span>
        </div>
        <button type="submit" name="importProfessorGroups">Import</button>
        </form>';
    }
    if ($_GET['url'] == 'crudProfessor-admin')
    {
        echo '
        <form action="./controllers/admin/crudProfessor.php" method="post" enctype="multipart/form-data">
        <div class="tooltipContainerColumns">
        <p>Alege fisierul profesoriCursuri.csv: <input type="file" name="file"/></p>
        <span class="tooltipText">col(id_profesor, id_curs, este_titular{1-da, 0-nu})</span>
        </div>
        <button type="submit" name="importProfessorCourses">Import</button>
        </form>';
    }
    ?>
</div>
<script type="text/javascript" src="./components/admin/crudEntity/crudEntity.js"></script>
<?php
unset($_SESSION['dataCreateEntityIsValid']);
unset($_SESSION['sameDataEntity']);
unset($_SESSION['dataUpdadeEntityIsValid']);
unset($_SESSION['studentIdForEdit']);
unset($_SESSION['courseIdForEdit']);
unset($_SESSION['questionIdForEdit']);
unset($_SESSION['specializationIdForEdit']);
unset($_SESSION['studyYearIdForEdit']);
unset($_SESSION['groupIdForEdit']);
unset($_SESSION['professorIdForEdit']);
?>