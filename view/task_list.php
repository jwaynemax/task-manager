<?php 
require_once('./util/secure_conn.php'); 
include './view/shared/header.php';
include './view/shared/nav.php';
?>
<main>
    <!-- part 1: the errors -->
    <?php if (count($errors) > 0) : ?>
    <h2>Errors:</h2>
    <ul>
        <?php foreach($errors as $error) : ?>
            <li><?php echo $error; ?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <!-- part 2: the tasks -->
    <h2>Tasks:</h2>
    <?php if (count($tasks) == 0) : ?>
        <p>There are no tasks in the task list.</p>
    <?php else: ?>
        <ul>
        <?php $count = 1; ?>
        <?php foreach( $tasks as $task ) : ?>
            <li><?php echo $count . '. ' . $task['task']; ?></li>
            <?php $count++; ?>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <br>

    <!-- part 3: the add form -->
    <h2>Add Task:</h2>
    <form action="." method="post" >
        <label>Task:</label>
        <input type="text" name="newtask" id="newtask"><br>
        <label>&nbsp;</label>
        <input type="submit" name="action" value="Add Task"><br>
    </form>
    <br>

    <!-- part 4: the delete form -->
    <?php if (count($tasks) > 0) : ?>
    <h2>Select Task:</h2>
    <form action="." method="post" >
        <label>Task:</label>
        <select name="taskid">
            <?php foreach( $tasks as $task ) : ?>
                <option value="<?php echo $task['taskID']; ?>">
                    <?php echo $task['task']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>
        <label>&nbsp;</label>
        <input type="submit" name="action" value="Delete Task">
    </form>
    <?php endif; ?>

</main>
</body>
</html>