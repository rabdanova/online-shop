<?php

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];
print_r($requestUri,$requestMethod);
if ($requestUri === '/registration') {
    if ($requestMethod === 'GET') {
        require_once './registration/registration-form.php';
    } elseif ($requestMethod === 'POST') {
        require_once './registration/handle-registration-form.php';
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
} elseif ($requestUri === '/login') {
    if ($requestMethod === 'GET') {
        require_once './login/login-form.php';
    } elseif ($requestMethod === 'POST') {
        require_once './login/handle-login.php';
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
}  elseif ($requestUri === '/catalog') {
    if ($requestMethod === 'GET') {
        require_once './catalog/catalog.php';
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
}   elseif ($requestUri === '/profile') {
    if ($requestMethod === 'GET') {
        require_once './profile/profile-page.php';
    } elseif ($requestMethod === 'POST') {
        require_once './profile/profile.php';
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
}  elseif ($requestUri === '/editProfile') {
    if ($requestMethod === 'GET') {
        require_once './editProfile/edit-profile-form.php';
    } elseif ($requestMethod === 'POST') {
        require_once './editProfile/handle-edit-profile.php';
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
} elseif ($requestUri === '/add-product') {
    if ($requestMethod === 'GET') {
        require_once './addProduct/add-product-form.php';
    } elseif ($requestMethod === 'POST') {
        require_once './addProduct/handle-add-product.php';
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
} elseif ($requestUri === '/cart') {
    if ($requestMethod === 'GET') {
        require_once './cart/cart.php';
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
} else {
    http_response_code(404);
   require_once  './404.php';
}