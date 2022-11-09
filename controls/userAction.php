<?php
session_start();
include_once "../class/UserClass.php";

$user = new User();

// Array for response 
$response = array(
    'status' => false,
    'message' => 'Message'
);

/**
 * Delete user from database by setting status value to 0.
 */
if (isset($_POST['userDeletionSubmit'])) {
    $response['message'] = "Inside";

    extract($_POST);
    if (empty($userId)) {
        $response['message'] = "Missing user Id";
    } else {
        $query = "UPDATE `user_data` SET `user_status`=0 WHERE `user_id`=:userId";
        $keyValue = array(':userId' => $userId);
        $result = $user->queryExecute($query, $keyValue);
        if ($result) {
            $response['status'] = true;
            $response['message'] = "User removed successfully.";
        } else {
            $response['message'] = "Removal failed.";
        }
    }
    echo json_encode($response);
    exit();
}
