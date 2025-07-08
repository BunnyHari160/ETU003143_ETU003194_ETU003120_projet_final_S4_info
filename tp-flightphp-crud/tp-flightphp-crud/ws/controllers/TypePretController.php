<?php

require_once __DIR__ . '/../models/TypePret.php';
require_once __DIR__ . '/../helpers/Utils.php';


class TypePretController {
    public static function getAll() {
        $typepret = TypePret::getAll();
        Flight::json($typepret);
    }

    public static function getById($id) {
        $typepret = TypePret::getByID($id);
        Flight::json($typepret);    
    }

    public static function create() {
        $data = Flight::request()->data;
        $id = TypePret::create($data);
        $dateFormatted = Utils::formatDate('2025-01-01');
        Flight::json(['message'=>'TypePret ajoute avec succer','id'=>$id]);
    }

public static function update($id) {
    $data = Flight::request()->data;
    TypePret::update($id, $data);
    Flight::json(['message'=>'TypePret Modifie']);
}

public static function delete($id) {
    TypePret::delete($id);
    Flight::json(['message'=>'TypePret Supprime']);
}
}
