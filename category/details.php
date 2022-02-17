<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

// include database and object files
include_once '../config/database.php';
include_once '../objects/category.php';

// instantiate database and category object
$database = new Database();
$db = $database->getConnection();

// initialize object
$category = new Category($db);

// set ID property of record to read
$category->id = isset($_GET['id']) ? $_GET['id'] : die(); 

// set if property of record to read
$category->getDetails();

if($category->name != null){
    //create array
    $category_arr = array(
        "id" => $category->id,
        "name" => $category->name,
        "description" => $category->description
    );

    // set response code - 200 OK
    http_response_code(200);

    // make it json format
    echo json_encode($category_arr);
}else{
    // set response code - 404 Not Found
    http_response_code(404);

    // tell the user category does not exist
    echo json_encode(array("message" => "category does not exist."));
}
?>