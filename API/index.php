<?php
/*db connection start*/
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");
$dbhost = '127.0.0.1';
$dbuser = 'root';
$db = 'slbfe_db';
$dbpass = 'password';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass);
mysqli_select_db($conn,$db)or die(mysqli_error());;
mysqli_set_charset($conn,'utf8');
$server_url = 'http://127.0.0.1';
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}
if(! $conn ) {
    die('Could not connect: ' . mysqli_error());
}
/*db connection end*/
$method = $_REQUEST['action'];
$message = '';
$response_array = array();
//citizen register
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $method == 'user_register'){
    $national_id = $_POST['national_id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $profession = $_POST['profession'];
    $email = $_POST['email'];
    $affiliation = $_POST['affiliation'];
    $password = md5($_POST['password']);
        if (!empty($national_id) && !empty($email)){
            $citizen_national_id_sql = "SELECT * FROM `citizens` WHERE `national_id` = '".$national_id."'";
            $citizen_national_id_result = mysqli_query($conn, $citizen_national_id_sql);
            $citizen_national_id_num = mysqli_num_rows($citizen_national_id_result);
            if($citizen_national_id_num >0 ){
                $response_array = array(
                    'status_code' => 409,
                    'status' => false,
                    'message' => 'National ID already exists',
                );
                echo  json_encode($response_array);
                exit;
            }

            $email_sql = "SELECT * FROM `citizens` WHERE `email` = '".$email."'";
            $email_result = mysqli_query($conn,$email_sql);
            $email_num = mysqli_num_rows($email_result);

            if($email_num >0 ){
                $response_array = array(
                    'status_code' => 409,
                    'status' => false,
                    'message' => 'Email address already exists',
                );
                echo  json_encode($response_array);
                exit;
            }

            if (!empty($national_id) && !empty($name) && !empty($age) && !empty($address) && !empty($latitude) && !empty($longitude) && !empty($profession) && !empty($email) && !empty($affiliation) && !empty($password)) {
                $insert_citizen_sql = "INSERT INTO `citizens`(`national_id` ,`name` ,`age` ,`address` ,`latitude` ,`longitude` ,`profession` ,`email` ,`affiliation` ,`password`) VALUES ('".$national_id."','".$name."','".$age."','".$address."','".$latitude."','".$longitude."','".$profession."','".$email."','".$affiliation."','".$password."')";
                mysqli_query($conn,$insert_citizen_sql);

                $response_array = array(
                    'status_code' => 201,
                    'status' => true,
                    'message' => 'user registered success',
                );
            }

    }else{
        $response_array = array(
            'status_code' => 400,
            'status' => false,
            'message' => 'required field can not find',
        );
    }
    echo json_encode($response_array);
    exit;
}

//staff register
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $method == 'staff_register'){
    $national_id = $_POST['national_id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $profession = $_POST['profession'];
    $email = $_POST['email'];
    $affiliation = $_POST['affiliation'];
    $password = md5($_POST['password']);
    if (!empty($national_id) && !empty($email)){
        $staff_national_id_sql = "SELECT * FROM `staff` WHERE `national_id` = '".$national_id."'";
        $staff_national_id_result = mysqli_query($conn, $staff_national_id_sql);
        $staff_national_id_num = mysqli_num_rows($staff_national_id_result);
        if($staff_national_id_num >0 ){
            $response_array = array(
                'status_code' => 409,
                'status' => false,
                'message' => 'national id already exists',
            );
            echo  json_encode($response_array);
            exit;
        }

        $email_sql = "SELECT * FROM `staff` WHERE `email` = '".$email."'";
        $email_result = mysqli_query($conn,$email_sql);
        $email_num = mysqli_num_rows($email_result);

        if($email_num >0 ){
            $response_array = array(
                'status_code' => 409,
                'status' => false,
                'message' => 'email address already exists',
            );
            echo  json_encode($response_array);
            exit;
        }

        if (!empty($national_id) && !empty($name) && !empty($age) && !empty($address) && !empty($profession) && !empty($email) && !empty($affiliation) && !empty($password)) {
            $insert_citizen_sql = "INSERT INTO `staff`(`national_id` ,`name` ,`age` ,`address` ,`profession` ,`email` ,`affiliation` ,`password`) VALUES ('".$national_id."','".$name."','".$age."','".$address."','".$profession."','".$email."','".$affiliation."','".$password."')";
            mysqli_query($conn,$insert_citizen_sql);

            $response_array = array(
                'status_code' => 201,
                'status' => true,
                'message' => 'staff officer registered success',
            );
        }

    }else{
        $response_array = array(
            'status_code' => 400,
            'status' => false,
            'message' => 'required field can not find',
        );
    }
    echo json_encode($response_array);
    exit;
}

// citizen login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $method == 'user_login') {
    $national_id = $_POST['national_id'];
    $password = md5($_POST['password']);
    if (!empty($national_id) && !empty($password)){
        $sql = "SELECT * FROM `citizens` WHERE `national_id` = '".$national_id."' and `password` = '".$password."'";
        $result = mysqli_query($conn,$sql);
        $num_row = mysqli_num_rows($result);

        if($num_row>0){
            $fetch = mysqli_fetch_array($result);

            //update device token
            $token = openssl_random_pseudo_bytes(16);
            if ($fetch['device_token'] == null){
                $device_token = bin2hex($token);
            }else{
                $exists_token = explode('|', $fetch['device_token']);
                array_push($exists_token, bin2hex($token));
                $device_token = implode('|', $exists_token);
            }
            $update_sql = "UPDATE `citizens` SET `device_token`='".$device_token."' WHERE `national_id` = '".$fetch['national_id']."' ";
            mysqli_query($conn,$update_sql);
            $arr = array(
                'national_id' =>$fetch['national_id'],
                'name' =>$fetch['name'],
                'age' =>$fetch['age'],
                'address' =>$fetch['address'],
                'latitude' =>$fetch['latitude'],
                'longitude' =>$fetch['longitude'],
                'profession' =>$fetch['profession'],
                'email' =>$fetch['email'],
                'affiliation' =>$fetch['affiliation'],
                );
            $response_array = array(
                'status_code' => 201,
                'status' => true,
                'message' => 'user login success',
                'data' => $arr,
                'device_token' => bin2hex($token),
            );

        }

    }else{
        $response_array = array(
            'status_code' => 400,
            'status' => false,
            'message' => 'national id and or password required',
        );
    }
    echo json_encode($response_array);
    exit;

}

// staff login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $method == 'staff_login') {
    $national_id = $_POST['national_id'];
    $password = md5($_POST['password']);
    if (!empty($national_id) && !empty($password)){
        $sql = "SELECT * FROM `staff` WHERE `national_id` = '".$national_id."' and `password` = '".$password."'";
        $result = mysqli_query($conn,$sql);
        $num_row = mysqli_num_rows($result);

        if($num_row>0){
            $fetch = mysqli_fetch_array($result);

            //update device token
            $token = openssl_random_pseudo_bytes(16);
            if ($fetch['device_token'] == null){
                $device_token = bin2hex($token);
            }else{
                $exists_token = explode('|', $fetch['device_token']);
                array_push($exists_token, bin2hex($token));
                $device_token = implode('|', $exists_token);
            }
            $update_sql = "UPDATE `staff` SET `device_token`='".$device_token."' WHERE `national_id` = '".$fetch['national_id']."' ";
            mysqli_query($conn,$update_sql);
            $arr = array(
                'national_id' =>$fetch['national_id'],
                'name' =>$fetch['name'],
                'age' =>$fetch['age'],
                'address' =>$fetch['address'],
                'profession' =>$fetch['profession'],
                'email' =>$fetch['email'],
                'affiliation' =>$fetch['affiliation'],
            );
            $response_array = array(
                'status_code' => 201,
                'status' => true,
                'message' => 'staff officer login success',
                'data' => $arr,
                'device_token' => bin2hex($token),
            );

        }

    }else{
        $response_array = array(
            'status_code' => 400,
            'status' => false,
            'message' => 'national id and or password required',
        );
    }
    echo json_encode($response_array);
    exit;

}

// get citizen details
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $method == 'get_user_details') {
    /******************************/
    $national_id = $_GET['national_id'];
    $auth_national_id = $_GET['auth_national_id'];
    $device_token = $_GET['device_token'];
    if (!empty($auth_national_id)){
        if (!empty($device_token)){
            $sql = "SELECT `national_id`, `device_token` FROM `staff` WHERE `national_id` = '".$national_id."'";
            $result = mysqli_query($conn,$sql);
            $num_row = mysqli_num_rows($result);
            if($num_row>0) {
                $fetch = mysqli_fetch_array($result);
                if ($fetch['device_token'] != null){
                    $device_tokens = explode('|', $fetch['device_token']);
                    if (in_array($fetch['device_token'], $device_tokens)){
                        if (!empty($national_id)){
                            $sql = "SELECT `national_id`, `name`, `age`, `address`, `latitude`, `longitude`, `profession`, `email`, `affiliation` FROM `citizens` WHERE `national_id` = '".$national_id."'";
                            $result = mysqli_query($conn,$sql);
                            $num_row = mysqli_num_rows($result);

                            if($num_row>0){
                                $fetch = mysqli_fetch_array($result);
                                $arr = array(
                                    'national_id' =>$fetch['national_id'],
                                    'name' =>$fetch['name'],
                                    'age' =>$fetch['age'],
                                    'address' =>$fetch['address'],
                                    'latitude' =>$fetch['latitude'],
                                    'longitude' =>$fetch['longitude'],
                                    'profession' =>$fetch['profession'],
                                    'email' =>$fetch['email'],
                                    'affiliation' =>$fetch['affiliation'],
                                );
                                $response_array = array(
                                    'status_code' => 201,
                                    'status' => true,
                                    'message' => 'Citizen details get success',
                                    'data' => $arr,
                                );
                            }else{
                                $response_array = array(
                                    'status_code' => 409,
                                    'status' => false,
                                    'message' => 'no any citizen with this national id',
                                );
                            }

                        }else{
                            $response_array = array(
                                'status_code' => 400,
                                'status' => false,
                                'message' => 'national id is required',
                            );
                        }
                    }else{
                        $response_array = array(
                            'status_code' => 403,
                            'status' => false,
                            'message' => 'you have not permission',
                        );
                    }
                }else{
                    $response_array = array(
                        'status_code' => 409,
                        'status' => false,
                        'message' => 'staff officer can not find',
                    );
                }
            }else{
                $response_array = array(
                    'status_code' => 409,
                    'status' => false,
                    'message' => 'staff officer can not find',
                );
            }
        }else{
            $response_array = array(
                'status_code' => 400,
                'status' => false,
                'message' => 'authenticated device token required',
            );
        }
    }else{
        $response_array = array(
            'status_code' => 400,
            'status' => false,
            'message' => 'authenticated staff officer national id is required',
        );
    }
    echo json_encode($response_array);
    exit;

    /**************************/
}

//verify citizen details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $method == 'verify_user_details') {
    $national_id = $_POST['national_id'];
    $verification = $_POST['verification'];
    $auth_national_id = $_POST['auth_national_id'];
    $device_token = $_POST['device_token'];
    if (!empty($auth_national_id)){
        if (!empty($device_token)){
            $sql = "SELECT `national_id`, `device_token` FROM `staff` WHERE `national_id` = '".$national_id."'";
            $result = mysqli_query($conn,$sql);
            $num_row = mysqli_num_rows($result);
            if($num_row>0) {
                $fetch = mysqli_fetch_array($result);
                if ($fetch['device_token'] != null){
                    $device_tokens = explode('|', $fetch['device_token']);
                    if (in_array($fetch['device_token'], $device_tokens)){
                        if (!empty($national_id)){
                            if (!empty($verification)){
                                if ($verification == 'verified' || $verification == 'unverified'){
                                    $sql = "SELECT * FROM `citizens` WHERE `national_id` = '".$national_id."'";
                                    $result = mysqli_query($conn,$sql);
                                    $num_row = mysqli_num_rows($result);
                                    if($num_row>0) {
                                        $fetch = mysqli_fetch_array($result);
                                        $update_sql = "UPDATE `citizens` SET `information_verification`='" . $verification . "' WHERE `national_id` = '" . $national_id . "' ";
                                        mysqli_query($conn, $update_sql);
                                        $response_array = array(
                                            'status_code' => 201,
                                            'status' => true,
                                            'message' => 'update user and user details: '. $verification,
                                        );
                                    }else{
                                        $response_array = array(
                                            'status_code' => 409,
                                            'status' => false,
                                            'message' => 'can not find user with this national id number',
                                        );
                                    }
                                }else{
                                    $response_array = array(
                                        'status_code' => 409,
                                        'status' => false,
                                        'message' => 'verification status must be a verified or unverified',
                                    );
                                }

                            }else{
                                $response_array = array(
                                    'status_code' => 400,
                                    'status' => false,
                                    'message' => 'verification status is required',
                                );
                            }
                        }else{
                            $response_array = array(
                                'status_code' => 400,
                                'status' => false,
                                'message' => 'national id is required',
                            );
                        }
                    }else{
                        $response_array = array(
                            'status_code' => 403,
                            'status' => false,
                            'message' => 'you have not permission',
                        );
                    }
                }else{
                    $response_array = array(
                        'status_code' => 409,
                        'status' => false,
                        'message' => 'staff officer can not find',
                    );
                }
            }else{
                $response_array = array(
                    'status_code' => 409,
                    'status' => false,
                    'message' => 'staff officer can not find',
                );
            }
        }else{
            $response_array = array(
                'status_code' => 400,
                'status' => false,
                'message' => 'authenticated device token required',
            );
        }
    }else{
        $response_array = array(
            'status_code' => 400,
            'status' => false,
            'message' => 'authenticated staff officer national id is required',
        );
    }
    echo json_encode($response_array);
    exit;
}

//delete citizen
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $method == 'delete_user') {
    $national_id = $_POST['national_id'];
    $auth_national_id = $_POST['auth_national_id'];
    $device_token = $_POST['device_token'];
    if (!empty($auth_national_id)){
        if (!empty($device_token)){
            $sql = "SELECT `national_id`, `device_token` FROM `staff` WHERE `national_id` = '".$national_id."'";
            $result = mysqli_query($conn,$sql);
            $num_row = mysqli_num_rows($result);
            if($num_row>0) {
                $fetch = mysqli_fetch_array($result);
                if ($fetch['device_token'] != null){
                    $device_tokens = explode('|', $fetch['device_token']);
                    if (in_array($fetch['device_token'], $device_tokens)){
                        if (!empty($national_id)){
                                    $sql = "SELECT * FROM `citizens` WHERE `national_id` = '".$national_id."'";
                                    $result = mysqli_query($conn,$sql);
                                    $num_row = mysqli_num_rows($result);
                                    if($num_row>0) {
                                        $fetch = mysqli_fetch_array($result);
                                        $delete_sql = "DELETE FROM `citizens` WHERE `national_id` = '" . $fetch['national_id'] . "'";
                                        mysqli_query($conn, $delete_sql);
                                        $response_array = array(
                                            'status_code' => 201,
                                            'status' => true,
                                            'message' => 'user removed success',
                                        );
                                    }else{
                                        $response_array = array(
                                            'status_code' => 409,
                                            'status' => false,
                                            'message' => 'can not find user with this national id number',
                                        );
                                    }
                        }else{
                            $response_array = array(
                                'status_code' => 400,
                                'status' => false,
                                'message' => 'national id is required',
                            );
                        }
                    }else{
                        $response_array = array(
                            'status_code' => 403,
                            'status' => false,
                            'message' => 'you have not permission',
                        );
                    }
                }else{
                    $response_array = array(
                        'status_code' => 409,
                        'status' => false,
                        'message' => 'staff officer can not find',
                    );
                }
            }else{
                $response_array = array(
                    'status_code' => 409,
                    'status' => false,
                    'message' => 'staff officer can not find',
                );
            }
        }else{
            $response_array = array(
                'status_code' => 400,
                'status' => false,
                'message' => 'authenticated device token required',
            );
        }
    }else{
        $response_array = array(
            'status_code' => 400,
            'status' => false,
            'message' => 'authenticated staff officer national id is required',
        );
    }
    echo json_encode($response_array);
    exit;
}

//citizen contact details
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $method == 'get_user_contact_details') {
    $national_id = $_GET['national_id'];
    $auth_national_id = $_GET['auth_national_id'];
    $device_token = $_GET['device_token'];
    if (!empty($auth_national_id)){
        if (!empty($device_token)){
            $sql = "SELECT `national_id`, `device_token` FROM `staff` WHERE `national_id` = '".$national_id."'";
            $result = mysqli_query($conn,$sql);
            $num_row = mysqli_num_rows($result);
            if($num_row>0) {
                $fetch = mysqli_fetch_array($result);
                if ($fetch['device_token'] != null){
                    $device_tokens = explode('|', $fetch['device_token']);
                    if (in_array($fetch['device_token'], $device_tokens)){
                        if (!empty($national_id)){
                            $sql = "SELECT `name`, `address`, `email` FROM `citizens` WHERE `national_id` = '".$national_id."'";
                            $result = mysqli_query($conn,$sql);
                            $num_row = mysqli_num_rows($result);

                            if($num_row>0){
                                $fetch = mysqli_fetch_array($result);
                                $arr = array(
                                    'name' =>$fetch['name'],
                                    'address' =>$fetch['address'],
                                    'email' =>$fetch['email'],
                                );
                                $response_array = array(
                                    'status_code' => 201,
                                    'status' => true,
                                    'message' => 'user details get success',
                                    'data' => $arr,
                                );
                            }else{
                                $response_array = array(
                                    'status_code' => 409,
                                    'status' => false,
                                    'message' => 'no any user with this national id',
                                );
                            }
                        }else{
                            $response_array = array(
                                'status_code' => 400,
                                'status' => false,
                                'message' => 'national id is required',
                            );
                        }
                    }else{
                        $response_array = array(
                            'status_code' => 403,
                            'status' => false,
                            'message' => 'you have not permission',
                        );
                    }
                }else{
                    $response_array = array(
                        'status_code' => 409,
                        'status' => false,
                        'message' => 'staff officer can not find',
                    );
                }
            }else{
                $response_array = array(
                    'status_code' => 409,
                    'status' => false,
                    'message' => 'staff officer can not find',
                );
            }
        }else{
            $response_array = array(
                'status_code' => 400,
                'status' => false,
                'message' => 'authenticated device token required',
            );
        }
    }else{
        $response_array = array(
            'status_code' => 400,
            'status' => false,
            'message' => 'authenticated staff officer national id is required',
        );
    }
    echo json_encode($response_array);
    exit;
}

//update citizen details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $method == 'update_user_details') {
    $national_id = $_POST['national_id'];
    $device_token = $_POST['device_token'];
    $name = isset($_POST['name'])?$_POST['name']:'';
    $age = isset($_POST['age'])?$_POST['age']:'';
    $address = isset($_POST['address'])?$_POST['address']:'';
    $latitude = isset($_POST['latitude'])?$_POST['latitude']:'';
    $longitude = isset($_POST['longitude'])?$_POST['longitude']:'';
    $profession = isset($_POST['profession'])?$_POST['profession']:'';
    $email = isset($_POST['email'])?$_POST['email']:'';
    $affiliation = isset($_POST['affiliation'])?$_POST['affiliation']:'';
    $qualification = isset($_POST['qualification'])?$_POST['qualification']:'';
    $password = isset($_POST['password'])?md5($_POST['password']):'';
    if (!empty($national_id)){
        if (!empty($device_token)){
            $sql = "SELECT `national_id`, `device_token` FROM `citizens` WHERE `national_id` = '".$national_id."'";
            $result = mysqli_query($conn,$sql);
            $num_row = mysqli_num_rows($result);
            if($num_row>0) {
                $fetch = mysqli_fetch_array($result);
                if ($fetch['device_token'] != null){
                    $device_tokens = explode('|', $fetch['device_token']);
                    if (in_array($fetch['device_token'], $device_tokens)){

                                    $sql = "SELECT * FROM `citizens` WHERE `national_id` = '".$national_id."'";
                                    $result = mysqli_query($conn,$sql);
                                    $num_row = mysqli_num_rows($result);
                                    if($num_row>0) {
                                        $fetch = mysqli_fetch_array($result);
                                        if (!empty($name)){
                                            $update_sql = "UPDATE `citizens` SET `name`='" . $name . "' WHERE `national_id` = '" . $fetch['national_id'] . "' ";
                                            mysqli_query($conn, $update_sql);
                                        }
                                        if (!empty($age)){
                                            $update_sql = "UPDATE `citizens` SET `age`='" . $age . "' WHERE `national_id` = '" . $fetch['national_id'] . "' ";
                                            mysqli_query($conn, $update_sql);
                                        }
                                        if (!empty($address)){
                                            $update_sql = "UPDATE `citizens` SET `address`='" . $address . "' WHERE `national_id` = '" . $fetch['national_id'] . "' ";
                                            mysqli_query($conn, $update_sql);
                                        }
                                        if (!empty($latitude)){
                                            $update_sql = "UPDATE `citizens` SET `latitude`='" . $latitude . "' WHERE `national_id` = '" . $fetch['national_id'] . "' ";
                                            mysqli_query($conn, $update_sql);
                                        }
                                        if (!empty($longitude)){
                                            $update_sql = "UPDATE `citizens` SET `longitude`='" . $longitude . "' WHERE `national_id` = '" . $fetch['national_id'] . "' ";
                                            mysqli_query($conn, $update_sql);
                                        }
                                        if (!empty($profession)){
                                            $update_sql = "UPDATE `citizens` SET `profession`='" . $profession . "' WHERE `national_id` = '" . $fetch['national_id'] . "' ";
                                            mysqli_query($conn, $update_sql);
                                        }
                                        if (!empty($email)){
                                            $update_sql = "UPDATE `citizens` SET `email`='" . $email . "' WHERE `national_id` = '" . $fetch['national_id'] . "' ";
                                            mysqli_query($conn, $update_sql);
                                        }
                                        if (!empty($affiliation)){
                                            $update_sql = "UPDATE `citizens` SET `affiliation`='" . $affiliation . "' WHERE `national_id` = '" . $fetch['national_id'] . "' ";
                                            mysqli_query($conn, $update_sql);
                                        }
                                        if (!empty($password)){
                                            $update_sql = "UPDATE `citizens` SET `password`='" . $password . "' WHERE `national_id` = '" . $fetch['national_id'] . "' ";
                                            mysqli_query($conn, $update_sql);
                                        }
                                        if (!empty($qualification)){
                                            $check_sql = "SELECT `qualifications` FROM `citizens` WHERE `national_id` = '".$fetch['national_id']."'";
                                            $result = mysqli_query($conn,$check_sql);
                                            $num_row = mysqli_num_rows($result);
                                            if($num_row>0) {
                                                $fetch_user = mysqli_fetch_array($result);
                                                if ($fetch_user['qualifications'] == null){
                                                    $user_qualification = $qualification;
                                                }else{
                                                    $temp_user_qualification = explode('|' ,$fetch_user['qualifications']);
                                                    array_push($temp_user_qualification, $qualification);
                                                    $temp_user_qualifications = implode('|',$temp_user_qualification);
                                                    $user_qualification = $temp_user_qualifications;
                                                }
                                            }
                                            $update_sql = "UPDATE `citizens` SET `qualifications`='" . $user_qualification . "' WHERE `national_id` = '" . $fetch['national_id'] . "' ";
                                            mysqli_query($conn, $update_sql);
                                        }

                                        $response_array = array(
                                            'status_code' => 201,
                                            'status' => true,
                                            'message' => 'update user\'s details',
                                        );

                                        /*resume file upload*/
                                        if ($_FILES['resume']){
                                            $resume_name = $_FILES["resume"]["name"];
                                            $resume_tmp_name = $_FILES["resume"]["tmp_name"];
                                            $error = $_FILES["resume"]["error"];

                                            if($error > 0){
                                                $response = array(
                                                    'status_code' => 400,
                                                    'status' => false,
                                                    'message' => "error uploading the resume"
                                                );
                                            }else
                                            {
                                                if (!file_exists('uploads/'.$national_id)) {
                                                    mkdir('uploads/'.$national_id, 0777, true);
                                                }
                                                $random_name = rand(1000,1000000)."-".$resume_name;
                                                $upload_name = 'uploads/'.$national_id.'/'.strtolower($random_name);
                                                $upload_name = preg_replace('/\s+/', '-', $upload_name);

                                                if(move_uploaded_file($resume_tmp_name , $upload_name)) {
                                                    $update_sql = "UPDATE `citizens` SET `resume`='" . $upload_name . "' WHERE `national_id` = '" . $fetch['national_id'] . "' ";
                                                    mysqli_query($conn, $update_sql);
                                                    $response = array(
                                                        'status_code' => 201,
                                                        "status" => true,
                                                        "message" => "resume uploaded successfully",
                                                        "url" => $server_url."/".$upload_name
                                                    );
                                                }else
                                                {
                                                    $response = array(
                                                        'status_code' => 400,
                                                        "status" => false,
                                                        "message" => "error uploading the resume!"
                                                    );
                                                }
                                            }
                                        }
                                        /*end file upload*/

                                        /*passport file upload*/
                                        if ($_FILES['passport']){
                                            $passport_name = $_FILES["passport"]["name"];
                                            $passport_tmp_name = $_FILES["passport"]["tmp_name"];
                                            $error = $_FILES["passport"]["error"];

                                            if($error > 0){
                                                $response = array(
                                                    'status_code' => 400,
                                                    'status' => false,
                                                    'message' => "error uploading the passport"
                                                );
                                            }else
                                            {
                                                if (!file_exists('uploads/'.$national_id)) {
                                                    mkdir('uploads/'.$national_id, 0777, true);
                                                }
                                                $random_name = rand(1000,1000000)."-".$passport_name;
                                                $upload_name = 'uploads/'.$national_id.'/'.strtolower($random_name);
                                                $upload_name = preg_replace('/\s+/', '-', $upload_name);

                                                if(move_uploaded_file($passport_tmp_name , $upload_name)) {
                                                    $update_sql = "UPDATE `citizens` SET `passport`='" . $upload_name . "' WHERE `national_id` = '" . $fetch['national_id'] . "' ";
                                                    mysqli_query($conn, $update_sql);
                                                    $response = array(
                                                        'status_code' => 201,
                                                        "status" => true,
                                                        "message" => "passport uploaded successfully",
                                                        "url" => $server_url."/".$upload_name
                                                    );
                                                }else
                                                {
                                                    $response = array(
                                                        'status_code' => 400,
                                                        "status" => false,
                                                        "message" => "error uploading the passport!"
                                                    );
                                                }
                                            }
                                        }
                                        /*end file upload*/

                                        /*passport file upload*/
                                        if ($_FILES['birth_certificate']){
                                            $birth_certificate_name = $_FILES["birth_certificate"]["name"];
                                            $birth_certificate_tmp_name = $_FILES["birth_certificate"]["tmp_name"];
                                            $error = $_FILES["birth_certificate"]["error"];

                                            if($error > 0){
                                                $response = array(
                                                    'status_code' => 400,
                                                    'status' => false,
                                                    'message' => "error uploading the birth certificate"
                                                );
                                            }else
                                            {
                                                if (!file_exists('uploads/'.$national_id)) {
                                                    mkdir('uploads/'.$national_id, 0777, true);
                                                }
                                                $random_name = rand(1000,1000000)."-".$birth_certificate_name;
                                                $upload_name = 'uploads/'.$national_id.'/'.strtolower($random_name);
                                                $upload_name = preg_replace('/\s+/', '-', $upload_name);

                                                if(move_uploaded_file($birth_certificate_tmp_name , $upload_name)) {
                                                    $update_sql = "UPDATE `citizens` SET `birth_certificate`='" . $upload_name . "' WHERE `national_id` = '" . $fetch['national_id'] . "' ";
                                                    mysqli_query($conn, $update_sql);
                                                    $response = array(
                                                        'status_code' => 201,
                                                        "status" => true,
                                                        "message" => "birth certificate uploaded successfully",
                                                        "url" => $server_url."/".$upload_name
                                                    );
                                                }else
                                                {
                                                    $response = array(
                                                        'status_code' => 400,
                                                        "status" => false,
                                                        "message" => "error uploading the birth certificate!"
                                                    );
                                                }
                                            }
                                        }
                                        /*end file upload*/
                                    }else{
                                        $response_array = array(
                                            'status_code' => 409,
                                            'status' => false,
                                            'message' => 'can not find user with this national id number',
                                        );
                                    }

                    }else{
                        $response_array = array(
                            'status_code' => 403,
                            'status' => false,
                            'message' => 'you have not permission',
                        );
                    }
                }else{
                    $response_array = array(
                        'status_code' => 409,
                        'status' => false,
                        'message' => 'staff officer can not find',
                    );
                }
            }else{
                $response_array = array(
                    'status_code' => 409,
                    'status' => false,
                    'message' => 'staff officer can not find',
                );
            }
        }else{
            $response_array = array(
                'status_code' => 400,
                'status' => false,
                'message' => 'authenticated device token required',
            );
        }
    }else{
        $response_array = array(
            'status_code' => 400,
            'status' => false,
            'message' => 'authenticated user national id is required',
        );
    }
    echo json_encode($response_array);
    exit;
}


//filter citizen qualification
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $method == 'filter_user_qualification') {
    $auth_national_id = $_GET['auth_national_id'];
    $device_token = $_GET['device_token'];
    $qualifications = $_GET['qualifications'];
    if (!empty($auth_national_id)){
        if (!empty($device_token)){
            $sql = "SELECT `national_id`, `device_token` FROM `staff` WHERE `national_id` = '".$auth_national_id."'";
            $result = mysqli_query($conn,$sql);
            $num_row = mysqli_num_rows($result);
            if($num_row>0) {
                $fetch = mysqli_fetch_array($result);
                if ($fetch['device_token'] != null){
                    $device_tokens = explode('|', $fetch['device_token']);
                    if (in_array($fetch['device_token'], $device_tokens)){
                            $sql = "SELECT * FROM `citizens`";
                            $results = mysqli_query($conn,$sql);
                            $num_row = mysqli_num_rows($results);

                            if($num_row>0){
                                $fetch = mysqli_fetch_array($results);
                                $arrs = array();
                                $available = false;
                               foreach ($results as $result){
                                   if($result['qualifications'] != null){
                                        $user_qualifications = explode('|', $result['qualifications']);
                                        $find_qualifications = explode(',', $qualifications);
                                        foreach ($find_qualifications as $find_qualification){
                                            if(in_array($find_qualification,$user_qualifications)){
                                                $available = true;
                                            }
                                        }
                                        if ($available){
                                            $available = false;
                                            $arr = array(
                                                'national_id' =>$fetch['national_id'],
                                                'name' =>$fetch['name'],
                                                'age' =>$fetch['age'],
                                                'address' =>$fetch['address'],
                                                'latitude' =>$fetch['latitude'],
                                                'longitude' =>$fetch['longitude'],
                                                'profession' =>$fetch['profession'],
                                                'email' =>$fetch['email'],
                                                'affiliation' =>$fetch['affiliation'],
                                            );
                                            array_push($arrs,$arr);

                                        }
                                    }
                               }
                                $response_array = array(
                                    'status_code' => 201,
                                    'status' => true,
                                    'message' => 'users filtered',
                                    'data' => $arrs,
                                );
                        }else{
                            $response_array = array(
                                'status_code' => 400,
                                'status' => false,
                                'message' => 'national id is required',
                            );
                        }
                    }else{
                        $response_array = array(
                            'status_code' => 403,
                            'status' => false,
                            'message' => 'you have not permission',
                        );
                    }
                }else{
                    $response_array = array(
                        'status_code' => 409,
                        'status' => false,
                        'message' => 'staff officer can not find',
                    );
                }
            }else{
                $response_array = array(
                    'status_code' => 409,
                    'status' => false,
                    'message' => 'staff officer can not find',
                );
            }
        }else{
            $response_array = array(
                'status_code' => 400,
                'status' => false,
                'message' => 'authenticated device token required',
            );
        }
    }else{
        $response_array = array(
            'status_code' => 400,
            'status' => false,
            'message' => 'authenticated staff officer national id is required',
        );
    }
    echo json_encode($response_array);
    exit;
}


?>