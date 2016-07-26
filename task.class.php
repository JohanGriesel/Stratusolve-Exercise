<?php
/**
 * This class handles the modification of a task object
 */
class Task {
    const TaskDataFilename = 'task_data.txt';

    public $TaskId;
    public $TaskName;
    public $TaskDescription;

    private $IsNew = false;

    public function __construct($Id = null) {
        if ($Id) {
            // This is an existing task
            $this->LoadFromId($Id);
        } else {
            // This is a new task
            $this->Create();
        }
    }

    private static $tasks;

    static function LoadTasks() {
        if(!self::$tasks) {
            $taskData = file_get_contents(self::TaskDataFilename);
            self::$tasks = json_decode($taskData);
        }

        return self::$tasks;
    }

    protected function Create() {
        $newId = $this->GetMaxId() + 1;

        $this->TaskId = $newId;
        $this->TaskName = '';
        $this->TaskDescription = '';

        $this->IsNew = true;
    }

    protected function LoadFromId($Id = null) {
        if ($Id) {
            $tasks = self::LoadTasks();

            foreach ($tasks as $task) {
                if ($task->TaskId == $Id) {
                    $this->TaskId = $Id;
                    $this->TaskName = $task->TaskName;
                    $this->TaskDescription = $task->TaskDescription;
                }
            }

            return $this;
        } else {
            return null;
        }
    }

    private function GetMaxId() {
        $maxId = 0;
        $tasks = self::LoadTasks();

        foreach ($tasks as $task) {
            if ($task->TaskId > $maxId) {
                $maxId = $task->TaskId;
            }
        }

        return $maxId;
    }

    private function SaveTasks() {
        $taskFile = fopen(self::TaskDataFilename, 'w');
        fwrite($taskFile, json_encode(self::$tasks));
        fclose($taskFile);
    }

    public function Save() {
        if ($this->IsNew) {
            array_push(self::$tasks, $this);
        } else {
            foreach (self::$tasks as $task) {
                if ($this->TaskId == $task->TaskId) {
                    $task->TaskName = $this->TaskName;
                    $task->TaskDescription = $this->TaskDescription;
                }
            }
        }

        $this->SaveTasks();
    }

    public function Delete() {
        foreach (self::$tasks as $elementKey => $task) {
            foreach ($task as $valueKey => $value) {
                if ($valueKey == 'TaskId' && $value == $this->TaskId) {
                    unset(self::$tasks[$elementKey]);
                }
            }
        }

        // fix after unsetting array
        self::$tasks = array_values(self::$tasks);

        $this->SaveTasks();
    }
}
?>