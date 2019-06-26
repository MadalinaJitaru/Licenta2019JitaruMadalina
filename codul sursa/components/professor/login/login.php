<style>
    <?php include 'login.css';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    ?>
</style>
<div class="containerLoginProfessor">
    <form action="./controllers/professor/loginProfessor.php" method="post">
        <?php 
        if (isset($_SESSION['dataIsValid']) && !$_SESSION['dataIsValid']) {
            echo '<p class="invalidDataProfessor">Date introduse incorect!</p>';
        }
        ?>
        <div class="email">
            <label for="email">Email</label>
            <?php 
            if (!isset($_SESSION['emailIsValid'])) {
                echo '<input type="text" name="email" id="email" placeholder="Introduceti adresa de email">';
            }
            elseif (isset($_SESSION['emailIsValid']) && !$_SESSION['emailIsValid']) {
                echo '<input type="text" name="email" id="email" placeholder="Introduceti adresa de email" class="borderErrorProfessor" onclick="function f(e){e.classList.remove(\'borderErrorProfessor\');}f(this);">';
            }
            elseif (isset($_SESSION['emailIsValid']) && $_SESSION['emailIsValid']) {
                echo '<input type="text" name="email" id="email" placeholder="Introduceti adresa de email" value="'.$_SESSION['email'].'">';
            }
            ?>
            <br>
        </div>
        <div class="password">
            <label for="parola">Parola</label>
            <?php 
            if (!isset($_SESSION['passwordIsValid'])) {
                echo '<input type="password" name="parola" id="parola" placeholder="Introduceti parola">';
            }
            elseif (isset($_SESSION['passwordIsValid']) && !$_SESSION['passwordIsValid']) {
                echo '<input type="password" name="parola" id="parola" placeholder="Introduceti parola" class="borderErrorProfessor" onclick="function f(e){e.classList.remove(\'borderErrorProfessor\');}f(this);">';
            }
            elseif (isset($_SESSION['passwordIsValid']) && $_SESSION['passwordIsValid']) {
                echo '<input type="password" name="parola" id="parola" placeholder="Introduceti parola" value="'.$_SESSION['password'].'">';
            }
            ?>
            <br>
        </div>
        <div class="loginButtonProfessor">
            <button type="submit" name="loginProfessor">Login</button>
        </div>
    </form>
    <div class="changePasswordButtonProfessor">
        <a href="changePassword-professor">
            <button>Schimbati parola</button>
        </a>
    </div>
    <div class="forgotPassword">
        <p id="idSendMail" onclick="showElement('sendMailPopUp')">Ati uitat parola?</p>        
    </div>
</div>
<div class="forgotPasswordPopUp" id="sendMailPopUp">
    <img onclick="hideElement('sendMailPopUp')" src="./components/admin/crudEntity/closePopUp.svg"/>
    <p>Introduceti adresa de email pentru recuperarea parolei:</p>
    <?php 
    if (isset($_SESSION['wrongMail']) && $_SESSION['wrongMail']) {
        echo '<script>
        window.addEventListener("DOMContentLoaded", function(){
            document.getElementById("idSendMail").click();  
            }, false);
            </script>';
            echo '<p class="invalidDataSendMail">Campuri necompletate sau completate gresit!</p>';
        }
        ?>
        <form action="./controllers/professor/loginProfessor.php" method="post">
            <div class="forgotPasswordEmail">
                <label for="emailForReceive">Email</label>
                <input type="text" name="emailForReceive" id="emailForReceive" placeholder="Introduceti adresa de email">
            </div>
            <button class="confirm" type="submit" name="sendMail">Recuperare parola</button>
        </form>
    </div>
    <script type="text/javascript" src="./components/admin/crudEntity/crudEntity.js"></script>
    <?php
    unset($_SESSION['email']);
    unset($_SESSION['password']);
    unset($_SESSION['emailIsValid']);
    unset($_SESSION['passwordIsValid']);
    unset($_SESSION['dataIsValid']);
    unset($_SESSION['wrongMail']);
    ?>