<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/Book.php';
include_once '../controllers/BookController.php';

$database = new Database();
$db = $database->getConnection();
$book = new Book($db);
$controller = new BookController($book);

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

try {
    switch ($method) {
        case 'GET':
            $books = $controller->index();
            echo json_encode($books);
            break;
        case 'POST':
            $response = $controller->create($data);
            echo json_encode($response);
            break;
        case 'PUT':
            $response = $controller->update($data);
            echo json_encode($response);
            break;
        case 'DELETE':
            $id = isset($_GET['id']) ? $_GET['id'] : die();
            $response = $controller->delete($id);
            echo json_encode($response);
            break;
        default:
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Internal Server Error']);
}
