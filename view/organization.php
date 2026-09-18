<?php

include "../config/db.php";
include "../model/include_model/Organization.Model.php";

$organizationModel = new Organization_Model($conn);


/*
|--------------------------------------------------------------------------
| GET SELECTED DEPARTMENT
|--------------------------------------------------------------------------
*/

$selectedDepartmentId = isset($_GET['department_id'])
    ? (int) $_GET['department_id']
    : 0;


/*
|--------------------------------------------------------------------------
| GET DIVISIONS WITH DEPARTMENTS
|--------------------------------------------------------------------------
*/

$divisions = $organizationModel->getDivisionsWithDepartments();


/*
|--------------------------------------------------------------------------
| GET SELECTED DEPARTMENT AND USERS WITH ASSETS
|--------------------------------------------------------------------------
*/

$selectedDepartment = null;
$users = [];

if ($selectedDepartmentId > 0) {

    $selectedDepartment =
        $organizationModel->getDepartment(
            $selectedDepartmentId
        );

    if ($selectedDepartment) {

        $users =
            $organizationModel->getUsersWithAssetsByDepartment(
                $selectedDepartmentId
            );

    }

}


/*
|--------------------------------------------------------------------------
| SUMMARY
|--------------------------------------------------------------------------
*/

$totalUsers = count($users);

$totalAssets = 0;
$totalAssetRecords = 0;

foreach ($users as $user) {

    $assets = $user['Assets'] ?? [];

    $totalAssetRecords += count($assets);

    foreach ($assets as $asset) {

        $totalAssets += (int) (
            $asset['Assets_Quantity'] ?? 0
        );

    }

}


/*
|--------------------------------------------------------------------------
| HELPER FUNCTIONS
|--------------------------------------------------------------------------
*/

function e($value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}


function formatDateValue($date): string
{
    if (empty($date)) {
        return '-';
    }

    $timestamp = strtotime($date);

    if ($timestamp === false) {
        return e($date);
    }

    return date('M d, Y', $timestamp);
}


function formatCurrencyValue($amount): string
{
    if ($amount === null || $amount === '') {
        return '-';
    }

    return '₱' . number_format(
        (float) $amount,
        2
    );
}


function getInitials($fullname): string
{
    $fullname = trim($fullname);

    if ($fullname === '') {
        return '?';
    }

    $words = preg_split('/\s+/', $fullname);

    if (count($words) >= 2) {

        return strtoupper(
            substr($words[0], 0, 1) .
            substr($words[count($words) - 1], 0, 1)
        );

    }

    return strtoupper(
        substr($fullname, 0, 1)
    );
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Organization / Asset Management
    </title>
    <style>
        /* =========================================================
   ORGANIZATION / ASSET MANAGEMENT
   COMPLETE RESPONSIVE CSS
   ========================================================= */


/* =========================================================
   ROOT VARIABLES
   ========================================================= */

:root {
    --primary: #4b50c5;
    --primary-dark: #303a9b;
    --primary-light: #eef0ff;

    --background: #f5f7fb;
    --white: #ffffff;

    --text: #25283d;
    --muted: #737891;
    --border: #e4e7f0;

    --success: #198754;
    --success-bg: #e8f7ee;

    --warning: #d99a06;
    --warning-bg: #fff8df;

    --danger: #dc3545;
    --danger-bg: #fff1f1;

    --shadow-sm: 0 4px 14px rgba(37, 40, 61, 0.04);
    --shadow-md: 0 12px 30px rgba(48, 58, 155, 0.12);
}


/* =========================================================
   GLOBAL RESET
   ========================================================= */

* {
    box-sizing: border-box;
}

html {
    width: 100%;
    height: 100%;
    scroll-behavior: smooth;
}

body {
    width: 100%;
    min-height: 100%;
    margin: 0;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    background: var(--background);
    color: var(--text);

    overflow-x: hidden;
}

a {
    color: inherit;
    text-decoration: none;
}

button,
input,
select,
textarea {
    font-family: inherit;
}

button,
a {
    -webkit-tap-highlight-color: transparent;
}

img {
    max-width: 100%;
    height: auto;
}


/* =========================================================
   PAGE WRAPPER
   ========================================================= */

.page-wrapper {
    width: 100%;
    max-width: 100vw;
    height: 100vh;

    display: flex;
    flex-direction: column;

    overflow: hidden;
}


/* =========================================================
   TOPBAR / HEADER
   ========================================================= */

.topbar {
    width: 100%;
    height: 72px;
    min-height: 72px;
    flex: 0 0 72px;

    padding: 0 28px;

    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;

    background: var(--white);
    border-bottom: 1px solid var(--border);

    position: relative;
    z-index: 100;
}

.brand-area {
    min-width: 0;

    display: flex;
    align-items: center;
    gap: 13px;
}

.brand-logo {
    width: 42px;
    height: 42px;
    min-width: 42px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 12px;

    background: var(--primary);
    color: var(--white);

    font-size: 17px;
    font-weight: 800;
}

.brand-text {
    min-width: 0;
}

.brand-title {
    margin: 0;

    color: var(--text);
    font-size: 18px;
    font-weight: 800;
    line-height: 1.3;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.brand-subtitle {
    margin-top: 4px;

    color: var(--muted);
    font-size: 12px;
    line-height: 1.3;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.topbar-status {
    display: flex;
    align-items: center;
    gap: 7px;

    flex: 0 0 auto;

    color: var(--success);
    font-size: 13px;
    font-weight: 700;
}

.status-dot {
    width: 8px;
    height: 8px;
    min-width: 8px;

    border-radius: 50%;
    background: var(--success);
}


/* =========================================================
   MAIN LAYOUT
   ========================================================= */

.main-layout {
    width: 100%;
    min-width: 0;
    min-height: 0;

    display: grid;
    grid-template-columns: 285px minmax(0, 1fr);

    flex: 1 1 auto;

    overflow: hidden;
}


/* =========================================================
   SIDEBAR
   ========================================================= */

.sidebar {
    width: 100%;
    min-width: 0;
    min-height: 0;
    height: 100%;

    padding: 24px 16px;

    background: var(--white);
    border-right: 1px solid var(--border);

    overflow-y: auto;
    overflow-x: hidden;

    scrollbar-width: thin;
    scrollbar-color: #cfd2f5 transparent;
}

.sidebar::-webkit-scrollbar {
    width: 7px;
}

.sidebar::-webkit-scrollbar-track {
    background: transparent;
}

.sidebar::-webkit-scrollbar-thumb {
    background: #cfd2f5;
    border-radius: 20px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: var(--primary);
}


/* =========================================================
   DASHBOARD LINK
   ========================================================= */

.dashboard-link {
    width: 100%;

    display: flex;
    align-items: center;
    gap: 10px;

    margin-bottom: 22px;
    padding: 12px;

    border: 1px solid var(--border);
    border-radius: 10px;

    background: var(--white);
    color: var(--text);

    font-size: 13px;
    font-weight: 800;

    transition:
        background 0.2s ease,
        border-color 0.2s ease,
        color 0.2s ease;
}

.dashboard-link:hover {
    background: var(--primary-light);
    border-color: var(--primary);
    color: var(--primary);
}

.dashboard-icon {
    width: 25px;
    height: 25px;
    min-width: 25px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 7px;

    background: var(--primary-light);
    color: var(--primary);

    font-size: 15px;
}


/* =========================================================
   SIDEBAR HEADING
   ========================================================= */

.sidebar-heading {
    padding: 0 12px;
    margin-bottom: 18px;

    color: var(--muted);

    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1px;
}


/* =========================================================
   DIVISION BLOCK
   ========================================================= */

.division-block {
    width: 100%;
    margin-bottom: 10px;
}

.division-title {
    width: 100%;

    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;

    padding: 12px;

    border: 1px solid transparent;
    border-radius: 10px;

    background: #fafaff;
    color: var(--text);

    cursor: pointer;
    user-select: none;

    transition:
        background 0.2s ease,
        border-color 0.2s ease,
        color 0.2s ease;
}

.division-title:hover {
    background: var(--primary-light);
    border-color: #dfe1ff;
    color: var(--primary);
}

.division-title-content {
    min-width: 0;

    display: flex;
    flex-direction: column;
    gap: 4px;
}

.division-name {
    font-size: 14px;
    font-weight: 800;
    line-height: 1.3;
}

.division-code {
    color: var(--muted);
    font-size: 10px;
    font-weight: 500;
}

.division-toggle {
    width: 24px;
    height: 24px;
    min-width: 24px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 7px;

    color: var(--muted);
    font-size: 11px;

    transition:
        transform 0.2s ease,
        background 0.2s ease;
}

.division-title:hover .division-toggle {
    background: rgba(75, 80, 197, 0.08);
    color: var(--primary);
}

.division-block.open .division-toggle {
    transform: rotate(180deg);
}


/* =========================================================
   DEPARTMENT LIST
   ========================================================= */

.department-list {
    display: none;
    flex-direction: column;
    gap: 3px;

    padding: 7px 0 3px 10px;
}

.division-block.open .department-list {
    display: flex;
}

.department-link {
    position: relative;

    width: 100%;
    min-width: 0;

    display: flex;
    align-items: center;

    padding: 10px 12px 10px 22px;

    border-radius: 8px;

    color: #666b83;

    font-size: 13px;
    line-height: 1.25;

    transition:
        background 0.2s ease,
        color 0.2s ease;
}

.department-link span {
    overflow-wrap: anywhere;
}

.department-link::before {
    content: "";

    position: absolute;
    left: 9px;
    top: 50%;

    width: 5px;
    height: 5px;

    border-radius: 50%;
    background: #b9bdd0;

    transform: translateY(-50%);

    transition: background 0.2s ease;
}

.department-link:hover {
    background: var(--primary-light);
    color: var(--primary);
}

.department-link:hover::before {
    background: var(--primary);
}

.department-link.active {
    background: var(--primary-light);
    color: var(--primary);
    font-weight: 800;
}

.department-link.active::before {
    background: var(--primary);
}

.empty-sidebar {
    padding: 14px 12px;

    color: var(--muted);
    font-size: 12px;
    line-height: 1.5;
}


/* =========================================================
   MAIN CONTENT
   ========================================================= */

/*
   DESKTOP:
   Main content remains fixed.
   Only .user-tiles will scroll.
*/

.content {
    width: 100%;
    min-width: 0;
    min-height: 0;
    height: 100%;

    padding: 30px;

    display: flex;
    flex-direction: column;

    overflow: hidden;

    box-sizing: border-box;
}


/* =========================================================
   BREADCRUMB
   ========================================================= */

.breadcrumb {
    width: 100%;
    min-width: 0;

    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;

    flex: 0 0 auto;

    margin-bottom: 12px;

    color: var(--muted);
    font-size: 12px;
    line-height: 1.5;
}

.breadcrumb strong {
    color: var(--primary);
    overflow-wrap: anywhere;
}


/* =========================================================
   PAGE HEADING
   ========================================================= */

.page-heading {
    width: 100%;
    min-width: 0;

    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;

    flex: 0 0 auto;

    margin-bottom: 25px;
}

.page-heading > div:first-child {
    min-width: 0;
}

.page-heading h1 {
    margin: 0;

    color: var(--text);
    font-size: 29px;
    font-weight: 800;
    line-height: 1.2;

    overflow-wrap: anywhere;
}

.page-heading p {
    margin: 9px 0 0;

    color: var(--muted);
    font-size: 13px;
    line-height: 1.5;
}

.refresh-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;

    flex: 0 0 auto;

    padding: 11px 16px;

    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 10px;

    color: var(--text);

    font-size: 12px;
    font-weight: 800;

    white-space: nowrap;

    transition:
        border-color 0.2s ease,
        color 0.2s ease,
        background 0.2s ease;
}

.refresh-link:hover {
    background: var(--primary-light);
    border-color: var(--primary);
    color: var(--primary);
}


/* =========================================================
   OVERVIEW GRID
   ========================================================= */

.overview-grid {
    width: 100%;
    min-width: 0;

    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;

    flex: 0 0 auto;

    margin-bottom: 28px;
}


/* =========================================================
   OVERVIEW CARD
   ========================================================= */

.overview-card {
    position: relative;

    min-width: 0;
    min-height: 118px;

    padding: 23px;

    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 16px;

    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.overview-card::after {
    content: "";

    position: absolute;
    right: -25px;
    bottom: -35px;

    width: 115px;
    height: 115px;

    border-radius: 50%;

    background: var(--primary-light);
    opacity: 0.6;

    pointer-events: none;
}

.overview-label {
    position: relative;
    z-index: 1;

    color: var(--muted);

    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.3px;
}

.overview-value {
    position: relative;
    z-index: 1;

    margin-top: 12px;

    color: var(--text);

    font-size: 32px;
    font-weight: 900;
    line-height: 1;
}

.overview-description {
    position: relative;
    z-index: 1;

    margin-top: 10px;

    color: var(--muted);
    font-size: 11px;
    line-height: 1.4;
}


/* =========================================================
   USERS SECTION
   ========================================================= */

.section {
    width: 100%;
    min-width: 0;
    min-height: 0;

    display: flex;
    flex-direction: column;

    flex: 1 1 auto;

    margin-bottom: 0;
}

.section-header {
    width: 100%;
    min-width: 0;

    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;

    flex: 0 0 auto;

    margin-bottom: 14px;
}

.section-header > div:first-child {
    min-width: 0;
}

.section-title {
    margin: 0;

    color: var(--text);
    font-size: 17px;
    font-weight: 900;
    line-height: 1.3;
}

.section-subtitle {
    margin-top: 5px;

    color: var(--muted);
    font-size: 12px;
    line-height: 1.5;
}

.section-counter {
    padding: 7px 11px;

    border-radius: 20px;

    background: var(--primary-light);
    color: var(--primary);

    font-size: 11px;
    font-weight: 800;

    white-space: nowrap;
}


/* =========================================================
   USER TILES CONTAINER
   ========================================================= */

/*
   IMPORTANT:
   - Only this area scrolls on desktop.
   - Grid rows use natural height.
   - Cards will not be compressed.
   - Bottom padding allows the last row to be seen.
*/

.user-tiles {
    width: 100%;
    min-width: 0;
    min-height: 0;

    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 10px;

    flex: 1 1 auto;

    align-content: start;
    justify-content: stretch;

    grid-auto-rows: max-content;

    overflow-y: auto;
    overflow-x: hidden;

    padding: 4px 10px 28px 0;

    scrollbar-width: thin;
    scrollbar-color: #cfd2f5 transparent;
}

.user-tiles::-webkit-scrollbar {
    width: 8px;
}

.user-tiles::-webkit-scrollbar-track {
    background: transparent;
}

.user-tiles::-webkit-scrollbar-thumb {
    background: #cfd2f5;
    border-radius: 20px;
}

.user-tiles::-webkit-scrollbar-thumb:hover {
    background: var(--primary);
}


/* =========================================================
   USER TILE
   ========================================================= */

.user-tile {
    width: 100%;
    min-width: 0;
    height: auto;
    min-height: 0;

    align-self: start;

    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 10px;

    overflow: hidden;
    box-shadow: var(--shadow-sm);

    transition:
        transform 0.2s ease,
        border-color 0.2s ease,
        box-shadow 0.2s ease;
}

.user-tile:hover {
    transform: translateY(-2px);

    border-color: #cfd2f5;
    box-shadow: var(--shadow-md);
}


/* =========================================================
   USER TILE HEADER
   ========================================================= */

.user-tile-header {
    width: 100%;
    min-width: 0;

    display: flex;
    align-items: center;
    gap: 8px;

    padding: 10px 11px;

    background: linear-gradient(
        135deg,
        #f7f7ff 0%,
        #ffffff 100%
    );

    border-bottom: 1px solid var(--border);
}

.user-tile-avatar {
    width: 30px;
    height: 30px;
    min-width: 30px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 8px;

    background: var(--primary-light);
    color: var(--primary);

    font-size: 11px;
    font-weight: 900;
}

.user-tile-user-info {
    min-width: 0;
    flex: 1 1 auto;
}

.user-tile-user-name {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;

    color: var(--text);

    font-size: 11px;
    font-weight: 900;
    line-height: 1.3;
}

.user-tile-user-id {
    margin-top: 2px;

    color: var(--muted);
    font-size: 8px;
    line-height: 1.3;
}


/* =========================================================
   USER TILE BODY
   ========================================================= */

.user-tile-body {
    width: 100%;
    height: auto;
    min-height: 0;

    padding: 10px;
}


/* =========================================================
   USER DETAILS GRID
   ========================================================= */

.user-details-grid {
    width: 100%;
    min-width: 0;

    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));

    gap: 5px;

    margin-bottom: 10px;
}

.user-detail-item {
    min-width: 0;

    padding: 6px 7px;

    background: #fafaff;
    border: 1px solid #eef0f7;
    border-radius: 6px;
}

.user-detail-label {
    margin-bottom: 3px;

    color: var(--muted);

    font-size: 7px;
    font-weight: 900;
    letter-spacing: 0.3px;
    text-transform: uppercase;
    line-height: 1.3;
}

.user-detail-value {
    min-width: 0;

    color: var(--text);

    font-size: 8px;
    font-weight: 700;
    line-height: 1.35;

    overflow-wrap: anywhere;
    word-break: break-word;
}


/* =========================================================
   CHECKED DATE / ASSET DETAIL BOX
   ========================================================= */

.asset-detail-item {
    min-width: 0;

    padding: 8px 9px;

    background: #fafaff;
    border: 1px solid #eef0f7;
    border-radius: 6px;
}

.asset-detail-label {
    margin-bottom: 4px;

    color: var(--muted);

    font-size: 8px;
    font-weight: 900;
    letter-spacing: 0.3px;
    text-transform: uppercase;
    line-height: 1.3;
}

.asset-detail-value {
    min-width: 0;

    color: var(--text);

    font-size: 10px;
    font-weight: 700;
    line-height: 1.35;

    overflow-wrap: anywhere;
    word-break: break-word;
}

.asset-detail-value.not-checked {
    color: var(--warning);
    font-weight: 800;
}


/* =========================================================
   ASSIGNED ASSETS TITLE
   ========================================================= */

.tile-section-title {
    width: 100%;
    min-width: 0;

    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;

    margin-bottom: 7px;
}

.tile-section-title h3 {
    min-width: 0;

    margin: 0;

    color: var(--text);

    font-size: 10px;
    font-weight: 900;
    line-height: 1.3;
}

.tile-asset-count {
    min-width: 19px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 3px 6px;

    border-radius: 20px;

    background: var(--primary-light);
    color: var(--primary);

    font-size: 7px;
    font-weight: 900;

    white-space: nowrap;
}


/* =========================================================
   NO ACTIVE ASSETS MESSAGE
   ========================================================= */

.no-assets-tile {
    width: 100%;
    min-height: 68px;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 5px;

    padding: 14px 16px;

    background: #fafaff;
    border: 1px dashed #dfe2f0;
    border-radius: 8px;

    color: var(--muted);

    font-size: 10px;
    font-weight: 600;
    line-height: 1.5;

    text-align: center;
}

.no-assets-tile span {
    display: block;
    max-width: 100%;
}

.no-assets-tile span:first-child {
    color: var(--muted);
}

.no-assets-tile span:last-child {
    color: #9a9eb3;
    font-size: 9px;
    font-weight: 500;
}


/* =========================================================
   VIEW ASSETS BUTTON
   ========================================================= */

.view-assets-button {
    width: 100%;

    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;

    padding: 9px 10px;

    border: none;
    border-radius: 7px;

    background: var(--primary);
    color: var(--white);

    cursor: pointer;

    font-size: 9px;
    font-weight: 900;

    transition:
        background 0.2s ease,
        transform 0.2s ease;
}

.view-assets-button:hover {
    background: var(--primary-dark);
}

.view-assets-button:active {
    transform: scale(0.98);
}

.view-assets-button span {
    font-size: 12px;
}


/* =========================================================
   EMPTY STATE
   ========================================================= */

.section-card {
    width: 100%;

    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 16px;

    overflow: hidden;
}

.empty-state {
    padding: 40px 20px;

    color: var(--muted);

    font-size: 13px;
    line-height: 1.6;
    text-align: center;
}

.empty-state strong {
    display: block;

    margin-bottom: 7px;

    color: var(--text);

    font-size: 15px;
    font-weight: 900;
}


/* =========================================================
   ASSET MODAL OVERLAY
   ========================================================= */

.asset-modal-overlay {
    position: fixed;
    inset: 0;

    display: none;
    align-items: center;
    justify-content: center;

    padding: 20px;

    background: rgba(20, 24, 48, 0.58);

    z-index: 9999;
}

.asset-modal-overlay.show {
    display: flex;
}


/* =========================================================
   ASSET MODAL
   ========================================================= */

.asset-modal {
    width: min(900px, 100%);
    max-height: 90vh;

    display: flex;
    flex-direction: column;

    background: var(--white);
    border-radius: 14px;

    overflow: hidden;

    box-shadow: 0 25px 80px rgba(0, 0, 0, 0.25);

    animation: modalShow 0.2s ease;
}

@keyframes modalShow {
    from {
        opacity: 0;
        transform: translateY(12px) scale(0.98);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.asset-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;

    padding: 16px 20px;

    background: #fafaff;
    border-bottom: 1px solid var(--border);
}

.asset-modal-title-wrapper {
    min-width: 0;
}

.asset-modal-title {
    margin: 0;

    color: var(--text);

    font-size: 17px;
    font-weight: 900;
    line-height: 1.3;
}

.asset-modal-subtitle {
    margin-top: 5px;

    color: var(--muted);
    font-size: 11px;
    line-height: 1.4;
}

.asset-modal-close {
    width: 30px;
    height: 30px;
    min-width: 30px;

    display: flex;
    align-items: center;
    justify-content: center;

    border: none;
    border-radius: 8px;

    background: #eeeef8;
    color: var(--text);

    cursor: pointer;

    font-size: 17px;
    font-weight: 900;
}

.asset-modal-close:hover {
    background: var(--danger-bg);
    color: var(--danger);
}

.asset-modal-body {
    padding: 18px;

    overflow-y: auto;
}

.modal-user-section {
    display: none;
}

.modal-user-section.active {
    display: block;
}

.modal-assets-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));

    gap: 12px;
}


/* =========================================================
   MODAL ASSET CARD
   ========================================================= */

.modal-asset-card {
    min-width: 0;

    padding: 13px;

    background: #ffffff;
    border: 1px solid var(--border);
    border-radius: 10px;
}

.modal-asset-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 10px;

    margin-bottom: 12px;
}

.modal-asset-heading {
    min-width: 0;
}

.modal-asset-name {
    color: var(--primary);

    font-size: 13px;
    font-weight: 900;
    line-height: 1.3;

    overflow-wrap: anywhere;
}

.modal-asset-description {
    margin-top: 4px;

    color: var(--muted);

    font-size: 10px;
    line-height: 1.4;
}

.modal-asset-status {
    flex: 0 0 auto;

    padding: 4px 7px;

    border-radius: 20px;

    background: var(--success-bg);
    color: var(--success);

    font-size: 8px;
    font-weight: 900;
    white-space: nowrap;
}

.modal-asset-status.not-operational {
    background: var(--danger-bg);
    color: var(--danger);
}

.modal-asset-details {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));

    gap: 9px;
}

.modal-asset-detail {
    min-width: 0;
}

.modal-asset-detail-label {
    margin-bottom: 3px;

    color: var(--muted);

    font-size: 8px;
    font-weight: 900;
    letter-spacing: 0.3px;
    text-transform: uppercase;
    line-height: 1.3;
}

.modal-asset-detail-value {
    min-width: 0;

    color: var(--text);

    font-size: 10px;
    line-height: 1.4;

    overflow-wrap: anywhere;
    word-break: break-word;
}

.modal-asset-detail-value.strong {
    font-weight: 900;
}

.modal-asset-notes {
    margin-top: 11px;
    padding-top: 10px;

    border-top: 1px solid var(--border);

    color: var(--muted);

    font-size: 10px;
    line-height: 1.5;

    overflow-wrap: anywhere;
}

.modal-asset-notes strong {
    color: var(--text);
}


/* =========================================================
   GENERATE QR BUTTON
   ========================================================= */

.generate-qr-button {
    width: 100%;
    margin-top: 12px;
    padding: 9px 12px;

    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;

    border: 1px solid #d8e0f0;
    border-radius: 8px;

    background: #f8faff;
    color: var(--primary);

    font-size: 10px;
    font-weight: 900;

    cursor: pointer;

    transition: 0.2s ease;
}

.generate-qr-button:hover {
    background: var(--primary-light);
    border-color: var(--primary);
}


/* =========================================================
   QR MODAL
   ========================================================= */

.qr-modal-overlay {
    position: fixed;
    inset: 0;

    z-index: 10000;

    display: none;
    align-items: center;
    justify-content: center;

    padding: 14px;

    background: rgba(15, 23, 42, 0.58);
}

.qr-modal-overlay.show {
    display: flex;
}

.qr-modal {
    width: min(440px, 100%);
    max-height: calc(100vh - 28px);

    overflow-y: auto;

    background: #ffffff;
    border-radius: 16px;

    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
}

.qr-modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;

    padding: 18px 22px 15px;

    border-bottom: 1px solid var(--border);
}

.qr-modal-title {
    margin: 0;

    color: #111827;

    font-size: 18px;
    font-weight: 900;
    line-height: 1.3;
}

.qr-modal-subtitle {
    margin-top: 5px;

    color: var(--muted);
    font-size: 11px;
}

.qr-modal-close {
    width: 31px;
    height: 31px;
    min-width: 31px;

    border: 0;
    border-radius: 8px;

    background: #eef0f8;
    color: #111827;

    font-size: 19px;
    font-weight: 900;

    cursor: pointer;
}

.qr-modal-close:hover {
    background: var(--danger-bg);
    color: var(--danger);
}

.qr-modal-body {
    padding: 22px;
}

.qr-code-wrapper {
    width: 236px;
    height: 236px;

    margin: 0 auto 20px;
    padding: 8px;

    display: flex;
    align-items: center;
    justify-content: center;

    border: 1px solid #dce3ef;
    border-radius: 10px;

    background: #ffffff;
}

.qr-code-wrapper canvas,
.qr-code-wrapper img {
    display: block;

    width: 220px !important;
    height: 220px !important;
}

.qr-record-summary {
    overflow: hidden;

    border: 1px solid #dbe3f0;
    border-radius: 11px;

    background: #f8faff;
}

.qr-summary-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;

    padding: 10px 13px;

    border-bottom: 1px solid #dbe3f0;

    font-size: 11px;
}

.qr-summary-row:last-child {
    border-bottom: 0;
}

.qr-summary-label {
    color: #64748b;
    font-weight: 900;
}

.qr-summary-value {
    min-width: 0;

    color: #111827;
    font-weight: 700;
    text-align: right;

    overflow-wrap: anywhere;
    word-break: break-word;
}

.qr-modal-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;

    gap: 8px;
    margin-top: 16px;
}

.qr-action-button {
    padding: 11px 12px;

    border-radius: 8px;

    font-size: 11px;
    font-weight: 900;

    cursor: pointer;
}

.qr-print-button {
    border: 1px solid #075bbb;
    background: #075bbb;
    color: #ffffff;
}

.qr-download-button {
    border: 1px solid #d5deed;
    background: #ffffff;
    color: var(--primary);
}

.qr-action-button:hover {
    opacity: 0.88;
}


/* =========================================================
   LARGE DESKTOP
   ========================================================= */

@media (min-width: 1441px) {

    .content {
        padding: 32px 36px;
    }

    .user-tiles {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

}


/* =========================================================
   LAPTOP
   ========================================================= */

@media (max-width: 1200px) {

    .main-layout {
        grid-template-columns: 245px minmax(0, 1fr);
    }

    .content {
        padding: 24px;
    }

    .page-heading h1 {
        font-size: 26px;
    }

    .user-tiles {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

}


/* =========================================================
   TABLET LANDSCAPE
   ========================================================= */

@media (max-width: 900px) {

    .topbar {
        padding: 0 20px;
    }

    .main-layout {
        grid-template-columns: 220px minmax(0, 1fr);
    }

    .sidebar {
        padding: 20px 12px;
    }

    .content {
        padding: 20px;
    }

    .page-heading {
        gap: 12px;
    }

    .page-heading h1 {
        font-size: 23px;
    }

    .overview-grid {
        gap: 12px;
    }

    .overview-card {
        padding: 18px;
    }

    .user-tiles {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

}


/* =========================================================
   TABLET PORTRAIT / LARGE PHONE
   ========================================================= */

@media (max-width: 700px) {

    /* -----------------------------------------------------
       HEADER
       ----------------------------------------------------- */

    .topbar {
        height: auto;
        min-height: 64px;

        padding: 12px 15px;
    }

    .brand-area {
        gap: 10px;
    }

    .brand-logo {
        width: 38px;
        height: 38px;
        min-width: 38px;

        border-radius: 10px;
        font-size: 15px;
    }

    .brand-title {
        font-size: 15px;
        line-height: 1.25;
    }

    .brand-subtitle {
        margin-top: 3px;
        font-size: 10px;
    }

    .topbar-status {
        display: none;
    }


    /* -----------------------------------------------------
       MAIN LAYOUT
       ----------------------------------------------------- */

    .main-layout {
        display: block;
        height: auto;
        min-height: 0;

        overflow: visible;
    }


    /* -----------------------------------------------------
       SIDEBAR
       ----------------------------------------------------- */

    .sidebar {
        width: 100%;
        height: auto;
        max-height: 260px;

        padding: 14px 12px;

        border-right: none;
        border-bottom: 1px solid var(--border);

        overflow-y: auto;
        overflow-x: hidden;
    }

    .dashboard-link {
        margin-bottom: 14px;
        padding: 10px;
    }

    .sidebar-heading {
        margin-bottom: 10px;
        padding: 0 10px;
    }

    .division-title {
        padding: 10px;
    }

    .department-link {
        padding: 9px 10px 9px 22px;
        font-size: 12px;
    }


    /* -----------------------------------------------------
       MAIN CONTENT
       ----------------------------------------------------- */

    .content {
        width: 100%;
        height: auto;
        min-height: 0;

        padding: 20px 15px;

        display: block;

        overflow: visible;
    }


    /* -----------------------------------------------------
       BREADCRUMB
       ----------------------------------------------------- */

    .breadcrumb {
        gap: 5px;
        margin-bottom: 10px;

        font-size: 10px;
        line-height: 1.5;
    }


    /* -----------------------------------------------------
       PAGE HEADING
       ----------------------------------------------------- */

    .page-heading {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 14px;

        margin-bottom: 20px;
    }

    .page-heading h1 {
        font-size: clamp(20px, 6vw, 27px);
        line-height: 1.2;

        overflow-wrap: anywhere;
    }

    .page-heading p {
        margin-top: 7px;
        font-size: 11px;
        line-height: 1.5;
    }

    .refresh-link {
        width: 100%;

        display: flex;
        align-items: center;
        justify-content: center;

        padding: 11px 14px;
    }


    /* -----------------------------------------------------
       OVERVIEW CARDS
       ----------------------------------------------------- */

    .overview-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;

        margin-bottom: 22px;
    }

    .overview-card {
        min-width: 0;
        min-height: 100px;

        padding: 15px;
        border-radius: 12px;
    }

    .overview-label {
        font-size: 9px;
        line-height: 1.3;
    }

    .overview-value {
        margin-top: 9px;
        font-size: clamp(24px, 7vw, 32px);
    }

    .overview-description {
        margin-top: 8px;
        font-size: 9px;
        line-height: 1.4;
    }


    /* -----------------------------------------------------
       SECTION HEADER
       ----------------------------------------------------- */

    .section {
        display: block;
        min-height: 0;
        margin-bottom: 25px;
    }

    .section-header {
        display: flex;
        align-items: flex-start;
        flex-direction: column;
        gap: 10px;

        margin-bottom: 13px;
    }

    .section-title {
        font-size: 16px;
        line-height: 1.3;
    }

    .section-subtitle {
        font-size: 10px;
        line-height: 1.5;
    }

    .section-counter {
        align-self: flex-start;
        font-size: 10px;
    }


    /* -----------------------------------------------------
       USER TILES
       ----------------------------------------------------- */

    .user-tiles {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;

        width: 100%;
        height: auto;
        max-height: none;

        overflow: visible;

        align-content: normal;
        grid-auto-rows: auto;

        padding: 0;
    }

    .user-tile {
        width: 100%;
        min-width: 0;
        height: auto;
    }

    .user-tile-header {
        padding: 11px 12px;
    }

    .user-tile-body {
        padding: 11px;
    }

    .user-tile-user-name {
        font-size: 11px;
    }

    .user-tile-user-id {
        font-size: 8px;
    }

    .user-details-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 6px;
    }

    .user-detail-item {
        min-width: 0;
        padding: 8px;
    }

    .user-detail-label {
        font-size: 7px;
    }

    .user-detail-value {
        font-size: 9px;
    }

    .asset-detail-item {
        padding: 8px;
    }

    .asset-detail-label {
        font-size: 7px;
    }

    .asset-detail-value {
        font-size: 9px;
    }

    .no-assets-tile {
        min-height: 72px;
        padding: 14px 12px;
    }


    /* -----------------------------------------------------
       ASSET MODAL
       ----------------------------------------------------- */

    .asset-modal-overlay {
        align-items: flex-start;
        padding: 12px;
    }

    .asset-modal {
        width: 100%;
        max-height: calc(100vh - 24px);

        margin-top: 5px;
        border-radius: 12px;
    }

    .asset-modal-header {
        padding: 14px;
    }

    .asset-modal-title {
        font-size: 15px;
    }

    .asset-modal-body {
        padding: 12px;
    }

    .modal-assets-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .modal-asset-card {
        padding: 12px;
    }

}


/* =========================================================
   SMALL PHONE - 480px
   ========================================================= */

@media (max-width: 480px) {

    .topbar {
        padding: 11px 12px;
    }

    .brand-logo {
        width: 35px;
        height: 35px;
        min-width: 35px;

        font-size: 14px;
    }

    .brand-title {
        font-size: 13px;
    }

    .brand-subtitle {
        font-size: 9px;
    }

    .content {
        padding: 16px 11px;
    }

    .page-heading h1 {
        font-size: 21px;
    }

    .overview-grid {
        gap: 8px;
    }

    .overview-card {
        min-height: 95px;
        padding: 13px;
        border-radius: 10px;
    }

    .overview-label {
        font-size: 8px;
    }

    .overview-value {
        font-size: 25px;
    }

    .overview-description {
        font-size: 8px;
    }

    .section-title {
        font-size: 15px;
    }

    .section-subtitle {
        font-size: 9px;
    }

    .user-tiles {
        gap: 10px;
    }

    .user-tile-header {
        padding: 10px;
    }

    .user-tile-body {
        padding: 10px;
    }

    .user-details-grid {
        gap: 5px;
    }

    .user-detail-item {
        padding: 7px;
    }

    .user-detail-label {
        font-size: 6.5px;
    }

    .user-detail-value {
        font-size: 8px;
    }

    .asset-detail-item {
        padding: 7px;
    }

    .asset-detail-label {
        font-size: 6.5px;
    }

    .asset-detail-value {
        font-size: 8px;
    }

    .no-assets-tile {
        min-height: 68px;
        padding: 12px 10px;

        font-size: 9px;
    }

    .no-assets-tile span:last-child {
        font-size: 8px;
    }

    .asset-modal-overlay {
        padding: 8px;
    }

    .asset-modal-header {
        padding: 12px;
    }

    .asset-modal-body {
        padding: 10px;
    }

    .qr-modal-body {
        padding: 18px;
    }

    .qr-code-wrapper {
        width: 216px;
        height: 216px;
    }

    .qr-code-wrapper canvas,
    .qr-code-wrapper img {
        width: 200px !important;
        height: 200px !important;
    }

}


/* =========================================================
   VERY SMALL PHONE - 360px
   ========================================================= */

@media (max-width: 360px) {

    .brand-title {
        font-size: 12px;
    }

    .brand-subtitle {
        font-size: 8px;
    }

    .content {
        padding: 14px 9px;
    }

    .page-heading h1 {
        font-size: 19px;
    }

    .overview-card {
        padding: 11px;
    }

    .overview-label {
        font-size: 7px;
    }

    .overview-value {
        font-size: 22px;
    }

    .overview-description {
        font-size: 7px;
    }

    .section-title {
        font-size: 14px;
    }

    .section-subtitle {
        font-size: 8px;
    }

    .user-tile-header {
        padding: 9px;
    }

    .user-tile-body {
        padding: 9px;
    }

    .user-tile-avatar {
        width: 28px;
        height: 28px;
        min-width: 28px;

        font-size: 10px;
    }

    .user-tile-user-name {
        font-size: 10px;
    }

    .user-tile-user-id {
        font-size: 7px;
    }

    .user-detail-item {
        padding: 6px;
    }

    .user-detail-label {
        font-size: 6px;
    }

    .user-detail-value {
        font-size: 7.5px;
    }

    .asset-detail-item {
        padding: 6px;
    }

    .asset-detail-label {
        font-size: 6px;
    }

    .asset-detail-value {
        font-size: 7.5px;
    }

    .no-assets-tile {
        font-size: 8px;
    }

    .no-assets-tile span:last-child {
        font-size: 7.5px;
    }

}


/* =========================================================
   LANDSCAPE PHONE
   ========================================================= */

@media (max-width: 900px) and (orientation: landscape) {

    .sidebar {
        max-height: 190px;
    }

    .content {
        padding: 18px;
    }

    .user-tiles {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

}


/* =========================================================
   TOUCH DEVICE IMPROVEMENTS
   ========================================================= */

@media (hover: none) and (pointer: coarse) {

    .user-tile:hover {
        transform: none;
    }

    .dashboard-link,
    .division-title,
    .department-link,
    .refresh-link,
    .view-assets-button,
    .generate-qr-button {
        min-height: 42px;
    }

}
    

/* =========================================================
   FINAL MOBILE OFF-CANVAS SIDEBAR + SCROLL FIX
   ========================================================= */

/* Desktop defaults */
.mobile-menu-btn {
    display: none;
    align-items: center;
    justify-content: center;
    flex: 0 0 40px;
    width: 40px;
    height: 40px;
    padding: 0;
    border: 1px solid var(--border, #e1e5ef);
    border-radius: 10px;
    background: #ffffff;
    color: var(--primary, #4b50c5);
    font-size: 23px;
    line-height: 1;
    cursor: pointer;
}

.mobile-menu-btn:hover {
    background: var(--primary-light, #eef0ff);
}

.sidebar-overlay {
    display: none;
}

/* Keep the application inside the viewport on desktop */
.page-wrapper {
    width: 100%;
    max-width: 100vw;
    height: 100vh;
    min-height: 0;
    overflow: hidden;
}

.main-layout {
    height: calc(100vh - 72px);
    min-height: 0;
    overflow: hidden;
}

.content {
    min-width: 0;
    min-height: 0;
    overflow-y: auto;
    overflow-x: hidden;
    overscroll-behavior: contain;
    padding-bottom: 36px;
}

.user-tiles {
    min-width: 0;
    min-height: 0;
    align-content: start;
    grid-auto-rows: max-content;
}

/* =========================================================
   TABLET
   ========================================================= */
@media (max-width: 900px) and (min-width: 701px) {
    .main-layout {
        grid-template-columns: 220px minmax(0, 1fr);
    }

    .content {
        overflow-y: auto;
    }
}

/* =========================================================
   MOBILE: SIDEBAR HIDDEN, BURGER OPENS IT
   ========================================================= */
@media (max-width: 700px) {

    .topbar {
        position: relative;
        z-index: 1200;
        height: 64px;
        min-height: 64px;
        flex: 0 0 64px;
        padding: 10px 14px;
        gap: 10px;
    }

    .mobile-menu-btn {
        display: inline-flex;
    }

    .brand-area {
        flex: 1 1 auto;
        min-width: 0;
        gap: 9px;
    }

    .brand-logo {
        width: 36px;
        height: 36px;
        min-width: 36px;
        border-radius: 10px;
        font-size: 14px;
    }

    .brand-title {
        max-width: calc(100vw - 115px);
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .brand-subtitle {
        max-width: calc(100vw - 115px);
        font-size: 9px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .topbar-status {
        display: none;
    }

    .main-layout {
        position: relative;
        display: block;
        width: 100%;
        height: calc(100vh - 64px);
        min-height: 0;
        overflow: hidden;
    }

    /* Off-canvas sidebar */
    .sidebar {
        position: fixed;
        top: 64px;
        left: 0;
        bottom: 0;
        z-index: 1100;

        width: min(290px, 86vw);
        height: calc(100vh - 64px);
        max-height: none;
        min-height: 0;

        padding: 16px 12px;
        background: #ffffff;
        border-right: 1px solid var(--border, #e1e5ef);
        border-bottom: 0;

        overflow-y: auto;
        overflow-x: hidden;

        transform: translateX(-105%);
        visibility: hidden;
        pointer-events: none;
        transition: transform .25s ease, visibility .25s ease;
        box-shadow: 8px 0 24px rgba(20, 30, 70, .12);
    }

    body.sidebar-open .sidebar {
        transform: translateX(0);
        visibility: visible;
        pointer-events: auto;
    }

    /* Dark clickable area behind sidebar */
    .sidebar-overlay {
        display: block;
        position: fixed;
        inset: 64px 0 0 0;
        z-index: 1050;
        background: rgba(15, 23, 42, .42);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity .25s ease, visibility .25s ease;
    }

    body.sidebar-open .sidebar-overlay {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    /* Main content takes the entire phone width and scrolls */
    .content {
        width: 100%;
        height: calc(100vh - 64px);
        min-height: 0;
        margin: 0;
        padding: 18px 12px 34px;
        overflow-y: auto;
        overflow-x: hidden;
        -webkit-overflow-scrolling: touch;
        overscroll-behavior: contain;
    }

    .page-heading {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
        margin-bottom: 18px;
    }

    .page-heading h1 {
        font-size: clamp(21px, 6vw, 28px);
        line-height: 1.15;
        overflow-wrap: anywhere;
    }

    .page-heading .refresh-link,
    .page-heading .refresh-button {
        width: 100%;
        min-height: 42px;
        justify-content: center;
    }

    .overview-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 9px;
        margin-bottom: 22px;
    }

    .overview-card {
        min-width: 0;
        padding: 14px 12px;
        border-radius: 12px;
    }

    .overview-card h3 {
        font-size: 9px;
    }

    .overview-value {
        font-size: clamp(24px, 8vw, 34px);
    }

    .overview-card p {
        font-size: 9px;
        line-height: 1.35;
    }

    .section-header {
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 14px;
    }

    .section-title {
        font-size: 17px;
        line-height: 1.2;
    }

    .section-subtitle {
        font-size: 10px;
        line-height: 1.35;
    }

    .section-counter {
        flex-shrink: 0;
        padding: 8px 10px;
        font-size: 10px;
    }

    /* One complete card per row; no nested tile scrollbar on mobile */
    .user-tiles {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 12px;
        width: 100%;
        height: auto;
        min-height: 0;
        overflow: visible;
        padding: 0 0 24px;
        align-content: start;
    }

    .user-tile {
        width: 100%;
        min-width: 0;
        height: auto;
        min-height: 0;
        align-self: start;
        overflow: hidden;
    }

    .user-tile-header {
        padding: 12px;
    }

    .user-tile-body {
        padding: 10px;
    }

    .user-tile-user-name {
        max-width: 100%;
        font-size: 11px;
    }

    .user-details {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 6px;
    }

    .asset-detail-item {
        min-width: 0;
        overflow-wrap: anywhere;
    }

    .view-assets-button,
    .generate-qr-button {
        width: 100%;
        min-height: 42px;
    }

    .no-assets-tile {
        min-height: 72px;
        padding: 14px 10px;
        line-height: 1.5;
    }
}

/* Small phones */
@media (max-width: 380px) {
    .topbar {
        padding-left: 10px;
        padding-right: 10px;
    }

    .content {
        padding-left: 10px;
        padding-right: 10px;
    }

    .overview-grid {
        gap: 7px;
    }

    .overview-card {
        padding: 12px 10px;
    }

    .overview-value {
        font-size: 27px;
    }

    .user-details {
        grid-template-columns: 1fr;
    }
}

/* Prevent background page movement while menu is open */
@media (max-width: 700px) {
    body.sidebar-open {
        overflow: hidden;
    }
}



/* =========================================================
   SELECT DEPARTMENT PAGE DESIGN
   Design only — does not change PHP or sidebar
   ========================================================= */

.select-department-page {
    width: 100%;
    min-height: 100%;
    padding: 10px 0;
}

/* Main welcome card */
.select-department-card {
    position: relative;
    width: 100%;
    max-width: 900px;
    margin: 30px auto;
    padding: 42px 40px;

    background: #ffffff;
    border: 1px solid #e2e6f0;
    border-radius: 20px;

    box-shadow: 0 10px 30px rgba(38, 51, 100, 0.05);
    overflow: hidden;
}

/* Decorative background circles */
.select-department-card::before {
    content: "";
    position: absolute;
    top: -100px;
    right: -80px;

    width: 260px;
    height: 260px;

    background: #f0f1ff;
    border-radius: 50%;

    pointer-events: none;
}

.select-department-card::after {
    content: "";
    position: absolute;
    bottom: -120px;
    left: -100px;

    width: 260px;
    height: 260px;

    background: #f7f8ff;
    border-radius: 50%;

    pointer-events: none;
}

/* Content stays above decorations */
.select-department-inner {
    position: relative;
    z-index: 1;
}

/* Icon */
.select-department-icon {
    display: flex;
    align-items: center;
    justify-content: center;

    width: 58px;
    height: 58px;
    margin-bottom: 22px;

    border-radius: 16px;

    background: #eef0ff;
    color: #4b50c5;

    font-size: 26px;
    font-weight: 800;

    box-shadow: 0 5px 15px rgba(75, 80, 197, 0.08);
}

/* Small label */
.select-department-label {
    display: inline-flex;
    align-items: center;
    gap: 7px;

    margin-bottom: 12px;

    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;

    color: #4b50c5;
}

.select-department-label::before {
    content: "";
    width: 7px;
    height: 7px;

    border-radius: 50%;
    background: #4b50c5;
}

/* Heading */
.select-department-card h1 {
    margin: 0 0 14px;

    color: #17213d;

    font-size: clamp(26px, 4vw, 38px);
    font-weight: 800;
    line-height: 1.15;
    letter-spacing: -0.8px;
}

/* Description */
.select-department-card p {
    max-width: 680px;
    margin: 0;

    color: #66718e;

    font-size: 15px;
    line-height: 1.7;
}

/* Instruction box */
.select-department-instruction {
    display: flex;
    align-items: center;
    gap: 14px;

    margin-top: 30px;
    padding: 16px 18px;

    background: #f8f9ff;
    border: 1px solid #e8eaff;
    border-radius: 12px;

    color: #4d5875;
    font-size: 13px;
    line-height: 1.5;
}

.select-department-instruction-icon {
    display: flex;
    align-items: center;
    justify-content: center;

    flex-shrink: 0;

    width: 34px;
    height: 34px;

    border-radius: 9px;

    background: #e9ebff;
    color: #4b50c5;

    font-size: 16px;
    font-weight: 800;
}

/* Optional bottom accent */
.select-department-accent {
    width: 48px;
    height: 4px;
    margin-top: 28px;

    border-radius: 20px;
    background: #4b50c5;
}


/* =========================================================
   RESPONSIVE
   ========================================================= */

@media (max-width: 768px) {
    .select-department-page {
        padding: 0;
    }

    .select-department-card {
        margin: 16px 0;
        padding: 30px 24px;
        border-radius: 16px;
    }

    .select-department-card h1 {
        font-size: 28px;
    }

    .select-department-card p {
        font-size: 14px;
        line-height: 1.6;
    }

    .select-department-icon {
        width: 52px;
        height: 52px;
        margin-bottom: 18px;
    }
}

@media (max-width: 480px) {
    .select-department-card {
        margin: 10px 0;
        padding: 26px 20px;
        border-radius: 14px;
    }

    .select-department-card h1 {
        font-size: 25px;
        letter-spacing: -0.4px;
    }

    .select-department-card p {
        font-size: 13px;
    }

    .select-department-instruction {
        align-items: flex-start;
        padding: 14px;
        font-size: 12px;
    }
}


/* =========================================================
   SIDEBAR MANAGEMENT NAVIGATION
   Assets / Services / Services List
   ========================================================= */

.sidebar-section-heading {
    padding: 0 12px;
    margin-top: 26px;
    margin-bottom: 10px;

    color: var(--muted);

    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1px;
}

/* Navigation wrapper */
.sidebar-management-nav {
    display: flex;
    flex-direction: column;
    gap: 5px;

    width: 100%;
}

/* Individual management link */
.management-link {
    position: relative;

    display: flex;
    align-items: center;
    gap: 11px;

    width: 100%;
    min-height: 44px;

    padding: 10px 12px;

    border: 1px solid transparent;
    border-radius: 10px;

    background: transparent;
    color: #5f6782;

    font-size: 13px;
    font-weight: 700;

    transition:
        background 0.2s ease,
        border-color 0.2s ease,
        color 0.2s ease,
        transform 0.2s ease;
}

/* Icon box */
.management-link-icon {
    display: flex;
    align-items: center;
    justify-content: center;

    flex: 0 0 auto;

    width: 28px;
    height: 28px;

    border-radius: 8px;

    background: #f0f1ff;
    color: var(--primary);

    font-size: 15px;
    line-height: 1;

    transition:
        background 0.2s ease,
        color 0.2s ease;
}

/* Text */
.management-link-text {
    flex: 1;
    min-width: 0;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Hover */
.management-link:hover {
    background: var(--primary-light);
    border-color: #dfe1ff;
    color: var(--primary);

    transform: translateX(2px);
}

.management-link:hover .management-link-icon {
    background: var(--primary);
    color: #ffffff;
}

/* Active state
   Add class="management-link active" to the current page */
.management-link.active {
    background: var(--primary-light);
    border-color: #dfe1ff;
    color: var(--primary);
    font-weight: 800;
}

.management-link.active .management-link-icon {
    background: var(--primary);
    color: #ffffff;
}

/* Small active indicator */
.management-link.active::before {
    content: "";

    position: absolute;
    left: -1px;
    top: 50%;
    transform: translateY(-50%);

    width: 3px;
    height: 22px;

    border-radius: 0 5px 5px 0;

    background: var(--primary);
}


/* =========================================================
   MOBILE SIDEBAR ADJUSTMENT
   ========================================================= */

@media (max-width: 700px) {

    .sidebar-section-heading {
        margin-top: 22px;
        padding-left: 10px;
    }

    .management-link {
        min-height: 46px;
        padding: 10px;
        font-size: 14px;
    }

    .management-link-icon {
        width: 30px;
        height: 30px;
    }

}

</style>



<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

</head>


<body>

<div class="page-wrapper">


    <!--
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    -->

    <header class="topbar">

        <button
            type="button"
            class="mobile-menu-btn"
            id="mobileMenuBtn"
            aria-label="Open sidebar menu"
            aria-controls="mobileSidebar"
            aria-expanded="false"
        >
            ☰
        </button>

        <div class="brand-area">

            <div class="brand-logo">
                OA
            </div>

            <div>

                <h1 class="brand-title">
                    Organization / Asset Management
                </h1>

                <div class="brand-subtitle">
                    Organization structure and assigned assets
                </div>

            </div>

        </div>


        <div class="topbar-status">

            <span class="status-dot"></span>

            System Online

        </div>

    </header>


    <!--
    |--------------------------------------------------------------------------
    | MAIN LAYOUT
    |--------------------------------------------------------------------------
    -->

    <div class="main-layout">

        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!--
        |--------------------------------------------------------------------------
        | SIDEBAR
        |--------------------------------------------------------------------------
        -->

        <aside class="sidebar" id="mobileSidebar">

        <a
    href="dashboard"
    class="dashboard-link"
>
    <span class="dashboard-icon">⌂</span>
    <span>Dashboard</span>
</a>

<!-- =====================================================
     MANAGEMENT NAVIGATION
     ===================================================== -->

<!-- =====================================================
     MANAGEMENT NAVIGATION
     ===================================================== -->

<div class="sidebar-section-heading">
    MANAGEMENT
</div>

<nav class="sidebar-management-nav">

    <!-- Assets -->
    <a href="assets" class="management-link">
        <span class="management-link-icon">▣</span>
        <span class="management-link-text">Assets</span>
    </a>

    <!-- Services -->
    <a href="services" class="management-link">
        <span class="management-link-icon">⚙</span>
        <span class="management-link-text">Services</span>
    </a>

    <!-- Services List -->
    <a href="services_view" class="management-link">
        <span class="management-link-icon">☷</span>
        <span class="management-link-text">Services List</span>
    </a>

    <!-- Scan QR -->
    <a href="scan-qr" class="management-link">
        <span class="management-link-icon">▦</span>
        <span class="management-link-text">Scan QR</span>
    </a>

</nav>

            <div class="sidebar-heading">
                DIVISIONS
            </div>


            <?php if (empty($divisions)): ?>

                <div class="empty-sidebar">
                    No divisions found.
                </div>

            <?php else: ?>


                <?php foreach ($divisions as $division): ?>

                    <?php

                    $hasSelectedDepartment = false;

                    if (!empty($division['Departments'])) {

                        foreach (
                            $division['Departments']
                            as $department
                        ) {

                            if (
                                (int) $selectedDepartmentId ===
                                (int) $department['Department_id']
                            ) {

                                $hasSelectedDepartment = true;

                                break;

                            }

                        }

                    }

                    ?>


                    <div
                        class="division-block <?= $hasSelectedDepartment ? 'open' : '' ?>"
                    >


                        <!-- DIVISION TITLE -->

                        <div
                            class="division-title"
                            onclick="toggleDivision(this)"
                        >

                            <div class="division-title-content">

                                <span class="division-name">
                                    <?= e(
                                        $division['Division_name']
                                    ) ?>
                                </span>

                                <span class="division-code">
                                    <?= e(
                                        $division['Division_code']
                                    ) ?>
                                </span>

                            </div>


                            <span class="division-toggle">
                                ▼
                            </span>

                        </div>


                        <!-- DEPARTMENT LIST -->

                        <?php if (!empty($division['Departments'])): ?>

                            <div class="department-list">


                                <?php foreach (
                                    $division['Departments']
                                    as $department
                                ): ?>

                                    <?php

                                    $isActive =
                                        (int) $selectedDepartmentId ===
                                        (int) $department['Department_id'];

                                    ?>


                                    <a
                                        class="department-link <?= $isActive ? 'active' : '' ?>"
                                        href="?department_id=<?= (int) $department['Department_id'] ?>"
                                    >

                                        <span>
                                            <?= e(
                                                $department['Department_name']
                                            ) ?>
                                        </span>

                                    </a>


                                <?php endforeach; ?>


                            </div>

                        <?php else: ?>

                            <div class="empty-sidebar">
                                No departments assigned.
                            </div>

                        <?php endif; ?>


                    </div>


                <?php endforeach; ?>


            <?php endif; ?>


        </aside>


        <!--
        |--------------------------------------------------------------------------
        | MAIN CONTENT
        |--------------------------------------------------------------------------
        -->

        <main class="content">


            <?php if ($selectedDepartment): ?>


                <!-- BREADCRUMB -->

                <div class="breadcrumb">

                    <span>
                        Organization
                    </span>

                    <span>/</span>

                    <span>
                        <?= e(
                            $selectedDepartment['Division_name']
                            ?? '-'
                        ) ?>
                    </span>

                    <span>/</span>

                    <strong>
                        <?= e(
                            $selectedDepartment['Department_name']
                            ?? '-'
                        ) ?>
                    </strong>

                </div>


                <!-- PAGE HEADING -->

                <div class="page-heading">

                    <div>

                        <h1>
                            <?= e(
                                $selectedDepartment['Department_name']
                                ?? 'Department'
                            ) ?>
                        </h1>

                        <p>
                            Users, assigned assets, and asset information
                        </p>

                    </div>


                    <a
                        class="refresh-link"
                        href="?department_id=<?= (int) $selectedDepartmentId ?>"
                    >

                        <span>
                            ↻
                        </span>

                        Refresh Data

                    </a>

                </div>


                <!-- OVERVIEW CARDS -->

                <section class="overview-grid">


                    <div class="overview-card">

                        <div class="overview-label">
                            TOTAL USERS
                        </div>

                        <div class="overview-value">
                            <?= $totalUsers ?>
                        </div>

                        <div class="overview-description">
                            Active users under this department
                        </div>

                    </div>


                    <div class="overview-card">

                        <div class="overview-label">
                            TOTAL ASSETS
                        </div>

                        <div class="overview-value">
                            <?= $totalAssets ?>
                        </div>

                        <div class="overview-description">
                            Total quantity of assigned assets
                        </div>

                    </div>


                </section>


                <!-- USERS AND ASSETS SECTION -->

                <section class="section">


                    <div class="section-header">

                        <div>

                            <h2 class="section-title">
                                Users and Assigned Assets
                            </h2>

                            <div class="section-subtitle">
                                Personnel and asset information under this department
                            </div>

                        </div>


                        <div class="section-counter">
                            <?= $totalUsers ?> User(s)
                        </div>

                    </div>


                    <?php if (empty($users)): ?>


                        <div class="section-card">

                            <div class="empty-state">

                                <strong>
                                    No users found
                                </strong>

                                There are no active users assigned to this department.

                            </div>

                        </div>


                    <?php else: ?>


                        <div class="user-tiles">


                            <?php foreach ($users as $user): ?>

                                <?php

                                $fullname = trim(
                                    $user['User_Fullname']
                                    ?? ''
                                );

                                $assets = $user['Assets'] ?? [];

                                $initials = getInitials($fullname);

                                $userId = (int) (
                                    $user['User_department_id']
                                    ?? 0
                                );

                                ?>


                                <!-- USER TILE -->

                                <article class="user-tile">


                                    <!-- USER HEADER -->

                                    <div class="user-tile-header">


                                        <div class="user-tile-avatar">
                                            <?= e($initials) ?>
                                        </div>


                                        <div class="user-tile-user-info">

                                            <div class="user-tile-user-name">

                                                <?= e(
                                                    $fullname
                                                    ?: 'Unnamed User'
                                                ) ?>

                                            </div>


                                            <div class="user-tile-user-id">

                                                User ID:
                                                <?= $userId ?>

                                            </div>

                                        </div>


                                    </div>


                                    <!-- USER BODY -->

                                    <div class="user-tile-body">


                                        <!-- USER DETAILS -->

                                        <div class="user-details-grid">


                                            <div class="user-detail-item">

                                                <div class="user-detail-label">
                                                    Full Name
                                                </div>

                                                <div class="user-detail-value">
                                                    <?= e(
                                                        $fullname ?: '-'
                                                    ) ?>
                                                </div>

                                            </div>


                                            <div class="user-detail-item">

                                                <div class="user-detail-label">
                                                    Department
                                                </div>

                                                <div class="user-detail-value">
                                                    <?= e(
                                                        $user['Department_name']
                                                        ?? '-'
                                                    ) ?>
                                                </div>

                                            </div>


                                            <div class="user-detail-item">

                                                <div class="user-detail-label">
                                                    Division
                                                </div>

                                                <div class="user-detail-value">
                                                    <?= e(
                                                        $user['Division_name']
                                                        ?? '-'
                                                    ) ?>
                                                </div>

                                            </div>


                                            <div class="user-detail-item">

                                                <div class="user-detail-label">
                                                    Asset Records
                                                </div>

                                                <div class="user-detail-value">
                                                    <?= count($assets) ?>
                                                    record(s)
                                                </div>

                                            </div>


                                        </div>


                                        <!-- ASSIGNED ASSETS TITLE -->

                                        <div class="tile-section-title">

                                            <h3>
                                                Assigned Assets
                                            </h3>

                                            <span class="tile-asset-count">
                                                <?= count($assets) ?>
                                            </span>

                                        </div>


                                        <?php if (!empty($assets)): ?>


                                            <!-- VIEW ASSETS BUTTON -->

                                            <button
                                                type="button"
                                                class="view-assets-button"
                                                onclick="openAssetModal(<?= $userId ?>)"
                                            >

                                                <span>
                                                    ▣
                                                </span>

                                                View Assigned Assets

                                            </button>


                                        <?php else: ?>


                                            <!-- NO ASSETS MESSAGE -->

                                            <div class="no-assets-tile">
                                                <span>
                                                    This user has no active assigned assets.
                                                </span>
                                                <span>
                                                    Or this user Assigned to shifting asset.
                                                </span>
                                            </div>


                                        <?php endif; ?>


                                    </div>


                                </article>


                            <?php endforeach; ?>


                        </div>


                    <?php endif; ?>


                </section>


            <?php else: ?>


                <!-- INITIAL LANDING STATE -->

               <div class="select-department-page">

    <div class="select-department-card">

        <div class="select-department-inner">

            <div class="select-department-icon">
                ⌂
            </div>

            <div class="select-department-label">
                Organization / Asset Management
            </div>

            <h1>Select a Department</h1>

            <p>
                Click a Division from the left sidebar to view its departments.
                Then select a department to display its users and assigned assets.
            </p>

            <div class="select-department-instruction">
                <div class="select-department-instruction-icon">
                    ✓
                </div>

                <div>
                    Select a division from the sidebar, then choose a department
                    to view its assigned personnel and assets.
                </div>
            </div>

            <div class="select-department-accent"></div>

        </div>

    </div>

</div>


            <?php endif; ?>


        </main>


    </div>


</div>


<!--
|--------------------------------------------------------------------------
| ASSET MODAL
|--------------------------------------------------------------------------
-->

<div
    id="assetModalOverlay"
    class="asset-modal-overlay"
    onclick="closeAssetModalOutside(event)"
>


    <div class="asset-modal">


        <!-- MODAL HEADER -->

        <div class="asset-modal-header">

            <div class="asset-modal-title-wrapper">

                <h2
                    id="assetModalTitle"
                    class="asset-modal-title"
                >
                    Assigned Assets
                </h2>

                <div
                    id="assetModalSubtitle"
                    class="asset-modal-subtitle"
                >
                    User assigned assets
                </div>

            </div>


            <button
                type="button"
                class="asset-modal-close"
                onclick="closeAssetModal()"
                aria-label="Close modal"
            >
                ×
            </button>

        </div>


        <!-- MODAL BODY -->

        <div class="asset-modal-body">


            <?php foreach ($users as $user): ?>

                <?php

                $modalUserId = (int) (
                    $user['User_department_id']
                    ?? 0
                );

                $modalFullname = trim(
                    $user['User_Fullname']
                    ?? ''
                );

                $modalAssets = $user['Assets'] ?? [];

                ?>


                <?php if (!empty($modalAssets)): ?>


                    <div
                        id="modalUser<?= $modalUserId ?>"
                        class="modal-user-section"
                    >


                        <div class="modal-assets-grid">


                            <?php foreach ($modalAssets as $asset): ?>

                                <?php

                                $isOperational = (int) (
                                    $asset['is_operational']
                                    ?? 0
                                );

                                ?>


                                <div class="modal-asset-card">


                                    <!-- ASSET HEADER -->

                                    <div class="modal-asset-top">


                                        <div class="modal-asset-heading">

                                            <div class="modal-asset-name">
                                                <?= e(
                                                    $asset['Asset_name']
                                                    ?? '-'
                                                ) ?>
                                            </div>

                                            <div class="modal-asset-description">
                                                <?= e(
                                                    $asset['Asset_description']
                                                    ?? '-'
                                                ) ?>
                                            </div>

                                        </div>


                                        <span
                                            class="modal-asset-status <?= !$isOperational ? 'not-operational' : '' ?>"
                                        >

                                            <?= $isOperational
                                                ? 'Operational'
                                                : 'Not Operational'
                                            ?>

                                        </span>


                                    </div>


                                    <!-- ASSET DETAILS -->

                                    <div class="modal-asset-details">


                                        <div class="modal-asset-detail">

                                            <div class="modal-asset-detail-label">
                                                Category
                                            </div>

                                            <div class="modal-asset-detail-value strong">
                                                <?= e(
                                                    $asset['Assets_category_name']
                                                    ?? '-'
                                                ) ?>
                                            </div>

                                        </div>


                                        <div class="modal-asset-detail">

                                            <div class="modal-asset-detail-label">
                                                Category Description
                                            </div>

                                            <div class="modal-asset-detail-value">
                                                <?= e(
                                                    $asset['Assets_category_description']
                                                    ?? '-'
                                                ) ?>
                                            </div>

                                        </div>


                                        <div class="modal-asset-detail">

                                            <div class="modal-asset-detail-label">
                                                Model
                                            </div>

                                            <div class="modal-asset-detail-value">
                                                <?= e(
                                                    $asset['Asset_Model']
                                                    ?? '-'
                                                ) ?>
                                            </div>

                                        </div>


                                        <div class="modal-asset-detail">

                                            <div class="modal-asset-detail-label">
                                                Serial Number
                                            </div>

                                            <div class="modal-asset-detail-value">
                                                <?= e(
                                                    $asset['Asset_Serial_Number']
                                                    ?? '-'
                                                ) ?>
                                            </div>

                                        </div>


                                        <div class="modal-asset-detail">

                                            <div class="modal-asset-detail-label">
                                                Purchase Date
                                            </div>

                                            <div class="modal-asset-detail-value">
                                                <?= formatDateValue(
                                                    $asset['Purchase_Date']
                                                    ?? null
                                                ) ?>
                                            </div>

                                        </div>


                                        <div class="modal-asset-detail">

                                            <div class="modal-asset-detail-label">
                                                Purchase Cost
                                            </div>

                                            <div class="modal-asset-detail-value">
                                                <?= formatCurrencyValue(
                                                    $asset['Purchase_Cost']
                                                    ?? null
                                                ) ?>
                                            </div>

                                        </div>


                                        <div class="modal-asset-detail">

                                            <div class="modal-asset-detail-label">
                                                Quantity
                                            </div>

                                            <div class="modal-asset-detail-value">
                                                <?= (int) (
                                                    $asset['Assets_Quantity']
                                                    ?? 0
                                                ) ?>
                                            </div>

                                        </div>


                                        <div class="modal-asset-detail">

                                            <div class="modal-asset-detail-label">
                                                Performance Status
                                            </div>

                                            <div class="modal-asset-detail-value">
                                                <?= e(
                                                    $asset['performance_status']
                                                    ?? '-'
                                                ) ?>
                                            </div>

                                        </div>


                                        <div class="modal-asset-detail">

                                            <div class="modal-asset-detail-label">
                                                Efficiency
                                            </div>

                                            <div class="modal-asset-detail-value">

                                                <?php if (
                                                    $asset['efficiency_percentage']
                                                    !== null
                                                    &&
                                                    $asset['efficiency_percentage']
                                                    !== ''
                                                ): ?>

                                                    <?= e(
                                                        $asset['efficiency_percentage']
                                                    ) ?>%

                                                <?php else: ?>

                                                    -

                                                <?php endif; ?>

                                            </div>

                                        </div>


                                        <div class="modal-asset-detail">

                                            <div class="asset-detail-label">Checked Date</div>
    <div class="asset-detail-value">
        <?php
            $checkedDate = $asset['LastCheckedAt'] ?? null;

            if (!empty($checkedDate)) {
                echo htmlspecialchars(
                    date('F d, Y', strtotime($checkedDate))
                );
            } else {
                echo 'Not yet checked';
            }
        ?>
    </div>

                                        </div>


                                    </div>


                                    <!-- TAGGING DESCRIPTION -->

                                    <?php if (
                                        !empty(
                                            $asset['Tagging_description']
                                        )
                                    ): ?>

                                        <div class="modal-asset-notes">

                                            <strong>
                                                Tagging Description:
                                            </strong>

                                            <?= e(
                                                $asset['Tagging_description']
                                            ) ?>

                                        </div>

                                    <?php endif; ?>


                                    <!-- ASSET REMARKS -->

                                    <?php if (
                                        !empty(
                                            $asset['Asset_Remarks']
                                        )
                                    ): ?>

                                        <div class="modal-asset-notes">

                                            <strong>
                                                Remarks:
                                            </strong>

                                            <?= e(
                                                $asset['Asset_Remarks']
                                            ) ?>

                                        </div>

                                    <?php endif; ?>


                                    <?php
                                    /*
                                     * QR DATA:
                                     * Exact ASSETS_per_USER_DEPARTMENT fields requested.
                                     * IsActive and Updated_at are intentionally excluded.
                                     */
                                   $qrPayload = (string) ($asset['asset_per_user_department_id'] ?? '');
                                    $qrPayloadString = json_encode(
                                        $qrPayload,
                                        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                                    );
                                    ?>

                                    <!-- GENERATE QR CODE BUTTON -->

                                    <button
                                        type="button"
                                        class="generate-qr-button"
                                        onclick="openQRModal(
                                            <?= (int) ($asset['asset_per_user_department_id'] ?? 0) ?>,
                                            <?= htmlspecialchars(json_encode($asset['Asset_name'] ?? '-'), ENT_QUOTES, 'UTF-8') ?>,
                                            <?= htmlspecialchars(json_encode($asset['Asset_Serial_Number'] ?? '-'), ENT_QUOTES, 'UTF-8') ?>,
                                            <?= htmlspecialchars(json_encode($modalFullname ?: '-'), ENT_QUOTES, 'UTF-8') ?>,
                                            <?= htmlspecialchars(json_encode($qrPayloadString), ENT_QUOTES, 'UTF-8') ?>
                                        )"
                                    >
                                        <span>■</span>
                                        Generate QR Code
                                    </button>


                                </div>


                            <?php endforeach; ?>


                        </div>


                    </div>


                <?php endif; ?>


            <?php endforeach; ?>


        </div>


    </div>


</div>


<!--
|--------------------------------------------------------------------------
| QR CODE MODAL
|--------------------------------------------------------------------------
-->

<div
    id="qrModalOverlay"
    class="qr-modal-overlay"
    onclick="closeQRModalOutside(event)"
>
    <div class="qr-modal">

        <div class="qr-modal-header">
            <div>
                <h2 class="qr-modal-title">Asset QR Code</h2>
                <div class="qr-modal-subtitle">
                    Scan this QR code to identify the asset
                </div>
            </div>

            <button
                type="button"
                class="qr-modal-close"
                onclick="closeQRModal()"
                aria-label="Close QR modal"
            >
                ×
            </button>
        </div>

        <div class="qr-modal-body">

            <div
                id="assetQRCode"
                class="qr-code-wrapper"
            ></div>

            <div class="qr-record-summary">

                <div class="qr-summary-row">
                    <span class="qr-summary-label">Asset Record ID</span>
                    <span
                        id="qrRecordId"
                        class="qr-summary-value"
                    >-</span>
                </div>

                <div class="qr-summary-row">
                    <span class="qr-summary-label">Asset Name</span>
                    <span
                        id="qrAssetName"
                        class="qr-summary-value"
                    >-</span>
                </div>

                <div class="qr-summary-row">
                    <span class="qr-summary-label">Serial Number</span>
                    <span
                        id="qrSerialNumber"
                        class="qr-summary-value"
                    >-</span>
                </div>

                <div class="qr-summary-row">
                    <span class="qr-summary-label">Assigned User</span>
                    <span
                        id="qrAssignedUser"
                        class="qr-summary-value"
                    >-</span>
                </div>

            </div>

            <div class="qr-modal-actions">

                <button
                    type="button"
                    class="qr-action-button qr-print-button"
                    onclick="printAssetQRCode()"
                >
                    ▣ Print QR
                </button>

                <button
                    type="button"
                    class="qr-action-button qr-download-button"
                    onclick="downloadAssetQRCode()"
                >
                    ↓ Download QR
                </button>

            </div>

        </div>
    </div>
</div>


<script>

/*
|--------------------------------------------------------------------------
| TOGGLE DIVISION
|--------------------------------------------------------------------------
*/

function toggleDivision(titleElement) {

    const divisionBlock =
        titleElement.closest('.division-block');

    if (!divisionBlock) {
        return;
    }

    divisionBlock.classList.toggle('open');

}


/*
|--------------------------------------------------------------------------
| KEEP SELECTED DEPARTMENT DIVISION OPEN
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', function () {

    const selectedDepartment =
        document.querySelector('.department-link.active');

    if (selectedDepartment) {

        const divisionBlock =
            selectedDepartment.closest('.division-block');

        if (divisionBlock) {
            divisionBlock.classList.add('open');
        }

    }

});


/*
|--------------------------------------------------------------------------
| OPEN ASSET MODAL
|--------------------------------------------------------------------------
*/

function openAssetModal(userId) {

    const overlay =
        document.getElementById('assetModalOverlay');

    const title =
        document.getElementById('assetModalTitle');

    const subtitle =
        document.getElementById('assetModalSubtitle');

    const selectedUserSection =
        document.getElementById('modalUser' + userId);

    if (!overlay || !selectedUserSection) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Hide all modal user sections first
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.modal-user-section')
        .forEach(function (section) {

            section.classList.remove('active');

        });


    /*
    |--------------------------------------------------------------------------
    | Show selected user's assets
    |--------------------------------------------------------------------------
    */

    selectedUserSection.classList.add('active');


    /*
    |--------------------------------------------------------------------------
    | Get user name from corresponding tile
    |--------------------------------------------------------------------------
    */

    const userTiles =
        document.querySelectorAll('.user-tile');

    let selectedUserName = 'User';

    userTiles.forEach(function (tile) {

        const button =
            tile.querySelector('.view-assets-button');

        if (
            button &&
            button.getAttribute('onclick') ===
            'openAssetModal(' + userId + ')'
        ) {

            const nameElement =
                tile.querySelector('.user-tile-user-name');

            if (nameElement) {
                selectedUserName =
                    nameElement.textContent.trim();
            }

        }

    });


    title.textContent =
        selectedUserName + ' — Assigned Assets';

    subtitle.textContent =
        'Complete list of active assets assigned to this user';


    overlay.classList.add('show');

    document.body.classList.add('modal-open');

}


/*
|--------------------------------------------------------------------------
| CLOSE ASSET MODAL
|--------------------------------------------------------------------------
*/

function closeAssetModal() {

    const overlay =
        document.getElementById('assetModalOverlay');

    if (!overlay) {
        return;
    }

    overlay.classList.remove('show');

    document.body.classList.remove('modal-open');

}


/*
|--------------------------------------------------------------------------
| CLOSE WHEN CLICKING OUTSIDE MODAL
|--------------------------------------------------------------------------
*/

function closeAssetModalOutside(event) {

    if (
        event.target.id === 'assetModalOverlay'
    ) {

        closeAssetModal();

    }

}


/*
|--------------------------------------------------------------------------
| CLOSE MODAL USING ESCAPE KEY
|--------------------------------------------------------------------------
*/

document.addEventListener('keydown', function (event) {

    if (event.key === 'Escape') {
        closeAssetModal();
    }

});

/*
|--------------------------------------------------------------------------
| QR CODE MODAL
|--------------------------------------------------------------------------
*/

let currentQRPayload = null;
let currentQRFileName = 'asset-qr';


function openQRModal(
    assetRecordId,
    assetName,
    serialNumber,
    assignedUser,
    qrPayloadString
) {
    const overlay = document.getElementById('qrModalOverlay');
    const qrContainer = document.getElementById('assetQRCode');

    if (!overlay || !qrContainer) {
        return;
    }

    /*
     * Clear the previous QR code before generating a new one.
     */
    qrContainer.innerHTML = '';

    /*
     * Use the complete database payload passed from PHP.
     * IsActive and Updated_at are excluded.
     */
    try {
        currentQRPayload = JSON.parse(qrPayloadString);
    } catch (error) {
        console.error('Invalid QR payload:', error);

        currentQRPayload = {
            asset_per_user_department_id: assetRecordId,
            Asset_name: assetName,
            Asset_Serial_Number: serialNumber,
            Assigned_User: assignedUser
        };
    }

    document.getElementById('qrRecordId').textContent = assetRecordId || '-';
    document.getElementById('qrAssetName').textContent = assetName || '-';
    document.getElementById('qrSerialNumber').textContent = serialNumber || '-';
    document.getElementById('qrAssignedUser').textContent = assignedUser || '-';

    currentQRFileName = 'asset-' + (assetRecordId || 'qr');

    if (typeof QRCode === 'undefined') {
        alert('QR Code library is not loaded.');
        return;
    }

    new QRCode(qrContainer, {
        text: JSON.stringify(currentQRPayload),
        width: 220,
        height: 220,
        colorDark: '#111111',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.M
    });

    overlay.classList.add('show');
}


function closeQRModal() {
    const overlay = document.getElementById('qrModalOverlay');

    if (overlay) {
        overlay.classList.remove('show');
    }
}


function closeQRModalOutside(event) {
    if (event.target.id === 'qrModalOverlay') {
        closeQRModal();
    }
}


function getCurrentQRImage() {
    const qrContainer = document.getElementById('assetQRCode');

    if (!qrContainer) {
        return null;
    }

    const canvas = qrContainer.querySelector('canvas');
    const image = qrContainer.querySelector('img');

    if (canvas) {
        return canvas.toDataURL('image/png');
    }

    if (image) {
        return image.src;
    }

    return null;
}


function downloadAssetQRCode() {
    const imageData = getCurrentQRImage();

    if (!imageData) {
        alert('QR code is not ready yet.');
        return;
    }

    const link = document.createElement('a');
    link.href = imageData;
    link.download = currentQRFileName + '.png';

    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}


function printAssetQRCode() {
    const imageData = getCurrentQRImage();

    if (!imageData) {
        alert('QR code is not ready yet.');
        return;
    }

    const title = document.getElementById('qrAssetName').textContent || 'Asset QR Code';
    const printWindow = window.open('', '_blank', 'width=500,height=650');

    if (!printWindow) {
        alert('Please allow pop-ups to print the QR code.');
        return;
    }

    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>${title} - QR Code</title>
            <style>
                body {
                    margin: 0;
                    padding: 35px;
                    text-align: center;
                    font-family: Arial, sans-serif;
                }

                h2 {
                    margin-bottom: 22px;
                    font-size: 20px;
                }

                img {
                    width: 280px;
                    height: 280px;
                    image-rendering: pixelated;
                }
            </style>
        </head>
        <body>
            <h2>${title}</h2>
            <img src="${imageData}" alt="Asset QR Code">
        </body>
        </html>
    `);

    printWindow.document.close();
    printWindow.focus();

    setTimeout(function () {
        printWindow.print();
        printWindow.close();
    }, 500);
}





/* =========================================================
   MOBILE SIDEBAR TOGGLE
   ========================================================= */
document.addEventListener('DOMContentLoaded', function () {
    const menuButton = document.getElementById('mobileMenuBtn');
    const sidebar = document.getElementById('mobileSidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (!menuButton || !sidebar || !overlay) return;

    function closeMobileSidebar() {
        document.body.classList.remove('sidebar-open');
        menuButton.setAttribute('aria-expanded', 'false');
        menuButton.setAttribute('aria-label', 'Open sidebar menu');
    }

    function toggleMobileSidebar() {
        const isOpen = document.body.classList.toggle('sidebar-open');
        menuButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        menuButton.setAttribute('aria-label', isOpen ? 'Close sidebar menu' : 'Open sidebar menu');
    }

    menuButton.addEventListener('click', toggleMobileSidebar);
    overlay.addEventListener('click', closeMobileSidebar);

    sidebar.addEventListener('click', function (event) {
        if (event.target.closest('a.department-link')) {
            closeMobileSidebar();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') closeMobileSidebar();
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth > 700) closeMobileSidebar();
    });
});

</script>


</body>

</html>