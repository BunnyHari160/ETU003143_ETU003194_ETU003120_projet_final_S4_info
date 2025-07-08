<?php 

require_once __DIR__ . '/../models/Fond.php';
require_once __DIR__ . '/../helpers/Utils.php';

class FondController {
    public static function getAll()
    {
        $fonds = Fond::getAll();
        Flight::json($fonds);
    }

    public static function getById($id)
    {
        $fond = Fond::getById($id);
        Flight::json($fond);
    }

    public static function create()
    {
        $data = Flight::request()->data;
        $id = Fond::create($data);
        $dateFormatted = Utils::formatDate('2025-01-01');
        Flight::json(['message'=>'Fond ajoute avec succer','id'=>$id]);
    }

    public static function update($id)
    {
        $data = Flight::request()->data;
        Fond::update($id, $data);
        Flight::json(['message'=>'Fond Modifie']);
    }

    public static function delete($id)
    {
        Fond::delete($id);
        Flight::json(['message'=>'Fond Supprime']);
    }
}

?>