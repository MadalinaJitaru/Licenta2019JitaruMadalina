<style>
    <?php include 'feedbackResults.css';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    ?>
</style>
<?php 
require './controllers/professor/feedbackResultsfetchDataForProfessor.php';
?>
<div class="exportResults">
    <?php 
    if (isset($_SESSION['existFeedback']) && $_SESSION['existFeedback'])
    {
        echo '
        <form action="./controllers/professor/feedbackResults.php" method="post">
        <button type="submit" name="exportResultsFeedback">
        <img src="./components/professor/feedbackResults/exportResults.svg"/>
        </button>
        <span class="tooltipText">Export rezultate</span>
        </form>';
    }
    ?>
</div>
<div class="feedbackResultsContainer">
    <?php 
    if (isset($_SESSION['existFeedback']) && !$_SESSION['existFeedback']) {
        echo '<p class="existFeedback">Feedback fara raspunsuri!</p>';   
    } 
    echo feedbackResultsfetchDataForProfessor();
    ?>
</div>

<script type="text/javascript" src="./components/professor/feedbackResults/feedbackResults.js"></script>