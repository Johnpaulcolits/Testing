<?php

class DepartmentDivision_Model
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }


    // CREATE DEPARTMENT DIVISION
    public function createDepartmentDivision(string $division_id, array $department_ids)
    {
        try {

            $this->conn->beginTransaction();

            foreach ($department_ids as $department_id) {

                $checkDepartment = $this->conn->prepare("
                    SELECT COUNT(*)
                    FROM Departments_per_Division
                    WHERE Department_id = ?
                ");

                $checkDepartment->execute([$department_id]);

                if ($checkDepartment->fetchColumn() > 0) {

                    $this->conn->rollBack();

                    return 'assigned';
                }

                $checkStmt = $this->conn->prepare("
                    SELECT COUNT(*)
                    FROM Departments_per_Division
                    WHERE Division_id = ? AND Department_id = ?
                ");

                $checkStmt->execute([
                    $division_id,
                    $department_id
                ]);

                $exist = $checkStmt->fetchColumn();

                if ($exist > 0) {

                    $this->conn->rollBack();

                    return 'exist';
                }

                $stmt = $this->conn->prepare("
                    INSERT INTO Departments_per_Division
                    (Division_id, Department_id)
                    VALUES (?, ?)
                ");

                $stmt->execute([
                    $division_id,
                    $department_id
                ]);
            }

            $this->conn->commit();

            return 'success';

        } catch (Exception $e) {

            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            error_log($e->getMessage());

            return 'failed';
        }
    }


    // CREATE ASSETS PER USER DEPARTMENT
    public function createAssetsPerUserDepartment(array $assets)
    {
        try {

            if (empty($assets) || !is_array($assets)) {
                return 'failed: No asset data received.';
            }

            if (count($assets) > 1000) {
                return 'invalid_quantity';
            }

            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("
                INSERT INTO ASSETS_per_USER_DEPARTMENT
                (
                    Tagging_id,
                    User_department_id,
                    Assets_id,
                    Assets_category_id,
                    Assets_Quantity
                )
                VALUES (?, ?, ?, ?, ?)
            ");

            foreach ($assets as $asset) {

                $tagging_id = $asset['Tagging_id'] ?? null;
                $user_department_id = $asset['User_department_id'] ?? null;
                $assets_id = $asset['Assets_id'] ?? null;
                $assets_category_id = $asset['Assets_category_id'] ?? null;

                if (
                    empty($tagging_id) ||
                    empty($user_department_id) ||
                    empty($assets_id) ||
                    empty($assets_category_id)
                ) {

                    $this->conn->rollBack();

                    return 'failed: Please complete the tagging of every asset.';
                }

                $stmt->execute([
                    $tagging_id,
                    $user_department_id,
                    $assets_id,
                    $assets_category_id,
                    1
                ]);
            }

            $this->conn->commit();

            return 'success';

        } catch (PDOException $e) {

            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            error_log($e->getMessage());

            return 'failed: ' . $e->getMessage();
        }
    }

}