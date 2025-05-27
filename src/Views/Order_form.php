
<!DOCTYPE html>
<!-- Created By CodingNepal -->
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>test html</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrapper">
    <div class="title">
        Register
    </div>
    <?php if (!empty($userProductsForOrder) && isset($totalSum)): ?>
        <div style="padding: 10px 30px;">
            <h3>Ваш заказ</h3>
            <table style="width:100%; border-collapse: collapse;">
                <thead>
                <tr style="background: #96c35a;">
                    <th style="padding: 8px; border: 1px solid #ccc;">Товар</th>
                    <th style="padding: 8px; border: 1px solid #ccc;">Цена за 1 шт</th>
                    <th style="padding: 8px; border: 1px solid #ccc;">Количество</th>
                    <th style="padding: 8px; border: 1px solid #ccc;">Сумма</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($userProductsForOrder as $item): ?>
                    <tr>
                        <td style="padding: 8px; border: 1px solid #ccc;"><?= htmlspecialchars($item['name']) ?></td>
                        <td style="padding: 8px; border: 1px solid #ccc;"><?= number_format($item['price'], 2, ',', ' ') ?> руб.</td>
                        <td style="padding: 8px; border: 1px solid #ccc;"><?= (int)$item['amount'] ?></td>
                        <td style="padding: 8px; border: 1px solid #ccc;"><?= number_format($item['sum'], 2, ',', ' ') ?> руб.</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr style="background: #96c35a;">
                    <td colspan="3" style="text-align: right; padding: 8px; border: 1px solid #ccc;"><strong>Итого:</strong></td>
                    <td style="padding: 8px; border: 1px solid #ccc;"><strong><?= number_format($totalSum, 2, ',', ' ') ?> руб.</strong></td>
                </tr>
                </tfoot>
            </table>
        </div>
    <?php endif; ?>
    <form action='create-order' method="POST">
        <div class="field">
            <input type="text" name= "name" id = "name" required>
            <label>Name</label>
            <?php if (isset($errors['name'])): ?>
            <?php echo $errors['name'] ?>
            <?php  endif;?>
        </div>
        <div class="field">
            <input type="text"  name="phone_number" id = "phone_number" required>
            <label>Pnone number</label>
            <?php if (isset($errors['phone_number'])): ?>
            <?php echo $errors['phone_number'] ?>
            <?php  endif;?>
        </div>
        <div class="field">
            <input type="address"  name="address" id = "address" required>
            <label>Address</label>
            <?php if (isset($errors['address'])): ?>
             <?php echo $errors['address'] ?>
            <?php  endif;?>
        </div>
        <div class="field">
            <input type="comment"  name="comment" id = "comment" required>
            <label>Comment</label>
            <?php if (isset($errors['comment'])): ?>
            <?php echo $errors['comment'] ?>
            <?php  endif;?>
        </div>
        <div class="field">
            <input type="submit" value="Order">
            <a href="./cart" >Корзина</a>
        </div>
    </form>
</div>
</body>
</html>

<style>
    @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');
    *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }
    html,body{
        display: grid;
        height: 100%;
        width: 100%;
        place-items: center;
        background: #f2f2f2;
        /* background: linear-gradient(-135deg, #c850c0, #4158d0); */
    }
    ::selection{
        background: #4158d0;
        color: #fff;
    }
    .wrapper{
        width: 380px;
        background: #fff;
        border-radius: 15px;
        box-shadow: 0px 15px 20px rgba(0,0,0,0.1);
    }
    .wrapper .title{
        font-size: 35px;
        font-weight: 600;
        text-align: center;
        line-height: 100px;
        color: #fff;
        user-select: none;
        border-radius: 15px 15px 0 0;
        background: linear-gradient(-135deg, #FF7373, #d2e089);
    }
    .wrapper form{
        padding: 10px 30px 50px 30px;
    }
    .wrapper form .field{
        height: 50px;
        width: 100%;
        margin-top: 20px;
        position: relative;
    }
    .wrapper form .field input{
        height: 100%;
        width: 100%;
        outline: none;
        font-size: 17px;
        padding-left: 20px;
        border: 1px solid lightgrey;
        border-radius: 25px;
        transition: all 0.3s ease;
    }
    .wrapper form .field input:focus,
    form .field input:valid{
        border-color: #4158d0;
    }
    .wrapper form .field label{
        position: absolute;
        top: 50%;
        left: 20px;
        color: #999999;
        font-weight: 400;
        font-size: 17px;
        pointer-events: none;
        transform: translateY(-50%);
        transition: all 0.3s ease;
    }
    form .field input:focus ~ label,
    form .field input:valid ~ label{
        top: 0%;
        font-size: 16px;
        color: #4158d0;
        background: #fff;
        transform: translateY(-50%);
    }
    form .content{
        display: flex;
        width: 100%;
        height: 50px;
        font-size: 16px;
        align-items: center;
        justify-content: space-around;
    }
    form .content .checkbox{
        display: flex;
        align-items: center;
        justify-content: center;
    }
    form .content input{
        width: 15px;
        height: 15px;
        background: red;
    }
    form .content label{
        color: #262626;
        user-select: none;
        padding-left: 5px;
    }
    form .content .pass-link{
        color: #4158d0;
    }
    form .field input[type="submit"]{
        color: #fff;
        border: none;
        padding-left: 0;
        margin-top: -10px;
        font-size: 20px;
        font-weight: 500;
        cursor: pointer;
        background: linear-gradient(-135deg, #d2e089, #FF7373);
        transition: all 0.3s ease;
    }
    form .field input[type="submit"]:active{
        transform: scale(0.95);
    }
    form .signup-link{
        color: #262626;
        margin-top: 20px;
        text-align: center;
    }
    form .pass-link a,
    form .signup-link a{
        color: #4158d0;
        text-decoration: none;
    }
    form .pass-link a:hover,
    form .signup-link a:hover{
        text-decoration: underline;
    }
</style>