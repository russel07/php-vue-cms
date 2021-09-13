<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


require_once __DIR__.'/include/HomeClass.php';
$env = __DIR__.'/.env';
$home = new HomeClass($env);

if(isset($_REQUEST) && ($_SERVER['REQUEST_METHOD'] === 'DELETE')) {
    if(!$home->isLoggedIn()){
        http_response_code(401);
        echo json_encode(array(
            "status"=> false,
            "message" => "Unauthorized"
        ));
        exit;
    }

    $pageId = (isset($_GET['page']) && !empty($_GET['page'])) ? $_GET['page']: '';

    $delete = $home->deletePage($pageId);
    echo json_encode(array(
        'status' => $delete,
        'message' => ''
    ));

} else{
    http_response_code(403);
    echo json_encode(array(
        "status"=> false,
        "message" => "Request method invalid"
    ));
}
