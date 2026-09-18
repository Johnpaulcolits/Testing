<?php 




class IncludeUserDepartment_Model
{
    private PDO $conn;





    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }



    public function getAllUserDepartment()
    {
        $stmt = $this->conn->prepare(
            "
            SELECT User_department_id,User_fname, User_lname FROM USERS WHERE IsActive = 1
            ORDER BY User_fname ASC;
            "
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}