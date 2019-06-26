<style>
    <?php include 'login.css';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    ?>
</style>
<div class="containerLoginStudent">
    <form action="./controllers/student/loginStudent.php" method="post">
        <?php 
        if (isset($_SESSION['dataIsValid']) && !$_SESSION['dataIsValid']) {
            echo '<p class="invalidDataStudent">Date introduse incorect!</p>';   
        }
        ?>
        <div class="registerNumber">
            <label for="numarMatricol">Numar Matricol</label>
            <?php 
            if(!isset($_SESSION['registerNumberIsValid']))
            {
                echo '<input type="text" name="numarMatricol" id="numarMatricol" placeholder="Introduceti numarul matricol">';
            }
            elseif (isset($_SESSION['registerNumberIsValid']) && !$_SESSION['registerNumberIsValid']) {
                echo '<input type="text" name="numarMatricol" id="numarMatricol" placeholder="Introduceti numarul matricol" class="borderErrorStudent" onclick="function f(e){e.classList.remove(\'borderErrorStudent\');}f(this);">';   
            }
            elseif (isset($_SESSION['registerNumberIsValid']) && $_SESSION['registerNumberIsValid']) {
                echo '<input type="text" name="numarMatricol" id="numarMatricol" placeholder="Introduceti numarul matricol" value="'.$_SESSION['registerNumber'].'">';
            }
            ?>
            <br>
        </div>
        <div class="identificationNumber">
            <label for="CNP">CNP</label>

            <?php 
            if(!isset($_SESSION['identificationNumberIsValid']))
            {
                echo '<input type="text" name="CNP" id="CNP" placeholder="Introduceti CNP-ul">';
            }
            elseif (isset($_SESSION['identificationNumberIsValid']) && !$_SESSION['identificationNumberIsValid']) {
                echo '<input type="text" name="CNP" id="CNP" placeholder="Introduceti CNP-ul" class="borderErrorStudent" onclick="function f(e){e.classList.remove(\'borderErrorStudent\');}f(this);">';   
            }
            elseif (isset($_SESSION['identificationNumberIsValid']) && $_SESSION['identificationNumberIsValid']) {
                echo '<input type="text" name="CNP" id="CNP" placeholder="Introduceti CNP-ul" value="'.$_SESSION['identificationNumber'].'">';
            }
            ?>
            <br>
        </div>

        <div class="loginButtonStudent">
            <button type="submit" name="loginStudent">Login</button>
        </div>
    </form>
</div>

<?php
    unset($_SESSION['registerNumber']);
    unset($_SESSION['identificationNumber']);
    unset($_SESSION['registerNumberIsValid']);
    unset($_SESSION['identificationNumberIsValid']);
    unset($_SESSION['dataIsValid']);
?>