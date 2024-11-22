<?php
session_start();
if (!isset($_SESSION['id'])) {
    include 'autho_register.php';
    exit;
}else{
    include 'account.php';
    exit;
}

?>
