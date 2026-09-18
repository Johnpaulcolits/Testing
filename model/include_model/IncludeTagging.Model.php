<?php 

class IncludeTagging_Model
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function getAllTagging()
    {
        $stmt = $this->conn->prepare(
            "
            SELECT Tagging_id,performance_status FROM TAGGING WHERE IsActive = 1
            ORDER BY performance_status ASC;
            "
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}