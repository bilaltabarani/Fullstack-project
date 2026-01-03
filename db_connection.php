<?php
session_start();

$con = mysqli_connect('localhost', 'root', '', 'fullstacklogin');

if (!$con) {
    die(json_encode(["error" => "Database connection failed: " . mysqli_connect_error()]));
}
