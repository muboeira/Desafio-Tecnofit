<?php
require_once '../../vendor/autoload.php';
$action = $_POST['action'];
$treinoId= $_POST['treinoId'];

$exercicio = new \Models\Exercicio();

$results = [];
if((int)$treinoId > 0) {

    switch ($action) {
        case 'getExerciciosByTreino':
            $results = $exercicio->fetchByTreinoId($treinoId);
            break;
        case 'getExerciciosNotInTreino':
                $results = $exercicio->fetchAllNotInTreino($treinoId);
            break;
        case 'create':
            $exercicioId= (int)$_POST['exercicioId'];
            $sessoes= (int)$_POST['sessoes'];
            if($exercicioId && $sessoes) {
                $results = $exercicio->addExercicioToTreino($treinoId, $exercicioId, $sessoes);
            }
            break;
        case 'delete':
            $exercicioId= (int)$_POST['exercicioId'];
            if($exercicioId) {
                $results = $exercicio->deleteExercicioFromTreino($treinoId, $exercicioId);
            }
            break;
        default:
            return false;
            break;
    }
}

echo json_encode($results);