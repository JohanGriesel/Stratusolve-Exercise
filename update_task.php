<?php
/**
 * This script is to be used to receive a POST with the object information and then either updates, creates or deletes the task object
 */
require_once('task.class.php');
// Assignment: Implement this script

if (isset($_POST) ) {
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
    $taskId = filter_input(INPUT_POST, 'task_id', FILTER_SANITIZE_NUMBER_INT);

    $taskClass = new Task($taskId);

    if ($action == 'save') {
        $taskClass->Save();
    }

    if ($action == 'delete') {
        $taskClass->Delete();
    }
}

echo json_encode(['message' => 'You either tried accessing this directly or submitted an invalid action. Please trying saving the form again', 'success'=>false]);
exit;
?>