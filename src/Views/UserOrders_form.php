
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

    <?php foreach ($newUserOrders as $newUserOrder): ?>
        <h2>Заказ №<?php echo $newUserOrder->getId() ?></h2>
        <p>Контактное имя:<?php echo $newUserOrder->getName()?></p>
        <p>Контактный номер телефона:<?php echo $newUserOrder->getPhoneNumber()?></p>
        <p>Адрес: <?php echo $newUserOrder->getAddress()?> </p>
        <p>Комментарий:<?php echo $newUserOrder->getComment()?></p>

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
                <?php foreach ($newUserOrder->getProducts() as $newOrderProduct): ?>
                    <tr>
                        <td><?php echo $newOrderProduct->getName() ?></td>
                        <td><img class="card-img-top" src="<?php echo $newOrderProduct->getImageUrl();?>" alt="Card image" height="160" width="160"></td>
                        <td><?php echo $newOrderProduct->getPrice() ?></td>
                        <td><?php echo $newOrderProduct->getAmount() ?></td>
                        <td><?php echo $newOrderProduct->getTotalSum() ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Итого:</strong></td>
                    <td><?php echo $newUserOrder->getTotal()?> руб.</td>
                </tr>
                </tfoot>
            </table>
    <?php endforeach; ?>

</body>
</html>