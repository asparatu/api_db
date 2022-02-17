<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../objects/category.php';

// instantiate database and category object
$database = new Database();
$db = $database->getConnection();

// initialize object
$category = new Category($db);

//get post data
$data = json_decode(file_get_contents("php://input"));

// set ID property to be deleted
$category->id = $data->id;

// make sure data is not empty
if($category->delete()){

    // set response code 200 - OK
    http_response_code(200);

    // tell the user
    echo json_encode(array("message" => "category was deleted."));

}else{
    //if unable to update the category, tell the user
    //set response code - 503 service unavailable
    http_response_code(503);

    // tell the user
    echo json_encode(array("message" => "Unable to delete category."));
}
?>