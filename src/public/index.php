<?php

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];
print_r($requestUri,$requestMethod);
if ($requestUri === '/registration') {
    if ($requestMethod === 'GET') {
        require_once  './registration_form.php';
    } elseif ($requestMethod === 'POST') {
        require_once  './handle_registration_form.php';
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
} elseif ($requestUri === '/login') {
    if ($requestMethod === 'GET') {
        require_once  './login_form.php';
    } elseif ($requestMethod === 'POST') {
        require_once  './handle_login.php';
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
}  elseif ($requestUri === '/catalog') {
    if ($requestMethod === 'GET') {
        require_once  './catalog.php';
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
}   elseif ($requestUri === '/profile') {
    if ($requestMethod === 'GET') {
        require_once  './user_profile_form.php';
    } elseif ($requestMethod === 'POST') {
        require_once  './handle_user_profile.php';
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
}  elseif ($requestUri === '/add_product') {
    if ($requestMethod === 'GET') {
        require_once  './add_product_form.php';
    } elseif ($requestMethod === 'POST') {
        require_once  './handle_add_product.php';
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
} elseif ($requestUri === '/cart') {
    if ($requestMethod === 'GET') {
        require_once  './cart.php';
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
} else {
    http_response_code(404);
   require_once  './404.php';
}