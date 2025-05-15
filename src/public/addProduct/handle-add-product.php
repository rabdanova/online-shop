<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: ./login.php');
    exit;
}

function Validate(array $data):array{
    $errors = [];

    $errorProductId = ValidateProductId($data);
    if (!empty($errorProductId)) {
        $errors['product_id'] = $errorProductId;
    }

    $errorAmount = ValidateAmount($data);
    if (!empty($errorAmount)) {
        $errors['amount'] = $errorAmount;
    }

    return $errors;
}
function ValidateProductId($data):string|null
{
    if (isset($data['product_id'])) {
        $productId = $data['product_id'];

        if (is_numeric($productId)) {
            $pdo = new PDO('pgsql:host=postgres; port = 5432;dbname=mydb', 'user', 'pass');
            $stmt = $pdo->prepare("SELECT id from products where id = :productId");
            $stmt->execute(['productId' => $productId]);
            $result = $stmt->fetch();

            if ($result === false) {
                return "Продукта с таким id не существует";
            } else {
                return NUll;
            }
        } else {
            return "Неправильный формат id";
        }
    } else {
        return 'Введите id продукта';
    }
}
 function ValidateAmount($data):string|null
 {
    if (isset($data['amount'])) {
        $amount = $data['amount'];

        if (!is_numeric($amount)) {
            return 'Введите число';
        } else {
            if ($amount > 0) {
               return null;
            } else{
                return 'Введите положительное число';
            }
        }
    } else {
        return 'Введите количество желаемого товара';
    }
}

$errors = Validate($_POST);

if (empty($errors)) {
    $amount = $_POST["amount"];
    $productId = $_POST["product_id"];
    $userId= $_SESSION["user_id"];

    $pdo = new PDO('pgsql:host=postgres; port = 5432;dbname=mydb', 'user', 'pass');
    $res = $pdo->prepare("select amount from user_products where user_id=:user_id and product_id=:product_id");
    $res->execute(['user_id' => $userId, 'product_id' => $productId]);
    $result = $res->fetch();

    if (empty($result)) {
        $res = $pdo->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES (:user_id, :product_id, :amount)");
        $res->execute(['user_id' => $userId, 'product_id' => $productId, 'amount' => $amount]);
    } else {
        $newAmount = $result["amount"] + $amount;
        $res = $pdo->prepare("Update user_products set amount = :amount where user_id = :user_id and product_id = :product_id");
        $res->execute(['user_id' => $userId, 'product_id' => $productId, 'amount' => $newAmount]);
    }

    header("Location: catalog");

} else {
    require_once './addProduct/add-product.php';
}