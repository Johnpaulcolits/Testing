<?php

include "../../config/db.php";
include "../../model/insertions_model/Insert.Model.php";

// header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== "POST") {

    echo '<script>
        alert("Invalid Method. Try Again");
    </script>';

    exit;
}

try {

    $insertModel = new DepartmentDivision_Model($conn);

    $action = $_POST['action'] ?? null;

    switch ($action) {

        // ==========================================
        // CREATE DEPARTMENT DIVISION
        // ==========================================

        case "create":

            $division_id = $_POST["division_syntax"] ?? null;
            $department_id = $_POST["department_syntax"] ?? [];

            $create = $insertModel->createDepartmentDivision(
                $division_id,
                $department_id
            );

            if ($create === 'success') {

                echo '<script>
                    alert("Successfully Created");
                    window.location.href = "services";
                </script>';

                exit;

            } elseif ($create === 'assigned') {

                echo '<script>
                    alert("Department Already Assigned");
                    window.location.href = "services";
                </script>';

                exit;

            } elseif ($create === 'exist') {

                echo '<script>
                    alert("Already Exist");
                    window.location.href = "services";
                </script>';

                exit;

            } else {

                echo '<script>
                    alert("Failed to create record. Please try again.");
                    window.location.href = "services";
                </script>';

                exit;
            }

            break;


        // ==========================================
        // CREATE ASSETS PER USER DEPARTMENT
        // ==========================================

        case 'create_assets':

            // Get asset rows from preview form
            $assets = $_POST['assets'] ?? [];

            if (empty($assets) || !is_array($assets)) {

                echo '<script>
                    alert("No asset data received. Please generate the preview first.");
                    window.location.href = "assets";
                </script>';

                exit;
            }

            // Validate every asset row
            foreach ($assets as $index => $asset) {

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

                    echo '<script>
                        alert("Please complete the tagging of every asset.");
                        window.location.href = "assets";
                    </script>';

                    exit;
                }
            }

            // Insert all asset rows
            $result = $insertModel->createAssetsPerUserDepartment($assets);

            if ($result === 'success') {

                echo '<script>
                    alert("Successfully Created");
                    window.location.href = "assets";
                </script>';

                exit;

            } elseif ($result === 'invalid_quantity') {

                echo '<script>
                    alert("Invalid Quantity. Please enter a value between 1 and 1000.");
                    window.location.href = "assets";
                </script>';

                exit;

            } else {

                echo '<script>
                    alert("' . addslashes($result) . '");
                    window.location.href = "assets";
                </script>';

                exit;
            }

            break;

    }

} catch (Exception $e) {

    echo '<script>
        alert("An internal error occurred. Please try again later.");
        window.location.href = "services";
    </script>';

    error_log($e->getMessage());
}