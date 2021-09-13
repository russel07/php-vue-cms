<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__.'/include/HomeClass.php';
$env = __DIR__.'/.env';
$home = new HomeClass($env);



$pages = $home->getActivePages();

if(isset($_REQUEST) && ($_SERVER['REQUEST_METHOD'] === 'GET')) {
    $pageId = (isset($_GET['page']) && !empty($_GET['page'])) ? $_GET['page']: '';
    if ($pageId) {
        $page = $home->getPagesById($pageId);

        echo json_encode($page);

    } else {
        echo json_encode($pages);
    }
} else {
    http_response_code(403);
    echo json_encode(array(
        "status"=> false,
        "message" => "Request method invalid"
    ));
}
