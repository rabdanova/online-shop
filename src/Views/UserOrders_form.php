
<!DOCTYPE html>
<html lang="ru">
<head>
    <a href="./profile">Профиль</a>
    <a href="./cart">Корзина</a>
    <a href="./logout" >Выйти</a>
    <meta charset="UTF-8">
    <title>Мои заказы</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
        h2 {
            margin-top: 40px;
        }
    </style>
</head>
<body>
<h1>Мои заказы</h1>

    <?php foreach ($userOrders as $userOrder): ?>
        <h2>Заказ №<?php echo $userOrder->getId() ?></h2>
        <p>Контактное имя:<?php echo $userOrder->getName()?></p>
        <p>Контактный номер телефона:<?php echo $userOrder->getPhoneNumber()?></p>
        <p>Адрес: <?php echo $userOrder->getAddress()?> </p>
        <p>Комментарий:<?php echo $userOrder->getComment()?></p>

            <table>
                <thead>
                <tr>
                    <th>Название товара</th>
                    <th>Фото товара</th>
                    <th>Цена за единицу</th>
                    <th>Количество</th>
                    <th>Сумма</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($userOrder->getProducts() as $orderProduct): ?>
                    <tr>
                        <td><?php echo $orderProduct->getName() ?></td>
                        <td><img class="card-img-top" src="<?php echo $orderProduct->getImageUrl();?>" alt="Card image" height="160" width="160"></td>
                        <td><?php echo $orderProduct->getPrice() ?></td>
                        <td><?php echo $orderProduct->getAmount() ?></td>
                        <td><?php echo $orderProduct->getTotalSum() ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Итого:</strong></td>
                    <td><?php echo $userOrder->getTotal()?> руб.</td>
                </tr>
                </tfoot>
            </table>
    <?php endforeach; ?>

</body>
</html>