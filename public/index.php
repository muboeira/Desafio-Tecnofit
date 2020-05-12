<?php
require_once '../vendor/autoload.php';

session_start();

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
}

$usuario = new \Models\Usuario();

$usuario->fetchById($_SESSION['user_id']);


include_once('views/header.php')

?>
<main role="main" class="container">

    Olá

</main>


<?php include_once('views/footer.php') ?>

<script>
</script>