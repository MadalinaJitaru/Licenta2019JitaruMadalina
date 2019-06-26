<style>
    <?php include 'questionForFeedback.css';?>
</style>
<?php 
require './controllers/student/questionsFetchDataForStudent.php';
?>
<div class="questionForFeedbackContainer">
    <?php
    if (isset($_SESSION['radioButtonEmpty']) && $_SESSION['radioButtonEmpty']) {
        echo '<p class="invalidDataQuestionForFeedback">Intrebari fara raspuns!</p>';   
    } 
    echo questionsfetchDataStudent();
    ?>

</div>

<script type="text/javascript" src="./components/student/questionForFeedback/questionForFeedback.js"></script>
<?php
    unset($_SESSION['radioButtonEmpty']);
?>