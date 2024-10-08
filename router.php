<?php
require_once 'config.php';
// Set timezone
date_default_timezone_set("Asia/Bangkok");

// Start session
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Method
function get($route, $path_to_include)
{
  if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    route($route, $path_to_include);
  }
}
function post($route, $path_to_include)
{
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    route($route, $path_to_include);
  }
}
function put($route, $path_to_include)
{
  if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    route($route, $path_to_include);
  }
}
function patch($route, $path_to_include)
{
  if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
    route($route, $path_to_include);
  }
}
function delete($route, $path_to_include)
{
  if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    route($route, $path_to_include);
  }
}
function any($route, $path_to_include)
{
  route($route, $path_to_include);
}

// Route
function route($route, $path_to_include)
{
  // Callback function
  if (is_callable($path_to_include)) {
    call_user_func($path_to_include);
    exit();
  }

  // If you are using this in the root directory
  // $ROOT = $_SERVER['DOCUMENT_ROOT'];

  // If you are using this in a subdirectory
  $ROOT = __DIR__;

  // 404 Page
  if ($route == "/404") {
    include_once("$ROOT/$path_to_include");
    exit();
  }

  // Get URI and filter illegal characters
  $request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);


  // Remove the subdirectory name from the request URL
  // $request_url = str_replace(dirname($_SERVER['SCRIPT_NAME']), '', $request_url);

  // Remove trailing slash
  $request_url = rtrim($request_url, '/');
  // Remove query string
  $request_url = strtok($request_url, '?');
  // Remove trailing slash
  $request_url = rtrim($request_url, '/');
  // Split Route
  $route_parts = explode('/', $route);
  // Split Request URL
  $request_url_parts = explode('/', $request_url);
  
  // Remove empty string
  array_shift($route_parts);
  array_shift($request_url_parts);

  // If the route is empty and the request URL is empty
  if ($route_parts[0] === '' && count($request_url_parts) === 0) {
    include_once("$ROOT/$path_to_include");
    exit();
  }

  // If the route and request URL have different lengths
  if (count($route_parts) != count($request_url_parts)) {
    return;
  }

  // Route parameters
  $parameters = [];
  for ($__i__ = 0; $__i__ < count($route_parts); $__i__++) {
    $route_part = $route_parts[$__i__];
    // If the route part is a parameter (starts with $)
    if (preg_match("/^[$]/", $route_part)) {
      // Remove the $ character
      $route_part = ltrim($route_part, '$');
      // Add the parameter to the parameters array
      array_push($parameters, $request_url_parts[$__i__]);
      // Create a variable with the parameter name and assign the value
      $$route_part = $request_url_parts[$__i__];
    } else if ($route_parts[$__i__] != $request_url_parts[$__i__]) {
      return;
    }
  }

  // Include the file
  include_once("$ROOT/$path_to_include");
  exit();
}