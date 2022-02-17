<?php
// required header
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// includes database and object files;
include_once '../config/database.php';
include_once '../objects/category.php';

// instantiate database and category object
$database = new Database();
$db = $database->getConnection();

// initialize object
$category = new Category($db);

// query categories
$stmt = $category->getList();
$num = $stmt->rowCount();

// check if more then 0 record found
if($num > 0){

    //category array
    $categories_arr = array();
    $categories_arr['records'] = array();

    // retrieve our table contents
    // fetch() is faster then fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        //this will make $row['name'] to
        // just $name only
        extract($row);

        $category_item = array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description)
        );

        array_push($categories_arr["records"], $category_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show categories data in json format
    echo json_encode($categories_arr);
}else{
     // set response code - 404 Not Found
     http_response_code(404);

     // show categories data in json format
     echo json_encode(
         array("message" => "No categories found.")
     );
}
?>