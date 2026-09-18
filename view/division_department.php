<?php

include "../config/db.php";
include "../model/include_model/includeDepartmentDivision.Model.php";

$includeModel = new IncludeDivisionDepartment_Model($conn);

$department = $includeModel->gerDepartments();
$division = $includeModel->getDivisions();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Organization Selection</title>

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
            --text-medium: #5f647b;
            --text-light: #8a8fa5;

            --border: #e2e5ef;
            --border-light: #edf0f6;

            --success: #20a66a;
            --danger: #dc3545;

            --shadow-sm: 0 4px 14px rgba(31, 38, 135, 0.05);
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
            color: var(--text-dark);
            letter-spacing: -0.5px;
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
            margin-bottom: 30px;
        }

        .page-heading h1 {
            font-size: 30px;
            line-height: 1.25;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .page-heading p {
            font-size: 15px;
            line-height: 1.7;
            color: var(--text-medium);
            max-width: 720px;
        }

        /* FORM CARD */

        .organization-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 18px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .card-header {
            padding: 26px 30px;
            border-bottom: 1px solid var(--border-light);
            background: #fcfcff;
        }

        .card-header h2 {
            font-size: 19px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--text-dark);
        }

        .card-header p {
            font-size: 13px;
            line-height: 1.6;
            color: var(--text-medium);
        }

        .card-body {
            padding: 30px;
        }

        .form-section {
            margin-bottom: 34px;
        }

        .form-section:last-child {
            margin-bottom: 0;
        }

        .section-title {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 18px;
        }

        .section-number {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 9px;
            background: var(--primary-light);
            color: var(--primary);
            font-size: 14px;
            font-weight: 700;
        }

        .section-title-content h3 {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .section-title-content p {
            font-size: 13px;
            color: var(--text-medium);
            line-height: 1.5;
        }

        /* DIVISION SELECT */

        .select-wrapper {
            position: relative;
        }

        .select-wrapper select {
            width: 100%;
            height: 50px;
            appearance: none;
            -webkit-appearance: none;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--white);
            color: var(--text-dark);
            font-size: 14px;
            padding: 0 45px 0 15px;
            outline: none;
            cursor: pointer;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .select-wrapper select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(75, 80, 197, 0.10);
        }

        .select-arrow {
            position: absolute;
            right: 17px;
            top: 50%;
            width: 9px;
            height: 9px;
            border-right: 2px solid var(--text-medium);
            border-bottom: 2px solid var(--text-medium);
            transform: translateY(-65%) rotate(45deg);
            pointer-events: none;
        }

        /* DEPARTMENT GRID */

        .department-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .department-option {
            position: relative;
        }

        .department-option input[type="checkbox"] {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .department-label {
            min-height: 72px;
            width: 100%;
            display: flex;
            align-items: center;
            position: relative;
            padding: 15px 42px 15px 16px;
            border: 1px solid var(--border);
            border-radius: 11px;
            background: var(--white);
            color: var(--text-dark);
            font-size: 13px;
            font-weight: 600;
            line-height: 1.4;
            cursor: pointer;
            transition:
                border-color 0.2s ease,
                background 0.2s ease,
                box-shadow 0.2s ease,
                transform 0.2s ease;
        }

        .department-label:hover {
            border-color: #b9bdf0;
            background: #fafaff;
            transform: translateY(-1px);
        }

        .department-label::after {
            content: "";
            position: absolute;
            right: 16px;
            top: 50%;
            width: 18px;
            height: 18px;
            border: 1.5px solid #c9cddd;
            border-radius: 5px;
            background: var(--white);
            transform: translateY(-50%);
            transition:
                background 0.2s ease,
                border-color 0.2s ease;
        }

        .department-label::before {
            content: "";
            position: absolute;
            right: 21px;
            top: 50%;
            width: 8px;
            height: 4px;
            border-left: 2px solid var(--white);
            border-bottom: 2px solid var(--white);
            opacity: 0;
            z-index: 2;
            transform: translateY(-70%) rotate(-45deg);
            transition: opacity 0.2s ease;
        }

        .department-option input[type="checkbox"]:checked + .department-label {
            border-color: var(--primary);
            background: var(--primary-light);
            box-shadow: 0 4px 12px rgba(75, 80, 197, 0.08);
        }

        .department-option input[type="checkbox"]:checked + .department-label::after {
            border-color: var(--primary);
            background: var(--primary);
        }

        .department-option input[type="checkbox"]:checked + .department-label::before {
            opacity: 1;
        }

        .department-option input[type="checkbox"]:focus-visible + .department-label {
            outline: 3px solid rgba(75, 80, 197, 0.20);
            outline-offset: 2px;
        }

        /* EMPTY STATE */

        .empty-state {
            padding: 25px;
            border: 1px dashed var(--border);
            border-radius: 11px;
            background: #fcfcff;
            text-align: center;
            color: var(--text-light);
            font-size: 13px;
        }

        /* FORM FOOTER */

        .card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 22px 30px;
            border-top: 1px solid var(--border-light);
            background: #fcfcff;
        }

        .form-note {
            font-size: 12px;
            line-height: 1.5;
            color: var(--text-light);
        }

        .form-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn {
            min-height: 44px;
            padding: 0 20px;
            border-radius: 9px;
            border: 1px solid transparent;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition:
                background 0.2s ease,
                border-color 0.2s ease,
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: var(--white);
            border-color: var(--border);
            color: var(--text-medium);
        }

        .btn-secondary:hover {
            background: #f7f8fc;
            border-color: #cdd1df;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
            box-shadow: 0 5px 14px rgba(75, 80, 197, 0.20);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            box-shadow: 0 7px 18px rgba(75, 80, 197, 0.26);
        }

        /* FOOTER */

        .page-footer {
            padding: 20px 24px;
            text-align: center;
            font-size: 12px;
            color: var(--text-light);
        }

        /* RESPONSIVE TABLET */

        @media (max-width: 900px) {
            .top-header {
                padding: 20px 24px;
            }

            .main-content {
                padding: 32px 20px 45px;
            }

            .department-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .card-header,
            .card-body,
            .card-footer {
                padding-left: 24px;
                padding-right: 24px;
            }
        }

        /* RESPONSIVE MOBILE */

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

            .organization-card {
                border-radius: 14px;
            }

            .card-header,
            .card-body,
            .card-footer {
                padding: 21px 17px;
            }

            .card-header h2 {
                font-size: 17px;
            }

            .section-title {
                gap: 10px;
            }

            .section-title-content h3 {
                font-size: 15px;
            }

            .section-title-content p {
                font-size: 12px;
            }

            .department-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .department-label {
                min-height: 64px;
            }

            .card-footer {
                align-items: stretch;
                flex-direction: column;
            }

            .form-note {
                text-align: center;
            }

            .form-actions {
                width: 100%;
                display: grid;
                grid-template-columns: 1fr 1fr;
            }

            .btn {
                width: 100%;
                padding: 0 12px;
            }
        }

        @media (max-width: 380px) {
            .form-actions {
                grid-template-columns: 1fr;
            }
        }

        /* BACK TO DASHBOARD BUTTON */
        .back-dashboard {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 22px;
            padding: 10px 16px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--white);
            color: var(--text-dark);
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            transition: all 0.2s ease;
            box-shadow: var(--shadow-sm);
        }

        .back-dashboard:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary);
            transform: translateX(-2px);
        }

        .back-dashboard span:first-child {
            font-size: 18px;
            line-height: 1;
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
                        Configure divisions and department assignments
                    </div>
                </div>

                <div class="header-status">
                    <span class="status-dot"></span>
                    System Available
                </div>

            </div>
        </header>


        <main class="main-content">
            <!-- BACK TO DASHBOARD -->
            <a href="dashboard" class="back-dashboard">
                <span>←</span>
                <span>Back to Dashboard</span>
            </a>


            <div class="page-heading">
                <h1>Organization Selection</h1>

                <p>
                    Select a division and assign the departments that belong
                    to your organization structure.
                </p>
            </div>


            <form action="regulator" method="POST">

            <input type="hidden" name="action" value="create">

                <div class="organization-card">

                    <div class="card-header">
                        <h2>Organization Details</h2>

                        <p>
                            Complete the required information below to configure
                            your organization assignment.
                        </p>
                    </div>


                    <div class="card-body">

                        <!-- DIVISION SECTION -->

                        <section class="form-section">

                            <div class="section-title">

                                <div class="section-number">
                                    01
                                </div>

                                <div class="section-title-content">
                                    <h3>Select Division</h3>

                                    <p>
                                        Choose the main division where the
                                        departments will be assigned.
                                    </p>
                                </div>

                            </div>


                            <div class="select-wrapper">

                                <select
                                    name="division_syntax"
                                    id="division_syntax"
                                    required
                                >

                                    <option value="" selected disabled>
                                        Select a division
                                    </option>

                                    <?php foreach ($division as $div): ?>

                                        <option value="<?= htmlspecialchars($div['Division_id']) ?>">
                                            <?= htmlspecialchars($div['Division_name']) ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>

                                <span class="select-arrow"></span>

                            </div>

                        </section>


                        <!-- DEPARTMENT SECTION -->

                        <section class="form-section">

                            <div class="section-title">

                                <div class="section-number">
                                    02
                                </div>

                                <div class="section-title-content">
                                    <h3>Select Departments</h3>

                                    <p>
                                        Select one or more departments under
                                        the chosen division.
                                    </p>
                                </div>

                            </div>


                            <?php if (!empty($department)): ?>

                                <div class="department-grid">

                                    <?php foreach ($department as $dept): ?>

                                        <div class="department-option">

                                            <input
                                                type="checkbox"
                                                name="department_syntax[]"
                                                id="department_<?= htmlspecialchars($dept['Department_id']) ?>"
                                                value="<?= htmlspecialchars($dept['Department_id']) ?>"
                                            >

                                            <label
                                                class="department-label"
                                                for="department_<?= htmlspecialchars($dept['Department_id']) ?>"
                                            >
                                                <?= htmlspecialchars($dept['Department_name']) ?>
                                            </label>

                                        </div>

                                    <?php endforeach; ?>

                                </div>

                            <?php else: ?>

                                <div class="empty-state">
                                    No departments available.
                                </div>

                            <?php endif; ?>

                        </section>

                    </div>


                    <div class="card-footer">

                        <div class="form-note">
                            Fields marked as required must be completed.
                        </div>

                        <div class="form-actions">

                            <button
                                type="reset"
                                class="btn btn-secondary"
                            >
                                Clear Selection
                            </button>

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Save Assignment
                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </main>


        <footer class="page-footer">
            Organization Management System
        </footer>

    </div>

</body>

</html>