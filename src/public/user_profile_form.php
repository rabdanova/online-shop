
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.0/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.0/js/bootstrap.min.js"></script>

<div class="container">
    <div id="main">


        <div class="row" id="real-estates-detail">
            <div class="col-lg-4 col-md-4 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <header class="panel-title">
                            <div class="text-center">
                                <strong>Пользователь сайта</strong>
                            </div>
                        </header>
                    </div>
                    <div class="panel-body">
                        <div class="text-center" id="author">
                            <img src="https://cs13.pikabu.ru/avatars/1008/x1008224-564930408.png">
                            <h3>Иван Иванович</h3>
                            <small class="label label-warning">Российская Федерация</small>
                            <p>Расскажите о себе</p>
                            <p class="sosmed-author">
                                <a href="#"><i class="fa fa-facebook" title="Facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter" title="Twitter"></i></a>
                                <a href="#"><i class="fa fa-google-plus" title="Google Plus"></i></a>
                                <a href="#"><i class="fa fa-linkedin" title="Linkedin"></i></a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-xs-12">
                <div class="panel">
                    <div class="panel-body">
                        <ul id="myTab" class="nav nav-pills">
                            <li class="active"><a href="#detail" data-toggle="tab">О пользователе</a></li>
                            <li class=""><a href="#contact" data-toggle="tab">Личные данные</a></li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <hr>
                            <div class="tab-pane fade active in" id="detail">
                                <h4>История профиля</h4>
                                <table class="table table-th-block">
                                    <tbody>
                                    <tr><td class="active">Зарегистрирован:</td><td>12-04-2025</td></tr>
                                    <tr><td class="active">Последняя активность:</td><td>25-04-2025 / 09:11</td></tr>
                                    <tr><td class="active">Страна:</td><td>Россия</td></tr>
                                    <tr><td class="active">Город:</td><td>Москва</td></tr>
                                    <tr><td class="active">Пол:</td><td>Мужской</td></tr>
                                    <tr><td class="active">Полных лет:</td><td>43</td></tr>
                                    <tr><td class="active">Семейное положение:</td><td>Не женат</td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="contact">
                                <p></p>
                                <form action="profile" method="post">
                                    <div class="form-group">
                                        <label>Ваше имя</label>
                                        <input type="text" class="form-control rounded" placeholder="Укажите Ваше Имя" name= "username" id = "username">
                                        <?php if (isset($errors['username'])): ?>
                                            <?php echo $errors['username'] ?>
                                        <?php  endif;?>
                                    </div>
                                    <div class="form-group">
                                        <label>E-mail адрес</label>
                                        <input type="email" class="form-control rounded" placeholder="Ваш Е-майл" name= "email" id = "email">
                                        <?php if (isset($errors['email'])): ?>
                                            <?php echo $errors['email'] ?>
                                        <?php  endif;?>
                                    </div>
                                    <div class="form-group">
                                        <label>Старый пароль</label>
                                        <input type="password" class="form-control rounded" name= "old_password" id = "old_password">
                                        <?php if (isset($errors['old_password'])): ?>
                                            <?php echo $errors['old_password'] ?>
                                        <?php  endif;?>
                                    </div>
                                    <div class="form-group">
                                        <label>Новый пароль</label>
                                        <input type="password" class="form-control rounded" name= "new_password" id = "new_password">
                                        <?php if (isset($errors['new_password'])): ?>
                                            <?php echo $errors['new_password'] ?>
                                        <?php  endif;?>
                                    </div>
                                    <div class="form-group">
                                        <label>Повторите новый пароль</label>
                                        <input type="password" class="form-control rounded" name= "repeat_password" id = "repeat_password">
                                        <?php if (isset($errors['repeat_password'])): ?>
                                            <?php echo $errors['repeat_password'] ?>
                                        <?php  endif;?>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success" data-original-title="" title="">Сохранить изменения</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /.main -->
</div><!-- /.container -->

<style>
    body{background:url(https://bootstraptema.ru/images/bg/bg-1.png)}

    #main {
        background-color: #f2f2f2;
        padding: 20px;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        -ms-border-radius: 4px;
        -o-border-radius: 4px;
        border-radius: 4px;
        border-bottom: 4px solid #ddd;
    }
    #real-estates-detail #author img {
        -webkit-border-radius: 100%;
        -moz-border-radius: 100%;
        -ms-border-radius: 100%;
        -o-border-radius: 100%;
        border-radius: 100%;
        border: 5px solid #ecf0f1;
        margin-bottom: 10px;
    }
    #real-estates-detail .sosmed-author i.fa {
        width: 30px;
        height: 30px;
        border: 2px solid #bdc3c7;
        color: #bdc3c7;
        padding-top: 6px;
        margin-top: 10px;
    }
    .panel-default .panel-heading {
        background-color: #fff;
    }
    #real-estates-detail .slides li img {
        height: 450px;
    }
</style>