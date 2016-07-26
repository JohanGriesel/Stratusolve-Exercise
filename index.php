<?php
/**
 * Created by PhpStorm.
 * User: johangriesel
 * Date: 13052016
 * Time: 08:48
 * @package    ${NAMESPACE}
 * @subpackage ${NAME}
 * @author     johangriesel <info@stratusolve.com>
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Basic Task Manager</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
</head>
<body>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                <form id="taskForm" action="update_task.php" method="post">
                    <div class="row">
                        <input id="InputTaskId" name="TaskId" type="hidden">
                        <div class="col-md-12" style="margin-bottom: 5px;">
                            <input id="InputTaskName" name="TaskName" type="text" placeholder="Task Name" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <textarea id="InputTaskDescription" name="TaskDescription" placeholder="Description" class="form-control"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="deleteTask" type="button" class="btn btn-danger">Delete Task</button>
                <button id="saveTask" type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">

        </div>
        <div class="col-md-6">
            <h2 class="page-header">Task List</h2>
            <!-- Button trigger modal -->
            <button id="newTask" type="button" class="btn btn-primary btn-lg" style="width:100%;margin-bottom: 5px;" data-toggle="modal" data-target="#myModal">
                Add Task
            </button>
            <div id="TaskList" class="list-group">
                <!-- Assignment: These are simply dummy tasks to show how it should look and work. You need to dynamically update this list with actual tasks -->
                <a id="1" href="#" class="list-group-item" data-toggle="modal" data-target="#myModal">
                    <h4 class="list-group-item-heading">Task Name</h4>
                    <p class="list-group-item-text">Task Description</p>
                </a>
                <a id="2" href="#" class="list-group-item" data-toggle="modal" data-target="#myModal">
                    <h4 class="list-group-item-heading">Task Name</h4>
                    <p class="list-group-item-text">Task Description</p>
                </a>
                <a id="3" href="#" class="list-group-item" data-toggle="modal" data-target="#myModal">
                    <h4 class="list-group-item-heading">Task Name</h4>
                    <p class="list-group-item-text">Task Description</p>
                </a>
                <a id="4" href="#" class="list-group-item" data-toggle="modal" data-target="#myModal">
                    <h4 class="list-group-item-heading">Task Name</h4>
                    <p class="list-group-item-text">Task Description</p>
                </a>
            </div>
        </div>
        <div class="col-md-3">

        </div>
    </div>
</div>
</body>
<script type="text/javascript" src="assets/js/jquery-1.12.3.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
<script type="text/javascript">
    $(function() {
        loadTasks();
    });

    function loadTasks() {
        $.ajax({
            url: 'get_tasks.php',
            method: 'GET',
            success: onGetTasksSuccess
        });
    }
    function onGetTasksSuccess(data) {
        var taskList = $('#TaskList');

        taskList.empty();

        $.each(data, function(index, value) {
            var taskItem = $('<a href="#" class="list-group-item" data-toggle="modal" data-target="#myModal"></a>');
            var taskItemName = $('<h4 class="list-group-item-heading"></h4>');
            var taskItemDescription = $('<p class="list-group-item-text"></p>');

            taskItem.attr("id", value.TaskId);

            taskItemName.text(value.TaskName);
            taskItemDescription.text(value.TaskDescription);

            taskItem.append(taskItemName);
            taskItem.append(taskItemDescription);

            taskList.append(taskItem);
        });
    }

    $('#myModal').on('show.bs.modal', function (event) {
        var triggerElement = $(event.relatedTarget); // Element that triggered the modal
        var modal = $(this);

        if (triggerElement.attr("id") == 'newTask') {
            modal.find('.modal-title').text('New Task');
            $('#deleteTask').hide();
        } else {
            modal.find('.modal-title').text('Task details');

            var taskId = triggerElement.attr("id");
            var taskName = triggerElement.find('.list-group-item-heading').text();
            var taskDescription = triggerElement.find('.list-group-item-text').text();

            modal.find('#InputTaskId').val(taskId);
            modal.find('#InputTaskName').val(taskName);
            modal.find('#InputTaskDescription').val(taskDescription);

            $('#deleteTask').show();
        }
    });

    $('#saveTask').click(function() {
        var data = {};
        $.each($('#taskForm').serializeArray(), function(index, keyValue) {
            data[keyValue.name] = keyValue.value;
        });

        data.TaskId = parseInt(data.TaskId);

        $.ajax({
            url: 'update_task.php',
            method: 'POST',
            data: JSON.stringify(data),
            success: function () {
                $('#myModal').modal('hide');
                loadTasks();
            }
        });
    });

    $('#deleteTask').click(function() {
        var data = {
            TaskId: $('#InputTaskId').val()
        };

        $.ajax({
            url: 'update_task.php',
            method: 'DELETE',
            data: JSON.stringify(data),
            success: function () {
                $('#myModal').modal('hide');
                loadTasks();
            }
        });
    });
</script>
</html>