<?php
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
        $product_id = $data['product_id'];

        if (is_numeric($product_id)) {
            $pdo = new PDO('pgsql:host=postgres; port = 5432;dbname=mydb', 'user', 'pass');
            $stmt = $pdo->query("SELECT id from products");
//            $stmt->execute(['product_id' => $product_id]);
            $result = $stmt->fetchAll();

            $new_arr = array_column($result, 'id');

            if (in_array($product_id, $new_arr)) {
                return NUll;
            } else {
                return "Продукта с таким id не существует";
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
            return null;
        }
    } else {
        return 'Введите количество желаемого товара';
    }
}

$errors = Validate($_POST);

if (empty($errors)) {
    session_start();
    $amount = $_POST["amount"];
    $product_id = $_POST["product_id"];
    $user_id= $_SESSION["user_id"];

    $pdo = new PDO('pgsql:host=postgres; port = 5432;dbname=mydb', 'user', 'pass');
    $res = $pdo->prepare("select amount from user_products where user_id=:user_id and product_id=:product_id");
    $res->execute(['user_id' => $user_id, 'product_id' => $product_id]);
    $result = $res->fetchAll();

    if (empty($result)) {
        $res = $pdo->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES (:user_id, :product_id, :amount)");
        $res->execute(['user_id' => $user_id, 'product_id' => $product_id, 'amount' => $amount]);
    } else {
        $new_amount = $result[0]["amount"] + $amount;
        $res = $pdo->prepare("Update user_products set amount = :amount where user_id = :user_id and product_id = :product_id");
        $res->execute(['user_id' => $user_id, 'product_id' => $product_id, 'amount' => $new_amount]);
    }

    header("Location: catalog");

} else {
    require_once './add_product_form.php';
}