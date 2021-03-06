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

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$category = new Category($db);

//get post data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(
    !empty($data->name) && 
    !empty($data->description)

){
    // set category property values
    $category->name = $data->name;
    $category->description = $data->description;
    $category->created = date('Y-m-d H:i:s');

    // created the category
    if($category->create()){
        
        // set response code 201 - created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "Category was created."));
    }else{
        //if unable to create the category, tell the user
        //set response code - 503 service unavailable
        http_response_code_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to create category."));
    }
}else{
    // tell the user data is incomplete
    //set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to create category. Data is incomplete."));
}
?>