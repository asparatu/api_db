<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// includes database and object files
include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../config/database.php';
include_once '../objects/product.php';

// utilities
$utils = new Utilities();

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$product = new Product($db);

// query statements
$stmt = $product->listPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// check if more than 0 records found
if($num > 0){

    // products array
    $product_arr = array();
    $product_arr['records'] = array();
    $product_arr['paging'] = array();

    // retrieve our table contents
    // fetch() is faster that fetchAll()
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

    // include paging
    $total_rows = $product->count();
    $page_url = "{$home_url}product/list_paging.php?";
    $paging = $utils->getPaging($page, $total_rows, $records_per_page, $page_url);
    $product_arr['paging'] = $paging;

    // set response code - 200 OK
    http_response_code(200);

    // make it json format
    echo json_encode($product_arr);
}else{

    // set response code - 404 Not Found
    http_response_code(404);

    // tell the user products does not exist
    echo json_encode(
        array("message" => "No products found.")
    );
}
?>