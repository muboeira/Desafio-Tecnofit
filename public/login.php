<?php

require '../vendor/autoload.php';

session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: /index.php');
}

if (!empty($_POST['login']) && !empty($_POST['senha'])) {
    $usuario = new Models\Usuario();

    $result = $usuario->logar($_POST['login'], $_POST['senha']);
    if ($result) {
        $_SESSION['user_id'] = $usuario->getId();
        $_SESSION['user_role'] = $usuario->getRole();
        header('Location: /index.php');
    } else {
        $message = 'Login e/ou senha incorretos.';
    }
}
?>


<?php require 'views/header.php' ?>
<div class="container">
    <div class="login-container mx-auto">

        <h1>Login</h1>
        <?php if(!empty($message)): ?>
            <p class="alert-danger"> <?= $message ?></p>
        <?php endif; ?>
        <span></span>
        <form action="login.php" method="POST" class="clearfix">
            <div class="form-group">
                <label for="login">Login</label>
                <input class="form-control" id="login" name="login" type="text" >
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input class="form-control" id="senha" name="senha" type="password">
            </div>
            <button type="submit" class="btn  btn-success float-right">Submit</button>
        </form>

    </div>

</div>
<?php include_once('views/header.php') ?>