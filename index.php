<?php
session_start();
include_once "./class/UserClass.php";
include_once "./class/TaskClass.php";
// Redirect to index if not logged in
if (!isset($_SESSION['token'])) {
    header("location:./login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUDPDO | Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="./asset/Style.css">

</head>

<body class="body-with-color">
    <div id="sidebar-menu" class="sidebar-menu">
        <div class="d-flex">
            <div class="circular--landscape mx-auto"> <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQUCxe7s30cFmp2jXTxlYKqUo79h2S0re4C0g&usqp=CAU" /> </div>
        </div>
        <ul class="navbar-nav mt-4">
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center active" aria-current="page" href="">
                    <svg viewBox="0 0 24 24">
                        <path fill="currentColor" d="M12,3L20,9V21H15V14H9V21H4V9L12,3Z" />
                    </svg>
                    Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center" aria-current="page" href="./trash.php">
                    <svg viewBox="0 0 24 24">
                        <path fill="currentColor" d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z" />
                    </svg>
                    Trash
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center" href="./controls/authAction.php?logout">
                    <svg viewBox="0 0 24 24">
                        <path fill="currentColor" d="M22 12L18 8V11H10V13H18V16M20 18A10 10 0 1 1 20 6H17.27A8 8 0 1 0 17.27 18Z" />
                    </svg>
                    Logout
                </a>
            </li>
        </ul>
    </div>

    <div id="mainBody">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <span style="font-size:20px;cursor:pointer" onclick="sideBarMenuControl()" id="sideVarControlBtn">&#9776;</span>
            <span class="h5 fw-bolder">Tasker</span>
            <span class="user-name"><?php echo $_SESSION['userName']; ?></span>
        </div>

        <div class="row mt-4">
            <div class="col-sm-12 col-md-5 row d-flex justify-content-center align-items-center">
                <div class="col-sm-12 col-md-9 p-4">
                    <h2 class="fw-bolder">Manage your tasks</h2>
                    <p>Create task group to group tasks for easy access</p>
                </div>
                <div class="col-sm-12 col-md-3 d-flex justify-content-center align-items-center">
                    <button class="add-category-btn m-2" data-bs-toggle="modal" data-bs-target="#createTaskGroup"> + </button>
                </div>
            </div>
            <div class="col-sm-12 col-md-7">
                <div class="h-scroll-wrap p-3 d-flex">
                    <?php
                    $task = new Task();
                    $userId = $_SESSION['userId'];
                    $taskGroupRow = $task->getTaskData("SELECT * FROM `task_group` WHERE `tg_status`='1' AND `user_id`='$userId'", []);
                    if (count($taskGroupRow) == 0) {
                        echo "
                        <svg width='152pt' height='152pt' version='1.1' viewBox='0 0 752 752' xmlns='http://www.w3.org/2000/svg'>
 <g>
  <path d='m357.53 406.31 5.6836-84.77c0.47266-6.6289 3.3164-13.262 8.0508-17.996s11.367-7.5781 17.996-8.0508l84.77-5.6836-84.77-5.6836c-6.6289-0.47266-13.262-3.3164-17.996-8.0508s-7.5781-11.367-8.0508-17.996l-5.6836-85.719-6.1562 85.242c-0.47266 6.6289-3.3164 13.262-8.0508 17.996-4.7344 4.7344-11.367 7.5781-17.996 8.0508l-84.77 5.6836 84.77 6.1562c6.6289 0.47266 13.262 3.3164 17.996 8.0508s7.5781 11.367 8.0508 17.996z'/>
  <path d='m511.45 518.55 3.7891-56.355c0.47266-4.2617 2.3672-8.5234 5.2109-11.84 3.3164-3.3164 7.5781-5.2109 11.84-5.2109l56.828-4.2617-56.355-3.7891c-4.2617-0.47266-8.5234-2.3672-11.84-5.2109-3.3164-3.3164-5.2109-7.5781-5.2109-11.84l-3.7891-56.355-3.7891 56.355c-0.47266 4.2617-2.3672 8.5234-5.2109 11.84-3.3164 3.3164-7.5781 5.2109-11.84 5.2109l-56.355 3.7891 56.355 3.7891c4.2617 0.47266 8.5234 2.3672 11.84 5.2109 3.3164 3.3164 5.2109 7.5781 5.2109 11.84z'/>
  <path d='m240.56 579.64 3.7891-56.355c0.47266-4.2617 2.3672-8.5234 5.2109-11.84 3.3164-3.3164 7.5781-5.2109 11.84-5.2109l56.355-3.7891-56.355-3.7891c-4.2617-0.47266-8.5234-2.3672-11.84-5.2109-3.3164-3.3164-5.2109-7.5781-5.2109-11.84l-3.7891-56.355-3.7891 56.355c-0.47266 4.2617-2.3672 8.5234-5.2109 11.84-3.3164 3.3164-7.5781 5.2109-11.84 5.2109l-56.828 3.3164 56.355 3.7891c4.2617 0.47266 8.5234 2.3672 11.84 5.2109 3.3164 3.3164 5.2109 7.5781 5.2109 11.84z'/>
 </g>
</svg>
";
                    }
                    foreach ($taskGroupRow as $key => $eachTaskGroup) {
                        if (($key + 1) < 10) {
                            $key = "0" . ($key + 1);
                        } else {
                            $key = ($key + 1);
                        }

                        echo "
                        <div class='cu-card p-3' id='" . $eachTaskGroup['tg_id'] . "' onclick='getTaskList(" . $eachTaskGroup['tg_id'] . ")' style='background: linear-gradient(200deg, rgb(" . (rand(0, 225)) . ", " . (rand(0, 225)) . ", " . (rand(0, 225)) . ") 0%, rgba(0, 0, 0, 1) 100%);'>
                        <p>" . $key . "</p>
                        <div>
                            <svg class='mb-2' style='width:45px;height:45px' viewBox='0 0 24 24'>
                                <path fill='currentColor' d='M22,2C22,2 14.36,1.63 8.34,9.88C3.72,16.21 2,22 2,22L3.94,21C5.38,18.5 6.13,17.47 7.54,16C10.07,16.74 12.71,16.65 15,14C13,13.44 11.4,13.57 9.04,13.81C11.69,12 13.5,11.6 16,12L17,10C15.2,9.66 14,9.63 12.22,10.04C14.19,8.65 15.56,7.87 18,8L19.21,6.07C17.65,5.96 16.71,6.13 14.92,6.57C16.53,5.11 18,4.45 20.14,4.32C20.14,4.32 21.19,2.43 22,2Z' />
                            </svg>
                            <h5 class='taskgroup-title'>" . $eachTaskGroup['tg_name'] . "</h5>
                            
                        </div>
                    </div>
                        ";
                    }
                    ?>

                </div>
            </div>
        </div>

        <!-- Modal for task group creation -->
        <div class="modal fade" id="createTaskGroup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="createTaskGroupLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <form class="col-12" id="createTaskGroupForm">
                            <div id="loginMessage"></div>
                            <div class="mb-3">
                                <label class="form-label">Task group name</label>
                                <input type="text" class="form-control cu-input" name="taskGroupName">
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <input type="submit" value="Create" class="cu-button" name="createTaskGroupFormSubmit">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Task view body -->
        <div class="row mt-4 d-flex justify-content-evenly d-none" id="taskListViewWrap">
            <div class="col-3 p-4 graph-card">
                <h6 class="fw-semibold">Progress</h6>
                <div class="d-flex justify-content-center">
                    <div class="outer-circle" id="outer-circle">
                        <div class="inner-circle" id="inner-circle">
                            0%
                        </div>
                    </div>
                </div>
                <div class="row m-2" id="graph-statistics">
                    <div class="col">10 Done</div>
                    <div class="col">21 Pending</div>
                </div>
                <div class="mt-3 mb-3 col-12 d-flex justify-content-center">
                    <div class="btn btn-outline-danger" style="font-size: 12px;" onclick="taskGroupStatusToggle()">Delete Group</div>
                </div>
            </div>
            <div class="col-8 p-4 task-card">
                <div class="d-flex justify-content-between mb-3">
                    <h6 class="fw-semibold">Task details</h6>
                    <button class="p-1 col-1 rounded" data-bs-toggle="modal" data-bs-target="#createTask"> + </button>
                </div>

                <div id="taskListViewBody">

                </div>
            </div>
        </div>
        <!-- Task view body end -->

        <!-- Modal for task creation -->
        <div class="modal fade" id="createTask" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="createTaskLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <form class="col-12" id="createTaskForm">
                            <div id="loginMessage"></div>
                            <div class="mb-3">
                                <label class="form-label">Task name</label>
                                <input type="text" class="form-control cu-input" name="taskName">
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <input type="submit" value="Add" class="cu-button" name="createTaskFormSubmit">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- End of mainBody div -->
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <script src="./asset/js/script.js"></script>
</body>

</html>