<?php
class Category{

    // database connection and table name
    private $conn;
    private $table_name = "categories";

    // object properties
    public $id;
    public $name;
    public $description;
    public $created;

    public function __construct($db){
        $this->conn = $db;
    }

    // used by select drop-down list
    public function getList(){
        // select all data
        $query = "SELECT
                    id, name, description
                FROM 
                    " . $this->table_name . "
                ORDER BY
                    name";

        // prepare query statement and execute it
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        // returns the statement
        return $stmt;
    }

    // create category
    function create(){

        // query to insert record
        $query = "INSERT INTO 
                    " . $this->table_name . "
                    SET 
                        name=:name, description=:description, created=:created";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->created = htmlspecialchars(strip_tags($this->created));

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":created", $this->created);

        // execute query
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    //used to return the details of a category
    function getDetails(){

        //query to read single record
       $query = "SELECT 
                   id, name, description
                FROM 
                    " . $this->table_name . "
                WHERE 
                    id = ? 
                LIMIT 
                    0, 1";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id of category to be updated
        $stmt->bindParam(1, $this->id);

        // execute query
        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->name = $row['name'];
        $this->description = $row['description'];
    }

    // update category
    function update(){

        // query to insert record
        $query = "UPDATE
                    " . $this->table_name . "
                    SET
                        name=:name,
                        description=:description
                    WHERE
                        id=:id";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":id", $this->id);

        // execute query
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    // delete the category
    function delete(){

        //delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));

        // bind id of record to delete
        $stmt->bindParam(1, $this->id);

        // execute query
        if($stmt->execute()){
            return true;
        }

        return false;
    }

    // search category
    function search($keywords){

        //select all query
        $query = "SELECT
                    id, name, description, created
                FROM
                    " . $this->table_name . "
                WHERE
                    name LIKE ? OR description LIKE ?
                ORDER BY
                    created DESC";

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // sanitize
    $keywords = htmlspecialchars(strip_tags($keywords));
    $keywords = "%{$keywords}%";

    // bind
    $stmt->bindParam(1, $keywords);
    $stmt->bindParam(2, $keywords);

    //execute query
    $stmt->execute();

    return $stmt;
    }
}
?>