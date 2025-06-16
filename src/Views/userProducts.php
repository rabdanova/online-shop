<a href="./profile">Профиль</a>
<a href="./catalog">Каталог</a>
<a href="./logout" >Выйти</a>
<div class="container">
    <h3>Корзина</h3>
    <div class="card-deck">
        <?php foreach ($cart as $products): ?>
<!--        --><?php //foreach ($products as $product): ?>
            <div class="card text-center">
                <a href="#">
                    </div>
                    <img class="card-img-top" src="<?php echo $products->getImageUrl();?>" alt="Card image" height="480" width="480">
                    <div class="card-body">
                        <p class="card-text text-muted"><?php echo $products->getName();?></p>
                        <a href="#"><h5 class="card-title"><?php echo $products->getDescription();?></h5></a>
                        <div class="card-footer">
                            <?php echo $products->getPrice();?>
                        </div>
                    <div> Количество: <?php echo $products->getAmount(); ?></div>
                    </div>
                </a>
            </div>
<!--            --><?php //endforeach; ?>
        <?php endforeach; ?>
    <a href="./create-order" >Заказать</a>
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