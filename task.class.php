<?php
/**
 * This class handles the modification of a task object
 */
class Task {
    public $TaskId;
    public $TaskName;
    public $TaskDescription;
    protected $TaskDataSource;
    protected $TaskDataSourcePath = 'Task_Data.txt';
    public function __construct($Id = null) {
        $this->TaskDataSource = $this->loadFromFile($this->TaskDataSourcePath);
        if (!$this->TaskDataSource)
            $this->TaskDataSource = array(); // If it does not, then the data source is assumed to be empty and we create an empty array
        if (!$this->LoadFromId($Id))
            $this->Create();
    }
    protected function Create() {
        // This function needs to generate a new unique ID for the task
        // Assignment: Generate unique id for the new task
        $this->TaskId = $this->getUniqueId();
        $this->TaskName = 'New Task';
        $this->TaskDescription = 'New Description';
    }
    protected function getUniqueId() {
        // Assignment: Code to get new unique ID
        $newID = count($this->TaskDataSource);
        if($newID == 0) {
            ++$newID;
            return $newID;
        } else {
            sort($this->TaskDataSource);
            if ($newID < (int)end($this->TaskDataSource)->TaskId) {
                $newID = (int)end($this->TaskDataSource)->TaskId;
                ++$newID;
            }
            return $newID;
        }
    }
    public function loadFromFile($Path) {
        $TempTaskDataSource = file_get_contents($Path);
        if (strlen($TempTaskDataSource) > 0)
            $TempTaskDataSource = json_decode($TempTaskDataSource); // Should decode to an array of Task objects
        else
            $TempTaskDataSource = array(); // If it does not, then the data source is assumed to be empty and we create an empty array
        return $TempTaskDataSource;

    }
    protected function LoadFromId($Id = null) {
        if ($Id) {
            $this->TaskDataSource = $this->loadFromFile($this->TaskDataSourcePath);
            foreach ($this->TaskDataSource as $task) {
                if($Id == $task->TaskId) {
                    return json_encode($task);
                }
            }
        } else
            return null;
    }
    public function Save() {
        if($this->TaskId == -1) {
            $this->TaskId = $this->getUniqueId();
            array_push($this->TaskDataSource, $this);
        } else {
            foreach ($this->TaskDataSource as $task) {
                if($this->TaskId == $task->TaskId) {
                    $task->TaskName = $this->TaskName;
                    $task->TaskDescription = $this->TaskDescription;
                }
            }
        }
        file_put_contents('Task_Data.txt', json_encode($this->TaskDataSource));
        echo $this->TaskId;
    }
    public function Delete() {
        $this->TaskDataSource = $this->loadFromFile($this->TaskDataSourcePath);
        //echo '$this->TaskId=' . $this->TaskId . ' | $this->TaskName=' . $this->TaskName . ' | $this->TaskDescription=' . $this->TaskDescription . '\n<br>/n';
        for ($pos = 0; $pos < count($this->TaskDataSource); $pos++) {
            if ($this->TaskDataSource[$pos]->TaskId == $this->TaskId) {
                unset($this->TaskDataSource[$pos]);
            }
        }
        file_put_contents($this->TaskDataSourcePath, json_encode($this->TaskDataSource));
        echo $this->TaskId;
    }
}
?>