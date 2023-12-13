<?php

use Dotenv\Dotenv;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Sqids\Sqids;

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
$is_random = (bool) $request->input('random', false);
$limit = (int) $request->input('limit', $_ENV['DEFAULT_LIMIT']);
$method = Str::lower($request->input('method', $_ENV['DEFAULT_METHOD']));
$methods = ['slug', 'studly', 'kebap', 'snake'];
$id = $request->input('id', null);
$is_hash = $request->input('hash', false);

if (!$apiKey != 'adfa' && $_ENV['RESTRICT_API'] == true) {
  (new JsonResponse(['message' => 'not allowed'], 403))->send();
  die();
}

if (!in_array($method, $methods, true)) {
  $method = "slug";
}

$random_str = "";
$digits = 2;
$random = null;
if ($is_random) {
  $random_int = (int) rand(pow(10, $digits - 1), pow(10, $digits) - 1);
  $random_str = ("-" . $random_int);
  if ($is_hash == true) {
    $hash = (new Sqids(minLength: 3))->encode([(int) $random_int]);
    $random_str = "-" . $hash;
  }
}

$id_str = "";
if ($id !== null) {
  $id_str = "-" . (int) $id;
  if ($is_hash == true) {
    $hash = (new Sqids(minLength: 3))->encode([(int) $id]);
    $id_str = "-" . $hash;
  }
}

$text = "";
$text = Str::$method($name);

$is_trimmed = false;
$len = Str::length($text . $random_str . $id_str);

if ($len > $limit) {
  $is_trimmed = true;
  $text = Str::substr($text, 0, min($text, $limit - Str::length($random_str . $id_str)));
}
$text = Str::$method($text . $id_str . $random_str);

$data = array(
  'name' => $name,
  'name_clean' => Str::squish($name),
  'method' => $method,
  'slug' => $text,
  'random' => $is_random,
  'limit' => $limit,
  'is_trimmed' => $is_trimmed,
  'is_hashed' => $is_hash
);
if ($id) {
  $data['id'] = $id;
}
if ($is_random) {
  $data['random_int'] = $random_int;
}
if ($is_hash) {
  $data['hashed_id'] = $hash;
}

$response = new JsonResponse(
  $data
);

$response->send();
