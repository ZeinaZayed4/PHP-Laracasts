<?php

use Core\App;
use Core\Database;
use Core\Validator;

$db = App::resolve(Database::class);

$email = $_POST['email'];
$password = $_POST['password'];

// validate the form inputs.
$errors = [];
if (!validator::email($email)) {
	$errors['email'] = 'Please provide a valid email address.';
}

if (!validator::string($password)) {
	$errors['password'] = 'Please provide a valid password.';
}

if (!empty($errors)) {
	view('session/create.view.php', [
		'errors' => $errors
	]);
}

$user = $db->query('SELECT * FROM users WHERE email = :email', [
	':email' => $email
])->find();

if ($user) {
	if (password_verify($password, $user['password'])) {
		login([
			'email' => $email
		]);
		
		header('Location: /');
		exit();
	}
}

view('session/create.view.php', [
	'errors' => [
		'email' => 'No matching account found for that email address and password.'
	]
]);
