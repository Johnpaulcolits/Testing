<?php


class IncludeDivisionDepartment_Model

{
    private PDO $conn;


    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }



    public function getDepartmentDivisions()
    {
        $stmt = $this->conn->prepare(
            " 
                SELECT
                div.Division_name,
                dep.Department_name
                FROM Departments_per_Division depdiv
                INNER JOIN Divisions div
                ON depdiv.Division_id = div.Division_id
                INNER JOIN Departments dep
                ON depdiv.Department_id = dep.Department_id 
                WHERE div.IsActive = 1 AND dep.IsActive = 1 
                ORDER BY div.Division_name ASC, dep.Department_name ASC; 

            "
        );

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }



    public function getDivisions()
    {
        $stmt = $this->conn->prepare("SELECT * FROM Divisions");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;


    }

    public function gerDepartments()
    {
        $stmt = $this->conn->prepare("SELECT * FROM Departments");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }


}