<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


require_once __DIR__.'/include/HomeClass.php';
$env = __DIR__.'/.env';
$home = new HomeClass($env);

if(isset($_REQUEST) && ($_SERVER['REQUEST_METHOD'] === 'POST')) {
    if(!$home->isLoggedIn()){
        http_response_code(401);
        echo json_encode(array(
            "status"=> false,
            "message" => "Unauthorized"
        ));
        exit;
    }
    $post = json_decode(file_get_contents("php://input"), true);
    $validate = $home->validatePageForm($post);

    if(!$validate['status']){
        echo json_encode($validate);
        exit;
    }

    $page = $home->createPage($post);
    echo json_encode($page);

} else{
    http_response_code(403);
    echo json_encode(array(
        "status"=> false,
        "message" => "Request method invalid"
    ));
}
