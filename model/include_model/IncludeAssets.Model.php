<?php



class IncludeAssets_Model

{
    private PDO $conn;


    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }


    public function getAllAssets()
    {
        $stmt = $this->conn->prepare(
            "
            SELECT Assets_id,Asset_name FROM ASSETS WHERE IsActive = 1
            ORDER BY Asset_Name ASC;
            "
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}