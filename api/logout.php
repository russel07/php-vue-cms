<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__.'/include/HomeClass.php';
$env = __DIR__.'/.env';
$home = new HomeClass($env);

$loggedIn = $home->isLoggedIn();
$username = $home->getLoggedInUserName();

if(isset($_REQUEST) && ($_SERVER['REQUEST_METHOD'] === 'GET')) {
    if(!$home->isLoggedIn()){
        http_response_code(401);
        echo json_encode(array(
            "status"=> false,
            "message" => "Unauthoraized"
        ));
    }

    $home->logout();
    echo json_encode(array(
        "status"=> true,
        "message" => ""
    ));
} else{
    http_response_code(401);
    echo json_encode(array(
        "status"=> false,
        "message" => "Request method invalid"
    ));
}
