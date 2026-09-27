<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function vyzadujPrihlaseni() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

function vyzadujAdmina() {
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: index.php');
        exit;
    }
}