<?php
require_once '../../vendor/autoload.php';
$action = $_POST['action'];


$user = new \Models\Usuario();

$results = [];

switch ($action) {
    case 'create':
        break;
    case 'read':
        $results = $user->fetchAll();
        break;
    case 'update':
        if(isset($_POST['id'])){
            $id = $_POST['id'];
            $results = $user->fetchById($id);
        }
        break;
    case 'delete':
        if(isset($_POST['id'])){
            $id = $_POST['id'];
            $results = $user->delete($id);
        }
        break;
}

echo json_encode($results);