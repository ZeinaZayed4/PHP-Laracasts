<?php

use Core\Response;
use JetBrains\PhpStorm\NoReturn;

#[NoReturn] function dd($value): void
{
	echo '<pre>';
	var_dump($value);
	echo '</pre>';
	die();
}

function urlIs($value): bool
{
	return $_SERVER['REQUEST_URI'] === $value;
}

function abort($code = 404)
{
	http_response_code($code);
	
	require base_path("views/{$code}.php");
	
	die();
}

function authorize($condition, $status = Response::FORBIDDEN): void
{
	if (!$condition) {
		abort($status);
	}
}

function base_path($path): string
{
	return BASE_PATH . $path;
}

function view($path, $attributes = [])
{
	extract($attributes);
	require base_path('views/' . $path);
}
