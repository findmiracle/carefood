<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: *");
    header("Content-Type: application/json; charset=UTF-8");
    include_once("../classes/MySQL.php");

    $request_method = $_SERVER['REQUEST_METHOD'];
    $mySQL = new MySQL(true);

    // NEW USER PASSWORD
    if ($request_method === 'PUT' && isset($_GET['id'])) {
        $userId = $_GET['id'];
        $user = json_decode(file_get_contents('php://input'));
        $password = $user->password;
        $passwordCheck = $user->passwordCheck;

        if (strlen($password) > 0 && strlen($passwordCheck) > 0) {
            if ($password == $passwordCheck) {
                $passEncrypt = password_hash($user->password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET password = '$passEncrypt' WHERE id = '$userId'";
                $mySQL->Query($sql, false);
                $response['passwordChangeSuccess'] = TRUE;
                $response['success'] = "Your password has been changed successfully";
                echo json_encode($response);
            }
            else {
                $response['passwordChangeSuccess'] = FALSE;
                $response['error'] = "Passwords do not match!";
                echo json_encode($response);
            }
        }
        else {
            $response['passwordChangeSuccess'] = FALSE;
            $response['warning'] = "Password field cannot be empty!";
            echo json_encode($response);
        }
    }
        
?>