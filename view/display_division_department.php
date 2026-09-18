<?php

include "../config/db.php";
include "../model/include_model/IncludeDepartmentDivision.Model.php";

$includeModel = new IncludeDivisionDepartment_Model($conn);

$data = $includeModel->getDepartmentDivisions();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Department and Division List</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #4b50c5;
            --primary-dark: #303a9b;
            --primary-light: #eef0ff;

            --background: #f5f7fb;
            --white: #ffffff;

            --text-dark: #20243a;
            --text-medium: #62677c;
            --text-light: #8d92a5;

            --border: #e3e6ef;
            --border-light: #eef0f5;

            --success: #20a66a;

            --shadow-md: 0 12px 35px rgba(31, 38, 135, 0.08);
        }

        body {
            min-height: 100vh;
            background: var(--background);
            color: var(--text-dark);
            font-family: Arial, Helvetica, sans-serif;
        }

        .page-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* HEADER */

        .top-header {
            width: 100%;
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 22px 40px;
        }

        .header-content {
            width: 100%;
            max-width: 1250px;
            margin: 0 auto;

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .brand-section {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .brand-title {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: var(--text-dark);
        }

        .brand-subtitle {
            font-size: 13px;
            color: var(--text-medium);
        }

        .header-status {
            display: flex;
            align-items: center;
            gap: 8px;

            color: var(--success);
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--success);
        }

        /* MAIN */

        .main-content {
            width: 100%;
            max-width: 1250px;
            margin: 0 auto;
            padding: 42px 24px 60px;
            flex: 1;
        }

        .page-heading {
            margin-bottom: 28px;
        }

        .page-heading h1 {
            font-size: 30px;
            line-height: 1.25;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--text-dark);
        }

        .page-heading p {
            max-width: 700px;
            font-size: 15px;
            line-height: 1.7;
            color: var(--text-medium);
        }

        /* DATA CARD */

        .data-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 18px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .card-header {
            padding: 26px 30px;
            background: #fcfcff;
            border-bottom: 1px solid var(--border-light);

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .card-heading h2 {
            font-size: 19px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .card-heading p {
            font-size: 13px;
            line-height: 1.6;
            color: var(--text-medium);
        }

        .record-count {
            padding: 9px 13px;
            border-radius: 8px;
            background: var(--primary-light);
            color: var(--primary);
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        /* FILTER SECTION */

        .filter-section {
            padding: 25px 30px;
            border-bottom: 1px solid var(--border-light);
            background: var(--white);
        }

        .filter-label {
            display: block;
            margin-bottom: 9px;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .select-wrapper {
            position: relative;
            max-width: 480px;
        }

        .division-select {
            width: 100%;
            height: 48px;
            padding: 0 42px 0 14px;

            border: 1px solid var(--border);
            border-radius: 9px;
            background: var(--white);
            color: var(--text-dark);

            font-size: 14px;
            outline: none;
            cursor: pointer;

            appearance: none;
            -webkit-appearance: none;

            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease;
        }

        .division-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(75, 80, 197, 0.10);
        }

        .select-arrow {
            position: absolute;
            right: 16px;
            top: 50%;

            width: 9px;
            height: 9px;

            border-right: 2px solid var(--text-medium);
            border-bottom: 2px solid var(--text-medium);

            transform: translateY(-65%) rotate(45deg);
            pointer-events: none;
        }

        .filter-description {
            margin-top: 9px;
            font-size: 12px;
            color: var(--text-light);
        }

        /* TABLE */

        .table-container {
            width: 100%;
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            min-width: 600px;
            border-collapse: collapse;
            font-size: 14px;
        }

        .data-table thead {
            background: #f8f9fd;
        }

        .data-table th {
            padding: 17px 30px;
            text-align: left;

            color: var(--text-medium);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.7px;
            text-transform: uppercase;

            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .data-table td {
            padding: 19px 30px;
            color: var(--text-dark);
            border-bottom: 1px solid var(--border-light);
            vertical-align: middle;
        }

        .data-table tbody tr {
            transition: background 0.2s ease;
        }

        .data-table tbody tr:hover {
            background: #fafaff;
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        .division-label {
            display: inline-block;
            padding: 7px 11px;
            border-radius: 7px;
            background: var(--primary-light);
            color: var(--primary-dark);
            font-size: 12px;
            font-weight: 700;
        }

        .department-label {
            display: inline-block;
            padding: 7px 11px;
            border-radius: 7px;
            background: #f5f6f9;
            color: var(--text-medium);
            font-size: 12px;
            font-weight: 600;
        }

        /* NO RESULT */

        .no-result {
            display: none;
            padding: 60px 25px;
            text-align: center;
        }

        .no-result-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .no-result-description {
            font-size: 13px;
            color: var(--text-light);
            line-height: 1.6;
        }

        /* EMPTY STATE */

        .empty-state {
            padding: 70px 25px;
            text-align: center;
        }

        .empty-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .empty-description {
            font-size: 13px;
            color: var(--text-light);
            line-height: 1.6;
        }

        /* FOOTER */

        .card-footer {
            padding: 18px 30px;
            border-top: 1px solid var(--border-light);
            background: #fcfcff;

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .footer-note {
            font-size: 12px;
            color: var(--text-light);
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            min-height: 40px;
            padding: 0 16px;

            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--white);
            color: var(--text-medium);

            font-size: 12px;
            font-weight: 700;
            text-decoration: none;

            transition:
                background 0.2s ease,
                border-color 0.2s ease,
                transform 0.2s ease;
        }

        .back-button:hover {
            background: #f7f8fc;
            border-color: #cdd1df;
            transform: translateY(-1px);
        }

        .page-footer {
            padding: 20px 24px;
            text-align: center;
            font-size: 12px;
            color: var(--text-light);
        }

        /* TABLET */

        @media (max-width: 900px) {
            .top-header {
                padding: 20px 24px;
            }

            .main-content {
                padding: 32px 20px 45px;
            }

            .card-header,
            .filter-section,
            .data-table th,
            .data-table td,
            .card-footer {
                padding-left: 24px;
                padding-right: 24px;
            }
        }

        /* MOBILE */

        @media (max-width: 600px) {
            .top-header {
                padding: 18px 16px;
            }

            .header-content {
                align-items: flex-start;
                flex-direction: column;
                gap: 12px;
            }

            .brand-title {
                font-size: 21px;
            }

            .brand-subtitle {
                font-size: 12px;
            }

            .header-status {
                font-size: 12px;
            }

            .main-content {
                padding: 25px 14px 35px;
            }

            .page-heading {
                margin-bottom: 22px;
            }

            .page-heading h1 {
                font-size: 25px;
            }

            .page-heading p {
                font-size: 13px;
            }

            .data-card {
                border-radius: 14px;
            }

            .card-header {
                align-items: flex-start;
                flex-direction: column;
                padding: 21px 17px;
                gap: 15px;
            }

            .card-heading h2 {
                font-size: 17px;
            }

            .card-heading p {
                font-size: 12px;
            }

            .record-count {
                font-size: 11px;
            }

            .filter-section {
                padding: 21px 17px;
            }

            .filter-label {
                font-size: 12px;
            }

            .division-select {
                font-size: 13px;
            }

            .data-table {
                min-width: 520px;
            }

            .data-table th {
                padding: 14px 17px;
                font-size: 10px;
            }

            .data-table td {
                padding: 16px 17px;
                font-size: 13px;
            }

            .division-label,
            .department-label {
                font-size: 11px;
                padding: 6px 9px;
            }

            .card-footer {
                align-items: stretch;
                flex-direction: column;
                padding: 18px 17px;
            }

            .footer-note {
                text-align: center;
            }

            .back-button {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="page-wrapper">

        <header class="top-header">
            <div class="header-content">

                <div class="brand-section">
                    <div class="brand-title">
                        Organization Management
                    </div>

                    <div class="brand-subtitle">
                        View divisions and their assigned departments
                    </div>
                </div>

                <div class="header-status">
                    <span class="status-dot"></span>
                    System Available
                </div>

            </div>
        </header>


        <main class="main-content">

            <div class="page-heading">
                <h1>Department and Division List</h1>

                <p>
                    Select a division to view only the departments assigned
                    under that division.
                </p>
            </div>


            <section class="data-card">

                <div class="card-header">

                    <div class="card-heading">
                        <h2>Organization Structure</h2>

                        <p>
                            Filter the list by selecting a specific division.
                        </p>
                    </div>

                    <div class="record-count" id="recordCount">
                        <?= is_array($data) ? count($data) : 0 ?> Records
                    </div>

                </div>


                <!-- DIVISION FILTER -->

                <div class="filter-section">

                    <label for="divisionFilter" class="filter-label">
                        Select Division
                    </label>

                    <div class="select-wrapper">

                        <select id="divisionFilter" class="division-select">

                            <option value="all">
                                All Divisions
                            </option>

                            <?php
                            $divisionList = [];

                            if (!empty($data)) {
                                foreach ($data as $row) {
                                    $divisionName = $row["Division_name"];

                                    if (!in_array($divisionName, $divisionList)) {
                                        $divisionList[] = $divisionName;
                                    }
                                }
                            }

                            foreach ($divisionList as $divisionName):
                            ?>

                                <option value="<?= htmlspecialchars($divisionName) ?>">
                                    <?= htmlspecialchars($divisionName) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                        <span class="select-arrow"></span>

                    </div>

                    <p class="filter-description">
                        Departments displayed below will automatically update
                        based on the selected division.
                    </p>

                </div>


                <?php if ($data): ?>

                    <div class="table-container">

                        <table class="data-table">

                            <thead>
                                <tr>
                                    <th>Division Name</th>
                                    <th>Department Name</th>
                                </tr>
                            </thead>

                            <tbody id="departmentTableBody">

                                <?php foreach ($data as $datas): ?>

                                    <tr
                                        class="department-row"
                                        data-division="<?= htmlspecialchars($datas["Division_name"]) ?>"
                                    >

                                        <td>
                                            <span class="division-label">
                                                <?= htmlspecialchars($datas["Division_name"]) ?>
                                            </span>
                                        </td>

                                        <td>
                                            <span class="department-label">
                                                <?= htmlspecialchars($datas["Department_name"]) ?>
                                            </span>
                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>


                    <div class="no-result" id="noResult">

                        <div class="no-result-title">
                            No Departments Found
                        </div>

                        <div class="no-result-description">
                            There are no departments assigned under the
                            selected division.
                        </div>

                    </div>

                <?php else: ?>

                    <div class="empty-state">

                        <div class="empty-title">
                            No Data Available
                        </div>

                        <div class="empty-description">
                            There are currently no divisions or departments
                            available to display.
                        </div>

                    </div>

                <?php endif; ?>


                <div class="card-footer">

                    <div class="footer-note">
                        Organization structure records
                    </div>

                    <a
                        href="dashboard"
                        class="back-button"
                    >
                        Back
                    </a>

                </div>

            </section>

        </main>


        <footer class="page-footer">
            Organization Management System
        </footer>

    </div>


    <script>
        const divisionFilter = document.getElementById("divisionFilter");
        const departmentRows = document.querySelectorAll(".department-row");
        const noResult = document.getElementById("noResult");
        const recordCount = document.getElementById("recordCount");

        if (divisionFilter) {

            divisionFilter.addEventListener("change", function() {

                const selectedDivision = this.value;
                let visibleCount = 0;

                departmentRows.forEach(function(row) {

                    const rowDivision = row.getAttribute("data-division");

                    if (
                        selectedDivision === "all" ||
                        rowDivision === selectedDivision
                    ) {
                        row.style.display = "";
                        visibleCount++;
                    } else {
                        row.style.display = "none";
                    }

                });

                if (visibleCount === 0) {
                    noResult.style.display = "block";
                } else {
                    noResult.style.display = "none";
                }

                recordCount.textContent = visibleCount + " Records";

            });

        }
    </script>

</body>

</html>