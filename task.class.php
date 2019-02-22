<?php
/**
 * This class handles the modification of a task object
 */
class Task {
    public $TaskId;
    public $TaskName;
    public $TaskDescription;
    protected $TaskDataSource;

    public function __construct($Id = null) {
        $this->TaskDataSource = file_get_contents('Task_Data.txt');
        if (strlen($this->TaskDataSource) > 0)
            $this->TaskDataSource = json_decode($this->TaskDataSource, true); // Should decode to an array of Task objects
        else
            $this->TaskDataSource = array(); // If it does not, then the data source is assumed to be empty and we create an empty array

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
                //unique id code taken from http://php.net/manual/en/function.uniqid.php (44 )by hackan at gmail dot com
        if (!empty($this->TaskDataSource)) {
           // Assignment: Code to get new unique ID
            // uniqid gives 13 chars, but you could adjust it to your needs.
            $length = 0;
            if (function_exists("random_bytes")) {
                $bytes = random_bytes(ceil($length / 2));
            } elseif (function_exists("openssl_random_pseudo_bytes")) {
                $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
            } else {
                throw new Exception("no cryptographically secure random function available");
            }
            return substr(bin2hex($bytes), 0, $length);
        }
        return -1; // Placeholder return for now
    }

    protected function LoadFromId($Id = null) {
        $Id = (int) $Id;
        if ($Id && !empty($this->TaskDataSource)) {
            foreach($this->TaskDataSource as $dataSource) {
                if ($Id == $dataSource['TaskId']) {
                    $this->TaskId = $dataSource['TaskId'];
                    $this->TaskName = $dataSource['TaskName'];
                    $this->TaskDescription = $dataSource['TaskDescription'];

                    return true;
                }
            }
        }

        return null;
    }

    public function Save() {
        //Assignment: Code to save task here
        $display_message = ['message' => 'Nothing was found', 'success' => false];
        if (isset($_POST)) {

            $this->TaskId = filter_input(INPUT_POST, 'task_id', FILTER_SANITIZE_NUMBER_INT);
            $this->TaskName = filter_input(INPUT_POST, 'task_name', FILTER_SANITIZE_STRING);
            $this->TaskDescription = filter_input(INPUT_POST, 'task_description', FILTER_SANITIZE_STRING);
            $updated = false;
            
            if ($this->TaskId > 0 && !empty($this->TaskDataSource)) {
                foreach ($this->TaskDataSource as $key=>$dataSource) {
                    if ($this->TaskId == $dataSource['TaskId']) {
                        $this->TaskDataSource[$key] = ['TaskId'=>$this->TaskId, 'TaskName' =>$this->TaskName, 'TaskDescription' => $this->TaskDescription];
                        $updated = true;
                        $display_message = ['message' => 'Task has been successfully updated', 'success' => true];
                        break;
                    }
                }
            }

            if (!$updated) {
                $this->TaskId = $this->getUniqueId();
                $this->TaskDataSource[] = ['TaskId'=>$this->TaskId, 'TaskName' =>$this->TaskName, 'TaskDescription' => $this->TaskDescription];
                $display_message = ['message' => 'Task has been successfully added', 'success' => true];
            }

            file_put_contents('Task_Data.txt', json_encode($this->TaskDataSource));
        }
        echo json_encode($display_message);
        exit();
    }

    public function Delete() {
        //Assignment: Code to delete task here
        $display_message = ['message' => 'Entered task not found', 'success' => false, 'task Id'=>$this->TaskId];
        if (!is_null($this->TaskId) && $this->TaskId > 0) {
            foreach ($this->TaskDataSource as $key=>$dataSource) {
                if ($this->TaskId == $dataSource['TaskId']) {
                    unset($this->TaskDataSource[$key]);
                    file_put_contents('Task_Data.txt', json_encode($this->TaskDataSource));
                    $display_message = ['message' => 'Tasks successfully deleted', 'success' => true];
                }
            }
        }

        echo json_encode($display_message);
        exit;

    }
}
?>