<?php
/**
 * This script is to be used to receive a POST with the object information and then either updates, creates or deletes the task object
 */
require('Task.class.php');
// Assignment: Implement this script
if (isset($_POST) ) {
    $process = filter_input(INPUT_POST, 'process', FILTER_SANITIZE_STRING);
    $taskId = filter_input(INPUT_POST, 'taskID', FILTER_SANITIZE_NUMBER_INT);
    $taskClass = new Task($taskId);
    if ($process == 'save') {
        $taskClass->Save();
    }
    if ($process == 'delete') {
        $taskClass->Delete();
    }
}
echo json_encode(['message'=>'Please try again....','success'=>false]);
exit;
?>
