<?php
require_once '../../vendor/autoload.php';

if(!isset($_POST['action']) || !isset($_POST['model'])){
    return false;
}

$action = $_POST['action'];

$model = 'Models\\'. $_POST['model'];

$modelHelper = new $model();

$results = false;

switch ($action) {
    case 'read':
        $results = $modelHelper->fetchAll();
        break;
    case 'update':
        if(isset($_POST['id'])){
            $id = $_POST['id'];
            $results = $modelHelper->fetchById($id);
        }
        break;
    case 'hasRelations':
        if(isset($_POST['id'])) {
            $id = $_POST['id'];
            $results = $modelHelper->hasRelations($id);
        }
        break;
    case 'delete':
        if(isset($_POST['id'])){
            $id = $_POST['id'];
            $results = $modelHelper->delete($id);
        }
        break;
    default:
        return false;
        break;
}
echo json_encode($results, JSON_THROW_ON_ERROR, 512);
