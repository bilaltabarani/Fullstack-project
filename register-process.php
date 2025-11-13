<?php
session_start();
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'fullstacklogin';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL!');
}
if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
	exit('Please complete the registration form!');
}
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
	exit('Please complete the registration form');
}
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	exit('Email is not valid!');
}
if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
	exit('Username is not valid!');
}
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		echo 'Username already exists! Please choose another!';
	} else {
        $registered = date('Y-m-d H:i:s');
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        if ($stmt = $con->prepare('INSERT INTO accounts (username, password, email, registered) VALUES (?, ?, ?, ?)')) {
	        $stmt->bind_param('ssss', $_POST['username'], $password, $_POST['email'], $registered);
	        $stmt->execute();
	        header('Location: ./index.php?registered=success');
            exit;
        } else {
	        echo 'Could not prepare statement!';
        }
	}
	$stmt->close();
} else {
	echo 'Could not prepare statement!';
}
$con->close();
?>