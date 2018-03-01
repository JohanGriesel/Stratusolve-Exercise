<?php
/**
 * This class handles the modification of a task object
 */
class Task{
	public $TaskId;
	public $TaskName;
	public $TaskDescription;
	protected $TaskDataSource;
	public function __construct($Id = null){
		$this->TaskDataSource = file_get_contents('Task_Data.txt');
		if(strlen($this->TaskDataSource) > 0)
			$this->TaskDataSource = json_decode($this->TaskDataSource, true); // Should decode to an array of Task objects
		else
			$this->TaskDataSource = array(); // If it does not, then the data source is assumed to be empty and we create an empty array
		if(!$this->TaskDataSource)
			$this->TaskDataSource = array(); // If it does not, then the data source is assumed to be empty and we create an empty array
		if(!$this->LoadFromId($Id))
			$this->Create();
	}
	protected function Create(){
	// This function needs to generate a new unique ID for the task
	// Assignment: Generate unique id for the new task
		$this->TaskId = $this->getUniqueId();
		$this->TaskName = 'New Task';
		$this->TaskDescription = 'New Description';
	}
	protected function getUniqueId(){
	// Assignment: Code to get new unique ID
		if(!empty($this->TaskDataSource)){
			$max = 0;
			foreach($this->TaskDataSource as $dataSource){
				$max = max($max,$dataSource['TaskId']);
			}
			return ++$max;
		}
		return 1; // Placeholder return for now
	}
	protected function LoadFromId($Id = null){
		$Id =(int)$Id;
		if($Id && !empty($this->TaskDataSource)){
			foreach($this->TaskDataSource as $dataSource){
				if($Id == $dataSource['TaskId']){
					$this->TaskId = $dataSource['TaskId'];
					$this->TaskName = $dataSource['TaskName'];
					$this->TaskDescription = $dataSource['TaskDescription'];
					return true;
				}
			}
		}
		return null;
	}
	public function Save(){
		//Assignment: Code to save task here
		$message = ['message' => 'No data....', 'success' => false];
		if (isset($_POST)){
			$this->TaskId = filter_input(INPUT_POST, 'taskID', FILTER_SANITIZE_NUMBER_INT);
			$this->TaskName = filter_input(INPUT_POST, 'task_name', FILTER_SANITIZE_STRING);
			$this->TaskDescription = filter_input(INPUT_POST, 'task_description', FILTER_SANITIZE_STRING);
			$isUpdated = false;
			if($this->TaskId > 0 && !empty($this->TaskDataSource)){
				foreach($this->TaskDataSource as $key=>$dataSource){
					if($this->TaskId == $dataSource['TaskId']){
						$this->TaskDataSource[$key] = ['TaskId'=>$this->TaskId, 'TaskName' =>$this->TaskName, 'TaskDescription' => $this->TaskDescription];
						$isUpdated = true;
						$message = ['message' => 'Updated successfully....', 'success' => true];
						break;
					}
				}
			}
			if(!$isUpdated){
				$this->TaskId = $this->getUniqueId();
				$this->TaskDataSource[] = ['TaskId'=>$this->TaskId, 'TaskName' =>$this->TaskName, 'TaskDescription' => $this->TaskDescription];
				$message = ['message' => 'Added successfully....', 'success' => true];
			}
			file_put_contents('Task_Data.txt', json_encode($this->TaskDataSource));
		}
		echo json_encode($message);
		exit();
	}
	public function Delete(){
		//Assignment: Code to delete task here
		$message = ['message' => 'No data....', 'success' => false, 'task Id'=>$this->TaskId];
		if(!is_null($this->TaskId) && $this->TaskId > 0){
			foreach($this->TaskDataSource as $key=>$dataSource){
				if($this->TaskId == $dataSource['TaskId']){
					unset($this->TaskDataSource[$key]);
					file_put_contents('Task_Data.txt', json_encode($this->TaskDataSource));
					$message = ['message' => 'Deleted successfully....', 'success' => true];
				}
			}
		}
		echo json_encode($message);
		exit;
	}
}
?>
