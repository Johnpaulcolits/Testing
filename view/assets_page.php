<?php 

include "../config/db.php";
include "../model/include_model/IncludeAssets.Model.php";
include "../model/include_model/IncludeTagging.Model.php";
include "../model/include_model/InludeAssetsCategory.Model.php";
include "../model/include_model/InludeUserDepartment.Model.php";


$includeModel = new IncludeAssets_Model($conn);
$includeTaggingModel = new IncludeTagging_Model($conn);
$includeAssetsCategoryModel = new IncludeAssetsCategory_Model($conn);
$includeUserDepartmentModel = new IncludeUserDepartment_Model($conn);


$assets = $includeModel->getAllAssets();
$tagging = $includeTaggingModel->getAllTagging();
$assetsCategories = $includeAssetsCategoryModel->geAssetsCategory();
$userDepartments = $includeUserDepartmentModel->getAllUserDepartment();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset Assignment | Asset Management</title>

    <style>
        :root {
            --primary: #4B50C5;
            --primary-dark: #303A9B;
            --primary-light: #EEF0FF;

            --text: #20243A;
            --muted: #747A92;

            --background: #F5F7FB;
            --white: #FFFFFF;
            --border: #E5E7F0;

            --success: #1F9D68;
            --success-light: #E9F8F0;

            --warning: #D99000;
            --warning-light: #FFF6DD;

            --shadow: 0 8px 30px rgba(35, 40, 80, 0.06);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif;
            background: var(--background);
            color: var(--text);
        }

        button,
        input,
        select {
            font: inherit;
        }

        button {
            cursor: pointer;
        }

        .page-wrapper {
            max-width: 1250px;
            margin: 0 auto;
            padding: 32px;
        }


        /* BACK TO DASHBOARD BUTTON */
        .back-dashboard {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            padding: 10px 16px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--white);
            color: var(--text);
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            transition: all 0.2s ease;
            box-shadow: 0 3px 10px rgba(35, 40, 80, 0.04);
        }

        .back-dashboard:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary);
            transform: translateX(-2px);
        }

        /* =========================
           PAGE HEADER
        ========================= */

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 28px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 10px;
        }

        .breadcrumb span:last-child {
            color: var(--primary);
            font-weight: 600;
        }

        .page-title {
            margin: 0;
            font-size: 30px;
            font-weight: 750;
            letter-spacing: -0.7px;
            color: var(--text);
        }

        .page-description {
            margin: 8px 0 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
        }

        .header-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 10px;
            background: var(--white);
            border: 1px solid var(--border);
            color: var(--muted);
            font-size: 13px;
            white-space: nowrap;
        }

        .header-badge .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--success);
        }

        /* =========================
           MAIN CARD
        ========================= */

        .assignment-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 18px;
            box-shadow: var(--shadow);
            overflow: visible;
        }

        .card-header {
            padding: 24px 28px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .card-header-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .card-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
        }

        .card-header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
        }

        .card-header p {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: 13px;
        }

        .required-note {
            color: var(--muted);
            font-size: 12px;
        }

        .required-note span {
            color: #E05252;
        }

        .card-body {
            padding: 28px;
        }

        /* =========================
           FORM GRID
        ========================= */

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 24px;
        }

        .form-group {
            position: relative;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 9px;
            font-size: 13px;
            font-weight: 700;
            color: var(--text);
        }

        .form-label .required {
            color: #E05252;
        }

        .form-helper {
            margin-top: 7px;
            font-size: 12px;
            color: var(--muted);
        }

        /* =========================
           CUSTOM SEARCHABLE SELECT
        ========================= */

        .custom-select {
            position: relative;
        }

        .select-trigger {
            width: 100%;
            min-height: 52px;
            padding: 0 15px;
            border: 1px solid var(--border);
            border-radius: 11px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            color: var(--muted);
            transition: all 0.2s ease;
            user-select: none;
        }

        .select-trigger:hover {
            border-color: #BFC4E8;
        }

        .custom-select.open .select-trigger {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(75, 80, 197, 0.10);
        }

        .select-trigger.selected {
            color: var(--text);
        }

        .select-trigger-content {
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 0;
        }

        .select-leading-icon {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            background: #F5F6FC;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            flex-shrink: 0;
            font-size: 15px;
        }

        .select-trigger.selected .select-leading-icon {
            background: var(--primary-light);
            color: var(--primary);
        }

        .select-text {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            min-width: 0;
        }

        .select-placeholder {
            font-size: 14px;
            color: var(--muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .select-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .select-subtext {
            font-size: 11px;
            color: var(--muted);
            margin-top: 2px;
        }

        .select-arrow {
            color: var(--muted);
            font-size: 12px;
            transition: transform 0.2s ease;
            flex-shrink: 0;
        }

        .custom-select.open .select-arrow {
            transform: rotate(180deg);
        }

        .select-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(30, 35, 75, 0.15);
            z-index: 100;
            display: none;
            overflow: hidden;
        }

        .custom-select.open .select-dropdown {
            display: block;
        }

        .search-wrapper {
            padding: 10px;
            border-bottom: 1px solid var(--border);
            background: #FCFCFE;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            width: 100%;
            height: 40px;
            padding: 0 12px 0 36px;
            border: 1px solid var(--border);
            border-radius: 8px;
            outline: none;
            font-size: 13px;
            background: #fff;
        }

        .search-box input:focus {
            border-color: var(--primary);
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 13px;
        }

        .options-list {
            max-height: 230px;
            overflow-y: auto;
            padding: 6px;
        }

        .select-option {
            display: flex;
            align-items: center;
            gap: 11px;
            width: 100%;
            padding: 11px 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .select-option:hover {
            background: var(--primary-light);
        }

        .option-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #F5F6FC;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            flex-shrink: 0;
        }

        .select-option:hover .option-icon {
            background: #fff;
            color: var(--primary);
        }

        .option-info {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .option-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .option-subtitle {
            font-size: 11px;
            color: var(--muted);
            margin-top: 3px;
        }

        .no-options {
            padding: 22px 10px;
            text-align: center;
            color: var(--muted);
            font-size: 13px;
        }

        /* =========================
           QUANTITY INPUT
        ========================= */

        .quantity-control {
            display: flex;
            align-items: center;
            height: 52px;
            border: 1px solid var(--border);
            border-radius: 11px;
            overflow: hidden;
            background: #fff;
        }

        .quantity-btn {
            width: 52px;
            height: 100%;
            border: none;
            background: #F8F9FC;
            color: var(--primary);
            font-size: 20px;
            transition: background 0.2s ease;
        }

        .quantity-btn:hover {
            background: var(--primary-light);
        }

        .quantity-control input {
            flex: 1;
            min-width: 0;
            height: 100%;
            border: none;
            outline: none;
            text-align: center;
            font-size: 17px;
            font-weight: 700;
            color: var(--text);
            background: #fff;
        }

        .quantity-control:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(75, 80, 197, 0.10);
        }

        /* =========================
           SELECTION SUMMARY
        ========================= */

        .selection-summary {
            margin-top: 28px;
            padding: 18px;
            border: 1px solid #E7E8F6;
            background: #FAFAFF;
            border-radius: 13px;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .summary-item {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .summary-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #fff;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 1px solid #ECECFA;
        }

        .summary-info {
            min-width: 0;
        }

        .summary-label {
            font-size: 11px;
            color: var(--muted);
            margin-bottom: 4px;
        }

        .summary-value {
            font-size: 13px;
            font-weight: 700;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* =========================
           PREVIEW SECTION
        ========================= */

        .preview-section {
            display: none;
            margin-top: 32px;
            border-top: 1px solid var(--border);
            padding-top: 28px;
        }

        .preview-section.visible {
            display: block;
        }

        .preview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 18px;
        }

        .preview-title-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .preview-title-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--success-light);
            color: var(--success);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .preview-title {
            margin: 0;
            font-size: 17px;
            font-weight: 700;
        }

        .preview-subtitle {
            margin: 4px 0 0;
            font-size: 12px;
            color: var(--muted);
        }

        .preview-count {
            display: inline-flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 8px;
            background: var(--primary-light);
            color: var(--primary);
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .table-container {
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow-x: auto;
        }

        .asset-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 760px;
        }

        .asset-table thead th {
            padding: 14px 16px;
            background: #F8F9FC;
            color: var(--muted);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 750;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        .asset-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #F0F1F5;
            vertical-align: middle;
            font-size: 13px;
        }

        .asset-table tbody tr:last-child td {
            border-bottom: none;
        }

        .asset-table tbody tr:hover {
            background: #FCFCFF;
        }

        .row-number {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: var(--primary-light);
            color: var(--primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 750;
            font-size: 12px;
        }

        .asset-cell {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .asset-cell-icon {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: #F2F3FA;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .asset-cell-info {
            min-width: 0;
        }

        .asset-cell-name {
            font-weight: 700;
            color: var(--text);
        }

        .asset-cell-sub {
            font-size: 11px;
            color: var(--muted);
            margin-top: 3px;
        }

        .user-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 750;
            flex-shrink: 0;
        }

        .user-info {
            min-width: 0;
        }

        .user-name {
            font-weight: 650;
            color: var(--text);
        }

        .user-sub {
            font-size: 11px;
            color: var(--muted);
            margin-top: 3px;
        }

        /* =========================
           CONDITION SELECT
        ========================= */

        .condition-select {
            min-width: 180px;
            padding: 10px 12px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: #fff;
            color: var(--text);
            font-size: 13px;
            outline: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .condition-select:hover {
            border-color: #BFC4E8;
        }

        .condition-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(75, 80, 197, 0.10);
        }

        /* =========================
           CARD FOOTER
        ========================= */

        .card-footer {
            padding: 20px 28px;
            border-top: 1px solid var(--border);
            background: #FCFCFE;
            border-radius: 0 0 18px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .footer-note {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--muted);
            font-size: 12px;
        }

        .footer-note-icon {
            color: var(--primary);
            font-size: 15px;
        }

        .footer-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn {
            min-height: 44px;
            padding: 0 18px;
            border-radius: 9px;
            border: none;
            font-size: 13px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .btn-secondary {
            background: #fff;
            color: var(--muted);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: #F7F8FC;
            color: var(--text);
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 12px rgba(75, 80, 197, 0.18);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-success {
            background: var(--success);
            color: #fff;
            box-shadow: 0 4px 12px rgba(31, 157, 104, 0.18);
        }

        .btn-success:hover {
            background: #188457;
            transform: translateY(-1px);
        }

        .btn:disabled {
            opacity: 0.55;
            cursor: not-allowed;
            transform: none;
        }

        /* =========================
           TOAST
        ========================= */

        .toast {
            position: fixed;
            right: 25px;
            bottom: 25px;
            background: #20243A;
            color: #fff;
            padding: 14px 18px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
            transform: translateY(20px);
            opacity: 0;
            pointer-events: none;
            transition: all 0.25s ease;
            z-index: 999;
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .toast.success .toast-icon {
            color: #6EE7B7;
        }

        .toast.error .toast-icon {
            color: #FCA5A5;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 900px) {

            .page-wrapper {
                padding: 22px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .selection-summary {
                grid-template-columns: 1fr;
            }

            .card-footer {
                flex-direction: column;
                align-items: stretch;
            }

            .footer-actions {
                justify-content: flex-end;
            }

        }

        @media (max-width: 600px) {

            .page-wrapper {
                padding: 14px;
            }

            .page-header {
                flex-direction: column;
                margin-bottom: 20px;
            }

            .page-title {
                font-size: 24px;
            }

            .header-badge {
                align-self: flex-start;
            }

            .card-header,
            .card-body,
            .card-footer {
                padding: 20px;
            }

            .card-header {
                align-items: flex-start;
            }

            .required-note {
                display: none;
            }

            .preview-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .footer-actions {
                width: 100%;
            }

            .footer-actions .btn {
                flex: 1;
            }

        }



        /* =========================================
   RESPONSIVE DESIGN
========================================= */

/* Prevent horizontal overflow */
html,
body {
    width: 100%;
    max-width: 100%;
    overflow-x: hidden;
}

/* Main page wrapper */
.page-container,
.asset-container,
.form-container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px;
}

/* Form card */
.form-card,
.asset-form-card,
.card {
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
}

/* Form grid */
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}

/* Full width field */
.form-group.full-width {
    grid-column: 1 / -1;
}

/* Input and select responsiveness */
input,
select,
textarea,
button {
    max-width: 100%;
    box-sizing: border-box;
}

/* Buttons container */
.form-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

/* Preview table wrapper */
.preview-table-wrapper,
.table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border-radius: 12px;
}

/* Preview table */
.preview-table {
    width: 100%;
    min-width: 650px;
    border-collapse: collapse;
}

/* Quantity control */
.quantity-control {
    display: flex;
    align-items: center;
    gap: 8px;
}

.quantity-control button {
    flex-shrink: 0;
}

/* Searchable dropdown menu */
.dropdown-menu,
.custom-dropdown-menu {
    max-width: 100%;
    width: 100%;
    box-sizing: border-box;
}

/* =========================================
   TABLET
========================================= */

@media (max-width: 900px) {
    .page-container,
    .asset-container,
    .form-container {
        padding: 20px;
    }

    .form-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .card-header h1,
    .card-header h2,
    .page-title {
        font-size: 24px;
    }

    .form-card,
    .asset-form-card {
        padding: 20px;
    }
}

/* =========================================
   MOBILE DEVICES
========================================= */

@media (max-width: 600px) {
    .page-container,
    .asset-container,
    .form-container {
        padding: 12px;
    }

    .form-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .form-group.full-width {
        grid-column: auto;
    }

    .form-card,
    .asset-form-card,
    .card {
        padding: 16px;
        border-radius: 14px;
    }

    .page-title,
    .card-header h1,
    .card-header h2 {
        font-size: 21px;
        line-height: 1.3;
    }

    .page-subtitle,
    .card-header p {
        font-size: 13px;
        line-height: 1.5;
    }

    label {
        font-size: 13px;
        margin-bottom: 6px;
    }

    input,
    select,
    textarea {
        width: 100%;
        min-height: 46px;
        font-size: 14px;
    }

    button {
        min-height: 44px;
        font-size: 14px;
    }

    .form-actions {
        flex-direction: column;
        width: 100%;
        gap: 10px;
    }

    .form-actions button,
    .form-actions a,
    .form-actions input[type="submit"] {
        width: 100%;
        justify-content: center;
    }

    .quantity-control {
        width: 100%;
    }

    .quantity-control input {
        flex: 1;
        text-align: center;
    }

    .quantity-control button {
        width: 44px;
        min-width: 44px;
        padding: 0;
    }

    .preview-table-wrapper,
    .table-responsive {
        margin-top: 12px;
        border: 1px solid #e5e7eb;
    }

    .preview-table {
        min-width: 700px;
        font-size: 13px;
    }

    .preview-table th,
    .preview-table td {
        padding: 10px;
        white-space: nowrap;
    }

    .preview-table select {
        min-width: 160px;
    }

    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .section-header button {
        width: 100%;
    }

    .dropdown-menu,
    .custom-dropdown-menu {
        max-height: 240px;
        overflow-y: auto;
    }
}

/* =========================================
   SMALL MOBILE DEVICES
========================================= */

@media (max-width: 380px) {
    .page-container,
    .asset-container,
    .form-container {
        padding: 8px;
    }

    .form-card,
    .asset-form-card,
    .card {
        padding: 12px;
        border-radius: 12px;
    }

    .page-title,
    .card-header h1,
    .card-header h2 {
        font-size: 19px;
    }

    input,
    select,
    textarea {
        min-height: 44px;
        font-size: 13px;
    }

    button {
        font-size: 13px;
    }

    .preview-table {
        min-width: 680px;
    }
}
    </style>
</head>

<body>

<div class="page-wrapper">

    <!-- BACK TO DASHBOARD -->
    <a href="dashboard" class="back-dashboard">
        <span>←</span>
        <span>Back to Dashboard</span>
    </a>

    <!-- PAGE HEADER -->
    <div class="page-header">

        <div>
            <div class="breadcrumb">
                <span>Asset Management</span>
                <span>›</span>
                <span>Assignments</span>
            </div>

            <h1 class="page-title">Assign Assets</h1>

            <p class="page-description">
                Assign physical assets to users and record the condition of each item.
            </p>
        </div>

        <div class="header-badge">
            <span class="status-dot"></span>
            Asset Assignment
        </div>

    </div>


    <!-- ASSIGNMENT CARD -->
    <div class="assignment-card">

        <div class="card-header">

            <div class="card-header-left">

                <div class="card-icon">
                    ▣
                </div>

                <div>
                    <h2>Assignment Information</h2>
                    <p>Choose the asset and the person responsible for it.</p>
                </div>

            </div>

            <div class="required-note">
                <span>*</span> Required fields
            </div>

        </div>


        <form action="regulator" method="POST" id="assetForm">

            <div class="card-body">

                <div class="form-grid">

                    <!-- ASSET CATEGORY -->
                    <div class="form-group">

                        <label class="form-label">
                            Asset Category <span class="required">*</span>
                        </label>

                        <div class="custom-select" id="categorySelect">

                            <input type="hidden"
                                   name="assets_category_syntax"
                                   id="categoryValue"
                                   required>

                            <button type="button"
                                    class="select-trigger"
                                    onclick="toggleDropdown('categorySelect')">

                                <div class="select-trigger-content">

                                    <div class="select-leading-icon">
                                        ▦
                                    </div>

                                    <div class="select-text">
                                        <span class="select-placeholder">
                                            Select asset category
                                        </span>
                                    </div>

                                </div>

                                <span class="select-arrow">▼</span>

                            </button>


                            <div class="select-dropdown">

                                <div class="search-wrapper">
                                    <div class="search-box">
                                        <span class="search-icon">⌕</span>
                                        <input type="text"
                                               placeholder="Search category..."
                                               oninput="filterOptions('categorySelect', this.value)">
                                    </div>
                                </div>

                                <div class="options-list">

                                    <?php if(!empty($assetsCategories)): ?>

                                        <?php foreach($assetsCategories as $allassetsCategories): ?>

                                            <div class="select-option"
                                                 data-value="<?= htmlspecialchars($allassetsCategories['Assets_category_id']) ?>"
                                                 data-search="<?= htmlspecialchars(strtolower($allassetsCategories['Assets_category_name'])) ?>"
                                                 onclick="selectOption(
                                                     'categorySelect',
                                                     'categoryValue',
                                                     '<?= htmlspecialchars($allassetsCategories['Assets_category_id'], ENT_QUOTES) ?>',
                                                     '<?= htmlspecialchars($allassetsCategories['Assets_category_name'], ENT_QUOTES) ?>',
                                                     'Asset Category'
                                                 )">

                                                <div class="option-icon">▦</div>

                                                <div class="option-info">
                                                    <span class="option-title">
                                                        <?= htmlspecialchars($allassetsCategories['Assets_category_name']) ?>
                                                    </span>

                                                    <span class="option-subtitle">
                                                        Asset Category
                                                    </span>
                                                </div>

                                            </div>

                                        <?php endforeach; ?>

                                    <?php else: ?>

                                        <div class="no-options">
                                            No asset categories available.
                                        </div>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>

                        <div class="form-helper">
                            Select the classification of the asset.
                        </div>

                    </div>


                    <!-- ASSET -->
                    <div class="form-group">

                        <label class="form-label">
                            Asset <span class="required">*</span>
                        </label>

                        <div class="custom-select" id="assetSelect">

                            <input type="hidden"
                                   name="asset_name_syntax"
                                   id="assetValue"
                                   required>

                            <button type="button"
                                    class="select-trigger"
                                    onclick="toggleDropdown('assetSelect')">

                                <div class="select-trigger-content">

                                    <div class="select-leading-icon">
                                        ▣
                                    </div>

                                    <div class="select-text">
                                        <span class="select-placeholder">
                                            Select asset
                                        </span>
                                    </div>

                                </div>

                                <span class="select-arrow">▼</span>

                            </button>


                            <div class="select-dropdown">

                                <div class="search-wrapper">
                                    <div class="search-box">
                                        <span class="search-icon">⌕</span>
                                        <input type="text"
                                               placeholder="Search asset..."
                                               oninput="filterOptions('assetSelect', this.value)">
                                    </div>
                                </div>

                                <div class="options-list">

                                    <?php if(!empty($assets)): ?>

                                        <?php foreach($assets as $allassets): ?>

                                            <div class="select-option"
                                                 data-value="<?= htmlspecialchars($allassets['Assets_id']) ?>"
                                                 data-search="<?= htmlspecialchars(strtolower($allassets['Asset_name'])) ?>"
                                                 onclick="selectOption(
                                                     'assetSelect',
                                                     'assetValue',
                                                     '<?= htmlspecialchars($allassets['Assets_id'], ENT_QUOTES) ?>',
                                                     '<?= htmlspecialchars($allassets['Asset_name'], ENT_QUOTES) ?>',
                                                     'Asset'
                                                 )">

                                                <div class="option-icon">▣</div>

                                                <div class="option-info">
                                                    <span class="option-title">
                                                        <?= htmlspecialchars($allassets['Asset_name']) ?>
                                                    </span>

                                                    <span class="option-subtitle">
                                                        Available asset
                                                    </span>
                                                </div>

                                            </div>

                                        <?php endforeach; ?>

                                    <?php else: ?>

                                        <div class="no-options">
                                            No assets available.
                                        </div>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>

                        <div class="form-helper">
                            Choose the asset to be assigned.
                        </div>

                    </div>


                    <!-- USER -->
                    <div class="form-group">

                        <label class="form-label">
                            Assigned To <span class="required">*</span>
                        </label>

                        <div class="custom-select" id="userSelect">

                            <input type="hidden"
                                   name="user_syntax"
                                   id="userValue"
                                   required>

                            <button type="button"
                                    class="select-trigger"
                                    onclick="toggleDropdown('userSelect')">

                                <div class="select-trigger-content">

                                    <div class="select-leading-icon">
                                        ♙
                                    </div>

                                    <div class="select-text">
                                        <span class="select-placeholder">
                                            Select user or department
                                        </span>
                                    </div>

                                </div>

                                <span class="select-arrow">▼</span>

                            </button>


                            <div class="select-dropdown">

                                <div class="search-wrapper">
                                    <div class="search-box">
                                        <span class="search-icon">⌕</span>
                                        <input type="text"
                                               placeholder="Search user..."
                                               oninput="filterOptions('userSelect', this.value)">
                                    </div>
                                </div>

                                <div class="options-list">

                                    <?php if(!empty($userDepartments)): ?>

                                        <?php foreach($userDepartments as $alluserDepartments): ?>

                                            <?php
                                                $fullName =
                                                    $alluserDepartments['User_fname'] . ' ' .
                                                    $alluserDepartments['User_lname'];
                                            ?>

                                            <div class="select-option"
                                                 data-value="<?= htmlspecialchars($alluserDepartments['User_department_id']) ?>"
                                                 data-search="<?= htmlspecialchars(strtolower($fullName)) ?>"
                                                 onclick="selectOption(
                                                     'userSelect',
                                                     'userValue',
                                                     '<?= htmlspecialchars($alluserDepartments['User_department_id'], ENT_QUOTES) ?>',
                                                     '<?= htmlspecialchars($fullName, ENT_QUOTES) ?>',
                                                     'Assigned User'
                                                 )">

                                                <div class="option-icon">♙</div>

                                                <div class="option-info">
                                                    <span class="option-title">
                                                        <?= htmlspecialchars($fullName) ?>
                                                    </span>

                                                    <span class="option-subtitle">
                                                        User / Department
                                                    </span>
                                                </div>

                                            </div>

                                        <?php endforeach; ?>

                                    <?php else: ?>

                                        <div class="no-options">
                                            No users available.
                                        </div>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>

                        <div class="form-helper">
                            Select the person responsible for the asset.
                        </div>

                    </div>


                    <!-- QUANTITY -->
                    <div class="form-group">

                        <label class="form-label">
                            Quantity <span class="required">*</span>
                        </label>

                        <div class="quantity-control">

                            <button type="button"
                                    class="quantity-btn"
                                    onclick="changeQuantity(-1)">
                                −
                            </button>

                            <input type="number"
                                   name="quantity_syntax"
                                   id="quantity_syntax"
                                   min="1"
                                   max="1000"
                                   value="1"
                                   required
                                   oninput="updateQuantitySummary()">

                            <button type="button"
                                    class="quantity-btn"
                                    onclick="changeQuantity(1)">
                                +
                            </button>

                        </div>

                        <div class="form-helper">
                            Enter the number of physical assets to assign.
                        </div>

                    </div>

                </div>


                <!-- SELECTION SUMMARY -->
                <div class="selection-summary">

                    <div class="summary-item">

                        <div class="summary-icon">▦</div>

                        <div class="summary-info">
                            <div class="summary-label">Category</div>
                            <div class="summary-value" id="summaryCategory">
                                Not selected
                            </div>
                        </div>

                    </div>


                    <div class="summary-item">

                        <div class="summary-icon">▣</div>

                        <div class="summary-info">
                            <div class="summary-label">Asset</div>
                            <div class="summary-value" id="summaryAsset">
                                Not selected
                            </div>
                        </div>

                    </div>


                    <div class="summary-item">

                        <div class="summary-icon">♙</div>

                        <div class="summary-info">
                            <div class="summary-label">Assigned To</div>
                            <div class="summary-value" id="summaryUser">
                                Not selected
                            </div>
                        </div>

                    </div>

                </div>


                <!-- GENERATE PREVIEW -->
                <button type="button"
                        class="btn btn-primary"
                        style="margin-top: 24px;"
                        onclick="generateAssetPreview()">

                    <span>▤</span>
                    Generate Asset Preview

                </button>


                <!-- PREVIEW SECTION -->
                <div class="preview-section" id="previewSection">

                    <div class="preview-header">

                        <div class="preview-title-wrapper">

                            <div class="preview-title-icon">
                                ✓
                            </div>

                            <div>
                                <h3 class="preview-title">
                                    Asset Assignment Preview
                                </h3>

                                <p class="preview-subtitle">
                                    Set the condition of each physical asset before saving.
                                </p>
                            </div>

                        </div>

                        <div class="preview-count" id="previewCount">
                            0 assets
                        </div>

                    </div>


                    <div class="table-container">

                        <table class="asset-table">

                            <thead>
                                <tr>
                                    <th style="width: 65px;">No.</th>
                                    <th>Asset</th>
                                    <th>Assigned To</th>
                                    <th>Condition / Tagging</th>
                                </tr>
                            </thead>

                            <tbody id="previewBody">
                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            <!-- FOOTER -->
            <div class="card-footer">

                <div class="footer-note">
                    <span class="footer-note-icon">ⓘ</span>
                    Each physical asset can have its own condition.
                </div>

                <div class="footer-actions">

                    <button type="button"
                            class="btn btn-secondary"
                            onclick="resetForm()">
                        Reset
                    </button>

                    <button type="submit"
                            class="btn btn-success"
                            id="submitBtn"
                            disabled>
                        <span>✓</span>
                        Save Assignment
                    </button>

                </div>

            </div>


            <input type="hidden"
                   name="action"
                   value="create_assets">

        </form>

    </div>

</div>


<!-- TOAST -->
<div class="toast" id="toast">
    <span class="toast-icon" id="toastIcon">✓</span>
    <span id="toastMessage"></span>
</div>


<script>

/* =========================
   CUSTOM DROPDOWN
========================= */

function toggleDropdown(id) {

    const dropdown = document.getElementById(id);

    document.querySelectorAll('.custom-select').forEach(select => {

        if (select.id !== id) {
            select.classList.remove('open');
        }

    });

    dropdown.classList.toggle('open');

}


function selectOption(selectId, inputId, value, text, type) {

    const select = document.getElementById(selectId);
    const input = document.getElementById(inputId);
    const trigger = select.querySelector('.select-trigger');
    const triggerText = trigger.querySelector('.select-text');

    input.value = value;

    trigger.classList.add('selected');

    triggerText.innerHTML = `
        <span class="select-value">${escapeHTML(text)}</span>
        <span class="select-subtext">${escapeHTML(type)}</span>
    `;

    select.classList.remove('open');

    // Update summary
    if (selectId === 'categorySelect') {
        document.getElementById('summaryCategory').textContent = text;
    }

    if (selectId === 'assetSelect') {
        document.getElementById('summaryAsset').textContent = text;
    }

    if (selectId === 'userSelect') {
        document.getElementById('summaryUser').textContent = text;
    }

}


function filterOptions(selectId, searchValue) {

    const select = document.getElementById(selectId);
    const options = select.querySelectorAll('.select-option');

    searchValue = searchValue.toLowerCase().trim();

    let visibleCount = 0;

    options.forEach(option => {

        const searchText = option.dataset.search || '';

        if (searchText.includes(searchValue)) {
            option.style.display = 'flex';
            visibleCount++;
        } else {
            option.style.display = 'none';
        }

    });

}


document.addEventListener('click', function(event) {

    if (!event.target.closest('.custom-select')) {

        document.querySelectorAll('.custom-select').forEach(select => {
            select.classList.remove('open');
        });

    }

});


/* =========================
   QUANTITY
========================= */

function changeQuantity(amount) {

    const input = document.getElementById('quantity_syntax');

    let quantity = parseInt(input.value) || 1;

    quantity += amount;

    if (quantity < 1) quantity = 1;
    if (quantity > 1000) quantity = 1000;

    input.value = quantity;

    updateQuantitySummary();

}


function updateQuantitySummary() {

    const input = document.getElementById('quantity_syntax');

    let quantity = parseInt(input.value) || 1;

    if (quantity < 1) quantity = 1;
    if (quantity > 1000) quantity = 1000;

    input.value = quantity;

}


/* =========================
   GENERATE PREVIEW
========================= */

function generateAssetPreview() {

    const categorySelect = document.getElementById('categorySelect');
    const assetSelect = document.getElementById('assetSelect');
    const userSelect = document.getElementById('userSelect');

    const categoryValue = document.getElementById('categoryValue').value;
    const assetValue = document.getElementById('assetValue').value;
    const userValue = document.getElementById('userValue').value;

    const categoryName = categorySelect.querySelector('.select-value')?.textContent || '';
    const assetName = assetSelect.querySelector('.select-value')?.textContent || '';
    const userName = userSelect.querySelector('.select-value')?.textContent || '';

    const quantity = parseInt(document.getElementById('quantity_syntax').value);

    const previewSection = document.getElementById('previewSection');
    const previewBody = document.getElementById('previewBody');
    const previewCount = document.getElementById('previewCount');
    const submitBtn = document.getElementById('submitBtn');


    // VALIDATION
    if (!categoryValue) {
        showToast('Please select an asset category.', 'error');
        return;
    }

    if (!assetValue) {
        showToast('Please select an asset.', 'error');
        return;
    }

    if (!userValue) {
        showToast('Please select an assigned user.', 'error');
        return;
    }

    if (!quantity || quantity < 1) {
        showToast('Please enter a valid quantity.', 'error');
        return;
    }


    // CLEAR PREVIOUS PREVIEW
    previewBody.innerHTML = '';


    // GENERATE ROWS
    for (let i = 1; i <= quantity; i++) {

        const row = document.createElement('tr');

        row.innerHTML = `

            <td>
                <span class="row-number">${i}</span>
            </td>

            <td>
                <div class="asset-cell">

                    <div class="asset-cell-icon">▣</div>

                    <div class="asset-cell-info">

                        <div class="asset-cell-name">
                            ${escapeHTML(assetName)}
                        </div>

                        <div class="asset-cell-sub">
                            Physical Asset #${i}
                        </div>

                    </div>

                </div>

                <input type="hidden"
                       name="assets[${i}][Assets_id]"
                       value="${escapeHTML(assetValue)}">

                <input type="hidden"
                       name="assets[${i}][Assets_category_id]"
                       value="${escapeHTML(categoryValue)}">
            </td>

            <td>
                <div class="user-cell">

                    <div class="user-avatar">
                        ${getInitials(userName)}
                    </div>

                    <div class="user-info">

                        <div class="user-name">
                            ${escapeHTML(userName)}
                        </div>

                        <div class="user-sub">
                            Assigned User
                        </div>

                    </div>

                </div>

                <input type="hidden"
                       name="assets[${i}][User_department_id]"
                       value="${escapeHTML(userValue)}">
            </td>

            <td>

                <select class="condition-select"
                        name="assets[${i}][Tagging_id]"
                        required>

                    <option value="">Select condition</option>

                    <?php if(!empty($tagging)): ?>

                        <?php foreach($tagging as $alltagging): ?>

                            <option value="<?= htmlspecialchars($alltagging['Tagging_id']) ?>">
                                <?= htmlspecialchars($alltagging['performance_status']) ?>
                            </option>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </select>

            </td>

        `;

        previewBody.appendChild(row);

    }


    previewCount.textContent = quantity + (quantity === 1 ? ' asset' : ' assets');

    previewSection.classList.add('visible');

    submitBtn.disabled = false;

    previewSection.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });

    showToast('Asset preview generated successfully.', 'success');

}


/* =========================
   FORM SUBMIT VALIDATION
========================= */

document.getElementById('assetForm').addEventListener('submit', function(event) {

    const taggingSelects = document.querySelectorAll(
        '#previewBody select[name*="[Tagging_id]"]'
    );

    if (taggingSelects.length === 0) {

        event.preventDefault();

        showToast('Please generate the asset preview first.', 'error');

        return;
    }

    for (const select of taggingSelects) {

        if (!select.value) {

            event.preventDefault();

            showToast('Please select the condition of every asset.', 'error');

            select.focus();

            return;
        }

    }

});


/* =========================
   RESET
========================= */

function resetForm() {

    document.getElementById('assetForm').reset();

    document.getElementById('categoryValue').value = '';
    document.getElementById('assetValue').value = '';
    document.getElementById('userValue').value = '';

    document.querySelectorAll('.select-trigger').forEach(trigger => {

        trigger.classList.remove('selected');

        trigger.querySelector('.select-text').innerHTML = `
            <span class="select-placeholder">Select an option</span>
        `;

    });

    document.getElementById('summaryCategory').textContent = 'Not selected';
    document.getElementById('summaryAsset').textContent = 'Not selected';
    document.getElementById('summaryUser').textContent = 'Not selected';

    document.getElementById('previewBody').innerHTML = '';

    document.getElementById('previewSection').classList.remove('visible');

    document.getElementById('submitBtn').disabled = true;

}


/* =========================
   HELPERS
========================= */

function escapeHTML(value) {

    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');

}


function getInitials(name) {

    const parts = name.trim().split(/\s+/);

    if (parts.length === 1) {
        return parts[0].substring(0, 2).toUpperCase();
    }

    return (
        parts[0].charAt(0) +
        parts[parts.length - 1].charAt(0)
    ).toUpperCase();

}


function showToast(message, type = 'success') {

    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    const toastIcon = document.getElementById('toastIcon');

    toastMessage.textContent = message;

    toast.classList.remove('success', 'error');
    toast.classList.add(type);

    toastIcon.textContent = type === 'success' ? '✓' : '!';

    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);

}

</script>

</body>
</html>