<?php
// v1/users.php
require '../db.php';

$database = new Database();
$db = $database->getConnection();

switch ($requestMethod) {
    case 'GET':
        getUsers($db);
        break;
    case 'POST':
        addUser($db);
        break;
    default:
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed."));
        break;
}

function getUsers($db) {
    $query = "SELECT id, name, email FROM users";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
}

function addUser($db) {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->name) && !empty($data->email)) {
        $query = "INSERT INTO users (name, email) VALUES (:name, :email)";
        $stmt = $db->prepare($query);

        $stmt->bindParam(":name", $data->name);
        $stmt->bindParam(":email", $data->email);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(array("message" => "User added."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to add user."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Incomplete data."));
    }
}
?>
