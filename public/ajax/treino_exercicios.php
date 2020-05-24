<?php
require_once '../../vendor/autoload.php';
$action = $_POST['action'];
$treinoId= $_POST['treinoId'];

$exercicio = new \Models\Exercicio();

$results = [];
if((int)$treinoId > 0) {
    if($action === 'getExerciciosByTreino') {
        $results = $exercicio->fetchByTreinoId($treinoId);
    }

    if($action === 'getExerciciosNotInTreino') {
        $results = $exercicio->fetchAllNotInTreino($treinoId);
    }
}

echo json_encode($results);