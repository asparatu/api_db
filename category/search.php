<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UFT-8");

// include database and object files
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/category.php';

// instantiate database and category object
$database = new Database();
$db = $database->getConnection();

// initialize object
$category = new Category($db);

// get keywords
$keywords = isset($_GET['s']) ? $_GET['s'] : "";

// query categories
$stmt = $category->search($keywords);
$num = $stmt->rowCount();

// check if more then 0 record found
if($num > 0){

    // category array
    $categories_arr = array();
    $categories_arr['records'] = array();

    // retrieve our table contents
    // fetch() is faster then fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);

        $category_item = array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description)
        );

        array_push($categories_arr['records'], $category_item);
    }

    // set response code - 200 ok
    http_response_code(200);

    // show categories data
    echo json_encode($categories_arr);
}else{
    // set response code - 404 Not Found
    http_response_code(404);

    // tell the user no category found
    echo json_encode(
        array("message" => "No categories found.")
    );
}
?>