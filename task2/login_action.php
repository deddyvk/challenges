<?php
session_start();

include "controller2.php";

$username = $_POST["username"];
$password = $_POST["password"];

$account = new BankAccount2();
$account->loginCheck($username,$password);

header("Location:user.php");
?>