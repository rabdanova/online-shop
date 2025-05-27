
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

<?php if (!empty($userOrderProducts)): ?>
    <?php foreach ($userOrderProducts as $orderData): ?>
        <h2>Заказ №<?= htmlspecialchars($orderData['order']['id']) ?> от <?= htmlspecialchars($orderData['order']['created_at'] ?? '') ?></h2>
        <p><strong>Адрес:</strong> <?= htmlspecialchars($orderData['order']['address'] ?? '') ?></p>
        <p><strong>Комментарий:</strong> <?= htmlspecialchars($orderData['order']['comment'] ?? '') ?></p>

        <?php if (!empty($orderData['products'])): ?>
            <table>
                <thead>
                <tr>
                    <th>Название товара</th>
                    <th>Цена за единицу</th>
                    <th>Количество</th>
                    <th>Сумма</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($orderData['products'] as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= number_format($product['price'], 2, ',', ' ') ?> руб.</td>
                        <td><?= (int)$product['amount'] ?></td>
                        <td><?= number_format($product['sum'], 2, ',', ' ') ?> руб.</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Итого:</strong></td>
                    <td><strong><?= number_format($orderData['totalSum'], 2, ',', ' ') ?> руб.</strong></td>
                </tr>
                </tfoot>
            </table>
        <?php else: ?>
            <p>В этом заказе нет товаров.</p>
        <?php endif; ?>

    <?php endforeach; ?>
<?php else: ?>
    <p>У вас пока нет заказов.</p>
<?php endif; ?>

</body>
</html>