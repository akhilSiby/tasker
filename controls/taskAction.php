<?php
session_start();
include_once "../class/TaskClass.php";

$task = new Task();

// Array for response 
$response = array(
    'status' => false,
    'message' => ''
);


/**
 * Create task group form submission action.
 */
if (isset($_POST['createTaskGroupFormSubmit'])) {
    extract($_POST);
    if (empty($taskGroupName)) {
        $response['message'] = "Please fill all fields";
    } else {
        $query = "INSERT INTO `task_group`(`tg_name`,`user_id`) VALUES (:taskGroupName,:userId)";
        $keyValue = array(':taskGroupName' => $taskGroupName, ':userId' => $_SESSION['userId']);
        $result = $task->queryExecute($query, $keyValue);
        if ($result) {
            $response['status'] = true;
            $response['message'] = "Task group created.";
        } else {
            $response['message'] = "Task group creation failed";
        }
    }
    echo json_encode($response);
    exit();
}

if (isset($_GET['taskGroupId'])) {
    extract($_GET);
    if (empty($taskGroupId)) {
        $response['message'] = "Please fill all fields";
    } else {
        $userId = $_SESSION['userId'];
        $query = "SELECT * FROM `task` WHERE `tg_id`='$taskGroupId' AND `t_status`='1' AND `user_id`='$userId' ORDER BY `t_iscompleted` ASC";
        $taskGroupRow = $task->getTaskData($query, []);

        $query = "SELECT * FROM `task` WHERE `tg_id`='$taskGroupId' AND `t_status`='1' AND `user_id`='$userId' AND `t_iscompleted`='1'";
        $result = $task->getTaskData($query, []);
        $response['done_count'] = count($result);
        $response['pending_count'] = count($taskGroupRow) - count($result);
        if (count($taskGroupRow) > 0) {
            $response['status'] = true;

            foreach ($taskGroupRow as $key => $eachTask) {

                $response['message'] .= "<div class='row mb-3 align-items-center'>";
                if ($eachTask['t_iscompleted'] == 1) {
                    $response['message'] .= "<span class='col-1'><input type='checkbox' checked onclick='taskStatusChange(" . $eachTask['t_id'] . ")' class='rounded' style='background: linear-gradient(200deg, rgb(" . (rand(0, 225)) . ", " . (rand(0, 225)) . ", " . (rand(0, 225)) . ") 0%, rgba(0, 0, 0, 1) 100%);width: 20px;height:20px;'>
                    </span><span class='col-9 text-decoration-line-through'>" . $eachTask['t_name'] . "</span>";
                } else {
                    $response['message'] .= "<span class='col-1'><input type='checkbox' onclick='taskStatusChange(" . $eachTask['t_id'] . ")' class='rounded' style='background: linear-gradient(200deg, rgb(" . (rand(0, 225)) . ", " . (rand(0, 225)) . ", " . (rand(0, 225)) . ") 0%, rgba(0, 0, 0, 1) 100%);width: 20px;height:20px;'>
                    </span><span class='col-9'>" . $eachTask['t_name'] . "</span>";
                }
                $response['message'] .= " 
                    <span class='col-2'>
                    <button onclick='taskStatusToggle(" . $eachTask['t_id'] . ")'>
<svg width='34px' height='34px' version='1.1' viewBox='0 0 752 752' xmlns='http://www.w3.org/2000/svg'>
 <path d='m376 188.54c-49.715 0-97.398 19.75-132.55 54.906-35.156 35.152-54.906 82.836-54.906 132.55 0 49.719 19.75 97.398 54.906 132.55 35.152 35.156 82.836 54.906 132.55 54.906 49.719 0 97.398-19.75 132.55-54.906s54.906-82.836 54.906-132.55c0-49.715-19.75-97.398-54.906-132.55-35.156-35.156-82.836-54.906-132.55-54.906zm55.746 226.93v-0.003906c2.2305 2.2227 3.4805 5.2422 3.4805 8.3867 0 3.1484-1.25 6.168-3.4805 8.3867-4.6484 4.5898-12.125 4.5898-16.773 0l-38.973-39.465-38.973 38.973h0.003906c-4.6523 4.5898-12.125 4.5898-16.773 0-2.2305-2.2227-3.4844-5.2383-3.4844-8.3867s1.2539-6.1641 3.4844-8.3867l38.973-38.973-38.973-38.973v0.003906c-4.6328-4.6328-4.6328-12.145 0-16.773 4.6289-4.6328 12.141-4.6328 16.773 0l38.969 38.973 39.465-38.973c4.6328-4.6328 12.145-4.6328 16.773 0 4.6328 4.6289 4.6328 12.141 0 16.773l-39.465 38.969z' fill='#f31f37'/>
</svg>

                </button>
                    </span>
                </div>";
            }
        } else {
            $response['message'] = "0 Task found";
        }
    }
    echo json_encode($response);
    exit();
}


/**
 * Create task form submission action.
 */
if (isset($_POST['createTaskFormSubmit'])) {
    extract($_POST);
    if (empty($taskName)) {
        $response['message'] = "Please fill all fields";
    } else {
        $query = "INSERT INTO `task`(`tg_id`,`user_id`, `t_name`) VALUES (:tg_id,:userId,:taskName)";
        $keyValue = array(':tg_id' => $tg_id, 'userId' => $_SESSION['userId'], ':taskName' => $taskName);
        $result = $task->queryExecute($query, $keyValue);
        if ($result) {
            $response['status'] = true;
            $response['message'] = "Task added.";
        } else {
            $response['message'] = "Task creation failed";
        }
    }
    echo json_encode($response);
    exit();
}


/**
 * Update task status.
 */
if (isset($_POST['taskStatusUpdate'])) {
    extract($_POST);
    if (empty($taskId)) {
        $response['message'] = "Please fill all fields";
    } else {
        $query = "UPDATE `task` SET `t_iscompleted`= CASE WHEN `t_iscompleted`=1 THEN 0 ELSE 1 END WHERE `t_id`='$taskId'";
        $result = $task->queryExecute($query, []);
        if ($result) {
            $response['status'] = true;
            $response['message'] = "Updated";
        } else {
            $response['message'] = "Updation failed";
        }
    }
    echo json_encode($response);
    exit();
}



/**
 * Remove task by updating t_status to 0.
 */
if (isset($_POST['taskStatusToggle'])) {
    extract($_POST);
    if (empty($taskId)) {
        $response['message'] = "Please fill all fields";
    } else {
        $query = "UPDATE `task` SET `t_status`= CASE WHEN `t_status`=1 THEN 0 ELSE 1 END WHERE `t_id`='$taskId'";
        $result = $task->queryExecute($query, []);
        if ($result) {
            $response['status'] = true;
            $response['message'] = "Task status changed";
        } else {
            $response['message'] = "Task status change failed";
        }
    }
    echo json_encode($response);
    exit();
}

/**
 * Remove task group.
 */
if (isset($_POST['taskGroupStatusToggle'])) {
    extract($_POST);
    if (empty($taskGroupId)) {
        $response['message'] = "Please fill all fields";
    } else {
        $query = "UPDATE `task_group` SET `tg_status`= CASE WHEN `tg_status`=1 THEN 0 ELSE 1 END WHERE `tg_id`='$taskGroupId'";
        $result = $task->queryExecute($query, []);
        if ($result) {
            $response['status'] = true;
            $response['message'] = "Task group status changed";
        } else {
            $response['message'] = "Task group status change failed";
        }
    }
    echo json_encode($response);
    exit();
}
