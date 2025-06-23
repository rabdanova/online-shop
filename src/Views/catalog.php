<a href="./profile">Профиль</a>
<a href="./cart">Корзина</a>
<a href="./my-orders">Мои заказы</a>
<a href="./logout" >Выйти</a>
<div class="container">
    <h3>Каталог</h3>
    <div class="catalog">
        <?php foreach ($products as $product): ?>
        <div class="card">
                <img class="card-img-top" src="<?php echo $product->getImageUrl();?>" alt="Card image" height="480" width="480">
                <div class="card-body">
                    <p class="card-text text-muted"><?php echo $product->getName();?></p>
                    <a href="#"><h5 class="card-title"><?php echo $product->getDescription();?></h5></a>
                    <div class="card-price">
                        <?php echo $product->getPrice() . 'руб';?>
                    </div>
                </div>
        </div>
            <!-- Created By CodingNepal -->
            <html lang="en" dir="ltr">
            <head>
                <meta charset="utf-8">
                <title>test html</title>
                <link rel="stylesheet" href="style.css">
            </head>
            <body>
            <div class="quantity-controls">
                <form action='add-product' method="POST">
                    <div class="field">
                        <input type="hidden" name= "product_id" value = "<?php echo $product->getId(); ?> "id = "product_id" required>
                    </div>
                    <div class="field">
                        <input type="submit" value="+">
                    </div>
                </form>
                <form action='decrease-product' method="POST">
                    <div class="field">
                        <input type="hidden" name= "product_id" value = "<?php echo $product->getId(); ?> "id = "product_id" required>
                    </div>
                    <div class="field">
                        <input type="submit" value="-">
                    </div>
                </form>
                <form action='/product-page' method="POST">
                    <div class="field">
                        <input type="hidden" name= "product_id" value = "<?php echo $product->getId(); ?> "id = "product_id" required>
                    </div>
                    <div class="field">
                        <input type="submit" value="Подробнее">
                    </div>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
    </div>


</div>

<style>
    body {
        font-family: 'Poppins', sans-serif;
    }

    a {
        text-decoration: none;
    }

    a:hover {
        text-decoration: none;
    }

    h3 {
        line-height: 3em;
    }

    .card {
        max-width: 16rem;
    }

    .card:hover {
        box-shadow: 1px 2px 10px lightgray;
        transition: 0.2s;
    }
    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 10px; /* расстояние между кнопками */
    }
    .quantity-controls form {
        margin: 0; /* убираем отступы, если есть */
    }
    .card-header {
        font-size: 13px;
        color: gray;
        background-color: white;
    }

    .text-muted {
        font-size: 11px;
    }

    .card-footer{
        font-weight: bold;
        font-size: 18px;
        background-color: white;
    }
</style>