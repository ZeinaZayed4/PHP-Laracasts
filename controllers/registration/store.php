<?php

use Core\App;
use Core\Database;
use Core\Validator;

$email = $_POST['email'];
$password = $_POST['password'];

// validate the form inputs.
$errors = [];
if (!validator::email($email)) {
	$errors['email'] = 'Please provide a valid email address.';
}

if (!validator::string($password, 7, 255)) {
	$errors['password'] = 'Please provide a password of at least 7 characters.';
}

if (!empty($errors)) {
	view('registration/create.view.php', [
		'errors' => $errors
	]);
}

$db = App::resolve(Database::class);
// check if the account already exists
$user = $db->query('SELECT * FROM users WHERE email = :email', [
	':email' => $email
])->find();

// if yes, redirect to a login page.
if ($user) {
	// then someone with that email already exists and has an account.
	// if yes, redirect to a login page.
	header('Location: /');
	exit();
} else {
// if not, save one to the database, and then log the user in, and redirect.
	$db->query('INSERT INTO users(email, password) VALUES (:email, :password)', [
		':email' => $email,
		':password' => $password
	]);
	
	// mark that the user has logged in.
	$_SESSION['user'] = [
		'email' => $email
	];
	
	header('Location: /');
	exit();
}

