<?php
session_start();
include_once "../class/UserClass.php";

$user = new User();

// Array for response 
$response = array(
    'status' => false,
    'message' => ''
);

/**
 * User registration form submission action.
 */
if (isset($_POST['registerUserFormSubmit'])) {
    extract($_POST);
    if (empty($userName) || empty($userMobile) || empty($userPassword)) {
        $response['message'] = "Please fill all fields";
    } else {
        $query = "INSERT INTO `user_data`(`user_name`, `user_mobile`, `user_password`) VALUES (:userName,:userMobile,:userPassword)";
        $keyValue = array(':userName' => $userName, ':userMobile' => $userMobile, ':userPassword' => $userPassword);
        $result = $user->queryExecute($query, $keyValue);
        if ($result) {
            $response['status'] = true;
            $response['message'] = "Account created successfully.";
        } else {
            $response['message'] = "Registration failed.";
        }
    }
    echo json_encode($response);
    exit();
}



/**
 * User login form submission action.
 */
if (isset($_POST['loginUserFormSubmit'])) {
    extract($_POST);
    if (empty($userMobile) || empty($userPassword)) {
        $response['message'] = "Please fill all fields";
    } else {
        $query = "SELECT * FROM `user_data` WHERE `user_mobile`=:userMobile AND `user_password`=:userPassword AND `user_status`=1";
        $keyValue = array(':userMobile' => $userMobile, ':userPassword' => $userPassword);
        $userRow = $user->getUserData($query, $keyValue);

        if (count($userRow) > 0) {
            $_SESSION['token'] = session_id();
            $_SESSION['userId'] = $userRow[0]['user_id'];
            $_SESSION['userName'] = $userRow[0]['user_name'];
            $response['status'] = true;
            $response['message'] = "Logined successfully.";
        } else {
            $response['message'] = "Invalid credentials.";
        }
    }
    echo json_encode($response);
    exit();
}

/**
 * Logout action
 */
if (isset($_GET['logout'])) {
    unset($_SESSION['token']);
    unset($_SESSION['userId']);
    unset($_SESSION['userName']);
    header("location:../login.php");
    exit();
}
