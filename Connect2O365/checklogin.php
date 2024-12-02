<?php
session_start();
if(!isset($_SESSION['username'])){
    $_SESSION['retURL'] = $_SERVER['REQUEST_URI'];
    header('Location:login.php');
}

?>