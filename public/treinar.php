<?php
require_once '../vendor/autoload.php';

session_start();

if (empty($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /../login.php');
}

$treinoHelper = new Models\Treino();

if($treinoHelper->getTreinoAtivoByUsuarioId($_SESSION['user_id'])){
    $exercicioHelper = new Models\Exercicio();

    $exercicios = $exercicioHelper->fetchByTreinoId($treinoHelper->getId());

    var_dump($exercicios);
    die();
}


?>
<?php include_once('views/header.php') ?>
<main role="main" class="container">

</main>

<?php include_once('views/footer.php') ?>

<script>

</script>