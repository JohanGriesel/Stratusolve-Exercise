<?php
/**
 * This script is to be used to receive a POST with the object information and then either updates, creates or deletes the task object
 */
require('task.class.php');

$action = $_POST['action'];
$task = new Task();
$taskToUpdate = $_POST['taskToUpdate'];
$task->TaskId = $taskToUpdate['TaskId'];
$task->TaskName = $taskToUpdate['TaskName'];
$task->TaskDescription = $taskToUpdate['TaskDescription'];
if ($action == 'update') {
    $task->Save();
} else if ($action == 'delete') {
    $task->Delete();
}

?>
