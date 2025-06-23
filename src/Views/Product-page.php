<a href="./profile">Профиль</a>
<a href="./cart">Корзина</a>
<a href="./my-orders">Мои заказы</a>
<a href="./logout" >Выйти</a>
<div class="container">
    <h3>Каталог</h3>
    <div class="catalog">
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
        <?php if ($averageRating > 0): ?>
            <div style="margin-bottom: 20px;">
                <strong>Средний рейтинг:</strong>
                <?php
                // Выводим звезды для среднего рейтинга (округлённого до целого)
                $roundedAvg = round($averageRating);
                for ($i = 1; $i <= 5; $i++) {
                    echo $i <= $roundedAvg ? '⭐' : '☆';
                }
                ?>
                <span style="margin-left: 8px; font-weight: bold;"><?php echo $averageRating; ?></span>
            </div>
        <?php else: ?>
            <p>Пока нет оценок.</p>
        <?php endif; ?>
        <div class="card">
            <?php foreach ($reviews as $review): ?>
            <div class="card-body">
                <p class="card-text text-muted"><?php echo $review->getComment();?></p>
                <p>
                    <?php
                    // Выводим звезды
                    $rating = $review->getRating();
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $rating) {
                            echo '⭐'; // заполненная звезда
                        } else {
                            echo '☆'; // пустая звезда
                        }
                    }
                    ?>
                    <span style="margin-left: 8px; font-weight: bold;"><?php echo $rating; ?></span>
                </p>
            </div>
            <?php endforeach; ?>
        </div>
                <form action='/Review' method="POST">
                    <div class="field">
                        <input type="hidden" name= "product_id" value = "<?php echo $product->getId(); ?> "id = "product_id" required>
                    </div>
                </form>
            </div>
        </div>
    </div>
<form action="/addReview" method="POST" style="max-width: 400px; margin: 20px auto; font-family: Arial, sans-serif;">
    <h2>Оставить отзыв</h2>

    <label for="rating">Рейтинг:</label><br>
    <select id="rating" name="rating" required>
        <option value="" disabled selected>Выберите рейтинг</option>
        <option value="5">5 - Отлично</option>
        <option value="4">4 - Хорошо</option>
        <option value="3">3 - Средне</option>
        <option value="2">2 - Плохо</option>
        <option value="1">1 - Очень плохо</option>
    </select>

    <br><br>

    <label for="comment">Комментарий:</label><br>
    <textarea id="comment" name="comment" rows="5" placeholder="Напишите ваш отзыв здесь..." required style="width: 100%;"></textarea>
    <div class="field">
        <input type="hidden" name= "product_id" value = "<?php echo $product->getId(); ?> "id = "product_id" required>
    </div>
    <br><br>

    <button type="submit" style="padding: 10px 20px; font-size: 16px;">Отправить отзыв</button>
</form>


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
        font-size: 16px;
    }

    .card-footer{
        font-weight: bold;
        font-size: 18px;
        background-color: white;
    }
</style>
