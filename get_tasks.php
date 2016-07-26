<?php

require('task.class.php');

header('Content-type: application/json');
echo json_encode(Task::LoadTasks());

?>