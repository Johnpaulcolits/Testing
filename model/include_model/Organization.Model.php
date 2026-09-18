<?php

class Organization_Model
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    /*
    |--------------------------------------------------------------------------
    | GET DEPARTMENT DIVISIONS
    |--------------------------------------------------------------------------
    */

    public function getDepartmentDivisions(): array
    {
        $sql = "
            SELECT
                d.Division_id,
                d.Division_code,
                d.Division_name,

                dep.Department_id,
                dep.Department_code,
                dep.Department_name

            FROM Divisions d

            LEFT JOIN Departments_per_Division dpd
                ON d.Division_id = dpd.Division_id
                AND dpd.IsActive = 1

            LEFT JOIN Departments dep
                ON dpd.Department_id = dep.Department_id
                AND dep.IsActive = 1

            WHERE d.IsActive = 1

            ORDER BY
                d.Division_id ASC,
                dep.Department_name ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | GET ALL DIVISIONS
    |--------------------------------------------------------------------------
    */

    public function getDivisions(): array
    {
        $sql = "
            SELECT *
            FROM Divisions
            WHERE IsActive = 1
            ORDER BY Division_name ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | GET ALL DEPARTMENTS
    |--------------------------------------------------------------------------
    */

    public function gerDepartments(): array
    {
        $sql = "
            SELECT *
            FROM Departments
            WHERE IsActive = 1
            ORDER BY Department_name ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | GET ALL DIVISIONS WITH DEPARTMENTS
    |--------------------------------------------------------------------------
    */

    public function getDivisionsWithDepartments(): array
    {
        $sql = "
            SELECT
                d.Division_id,
                d.Division_code,
                d.Division_name,

                dpd.department_division_id,

                dep.Department_id,
                dep.Department_code,
                dep.Department_name

            FROM Divisions d

            LEFT JOIN Departments_per_Division dpd
                ON d.Division_id = dpd.Division_id
                AND dpd.IsActive = 1

            LEFT JOIN Departments dep
                ON dpd.Department_id = dep.Department_id
                AND dep.IsActive = 1

            WHERE d.IsActive = 1

            ORDER BY
                d.Division_name ASC,
                dep.Department_name ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $divisions = [];

        foreach ($rows as $row) {
            $divisionId = (int) $row['Division_id'];

            if (!isset($divisions[$divisionId])) {
                $divisions[$divisionId] = [
                    'Division_id' => $row['Division_id'],
                    'Division_code' => $row['Division_code'],
                    'Division_name' => $row['Division_name'],
                    'Departments' => []
                ];
            }

            if (
                isset($row['Department_id']) &&
                $row['Department_id'] !== null
            ) {
                $divisions[$divisionId]['Departments'][] = [
                    'Department_id' => $row['Department_id'],
                    'Department_code' => $row['Department_code'],
                    'Department_name' => $row['Department_name']
                ];
            }
        }

        return array_values($divisions);
    }

    /*
    |--------------------------------------------------------------------------
    | GET DEPARTMENT INFORMATION
    |--------------------------------------------------------------------------
    */

    public function getDepartment(int $departmentId): ?array
    {
        $sql = "
            SELECT TOP 1
                dep.Department_id,
                dep.Department_code,
                dep.Department_name,

                d.Division_id,
                d.Division_code,
                d.Division_name

            FROM Departments dep

            INNER JOIN Departments_per_Division dpd
                ON dep.Department_id = dpd.Department_id

            INNER JOIN Divisions d
                ON dpd.Division_id = d.Division_id

            WHERE dep.Department_id = :department_id
                AND dep.IsActive = 1
                AND dpd.IsActive = 1
                AND d.IsActive = 1

            ORDER BY d.Division_name ASC
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(
            ':department_id',
            $departmentId,
            PDO::PARAM_INT
        );

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    /*
    |--------------------------------------------------------------------------
    | GET USERS UNDER DEPARTMENT
    |--------------------------------------------------------------------------
    |
    | Actual USERS table structure:
    |
    | User_department_id
    | Department_id
    | User_fname
    | User_lname
    | User_middlename
    | IsActive
    |
    | Assets are connected directly using:
    |
    | USERS.User_department_id
    | =
    | ASSETS_per_USER_DEPARTMENT.User_department_id
    |
    */

    public function getUsersByDepartment(int $departmentId): array
    {
        $sql = "
            SELECT
                u.User_department_id,
                u.Department_id,

                u.User_fname,
                u.User_middlename,
                u.User_lname,

                CONCAT(
                    u.User_fname,
                    CASE
                        WHEN u.User_middlename IS NOT NULL
                             AND LTRIM(RTRIM(u.User_middlename)) <> ''
                        THEN CONCAT(' ', u.User_middlename)
                        ELSE ''
                    END,
                    CASE
                        WHEN u.User_lname IS NOT NULL
                             AND LTRIM(RTRIM(u.User_lname)) <> ''
                        THEN CONCAT(' ', u.User_lname)
                        ELSE ''
                    END
                ) AS User_Fullname,

                COUNT(
                    CASE
                        WHEN apud.IsActive = 1 THEN 1
                    END
                ) AS TotalAssetRecords,

                COALESCE(
                    SUM(
                        CASE
                            WHEN apud.IsActive = 1
                            THEN apud.Assets_Quantity
                            ELSE 0
                        END
                    ),
                    0
                ) AS TotalAssets

            FROM USERS u

            LEFT JOIN ASSETS_per_USER_DEPARTMENT apud
                ON u.User_department_id = apud.User_department_id
                AND apud.IsActive = 1

            WHERE u.Department_id = :department_id
                AND u.IsActive = 1

            GROUP BY
                u.User_department_id,
                u.Department_id,
                u.User_fname,
                u.User_middlename,
                u.User_lname

            ORDER BY
                User_Fullname ASC
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(
            ':department_id',
            $departmentId,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | GET USER INFORMATION
    |--------------------------------------------------------------------------
    */

    public function getUserDepartment(int $userDepartmentId): ?array
    {
        $sql = "
            SELECT TOP 1
                u.User_department_id,
                u.Department_id,

                u.User_fname,
                u.User_middlename,
                u.User_lname,

                CONCAT(
                    u.User_fname,
                    CASE
                        WHEN u.User_middlename IS NOT NULL
                             AND LTRIM(RTRIM(u.User_middlename)) <> ''
                        THEN CONCAT(' ', u.User_middlename)
                        ELSE ''
                    END,
                    CASE
                        WHEN u.User_lname IS NOT NULL
                             AND LTRIM(RTRIM(u.User_lname)) <> ''
                        THEN CONCAT(' ', u.User_lname)
                        ELSE ''
                    END
                ) AS User_Fullname,

                dep.Department_id,
                dep.Department_code,
                dep.Department_name,

                d.Division_id,
                d.Division_code,
                d.Division_name

            FROM USERS u

            INNER JOIN Departments dep
                ON u.Department_id = dep.Department_id

            INNER JOIN Departments_per_Division dpd
                ON dep.Department_id = dpd.Department_id
                AND dpd.IsActive = 1

            INNER JOIN Divisions d
                ON dpd.Division_id = d.Division_id
                AND d.IsActive = 1

            WHERE u.User_department_id = :user_department_id
                AND u.IsActive = 1
                AND dep.IsActive = 1
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(
            ':user_department_id',
            $userDepartmentId,
            PDO::PARAM_INT
        );

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    /*
    |--------------------------------------------------------------------------
    | GET ASSETS BY USER
    |--------------------------------------------------------------------------
    |
    | Actual asset columns:
    |
    | ASSETS_per_USER_DEPARTMENT:
    | - asset_per_user_department_id
    | - Tagging_id
    | - User_department_id
    | - Assets_id
    | - Assets_category_id
    | - Assets_Quantity
    | - Asset_Model
    | - Asset_Serial_Number
    | - Purchase_Date
    | - Purchase_Cost
    | - Remarks
    |
    | ASSETS:
    | - Assets_id
    | - Asset_name
    |
    | ASSETS_CATEGORY:
    | - Assets_category_id
    | - Assets_category_name
    |
    | TAGGING:
    | - Tagging_id
    | - performance_status
    | - is_operational
    |
    */

    public function getAssetsByUserDepartment(
        int $userDepartmentId
    ): array {
        $sql = "
            SELECT
                apud.asset_per_user_department_id,
                apud.Tagging_id,
                apud.User_department_id,
                apud.Assets_id,
                apud.Assets_category_id,

                apud.Assets_Quantity,
                apud.Asset_Model,
                apud.Asset_Serial_Number,

                apud.Purchase_Date,
                apud.Purchase_Cost,
                apud.Remarks,

                apud.Created_at,
                apud.Updated_at,
                apud.last_checked_at,

                a.Asset_name,
                a.Asset_description,

                ac.Assets_category_name,
                ac.Assets_category_description,

                t.performance_status,
                t.Tagging_description,
                t.efficiency_percentage,
                t.is_operational

            FROM ASSETS_per_USER_DEPARTMENT apud

            INNER JOIN ASSETS a
                ON apud.Assets_id = a.Assets_id

            INNER JOIN ASSETS_CATEGORY ac
                ON apud.Assets_category_id = ac.Assets_category_id

            INNER JOIN TAGGING t
                ON apud.Tagging_id = t.Tagging_id

            WHERE apud.User_department_id = :user_department_id
                AND apud.IsActive = 1

            ORDER BY
                apud.Created_at DESC
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(
            ':user_department_id',
            $userDepartmentId,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | GET ALL USERS
    |--------------------------------------------------------------------------
    */

    public function getAllUsers(): array
    {
        $sql = "
            SELECT
                u.User_department_id,
                u.Department_id,

                u.User_fname,
                u.User_middlename,
                u.User_lname,

                CONCAT(
                    u.User_fname,
                    CASE
                        WHEN u.User_middlename IS NOT NULL
                             AND LTRIM(RTRIM(u.User_middlename)) <> ''
                        THEN CONCAT(' ', u.User_middlename)
                        ELSE ''
                    END,
                    CASE
                        WHEN u.User_lname IS NOT NULL
                             AND LTRIM(RTRIM(u.User_lname)) <> ''
                        THEN CONCAT(' ', u.User_lname)
                        ELSE ''
                    END
                ) AS User_Fullname,

                dep.Department_name

            FROM USERS u

            INNER JOIN Departments dep
                ON u.Department_id = dep.Department_id

            WHERE u.IsActive = 1
                AND dep.IsActive = 1

            ORDER BY
                User_Fullname ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | GET TOTAL USERS BY DEPARTMENT
    |--------------------------------------------------------------------------
    */

    public function getTotalUsersByDepartment(
        int $departmentId
    ): int {
        $sql = "
            SELECT COUNT(*) AS TotalUsers
            FROM USERS
            WHERE Department_id = :department_id
                AND IsActive = 1
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(
            ':department_id',
            $departmentId,
            PDO::PARAM_INT
        );

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) ($result['TotalUsers'] ?? 0);
    }

    /*
    |--------------------------------------------------------------------------
    | GET TOTAL ASSETS BY DEPARTMENT
    |--------------------------------------------------------------------------
    */

    public function getTotalAssetsByDepartment(
        int $departmentId
    ): int {
        $sql = "
            SELECT
                COALESCE(SUM(apud.Assets_Quantity), 0) AS TotalAssets

            FROM USERS u

            INNER JOIN ASSETS_per_USER_DEPARTMENT apud
                ON u.User_department_id = apud.User_department_id

            WHERE u.Department_id = :department_id
                AND u.IsActive = 1
                AND apud.IsActive = 1
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(
            ':department_id',
            $departmentId,
            PDO::PARAM_INT
        );

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) ($result['TotalAssets'] ?? 0);
    }




public function getUsersWithAssetsByDepartment($departmentId)
{
    $sql = "
        SELECT
            /*
            |--------------------------------------------------------------------------
            | USER INFORMATION
            |--------------------------------------------------------------------------
            */

            u.User_department_id,
            u.Is_Shifting,

            CONCAT(
                u.User_fname,
                CASE
                    WHEN u.User_middlename IS NOT NULL
                         AND LTRIM(RTRIM(u.User_middlename)) <> ''
                    THEN CONCAT(' ', u.User_middlename)
                    ELSE ''
                END,
                CASE
                    WHEN u.User_lname IS NOT NULL
                         AND LTRIM(RTRIM(u.User_lname)) <> ''
                    THEN CONCAT(' ', u.User_lname)
                    ELSE ''
                END
            ) AS User_Fullname,

            /*
            |--------------------------------------------------------------------------
            | DEPARTMENT AND DIVISION
            |--------------------------------------------------------------------------
            */

            d.Department_id,
            d.Department_name,

            div.Division_id,
            div.Division_name,

            /*
            |--------------------------------------------------------------------------
            | ASSET ASSIGNMENT INFORMATION
            |--------------------------------------------------------------------------
            | IsActive and Updated_at are intentionally not selected.
            |--------------------------------------------------------------------------
            */

            apud.asset_per_user_department_id,
            apud.Tagging_id,
            apud.Assets_id,
            apud.Assets_category_id,
            apud.Assets_Quantity,

            apud.Created_at,
            apud.last_checked_at,

            apud.Asset_Model,
            apud.Asset_Serial_Number,

            apud.Purchase_Date,
            apud.Purchase_Cost,
            apud.Remarks AS Asset_Remarks,

            /*
            |--------------------------------------------------------------------------
            | ASSET CATEGORY INFORMATION
            |--------------------------------------------------------------------------
            */

            ac.Assets_category_name,
            ac.Assets_category_description,

            /*
            |--------------------------------------------------------------------------
            | ASSET INFORMATION
            |--------------------------------------------------------------------------
            */

            a.Asset_name,
            a.Asset_description,

            /*
            |--------------------------------------------------------------------------
            | TAGGING INFORMATION
            |--------------------------------------------------------------------------
            */

            t.performance_status,
            t.Tagging_description,
            t.efficiency_percentage,
            t.is_operational

        FROM USERS u

        INNER JOIN Departments d
            ON u.Department_id = d.Department_id

        INNER JOIN Departments_per_Division dpd
            ON dpd.Department_id = d.Department_id

        INNER JOIN Divisions div
            ON div.Division_id = dpd.Division_id

        LEFT JOIN ASSETS_per_USER_DEPARTMENT apud
            ON apud.User_department_id = u.User_department_id
            AND apud.IsActive = 1

        LEFT JOIN ASSETS_CATEGORY ac
            ON apud.Assets_category_id = ac.Assets_category_id

        LEFT JOIN ASSETS a
            ON apud.Assets_id = a.Assets_id

        LEFT JOIN TAGGING t
            ON apud.Tagging_id = t.Tagging_id

        WHERE
            u.Department_id = :department_id
    AND u.IsActive = 1
    AND u.Is_Shifting = 0

        ORDER BY
            User_Fullname ASC,
            apud.Created_at DESC
    ";

    $stmt = $this->conn->prepare($sql);

    $stmt->bindValue(
        ':department_id',
        $departmentId,
        PDO::PARAM_INT
    );

    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /*
    |--------------------------------------------------------------------------
    | GROUP ASSETS PER USER
    |--------------------------------------------------------------------------
    */

    $users = [];

    foreach ($rows as $row) {

        $userId = (int) $row['User_department_id'];

        /*
        |--------------------------------------------------------------------------
        | CREATE USER RECORD
        |--------------------------------------------------------------------------
        */

        if (!isset($users[$userId])) {

            $users[$userId] = [
                'User_department_id' => $row['User_department_id'],
                'Is_Shifting' => (int) $row['Is_Shifting'],
                'User_Fullname' => $row['User_Fullname'],

                'Department_id' => $row['Department_id'],
                'Department_name' => $row['Department_name'],

                'Division_id' => $row['Division_id'],
                'Division_name' => $row['Division_name'],

                'Assets' => []
            ];

        }

        /*
        |--------------------------------------------------------------------------
        | ADD ASSET ONLY IF USER HAS AN ACTIVE ASSET
        |--------------------------------------------------------------------------
        */

        if (!empty($row['Assets_id'])) {

            $users[$userId]['Assets'][] = [

                /*
                |--------------------------------------------------------------------------
                | ASSET IDENTIFICATION
                |--------------------------------------------------------------------------
                */

                'asset_per_user_department_id' =>
                    $row['asset_per_user_department_id'],

                'Tagging_id' =>
                    $row['Tagging_id'],

                'User_department_id' =>
                    $row['User_department_id'],

                'Assets_id' =>
                    $row['Assets_id'],

                'Assets_category_id' =>
                    $row['Assets_category_id'],

                /*
                |--------------------------------------------------------------------------
                | QUANTITY AND DATE INFORMATION
                |--------------------------------------------------------------------------
                */

                'Assets_Quantity' =>
                    $row['Assets_Quantity'],

                'Created_at' =>
                    $row['Created_at'],

                'last_checked_at' =>
                    $row['last_checked_at'],

                /*
                |--------------------------------------------------------------------------
                | ASSET DETAILS
                |--------------------------------------------------------------------------
                */

                'Asset_Model' =>
                    $row['Asset_Model'],

                'Asset_Serial_Number' =>
                    $row['Asset_Serial_Number'],

                'Purchase_Date' =>
                    $row['Purchase_Date'],

                'Purchase_Cost' =>
                    $row['Purchase_Cost'],

                'Asset_Remarks' =>
                    $row['Asset_Remarks'],

                /*
                |--------------------------------------------------------------------------
                | ASSET MASTER INFORMATION
                |--------------------------------------------------------------------------
                */

                'Asset_name' =>
                    $row['Asset_name'],

                'Asset_description' =>
                    $row['Asset_description'],

                /*
                |--------------------------------------------------------------------------
                | ASSET CATEGORY INFORMATION
                |--------------------------------------------------------------------------
                */

                'Assets_category_name' =>
                    $row['Assets_category_name'],

                'Assets_category_description' =>
                    $row['Assets_category_description'],

                /*
                |--------------------------------------------------------------------------
                | TAGGING INFORMATION
                |--------------------------------------------------------------------------
                */

                'performance_status' =>
                    $row['performance_status'],

                'Tagging_description' =>
                    $row['Tagging_description'],

                'efficiency_percentage' =>
                    $row['efficiency_percentage'],

                'is_operational' =>
                    $row['is_operational']

            ];

        }

    }

    return array_values($users);
}





}