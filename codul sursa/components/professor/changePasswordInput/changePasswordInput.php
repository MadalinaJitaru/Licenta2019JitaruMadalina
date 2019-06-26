<style>
    <?php include 'changePasswordInput.css';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }?>
</style>
<div class="containerChangePassword">
    <form action="./controllers/professor/changePassword.php" method="post">
        <?php 
        if (isset($_SESSION['dataForChangePasswordIsValid']) && !$_SESSION['dataForChangePasswordIsValid']) {
            echo '<p class="invalidDataChangePasswordProfessor">Date introduse incorect</p>';
        }
        ?>
        <div class="emailChangePassword">
            <label for="emailSchimbareParola">Email</label>
            <?php 
            if (!isset($_SESSION['emailChangePasswordIsValid'])) {
                echo '<input type="text" name="emailSchimbareParola" id="emailSchimbareParola" placeholder="Introduceti adresa de email">';
            }
            elseif (isset($_SESSION['emailChangePasswordIsValid']) && !$_SESSION['emailChangePasswordIsValid']) {
                echo '<input type="text" name="emailSchimbareParola" id="emailSchimbareParola" placeholder="Introduceti adresa de email" class="borderErrorChangePasswordProfessor" onclick="function f(e){e.classList.remove(\'borderErrorChangePasswordProfessor\');}f(this);">';
            }
            elseif (isset($_SESSION['emailChangePasswordIsValid']) && $_SESSION['emailChangePasswordIsValid']) {
                echo '<input type="text" name="emailSchimbareParola" id="emailSchimbareParola" placeholder="Introduceti adresa de email" value="'.$_SESSION['emailChangePassword'].'">';
            }
            ?>
            <br>
        </div>
        <div class="oldPassword">
            <label for="parolaVeche">Parola veche</label>
            <?php 
            if (!isset($_SESSION['oldPasswordIsValid'])) {
                echo '<input type="password" name="parolaVeche" id="parolaVeche" placeholder="Introduceti parola veche">';
            }
            elseif (isset($_SESSION['oldPasswordIsValid']) && !$_SESSION['oldPasswordIsValid']) {
                echo '<input type="password" name="parolaVeche" id="parolaVeche" placeholder="Introduceti parola veche" class="borderErrorChangePasswordProfessor" onclick="function f(e){e.classList.remove(\'borderErrorChangePasswordProfessor\');}f(this);">';
            }
            elseif (isset($_SESSION['oldPasswordIsValid']) && $_SESSION['oldPasswordIsValid']) {
                echo '<input type="password" name="parolaVeche" id="parolaVeche" placeholder="Introduceti parola veche" value="'.$_SESSION['oldPassword'].'">';
            }
            ?>
            <br>
        </div>
        <div class="newPassword">
            <label for="parolaNoua">Parola noua</label>
            <?php 
            if (!isset($_SESSION['newPasswordIsValid'])) {
                echo '<input type="password" name="parolaNoua" id="parolaNoua" placeholder="Introduceti o parola noua">';
            }
            elseif (isset($_SESSION['newPasswordIsValid']) && !$_SESSION['newPasswordIsValid']) {
                echo '<input type="password" name="parolaNoua" id="parolaNoua" placeholder="Introduceti o parola noua" class="borderErrorChangePasswordProfessor" onclick="function f(e){e.classList.remove(\'borderErrorChangePasswordProfessor\');}f(this);">';
            }
            elseif (isset($_SESSION['newPasswordIsValid']) && $_SESSION['newPasswordIsValid']) {
                echo '<input type="password" name="parolaNoua" id="parolaNoua" placeholder="Introduceti o parola noua" value="'.$_SESSION['newPassword'].'">';
            }
            ?>
            <br>
        </div>
        <div class="confirmNewPassword">
            <label for="confirmareParolaNoua">Confirmare parola noua</label>
            <?php 
            if (!isset($_SESSION['confirmNewPasswordIsValid'])) {
                echo '<input type="password" name="confirmareParolaNoua" id="confirmareParolaNoua" placeholder="Introduceti parola noua">';
            }
            elseif (isset($_SESSION['confirmNewPasswordIsValid']) && !$_SESSION['confirmNewPasswordIsValid']) {
                echo '<input type="password" name="confirmareParolaNoua" id="confirmareParolaNoua" placeholder="Introduceti parola noua" class="borderErrorChangePasswordProfessor" onclick="function f(e){e.classList.remove(\'borderErrorChangePasswordProfessor\');}f(this);">';
            }
            elseif (isset($_SESSION['confirmNewPasswordIsValid']) && $_SESSION['confirmNewPasswordIsValid']) {
                echo '<input type="password" name="confirmareParolaNoua" id="confirmareParolaNoua" placeholder="Introduceti parola noua" value="'.$_SESSION['confirmNewPassword'].'">';
            }
            ?>
            <br>
        </div>
        <div class="changePasswordButton">
            <button type="submit" name="changePasswordProfessor">Schimbare parola</button>
        </div>
    </form>
</div>

<?php
unset($_SESSION['dataForChangePasswordIsValid']);
unset($_SESSION['emailChangePasswordIsValid']);
unset($_SESSION['oldPasswordIsValid']);
unset($_SESSION['newPasswordIsValid']);
unset($_SESSION['confirmNewPasswordIsValid']);
unset($_SESSION['emailChangePassword']);
unset($_SESSION['oldPassword']);
unset($_SESSION['newPassword']);
unset($_SESSION['confirmNewPassword']);
?>