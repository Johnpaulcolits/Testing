<?php


class IncludeAssetsCategory_Model

{
    private PDO $conn;



    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }


    public function geAssetsCategory()
    {
        $stmt = $this->conn->prepare(
            "
            SELECT Assets_category_id, Assets_category_name FROM ASSETS_CATEGORY WHERE IsActive = 1
            ORDER BY Assets_category_name ASC;
            "
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}



