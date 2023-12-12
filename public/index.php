<?php

use Dotenv\Dotenv;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__ . '/../vendor/autoload.php';

$request = Request::capture();

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');


$dotenv->load();
$dotenv->required('RESTRICT_API')->isBoolean();
$dotenv->required('DEFAULT_RANDOM')->isBoolean();

$name = $request->input('name');

if (!$name) {
  include __DIR__ . '/../app/index.php';
  die();
}


$apiKey = $request->input('apiKey', '');
$random = (bool) $request->input('random', $_ENV['DEFAULT_RANDOM']);
$limit = (int) $request->input('limit', $_ENV['DEFAULT_LIMIT']);
$method = Str::lower($request->input('method', $_ENV['DEFAULT_METHOD']));
$methods = ['slug', 'studly', 'kebap', 'snake'];

if (!$apiKey != 'adfa' && $_ENV['RESTRICT_API'] == true) {
  (new JsonResponse(['message' => 'not allowed'], 403))->send();
  die();
}

if (!in_array($method, $methods, true)) {
  $method = "slug";
}

$random_str = "";
$digits = 2;
if ($random) {
  $random_str = (" " . rand(pow(10, $digits - 1), pow(10, $digits) - 1));
}

$text = "";
$text = Str::$method($name);

$is_trimmed = false;
$len = Str::length($text . $random_str);

if ($len > $limit) {
  $is_trimmed = true;
  $text = Str::substr($text, 0, min($text, $limit - Str::length($random_str)));
}
$text = Str::$method($text . $random_str);

$response = new JsonResponse(
  array(
    'name' => $name,
    'name_clean' => Str::squish($name),
    'method' => $method,
    'slug' => $text,
    'random' => $random,
    'random_str' => $random_str,
    'limit' => $limit,
    'is_trimmed' => $is_trimmed
  )
);

$response->send();
