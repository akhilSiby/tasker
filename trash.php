<?php
session_start();
include_once "./class/UserClass.php";
include_once "./class/TaskClass.php";
// Redirect to index if not logged in
if (!isset($_SESSION['token'])) {
    header("location:./login.php");
}
$isTrashEmpty = true;
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
                <a class="nav-link d-flex align-items-center" aria-current="page" href="./index.php">
                    <svg viewBox="0 0 24 24">
                        <path fill="currentColor" d="M12,3L20,9V21H15V14H9V21H4V9L12,3Z" />
                    </svg>
                    Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center active" aria-current="page" href="">
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

        <div class="col-8">
            <?php
            $task = new Task();
            $userId = $_SESSION['userId'];
            $trashRow = $task->getTaskData("SELECT task_group.*, task.`t_id`,task.`t_name` FROM task_group INNER JOIN task ON task.`tg_id`=task_group.`tg_id` AND task.`t_status`='0' OR task_group.`tg_status`='0' AND task.`user_id`='$userId' AND task_group.`user_id`='$userId' ORDER BY task_group.`tg_id` ASC; ", []);
            ?>

            <div class="accordion" id="accordionPanelsStayOpenExample">
                <?php
                $trashArray = array();
                // Creating new assoc array for taskgroup and sub tasks.
                foreach ($trashRow as $key => $eachTrash) {
                    if (!isset($trashArray[$eachTrash['tg_id']])) {
                        $trashArray[$eachTrash['tg_id']] =  array('tg_name' => $eachTrash['tg_name'], 'tg_status' => $eachTrash['tg_status'], 'task' => array($eachTrash['t_id'] => $eachTrash['t_name']));
                    } else {
                        $trashArray[$eachTrash['tg_id']]['task'][$eachTrash['t_id']] = $eachTrash['t_name'];
                    }
                }
                // Printing the taskgroup and task as accordian
                foreach ($trashArray as $tg_id => $tg_name_and_tasks) {
                    if (reset($tg_name_and_tasks['task']) != false || $tg_name_and_tasks['tg_status'] == '0') {
                        $isTrashEmpty = false;
                        echo "<div class='accordion-item'>
                        <h2 class='accordion-header' id='panelsStayOpen-heading" . $tg_id . "'>
                        <div class='accordion-button acc-head' type='button' data-bs-toggle='collapse' data-bs-target='#panelsStayOpen-collapse" . $tg_id . "' aria-expanded='false' aria-controls='panelsStayOpen-collapse" . $tg_id . "'>";
                        echo $tg_name_and_tasks['tg_name'];
                        if ($tg_name_and_tasks['tg_status'] == '0') {
                            echo "<div class='col-10 d-flex justify-content-end'>
                            <div class='btn btn-outline-success' style='font-size: 12px;' onclick='taskGroupStatusToggle(" . $tg_id . ")'>Restore</div>
                            </div>";
                        }
                        echo "</div>
                        </h2>
                        <div id='panelsStayOpen-collapse" . $tg_id . "' class='accordion-collapse collapse' aria-labelledby='panelsStayOpen-heading" . $tg_id . "'>
                            <div class='accordion-body acc-body'>";
                        foreach ($tg_name_and_tasks['task'] as $t_id => $eachTask) {
                            if ($t_id != null) {
                                echo "<div class='row mb-3 align-items-center'>
                                <span class='col-9'>" . $eachTask . "</span>
                                <span class='col-2'>
                                    <button class='btn btn-outline-success' onclick='taskStatusToggle(" . $t_id . ",true)'>
                                        <svg style='width:24px;height:24px' viewBox='0 0 24 24'><path fill='currentColor' d='M13,3A9,9 0 0,0 4,12H1L4.89,15.89L4.96,16.03L9,12H6A7,7 0 0,1 13,5A7,7 0 0,1 20,12A7,7 0 0,1 13,19C11.07,19 9.32,18.21 8.06,16.94L6.64,18.36C8.27,20 10.5,21 13,21A9,9 0 0,0 22,12A9,9 0 0,0 13,3Z' /></svg>
                                    </button>
                                </span>
                                </div>";
                            }
                        }
                        echo "</div>
                        </div>
                        </div>";
                    }
                }
                ?>
            </div>
            <?php
            if ($isTrashEmpty) {
                echo "<div class='d-flex w-100 justify-content-center'>
                <svg width='172pt' height='172pt' version='1.1' viewBox='0 0 752 752' xmlns='http://www.w3.org/2000/svg'>
                    <g>
                    <path d='m357.53 406.31 5.6836-84.77c0.47266-6.6289 3.3164-13.262 8.0508-17.996s11.367-7.5781 17.996-8.0508l84.77-5.6836-84.77-5.6836c-6.6289-0.47266-13.262-3.3164-17.996-8.0508s-7.5781-11.367-8.0508-17.996l-5.6836-85.719-6.1562 85.242c-0.47266 6.6289-3.3164 13.262-8.0508 17.996-4.7344 4.7344-11.367 7.5781-17.996 8.0508l-84.77 5.6836 84.77 6.1562c6.6289 0.47266 13.262 3.3164 17.996 8.0508s7.5781 11.367 8.0508 17.996z'/>
                    <path d='m511.45 518.55 3.7891-56.355c0.47266-4.2617 2.3672-8.5234 5.2109-11.84 3.3164-3.3164 7.5781-5.2109 11.84-5.2109l56.828-4.2617-56.355-3.7891c-4.2617-0.47266-8.5234-2.3672-11.84-5.2109-3.3164-3.3164-5.2109-7.5781-5.2109-11.84l-3.7891-56.355-3.7891 56.355c-0.47266 4.2617-2.3672 8.5234-5.2109 11.84-3.3164 3.3164-7.5781 5.2109-11.84 5.2109l-56.355 3.7891 56.355 3.7891c4.2617 0.47266 8.5234 2.3672 11.84 5.2109 3.3164 3.3164 5.2109 7.5781 5.2109 11.84z'/>
                    <path d='m240.56 579.64 3.7891-56.355c0.47266-4.2617 2.3672-8.5234 5.2109-11.84 3.3164-3.3164 7.5781-5.2109 11.84-5.2109l56.355-3.7891-56.355-3.7891c-4.2617-0.47266-8.5234-2.3672-11.84-5.2109-3.3164-3.3164-5.2109-7.5781-5.2109-11.84l-3.7891-56.355-3.7891 56.355c-0.47266 4.2617-2.3672 8.5234-5.2109 11.84-3.3164 3.3164-7.5781 5.2109-11.84 5.2109l-56.828 3.3164 56.355 3.7891c4.2617 0.47266 8.5234 2.3672 11.84 5.2109 3.3164 3.3164 5.2109 7.5781 5.2109 11.84z'/>
                    </g>
                </svg>
                </div>";
            }
            ?>
        </div>
        <!-- End of mainBody div -->
        <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
        <script src="./asset/js/script.js"></script>
</body>

</html>