<?php
/**
 * This script is to be used to receive a POST with the object information and then either updates, creates or deletes the task object
 */
require('task.class.php');

$method = $_SERVER['REQUEST_METHOD'];

$jsonData = json_decode(file_get_contents('php://input'));
$task = new Task($jsonData->TaskId);

switch ($method) {
    case 'POST':
        $task->TaskName = $jsonData->TaskName;
        $task->TaskDescription = $jsonData->TaskDescription;

        $task->Save();
        break;

    case 'DELETE':
        $task->Delete();
        break;
}

header('Content-type: application/json');
echo json_encode($task);

?>