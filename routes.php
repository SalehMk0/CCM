
<?php
$request_uri = $_SERVER['REQUEST_URI'];

$routes = [
    '/' => 'homePage',
    '/register' => 'registerPage',
    
];

function homePage() {
    header('Location: index.php');
    exit(); // Ensure no further code is executed after the redirect
}
function registerPage() {
    header('Location: register.php');
    exit();
}



if (array_key_exists($request_uri, $routes)) {
    $action = $routes[$request_uri];
    if (function_exists($action)) {
        call_user_func($action);
    } else {
        echo "404 - Not Found";
    }
} else {
    echo "404 - Not Found";
}
?>