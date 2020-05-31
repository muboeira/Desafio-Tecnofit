<?php

require_once '../../vendor/autoload.php';
$action = $_POST['action'];
$treinoId = (int)$_POST['treinoId'];

$treino = new \Models\Treino();

$results = [];
if ($treinoId > 0) {

    switch ($action) {
        case 'updateAtivadoById':
            $ativado = filter_var($_POST['ativado'], FILTER_VALIDATE_BOOLEAN);
            if(is_bool($ativado)) {
                $results = $treino->updateAtivadoById($treinoId,$ativado);
            }
            break;
        default:
            return false;
            break;
    }
}

echo json_encode($results);