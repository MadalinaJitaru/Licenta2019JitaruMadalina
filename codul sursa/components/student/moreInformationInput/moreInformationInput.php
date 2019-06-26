<style>
    <?php include 'moreInformationInput.css';?>
</style>

<?php 
require './controllers/student/moreInformationFetchData.php';
?>

<div class="containerMoreInformation">
    <form action="./controllers/student/moreInformation.php" method="post">
        <?php 
        if (isset($_SESSION['invalidData']) && $_SESSION['invalidData']) {
            echo '<p class="invalidDataMoreInformation">Campuri goale sau completate gresit!</p>';   
        }
        ?>
        <div class="faculty">
            <label>Facultatea</label>
            <div class="customSelect">
                <select id="facultySelect" name="facultySelect">
                    <option value="0">Selecteaza facultatea</option>
                    <?php
                    echo fetchFaculties();
                    ?>
                </select>
            </div>
        </div>
        <div class="specialization">
            <label>Specializarea</label>
            <div class="customSelect">
                <select id="specializationSelect" name="specializationSelect">
                    <option value="0">Selecteaza specializarea</option>
                </select>
            </div>
        </div>
        <div class="yearOfStudy">
            <label>Anul de studiu</label>
            <div class="customSelect">
                <select id="yearOfStudySelect" name="yearOfStudySelect">
                    <option value="0">Selecteaza anul de studiu</option>
                </select>
            </div>
        </div>
        <div class="group">
            <label>Grupa</label>
            <div class="customSelect">
                <select id="groupSelect" name="groupSelect">
                    <option value="0">Selecteaza grupa</option>
                </select>
            </div>
        </div>
        <div class="moreInformationButton">
            <button type="submit" name="next">Inainte</button>
        </div>
    </form>
</div>
<script src="./components/student/moreInformationInput/moreInformationInputSelectDropdown.js"></script>
<script src="./components/student/moreInformationInput/moreInformationInputFetchData.js"></script>
<?php
    unset($_SESSION['invalidData']);
?>