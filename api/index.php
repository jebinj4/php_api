<?php
// index.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$requestMethod = $_SERVER["REQUEST_METHOD"];
$requestUri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

if (isset($requestUri[1]) && $requestUri[1] === 'api') {
    if (isset($requestUri[2])) {
        switch ($requestUri[2]) {
            case 'users':
                require 'v1/users.php';
                break;
            default:
                http_response_code(404);
                echo json_encode(array("message" => "Resource not found."));
                break;
        }
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "Resource not found."));
    }
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Resource not found."));
}
?>
