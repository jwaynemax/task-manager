<nav>
	<a href=".?action=Home">Home</a>
	<a href=".?action=Show Tasks">Tasks</a>
	<?php if (!isset($_SESSION['is_valid_user'])): ?>
    	<a href=".?action=Show Login">Login</a>
        <a href=".?action=Show Register">Register</a>
    <?php else: ?>
    	<a href=".?action=Logout">Logout</a>
    <?php endif; ?>
</nav>

   