<?php
require_once '../vendor/autoload.php';

session_start();

if (empty($_SESSION['user_id'])) {
    header('Location: /../login.php');
}

$treinoHelper = new Models\Treino();

if($treinoHelper->getTreinoAtivoByUsuarioId($_SESSION['user_id'])){
    $exercicioHelper = new Models\Exercicio();

    $treinoExercicios = [];

    $treinoExercicios = $exercicioHelper->fetchByTreinoId($treinoHelper->getId());

    if(count($treinoExercicios) === 0) {
        $exercicioCard = <<<HTML
    <p>O treino ativo não posssui exercícios</p>
HTML;
    }

    foreach($treinoExercicios as $key => $treinoExercicio) {
        $exercicio = $exercicioHelper->fetchById($treinoExercicio['id']);

        $style = $key > 0 ? 'display:none' : '';

        $exercicioCard .= <<<HTML
    <div id="${exercicio['id']}" class="card mt-3" style="width: 18rem;${style}">
        <div class="card-body">
            <h5 class="card-title">${exercicio['nome']}</h5>
            <p class="card-text">${treinoExercicio['sessoes']} repeticões</p>
            <a href="#" class="btn btn-primary" onclick="skipExercicio(${exercicio['id']}, this)">Pular</a>
            <a href="#" class="btn btn-danger" onclick="finalizeExercicio(${exercicio['id']}, this)">Terminar</a>
        </div>
    </div>
HTML;
    }
}


?>
<?php include_once('views/header.php') ?>
<main id="main" role="main" class="container mt-2">
    <?php
        echo $exercicioCard;
    ?>
</main>

<?php include_once('views/footer.php') ?>

<script>
    $(document).ready(function() {
        $('#main > div').slice(1).hide();
    });

    const main = $('#main');

    function skipExercicio(id, element) {
        const cardClicked = $(element).closest('.card');
        const nearestCard = $(cardClicked).siblings().first();

        if(nearestCard.length === 0){
            alert('Esse é o último exercício');
            return false;
        }

        const card = $(cardClicked).hide().detach();

        main.append(card);

        nearestCard.show();

    }

    function finalizeExercicio(id, element) {
        const cardClicked = $(element).closest('.card');
        const nearestCard = $(cardClicked).siblings().first();

        if(nearestCard.length === 0){
            main.append('<p>Terminou o treino<p>');
        }

        nearestCard.show();

        $(cardClicked).remove();
    }
</script>