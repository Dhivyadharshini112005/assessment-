<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function require_employee() {
    if (empty($_SESSION['employee_id'])) {
        header("Location: ../login.php");
        exit;
    }
}
function require_admin() {
    if (empty($_SESSION['admin_id'])) {
        header("Location: ../admin/login.php");
        exit;
    }
}
?>