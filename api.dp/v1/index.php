<?php

require 'control/users.php';
require 'control/money.php';
require 'control/apps.php';
require 'control/messages.php';
require 'vendor/slim/slim/Slim/Slim.php';

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");         

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

}

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$user_id = NULL;
$app_id = NULL;

function authenticate(\Slim\Route $route) {
    // Getting request headers
    $headers = apache_request_headers();
    $response = array();
    $app = \Slim\Slim::getInstance();
 
    // Verifying Authorization Header
    if (isset($headers['Authorization'])) {
        $db = new users();
 
        // get the api key
        $api_key = $headers['Authorization'];
        // validating api key
        if (!$db->isValidApiKey($api_key)) {
            // api key is not present in users table
            echo json_encode(array('error' => true, 'message' => 'Access Denied. Invalid Apy key'));
            $app->stop();
        } else {
            global $user_id;
            // get user primary key id
            $user = $db->getUserId($api_key);
            if ($user != NULL){
                $user_id=$user;
            }
        }
    }else{
        // api key is missing in header
        echo json_encode(array('error' => true, 'message' => 'Apy key is misssing'));
        $app->stop();
    }
}

function authenticateapp(\Slim\Route $route) {
    // Getting request headers
    $headers = apache_request_headers();
    $response = array();
    $app = \Slim\Slim::getInstance();
 
    // Verifying Authorization Header
    if (isset($headers['Authorization'])) {
        $db = new apps();
 
        // get the app key
        $app_key = $headers['Authorization'];
        // validating app key
        if (!$db->isValidAppKey($app_key)) {
            // app key is not present in applications table
            echo json_encode(array('error' => true, 'message' => 'Access Denied. Invalid App key'));
            $app->stop();
        } else {
            global $app_id;
            // get app primary key id
            $app = $db->getAppId($app_key);
            if ($app != NULL){
                $app_id=$app;
            }
        }
    }else{
        // app key is missing in header
        echo json_encode(array('error' => true, 'message' => 'App key is misssing'));
        $app->stop();
    }
}

$app->post('/users/register', 'authenticateapp',function() use ($app) {
    $json = $app->request->getBody();
    $data = json_decode($json);
    $username = $data->username;
    $password = $data->password;
    
    $response = array();
    $db = new users();
    $res = $db->crear($username, $password); 
    if ($res == USER_CREATED_SUCCESSFULLY) {
        echo json_encode(array('error' => false, 'message' => 'You are successfully registered'));
    } else if ($res == USER_CREATE_FAILED) {
        echo json_encode(array('error' => true, 'message' => 'Oops! An error occurred while registereing'));
    } else if ($res == USER_ALREADY_EXISTED) {
        echo json_encode(array('error' => true, 'message' => 'Sorry, this user already existed'));
    }
});

$app->post('/users/login', function() use ($app) {
    $json = $app->request->getBody();
    $data = json_decode($json);
    $username = $data->username;
    $password = $data->password;
    $response = array();
 
    $db = new users();
    if ($db->autenticar($username, $password)) {
        $user = $db->getUserByUsername($username);
        if ($user != NULL) {
            echo json_encode(array('error' => false, 'message' => 'You are successfully login', 
            'username' => $user['username'], 'api_key' => $user['api_key']));
        } else {
            echo json_encode(array('error' => true, 'message' => 'Oops! An error occurred while login'));
        }
    } else {
        echo json_encode(array('error' => true, 'message' => 'Login failed. Incorrect credentials'));
    }
});

$app->post('/users/logout', function() use ($app) {
    $json = $app->request->getBody();
    $data = json_decode($json);
    $username = $data->username;

    $response = array();
 
    $db = new users();
    if ($db->isUserExists($username)) {
        echo json_encode(array('error' => false, 'message' => 'Good Bye'));
    } else {
        echo json_encode(array('error' => true, 'message' => 'Login failed. Incorrect username'));
    }
});

$app->post('/money', 'authenticate',  function() use ($app) {
    $json = $app->request->getBody();
    $data = json_decode($json);
    $name = $data->name;
    $price = $data->price;
    $response = array();
    global $user_id;
    $db = new money();
    $idMoney = $db->createMoney($user_id,$name,$price);
    if ($idMoney != NULL) {
        $db->updateMoney($user_id,$idMoney,$price);
        echo json_encode(array('error' => false, 'message' => 'Money created successfully',
            'id' => $idMoney));
    }else{
        echo json_encode(array('error' => true, 'message' => 'Failed to create money. Please try again'));
    } 
});

$app->put('/money/:id', 'authenticate', function($idMoney) use ($app) {
    $name=NULL;
    $price=NULL;
    $json = $app->request->getBody();
    $data = json_decode($json);
    if (!empty($data->name))
        $name = $data->name;
    if (!empty($data->price))
        $price = $data->price;
    $response = array();
    global $user_id;
    $db = new money();
    $res = $db->update($user_id, $idMoney, $name, $price);
    if ($res != NULL) {
        echo json_encode(array('error' => false, 'message' => 'Money update successfully',
            'id' => $idMoney));
    } else{
        echo json_encode(array('error' => true, 'message' => 'Failed to update money. Please try again'));
    }
});

$app->get('/money', 'authenticate', function() {
            global $user_id;
            $response = array();
            $db = new money();
 
            $result = $db->getMoney();
            echo json_encode($result);
});

$app->get('/money/:id', 'authenticate', function($idMoney) use ($app) {
            global $user_id;
            $response = array();
            $start = $app->request()->params('start');
            $end = $app->request()->params('end');
            $db = new money();
            $result = $db->getHistoryMoney($idMoney,$start,$end);
            if ($result != NULL) {
                echo json_encode(array('id' => $idMoney, 
                'name' => $db->getNameById($idMoney)['name'],'prices' => $result));
            } else{
                echo json_encode(array('error' => true, 'message' => 'Failed to get money. Please try again'));
            }
});

$app->get('/messages', 'authenticate', function() {
            global $user_id;
            $response = array();
            $db = new messages();
 
            $result = $db->getMessages();
            echo json_encode(array('error' => false, 'message' => $result));
});

$app->put('/messages', 'authenticate', function() use ($app) {
    $json = $app->request->getBody();
    $data = json_decode($json);
    $message = $data->message;
    $response = array();
    global $user_id;
    $db = new messages();
    $res = $db->updateMessage($message);
    if ($res != NULL) {
        echo json_encode(array('error' => false, 'message' => 'Message update successfully'));
    } else{
        echo json_encode(array('error' => true, 'message' => 'Failed to update message. Please try again'));
    }
});

$app->map('/:x+', function($x) {
    http_response_code(200);
})->via('OPTIONS');

$app->run();