<?php

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];
print_r($requestUri,$requestMethod);
if ($requestUri === '/registration') {
    require_once './classes/User.php';
    $user = new User();
    if ($requestMethod === 'GET') {
        $user->getRegistrate();
    } elseif ($requestMethod === 'POST') {
//        require_once './classes/User.php';
        $user->registrate();
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
} elseif ($requestUri === '/login') {
    require_once './classes/User.php';
    $user = new User();
    if ($requestMethod === 'GET') {
        $user->getLogin();
    } elseif ($requestMethod === 'POST') {
       $user->login();
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
}  elseif ($requestUri === '/catalog') {
    require_once './classes/Product.php';
    $product = new Product();
    if ($requestMethod === 'GET') {
        $product->catalog();
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
}   elseif ($requestUri === '/profile') {
    require_once './classes/User.php';
    $user = new User();
    if ($requestMethod === 'GET') {
        $user->profile();
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
}  elseif ($requestUri === '/editProfile') {
    require_once './classes/User.php';
    $user = new User();
    if ($requestMethod === 'GET') {
        $user->getEditForm();
    } elseif ($requestMethod === 'POST') {
        $user->editProfile();
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
} elseif ($requestUri === '/add-product') {
    require_once './classes/Product.php';
    $product = new Product();
    if ($requestMethod === 'GET') {
        $product->getAddProductForm();
    } elseif ($requestMethod === 'POST') {
        $product->addProduct();
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
} elseif ($requestUri === '/cart') {
    require_once './classes/Product.php';
    $product = new Product();
    if ($requestMethod === 'GET') {
        $product->cart();
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
} else {
    http_response_code(404);
   require_once  './404.php';
}