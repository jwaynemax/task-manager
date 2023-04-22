<?php
require_once('./util/secure_conn.php'); 
include './view/shared/header.php';
include './view/shared/nav.php';
?>
<main>
    <h2>Register</h2>
    <p>Provide username and password.</p>

    <form action="." method="post" id="login_form" class="aligned">

        <label>Username:</label>
        <input type="text" class="text" name="username">
        <p><?php echo $printUsernameError?></p>
        <br>

        <label>Password:</label>
        <input type="password" class="text" name="password">
        <p><?php echo $printPasswordError?></p>
        <br>

        <label>&nbsp;</label>
        <input type="submit" name="action" value="Register">
    </form>

</main>
</body>
</html>

