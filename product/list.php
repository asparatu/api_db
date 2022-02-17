<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../objects/product.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$product = new Product($db);

// query products
$stmt = $product->getList();
$num = $stmt->rowCount();

// check if more then 0 record found
if($num > 0){

    // product array
    $product_arr = array();
    $product_arr['records'] = array();

    // retrieve our table contents
    // fetch() is faster then fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);

        $product_item = array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description),
            "price" => $price,
            "category_id" => $category_id,
            "category_name" => $category_name
        );

        array_push($product_arr['records'], $product_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show response data in json format
    echo json_encode($product_arr);
}else{
    //set response code - 404 Not Found
    http_response_code(404);

    //tells the user no products found
    echo json_encode(
        array("message" => "No products found.")
    );
}
?>